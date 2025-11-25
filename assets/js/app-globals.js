// Centralized client-side globals exposed by server
(function () {
    if (typeof window === 'undefined') return;

    // BASE_URL should be injected server-side if not present
    if (typeof window.BASE_URL === 'undefined') {
        // Fallback to root
        window.BASE_URL = '/';
    }

    // currentUser can be set server-side in header; ensure default
    if (typeof window.currentUser === 'undefined') {
        window.currentUser = { id: null, role: '' };
    }

    // Small helper to safely call toast API
    window.safeShowToast = function (notification) {
        if (typeof window.showToastNotification === 'function') {
            window.showToastNotification(notification);
        } else {
            // Fallback simple alert
            try {
                alert(notification.title + "\n" + notification.message);
            } catch (e) {
                console.log('Notification:', notification);
            }
        }
    };
})();