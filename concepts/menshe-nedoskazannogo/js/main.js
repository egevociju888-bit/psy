(() => {
'use strict';

const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];
const byId = id => document.getElementById(id);

/* ——— типографика ——— */
const NB = ' ';
const SHORT = /(^|[\s(«„])((?:в|во|к|ко|с|со|о|об|от|до|из|за|на|по|при|про|под|над|без|для|не|ни|же|ли|бы|да|а|и|но|я|ты|он|мы|вы|их|то|у)\s)/giu;
const typo = s => s
  .replace(SHORT, (m, a, b) => a + b.slice(0, -1) + NB)
  .replace(SHORT, (m, a, b) => a + b.slice(0, -1) + NB)
  .replace(/(\d)[ ]+(?=\p{L})/gu, '$1' + NB)
  .replace(/\s+([—–])/g, NB + '$1');

function typoTree(root) {
  const skip = new Set(['SCRIPT', 'STYLE', 'TEXTAREA', 'CODE', 'NOSCRIPT']);
  const w = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
    acceptNode: n => !n.nodeValue.trim() || skip.has(n.parentNode.nodeName) || n.parentNode.closest('[data-raw]')
      ? NodeFilter.FILTER_REJECT : NodeFilter.FILTER_ACCEPT
  });
  const nodes = [];
  while (w.nextNode()) nodes.push(w.currentNode);
  nodes.forEach(n => { n.nodeValue = typo(n.nodeValue); });
}

/* ——— уведомление и копирование ——— */
const toastEl = byId('toast');
const toastMsg = byId('toastMsg');
const toastBtn = byId('toastBtn');
let toastTimer = 0;
let toastAction = null;

function toast(msg, label, action, ms = 5000) {
  toastMsg.textContent = msg;
  toastAction = action || null;
  toastBtn.hidden = !label;
  toastBtn.textContent = label || '';
  toastEl.classList.add('is-on');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toastEl.classList.remove('is-on'), label ? Math.max(ms, 7000) : ms);
}

toastBtn.addEventListener('click', () => {
  const fn = toastAction;
  toastAction = null;
  toastEl.classList.remove('is-on');
  if (fn) fn();
});

async function copyText(text) {
  try {
    await navigator.clipboard.writeText(text);
    return true;
  } catch (e) {
    const t = document.createElement('textarea');
    t.value = text;
    t.setAttribute('readonly', '');
    t.style.cssText = 'position:fixed;top:0;left:0;opacity:0';
    document.body.appendChild(t);
    t.select();
    let ok = false;
    try { ok = document.execCommand('copy'); } catch (err) { ok = false; }
    t.remove();
    return ok;
  }
}

async function copyWithToast(text, okMsg) {
  const ok = await copyText(text);
  toast(ok ? okMsg : 'Не удалось скопировать. Выделите текст и скопируйте вручную.');
}

/* ——— шапка и навигация ——— */
function mountNav() {
  const top = byId('top');
  const nav = byId('nav');
  const burger = byId('burger');
  const links = $$('a[href^="#"]', nav).filter(a => !a.classList.contains('btn'));
  const targets = links.map(a => byId(a.getAttribute('href').slice(1))).filter(Boolean);

  const close = () => {
    nav.dataset.open = 'false';
    burger.setAttribute('aria-expanded', 'false');
  };

  burger.addEventListener('click', () => {
    const open = nav.dataset.open !== 'true';
    nav.dataset.open = String(open);
    burger.setAttribute('aria-expanded', String(open));
  });
  nav.addEventListener('click', e => { if (e.target.closest('a')) close(); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && nav.dataset.open === 'true') { close(); burger.focus(); }
  });
  document.addEventListener('click', e => {
    if (nav.dataset.open === 'true' && !top.contains(e.target)) close();
  });
  matchMedia('(min-width:1061px)').addEventListener('change', close);

  let queued = false;
  const update = () => {
    queued = false;
    top.classList.toggle('is-stuck', window.scrollY > 8);
    const y = window.scrollY + top.offsetHeight + 120;
    let cur = null;
    targets.forEach(t => { if (t.offsetTop <= y) cur = t; });
    links.forEach(a => {
      if (cur && a.getAttribute('href') === '#' + cur.id) a.setAttribute('aria-current', 'true');
      else a.removeAttribute('aria-current');
    });
  };
  const onScroll = () => { if (!queued) { queued = true; requestAnimationFrame(update); } };
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll);
  update();
}

