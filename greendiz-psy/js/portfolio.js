(function () {
  'use strict';
  const archive = document.querySelector('.gz-archive');
  if (!archive) return;
  const offers = JSON.parse(archive.dataset.offers);
  const cards = Array.from(archive.querySelectorAll('[data-case-type]'));
  const links = Array.from(archive.querySelectorAll('[data-case-filter]'));
  const picker = archive.querySelector('#gz-archive-picker');
  const custom = archive.querySelector('#gz-archive-custom');
  const panel = archive.querySelector('#gz-archive-filter');
  const grid = archive.querySelector('#gz-archive-grid');
  const empty = archive.querySelector('#gz-archive-empty');
  const status = archive.querySelector('#gz-archive-status');
  const discuss = archive.querySelector('#gz-archive-discuss');
  const compact = window.matchMedia('(max-width: 1100px)');
  const inputs = panel ? Array.from(panel.querySelectorAll('input[type="checkbox"]')) : [];
  const keys = Array.from(new Set(inputs.map((input) => input.name)));
  const badge = panel ? panel.querySelector('[data-filter-badge]') : null;
  const clearButtons = Array.from(archive.querySelectorAll('[data-filter-clear]'));
  const emptyTitle = empty.querySelector('[data-empty-title]');
  const emptyCopy = empty.querySelector('[data-empty-copy]');
  const emptyDefault = [emptyTitle.textContent, emptyCopy.textContent];
  const disclosures = [picker, custom, panel].filter(Boolean);
  const valid = (value) => Object.hasOwn(offers, value) ? value : 'all';
  let type = valid(new URL(window.location.href).searchParams.get('case-type'));

  function selected() {
    const map = {};
    keys.forEach((key) => {
      map[key] = inputs.filter((input) => input.name === key && input.checked).map((input) => input.value);
    });
    return map;
  }
  function readFilters(params) {
    inputs.forEach((input) => {
      input.checked = params.getAll(input.name).includes(input.value);
      if (input.checked) input.closest('details')?.setAttribute('open', '');
    });
  }
  function matches(card, map) {
    return keys.every((key) => {
      if (!map[key].length) return true;
      const have = (card.getAttribute('data-filter-' + key) || '').split(' ');
      return map[key].some((slug) => have.includes(slug));
    });
  }
  function syncDisclosure() {
    disclosures.forEach((details) => {
      details.open = !compact.matches;
      details.querySelector('summary').tabIndex = compact.matches ? 0 : -1;
      details.dispatchEvent(new Event('gz-details-reset'));
    });
  }
  function render(animate = true) {
    if (type !== 'consept') inputs.forEach((input) => { input.checked = false; });
    const map = selected();
    const active = keys.reduce((sum, key) => sum + map[key].length, 0);
    let count = 0;
    const applied = type === 'project' ? 0 : active;
    cards.forEach((card) => {
      const byType = type === 'all' || card.dataset.caseType === type;
      card.hidden = !(byType && (applied === 0 || (card.dataset.caseType === 'consept' && matches(card, map))));
      if (!card.hidden) count++;
    });
    links.forEach((link) => {
      if (link.dataset.caseFilter === type) link.setAttribute('aria-current', 'true');
      else link.removeAttribute('aria-current');
    });
    grid.hidden = count === 0;
    empty.hidden = count > 0;
    if (applied > 0) {
      emptyTitle.textContent = 'По этим условиям пока нет концептов';
      emptyCopy.textContent = 'Снимите часть условий или расскажите о своей практике — подберу близкий вариант.';
    } else {
      emptyTitle.textContent = emptyDefault[0];
      emptyCopy.textContent = emptyDefault[1];
    }
    clearButtons.forEach((button) => { button.hidden = applied === 0; });
    if (badge) {
      badge.textContent = String(active);
      badge.hidden = active === 0;
    }
    if (panel) panel.hidden = type !== 'consept';
    picker.hidden = type === 'project';
    custom.hidden = type !== 'project';
    status.textContent = 'Показано ' + (type === 'project' ? 'проектов' : type === 'consept' ? 'концептов' : 'работ') + ': ' + count;
    const offer = offers[type];
    archive.querySelector('#gz-archive-offer-title').textContent = offer[0];
    archive.querySelector('#gz-archive-offer-copy').textContent = offer[1];
    discuss.textContent = offer[2] + ' ↗';
    discuss.dataset.contactPreset = offer[3];
    if (animate) {
      [archive.querySelector('.gz-archive-results'), archive.querySelector('.gz-archive-offer'), type === 'project' ? custom : picker].forEach((element) => {
        if (typeof element.animate !== 'function') return;
        element.getAnimations().forEach((animation) => animation.cancel());
        element.animate([{ opacity: 0, transform: 'translateY(10px)' }, { opacity: 1, transform: 'translateY(0)' }], { duration: 360, easing: 'cubic-bezier(.22,.61,.36,1)' });
      });
    }
  }
  function buildUrl() {
    const url = new URL(window.location.href);
    url.searchParams.delete('case-type');
    keys.forEach((key) => url.searchParams.delete(key));
    if (type !== 'all') url.searchParams.set('case-type', type);
    const map = selected();
    keys.forEach((key) => map[key].forEach((slug) => url.searchParams.append(key, slug)));
    return url;
  }
  function writeUrl(replace) {
    const url = buildUrl();
    if (url.href === window.location.href) return;
    if (replace) window.history.replaceState(null, '', url);
    else window.history.pushState(null, '', url);
  }
  function change(next) {
    type = valid(next);
    render();
    writeUrl(false);
  }
  function clearFilters() {
    inputs.forEach((input) => { input.checked = false; });
    render(false);
    writeUrl(true);
  }
  links.forEach((link) => link.addEventListener('click', (event) => {
    if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    event.preventDefault();
    change(link.dataset.caseFilter);
  }));
  archive.querySelector('[data-filter-reset]').addEventListener('click', (event) => {
    if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    event.preventDefault();
    inputs.forEach((input) => { input.checked = false; });
    change('all');
    links[0].focus({ preventScroll: true });
  });
  inputs.forEach((input) => input.addEventListener('change', () => {
    render(false);
    writeUrl(true);
  }));
  clearButtons.forEach((button) => button.addEventListener('click', clearFilters));
  disclosures.forEach((details) => details.querySelector('summary').addEventListener('click', (event) => {
    if (!compact.matches) event.preventDefault();
  }));
  compact.addEventListener('change', syncDisclosure);
  window.addEventListener('popstate', () => {
    const params = new URL(window.location.href).searchParams;
    type = valid(params.get('case-type'));
    readFilters(params);
    render();
  });
  readFilters(new URL(window.location.href).searchParams);
  syncDisclosure();
  render(false);
})();
