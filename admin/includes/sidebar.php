<div class="sidebar">
    <div class="sidebar-content">
        <div class="sidebar-header">
            <h2 class="logo">Glow & Style <span>Admin</span></h2>
        </div>

        <nav class="menu">
            <a href="dashboard.php" class="menu-item">
                <span class="icon">📊</span>
                <span class="text">Dashboard</span>
            </a>

            <a href="appointments.php" class="menu-item">
                <span class="icon">📅</span>
                <span class="text">Appointments</span>
            </a>

            <a href="services.php" class="menu-item">
                <span class="icon">✂️</span>
                <span class="text">Services</span>
            </a>

            <a href="employees.php" class="menu-item">
                <span class="icon">👥</span>
                <span class="text">Employees</span>
            </a>

            <a href="logs.php" class="menu-item">
                <span class="icon">📜</span>
                <span class="text">System Logs</span>
            </a>
        </nav>
    </div>

    <div class="sidebar-footer">
        <div class="notification-wrapper">
            <button id="notifBtn" class="notif-btn">
                <span class="icon">🔔</span>
                <span id="notif-count" class="count-badge">0</span>
            </button>

            <div id="notif-dropdown" class="notif-dropdown">
                <div class="notif-header">
                    <h3>Notifications</h3>
                </div>
                <div id="notif-list" class="notif-body">
                    <div class="loading-state">Loading...</div>
                </div>
            </div>
        </div>

        <div class="logout-section">
            <a href="../logout.php" class="logout-btn">
                <span class="icon">🚪</span> Logout
            </a>
        </div>
    </div>
</div>

<script>
/**
|--------------------------------------------------------------------------
| NOTIFICATION INTERACTION LOGIC
|--------------------------------------------------------------------------
*/
const notifBtn = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notif-dropdown');

if (!notifBtn) {
    console.error('[Notifications] notifBtn not found on this page');
} else if (!notifDropdown) {
    console.error('[Notifications] notif-dropdown not found on this page');
}

// Toggle dropdown visibility
if (notifBtn && notifDropdown) {
    notifBtn.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevents immediate closing from the document listener

        console.log('[Notifications] notifBtn clicked');
        // Visual debug: outline toggles so we can confirm click receipt
        notifBtn.style.outline = notifBtn.style.outline ? '' : '2px solid red';

        notifDropdown.classList.toggle('show');

        // When opening, fetch fresh data (in case initial load failed)
        if (notifDropdown.classList.contains('show')) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
document.addEventListener('click', (event) => {
        if (!notifDropdown.contains(event.target) && event.target !== notifBtn) {
            notifDropdown.classList.remove('show');
        }
    });
}

/**
|--------------------------------------------------------------------------
| ASYNC NOTIFICATION FETCHING
|--------------------------------------------------------------------------
*/
function loadNotifications() {
    const listEl = document.getElementById('notif-list');
    const countEl = document.getElementById('notif-count');

    if (!listEl || !countEl) {
        console.error('[Notifications] notif-list or notif-count not found');
        return;
    }

    fetch('fetch_notifications.php')
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            listEl.innerHTML = data.notifications;
            countEl.innerHTML = data.count;

            // Hide badge if count is 0 for a cleaner look
            countEl.style.display = (data.count > 0) ? 'flex' : 'none';
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            listEl.innerHTML = '<div class="error">Update failed</div>';
        });
}

// Initial Load and Auto-Refresh
loadNotifications();
setInterval(loadNotifications, 10000); // Refreshes every 10 seconds for better performance
</script>