/* ——— раскрывающиеся блоки ——— */
function mountAcc() {
  document.addEventListener('click', e => {
    if (e.target.closest('a[href="#"]')) e.preventDefault();
    const b = e.target.closest('.acc-btn, .hint-btn');
    if (!b) return;
    const panel = byId(b.getAttribute('aria-controls'));
    if (!panel) return;
    const open = b.getAttribute('aria-expanded') !== 'true';
    b.setAttribute('aria-expanded', String(open));
    panel.dataset.open = String(open);
  });
}

/* ——— разбор диалогов ——— */
const SITS = [
  {
    tab: 'Задержался и не написал',
    sub: 'про ожидание и тревогу',
    ctx: 'Он задержался на работе и не предупредил. Она весь вечер ждала с ужином.',
    before: [
      ['Она', 'Опять ты не написал. Тебе вообще всё равно, что я тут сижу и жду?'],
      ['Он', 'Началось. Я работаю, а не гуляю. Может, хоть раз спросишь, как у меня прошёл день?']
    ],
    alt: [
      ['Она', '[fact|Ты пришёл в одиннадцать, а я ждала с семи.] [feel|Мне было тревожно и обидно,] [need|потому что мне важно знать, что с тобой всё в порядке.] [ask|Напиши, пожалуйста, если задерживаешься больше чем на полчаса.]'],
      ['Он', '[feel|Мне неприятно, что ты так переживала.] [fact|Я был в запаре и не подумал о сообщении.] [ask|Давай так: я пишу, если задерживаюсь, а ты звонишь, если я долго молчу.]']
    ]
  },
  {
    tab: 'Дома больше молчим',
    sub: 'про близость',
    ctx: 'Выходные прошли тихо. Они говорили только о делах, и она чувствует дистанцию.',
    before: [
      ['Она', 'Мы живём как соседи. Ты вообще замечаешь, что мы не разговариваем?'],
      ['Он', 'Всё же нормально. Ты вечно что-то придумываешь.']
    ],
    alt: [
      ['Она', '[fact|На выходных мы говорили только о делах.] [feel|Мне одиноко,] [need|потому что мне важно чувствовать, что мы близки.] [ask|Давай в среду вечером посидим вдвоём без телефонов хотя бы двадцать минут?]'],
      ['Он', '[fact|Я правда не заметил, что мы так мало говорили.] [feel|Мне неловко от этого,] [need|мне тоже хочется, чтобы между нами было тепло.] [ask|Давай в среду. О чём ты хотела бы поговорить?]']
    ]
  },
  {
    tab: 'Телефон вместо разговора',
    sub: 'про внимание',
    ctx: 'Она рассказывает о работе, а он в это время смотрит в телефон.',
    before: [
      ['Она', 'Ты меня вообще слушаешь? Всё время в этом телефоне!'],
      ['Он', 'Слушаю я. Могу делать два дела сразу, не раздувай.']
    ],
    alt: [
      ['Она', '[fact|Когда я рассказывала про работу, ты смотрел в телефон.] [feel|Мне стало обидно,] [need|потому что для меня важно, чтобы меня слушали, когда я говорю о значимом.] [ask|Можешь отложить телефон, пока я рассказываю? Это минут десять.]'],
      ['Он', '[feel|Мне неприятно, что так вышло.] [fact|Я ждал рабочее сообщение.] [ask|Давай я буду класть телефон экраном вниз, а если нужно ответить, скажу об этом заранее.]']
    ]
  },
  {
    tab: 'Гости без звонка',
    sub: 'про личное пространство',
    ctx: 'Её родители приехали в субботу утром без предупреждения. Он хотел провести выходной спокойно.',
    before: [
      ['Он', 'Опять твои без звонка! Это вообще мой дом или проходной двор?'],
      ['Она', 'Это моя мама. Тебе что, нельзя потерпеть?']
    ],
    alt: [
      ['Он', '[fact|Твои родители пришли в субботу утром без звонка.] [feel|Я растерялся и разозлился,] [need|потому что мне важно иметь спокойное время в выходные.] [ask|Давай попросим их звонить хотя бы за час. Как тебе такой вариант?]'],
      ['Она', '[feel|Мне обидно слышать про «проходной двор»,] [need|для меня важно, чтобы мои близкие были здесь рады.] [fact|Но я понимаю, что ты не успел подготовиться.] [ask|Давай я поговорю с мамой, и мы выберем удобные дни.]']
    ]
  }
];

