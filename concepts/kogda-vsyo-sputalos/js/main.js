(() => {
'use strict';
const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];
const goTo = el => el.scrollIntoView({ behavior: 'smooth', block: 'start' });
const INTRO = 'бесплатное знакомство, 20 минут';
document.addEventListener('click', e => { if (e.target.closest('a[href="#"]')) e.preventDefault(); });

const toastEl = $('#toast');
let toastT;
function toast(msg) {
  toastEl.textContent = msg;
  toastEl.classList.add('on');
  clearTimeout(toastT);
  toastT = setTimeout(() => toastEl.classList.remove('on'), 2600);
}
async function copy(text) {
  try { await navigator.clipboard.writeText(text); return true; }
  catch (e) {
    const ta = document.createElement('textarea');
    ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
    document.body.appendChild(ta); ta.select();
    let ok = false;
    try { ok = document.execCommand('copy'); } catch (_) {}
    ta.remove();
    return ok;
  }
}

const tpl = $('#crisisBlock');
$$('.crisis-slot').forEach(s => s.appendChild(tpl.content.cloneNode(true)));

const prog = $('.progress span');
function onScroll() {
  const d = document.documentElement, max = d.scrollHeight - d.clientHeight;
  prog.style.transform = `scaleX(${max > 0 ? Math.min(1, d.scrollTop / max) : 0})`;
}
addEventListener('scroll', onScroll, { passive: true });
onScroll();

const dividers = $$('.divider');
if ('IntersectionObserver' in window) {
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('is-in'); io.unobserve(e.target); }
  }), { threshold: 0.35 });
  dividers.forEach(d => io.observe(d));
} else dividers.forEach(d => d.classList.add('is-in'));

function chipGroup(el, { multi = false, onChange } = {}) {
  const bs = $$('button', el);
  const values = () => bs.filter(b => b.getAttribute('aria-pressed') === 'true').map(b => b.dataset.v);
  const set = v => bs.forEach(b => b.setAttribute('aria-pressed', String(multi ? v.includes(b.dataset.v) : b.dataset.v === v)));
  bs.forEach(b => b.addEventListener('click', () => {
    if (multi) b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true'));
    else bs.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    if (onChange) onChange(values(), b);
  }));
  return { values, set };
}

function tabs(list, onChange) {
  const ts = $$('[role=tab]', list);
  function select(t, focus) {
    ts.forEach(x => {
      const on = x === t;
      x.setAttribute('aria-selected', String(on));
      x.tabIndex = on ? 0 : -1;
      const p = document.getElementById(x.getAttribute('aria-controls'));
      if (p) p.hidden = !on;
    });
    if (focus) t.focus();
    if (onChange) onChange(t.dataset.tab);
  }
  ts.forEach((t, i) => {
    t.addEventListener('click', () => select(t));
    t.addEventListener('keydown', e => {
      let j = null;
      if (e.key === 'ArrowRight') j = (i + 1) % ts.length;
      if (e.key === 'ArrowLeft') j = (i - 1 + ts.length) % ts.length;
      if (e.key === 'Home') j = 0;
      if (e.key === 'End') j = ts.length - 1;
      if (j !== null) { e.preventDefault(); select(ts[j], true); }
    });
  });
  return { select: id => { const t = ts.find(x => x.dataset.tab === id); if (t) select(t); } };
}

