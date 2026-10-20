# 🛡️ Kestrel VPN Admin Panel

**Kestrel VPN Panel** is a robust admin dashboard tailored for managing VPN infrastructures with ease and security. Built for efficiency, it includes end-to-end control of VPS servers, VPN deployments, user accounts, subscription plans, ticketing, user feedback, SMTP configurations, and legal content like Terms of Service and Privacy Policy.

---

## 🔧 Features

### 🖥 VPS Server Management

-   **Add/Edit/Delete VPS Servers**
-   **Execute Remote Commands** via SSH (phpseclib)
-   **Real-Time VPS Monitoring** (CPU, RAM, Disk)

### 🔐 VPN Server Management

-   **Connect VPN Servers to VPS**
-   **Deployment & Monitoring** of VPN services
-   **Track Connected Users** & Bandwidth usage

### 👥 User Management

-   **Add, Edit, Block Users**
-   **Track Activity & Usage Logs**
-   **Subscription Status Control**

### 💳 Plan & Purchase Management

-   **Create/Edit VPN Plans** with pricing & durations
-   **Handle User Purchases** and expiration tracking
-   **Active/Expired Plan Monitoring**

### 🎟 Ticket Support

-   **Users Can Submit Tickets**
-   **Admin Replies, Closes, or Deletes**
-   **Ticket Conversation Log**

### 💬 Feedback System

-   **Users Submit Feedback**
-   **Admins View, Filter, and Respond**

### 📧 SMTP Configuration

-   **Dynamic Email Config Setup**
-   **Test & Save SMTP Credentials**

### 📃 Terms & Privacy Management

-   **Manage Terms of Service Content**
-   **Update Privacy Policy Dynamically**

---

## 🚀 Installation Guide

### 📦 Requirements

-   PHP 8.2+
-   Laravel 12+
-   MySQL
-   Composer
-   VPS Access with SSH

### ⚙️ Setup

```bash
git clone https://github.com/SyedMuhammadAliRizvi/kestrel-vpn-panel.git
cd kestrel-vpn-panel

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve
```

### 🌐 Accessing the Panel

-   Open your browser and navigate to `http://localhost:8000` (or your server's IP).
-   Login with the default credentials:
    -   **Email:** `admin@gmail.com`
        -   **Password:** `admin12345`
-   Change the password immediately after the first login.
-   Configure your SMTP settings in the admin panel for email notifications.
-   Add your VPS servers and connect them to the VPN service.
-   Deploy VPN servers and manage user accounts.
-   Monitor user activity, bandwidth, and ticket support.
-   Customize your Terms of Service and Privacy Policy.
-   Use the feedback system to gather user insights.
-   Enjoy a seamless VPN management experience!

## 🧰 Tech Stack

-   **Backend:** PHP, Laravel 12
-   **Frontend:** Livewire 3, Bootstrap 5
-   **Database:** MySQL
-   **VPS Management:** SSH (phpseclib)
-   **Mailing:** SMTP
-   **Charts:** ApexCharts
-   **Media Handling:** Spatie Media Library
-   **License:** MIT
-   **Contributors:** [SyedMuhammadAliRizvi](https://github.com/SyedMuhammadAliRizvi)

---

## Contributing

Contributions are welcome! Fork the repository, create a new branch, and submit a pull request. Open an issue first for significant changes.

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.