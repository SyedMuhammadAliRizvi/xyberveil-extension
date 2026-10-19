"""
proxy-agent.py — Self-contained proxy agent for SecureProxy.

Runs two services on a single Python process:
  1.  FastAPI management API   on AGENT_PORT  (default 9191)
  2.  HTTP CONNECT proxy       on PROXY_PORT  (default 10080)
  3.  SOCKS5 proxy             on SOCKS_PORT  (default 10081)

Both proxy servers use the same user database (flat file at USERS_FILE).

Environment variables:
  AGENT_KEY    — shared secret for management API (required)
  USERS_FILE   — path to user store (default /etc/proxy-agent/users.cfg)
  PROXY_PORT   — HTTP CONNECT port   (default 10080)
  SOCKS_PORT   — SOCKS5 port         (default 10081)
  AGENT_PORT   — management API port (default 9191)

Install + run:
  python3 -m venv /opt/proxy-agent/venv
  /opt/proxy-agent/venv/bin/pip install fastapi uvicorn
  AGENT_KEY=secret uvicorn main:app --host 0.0.0.0 --port 9191
"""
 
from __future__ import annotations

import base64
import logging
import os
import re
import socket
import struct
import threading
from pathlib import Path
from typing import Optional

from fastapi import FastAPI, Header, HTTPException
from pydantic import BaseModel

# ── Config ────────────────────────────────────────────────────────────────────

AGENT_KEY  = os.getenv("AGENT_KEY",   "changeme")
USERS_FILE = Path(os.getenv("USERS_FILE", "/etc/proxy-agent/users.cfg"))
PROXY_PORT = int(os.getenv("PROXY_PORT", "10080"))
SOCKS_PORT = int(os.getenv("SOCKS_PORT", "10081"))
AGENT_PORT = int(os.getenv("AGENT_PORT", "9191"))

logging.basicConfig(level=logging.INFO, format="%(asctime)s %(levelname)s %(name)s: %(message)s")
log = logging.getLogger("proxy-agent")

# ── User store ────────────────────────────────────────────────────────────────

_users_lock = threading.Lock()
_users: dict[str, str] = {}   # {username: password}


def _load_users() -> None:
    USERS_FILE.parent.mkdir(parents=True, exist_ok=True)
    if not USERS_FILE.exists():
        USERS_FILE.write_text("")
        return
    with _users_lock:
        _users.clear()
        for line in USERS_FILE.read_text().splitlines():
            line = line.strip()
            if ":" in line and not line.startswith("#"):
                u, p = line.split(":", 1)
                _users[u.strip()] = p.strip()


def _save_users() -> None:
    USERS_FILE.parent.mkdir(parents=True, exist_ok=True)
    with _users_lock:
        lines = [f"{u}:{p}" for u, p in sorted(_users.items())]
    USERS_FILE.write_text("\n".join(lines) + "\n" if lines else "")


def _check_auth(username: str, password: str) -> bool:
    with _users_lock:
        return _users.get(username) == password


# ── HTTP CONNECT proxy ────────────────────────────────────────────────────────

_CONN_LOG: list[dict] = []   # last 1000 connections
_CONN_LOCK = threading.Lock()


def _log_conn(client_ip: str, target: str, username: str) -> None:
    with _CONN_LOCK:
        _CONN_LOG.append({"client_ip": client_ip, "target": target, "user": username})
        if len(_CONN_LOG) > 1000:
            _CONN_LOG.pop(0)


def _relay(src: socket.socket, dst: socket.socket) -> None:
    try:
        while True:
            data = src.recv(65536)
            if not data:
                break
            dst.sendall(data)
    except Exception:
        pass
    finally:
        try: src.close()
        except Exception: pass
        try: dst.close()
        except Exception: pass


