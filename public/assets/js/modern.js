// Keep the current section visible, including nested menus and renewal types.
document.addEventListener('DOMContentLoaded', () => {
    // AdminLTE wraps the sidebar in its own scroll viewport. Configure that
    // viewport too; hiding overflow on the outer sidebar alone is insufficient.
    const configureSidebarScroll = () => {
        if (window.jQuery && typeof window.jQuery.fn.overlayScrollbars === 'function') {
            window.jQuery('.main-sidebar .sidebar').overlayScrollbars({
                overflowBehavior: { x: 'hidden', y: 'scroll' }
            });
        }
    };
    configureSidebarScroll();
    window.addEventListener('load', configureSidebarScroll, { once: true });

    const current = new URL(window.location.href);
    document.querySelectorAll('.nav-sidebar a.nav-link').forEach(link => {
        const destination = new URL(link.href, window.location.origin);
        if (link.getAttribute('href') === '#' || destination.origin !== current.origin) return;
        const matches = destination.pathname === current.pathname &&
            (!destination.search || destination.search === current.search);
        link.classList.toggle('active', matches);
        if (matches) {
            link.setAttribute('aria-current', 'page');
            const tree = link.closest('.nav-treeview');
            if (tree) {
                tree.style.display = 'block';
                tree.parentElement.classList.add('menu-open');
            }
        }
    });
});
