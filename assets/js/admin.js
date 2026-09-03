(function () {
  function ready() {
    const sidebar = document.getElementById('swcSidebar');
    const toggle = document.getElementById('swcNavbarToggle');
    const backdrop = document.getElementById('swcSidebarBackdrop');
    const close = sidebar ? sidebar.querySelector('[data-sidebar-close]') : null;
    if (!sidebar || !toggle) return;

    const isMobile = () => window.matchMedia('(max-width: 1024px)').matches;
    const setOpen = (open) => {
      sidebar.classList.toggle('active', open);
      document.body.classList.toggle('swc-sidebar-open', open && isMobile());
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      if (backdrop) backdrop.classList.toggle('active', open && isMobile());
    };

    toggle.addEventListener('click', (event) => {
      event.stopPropagation();
      setOpen(!sidebar.classList.contains('active'));
    });
    if (backdrop) backdrop.addEventListener('click', () => setOpen(false));
    if (close) close.addEventListener('click', () => setOpen(false));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && isMobile()) setOpen(false);
    });
    sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
      if (isMobile()) setOpen(false);
    }));
    window.addEventListener('resize', () => {
      if (!isMobile()) setOpen(false);
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', ready);
  else ready();
})();
