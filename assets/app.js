document.addEventListener('DOMContentLoaded', () => {
  // Password visibility toggles
  document.querySelectorAll('.toggle-pw').forEach((button) => {
    button.addEventListener('click', () => {
      const wrap = button.closest('.input-wrap');
      const input = wrap ? wrap.querySelector('input') : null;
      if (!input) return;

      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      button.setAttribute('aria-pressed', String(isHidden));
      button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');

      const eyeOpen = button.querySelector('.icon-eye');
      const eyeClosed = button.querySelector('.icon-eye-off');
      if (eyeOpen && eyeClosed) {
        eyeOpen.style.display = isHidden ? 'none' : 'block';
        eyeClosed.style.display = isHidden ? 'block' : 'none';
      }
    });
  });

  // Mobile sidebar drawer
  const closeDrawer = () => document.body.classList.remove('drawer-open');
  document.querySelectorAll('[data-drawer-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
      document.body.classList.toggle('drawer-open');
    });
  });
  document.querySelectorAll('[data-drawer-close], .sidebar-backdrop').forEach((el) => {
    el.addEventListener('click', closeDrawer);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDrawer();
  });
});