const MARK_RE = /\[(fact|feel|need|ask)\|([^\]]+)\]/g;

function marked(str) {
  const frag = document.createDocumentFragment();
  let last = 0;
  let i = 0;
  str.replace(MARK_RE, (m, kind, text, at) => {
    if (at > last) frag.append(typo(str.slice(last, at)));
    const s = document.createElement('span');
    s.className = 'mk mk--' + kind + ' mk--draw';
    s.style.setProperty('--i', String(i++));
    s.textContent = typo(text);
    frag.append(s);
    last = at + m.length;
    return m;
  });
  if (last < str.length) frag.append(typo(str.slice(last)));
  return frag;
}

function turn(who, content) {
  const li = document.createElement('li');
  li.className = 'dlg-turn';
  const w = document.createElement('div');
  w.className = 'dlg-who';
  w.textContent = who;
  const p = document.createElement('p');
  p.className = 'dlg-say';
  if (typeof content === 'string') p.textContent = typo('«' + content + '»');
  else p.append(content);
  li.append(w, p);
  return li;
}

function mountDialogues() {
  const tabsEl = byId('sitTabs');
  const panel = byId('sitPanel');
  const sheet = byId('sitSheet');
  const ctx = byId('sitCtx');
  const before = byId('sitBefore');
  const altLayer = byId('sitAlt');
  const alt = byId('sitAltTurns');
  const modeBtns = $$('.modes button');
  let cur = 0;
  let mode = 'before';

  const tabs = SITS.map((s, i) => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'dlg-tab';
    b.id = 'sitTab' + i;
    b.setAttribute('role', 'tab');
    b.setAttribute('aria-controls', 'sitPanel');
    const t = document.createElement('b');
    t.textContent = typo(s.tab);
    const sm = document.createElement('small');
    sm.textContent = s.sub;
    b.append(t, sm);
    b.addEventListener('click', () => select(i));
    tabsEl.append(b);
    return b;
  });

  function render() {
    const s = SITS[cur];
    ctx.textContent = typo(s.ctx);
    before.replaceChildren(...s.before.map(([w, t]) => turn(w, t)));
    alt.replaceChildren(...s.alt.map(([w, t]) => turn(w, marked(t))));
  }

  function select(i, focus) {
    cur = i;
    tabs.forEach((b, k) => {
      b.setAttribute('aria-selected', String(k === i));
      b.tabIndex = k === i ? 0 : -1;
    });
    panel.setAttribute('aria-labelledby', 'sitTab' + i);
    render();
    if (mode === 'alt') {
      altLayer.classList.remove('is-on');
      void altLayer.offsetWidth;
      altLayer.classList.add('is-on');
    }
    sheet.classList.remove('is-bloom');
    void sheet.offsetWidth;
    sheet.classList.add('is-bloom');
    if (focus) tabs[i].focus();
  }

  tabsEl.addEventListener('keydown', e => {
    const n = tabs.length;
    const map = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 };
    let next = null;
    if (e.key in map) next = (cur + map[e.key] + n) % n;
    else if (e.key === 'Home') next = 0;
    else if (e.key === 'End') next = n - 1;
    if (next === null) return;
    e.preventDefault();
    select(next, true);
  });

  modeBtns.forEach(b => b.addEventListener('click', () => {
    mode = b.dataset.mode;
    modeBtns.forEach(x => x.setAttribute('aria-pressed', String(x === b)));
    before.classList.toggle('is-on', mode === 'before');
    altLayer.classList.toggle('is-on', mode === 'alt');
  }));

  select(0);
}

/* ——— практика ——— */
const FEELINGS = ['тревожно', 'обидно', 'одиноко', 'грустно', 'страшно', 'досадно', 'неловко', 'тяжело', 'пусто', 'неспокойно'];
const EXAMPLE = {
  event: 'В четверг ты вернулся в одиннадцать вечера и не написал, что задерживаешься.',
  feel: ['тревожно', 'обидно'],
  feelText: '',
  need: 'знать, что с тобой всё в порядке',
  ask: 'Напиши мне, пожалуйста, если задерживаешься больше чем на полчаса.'
};

