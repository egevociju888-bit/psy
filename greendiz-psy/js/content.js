(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;
  const regions = [];
  scope.querySelectorAll('.gz-entry table').forEach((table) => {
    if (table.parentElement.closest('table') || table.parentElement.classList.contains('gz-table-scroll')) return;
    const region = document.createElement('div');
    region.className = 'gz-table-scroll';
    table.before(region);
    region.append(table);
    const caption = table.caption?.textContent.trim();
    const update = () => {
      const overflowing = region.clientWidth > 0 && region.scrollWidth > region.clientWidth + 1;
      region.classList.toggle('is-scrollable', overflowing);
      if (overflowing) {
        region.tabIndex = 0;
        region.setAttribute('role', 'region');
        region.setAttribute('aria-label', caption ? `Таблица «${caption}», горизонтальная прокрутка` : 'Таблица с горизонтальной прокруткой');
      } else {
        region.removeAttribute('tabindex');
        region.removeAttribute('role');
        region.removeAttribute('aria-label');
      }
    };
    regions.push(update);
    if ('ResizeObserver' in window) {
      const observer = new ResizeObserver(update);
      observer.observe(region);
      observer.observe(table);
    }
    update();
  });
  if (regions.length && !('ResizeObserver' in window)) {
    window.addEventListener('resize', () => regions.forEach((update) => update()), { passive: true });
    window.addEventListener('load', () => regions.forEach((update) => update()), { once: true });
  }
})();
