(function () {
  'use strict';
  const page = document.querySelector('.gz-article-page');
  if (!page) return;
  const desktop = window.matchMedia('(min-width: 1101px)');
  const panels = Array.from(page.querySelectorAll('.gz-article-panel'));
  panels.forEach(panel => panel.querySelector('summary').addEventListener('click', event => {
    if (desktop.matches) event.preventDefault();
  }));
  function syncPanels() {
    panels.forEach(panel => {
      const content = panel.querySelector('.gz-quiet-details-content');
      if (!desktop.matches && content.contains(document.activeElement)) {
        panel.querySelector('summary').focus({ preventScroll: true });
      }
      panel.dispatchEvent(new Event('gz-details-reset'));
      panel.open = desktop.matches;
    });
  }
  desktop.addEventListener('change', syncPanels);
  syncPanels();
  const links = Array.from(page.querySelectorAll('.gz-article-toc a'));
  const sections = links.map(link => {
    try {
      return document.getElementById(decodeURIComponent(new URL(link.href).hash.slice(1)));
    } catch {
      return null;
    }
  });
  if (!links.length) return;
  const layout = page.querySelector('.gz-article-layout');
  let frame = null;
  function update() {
    frame = null;
    const offset = parseFloat(window.getComputedStyle(layout).getPropertyValue('--gz-article-sticky-top')) || 50;
    const scrollPadding = parseFloat(window.getComputedStyle(document.documentElement).scrollPaddingTop) || 0;
    let active = 0;
    sections.forEach((section, index) => {
      if (!section) return;
      const scrollMargin = parseFloat(window.getComputedStyle(section).scrollMarginTop) || 0;
      const threshold = Math.max(offset + 24, scrollPadding + scrollMargin + 2);
      if (section.getBoundingClientRect().top <= threshold) active = index;
    });
    links.forEach((link, index) => {
      if (index === active) link.setAttribute('aria-current', 'location');
      else link.removeAttribute('aria-current');
    });
  }
  function schedule() {
    if (frame === null) frame = window.requestAnimationFrame(update);
  }
  links.forEach((link, index) => link.addEventListener('click', event => {
    if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey ||
        link.hasAttribute('download') || (link.target && link.target !== '_self') || !sections[index]) return;
    event.preventDefault();
    const hash = new URL(link.href).hash;
    if (window.location.hash !== hash) window.history.pushState(null, '', hash);
    sections[index].focus({ preventScroll: true });
    sections[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
  }));
  window.addEventListener('scroll', schedule, { passive: true });
  window.addEventListener('resize', schedule, { passive: true });
  window.addEventListener('hashchange', schedule);
  window.addEventListener('pageshow', schedule);
  document.addEventListener('scrollend', schedule);
  if (typeof ResizeObserver === 'function') new ResizeObserver(schedule).observe(layout);
  if (document.fonts) document.fonts.ready.then(schedule);
  update();
})();
