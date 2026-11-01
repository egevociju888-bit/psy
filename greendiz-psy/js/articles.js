(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;
  const section = scope.querySelector('#gz-articles');
  if (!section) return;
  const itemLabel = section.dataset.itemLabel || 'Статья';
  const viewport = section.querySelector('.gz-articles-viewport');
  const track = section.querySelector('.gz-articles-track');
  const cards = Array.from(section.querySelectorAll('.gz-article'));
  const controls = section.querySelector('.gz-articles-controls');
  const previous = section.querySelector('[data-articles-prev]');
  const next = section.querySelector('[data-articles-next]');
  const progress = section.querySelector('.gz-articles-progress');
  const thumb = section.querySelector('.gz-articles-progress-thumb');
  const status = section.querySelector('#gz-articles-status');
  if (!viewport || !track || !cards.length || !controls || !previous || !next || !progress || !thumb || !status) return;
  let frame = null;
  let announcement = null;
  const maximum = () => Math.max(0, viewport.scrollWidth - viewport.clientWidth);
  const position = () => Math.min(maximum(), Math.max(0, viewport.scrollLeft));
  function update() {
    frame = null;
    const max = maximum();
    const left = position();
    previous.disabled = left <= 2;
    next.disabled = max - left <= 2;
    controls.hidden = max <= 2;
    thumb.style.width = (viewport.scrollWidth ? Math.min(1, viewport.clientWidth / viewport.scrollWidth) * 100 : 100) + '%';
    const ratio = progress.clientWidth ? thumb.offsetWidth / progress.clientWidth : 1;
    const travel = ratio > 0 ? (1 - ratio) / ratio : 0;
    thumb.style.setProperty('--gz-articles-progress', (max ? left / max : 0) * travel * 100 + '%');
  }
  function scheduleUpdate() {
    if (frame === null) frame = window.requestAnimationFrame(update);
  }
  function targets() {
    const origin = cards[0].getBoundingClientRect().left;
    return cards.map(card => Math.min(maximum(), Math.max(0, card.getBoundingClientRect().left - origin)));
  }
  function move(direction) {
    const left = position();
    const stops = targets();
    const destination = direction > 0
      ? stops.find(stop => stop > left + 2) ?? maximum()
      : stops.reverse().find(stop => stop < left - 2) ?? 0;
    viewport.scrollTo({ left: destination, behavior: 'smooth' });
  }
  previous.addEventListener('click', () => move(-1));
  next.addEventListener('click', () => move(1));
  viewport.addEventListener('keydown', event => {
    if (event.target !== viewport || event.altKey || event.ctrlKey || event.metaKey || event.shiftKey) return;
    if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
    event.preventDefault();
    if (event.key === 'ArrowLeft') move(-1);
    else if (event.key === 'ArrowRight') move(1);
    else viewport.scrollTo({ left: event.key === 'Home' ? 0 : maximum(), behavior: 'smooth' });
  });
  viewport.addEventListener('scroll', () => {
    scheduleUpdate();
    window.clearTimeout(announcement);
    announcement = window.setTimeout(() => {
      const left = position();
      const stops = targets();
      const index = stops.reduce((best, stop, i) => Math.abs(stop - left) < Math.abs(stops[best] - left) ? i : best, 0);
      status.textContent = itemLabel + ' ' + (index + 1) + ' из ' + cards.length;
    }, 180);
  }, { passive: true });
  window.addEventListener('resize', scheduleUpdate, { passive: true });
  window.addEventListener('pageshow', scheduleUpdate);
  if (typeof ResizeObserver === 'function') {
    const observer = new ResizeObserver(scheduleUpdate);
    observer.observe(viewport);
    observer.observe(track);
  }
  if (document.fonts) document.fonts.ready.then(scheduleUpdate);
  section.classList.add('is-carousel-ready');
  update();
})();