const tidy = s => s.replace(/\s+/g, ' ').trim();
const upFirst = s => s.charAt(0).toUpperCase() + s.slice(1);
const lowFirst = s => s.charAt(0).toLowerCase() + s.slice(1);
const bare = s => tidy(s).replace(/[.\s]+$/, '');
const sentence = s => {
  const t = tidy(s);
  if (!t) return '';
  return upFirst(/[.!?…]$/.test(t) ? t : t + '.');
};
const list = a => a.length < 2 ? a.join('') : a.slice(0, -1).join(', ') + ' и ' + a[a.length - 1];

function mountPractice() {
  const f = { event: byId('event'), feelText: byId('feelText'), need: byId('need'), ask: byId('ask') };
  const chipsEl = byId('feelChips');
  const draft = byId('draft');
  const intro = byId('optIntro');
  const outro = byId('optOutro');
  const status = byId('draftStatus');
  const progTxt = byId('progTxt');
  const readTime = byId('readTime');
  const segs = { fact: $('.seg--fact'), feel: $('.seg--feel'), ask: $('.seg--ask') };
  let dirty = false;

  const chips = FEELINGS.map(name => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'chip';
    b.textContent = name.charAt(0).toUpperCase() + name.slice(1);
    b.dataset.v = name;
    b.setAttribute('aria-pressed', 'false');
    chipsEl.append(b);
    return b;
  });

  const picked = () => chips.filter(c => c.getAttribute('aria-pressed') === 'true').map(c => c.dataset.v);
  const state = () => ({
    event: f.event.value, feel: picked(), feelText: f.feelText.value, need: f.need.value, ask: f.ask.value
  });
  const apply = s => {
    f.event.value = s.event;
    f.feelText.value = s.feelText;
    f.need.value = s.need;
    f.ask.value = s.ask;
    chips.forEach(c => c.setAttribute('aria-pressed', String(s.feel.includes(c.dataset.v))));
  };

  function build(s) {
    const fact = sentence(s.event);
    const feelParts = [];
    if (s.feel.length) feelParts.push('Мне ' + list(s.feel) + '.');
    if (tidy(s.feelText)) feelParts.push(sentence(s.feelText));
    const need = bare(s.need) ? 'Мне важно ' + lowFirst(bare(s.need)) + '.' : '';
    const ask = sentence(s.ask);
    const main = [fact, ...feelParts, need].filter(Boolean).join(' ');
    if (!main && !ask) return '';
    const out = [];
    if (intro.getAttribute('aria-pressed') === 'true') out.push('Я хочу спокойно поговорить с тобой об этом.');
    if (main) out.push(main);
    if (ask) out.push(ask);
    if (outro.getAttribute('aria-pressed') === 'true') out.push('Что ты об этом думаешь?');
    return out.join('\n\n');
  }

  function meta() {
    const text = draft.value.trim();
    const s = state();
    const done = {
      fact: !!tidy(s.event),
      feel: !!(s.feel.length || tidy(s.feelText) || tidy(s.need)),
      ask: !!tidy(s.ask)
    };
    Object.keys(done).forEach(k => segs[k].classList.toggle('on', done[k]));
    const n = Object.values(done).filter(Boolean).length;
    progTxt.textContent = 'Заполнено ' + n + ' из 3';
    status.textContent = dirty ? 'Изменено вручную' : (text ? 'Собирается из ответов' : 'Пока пусто');
    if (!text) readTime.textContent = 'Вслух: пока нечего читать';
    else {
      const words = text.split(/\s+/).length;
      readTime.textContent = 'Вслух: около ' + Math.max(5, Math.round(words / 2.2 / 5) * 5) + ' секунд';
    }
  }

  function refresh() {
    if (!dirty) {
      draft.value = build(state());
      draft.dispatchEvent(new Event('input', { bubbles: true }));
    }
    meta();
  }

  Object.values(f).forEach(el => el.addEventListener('input', refresh));
  chipsEl.addEventListener('click', e => {
    const c = e.target.closest('.chip');
    if (!c) return;
    c.setAttribute('aria-pressed', String(c.getAttribute('aria-pressed') !== 'true'));
    refresh();
  });
  [intro, outro].forEach(b => b.addEventListener('click', () => {
    b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true'));
    if (dirty) toast('Черновик изменён вручную. Нажмите «Собрать черновик», чтобы применить.');
    refresh();
  }));
  draft.addEventListener('input', e => {
    if (!e.isTrusted) return;
    dirty = true;
    meta();
  });

  $$('.ex').forEach(b => b.addEventListener('click', () => {
    const el = f[b.dataset.fill];
    if (!el) return;
    const txt = b.dataset.text;
    const prev = el.value;
    el.value = b.dataset.mode !== 'replace' && tidy(prev) ? tidy(prev) + ' ' + txt : txt;
    refresh();
    el.focus();
    toast('Подставлено', 'Отменить', () => { el.value = prev; refresh(); });
  }));

  const snapshot = () => ({ s: state(), text: draft.value, dirty });
  const restore = snap => {
    apply(snap.s);
    dirty = snap.dirty;
    draft.value = snap.text;
    draft.dispatchEvent(new Event('input', { bubbles: true }));
    meta();
  };

  byId('fillExample').addEventListener('click', () => {
    const snap = snapshot();
    apply(EXAMPLE);
    dirty = false;
    refresh();
    toast('Подставлен пример', 'Вернуть своё', () => restore(snap));
  });

  byId('btnBuild').addEventListener('click', () => {
    const snap = snapshot();
    dirty = false;
    refresh();
    if (!draft.value.trim()) { toast('Заполните хотя бы один из трёх вопросов'); return; }
    if (snap.dirty) toast('Черновик собран заново', 'Вернуть', () => restore(snap));
  });

  byId('btnCopy').addEventListener('click', () => {
    if (!draft.value.trim()) { toast('Черновик пока пуст'); return; }
    copyWithToast(draft.value, 'Черновик скопирован');
  });

  byId('btnClear').addEventListener('click', () => {
    if (!draft.value.trim() && !Object.values(state()).some(v => (Array.isArray(v) ? v.length : tidy(v)))) return;
    const snap = snapshot();
    apply({ event: '', feel: [], feelText: '', need: '', ask: '' });
    dirty = false;
    refresh();
    toast('Всё очищено', 'Вернуть', () => restore(snap));
  });

  refresh();
}

