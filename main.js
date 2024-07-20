document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');

    function handleMenuToggle() {
        if (window.matchMedia('(max-width: 900px)').matches) {
            sidebar.classList.remove('open');
            menuToggle.addEventListener('click', toggleSidebar);
            document.addEventListener('click', closeSidebarOnClickOutside);
        } else {
            sidebar.classList.add('open');
            menuToggle.removeEventListener('click', toggleSidebar);
            document.removeEventListener('click', closeSidebarOnClickOutside);
        }
    }
    
    function toggleSidebar(event) {
        sidebar.classList.toggle('open');
        event.stopPropagation();
    }

    function closeSidebarOnClickOutside(event) {
        if (sidebar.classList.contains('open') && !sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('open');
        }
    }

    handleMenuToggle();
    window.addEventListener('resize', handleMenuToggle);
});
