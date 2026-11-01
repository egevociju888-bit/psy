(function () {
  'use strict';
  const cookie = (window.GdpsyThemeScope || document).getElementById('cookie');
  if (!cookie) return;
  let seen = false;
  try {
    seen = Boolean(window.localStorage.getItem('gd_cookie'));
  } catch (error) {}
  cookie.hidden = seen;
  function notifyLayout() {
    document.dispatchEvent(new Event('gd:layout'));
  }
  cookie.addEventListener('click', (event) => {
    const button = event.target.closest('[data-cookie]');
    if (!button || !cookie.contains(button)) return;
    try {
      window.localStorage.setItem('gd_cookie', button.dataset.cookie);
    } catch (error) {}
    cookie.hidden = true;
    notifyLayout();
  });
  if ('ResizeObserver' in window) {
    new ResizeObserver(notifyLayout).observe(cookie);
  }
  notifyLayout();
})();