def _handle_http_connect(conn: socket.socket, addr: tuple) -> None:
    client_ip = addr[0]
    try:
        buf = b""
        while b"\r\n\r\n" not in buf:
            chunk = conn.recv(4096)
            if not chunk:
                return
            buf += chunk
            if len(buf) > 65536:
                return

        header_part = buf.split(b"\r\n\r\n")[0].decode("utf-8", errors="replace")
        lines = header_part.split("\r\n")
        if not lines:
            return

        first_line = lines[0].split()
        if len(first_line) < 2:
            return

        method = first_line[0].upper()
        if method != "CONNECT":
            conn.sendall(b"HTTP/1.1 405 Method Not Allowed\r\n\r\n")
            return

        host_port = first_line[1]
        if ":" in host_port:
            host, port_str = host_port.rsplit(":", 1)
            port = int(port_str)
        else:
            host, port = host_port, 443

        # Parse Proxy-Authorization
        username = None
        for line in lines[1:]:
            if line.lower().startswith("proxy-authorization:"):
                auth_val = line.split(":", 1)[1].strip()
                if auth_val.lower().startswith("basic "):
                    try:
                        decoded = base64.b64decode(auth_val[6:]).decode("utf-8")
                        u, p = decoded.split(":", 1)
                        if _check_auth(u, p):
                            username = u
                    except Exception:
                        pass
                break

        if username is None:
            conn.sendall(
                b"HTTP/1.1 407 Proxy Authentication Required\r\n"
                b'Proxy-Authenticate: Basic realm="SecureProxy"\r\n'
                b"Content-Length: 0\r\n"
                b"Proxy-Connection: close\r\n\r\n"
            )
            return

        # Connect to target
        try:
            remote = socket.create_connection((host, port), timeout=10)
        except Exception as exc:
            conn.sendall(b"HTTP/1.1 502 Bad Gateway\r\n\r\n")
            return

        conn.sendall(b"HTTP/1.1 200 Connection Established\r\n\r\n")
        _log_conn(client_ip, f"{host}:{port}", username)
        log.debug("CONNECT %s:%d ← %s [%s]", host, port, client_ip, username)

        t1 = threading.Thread(target=_relay, args=(conn, remote),   daemon=True)
        t2 = threading.Thread(target=_relay, args=(remote, conn),   daemon=True)
        t1.start(); t2.start()
        t1.join();  t2.join()

    except Exception as exc:
        log.debug("HTTP proxy error %s: %s", client_ip, exc)
    finally:
        try: conn.close()
        except Exception: pass


def _run_http_proxy() -> None:
    srv = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    srv.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    srv.bind(("0.0.0.0", PROXY_PORT))
    srv.listen(512)
    log.info("HTTP CONNECT proxy listening on :%d", PROXY_PORT)
    while True:
        try:
            conn, addr = srv.accept()
            conn.settimeout(300)
            threading.Thread(target=_handle_http_connect, args=(conn, addr), daemon=True).start()
        except Exception as exc:
            log.warning("Accept error: %s", exc)


# ── SOCKS5 proxy ──────────────────────────────────────────────────────────────


