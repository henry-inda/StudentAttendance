// Student Attendance View Enhancements

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tooltips
    initTooltips();
    
    // Add loading state to refresh button
    const refreshBtn = document.querySelector('[title="Refresh Data"]');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            setTimeout(() => {
                window.location.reload();
            }, 500);
        });
    }
    
    // Handle download report
    const downloadBtn = document.getElementById('downloadReport');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', async function() {
            try {
                showSpinner(document.getElementById('attendanceCards'));
                const response = await ajaxRequest(`${BASE_URL}student/myAttendance/download`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                if (response.url) {
                    window.location.href = response.url;
                    showToast('Report download started', 'success');
                }
            } catch (error) {
                showToast('Failed to generate report', 'danger');
            }
        });
    }
    
    // Stagger card animations
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animate__fadeInUp');
        }, index * 100);
    });
    
    // Add hover effect for better interactivity
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow-lg');
        });
        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow-lg');
        });
    });
});