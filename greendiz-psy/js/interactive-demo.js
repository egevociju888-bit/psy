(function () {
  'use strict';
  window.gdpsyInteractiveScroll = function (target) {
    if (!target || !document.querySelector('[data-gdpsy-interactive-demo]')?.contains(target)) return;
    window.scrollTo({ top: Math.max(0, scrollY + target.getBoundingClientRect().top - 100), behavior: 'smooth' });
  };
})();