def _handle_socks5(conn: socket.socket, addr: tuple) -> None:
    client_ip = addr[0]
    try:
        # Greeting
        header = conn.recv(2)
        if len(header) < 2 or header[0] != 5:
            return
        nmethods = header[1]
        methods = conn.recv(nmethods)

        if 2 in methods:
            conn.sendall(b"\x05\x02")   # request username/password

            # Auth sub-negotiation
            ver = conn.recv(1)
            if ver != b"\x01":
                conn.sendall(b"\x01\x01")
                return
            ulen = struct.unpack("B", conn.recv(1))[0]
            username = conn.recv(ulen).decode("utf-8", errors="replace")
            plen = struct.unpack("B", conn.recv(1))[0]
            password = conn.recv(plen).decode("utf-8", errors="replace")

            if not _check_auth(username, password):
                conn.sendall(b"\x01\x01")   # failure
                return
            conn.sendall(b"\x01\x00")       # success

        elif 0 in methods:
            conn.sendall(b"\x05\x00")   # no auth
            username = "anonymous"
        else:
            conn.sendall(b"\x05\xff")   # no acceptable methods
            return

        # Request
        req = conn.recv(4)
        if len(req) < 4 or req[0] != 5:
            return
        cmd, _, atyp = req[1], req[2], req[3]

        if atyp == 1:       # IPv4
            addr_bytes = conn.recv(4)
            host = socket.inet_ntoa(addr_bytes)
        elif atyp == 3:     # domain
            dlen = struct.unpack("B", conn.recv(1))[0]
            host = conn.recv(dlen).decode("utf-8", errors="replace")
        elif atyp == 4:     # IPv6
            addr_bytes = conn.recv(16)
            host = socket.inet_ntop(socket.AF_INET6, addr_bytes)
        else:
            conn.sendall(b"\x05\x08\x00\x01" + b"\x00"*4 + b"\x00\x00")
            return

        port_bytes = conn.recv(2)
        port = struct.unpack(">H", port_bytes)[0]

        if cmd != 1:    # only CONNECT supported
            conn.sendall(b"\x05\x07\x00\x01" + b"\x00"*4 + b"\x00\x00")
            return

        try:
            remote = socket.create_connection((host, port), timeout=10)
        except Exception:
            conn.sendall(b"\x05\x04\x00\x01" + b"\x00"*4 + b"\x00\x00")
            return

        # Success response
        conn.sendall(b"\x05\x00\x00\x01" + socket.inet_aton("0.0.0.0") + b"\x00\x00")
        _log_conn(client_ip, f"{host}:{port}", username or "anon")
        log.debug("SOCKS5 %s:%d ← %s [%s]", host, port, client_ip, username)

        t1 = threading.Thread(target=_relay, args=(conn, remote),   daemon=True)
        t2 = threading.Thread(target=_relay, args=(remote, conn),   daemon=True)
        t1.start(); t2.start()
        t1.join();  t2.join()

    except Exception as exc:
        log.debug("SOCKS5 error %s: %s", client_ip, exc)
    finally:
        try: conn.close()
        except Exception: pass


def _run_socks5_proxy() -> None:
    srv = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    srv.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)
    srv.bind(("0.0.0.0", SOCKS_PORT))
    srv.listen(512)
    log.info("SOCKS5 proxy listening on :%d", SOCKS_PORT)
    while True:
        try:
            conn, addr = srv.accept()
            conn.settimeout(300)
            threading.Thread(target=_handle_socks5, args=(conn, addr), daemon=True).start()
        except Exception as exc:
            log.warning("SOCKS5 accept error: %s", exc)


# ── FastAPI management API ────────────────────────────────────────────────────

app = FastAPI(title="SecureProxy Agent", docs_url=None, redoc_url=None)


def _auth(x_api_key: str = Header(None, alias="X-API-Key")) -> None:
    if x_api_key != AGENT_KEY:
        raise HTTPException(401, "Unauthorized")


class UserIn(BaseModel):
    username: str
    password: str


@app.on_event("startup")
def startup():
    _load_users()
    threading.Thread(target=_run_http_proxy,   daemon=True).start()
    threading.Thread(target=_run_socks5_proxy, daemon=True).start()


@app.post("/users", status_code=201)
def create_user(body: UserIn, _=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    with _users_lock:
        _users[body.username] = body.password
    _save_users()
    return {"status": "created", "username": body.username}


@app.put("/users/{username}")
def update_user(username: str, body: UserIn, _=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    with _users_lock:
        _users[username] = body.password
    _save_users()
    return {"status": "updated"}


@app.delete("/users/{username}")
def delete_user(username: str, _=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    with _users_lock:
        removed = _users.pop(username, None)
    if removed is not None:
        _save_users()
    return {"status": "deleted" if removed else "not_found"}


@app.get("/users")
def list_users(_=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    with _users_lock:
        return {"users": list(_users.keys())}


@app.get("/connections")
def get_connections(_=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    with _CONN_LOCK:
        return {"connections": list(_CONN_LOG), "count": len(_CONN_LOG)}


@app.post("/reload")
def reload_users(_=Header(None, alias="X-API-Key")):
    if _ != AGENT_KEY: raise HTTPException(401, "Unauthorized")
    _load_users()
    return {"status": "reloaded", "user_count": len(_users)}


@app.get("/health")
def health():
    with _users_lock:
        user_count = len(_users)
    return {
        "status":      "ok",
        "user_count":  user_count,
        "proxy_port":  PROXY_PORT,
        "socks_port":  SOCKS_PORT,
        "agent_port":  AGENT_PORT,
    }
