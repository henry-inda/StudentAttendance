document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');
    const body = document.body; // To add/remove overflow hidden

    if (sidebarToggle && sidebar && content) {
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                // Mobile view: toggle 'show' class and overlay
                sidebar.classList.toggle('show');
                body.classList.toggle('overflow-hidden'); // Prevent scrolling body when sidebar is open
            } else {
                // Desktop view: toggle 'collapsed' class and adjust content padding
                sidebar.classList.toggle('collapsed');
                if (sidebar.classList.contains('collapsed')) {
                    content.style.paddingLeft = '0';
                } else {
                    content.style.paddingLeft = '250px';
                }
            }
        }

        sidebarToggle.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                    body.classList.remove('overflow-hidden');
                }
            }
        });

        // Handle window resize events
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                // On mobile, ensure sidebar is hidden by default and content padding is 0
                sidebar.classList.remove('collapsed'); // Remove desktop collapsed state
                sidebar.classList.remove('show'); // Hide mobile sidebar
                content.style.paddingLeft = '0';
                body.classList.remove('overflow-hidden');
            } else {
                // On desktop, ensure sidebar is not hidden by 'show' class and adjust padding
                sidebar.classList.remove('show'); // Remove mobile show state
                body.classList.remove('overflow-hidden');
                // Restore desktop collapsed state if it was set
                if (!sidebar.classList.contains('collapsed')) {
                    content.style.paddingLeft = '250px';
                }
            }
        });

        // Initial setup based on screen size
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed'); // Ensure it's collapsed on mobile initially
            content.style.paddingLeft = '0';
        } else {
            content.style.paddingLeft = '250px'; // Default desktop state
        }
    }
});