/* hero: the knot */
(function knot() {
  const cv = $('#knot'), ctx = cv.getContext('2d');
  const stageEl = $('#knotStage'), hint = $('#knotHint'), msg = $('#knotMsg'), btn = $('#knotBtn');
  const css = getComputedStyle(document.documentElement);
  const COL = css.getPropertyValue('--thread').trim() || '#2B45E0';
  const INK = css.getPropertyValue('--ink').trim() || '#1C1C1C';
  const PINK = css.getPropertyValue('--pink').trim() || '#FFC5C0';
  const N = 240;
  let W = 0, H = 0, tangled = [], straight = [];
  let u = 0, target = 0, dragging = false, lastX = 0, lastY = 0, raf = 0, visible = true, stage = 0;
  const t0 = performance.now();

  function build() {
    const r = cv.getBoundingClientRect(), dpr = Math.min(window.devicePixelRatio || 1, 2);
    W = r.width; H = r.height;
    if (!W || !H) return;
    cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    tangled = []; straight = [];
    const cx = W * 0.54, cy = H * 0.52, R = Math.min(W * 0.3, H * 0.38);
    const A = 0.12, B = 0.86;
    const start = { x: -12, y: H * 0.66 }, end = { x: W + 12, y: H * 0.34 };
    const ball = t => {
      const th = t * Math.PI * 2 * 11.3;
      const rr = R * (0.6 + 0.4 * Math.sin(t * Math.PI * 7.7 + 0.7));
      const tilt = t * 4.1;
      const ex = rr * Math.cos(th), ey = rr * 0.78 * Math.sin(th * 1.05 + 0.6);
      return { x: cx + ex * Math.cos(tilt) - ey * Math.sin(tilt) * 0.9, y: cy + ex * Math.sin(tilt) * 0.75 + ey * Math.cos(tilt) };
    };
    const b0 = ball(0), b1 = ball(1);
    for (let i = 0; i < N; i++) {
      const s = i / (N - 1);
      let p;
      if (s < A) { const k = s / A; p = { x: start.x + (b0.x - start.x) * k, y: start.y + (b0.y - start.y) * k + Math.sin(k * Math.PI) * H * 0.08 }; }
      else if (s > B) { const k = (s - B) / (1 - B); p = { x: b1.x + (end.x - b1.x) * k, y: b1.y + (end.y - b1.y) * k - Math.sin(k * Math.PI) * H * 0.1 }; }
      else p = ball((s - A) / (B - A));
      tangled.push(p);
      straight.push({ x: -12 + (W + 24) * s, y: H * 0.5 + Math.sin(s * Math.PI * 2.3 + 0.5) * H * 0.1 });
    }
    draw(performance.now());
  }

  function draw(now) {
    if (!W) return;
    const time = (now - t0) / 1000;
    u += (target - u) * 0.09;
    if (Math.abs(target - u) < 0.0005) u = target;
    ctx.clearRect(0, 0, W, H);
    const pts = new Array(N);
    for (let i = 0; i < N; i++) {
      const s = i / (N - 1);
      let k = Math.min(1, Math.max(0, u * 1.6 - (1 - s) * 0.6));
      k = k * k * (3 - 2 * k);
      const a = tangled[i], b = straight[i];
      const wob = (1 - k) * 1.3 * Math.sin(time * 1.4 + i * 0.33) + k * 2 * Math.sin(time * 0.9 + s * 6);
      pts[i] = { x: a.x + (b.x - a.x) * k, y: a.y + (b.y - a.y) * k + wob };
    }
    ctx.beginPath();
    ctx.moveTo(pts[0].x, pts[0].y);
    for (let i = 1; i < N - 1; i++) {
      const mx = (pts[i].x + pts[i + 1].x) / 2, my = (pts[i].y + pts[i + 1].y) / 2;
      ctx.quadraticCurveTo(pts[i].x, pts[i].y, mx, my);
    }
    ctx.lineTo(pts[N - 1].x, pts[N - 1].y);
    ctx.strokeStyle = COL; ctx.lineWidth = 3; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.stroke();
    const e = pts[Math.round(N * 0.95)];
    if (u < 0.98) {
      const ph = (time * 0.8) % 1;
      ctx.beginPath(); ctx.arc(e.x, e.y, 11 + 9 * ph, 0, Math.PI * 2);
      ctx.strokeStyle = PINK; ctx.globalAlpha = 1 - ph; ctx.lineWidth = 3; ctx.stroke(); ctx.globalAlpha = 1;
    }
    ctx.beginPath(); ctx.arc(e.x, e.y, 8, 0, Math.PI * 2);
    ctx.fillStyle = INK; ctx.fill();
  }

  function loop(now) { draw(now); raf = visible ? requestAnimationFrame(loop) : 0; }
  function kick() { if (!raf && visible) raf = requestAnimationFrame(loop); }

  function progress() {
    if (target > 0.02) { hint.style.visibility = 'hidden'; stageEl.classList.add('is-pulled'); }
    if (target >= 0.45 && stage < 1) { stage = 1; msg.textContent = 'Узел не режут — его разбирают петля за петлёй.'; }
    if (target >= 1 && stage < 2) {
      stage = 2;
      msg.textContent = 'Распутано. В жизни на это уходит больше времени, но устроено так же: по одной нити.';
      btn.textContent = 'Спутать обратно';
    }
  }
  cv.addEventListener('pointerdown', e => { dragging = true; lastX = e.clientX; lastY = e.clientY; });
  addEventListener('pointermove', e => {
    if (!dragging) return;
    const d = Math.abs(e.clientX - lastX) + (e.pointerType === 'mouse' ? Math.abs(e.clientY - lastY) * 0.5 : 0);
    lastX = e.clientX; lastY = e.clientY;
    target = Math.min(1, target + d / (W * 1.5));
    progress(); kick();
  });
  addEventListener('pointerup', () => { dragging = false; });
  addEventListener('pointercancel', () => { dragging = false; });
  btn.addEventListener('click', () => {
    if (stage === 2) {
      target = 0; stage = 0; msg.textContent = '';
      btn.textContent = 'Распутать одним нажатием';
      hint.style.visibility = ''; stageEl.classList.remove('is-pulled');
    } else { target = 1; progress(); }
    kick();
  });
  new ResizeObserver(build).observe(cv);
  new IntersectionObserver(es => { visible = es[0].isIntersecting; kick(); }).observe(cv);
  build(); kick();
})();