/* ——— форматы и обращение ——— */
const NAME_RE = /^[\p{L}][\p{L}\s'’-]{0,59}$/u;
const TIMES = ['Будни утром', 'Будни днём', 'Будни вечером', 'Выходные'];
const FMT = {
  solo: { want: 'индивидуальную встречу (50 минут)', hint: 'Индивидуальная встреча: 50 минут, 4 500 ₽.' },
  pair: { want: 'консультацию для пары (80 минут)', hint: 'Консультация для пары: 80 минут, 7 000 ₽.' }
};
const PLACE = { online: 'онлайн', offline: 'очно в Москве' };

function mountContact() {
  const radios = { solo: byId('f-solo'), pair: byId('f-pair') };
  const places = { online: byId('p-online'), offline: byId('p-offline') };
  const picks = $$('.fmt-pick');
  const hint = byId('fmtHint');
  const name = byId('name');
  const nameErr = byId('nameErr');
  const timeChips = byId('timeChips');
  const timeText = byId('timeText');
  const topic = byId('topic');
  const topicCount = byId('topicCount');
  const attachWrap = byId('attachWrap');
  const attach = byId('attach');
  const msg = byId('msg');
  const msgStatus = byId('msgStatus');
  const rebuildBtn = byId('msgRebuild');
  const draft = byId('draft');
  const consent = byId('consent');
  const consentErr = byId('consentErr');
  const hintDefault = hint.textContent;
  let dirty = false;

  const fmt = () => (radios.solo.checked ? 'solo' : radios.pair.checked ? 'pair' : '');
  const place = () => (places.online.checked ? 'online' : places.offline.checked ? 'offline' : '');

  const tChips = TIMES.map(t => {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = 'chip';
    b.textContent = t;
    b.setAttribute('aria-pressed', 'false');
    timeChips.append(b);
    return b;
  });

  function validName() {
    const v = tidy(name.value);
    return v && NAME_RE.test(v) ? v : '';
  }

  function checkName(show) {
    const v = tidy(name.value);
    const bad = !!v && !NAME_RE.test(v);
    name.setAttribute('aria-invalid', String(bad));
    if (bad && show) {
      nameErr.textContent = 'Имя может содержать только буквы, пробел и дефис.';
      nameErr.hidden = false;
    } else if (!bad) {
      nameErr.hidden = true;
      nameErr.textContent = '';
    }
  }

  function build() {
    const n = validName();
    const k = fmt();
    const lines = ['Здравствуйте, Вера!'];
    if (n) lines.push('Меня зовут ' + n + '.');
    const pl = place();
    lines.push('Хочу записаться на ' + (k ? FMT[k].want : 'консультацию') + (pl ? ', ' + PLACE[pl] : '') + '.');
    const times = tChips.filter(c => c.getAttribute('aria-pressed') === 'true').map(c => lowFirst(c.textContent));
    const tt = bare(timeText.value);
    if (times.length || tt) lines.push('Удобное время: ' + [times.join(', '), tt].filter(Boolean).join('; ') + '.');
    const tp = tidy(topic.value);
    if (tp) lines.push('Коротко о запросе: ' + tp);
    let out = lines.join('\n');
    const d = draft.value.trim();
    if (attach.checked && d) out += '\n\nЧерновик разговора, с которым хочу работать:\n' + d;
    out += '\n\nС уважением' + (n ? ',\n' + n : '');
    return out;
  }

  function syncAttach() {
    const has = !!draft.value.trim();
    attachWrap.hidden = !has;
    if (!has) attach.checked = false;
  }

  function refresh() {
    if (!dirty) msg.value = build();
    msgStatus.textContent = dirty ? 'Изменено вручную' : 'Обновляется по форме';
    rebuildBtn.hidden = !dirty;
  }

  function syncFormat(k) {
    if (k) radios[k].checked = true;
    else { radios.solo.checked = false; radios.pair.checked = false; }
    const cur = fmt();
    picks.forEach(b => {
      const on = b.dataset.fmt === cur;
      b.setAttribute('aria-pressed', String(on));
      $('span', b).textContent = on ? 'Выбрано' : 'Выбрать этот формат';
      $('.fmt-go', b.parentNode).hidden = !on;
      b.closest('.fmt-card').classList.toggle('is-selected', on);
    });
    hint.textContent = typo(cur ? FMT[cur].hint : hintDefault);
    refresh();
  }

  picks.forEach(b => b.addEventListener('click', () => {
    syncFormat(b.getAttribute('aria-pressed') === 'true' ? null : b.dataset.fmt);
  }));
  Object.values(radios).forEach(r => r.addEventListener('change', () => syncFormat(fmt())));
  Object.values(places).forEach(r => r.addEventListener('change', refresh));

  name.addEventListener('input', () => { checkName(false); refresh(); });
  name.addEventListener('blur', () => checkName(true));
  timeText.addEventListener('input', refresh);
  timeChips.addEventListener('click', e => {
    const c = e.target.closest('.chip');
    if (!c) return;
    c.setAttribute('aria-pressed', String(c.getAttribute('aria-pressed') !== 'true'));
    refresh();
  });
  topic.addEventListener('input', () => {
    topicCount.textContent = topic.value.length + ' из ' + topic.maxLength;
    refresh();
  });
  attach.addEventListener('change', refresh);
  draft.addEventListener('input', () => { syncAttach(); refresh(); });

  msg.addEventListener('input', e => {
    if (!e.isTrusted) return;
    dirty = true;
    refresh();
  });
  rebuildBtn.addEventListener('click', () => {
    const prev = msg.value;
    dirty = false;
    refresh();
    toast('Сообщение собрано заново', 'Вернуть', () => { dirty = true; msg.value = prev; refresh(); });
  });
  consent.addEventListener('change', () => {
    if (!consent.checked) return;
    consent.removeAttribute('aria-invalid');
    consentErr.hidden = true;
    consentErr.textContent = '';
  });
  byId('msgCopy').addEventListener('click', () => {
    checkName(true);
    if (name.getAttribute('aria-invalid') === 'true') { name.focus(); return; }
    if (!consent.checked) {
      consent.setAttribute('aria-invalid', 'true');
      consentErr.textContent = 'Чтобы скопировать сообщение, отметьте согласие на обработку персональных данных.';
      consentErr.hidden = false;
      consent.focus();
      return;
    }
    copyWithToast(msg.value, 'Сообщение скопировано');
  });
  byId('reqForm').addEventListener('submit', e => e.preventDefault());

  syncAttach();
  syncFormat(null);
}

/* ——— старт ——— */
function start() {
  typoTree(document.body);
  mountNav();
  mountAcc();
  mountDialogues();
  mountPractice();
  mountContact();
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
else start();
})();
