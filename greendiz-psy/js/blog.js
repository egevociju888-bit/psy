(function () {
  'use strict';
  const categories = document.querySelector('.gz-blog-categories');
  if (!categories) return;
  const desktop = window.matchMedia('(min-width: 1101px)');
  categories.querySelector('summary').addEventListener('click', (event) => {
    if (desktop.matches) event.preventDefault();
  });
  function sync() {
    if (!desktop.matches && categories.querySelector('.gz-quiet-details-content').contains(document.activeElement)) {
      categories.querySelector('summary').focus({ preventScroll: true });
    }
    categories.dispatchEvent(new Event('gz-details-reset'));
    categories.open = desktop.matches;
  }
  desktop.addEventListener('change', sync);
  sync();
})();
