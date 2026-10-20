(function () {
    'use strict';

    var STORAGE_KEY = 'kestrel-admin-theme';
    var icon = document.getElementById('themeModeIcon');
    var toggleBtn = document.getElementById('themeModeToggle');

    function applyIcon(theme) {
        if (!icon) {
            return;
        }
        icon.setAttribute('icon', theme === 'light' ? 'mdi:weather-sunny' : 'mdi:weather-night');
    }

    applyIcon(document.documentElement.getAttribute('data-bs-theme') || 'light');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            var current = document.documentElement.getAttribute('data-bs-theme') || 'light';
            var next = current === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem(STORAGE_KEY, next);
            applyIcon(next);
        });
    }
})();
