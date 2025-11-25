document.addEventListener('DOMContentLoaded', function() {
    const notificationBadge = document.querySelector('#notification-badge');
    const notificationMenu = document.querySelector('#notification-menu');

    // Function to fetch and display notifications
    async function fetchNotifications() {
        try {
            const response = await fetch(BASE_URL + 'notifications/get_new');
            let data;
            try {
                data = await response.json();
            } catch (jsonError) {
                console.error('Error parsing notifications response as JSON:', jsonError);
                return;
            }
            if (!response.ok || !data.success) {
                console.error('Error fetching notifications:', data.error || 'Unknown error');
                return;
            }

            // Clear existing notifications
            if (notificationMenu) {
                notificationMenu.innerHTML = '';
            }

            if (data.notifications && data.notifications.length > 0) {
                // Update notification badge count
                if (notificationBadge) {
                    notificationBadge.textContent = data.notifications.length;
                    notificationBadge.style.display = 'inline';
                }

                // Populate dropdown and show toast notifications
                data.notifications.forEach(notification => {
                    // Add to dropdown
                    const listItem = document.createElement('li');
                    const link = document.createElement('a');
                    link.className = 'dropdown-item';
                    link.href = notification.link;
                    link.textContent = notification.title;
                    listItem.appendChild(link);
                    if (notificationMenu) {
                        notificationMenu.appendChild(listItem);
                    }

                    // Show toast
                    showToastNotification(notification);
                });

                // Add 'View all' link
                if (notificationMenu) {
                    const divider = document.createElement('li');
                    divider.innerHTML = '<hr class="dropdown-divider">';
                    notificationMenu.appendChild(divider);
                    const viewAllItem = document.createElement('li');
                    const viewAllLink = document.createElement('a');
                    viewAllLink.className = 'dropdown-item';
                    viewAllLink.href = BASE_URL + 'notifications';
                    viewAllLink.textContent = 'View all';
                    viewAllItem.appendChild(viewAllLink);
                    notificationMenu.appendChild(viewAllItem);
                }

            } else {
                // No new notifications
                if (notificationBadge) {
                    notificationBadge.style.display = 'none';
                }
                if (notificationMenu) {
                    const noNotificationsItem = document.createElement('li');
                    noNotificationsItem.innerHTML = '<a class="dropdown-item" href="#">No new notifications</a>';
                    notificationMenu.appendChild(noNotificationsItem);
                }
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    // Function to show toast notification (exposed globally)
    window.showToastNotification = function(notification) {
        const toast = document.createElement('div');
        toast.className = 'toast toast-clickable';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        toast.style.cursor = 'pointer';
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-bell text-primary me-2"></i>
                <strong class="me-auto">${notification.title}</strong>
                <small>${'Just now'}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${notification.message}
            </div>
        `;

        // Add click event to navigate to notification page
        toast.addEventListener('click', function(e) {
            // Prevent closing if clicking the close button
            if (e.target.classList.contains('btn-close')) return;
            window.location.href = notification.link;
        });

        const toastContainer = document.querySelector('.toast-container');
        if (toastContainer) {
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    }

    // Add hover effect for mark as read buttons
    document.querySelectorAll('.notification-card').forEach(card => {
        const markReadBtn = card.querySelector('.btn-link');
        if (markReadBtn) {
            markReadBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                
                const targetElement = card; // Use the card as the spinner target
                showSpinner(targetElement);
                try {
                    const response = await fetch(url);
                    if (response.ok) {
                        // UI update logic on success
                        card.classList.remove('border-start', 'border-4', 'border-primary');
                        const icon = card.querySelector('.notification-icon i');
                        icon.classList.remove('text-primary');
                        icon.classList.add('text-muted');
                        
                        this.style.display = 'none';
                        
                        const title = card.querySelector('h6');
                        title.classList.add('text-muted');
                        title.classList.remove('text-dark');

                        const badge = document.querySelector('#notification-badge');
                        if (badge) {
                            const currentCount = parseInt(badge.textContent);
                            if (currentCount > 1) {
                                badge.textContent = currentCount - 1;
                            } else {
                                badge.style.display = 'none';
                            }
                        }
                        showToast('Notification marked as read.', 'success');
                    } else {
                        // Handle non-ok responses
                        showToast('Failed to mark notification as read.', 'danger');
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                    showToast('An error occurred. Please try again.', 'danger');
                } finally {
                    hideSpinner(targetElement);
                }
            });
        }
    });

    // Fetch notifications every 30 seconds
    setInterval(fetchNotifications, 30000);

    // Initial fetch
    fetchNotifications();
});
