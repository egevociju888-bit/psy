(function () {
  'use strict';
  var scope = window.GdpsyThemeScope;
  var interactive = !!window.GdpsyInteractivePreviewConfig;
  var config = window.GdpsyInteractivePreviewConfig || window.GdpsyConceptConfig;
  var preview = interactive ? document.querySelector('[data-gdpsy-interactive-demo]') : null;
  var head = document.getElementById('gdd-host');
  var foot = interactive ? head : document.getElementById('gdd-foot-host');
  if (!scope || !head || !foot || !config || (interactive && !preview)) return;
  function find(selector) { return scope.querySelector(selector); }
  var html = document.documentElement;
  var fab = find('#fab');
  var plate = find('#plate');
  var pop = find('#pop');
  var hint = find('#hint');
  var views = scope.querySelectorAll('[data-view]');
  var viewBar = find('[data-view-bar]');
  var HINT_KEY = 'gd_concept_notice';
  function viewKind(view) {
    var kind = view.dataset.view;
    return kind === 'desktop' || kind === 'mobile' ? kind : '';
  }
  function viewPressed(view, on) {
    var kind = viewKind(view);
    return kind ? (kind === 'mobile') === on : on;
  }
  function setPop(open) {
    if (!pop || !plate) return;
    pop.hidden = !open;
    plate.setAttribute('aria-expanded', String(open));
  }
  if (hint) {
    try { hint.hidden = !!localStorage.getItem(HINT_KEY); } catch (_) {}
  }
  views.forEach(function (view) {
    view.hidden = !config.hasDemo;
    view.setAttribute('aria-pressed', String(viewPressed(view, false)));
  });
  if (viewBar) viewBar.hidden = !config.hasDemo;
  head.shadowRoot.querySelector(interactive ? '.gz-interactive-preview-shell' : '.gz-concept-page').classList.add('is-ready');
  var stage = null;
  var mobile = false;
  var wide = window.matchMedia('(min-width: 1001px)');
  var syncFab = null;

  function barHeight() {
    var bar = document.getElementById('wpadminbar');
    return bar && getComputedStyle(bar).position === 'fixed' ? bar.offsetHeight : 0;
  }

  function sizeStage() {
    if (stage) stage.style.height = Math.max(320, window.innerHeight - barHeight()) + 'px';
    updateThumb();
  }

  function updateThumb() {
    if (!track) return;
    try {
      var win = frame && frame.contentWindow;
      var total = win ? win.document.documentElement.scrollHeight : 0;
      var shown = win ? win.innerHeight : 0;
      if (!win || total <= shown + 1) {
        track.hidden = true;
        return;
      }
      track.hidden = false;
      var room = track.clientHeight;
      var size = Math.max(40, room * shown / total);
      thumb.style.height = size + 'px';
      thumb.style.top = (room - size) * (win.pageYOffset / (total - shown)) + 'px';
    } catch (_) { }
  }

  function dragTo(clientY) {
    var win = frame && frame.contentWindow;
    if (!win || !drag) return;
    var rect = track.getBoundingClientRect();
    var travel = Math.max(1, track.clientHeight - thumb.offsetHeight);
    var ratio = Math.min(1, Math.max(0, (clientY - rect.top - drag.grab) / travel));
    jump(win, ratio * (win.document.documentElement.scrollHeight - win.innerHeight));
  }

  function endDrag() {
    drag = null;
    track.classList.remove('is-drag');
  }

  function buildTrack() {
    track = document.createElement('div');
    track.className = 'gz-concept-scroll';
    track.setAttribute('aria-hidden', 'true');
    track.hidden = true;
    thumb = document.createElement('i');
    track.appendChild(thumb);
    track.addEventListener('pointerdown', function (event) {
      if (event.button !== 0) return;
      var rect = thumb.getBoundingClientRect();
      var onThumb = event.clientY >= rect.top && event.clientY <= rect.bottom;
      drag = { grab: onThumb ? event.clientY - rect.top : thumb.offsetHeight / 2 };
      track.setPointerCapture(event.pointerId);
      track.classList.add('is-drag');
      dragTo(event.clientY);
      event.preventDefault();
    });
    track.addEventListener('pointermove', function (event) {
      if (drag) dragTo(event.clientY);
    });
    track.addEventListener('pointerup', endDrag);
    track.addEventListener('pointercancel', endDrag);
    return track;
  }

  function rawUrl() {
    var u = new URL(window.location.href);
    u.searchParams.set(interactive ? 'gdpsy-interactive-demo' : 'gdd-raw', '1');
    u.hash = '';
    return u.toString();
  }

  var SKIP = { SCRIPT: 1, STYLE: 1, LINK: 1, TEMPLATE: 1, NOSCRIPT: 1, META: 1 };
  var frame = null;
  var frameLoaded = false;
  var pending = null;
  var track = null;
  var thumb = null;
  var drag = null;

  function pageRoots() {
    return Array.prototype.filter.call(interactive ? preview.children : document.body.children, function (n) {
      if (SKIP[n.tagName]) return false;
      var id = n.id || '';
      return id !== 'gdd-host' && id !== 'gdd-foot-host' && id !== 'gdd-stage' &&
        id !== 'wpadminbar' && id.indexOf('query-monitor') !== 0 && id.indexOf('qm') !== 0;
    });
  }

  function frameRoots(doc) {
    return Array.prototype.filter.call(doc.body.children, function (n) {
      return !SKIP[n.tagName];
    });
  }

  function flatten(roots) {
    var out = [];
    roots.forEach(function (r) {
      out.push(r);
      Array.prototype.forEach.call(r.querySelectorAll('*'), function (n) {
        if (!SKIP[n.tagName]) out.push(n);
      });
    });
    return out;
  }

  function measurable(el) {
    if (SKIP[el.tagName]) return false;
    var r = el.getBoundingClientRect();
    if (r.height <= 0 || r.width <= 0) return false;
    var pos = el.ownerDocument.defaultView.getComputedStyle(el).position;
    return pos !== 'fixed' && pos !== 'sticky' && pos !== 'absolute';
  }

  function anchorIn(roots, line) {
    var list = roots;
    var node = null;
    var frac = 0;
    while (list.length) {
      var pick = null;
      var last = null;
      for (var i = 0; i < list.length; i++) {
        if (!measurable(list[i])) continue;
        last = list[i];
        if (list[i].getBoundingClientRect().bottom > line) {
          pick = list[i];
          break;
        }
      }
      var el = pick || last;
      if (!el) break;
      var r = el.getBoundingClientRect();
      if (pick && r.top > line && node) break;
      node = el;
      frac = pick ? Math.max(0, Math.min(1, (line - r.top) / r.height)) : 1;
      if (pick && r.top > line) break;
      list = Array.prototype.slice.call(el.children);
    }
    if (!node) return null;
    return { id: node.id || '', index: flatten(roots).indexOf(node), tag: node.tagName, frac: frac };
  }

  function resolveAnchor(a, doc, roots) {
    if (a.id) {
      var byId = doc.getElementById(a.id);
      if (byId) return byId;
    }
    var n = flatten(roots)[a.index];
    return n && n.tagName === a.tag ? n : null;
  }

  function jump(win, top) {
    try {
      win.scrollTo({ top: top, behavior: 'instant' });
    } catch (_) {
      win.scrollTo(0, top);
    }
  }

  function desktopAnchor() {
    return anchorIn(pageRoots(), barHeight());
  }

  function frameAnchor() {
    try {
      var doc = frame && frameLoaded ? frame.contentDocument : null;
      return doc && doc.body ? anchorIn(frameRoots(doc), 0) : null;
    } catch (_) {
      return null;
    }
  }

  function applyToFrame(a) {
    if (!a || !frame) return;
    if (!frameLoaded) {
      pending = a;
      return;
    }
    try {
      var doc = frame.contentDocument;
      var win = frame.contentWindow;
      var el = doc && doc.body ? resolveAnchor(a, doc, frameRoots(doc)) : null;
      if (!el) return;
      var r = el.getBoundingClientRect();
      jump(win, Math.max(0, win.pageYOffset + r.top + a.frac * r.height));
    } catch (_) { }
  }

  function applyToDesktop(a) {
    if (!a) return false;
    var el = resolveAnchor(a, document, pageRoots());
    if (!el) return false;
    var r = el.getBoundingClientRect();
    jump(window, Math.max(0, window.pageYOffset + r.top + a.frac * r.height - barHeight()));
    return true;
  }

  function ensureStage() {
    if (stage) return;
    stage = document.createElement('div');
    stage.id = 'gdd-stage';
    var phone = document.createElement('div');
    phone.className = 'gz-concept-phone';
    frame = document.createElement('iframe');
    frame.title = interactive ? 'Интерактив в мобильном виде' : 'Концепт в мобильном виде';
    frame.addEventListener('load', function () {
      frameLoaded = true;
      try {
        var win = frame.contentWindow;
        win.addEventListener('scroll', updateThumb, { passive: true });
        win.addEventListener('resize', updateThumb);
        if (win.ResizeObserver) new win.ResizeObserver(updateThumb).observe(win.document.documentElement);
      } catch (_) { }
      updateThumb();
      if (pending) {
        var a = pending;
        pending = null;
        requestAnimationFrame(function () {
          applyToFrame(a);
        });
      }
    });
    frame.src = rawUrl();
    phone.appendChild(frame);
    stage.appendChild(phone);
    if (!interactive) stage.appendChild(buildTrack());
    if (interactive) preview.appendChild(stage);
    else if (foot && foot.parentNode) foot.parentNode.insertBefore(stage, foot);
    else document.body.appendChild(stage);
  }

  function setView(on, quiet) {
    if (on && (!wide.matches || !config.hasDemo)) on = false;
    if (on === mobile) return;
    var anchor = null;
    if (!quiet) anchor = on ? desktopAnchor() : frameAnchor();
    mobile = on;
    if (on) ensureStage();
    html.classList.toggle('gdd-mobile', on);
    if (interactive) find('[data-interactive-preview]').classList.toggle('is-mobile-preview', on);
    views.forEach(function (v) {
      v.setAttribute('aria-pressed', String(viewPressed(v, on)));
      if (viewKind(v)) return;
      var txt = v.querySelector('[data-view-text]');

      if (txt) {
        txt.textContent = on ? 'Посмотреть десктопную версию' : 'Посмотреть мобильную версию';
      } else {
        var label = on ? 'Показать десктопную версию' : 'Показать мобильную версию';
        v.setAttribute('aria-label', label);
        v.title = label;
      }

    });
    setPop(false);
    if (on) sizeStage();
    if (syncFab) syncFab();
    if (quiet) return;
    if (on) {
      applyToFrame(anchor);
      var s = stage.getBoundingClientRect();
      window.scrollTo({ top: Math.max(0, s.top + window.pageYOffset - barHeight()), behavior: 'smooth' });
      return;
    }
    if (applyToDesktop(anchor)) return;
    var head = find('#gz-concept-head');
    if (!head) return;
    var h = head.getBoundingClientRect();
    window.scrollTo({ top: Math.max(0, h.bottom + window.pageYOffset - barHeight()), behavior: 'smooth' });
  }

  window.addEventListener('resize', function () {
    if (mobile && !wide.matches) setView(false, true);
    else sizeStage();
    if (mobile && syncFab) syncFab();
  });

  window.addEventListener('scroll', function () {
    if (mobile && syncFab) syncFab();
  }, { passive: true });


  var zones = {head: true, foot: false};
  function sync() {
    if (!fab) return;
    var hide = zones.head || zones.foot;
    if (mobile && stage) {
      var rect = stage.getBoundingClientRect();
      hide = Math.min(rect.bottom, window.innerHeight) - Math.max(rect.top, 0) < window.innerHeight * 0.5;
    }
    var dialog = !!scope.querySelector('dialog[open]');
    hide = hide || dialog;
    var switchable = !!viewBar && !viewBar.hidden && wide.matches;
    var gone = dialog || zones.foot || (hide && !switchable);
    if (plate) {
      plate.classList.toggle('is-hidden', hide);
      plate.inert = hide;
    }
    fab.classList.toggle('is-hidden', gone);
    fab.inert = gone;
    if (hide) setPop(false);
    var cookie = find('#cookie');
    var offset = cookie && !cookie.hidden ? Math.max(0, window.innerHeight - cookie.getBoundingClientRect().top) : 0;
    fab.style.setProperty('--gz-concept-cookie-offset', offset + 'px');
  }
  syncFab = sync;
  wide.addEventListener('change', sync);
  if ('IntersectionObserver' in window) {
    [['#gz-concept-head','head'],['#gz-concept-end','foot']].forEach(function (item) {
      new IntersectionObserver(function (entries) { zones[item[1]] = entries[entries.length - 1].isIntersecting; sync(); }).observe(find(item[0]));
    });
  } else {
    zones.head = false;
    function syncZones() {
      var upper = find('#gz-concept-head').getBoundingClientRect();
      var lower = find('#gz-concept-end').getBoundingClientRect();
      zones.head = upper.bottom > 0 && upper.top < window.innerHeight;
      zones.foot = lower.bottom > 0 && lower.top < window.innerHeight;
      sync();
    }
    window.addEventListener('scroll', syncZones, {passive: true});
    window.addEventListener('resize', syncZones);
    syncZones();
  }
  scope.querySelectorAll('dialog').forEach(function (dialog) {
    new MutationObserver(sync).observe(dialog, {attributes: true, attributeFilter: ['open']});
  });
  document.addEventListener('gd:layout', sync);
  scope.addEventListener('click', function (event) {
    var target = event.target;
    var viewButton = target.closest('[data-view]');
    if (viewButton) {
      var kind = viewKind(viewButton);
      setView(kind ? kind === 'mobile' : !mobile);
    }
    if (target.closest('#plate')) setPop(pop.hidden);
    var goto = target.closest('[data-goto]');
    if (goto) { setPop(false); scope.getElementById(goto.dataset.goto)?.scrollIntoView({behavior: 'smooth', block: 'start'}); }
    if (target.closest('[data-open-dialog]')) setPop(false);
    if (target.closest('[data-hint]') && hint) {
      hint.hidden = true;
      try { localStorage.setItem(HINT_KEY, '1'); } catch (_) {}
    }
    sync();
  });
  document.addEventListener('click', function (event) { if (!event.composedPath().includes(fab)) setPop(false); });
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && pop && !pop.hidden) { setPop(false); plate.focus({preventScroll: true}); }
  });
  sync();
})();


