document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('swifty-blog-filter-toggle');
    const filterClose = document.getElementById('swifty-blog-filter-close');
    const sidebar = document.getElementById('swifty-blog-sidebar');
    const overlay = document.getElementById('swifty-blog-filter-overlay');

    if (filterToggle && sidebar && overlay) {
        // Open Filter Modal
        filterToggle.addEventListener('click', function(e) {
            e.preventDefault();
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        // Close Filter Modal
        function closeFilter() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        if (filterClose) {
            filterClose.addEventListener('click', function(e) {
                e.preventDefault();
                closeFilter();
            });
        }

        // Close on overlay click
        overlay.addEventListener('click', function() {
            closeFilter();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                closeFilter();
            }
        });
    }
});
