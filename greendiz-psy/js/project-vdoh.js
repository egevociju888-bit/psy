(function () {
  var root = document.getElementById('vdDemo');
  if (!root)
    return;
  root.classList.add('is-enhanced');
  var scenes = root.querySelectorAll('.vd-scene');
  var segs = root.querySelectorAll('.vd-seg');
  var caps = root.querySelectorAll('.vd-cap span');
  var pp = root.querySelector('.vd-pp');
  var phone = root.querySelector('.vd-phone');
  var n = scenes.length
    , cur = 0
    , started = false
    , paused = false
    , visible = false;

  function show(i) {
    cur = (i + n) % n;
    for (var k = 0; k < n; k++) {
      scenes[k].classList.remove('is-on');
      segs[k].classList.remove('is-on');
    }
    void root.offsetWidth;
    for (k = 0; k < n; k++) {
      scenes[k].classList.toggle('is-on', k === cur);
      segs[k].classList.toggle('is-on', k === cur);
      segs[k].classList.toggle('is-done', k < cur);
      caps[k].classList.toggle('is-on', k === cur);
      if (k === cur)
        segs[k].setAttribute('aria-current', 'true');
      else
        segs[k].removeAttribute('aria-current');
    }
  }

  function setPaused(p) {
    paused = p;
    root.classList.toggle('is-paused', p);
    pp.setAttribute('aria-pressed', p ? 'true' : 'false');
    pp.setAttribute('aria-label', p ? 'Продолжить показ' : 'Поставить на паузу');
  }

  function go(i) {
    if (started && paused)
      root.classList.remove('is-run');
    show(i);
  }

  function start() {
    if (started)
      return;
    started = true;
    root.classList.add('is-run');
    show(cur);
  }

  function sync() {
    root.classList.toggle('is-off', !visible || document.hidden);
    if (visible)
      start();
  }

  root.addEventListener('animationend', function (e) {
    if (e.animationName !== 'vd-fill' || !segs[cur].contains(e.target))
      return;
    if (root.classList.contains('is-run') && !paused)
      show(cur + 1);
  });

  Array.prototype.forEach.call(segs, function (s, i) {
    s.addEventListener('click', function () {
      go(i);
    });
  });

  pp.addEventListener('click', function () {
    if (!started) {
      start();
      return;
    }
    if (paused) {
      setPaused(false);
      if (!root.classList.contains('is-run')) {
        root.classList.add('is-run');
        show(cur);
      }
    } else
      setPaused(true);
  });

  var sx = 0
    , sy = 0
    , down = false;
  phone.addEventListener('pointerdown', function (e) {
    down = true;
    sx = e.clientX;
    sy = e.clientY;
  });
  phone.addEventListener('pointercancel', function () {
    down = false;
  });
  phone.addEventListener('pointerup', function (e) {
    if (!down)
      return;
    down = false;
    var dx = e.clientX - sx
      , dy = e.clientY - sy;
    if (Math.abs(dx) > 40 && Math.abs(dx) > Math.abs(dy)) {
      go(cur + (dx < 0 ? 1 : -1));
      return;
    }
    if (Math.abs(dx) < 10 && Math.abs(dy) < 10) {
      var r = phone.getBoundingClientRect();
      go(cur + (e.clientX - r.left < r.width / 3 ? -1 : 1));
    }
  });

  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (en) {
      visible = en[0].isIntersecting;
      sync();
    }
      , {
        threshold: .35
      }).observe(root);
  } else {
    visible = true;
    sync();
  }
  document.addEventListener('visibilitychange', sync);
}
)();

(function () {
  'use strict';
  document.querySelectorAll('[data-vdoh-cabinet]').forEach(cabinet => {
    const buttons = Array.from(cabinet.querySelectorAll('[data-vdoh-tab]'));
    const panels = Array.from(cabinet.querySelectorAll('[data-vdoh-panel]'));
    const tabs = cabinet.querySelector('[data-vdoh-tabs]');
    if (!tabs || !buttons.length || panels.length !== buttons.length) return;
    function select(key) {
      panels.forEach(panel => { panel.hidden = panel.dataset.vdohPanel !== key; });
      buttons.forEach(button => { button.setAttribute('aria-pressed', String(button.dataset.vdohTab === key)); });
    }
    buttons.forEach(button => { button.addEventListener('click', () => select(button.dataset.vdohTab)); });
    select(buttons[0].dataset.vdohTab);
    tabs.hidden = false;
    const legend = cabinet.querySelector('[data-vdoh-legend]');
    const lines = Array.from(cabinet.querySelectorAll('[data-vdoh-line]'));
    const series = Array.from(cabinet.querySelectorAll('[data-vdoh-series]'));
    const chart = cabinet.querySelector('[data-vdoh-chart]');
    const empty = cabinet.querySelector('[data-vdoh-empty]');
    if (!legend || !chart || !empty || !lines.length) return;
    function update() {
      lines.forEach(line => {
        series.filter(group => group.dataset.vdohSeries === line.dataset.vdohLine).forEach(group => {
          if (line.checked) group.removeAttribute('hidden');
          else group.setAttribute('hidden', '');
        });
      });
      const active = lines.some(line => line.checked);
      chart.hidden = !active;
      empty.hidden = active;
    }
    lines.forEach(line => { line.addEventListener('change', update); });
    update();
    legend.hidden = false;
  });
})();