/* recognise cards */
$$('.flip').forEach(b => b.addEventListener('click', () => b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true'))));

/* tools */
let stopAll = () => {};
const toolTabs = tabs($('#toolTabs'), () => stopAll());

const BR = {
  long: { note: 'Вдох на 4 счёта, выдох на 6. Длинный выдох замедляет сердцебиение. Подходит при тревоге и перед сном.', seq: [['in', 4, 1], ['out', 6, 0]] },
  box: { note: 'Вдох, задержка, выдох, пауза — по 4 счёта. Собирает внимание перед важным разговором или экзаменом.', seq: [['in', 4, 1], ['hold', 4, 1], ['out', 4, 0], ['rest', 4, 0]] },
  double: { note: 'Полный вдох носом, короткий довдох и медленный выдох ртом. Помогает при острой тревоге: хватает 2–3 циклов.', seq: [['in', 2.5, 0.72], ['in2', 1, 1], ['out', 6, 0], ['rest', 1.5, 0]] }
};
const PH = { in: 'Вдох', in2: 'Ещё чуть-чуть', hold: 'Задержите', out: 'Выдох', rest: 'Пауза' };
const br = { mode: 'long', min: 1, running: false, raf: 0, t0: 0, lastIdx: -1 };
const X0 = 16, X1 = 304, TOP = 22, BOT = 128;
const brBase = $('#brBase'), brDone = $('#brDone'), brDot = $('#brDot'), brClip = $('#brClipRect');
const brPhase = $('#brPhase'), brCount = $('#brCount'), brStart = $('#brStart'), brLeft = $('#brLeft'), brNote = $('#brNote');
const BR_IDLE = 'Линия — это объём воздуха в лёгких. Следите за точкой: вверх — вдох, вниз — выдох.';
const cycleLen = seq => seq.reduce((a, s) => a + s[1], 0);
function levelAt(seq, t) {
  let acc = 0, prev = 0;
  for (let i = 0; i < seq.length; i++) {
    const [ph, d, to] = seq[i];
    if (t < acc + d) {
      const k = (t - acc) / d, e = (1 - Math.cos(Math.PI * k)) / 2;
      return { lv: prev + (to - prev) * e, ph, rem: acc + d - t, idx: i };
    }
    acc += d; prev = to;
  }
  return { lv: prev, ph: seq[seq.length - 1][0], rem: 0, idx: seq.length - 1 };
}
const bx = (t, c) => X0 + (t / c) * (X1 - X0);
const by = lv => BOT - lv * (BOT - TOP);
function drawBreathPath() {
  const seq = BR[br.mode].seq, c = cycleLen(seq);
  let d = '';
  for (let i = 0; i <= 180; i++) {
    const t = Math.min(c - 1e-6, (i / 180) * c);
    d += (i ? 'L' : 'M') + bx(t, c).toFixed(1) + ' ' + by(levelAt(seq, t).lv).toFixed(1);
  }
  brBase.setAttribute('d', d); brDone.setAttribute('d', d);
  brClip.setAttribute('width', 0); brDot.setAttribute('cx', X0); brDot.setAttribute('cy', BOT);
  brNote.textContent = BR[br.mode].note;
}
function brTick(now) {
  const seq = BR[br.mode].seq, c = cycleLen(seq), total = br.min * 60;
  const el = (now - br.t0) / 1000;
  if (el >= total) { stopBreath(true); return; }
  const tc = el % c, L = levelAt(seq, tc), x = bx(tc, c);
  brDot.setAttribute('cx', x.toFixed(1)); brDot.setAttribute('cy', by(L.lv).toFixed(1));
  brClip.setAttribute('width', x.toFixed(1));
  const key = Math.floor(el / c) * 10 + L.idx;
  if (key !== br.lastIdx) {
    br.lastIdx = key; brPhase.textContent = PH[L.ph];
    if ($('#brVib').checked && navigator.vibrate) navigator.vibrate(L.ph === 'out' ? [30, 60, 30] : 40);
  }
  brCount.textContent = Math.ceil(L.rem);
  const left = Math.ceil(total - el);
  brLeft.textContent = `Осталось ${Math.floor(left / 60)}:${String(left % 60).padStart(2, '0')}`;
  br.raf = requestAnimationFrame(brTick);
}
function startBreath() {
  if (br.running) return;
  br.running = true; br.t0 = performance.now(); br.lastIdx = -1; brStart.textContent = 'Остановить';
  br.raf = requestAnimationFrame(brTick);
}
function stopBreath(finished) {
  cancelAnimationFrame(br.raf); br.running = false; brStart.textContent = 'Начать';
  brCount.textContent = '';
  if (finished) { brPhase.textContent = 'Готово'; brLeft.textContent = 'Посидите ещё немного. Что изменилось в теле по сравнению с началом?'; }
  else { brPhase.textContent = 'Готовы?'; brLeft.textContent = BR_IDLE; drawBreathPath(); }
}
const brMode = chipGroup($('#brMode'), { onChange: v => { br.mode = v[0]; if (br.running) stopBreath(false); drawBreathPath(); } });
const brMin = chipGroup($('#brMin'), { onChange: v => { br.min = +v[0]; } });
brStart.addEventListener('click', () => br.running ? stopBreath(false) : startBreath());
if ('vibrate' in navigator) $('#brVibWrap').hidden = false;
drawBreathPath();

/* grounding 5-4-3-2-1 */
const G = [
  { n: 5, t: 'Найдите глазами 5 вещей вокруг', h: 'Подойдёт что угодно: розетка, трещина на потолке, цвет чашки. Назовите каждую про себя.' },
  { n: 4, t: 'Прикоснитесь к 4 предметам', h: 'Ткань одежды, прохладный стол, собственная ладонь. Заметьте температуру и фактуру.' },
  { n: 3, t: 'Услышьте 3 звука', h: 'Дальние и близкие: машина за окном, гудение техники, собственное дыхание.' },
  { n: 2, t: 'Найдите 2 запаха', h: 'Если ничего не чувствуется — понюхайте рукав, чай или мыло. Можно вспомнить любимый запах.' },
  { n: 1, t: 'Заметьте 1 вкус', h: 'Глоток воды, мятная конфета или просто вкус во рту прямо сейчас.' }
];
let gi = 0;
const gDots = $('#gDots');
function gRender() {
  if (gi >= G.length) {
    $('#gStep').textContent = 'Готово';
    $('#gTitle').textContent = 'Вы здесь, в этом месте и в этом моменте';
    $('#gHint').textContent = 'Заметьте, что изменилось по сравнению с началом: дыхание, плечи, мысли. Если стало хоть немного спокойнее — упражнение сработало.';
    gDots.replaceChildren(); $('#gLeft').textContent = '';
    return;
  }
  const s = G[gi];
  $('#gStep').textContent = `Шаг ${gi + 1} из 5`; $('#gTitle').textContent = s.t; $('#gHint').textContent = s.h;
  $('#gLeft').textContent = 'Отмечайте каждую найденную вещь нажатием на кружок.';
  gDots.replaceChildren();
  for (let i = 0; i < s.n; i++) {
    const b = document.createElement('button');
    b.type = 'button'; b.className = 'g-dot';
    b.setAttribute('aria-pressed', 'false'); b.setAttribute('aria-label', `Отметить ${i + 1} из ${s.n}`);
    b.addEventListener('click', () => {
      b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true'));
      if (navigator.vibrate) navigator.vibrate(15);
      if ($$('.g-dot[aria-pressed="true"]', gDots).length === s.n) setTimeout(() => { gi++; gRender(); const f = $('.g-dot', gDots); if (f) f.focus(); }, 550);
    });
    gDots.appendChild(b);
  }
}
$('#gReset').addEventListener('click', () => { gi = 0; gRender(); });
gRender();

/* unload thoughts */
let items = [], uid = 0;
const uList = $('#uList');
function uRender() {
  uList.replaceChildren();
  items.forEach(it => {
    const li = document.createElement('li'); li.className = 'u-item';
    const top = document.createElement('div'); top.className = 'u-text';
    const txt = document.createElement('span'); txt.textContent = it.text;
    const del = document.createElement('button');
    del.type = 'button'; del.className = 'u-del'; del.textContent = '×'; del.setAttribute('aria-label', 'Удалить: ' + it.text);
    del.addEventListener('click', () => { items = items.filter(x => x !== it); uRender(); });
    top.append(txt, del);
    const row = document.createElement('div'); row.className = 'chips inv';
    [['can', 'Могу повлиять'], ['cant', 'Не от меня']].forEach(([v, l]) => {
      const b = document.createElement('button');
      b.type = 'button'; b.className = 'chip'; b.textContent = l;
      b.setAttribute('aria-pressed', String(it.bucket === v));
      b.addEventListener('click', () => { it.bucket = v; uRender(); });
      row.appendChild(b);
    });
    li.append(top, row); uList.appendChild(li);
  });
  const open = items.filter(i => !i.bucket).length;
  $('#uSort').disabled = !items.length || open > 0;
  $('#uStatus').textContent = !items.length ? '' : open ? `Осталось отметить: ${open}` : 'Всё отмечено';
  $('#uEmpty').hidden = items.length > 0;
}
$('#uForm').addEventListener('submit', e => {
  e.preventDefault();
  const f = $('#uField'), v = f.value.trim();
  if (!v) return;
  items.push({ id: ++uid, text: v, bucket: null, step: '' });
  f.value = ''; uRender(); f.focus();
});
$('#uSort').addEventListener('click', () => {
  const go = $('#uGo'), keep = $('#uKeep');
  go.replaceChildren(); keep.replaceChildren();
  items.filter(i => i.bucket === 'cant').forEach(i => { const li = document.createElement('li'); li.textContent = i.text; go.appendChild(li); });
  if (!go.children.length) { const li = document.createElement('li'); li.textContent = 'Таких нет — всё в ваших руках.'; go.appendChild(li); }
  items.filter(i => i.bucket === 'can').forEach(i => {
    const li = document.createElement('li'), t = document.createElement('div');
    t.textContent = i.text;
    const lab = document.createElement('label'); lab.textContent = 'Первый шаг на 10 минут'; lab.htmlFor = 'step' + i.id;
    const inp = document.createElement('input');
    inp.className = 'field'; inp.id = 'step' + i.id; inp.maxLength = 140; inp.placeholder = 'Например: написать коллеге одно сообщение';
    inp.value = i.step; inp.addEventListener('input', () => { i.step = inp.value; });
    li.append(t, lab, inp); keep.appendChild(li);
  });
  if (!keep.children.length) { const li = document.createElement('li'); li.textContent = 'На это сейчас нельзя повлиять. Значит, самое полезное — позаботиться о себе: поесть, подвигаться, поговорить с кем-то.'; keep.appendChild(li); }
  $('#uInput').hidden = true; $('#uResult').hidden = false;
});
$('#uCopy').addEventListener('click', async () => {
  const can = items.filter(i => i.bucket === 'can'), cant = items.filter(i => i.bucket === 'cant');
  let t = 'Беру в работу:\n' + (can.map(i => `— ${i.text}${i.step ? ' → первый шаг: ' + i.step : ''}`).join('\n') || '—');
  t += '\n\nОтпускаю (не от меня):\n' + (cant.map(i => '— ' + i.text).join('\n') || '—');
  toast(await copy(t) ? 'План скопирован' : 'Не получилось скопировать — выделите текст вручную');
});
$('#uAgain').addEventListener('click', () => { items = []; $('#uInput').hidden = false; $('#uResult').hidden = true; uRender(); });

/* PMR */
const PM = [
  { n: 'Кисти рук', t: 'Сожмите кулаки изо всех сил' },
  { n: 'Плечи', t: 'Поднимите плечи к ушам' },
  { n: 'Лицо', t: 'Зажмурьтесь и сожмите челюсти' },
  { n: 'Живот', t: 'Втяните и напрягите живот' },
  { n: 'Ноги', t: 'Потяните носки на себя, напрягите бёдра' }
];
const TENSE = 5, REL = 10;
const pm = { running: false, raf: 0, t0: 0, amp: 12, last: -1 };
const pmPath = $('#pmrPath');
function pmDraw(time, tense) {
  pm.amp += ((tense ? 0 : 12) - pm.amp) * 0.08;
  let d = '';
  for (let x = 10; x <= 310; x += 6) { const y = 45 + pm.amp * Math.sin(x / 17 + time * 1.2); d += (x === 10 ? 'M' : 'L') + x + ' ' + y.toFixed(1); }
  pmPath.setAttribute('d', d); pmPath.setAttribute('stroke-width', tense ? 6 : 3);
}
function pmTick(now) {
  const el = (now - pm.t0) / 1000, per = TENSE + REL, i = Math.floor(el / per);
  if (i >= PM.length) { pmStop(true); return; }
  const inP = el - i * per, tense = inP < TENSE, key = i * 2 + (tense ? 0 : 1);
  if (key !== pm.last) {
    pm.last = key;
    $('#pmrStep').textContent = `${i + 1} из ${PM.length}`;
    $('#pmrName').textContent = PM[i].n;
    $('#pmrText').textContent = tense ? PM[i].t : 'Отпустите. Почувствуйте, как уходит напряжение';
    if (navigator.vibrate) navigator.vibrate(30);
  }
  $('#pmrCount').textContent = Math.ceil(tense ? TENSE - inP : per - inP);
  pmDraw(el, tense);
  pm.raf = requestAnimationFrame(pmTick);
}
function pmStart() { pm.running = true; pm.t0 = performance.now(); pm.last = -1; $('#pmrStart').textContent = 'Остановить'; pm.raf = requestAnimationFrame(pmTick); }
function pmStop(done) {
  cancelAnimationFrame(pm.raf); pm.running = false;
  $('#pmrStart').textContent = done ? 'Ещё раз' : 'Начать';
  $('#pmrCount').textContent = '';
  if (done) { $('#pmrStep').textContent = 'Готово'; $('#pmrName').textContent = 'Тело отпущено'; $('#pmrText').textContent = 'Посидите ещё полминуты. Если где-то осталось напряжение, повторите только эту группу мышц.'; }
  else { $('#pmrStep').textContent = '5 групп мышц'; $('#pmrName').textContent = 'Начнём с кистей рук'; $('#pmrText').textContent = 'Сядьте удобно, стопы на полу.'; }
  pm.amp = 12; pmDraw(0, false);
}
$('#pmrStart').addEventListener('click', () => pm.running ? pmStop(false) : pmStart());
pmDraw(0, false);

stopAll = () => { if (br.running) stopBreath(false); if (pm.running) pmStop(false); };

function openTool(name, opt = {}) {
  toolTabs.select(name);
  if (name === 'breath') {
    if (opt.mode) { br.mode = opt.mode; brMode.set(opt.mode); drawBreathPath(); }
    if (opt.min) { br.min = opt.min; brMin.set(String(opt.min)); }
  }
  if (name === 'ground') { gi = 0; gRender(); }
  goTo($('#tools'));
  if (opt.autostart && name === 'breath') setTimeout(startBreath, 700);
}

/* navigator */
const NAV = {
  body: { req: 'тело реагирует тревогой: сердцебиение, напряжение, трудно дышать ровно', now: { t: 'Двойной вдох, одна минута', p: 'Полный вдох носом, в конце — короткий довдох, затем медленный выдох ртом. Два-три таких цикла помогают снизить телесную тревогу: длинный выдох включает систему успокоения.', tool: 'breath', opt: { mode: 'double', min: 1, autostart: true }, b: 'Подышать вместе' }, flag: 'Если сердцебиение или боль в груди появились впервые — сначала исключите медицинскую причину: позвоните 103 или 112.' },
  loop: { req: 'мысли ходят по кругу, трудно переключиться', now: { t: 'Выгрузить мысли и разделить', p: 'Выпишите всё, что крутится в голове, и отделите то, на что можете повлиять, от того, на что нет. Записанная мысль перестаёт звучать на повторе.', tool: 'unload', b: 'Открыть разгрузку' } },
  empty: { req: 'нет сил, даже простые дела даются с трудом', now: { t: 'Один минимальный шаг', p: 'Когда сил нет, не нужно «собираться». Выпейте стакан воды, постойте пять минут у открытого окна, выберите одно дело на две минуты. Если трудно даже это — начните с заземления: оно не требует энергии.', tool: 'ground', b: 'Начать заземление' } },
  irritation: { req: 'постоянное раздражение, срываюсь на близких', now: { t: 'Снять заряд с мышц', p: 'Раздражение живёт в теле — в челюсти, плечах, кулаках. Напрячь мышцы на 5 секунд и отпустить: так тело сбрасывает накопленное и становится легче не сорваться.', tool: 'pmr', b: 'Начать упражнение' } },
  sleep: { req: 'проблемы со сном', now: { t: 'Длинный выдох перед сном', p: 'Вдох на 4, выдох на 6 — пять минут лёжа. И два правила: не смотреть на часы и, если не спится дольше 20 минут, встать и заняться чем-то скучным при приглушённом свете.', tool: 'breath', opt: { mode: 'long', min: 5 }, b: 'Открыть дыхание' } },
  people: { req: 'тяжело в отношениях с конкретным человеком', now: { t: 'Отделить своё от чужого', p: 'В трудных отношениях много сил уходит на то, что зависит от другого человека. Выпишите, что тревожит, и отметьте, на что влияете вы: свои слова, границы, решения.', tool: 'unload', b: 'Открыть разгрузку' } }
};
const DUR = {
  today: { l: 'появилось сегодня-вчера', lv: 0, p: 'Острая реакция на событие — нормальная работа психики: она переваривает случившееся. Обычно за несколько дней становится легче. Помогают сон, еда по расписанию и разговор с близким человеком.' },
  weeks: { l: 'длится пару недель', lv: 1, p: 'Стоит понаблюдать. Если за 2–4 недели не становится легче или становится хуже, это повод прийти на консультацию, не дожидаясь, пока накроет.' },
  months: { l: 'длится больше месяца', lv: 2, p: 'Состояние держится долго, а одной силой воли такое редко проходит. Работа со специалистом здесь оправдана: вдвоём разобраться обычно проще, чем в одиночку.' },
  years: { l: 'знакомо годами', lv: 2, p: 'Похоже на устойчивый сценарий, а не на реакцию на обстоятельства. С такими вещами хорошо работает схема-терапия: разбираемся, откуда он взялся и что его поддерживает.' }
};
const AREA = { work: 'работа', rel: 'отношения', sleep: 'сон', food: 'аппетит', health: 'здоровье', none: 'пока ничего' };
const nav = { step: 1, q1: null, q2: null };
const navSteps = $$('.nav-step'), navRes = $('#navRes'), navTop = $('#navTop');
const routePath = $('#navRoutePath'), routeNodes = $$('#navRoute circle');
function navGo(n) {
  nav.step = n;
  navSteps.forEach(s => { s.hidden = +s.dataset.step !== n; });
  navRes.hidden = true; navTop.hidden = false;
  routePath.style.strokeDashoffset = String(1 - (n - 1) / 2);
  routeNodes.forEach((c, i) => c.classList.toggle('on', i < n));
  $('#navCount').textContent = `Вопрос ${n} из 3`;
}
const q1 = chipGroup($('#q1'), { onChange: v => { nav.q1 = v[0]; setTimeout(() => v[0] === 'crisis' ? showCrisis() : navGo(2), 220); } });
chipGroup($('#q2'), { onChange: v => { nav.q2 = v[0]; setTimeout(() => navGo(3), 220); } });
const q2 = { set: v => $$('#q2 button').forEach(b => b.setAttribute('aria-pressed', String(b.dataset.v === v))) };
const q3 = chipGroup($('#q3'), { multi: true, onChange: (v, b) => {
  if (b.dataset.v === 'none' && v.includes('none')) q3.set(['none']);
  else if (v.includes('none') && v.length > 1) q3.set(v.filter(x => x !== 'none'));
  $('#navShow').disabled = !q3.values().length;
} });
$$('[data-back]').forEach(b => b.addEventListener('click', () => navGo(nav.step - 1)));
function navShow(html) {
  navSteps.forEach(s => { s.hidden = true; });
  navTop.hidden = true;
  navRes.innerHTML = html; navRes.hidden = false; navRes.focus({ preventScroll: true });
  goTo($('#navPanel'));
}
function showCrisis() {
  navShow(`<p class="intro">Спасибо, что отметили это. Такие мысли бывают у многих в очень тяжёлые периоды, и оставаться с ними в одиночку не нужно.</p>${tpl.innerHTML}<div class="res-actions"><button type="button" class="btn btn-ghost" data-act="breath">Подышать минуту, пока решаюсь позвонить</button><button type="button" class="link-btn" data-act="restart">Пройти заново</button></div>`);
}
$('#navShow').addEventListener('click', () => {
  const a = NAV[nav.q1], d = DUR[nav.q2], areas = q3.values();
  const real = areas.filter(x => x !== 'none');
  let html = `<div class="res-grid"><div class="res-block res-now"><p class="res-label">Прямо сейчас</p><h3>${a.now.t}</h3><p>${a.now.p}</p><button type="button" class="btn btn-light" data-act="tool">${a.now.b}</button></div><div class="res-side">`;
  html += `<div class="res-block"><p class="res-label">Если смотреть шире</p><p>${d.p}</p>`;
  if (real.length >= 2) html += `<p>Задето сразу несколько сфер: ${real.map(x => AREA[x]).join(', ')}. Это серьёзная нагрузка — консультация поможет понять, с чего начать.</p>`;
  else if (areas.includes('none')) html += '<p>Хорошо, что пока это не мешает жить. Самое время заметить, что помогает, и не доводить до перегруза.</p>';
  html += '</div>';
  const med = ((nav.q1 === 'empty' || nav.q1 === 'sleep') && d.lv >= 1) || real.includes('food') || real.includes('health');
  if (a.flag || med) {
    html += '<div class="res-block flag"><p class="res-label">Стоит проверить</p>';
    if (a.flag) html += `<p>${a.flag}</p>`;
    if (med) html += '<p>При долгой усталости, проблемах со сном или аппетитом сходите к терапевту: такие состояния бывают связаны со щитовидной железой, анемией или дефицитами. А к психиатру обращаются не только в крайних случаях — он может подобрать лечение, которое облегчит состояние, пока идёт терапия.</p>';
    html += '</div>';
  }
  html += `<div class="res-block"><p class="res-label">Следующий шаг</p><p>${d.lv === 0 ? 'Если через пару недель не станет легче — приходите на знакомство.' : 'Можно записаться на бесплатное знакомство: за 20 минут поймём, подхожу ли я для вашего запроса.'}</p>`;
  html += '<div class="res-actions"><button type="button" class="btn btn-thread" data-act="book">Взять это в заявку</button><button type="button" class="link-btn" data-act="restart">Пройти заново</button></div></div>';
  html += '</div></div><p class="small">Это ориентир, а не диагноз. Точнее разобраться можно только в разговоре.</p>';
  navShow(html);
});
navRes.addEventListener('click', e => {
  const b = e.target.closest('[data-act]');
  if (!b) return;
  const act = b.dataset.act, a = NAV[nav.q1];
  if (act === 'tool') openTool(a.now.tool, a.now.opt || {});
  if (act === 'breath') openTool('breath', { mode: 'long', min: 1, autostart: true });
  if (act === 'restart') { q1.set(''); q2.set(''); q3.set([]); $('#navShow').disabled = true; navGo(1); }
  if (act === 'book') {
    const real = q3.values().filter(x => x !== 'none');
    let t = `Сейчас ${a.req}; ${DUR[nav.q2].l}.`;
    if (real.length) t += ` Затронуто: ${real.map(x => AREA[x]).join(', ')}.`;
    $('#bTopic').value = t; bFmt.set(INTRO);
    goTo($('#book'));
    setTimeout(() => $('#bName').focus({ preventScroll: true }), 600);
  }
});
navGo(1);

/* estimator */
const EST = {
  situ: { a: 5, b: 10, t: '<b>5–10 встреч</b>, это 1–2,5 месяца. Работаем прицельно с одной ситуацией.' },
  anx: { a: 12, b: 20, t: '<b>12–20 встреч</b>, это 3–5 месяцев. К 4–6 встрече вместе смотрим, есть ли первые сдвиги.' },
  burn: { a: 10, b: 20, t: '<b>10–20 встреч</b>. Много времени уходит на то, чтобы перестроить нагрузку и вернуть восстановление.' },
  pattern: { a: 25, b: 70, t: '<b>От полугода до полутора лет</b>. Это глубокая работа, поэтому она занимает больше времени.' }
};
function est(v) {
  const e = EST[v], M = 70, r = $('#estRange');
  r.style.left = (e.a / M * 100) + '%'; r.style.width = ((e.b - e.a) / M * 100) + '%';
  $('#estOut').innerHTML = e.t;
}
chipGroup($('#estChips'), { onChange: v => est(v[0]) });
est('situ');

tabs($('#scopeTabs')); tabs($('#eduTabs')); tabs($('#priceTabs'));

/* booking */
const bFmt = chipGroup($('#bFmt'));
const bTime = chipGroup($('#bTime'), { multi: true });
const bName = $('#bName'), bContact = $('#bContact'), bAgree = $('#bAgree');
const WAY = {
  Telegram: { ph: '@username', type: 'text', mode: 'text', ac: 'off', re: /^@?\w{5,32}$/, bad: 'Нужен ник вида @username.' },
  WhatsApp: { ph: '+7(000)000-00-00', type: 'tel', mode: 'tel', ac: 'tel', re: /^\+?[\d\s()-]{10,20}$/, bad: 'Введите номер в формате +7(000)000-00-00.' },
  'почта': { ph: 'name@mail.ru', type: 'email', mode: 'email', ac: 'email', re: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/, bad: 'Проверьте адрес почты.' }
};
function bField(el, msg) {
  const p = $('#' + el.id + 'Err');
  p.textContent = msg; p.hidden = !msg;
  el.setAttribute('aria-invalid', String(!!msg));
}
const bWay = chipGroup($('#bWay'), { onChange: v => {
  const w = WAY[v[0]];
  bContact.placeholder = w.ph; bContact.type = w.type; bContact.inputMode = w.mode; bContact.autocomplete = w.ac;
  bField(bContact, '');
} });
function bValid() {
  const w = WAY[bWay.values()[0] || 'Telegram'], v = bContact.value.trim();
  const bad = !v ? 'Укажите, как с вами связаться.' : (!w.re.test(v) || (w.type === 'tel' && v.replace(/\D/g, '').length < 10)) ? w.bad : '';
  const errs = [
    [bName, bName.value.trim().length < 2 ? 'Напишите, как к вам обращаться.' : ''],
    [bContact, bad],
    [bAgree, bAgree.checked ? '' : 'Нужно согласие на обработку данных.']
  ];
  errs.forEach(([el, m]) => bField(el, m));
  const first = errs.find(x => x[1]);
  if (first) first[0].focus();
  return !first;
}
[bName, bContact].forEach(el => el.addEventListener('input', () => bField(el, '')));
bAgree.addEventListener('change', () => bField(bAgree, ''));
let bMsg = '';
$('#bForm').addEventListener('submit', e => {
  e.preventDefault();
  if (!bValid()) return;
  const name = bName.value.trim(), topic = $('#bTopic').value.trim(), contact = bContact.value.trim();
  const times = bTime.values(), way = bWay.values()[0] || 'Telegram', fmt = bFmt.values()[0] || INTRO;
  bMsg = `Здравствуйте, Вера! Меня зовут ${name}.\nХочу записаться: ${fmt}.`;
  if (times.length) bMsg += `\nУдобное время: ${times.join(', ')}.`;
  if (topic) bMsg += `\nО чём хочется поговорить: ${topic}`;
  bMsg += `\nОтветить мне: ${way}, ${contact}.`;
  $('#bText').textContent = bMsg;
  $('#bOut').hidden = false;
  $('#bOut').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  $('#bOut').focus({ preventScroll: true });
});
$('#bCopy').addEventListener('click', async () => toast(await copy(bMsg) ? 'Сообщение скопировано' : 'Не получилось скопировать — выделите текст вручную'));
[['#bTg', 'в чат'], ['#bWa', 'в чат'], ['#bMail', 'в письмо']].forEach(([id, where]) => $(id).addEventListener('click', async () => { if (await copy(bMsg)) toast('Текст скопирован — вставьте его ' + where); }));

/* quick-help sheet */
const sheet = $('#sheet');
$$('[data-open-sheet]').forEach(b => b.addEventListener('click', () => sheet.showModal()));
$('#sheetClose').addEventListener('click', () => sheet.close());
sheet.addEventListener('click', e => { if (e.target === sheet) sheet.close(); });
$$('[data-quick]', sheet).forEach(b => b.addEventListener('click', () => {
  sheet.close();
  const q = b.dataset.quick;
  if (q === 'breath') openTool('breath', { mode: 'double', min: 1, autostart: true });
  else openTool(q);
}));
})();
