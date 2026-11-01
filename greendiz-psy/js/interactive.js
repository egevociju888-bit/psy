'use strict';
(() => {
  const scope = window.GdpsyThemeScope || document;
  const key = 'gdpsy-interactive-selection';
  const saveButtons = () => [...scope.querySelectorAll('[data-interactive-save]')];
  const form = scope.querySelector('.gz-interactive-filter');
  const filter = scope.querySelector('[data-interactive-selection]');
  const results = scope.querySelector('.gz-interactive-results');
  const live = scope.querySelector('[data-interactive-live]');
  const status = document.createElement('p');
  status.className = 'gz-interactive-selection-status';
  status.setAttribute('role', 'status');
  (form || scope.querySelector('.gz-interactive-hero') || saveButtons()[0]?.parentElement)?.append(status);
  const normalize = ids => [...new Set(ids.filter(id => Number.isSafeInteger(id) && id > 0))].slice(0, 1000);
  const order = scope.querySelector('[data-interactive-order]');
  let titles = {};
  try { titles = JSON.parse(order?.dataset.interactiveTitles || '{}'); } catch { titles = {}; }
  let ids = [];
  function syncOrder() {
    if (!order) return;
    const names = ids.map(id => titles[id]).filter(name => typeof name === 'string' && name !== '');
    order.hidden = names.length === 0;
    order.dataset.contactPreset = names.length
      ? `Здравствуйте, Анастасия! Хочу заказать интерактивы: ${names.map(name => `«${name}»`).join(', ')}.`.slice(0, 4900)
      : '';
  }
  function read() {
    let value;
    try { value = JSON.parse(localStorage.getItem(key) || '[]'); }
    catch (error) { if (error instanceof SyntaxError) return []; throw error; }
    return Array.isArray(value) ? normalize(value) : [];
  }
  function sync() {
    saveButtons().forEach(button => {
      const saved = ids.includes(Number(button.dataset.interactiveSave));
      button.hidden = false;
      button.setAttribute('aria-pressed', String(saved));
      const label = saved ? 'Убрать из подборки' : 'В подборку';
      if (button.hasAttribute('data-save-icon')) {
        button.setAttribute('aria-label', label);
        button.title = label;
      } else button.textContent = label;
    });
    if (filter) filter.hidden = false;
    syncOrder();
    const input = form?.querySelector('[name="selection_ids"]');
    if (input) {
      input.value = ids.join(',');
      input.disabled = !filter?.querySelector('input').checked;
    }
  }
  function submitSelection() {
    sync();
    if (form && filter?.querySelector('input').checked) form.requestSubmit();
  }
  try { ids = read(); sync(); }
  catch { status.textContent = 'Подборка недоступна: браузер ограничил локальное хранилище.'; }
  scope.addEventListener('click', event => {
    const button = event.target instanceof Element ? event.target.closest('[data-interactive-save]') : null;
    if (!button) return;
    try {
      ids = read();
      const id = Number(button.dataset.interactiveSave);
      if (!ids.includes(id) && ids.length >= 1000) {
        status.textContent = 'В подборке уже 1000 интерактивов. Сначала уберите ненужные.';
        return;
      }
      const next = ids.includes(id) ? ids.filter(value => value !== id) : [...ids, id];
      localStorage.setItem(key, JSON.stringify(next));
      ids = next;
      sync();
      status.textContent = ids.includes(id) ? 'Добавлено в подборку этого браузера.' : 'Удалено из подборки.';
      submitSelection();
    } catch { status.textContent = 'Не удалось изменить подборку. Проверьте доступ к локальному хранилищу.'; }
  });
  filter?.querySelector('input').addEventListener('change', () => {
    sync();
    form.requestSubmit();
  });
  form?.addEventListener('submit', sync);

  if (form && results) {
    const body = results.querySelector('.gz-interactive-body');
    const count = results.querySelector('.gz-interactive-count');
    const chips = results.querySelector('.gz-interactive-chips');
    const badge = scope.querySelector('.gz-interactive-panel-count');
    const reset = scope.querySelector('.gz-interactive-reset');
    const paramName = name => name.replace(/\[\d*\]$/, '');
    const pathOf = value => { const url = new URL(value, location.href); return url.pathname + url.search; };
    let controller = null;
    let applyTimer = 0;
    let replaceNext = false;
    let lastPath = pathOf(location.href);
    const urlFromForm = () => {
      const params = new URLSearchParams();
      new FormData(form).forEach((value, name) => { if (typeof value === 'string' && value !== '') params.append(name, value); });
      const url = new URL(form.action, location.href);
      url.search = params.toString();
      return url.href;
    };
    const replaceContent = (target, source) => target.replaceChildren(...[...source.childNodes].map(node => document.importNode(node, true)));
    const applyParams = value => {
      const values = {};
      new URL(value, location.href).searchParams.forEach((item, name) => { (values[paramName(name)] ||= []).push(item); });
      form.querySelectorAll('.gz-interactive-options input').forEach(input => {
        input.checked = (values[paramName(input.name)] || []).includes(input.value);
        if (input.checked) input.closest('details')?.setAttribute('open', '');
      });
      const selection = filter?.querySelector('input');
      if (selection) selection.checked = (values.selection || []).includes('1');
      sync();
    };
    const local = value => { const url = new URL(value, location.href); url.protocol = location.protocol; url.host = location.host; return url.href; };
    async function load(target, mode, fromForm, scrollUp) {
      const url = local(target);
      controller?.abort();
      const current = controller = new AbortController();
      results.classList.add('is-loading');
      results.setAttribute('aria-busy', 'true');
      try {
        const response = await fetch(url, { credentials: 'same-origin', signal: current.signal, headers: { Accept: 'text/html' } });
        if (!response.ok) throw new Error(String(response.status));
        const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
        const next = {
          body: doc.querySelector('.gz-interactive-body'),
          count: doc.querySelector('.gz-interactive-count'),
          chips: doc.querySelector('.gz-interactive-chips'),
        };
        if (!next.body || !next.count || !next.chips) throw new Error('markup');
        replaceContent(body, next.body);
        replaceContent(count, next.count);
        replaceContent(chips, next.chips);
        const total = next.chips.querySelectorAll('li').length;
        chips.hidden = total === 0;
        if (badge) { badge.textContent = String(total); badge.hidden = total === 0; }
        if (reset) reset.hidden = total === 0;
        if (live) live.textContent = count.textContent;
        if (!fromForm) applyParams(url);
        if (mode === 'push') history.pushState({}, '', url);
        else if (mode === 'replace') history.replaceState({}, '', url);
        lastPath = pathOf(url);
        sync();
        if (scrollUp) {
          const top = results.getBoundingClientRect().top + window.scrollY - 24;
          if (window.scrollY > top) window.scrollTo(0, Math.max(0, top));
        }
      } catch (error) {
        if (error.name === 'AbortError') return;
        location.href = url;
      } finally {
        if (controller === current) {
          results.classList.remove('is-loading');
          results.removeAttribute('aria-busy');
        }
      }
    }
    form.addEventListener('submit', event => {
      event.preventDefault();
      const mode = replaceNext ? 'replace' : 'push';
      replaceNext = false;
      load(urlFromForm(), mode, true, false);
    });
    form.querySelectorAll('.gz-interactive-options input').forEach(input => input.addEventListener('change', () => {
      clearTimeout(applyTimer);
      applyTimer = setTimeout(() => form.requestSubmit(), 250);
    }));
    scope.addEventListener('click', event => {
      if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
      const link = event.target instanceof Element ? event.target.closest('a.gz-interactive-chip, a.page-numbers, a.gz-interactive-reset, .gz-interactive-empty a') : null;
      if (!link || !link.href) return;
      event.preventDefault();
      load(link.href, 'push', false, link.classList.contains('page-numbers'));
    });
    window.addEventListener('popstate', () => {
      if (pathOf(location.href) === lastPath) return;
      load(location.href, 'none', false, false);
    });
    if (filter?.querySelector('input').checked) replaceNext = true;
  }

  results?.addEventListener('click', event => {
    const trigger = event.target instanceof Element ? event.target.closest('[data-interactive-custom]') : null;
    if (!trigger) return;
    const root = window.GdpsyThemeScope || document.getElementById('greendiz-design-study');
    scope.querySelector('#gz-contact')?.dispatchEvent(new CustomEvent('gz:contact-preset', { detail: { message: trigger.dataset.contactPreset } }));
    root?.dispatchEvent(new CustomEvent('gz:open-dialog', { detail: { id: 'gz-contact-dialog', opener: trigger } }));
  });

  const panel = scope.querySelector('.gz-interactive-panel');
  if (panel) {
    const desktop = window.matchMedia('(min-width: 1101px)');
    const summary = panel.querySelector('summary');
    summary.addEventListener('click', event => { if (desktop.matches) event.preventDefault(); });
    const syncPanel = () => {
      if (!desktop.matches && panel.querySelector('.gz-quiet-details-content').contains(document.activeElement)) summary.focus({ preventScroll: true });
      panel.dispatchEvent(new Event('gz-details-reset'));
      panel.open = desktop.matches;
    };
    const fitPanel = () => {
      panel.style.top = desktop.matches ? `${Math.min(24, window.innerHeight - panel.offsetHeight - 24)}px` : '';
    };
    desktop.addEventListener('change', () => { syncPanel(); fitPanel(); });
    panel.addEventListener('toggle', fitPanel, true);
    window.addEventListener('resize', fitPanel);
    syncPanel();
    fitPanel();
  }
  window.addEventListener('storage', event => {
    if (event.key !== key && event.key !== null) return;
    try { ids = read(); sync(); submitSelection(); } catch { /* Keep the current page usable. */ }
  });
  if (filter?.querySelector('input').checked) {
    const submitted = new URL(location.href).searchParams.get('selection_ids') || '';
    if (submitted !== ids.join(',')) submitSelection();
  }
})();
