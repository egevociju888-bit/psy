'use strict';
(function(){


  /* Плавное появление блоков */
  if ('IntersectionObserver' in window){
    const revealIO = new IntersectionObserver(es => es.forEach(e => {
      if (e.isIntersecting){ e.target.classList.add('is-in'); revealIO.unobserve(e.target); }
    }), {threshold:.05, rootMargin:'0px 0px -6% 0px'});
    document.querySelectorAll('.section-head,.format-grid,.not-doing,.start,.triage,.privacy-notes,.memo-list,.quiz-card,.faq-list,.form-card').forEach(el => {
      el.classList.add('reveal'); revealIO.observe(el);
    });
  } else {
    document.documentElement.classList.remove('js');
  }

  /* Ссылки-заглушки не прокручивают страницу к началу */
  document.addEventListener('click', e => {
    const a = e.target.closest('a[href="#"]');
    if (a) e.preventDefault();
  });

  /* Header */
  const header = document.getElementById('header');
  const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
  window.addEventListener('scroll', onScroll, {passive:true}); onScroll();

  const nav = document.getElementById('nav');
  const navToggle = document.getElementById('navToggle');
  navToggle.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    navToggle.setAttribute('aria-expanded', open);
    navToggle.textContent = open ? 'Закрыть' : 'Меню';
  });
  nav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
    nav.classList.remove('is-open'); navToggle.setAttribute('aria-expanded', false); navToggle.textContent = 'Меню';
  }));

  /* Two voices */
  const situations = [
    { tab:'Закрылся', parentWho:'Мама Миши', parent:'«Он стал грубым, сидит у себя в комнате и ничего не рассказывает.»',
      childWho:'Миша, 14 лет', child:'Что ни скажу — начнут воспитывать. Проще молчать.',
      me:'Помогаю подростку найти слова для того, что с ним происходит, а родителям — способ спрашивать так, чтобы хотелось отвечать.' },
    { tab:'Не хочет в школу', parentWho:'Папа Лизы', parent:'«Каждое утро слёзы и болит живот. Врачи говорят — здорова.»',
      childWho:'Лиза, 8 лет', child:'Вдруг меня вызовут к доске, я всё забуду, и все будут смеяться.',
      me:'Разбираемся, откуда тревога, и учимся с ней справляться через игру, тело и маленькие шаги. Вам даю план на утро.' },
    { tab:'Развод', parentWho:'Мама Сони', parent:'«Мы не ругаемся при ней. Кажется, она спокойно всё приняла.»',
      childWho:'Соня, 6 лет', child:'Если бы я была хорошая, папа бы не ушёл.',
      me:'Даю ребёнку безопасное место для чувств, а вам — слова, которые снимут с него вину. Помогаю договориться обоим родителям.' },
    { tab:'Весь в телефоне', parentWho:'Мама Кати', parent:'«Её не оторвать от телефона. Она живёт там, а не с нами.»',
      childWho:'Катя, 15 лет', child:'Там меня понимают. Дома я слышу только, что всё делаю не так.',
      me:'Ищем, что именно даёт подростку телефон, и как вернуть это в семью — без войны за гаджеты.' }
  ];
  const voices = document.getElementById('voices');
  const range = document.getElementById('voicesRange');
  const sitWrap = document.getElementById('situations');
  const els = {
    parentWho:document.getElementById('parentWho'), parentQuote:document.getElementById('parentQuote'),
    childWho:document.getElementById('childWho'), childQuote:document.getElementById('childQuote'),
    me:document.getElementById('meText')
  };
  const heroRight = document.getElementById('duo');

  function fill(s){
    els.parentWho.textContent = s.parentWho; els.parentQuote.textContent = s.parent;
    els.childWho.textContent = s.childWho; els.childQuote.textContent = s.child;
    [...els.me.children].forEach((pEl, j) => pEl.classList.toggle('is-active', situations[j] === s));
  }
  situations.forEach((s,i) => {
    const b = document.createElement('button');
    b.type = 'button'; b.textContent = s.tab; b.setAttribute('aria-pressed', i===0);
    b.addEventListener('click', () => {
      sitWrap.querySelectorAll('button').forEach(x => x.setAttribute('aria-pressed', x===b));
      syncTabs();
      heroRight.classList.add('is-fading');
      setTimeout(() => { fill(s); heroRight.classList.remove('is-fading'); }, 220);
    });
    sitWrap.appendChild(b);
  });
  situations.forEach(s => {
    const pEl = document.createElement('p');
    const label = document.createElement('b');
    label.textContent = 'Что я делаю: ';
    pEl.append(label, s.me); els.me.appendChild(pEl);
  });
  fill(situations[0]);

  function setPos(p){
    p = Math.max(0, Math.min(100, p));
    voices.style.setProperty('--pos', p + '%');
    range.value = Math.round(p);
    range.setAttribute('aria-valuetext', p < 50 ? 'Больше слышно ребёнка' : 'Больше слышно родителя');
  }
  let introRunning = false, dragging = false;
  function stopIntro(){ introRunning = false; }

  voices.addEventListener('pointerdown', e => {
    dragging = true; stopIntro();
    voices.setPointerCapture(e.pointerId);
    move(e);
  });
  voices.addEventListener('pointermove', e => { if (dragging) move(e); });
  ['pointerup','pointercancel','lostpointercapture'].forEach(t => voices.addEventListener(t, () => dragging = false));
  function move(e){
    const r = voices.getBoundingClientRect();
    setPos((e.clientX - r.left) / r.width * 100);
  }
  range.addEventListener('input', () => { stopIntro(); setPos(+range.value); });
  setPos(88);

  // One orchestrated moment: the divider sweeps once to show the child's side
  function intro(){
    const legs = [[88, 14, 1500], [14, 88, 1300]];
    const ease = t => t < .5 ? 4*t*t*t : 1 - Math.pow(-2*t + 2, 3) / 2;
    introRunning = true;
    let leg = 0, start = null;
    function frame(ts){
      if (!introRunning) return;
      if (start === null) start = ts;
      const [from, to, dur] = legs[leg];
      const t = Math.min(1, (ts - start) / dur);
      setPos(from + (to - from) * ease(t));
      if (t === 1){
        leg++; start = null;
        if (leg >= legs.length){ introRunning = false; return; }
        return setTimeout(() => requestAnimationFrame(frame), 900);
      }
      requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  const io = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting){ setTimeout(intro, 1100); io.disconnect(); }
  }, {threshold:.6});
  io.observe(voices);

  /* Signs checklist */
  const signs = {
    small:[
      'Надолго вернулся к «малышовому» поведению: снова мочится ночью, просится на руки, говорит как младше',
      'Страхи мешают жить: не может заснуть один, не отпускает вас ни на шаг',
      'Частые и сильные истерики, которые долго не удаётся остановить',
      'В игре снова и снова повторяет одну и ту же пугающую сцену',
      'Сторонится детей, в саду почти не играет с другими',
      'Жалуется на боли в животе или голове, а врачи причин не находят',
      'Стал бить, кусать, ломать вещи чаще, чем раньше'
    ],
    school:[
      'Не хочет идти в школу, по утрам болит живот или голова',
      'Много тревожных «а что если…», переспрашивает, ищет подтверждения',
      'Плачет из-за оценок, переписывает, боится ошибиться',
      'Нет друзей или жалуется, что его обижают',
      'Вспышки злости, после которых сам расстраивается',
      'Изменились сон или аппетит',
      'Говорит о себе «я глупый», «у меня ничего не получается»'
    ],
    teen:[
      'Закрылся не только от вас, но и от друзей',
      'Пропал интерес к тому, что раньше увлекало',
      'Резко упала учёба или начались прогулы',
      'Спит почти весь день или почти не спит ночью',
      'Сильно изменилось отношение к еде или своей внешности',
      'Постоянные конфликты, раздражительность, вспышки',
      'Часто говорит о бессмысленности, усталости от всего'
    ]
  };
  const checklist = document.getElementById('checklist');
  const meter = document.getElementById('meter');
  const rTitle = document.getElementById('resultTitle');
  const rText = document.getElementById('resultText');
  const rCta = document.getElementById('resultCta');
  const resultBox = document.querySelector('.result');
  const resultCount = document.getElementById('resultCount');

  function renderSigns(age){
    checklist.innerHTML = '';
    signs[age].forEach((text, i) => {
      const li = document.createElement('li');
      li.innerHTML = `<label class="check"><input type="checkbox" data-i="${i}"><span>${text}</span></label>`;
      checklist.appendChild(li);
    });
    meter.innerHTML = signs[age].map(() => '<i></i>').join('');
    updateResult();
  }
  function updateResult(){
    const n = checklist.querySelectorAll('input:checked').length;
    resultCount.textContent = n ? 'Отмечено: ' + n + ' из ' + checklist.querySelectorAll('input').length : '';
    [...meter.children].forEach((d,i) => d.classList.toggle('on', i < n));
    meter.classList.toggle('is-high', n >= 3);
    resultBox.dataset.level = n === 0 ? 0 : (n <= 2 ? 1 : 2);
    if (n === 0){
      rTitle.textContent = 'Отметьте, что вы замечаете';
      rText.textContent = 'Результат появится здесь. Никакие данные не сохраняются и никуда не отправляются.';
      rCta.hidden = true;
    } else if (n <= 2){
      rTitle.textContent = 'Пока стоит понаблюдать';
      rText.textContent = 'Если это держится дольше 2–⁠3 недель или усиливается, приходите на консультацию для родителей. Иногда одной встречи хватает, чтобы понять, что происходит и что делать дома.';
      rCta.hidden = false;
    } else {
      rTitle.textContent = 'Стоит поговорить со специалистом';
      rText.textContent = 'Несколько признаков сразу — повод не ждать, что пройдёт само. Начнём со встречи с вами, без ребёнка: разберём, что происходит, и решим, нужна ли ему работа с психологом.';
      rCta.hidden = false;
    }
  }
  checklist.addEventListener('change', updateResult);
  const ageTabs = document.getElementById('ageTabs');
  ageTabs.addEventListener('click', e => {
    const b = e.target.closest('button'); if (!b) return;
    selectAge(b);
  });
  function selectAge(b, focus){
    ageTabs.querySelectorAll('button').forEach(x => { x.setAttribute('aria-selected', x===b); x.tabIndex = x===b ? 0 : -1; });
    document.getElementById('agePanel').setAttribute('aria-labelledby', b.id);
    renderSigns(b.dataset.age);
    syncTabs();
    if (focus) b.focus();
  }
  ageTabs.addEventListener('keydown', e => {
    const tabs = [...ageTabs.querySelectorAll('button')], i = tabs.indexOf(document.activeElement);
    if (i < 0) return;
    const next = {ArrowRight:(i+1)%tabs.length, ArrowLeft:(i+tabs.length-1)%tabs.length, Home:0, End:tabs.length-1}[e.key];
    if (next === undefined) return;
    e.preventDefault(); selectAge(tabs[next], true);
  });
  renderSigns('small');

  /* Что остаётся между нами: примеры и маршрут */
  const levels = {
    inner:{ title:'Останется между нами', flow:'ничего не передаётся', parent:'Родители получают совет, а не слова ребёнка' },
    middle:{ title:'Родители узнают главное, в общих словах', flow:'только общее, без подробностей', parent:'Рассказываю, как поддержать ребёнка' },
    outer:{ title:'Я обязана сказать', flow:'сообщаю ради безопасности', parent:'Подключаю взрослых для защиты ребёнка' }
  };
  const cases = [
    { q:'Мне страшно, что меня вызовут к доске', level:'inner',
      text:'Такие переживания и есть материал для работы. Родителям я их не пересказываю, а подсказываю, как поддержать ребёнка дома, не раскрывая его слов.' },
    { q:'Я поссорился с лучшим другом', level:'inner',
      text:'Истории про друзей и ссоры остаются в кабинете. Родители узнают лишь то, что поможет им быть рядом, если ребёнок сам захочет рассказать.' },
    { q:'Мне кажется, мама любит брата больше', level:'inner',
      text:'Чувства к семье самые личные. Я не передаю их, но могу помочь ребёнку найти слова, если он захочет поговорить с вами.' },
    { q:'Каждое утро болит живот перед школой', level:'middle',
      text:'Расскажу вам о главном: с чем ребёнку сейчас трудно и как вести себя по утрам. Без его слов и подробностей.' },
    { q:'Я хочу, чтобы папа перестал кричать', level:'middle',
      text:'Помогаю ребёнку подготовиться и сказать это самому. Если ему страшно, на встрече с вами я буду рядом.' },
    { q:'Я думаю о том, чтобы причинить себе вред', level:'outer',
      text:'Здесь безопасность важнее тайны. Сначала я скажу ребёнку, что должна сообщить, и мы вместе решим, как это сделать.' },
    { q:'Мне делают больно, и я боюсь об этом говорить', level:'outer',
      text:'Если ребёнок пострадал от насилия, я обязана сообщить, чтобы его защитили. Он знает об этом с первой встречи, и я не делаю это за его спиной.' }
  ];
  const caseList = document.getElementById('caseList');
  const routeEl = document.getElementById('route');
  const tTitle = document.getElementById('tTitle');
  const tText = document.getElementById('tText');
  const flowLabel = document.getElementById('flowLabel');
  const triageResult = document.getElementById('triageResult');
  const groups = {};
  ['inner','middle','outer'].forEach((lv, n) => {
    const g = document.createElement('div');
    g.className = 'case-group'; g.dataset.level = lv;
    g.innerHTML = `<p class="case-group__title"><b aria-hidden="true">${n+1}</b><span>${levels[lv].title}</span></p>`;
    caseList.appendChild(g); groups[lv] = g;
  });
  cases.forEach((c, i) => {
    const b = document.createElement('button');
    b.type = 'button'; b.className = 'case'; b.dataset.level = c.level; b.dataset.i = i;
    b.textContent = '«' + c.q + '»';
    b.setAttribute('aria-pressed', i === 0);
    b.addEventListener('click', () => {
      showCase(i);
      if (window.matchMedia('(max-width:900px)').matches) triageResult.scrollIntoView({block:'nearest', behavior: 'smooth'});
    });
    groups[c.level].appendChild(b);
  });
  function showCase(i){
    const c = cases[i], l = levels[c.level];
    caseList.querySelectorAll('.case').forEach(b => b.setAttribute('aria-pressed', +b.dataset.i === i));
    triageResult.dataset.level = c.level;
    routeEl.dataset.level = c.level;
    flowLabel.textContent = l.flow;
    document.getElementById('parentRouteNote').textContent = l.parent;
    document.getElementById('zoneChip').textContent = 'Зона ' + (['inner','middle','outer'].indexOf(c.level) + 1);
    tTitle.textContent = l.title;
    tText.textContent = c.text;
    triageResult.classList.remove('swap'); void triageResult.offsetWidth; triageResult.classList.add('swap');
  }
  showCase(0);

  /* Memos */
  const memos = [
    { id:'divorce', tag:'Развод', tone:'sun', title:'Как сказать ребёнку о разводе',
      summary:'Что сказать в первом разговоре, как снять с ребёнка чувство вины и каких ошибок избежать.',
      meta:'Для родителей детей 4–⁠14 лет, 4 минуты',
      body:`<p class="memo-lead">Детям нужна не идеальная формулировка, а ощущение, что взрослые справляются и мир остаётся предсказуемым.</p>
      <ol>
        <li><b>Скажите вместе, если это возможно.</b> Заранее договоритесь о словах, чтобы ваши версии не расходились. Выберите спокойное время — не перед сном и не перед школой.</li>
        <li><b>Короткая правда по возрасту.</b> «Мы с папой решили жить в разных домах. Это решение взрослых, и мы приняли его вместе». Подробности конфликта ребёнку не нужны.</li>
        <li><b>Прямо снимите вину.</b> «Ты ни в чём не виноват, и ты не мог бы это изменить». Повторите это не раз: дошкольники почти всегда ищут причину в себе.</li>
        <li><b>Объясните, что изменится в быту.</b> Где он будет жить, когда увидит второго родителя, что останется прежним — школа, кружки, бабушка по выходным.</li>
        <li><b>Не делайте ребёнка посредником.</b> Не передавайте через него сообщения, не просите выбирать и не говорите при нём плохо о втором родителе.</li>
        <li><b>Ждите вопросов позже.</b> Они могут появиться через неделю или месяц. Возврат к «малышовому» поведению в первые месяцы — частая и обычно временная реакция.</li>
      </ol>
      <div class="memo-when"><h3>Когда стоит обратиться</h3><p>Если через 2–⁠3 месяца ребёнок всё ещё плохо спит, часто плачет, винит себя или отказывается видеться с одним из родителей. Или если вам самим трудно говорить об этом спокойно — с этим тоже можно прийти на консультацию для родителей.</p></div>` },
    { id:'anxiety', tag:'Тревога', tone:'deep', title:'Как распознать тревожность у ребёнка',
      summary:'Детская тревога редко звучит как «мне тревожно». Где её искать и что помогает дома.',
      meta:'Для родителей детей 4–⁠17 лет, 5 минут',
      body:`<p class="memo-lead">Детская тревога редко звучит как «мне тревожно». Чаще её видно в теле и в поведении.</p>
      <ul>
        <li><b>Тело.</b> Живот или голова болят без медицинской причины — особенно перед школой, поездкой, выступлением.</li>
        <li><b>Избегание.</b> Отказывается от того, что раньше любил, придумывает поводы не идти.</li>
        <li><b>«А что если…»</b> Много тревожных вопросов, просит снова и снова подтвердить, что всё будет хорошо.</li>
        <li><b>Сон.</b> Долго засыпает, приходит ночью, боится оставаться в комнате один.</li>
        <li><b>Злость.</b> У детей тревога часто выглядит как раздражительность и вспышки.</li>
        <li><b>Перфекционизм.</b> Плачет из-за четвёрки, переписывает, боится начинать, чтобы не ошибиться.</li>
      </ul>
      <h3>Что помогает дома</h3>
      <ul>
        <li>Назовите чувство: «Похоже, тебе страшно. Так бывает». Уговоры «не бойся» тревогу не снимают.</li>
        <li>Не убирайте всё пугающее, а делите на маленькие шаги. Полное избегание делает страх сильнее.</li>
        <li>Замечайте свою тревогу: дети считывают её раньше, чем слова.</li>
      </ul>
      <div class="memo-when"><h3>Когда стоит обратиться</h3><p>Если тревога мешает ходить в школу, спать, общаться с друзьями, и это длится дольше месяца.</p></div>` },
    { id:'teen', tag:'Подростки', tone:'sage', title:'Если подросток закрылся',
      summary:'Как остаться рядом, когда на вопросы отвечают «нормально» и закрывают дверь.',
      meta:'Для родителей подростков 12–⁠17 лет, 4 минуты',
      body:`<p class="memo-lead">Отдаление в 12–⁠15 лет — часть взросления. Задача не вернуть всё как было, а остаться рядом так, чтобы к вам можно было прийти.</p>
      <ol>
        <li><b>Разговаривайте «сбоку».</b> В машине, на прогулке, за готовкой. Лицом к лицу подростку часто слишком напряжённо.</li>
        <li><b>Интересуйтесь его миром, а не оценками.</b> Музыка, игры, друзья. Спросите, что ему в этом нравится, и выслушайте.</li>
        <li><b>Меньше советов.</b> «Понимаю», «звучит тяжело», «а ты что думаешь?» открывают разговор. Совет в первую минуту его закрывает.</li>
        <li><b>Признавайте свои ошибки.</b> «Я вчера был резок, извини» даёт подростку право ошибаться тоже.</li>
        <li><b>Договаривайтесь, а не запрещайте.</b> Правила, которые обсудили вместе, выполняются лучше.</li>
      </ol>
      <div class="memo-when"><h3>Когда стоит обратиться</h3><p>Если подросток закрылся не только от вас, но и от друзей, пропал интерес ко всему, изменились сон и аппетит, звучат мысли о бессмысленности. Если он говорит о смерти или наносит себе повреждения — обращайтесь сразу.</p></div>` },
    { id:'first', tag:'Первый визит', tone:'sun', title:'Как подготовить ребёнка к первой встрече с психологом',
      summary:'Что сказать, чтобы поход к психологу не выглядел наказанием или обманом.',
      meta:'Для родителей детей 4–⁠17 лет, 3 минуты',
      body:`<p class="memo-lead">От того, как вы объясните, зачем идти к психологу, во многом зависит, как пройдёт первая встреча.</p>
      <ol>
        <li><b>Скажите правду.</b> «Психолог — это человек, который помогает разобраться с чувствами и трудностями». Не говорите «идём к тёте поиграть».</li>
        <li><b>Не используйте как угрозу.</b> Фраза «будешь так себя вести — поведу к психологу» превращает помощь в наказание.</li>
        <li><b>Объясните, что с ним всё в порядке.</b> «Нам всем сейчас непросто, и мы хотим, чтобы стало легче».</li>
        <li><b>Скажите, что вы тоже участвуете.</b> Ребёнку спокойнее, когда «чинят» не только его.</li>
        <li><b>Подростку дайте выбор.</b> Время, очно или онлайн, одна пробная встреча. Право решить, продолжать ли, — тоже его.</li>
      </ol>
      <div class="memo-when"><h3>Если ребёнок отказывается</h3><p>Не тащите силой. Приходите сначала сами — часто после этого что-то меняется и дома.</p></div>` },
    { id:'school', tag:'Школа', tone:'sage', title:'Ребёнок не хочет идти в школу',
      summary:'Как отличить каприз от тревоги и что делать по утрам, чтобы стало легче всем.',
      meta:'Для родителей детей 7–⁠14 лет, 4 минуты',
      body:`<p class="memo-lead">Отказ от школы почти всегда имеет причину: нагрузка, конфликт, страх ошибиться. Задача — не уговорить, а понять, что стоит за «не хочу».</p>
      <ol>
        <li><b>Спрашивайте вечером, а не утром.</b> Утром все спешат и напряжены. Вечером, в спокойной обстановке, ребёнок скорее расскажет, что его пугает.</li>
        <li><b>Верьте телу.</b> Боль в животе или голове при тревоге настоящая. Но и оставлять дома каждый раз не стоит: избегание усиливает страх.</li>
        <li><b>Узнайте, что происходит в школе.</b> Поговорите с учителем: как ребёнок ведёт себя на уроках и переменах, нет ли конфликтов и насмешек.</li>
        <li><b>Сделайте утро предсказуемым.</b> Одежда и портфель с вечера, один и тот же порядок действий, без спешки и споров на ходу.</li>
        <li><b>Договоритесь о маленьком шаге.</b> Дойти до школы, остаться на первый урок, написать вам с перемены. Отмечайте каждую попытку, а не только результат.</li>
      </ol>
      <div class="memo-when"><h3>Когда стоит обратиться</h3><p>Если ребёнок не ходит в школу больше двух недель, утро каждый день заканчивается слезами или есть подозрение, что его обижают. При травле сначала обратитесь в школу, а затем приходите на консультацию.</p></div>` },
    { id:'anger', tag:'Злость', tone:'deep', title:'Вспышки злости: что делать сразу и потом',
      summary:'Как пережить бурю без крика и чему научить ребёнка, когда он успокоится.',
      meta:'Для родителей детей 4–⁠12 лет, 4 минуты',
      body:`<p class="memo-lead">Во время вспышки ребёнок не слышит доводов. Сначала важно помочь ему успокоиться, а учить и обсуждать — потом.</p>
      <ol>
        <li><b>Начните с себя.</b> Понизьте голос, замедлитесь. Спокойный взрослый — самое сильное, что есть у ребёнка в этот момент.</li>
        <li><b>Позаботьтесь о безопасности.</b> Уберите то, чем можно навредить, и отойдите на расстояние. В пике не читайте нотации и не спорьте.</li>
        <li><b>Назовите чувство коротко.</b> «Ты очень злишься». Одной фразы достаточно, объяснения подождут.</li>
        <li><b>Разделяйте чувство и поступок.</b> «Злиться можно, бить нельзя». Чувства не запрещают, ограничивают только действия.</li>
        <li><b>Поговорите, когда всё стихло.</b> Через 20–⁠30 минут или позже: что случилось, что он почувствовал, как можно поступить иначе.</li>
        <li><b>Придумайте план вместе.</b> Что делать, когда накрывает: уйти в свой уголок, подышать, помять мягкий мяч, нарисовать злость.</li>
      </ol>
      <div class="memo-when"><h3>Когда стоит обратиться</h3><p>Если вспышки случаются каждый день, ребёнок причиняет вред себе или другим, долго не может успокоиться или после них сильно переживает и винит себя.</p></div>` }
  ];
  const memoList = document.getElementById('memoList');
  const filters = document.getElementById('filters');
  const tags = ['Все', ...new Set(memos.map(m => m.tag))];
  tags.forEach((t,i) => {
    const b = document.createElement('button');
    b.type = 'button'; b.textContent = t; b.setAttribute('aria-pressed', i===0);
    b.addEventListener('click', () => {
      filters.querySelectorAll('button').forEach(x => x.setAttribute('aria-pressed', x===b));
      memoList.querySelectorAll('li').forEach(li => li.hidden = !(t === 'Все' || li.dataset.tag === t));
    });
    filters.appendChild(b);
  });
  memos.forEach(m => {
    const li = document.createElement('li');
    li.dataset.tag = m.tag;
    li.dataset.tone = m.tone;
    li.innerHTML = `<button class="memo-btn" type="button" data-id="${m.id}">
      <span class="memo-tab" data-tone="${m.tone}" aria-hidden="true"></span>
      <span class="memo-tag">${m.tag}</span>
      <span class="memo-text"><span class="memo-title">${m.title}</span><span class="memo-sum">${m.summary}</span><small>${m.meta}</small></span>
      <span class="memo-open" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg></span>
    </button>`;
    memoList.appendChild(li);
  });
  const dialog = document.getElementById('memoDialog');
  const memoBody = document.getElementById('memoBody');
  const memoMeta = document.getElementById('memoMeta');
  memoList.addEventListener('click', e => {
    const b = e.target.closest('.memo-btn'); if (!b) return;
    const m = memos.find(x => x.id === b.dataset.id);
    memoMeta.textContent = m.meta;
    memoBody.innerHTML = `<h2 id="memoTitle">${m.title}</h2>${m.body}
      <div class="memo-cta"><a class="btn" href="#booking" data-close>Записаться на консультацию</a></div>`;
    if (typeof dialog.showModal === 'function') dialog.showModal(); else dialog.setAttribute('open','');
    memoBody.scrollTop = 0;
  });
  document.getElementById('memoClose').addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', e => {
    if (e.target === dialog || e.target.closest('[data-close]')) dialog.close();
  });

  /* Booking form */
  const ageSel = document.getElementById('fAge');
  for (let a = 4; a <= 17; a++){
    const o = document.createElement('option');
    o.value = a; o.textContent = a + ' ' + (a%10===1 && a!==11 ? 'год' : (a%10>=2 && a%10<=4 && (a<12 || a>14)) ? 'года' : 'лет');
    ageSel.appendChild(o);
  }
  const adult = document.createElement('option'); adult.value = '18'; adult.textContent = '18 лет и старше'; ageSel.appendChild(adult);

  const ageHint = document.getElementById('ageHint');
  ageSel.addEventListener('change', () => {
    const v = ageSel.value; let msg = '';
    if (v === 'u4') msg = 'С детьми младше 4 лет я работаю через консультации для родителей. Отметьте этот формат ниже — или оставьте «Пока не знаю».';
    else if (+v >= 4 && +v <= 11) msg = 'Работаю с детьми этого возраста индивидуально и вместе с семьёй.';
    else if (+v >= 12 && +v <= 17) msg = 'Подростку важно, чтобы решение прийти было и его тоже. Если он против — начнём со встречи с вами.';
    else if (v === '18') msg = 'Я работаю с детьми и подростками до 18 лет. Оставьте заявку — порекомендую проверенного коллегу для взрослых.';
    ageHint.textContent = msg; ageHint.hidden = !msg;
    clearError('age');
  });

  const chipTopics = ['Тревога и страхи','Трудности в школе','Развод родителей','Вспышки злости','Нет друзей','Подросток отдалился','Потеря близкого','Проблемы со сном'];
  const chips = document.getElementById('chips');
  const req = document.getElementById('fRequest');
  chipTopics.forEach(t => {
    const b = document.createElement('button');
    b.type = 'button'; b.textContent = '+ ' + t;
    b.addEventListener('click', () => {
      if (b.classList.contains('used')) return;
      b.classList.add('used'); b.textContent = t;
      req.value = (req.value.trim() ? req.value.trim().replace(/[.,]?$/, '') + ', ' : '') + t.toLowerCase();
      req.focus(); req.setSelectionRange(req.value.length, req.value.length);
      clearError('request');
    });
    chips.appendChild(b);
  });

  const form = document.getElementById('bookingForm');
  const formAlert = document.getElementById('formAlert');
  const contactInput = document.getElementById('fContact');
  const contactLabel = document.getElementById('lContact');
  const contactHint = document.getElementById('hContact');
  const contactErr = document.getElementById('eContact');
  const submitBtn = document.getElementById('submitBtn');
  const FIELD_LABEL = {name:'имя', contact:'контакт', age:'возраст ребёнка', request:'описание', consent:'согласие'};
  const CHANNELS = {
    'Телефон':{label:'Номер телефона', type:'tel', mode:'tel', ac:'tel', ph:'+7(000)000-00-00', hint:'Перезвоню в рабочее время.', err:'Введите номер полностью, например +7(000)000-00-00.'},
    'Telegram':{label:'Ник в Telegram', type:'text', mode:'text', ac:'off', ph:'Ваш ник, начиная с @', hint:'Напишу вам в Telegram.', err:'Укажите ник в Telegram латиницей, от 5 символов.'}
  };
  const channel = () => form.elements.channel.value;
  const contactValues = new Map();
  let activeChannel = null;

  function setChannel(){
    const nextChannel = channel();
    if (activeChannel !== null) contactValues.set(activeChannel, contactInput.value);
    const c = CHANNELS[nextChannel];
    contactInput.type = c.type; contactInput.inputMode = c.mode; contactInput.autocomplete = c.ac;
    contactInput.placeholder = c.ph; contactInput.spellcheck = false; contactInput.autocapitalize = 'none';
    if (activeChannel !== null) contactInput.value = contactValues.get(nextChannel) || '';
    activeChannel = nextChannel;
    contactLabel.textContent = c.label; contactHint.textContent = c.hint; contactErr.textContent = c.err;
    clearError('contact');
  }
  function fieldEl(name){ return form.querySelector(`[data-field="${name}"]`); }
  function setError(name){
    fieldEl(name).classList.add('has-error');
    const ctl = fieldEl(name).querySelector('input, select, textarea'); if (ctl) ctl.setAttribute('aria-invalid','true');
  }
  function clearError(name){
    const f = fieldEl(name); if (!f) return;
    f.classList.remove('has-error');
    const ctl = f.querySelector('input, select, textarea'); if (ctl) ctl.removeAttribute('aria-invalid');
  }
  function phoneDigits(v){ return v.replace(/\D/g,''); }
  function isPhone(v){
    const d = phoneDigits(v);
    if (/^\s*\+/.test(v)) return d.length >= 10 && d.length <= 15;
    return (d.length === 11 && /^[78]/.test(d)) || (d.length === 10 && d[0] === '9');
  }
  function normTelegram(v){
    v = v.trim().replace(/^https?:\/\/(t\.me|telegram\.me)\//i,'').replace(/^@+/,'').replace(/[/?#].*$/,'');
    return v ? '@' + v : '';
  }
  function formatPhone(v){
    const d = phoneDigits(v);
    if (/^\s*\+/.test(v) && !d.startsWith('7')) return v.trim();
    if (!d || d.length > 11) return v.trim();
    const r = /^[78]/.test(d) ? d.slice(1) : d.startsWith('9') ? d : null;
    if (r === null || r.length > 10) return v.trim();
    return '+7' + (r ? '(' + r.slice(0,3) : '') + (r.length > 3 ? ')' + r.slice(3,6) : '') +
      (r.length > 6 ? '-' + r.slice(6,8) : '') + (r.length > 8 ? '-' + r.slice(8,10) : '');
  }
  contactInput.addEventListener('input', e => {
    if (channel() !== 'Телефон' || e.isComposing) return;
    const raw = contactInput.value;
    const caret = contactInput.selectionStart ?? raw.length;
    let digitsBefore = phoneDigits(raw.slice(0, caret)).length;
    if (!/^\s*\+/.test(raw) && phoneDigits(raw).startsWith('9')) digitsBefore++;
    const formatted = formatPhone(raw);
    if (formatted === raw) return;
    let nextCaret = formatted.length;
    if (caret < raw.length){
      let seen = 0;
      nextCaret = 0;
      while (nextCaret < formatted.length && seen < digitsBefore){
        if (/\d/.test(formatted[nextCaret])) seen++;
        nextCaret++;
      }
    }
    contactInput.value = formatted;
    contactInput.setSelectionRange(nextCaret, nextCaret);
  });
  function valid(name){
    const f = form.elements;
    switch (name){
      case 'name': return f.name.value.trim().length >= 2;
      case 'contact': return channel() === 'Телефон' ? isPhone(f.contact.value) : /^@[A-Za-z][A-Za-z0-9_]{4,31}$/.test(normTelegram(f.contact.value));
      case 'age': return !!f.age.value;
      case 'request': return f.request.value.trim().length >= 10;
      case 'consent': return f.consent.checked;
    }
    return true;
  }
  function check(name, soft){
    const ok = valid(name);
    if (ok) clearError(name); else if (!soft) setError(name);
    return ok;
  }
  ['name','contact','request'].forEach(n => {
    const el = form.elements[n];
    el.addEventListener('input', () => { if (fieldEl(n).classList.contains('has-error')) check(n, true); });
    el.addEventListener('blur', () => {
      if (n === 'contact'){
        el.value = channel() === 'Телефон' ? formatPhone(el.value) : normTelegram(el.value);
      }
      if (el.value.trim()) check(n);
    });
  });
  form.elements.age.addEventListener('blur', () => { if (form.elements.age.value) clearError('age'); });
  form.elements.consent.addEventListener('change', () => clearError('consent'));
  form.querySelectorAll('input[name="channel"]').forEach(r => r.addEventListener('change', setChannel));
  setChannel();

  const plural = (n, a, b, c) => n % 10 === 1 && n % 100 !== 11 ? a : (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 12 || n % 100 > 14)) ? b : c;
  form.addEventListener('submit', e => {
    e.preventDefault();
    const bad = ['name','contact','age','request','consent'].filter(n => !check(n));
    if (bad.length){
      formAlert.textContent = `Проверьте ${bad.length} ${plural(bad.length,'поле','поля','полей')}: ${bad.map(n => FIELD_LABEL[n]).join(', ')}.`;
      const first = fieldEl(bad[0]).querySelector('input, select, textarea');
      first && first.focus();
      return;
    }
    formAlert.textContent = '';
    const data = Object.fromEntries(new FormData(form).entries());
    const verb = channel() === 'Telegram' ? 'напишу' : 'позвоню';
    document.getElementById('successText').textContent =
      `${data.name.trim()}, спасибо. Я ${verb} вам в течение рабочего дня. Если ситуация срочная, не ждите — звоните по телефону доверия 8 800 2000 122 или 112.`;
    form.hidden = true;
    const s = document.getElementById('success'); s.hidden = false; s.focus();
    s.scrollIntoView({block:'nearest', behavior:'smooth'});
  });
  /* Окно с источниками */
  const srcD = document.getElementById('srcDialog');
  document.getElementById('srcOpen').addEventListener('click', () => {
    if (typeof srcD.showModal === 'function') srcD.showModal(); else srcD.setAttribute('open','');
  });
  document.getElementById('srcClose').addEventListener('click', () => srcD.close());
  srcD.addEventListener('click', e => { if (e.target === srcD) srcD.close(); });

  /* Опросник для родителей */
  const QT = {
    res:{ n:'Ваши силы',
      low:'Усталость накопилась, и на ребёнка остаётся меньше ресурса. Это частая реакция на длительную нагрузку, а не личная слабость.',
      steps:[
        'Выберите одно, что реально восстанавливает вас, и закладывайте это в неделю как обязательное дело.',
        'Просите конкретную помощь: не «помогите», а «заберите ребёнка в субботу на три часа».',
        'Если усталость держится несколько недель, обсудите её со специалистом. Раньше обратиться проще, чем позже.'],
      memo:null },
    emo:{ n:'Эмоции ребёнка',
      low:'Вы чаще стараетесь быстро успокоить или решить проблему, чем сначала побыть с чувством ребёнка.',
      steps:[
        'Назовите чувство одной фразой: «Похоже, тебе обидно».',
        'Сначала поддержите, потом решайте. Решение лучше работает, когда ребёнок почувствовал, что его поняли.',
        'Отделяйте чувство от поступка: «Злиться можно, бить нельзя».'],
      memo:'anxiety' },
    lim:{ n:'Границы и последовательность',
      low:'Правила и реакции пока зависят от ситуации и настроения, и ребёнку трудно предугадать, чего ждать.',
      steps:[
        'Выберите два-три правила, которые действительно важны, и держитесь их спокойно.',
        'Предупреждайте о последствии заранее и выполняйте его без долгих лекций.',
        'Договаривайтесь о главном с другим взрослым, а разногласия обсуждайте без ребёнка.'],
      memo:'anger' },
    con:{ n:'Контакт и самостоятельность',
      low:'Спокойного времени вдвоём и места для собственных решений ребёнка пока немного.',
      steps:[
        'Найдите 10–⁠15 минут в день без телефонов и без воспитания: занятие выбирает ребёнок.',
        'Отмечайте усилие конкретными словами: «Ты не бросил, хотя было трудно».',
        'Отдайте ему одно небольшое безопасное решение, например что надеть или с чего начать уроки.'],
      memo:'teen' }
  };
  const QORDER = ['res','emo','lim','con'];
  const QQ = [
    { t:'emo', q:'Ребёнок вернулся из школы расстроенный и молчит. Что вы обычно делаете?', o:[
      {x:'Предлагаете отвлечься: чай, мультик, прогулка', v:1},
      {x:'Говорите, что видите: «Похоже, день был тяжёлым», — и оставляете ему время', v:2},
      {x:'Говорите, что переживать не из-за чего, и переходите к урокам', v:0}] },
    { t:'lim', q:'Вы договорились, что телефон убирается в 21:00. Ребёнок просит ещё «пять минут». Как вы обычно поступаете?', o:[
      {x:'Соглашаетесь: спорить нет сил', v:1},
      {x:'Повышаете голос или забираете телефон без разговора', v:0},
      {x:'Спокойно напоминаете договорённость и остаётесь при ней', v:2}] },
    { t:'res', q:'К концу дня в последние недели вы чаще всего чувствуете…', o:[
      {x:'Усталость сильнее обычной, а отдых почти не помогает', v:1},
      {x:'Что на ребёнка совсем не осталось сил', v:0},
      {x:'Обычную усталость: после отдыха силы возвращаются', v:2}] },
    { t:'con', q:'Как часто у вас получается побыть с ребёнком вдвоём, без телефонов и дел, просто рядом?', o:[
      {x:'Раз-два в неделю', v:1},
      {x:'Почти каждый день, хотя бы 10–⁠15 минут', v:2},
      {x:'Редко: всегда что-то отвлекает', v:0}] },
    { t:'emo', q:'Ребёнок плачет из-за того, что вам кажется мелочью: сломалась игрушка, не взяли в игру. Что вы обычно делаете?', o:[
      {x:'Просите успокоиться: ничего страшного не произошло', v:0},
      {x:'Сразу предлагаете решение: починим, найдём другую игру', v:1},
      {x:'Говорите «Понимаю, обидно» и только потом думаете, что делать', v:2}] },
    { t:'res', q:'Бывает ли так, что вы срываетесь на ребёнка, а потом сами пугаетесь своей реакции?', o:[
      {x:'Да, это повторяется, и мне от этого не по себе', v:0, alert:true},
      {x:'Нет, обычно удаётся вовремя остановиться', v:2},
      {x:'Иногда: потом извиняюсь и разбираюсь, что случилось', v:1}] },
    { t:'lim', q:'Ребёнок нарушил правило, о котором вы предупреждали. Что обычно происходит дальше?', o:[
      {x:'Вы долго объясняете, почему так нельзя, иногда по несколько раз', v:1},
      {x:'Реакция зависит от вашего настроения и усталости', v:0},
      {x:'Наступает последствие, о котором вы говорили заранее, без долгих лекций', v:2}] },
    { t:'con', q:'Ребёнок хочет сделать что-то по-своему: одежда, порядок в комнате, уроки. Это безопасно. Что вы обычно делаете?', o:[
      {x:'Объясняете, как лучше, и часто делаете по-своему', v:1},
      {x:'Даёте решить самому и помогаете, если попросит', v:2},
      {x:'Проще сделать самим, чем ждать и переделывать', v:0}] },
    { t:'emo', q:'Ребёнок говорит, что злится на вас. Как вы обычно реагируете?', o:[
      {x:'Напоминаете, что со взрослыми так разговаривать нельзя', v:0},
      {x:'Стараетесь быстро уладить: извиняетесь или уступаете, чтобы злость прошла', v:1},
      {x:'Выслушиваете, не споря, и объясняете свою позицию позже', v:2}] },
    { t:'lim', q:'Взрослые в семье по-разному смотрят на правила. Как это выглядит для ребёнка?',
      hint:'Если вы растите ребёнка один, подумайте о других взрослых рядом: бабушках, втором родителе, школе.', o:[
      {x:'Иногда ребёнок идёт к тому, кто разрешит', v:1},
      {x:'Основные правила общие, расхождения обсуждаются без ребёнка', v:2},
      {x:'Правила часто меняются, и ребёнок не знает, чего ждать', v:0}] },
    { t:'res', q:'Есть ли у вас время для себя и люди, на которых можно опереться?', o:[
      {x:'Бывает редко, в основном справляюсь сам(а)', v:1},
      {x:'Да, хотя бы иногда, и есть на кого опереться', v:2},
      {x:'Нет: времени на себя нет, помощи тоже', v:0}] },
    { t:'con', q:'Когда вы замечаете в ребёнке что-то хорошее: усилие, доброту, терпение, что вы делаете?', o:[
      {x:'Хвалите в общем: «Молодец»', v:1},
      {x:'Обычно молчите: так и должно быть', v:0},
      {x:'Говорите конкретно: «Ты не бросил, хотя было трудно»', v:2}] }
  ];
  const QN = QQ.length;
  const qCard = document.getElementById('quizCard');
  const qViews = { intro:document.getElementById('quizIntro'), run:document.getElementById('quizRun'), hand:document.getElementById('quizHand'), result:document.getElementById('quizResult') };
  const qMain = document.getElementById('quizMain');
  const qBack = document.getElementById('quizBack');
  const qNote = document.getElementById('quizNote');
  const NOTE_INTRO = qNote.textContent;
  const qs = { phase:'intro', mode:'solo', who:0, i:0, ans:[[],[]], focusName:'' };

  const qScore = w => { const s = {emo:0,lim:0,con:0,res:0}; qs.ans[w].forEach((a,k) => { s[QQ[k].t] += QQ[k].o[a].v; }); return s; };
  const qAlert = w => qs.ans[w].some((a,k) => QQ[k].o[a].alert);
  const qBand = s => s <= 2 ? 'Здесь труднее' : s <= 4 ? 'Рабочая зона' : 'Опора';
  const qBar = (s,c,t) => `<span class="qz-bar" style="--c:${c}" aria-hidden="true">${[1,2,3,4,5,6].map(k => `<i class="${k<=s?'on':''}"></i>`).join('')}</span>`;
  const qMemoTitle = id => (memos.find(m => m.id === id) || {}).title || '';

  function qUpdateMain(){
    qBack.setAttribute('aria-disabled', qs.phase === 'run' && qs.i > 0 ? 'false' : 'true');
  }

  const QCOL = ['var(--sage-700)','var(--sun-500)'];
  const QTXT = ['#fff','var(--ink)'];
  const qWho = w => `<span class="qz-who" style="--c:${QCOL[w]};--t:${QTXT[w]}" aria-hidden="true">${w+1}</span>`;

  function qRenderRun(){
    const q = QQ[qs.i], sel = qs.ans[qs.who][qs.i], pair = qs.mode === 'pair';
    const turn = pair ? `<p class="qz-turn">${qWho(qs.who)}Отвечает ${qs.who ? 'второй' : 'первый'} родитель, за себя</p>` : '';
    qViews.run.innerHTML =
      `<div class="qz-progress" style="--pc:${QCOL[pair ? qs.who : 0]}" aria-hidden="true">${QQ.map((_,k) => `<i class="${k<=qs.i?'on':''}"></i>`).join('')}</div>
       <p class="qz-meta">Вопрос ${qs.i+1} из ${QN}</p>${turn}
       <fieldset class="qz-set" aria-describedby="qzKb"><legend class="qz-q">${q.q}</legend>
         ${q.hint ? `<p class="qz-hint">${q.hint}</p>` : ''}
         <span class="sr-only" id="qzKb">Выберите вариант стрелками и нажмите Enter, чтобы перейти к следующему вопросу.</span>
         <div class="qz-opts" style="margin-top:22px">${q.o.map((o,k) =>
           `<label class="qz-opt"><input type="radio" name="qzo" value="${k}"${sel===k?' checked':''}><span>${o.x}</span></label>`).join('')}</div>
       </fieldset>`;
  }

  function qRenderHand(){
    const w = qs.who;
    qViews.hand.innerHTML = w === 0
      ? `<span class="eyebrow">Отвечаете по очереди</span>
         <h2 class="qz-hand-title">${qWho(0)}Сначала отвечает первый родитель</h2>
         <p>Каждый отвечает отдельно и только за себя. Пока один отвечает, второй не смотрит на экран и не подсказывает. Ответы первого будут скрыты, результаты вы увидите вместе в конце.</p>`
      : `<span class="eyebrow">Передайте устройство</span>
         <h2 class="qz-hand-title">${qWho(1)}Теперь отвечает второй родитель</h2>
         <p>Первый родитель закончил, его ответы скрыты. Передайте телефон или компьютер второму: он отвечает сам, не заглядывая в чужие ответы.</p>`;
  }

  function qRow(name, lines, focus){
    return `<li class="qz-row${focus?' is-focus':''}"><b>${name}</b><div class="qz-lines">${lines}</div></li>`;
  }

  function qRenderResult(){
    const pair = qs.mode === 'pair';
    const s0 = qScore(0), s1 = pair ? qScore(1) : null;
    const avg = t => pair ? (s0[t] + s1[t]) / 2 : s0[t];
    let focus = QORDER[0];
    QORDER.forEach(t => { if (avg(t) < avg(focus)) focus = t; });
    const allGood = avg(focus) >= 5;
    qs.focusName = allGood ? '' : QT[focus].n;

    const rows = QORDER.map(t => {
      if (!pair){
        return qRow(QT[t].n, `<div class="qz-line">${qBar(s0[t],'var(--sage-700)')}<span class="lbl">${qBand(s0[t])}<span class="sr-only">, ${s0[t]} из 6</span></span></div>`, !allGood && t === focus);
      }
      const d = Math.abs(s0[t] - s1[t]);
      return qRow(QT[t].n,
        `<div class="qz-line"><span class="qz-who" style="--c:var(--sage-700)">1</span>${qBar(s0[t],'var(--sage-700)')}<span class="lbl">${qBand(s0[t])}<span class="sr-only">, ${s0[t]} из 6</span></span></div>
         <div class="qz-line"><span class="qz-who" style="--c:var(--sun-500);--t:var(--ink)">2</span>${qBar(s1[t],'var(--sun-500)')}<span class="lbl">${qBand(s1[t])}<span class="sr-only">, ${s1[t]} из 6</span></span></div>
         <p class="qz-diff">${d >= 2 ? 'Вы видите это по-разному' : 'Ваши ответы близки'}</p>`, !allGood && t === focus);
    }).join('');

    let title, lead;
    if (allGood){
      title = pair ? 'У вас обоих много опоры' : 'Опоры у вас много';
      lead = 'По всем четырём темам отмечены устойчивые привычки. Это не значит, что трудностей нет, но у вас есть на что опереться. Если что-то беспокоит в поведении ребёнка, это повод для разговора со специалистом, а не оценка вас как родителя.';
    } else {
      title = pair ? `Вместе вам сейчас труднее всего: ${QT[focus].n.toLowerCase()}` : `Сейчас труднее всего: ${QT[focus].n.toLowerCase()}`;
      lead = QT[focus].low;
    }

    let gap = '';
    if (pair){
      let big = QORDER[0];
      QORDER.forEach(t => { if (Math.abs(s0[t]-s1[t]) > Math.abs(s0[big]-s1[big])) big = t; });
      if (Math.abs(s0[big]-s1[big]) >= 2){
        gap = `<p class="qz-lead">Сильнее всего ответы расходятся в теме «${QT[big].n}». Это не значит, что кто-то ошибается: у каждого свой опыт. Можно начать разговор с вопроса: «Когда в последний раз это было непросто?»</p>`;
      }
    }

    const focusBox = allGood ? '' :
      `<div class="qz-focus"><h4>С чего можно начать</h4>
        <ol>${QT[focus].steps.map(x => `<li>${x}</li>`).join('')}</ol>
        <div class="qz-btns">
          ${QT[focus].memo ? `<button class="btn btn--small btn--ghost" type="button" data-memo="${QT[focus].memo}">Памятка: ${qMemoTitle(QT[focus].memo)}</button>` : ''}
          <button class="btn btn--small btn--ghost" type="button" data-addreq="1">Добавить тему в заявку</button>
          <a class="btn btn--small" href="#booking">Записаться на консультацию</a>
        </div></div>`;
    const bookOnly = allGood ? `<div class="qz-btns"><a class="btn btn--small" href="#booking">Записаться на консультацию</a></div>` : '';

    const alerted = qAlert(0) || (pair && qAlert(1));
    const alertBox = alerted ?
      `<div class="qz-alert"><p><b>Об одном из ответов.</b> ${pair ? 'Кто-то из вас отметил' : 'Вы отметили'}, что срывы повторяются и пугают. Это бывает при длительном перенапряжении, и с этим можно работать. Не ждите, пока станет хуже: запишитесь на консультацию для родителей. Если есть риск навредить ребёнку или себе, звоните <a class="tel" href="#">112</a>. Телефон доверия для детей и родителей: <a class="tel" href="#">8 800 2000 122</a>.</p></div>` : '';

    qViews.result.innerHTML =
      `<span class="eyebrow">Результат${pair ? ' для двоих' : ''}</span>
       <h3 class="qz-title" tabindex="-1">${title}</h3>
       <p class="qz-lead">${lead}</p>
       ${pair ? '<p class="qz-legend">1 — первый родитель, 2 — второй</p>' : ''}
       <ul class="qz-rows">${rows}</ul>
       ${gap}${alertBox}${focusBox}${bookOnly}
       <p class="qz-fine">Это не диагностика и не оценка родительства: шкала от 0 до 6 по каждой теме показывает привычки, а не «правильность».</p>`;
  }

  function qRender(moveFocus){
    Object.keys(qViews).forEach(k => { qViews[k].hidden = k !== qs.phase; });
    qCard.classList.toggle('is-active', qs.phase !== 'intro');
    qBack.hidden = qs.phase !== 'run';
    qMain.hidden = qs.phase === 'run';
    if (qs.phase === 'run') qRenderRun();
    if (qs.phase === 'hand') qRenderHand();
    if (qs.phase === 'result') qRenderResult();
    qMain.classList.toggle('btn--ghost', qs.phase === 'result');
    qMain.textContent = qs.phase === 'intro' ? 'Начать опросник'
      : qs.phase === 'hand' ? (qs.who ? 'Начать второму родителю' : 'Начать первому родителю')
      : 'Пройти заново';
    qNote.textContent = qs.phase === 'intro' ? NOTE_INTRO
      : qs.phase === 'run' ? 'Выберите вариант, и откроется следующий вопрос. С клавиатуры: стрелки и Enter.'
      : qs.phase === 'result' ? 'Ответы не сохраняются: при обновлении страницы всё сбросится.' : '';
    qUpdateMain();
    if (moveFocus) qFocus();
  }

  function qFocus(){
    let el = null;
    if (qs.phase === 'run') el = qViews.run.querySelector('input:checked') || qViews.run.querySelector('input[name="qzo"]');
    else if (qs.phase === 'result') el = qViews.result.querySelector('.qz-title');
    else el = qMain;
    if (el) el.focus({preventScroll:true});
  }

  function qAdvance(){
    if (qs.phase !== 'run') return;
    if (qs.i < QN - 1) qs.i++;
    else if (qs.mode === 'pair' && qs.who === 0){ qs.who = 1; qs.i = 0; qs.phase = 'hand'; }
    else qs.phase = 'result';
    qRender(true);
  }

  function qChoose(k, instant){
    if (qs.phase !== 'run' || qs.lock) return;
    qs.ans[qs.who][qs.i] = k;
    const inp = qViews.run.querySelectorAll('input[name="qzo"]')[k];
    if (inp) inp.checked = true;
    if (instant) return qAdvance();
    qs.lock = true;
    setTimeout(() => { qs.lock = false; qAdvance(); }, 220);
  }

  qMain.addEventListener('click', () => {
    if (qs.phase === 'intro'){
      qs.mode = document.querySelector('input[name="quizMode"]:checked').value;
      qs.who = 0; qs.i = 0; qs.ans = [[],[]];
      qs.phase = qs.mode === 'pair' ? 'hand' : 'run';
    } else if (qs.phase === 'hand'){
      qs.i = 0; qs.phase = 'run';
    } else if (qs.phase === 'result'){
      qs.phase = 'intro';
    }
    qRender(true);
  });
  qBack.addEventListener('click', () => {
    if (qs.phase === 'run' && qs.i > 0 && !qs.lock){ qs.i--; qRender(false); }
  });
  qViews.run.addEventListener('click', e => {
    const lab = e.target.closest('.qz-opt');
    if (!lab || e.target.tagName === 'INPUT') return;
    const inp = lab.querySelector('input');
    if (!inp) return;
    e.preventDefault();
    qChoose(+inp.value, false);
  });
  qViews.run.addEventListener('keydown', e => {
    if (e.key === 'Enter' && e.target.name === 'qzo'){ e.preventDefault(); qChoose(+e.target.value, true); }
  });
  qViews.run.addEventListener('change', e => {
    if (e.target.name === 'qzo') qs.ans[qs.who][qs.i] = +e.target.value;
  });
  qViews.result.addEventListener('click', e => {
    const m = e.target.closest('[data-memo]');
    if (m){ const b = document.querySelector(`.memo-btn[data-id="${m.dataset.memo}"]`); if (b) b.click(); return; }
    const a = e.target.closest('[data-addreq]');
    if (a && a.getAttribute('aria-disabled') !== 'true'){
      const add = `Прошёл(ла) опросник для родителей. Сейчас труднее всего: ${qs.focusName.toLowerCase()}.`;
      req.value = req.value.trim() ? req.value.trim() + ' ' + add : add;
      clearError('request');
      a.textContent = 'Тема добавлена в заявку';
      a.setAttribute('aria-disabled','true');
    }
  });
  qRender();

  /* Скользящие индикаторы вкладок */
  function slide(group, active){
    if (!group || !active) return;
    group.style.setProperty('--x', active.offsetLeft + 'px');
    group.style.setProperty('--w', active.offsetWidth + 'px');
  }
  function syncTabs(){
    slide(sitWrap, sitWrap.querySelector('[aria-pressed="true"]'));
    slide(ageTabs, ageTabs.querySelector('[aria-selected="true"]'));
  }
  syncTabs();
  if ('ResizeObserver' in window){ const ro = new ResizeObserver(syncTabs); ro.observe(sitWrap); ro.observe(ageTabs); }
  if (document.fonts && document.fonts.ready) document.fonts.ready.then(syncTabs);

  /* Меню: Escape и подсветка текущего раздела */
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && nav.classList.contains('is-open')){
      nav.classList.remove('is-open'); navToggle.setAttribute('aria-expanded', false); navToggle.textContent = 'Меню'; navToggle.focus();
    }
  });
  if ('IntersectionObserver' in window){
    const links = [...nav.querySelectorAll('a[href^="#"]:not(.btn)')];
    const byId = new Map(links.map(a => [a.getAttribute('href').slice(1), a]));
    const spyIO = new IntersectionObserver(es => es.forEach(e => {
      if (!e.isIntersecting) return;
      links.forEach(a => a.removeAttribute('aria-current'));
      const a = byId.get(e.target.id); a && a.setAttribute('aria-current','true');
    }), {rootMargin:'-40% 0px -55% 0px'});
    document.querySelectorAll('main > section[id], section[id]').forEach(sec => spyIO.observe(sec));
  }

  /* Вопросы: плавное раскрытие */
  document.querySelectorAll('.faq details').forEach(d => {
    const body = d.querySelector('.faq-body');
    d.querySelector('summary').addEventListener('click', e => {
      e.preventDefault();
      const cur = body.getBoundingClientRect().height;
      if (d._a) d._a.cancel();
      const opening = !d.classList.contains('is-open');
      d.classList.toggle('is-open', opening);
      if (opening) d.open = true;
      const to = opening ? body.scrollHeight : 0;
      d._a = body.animate({height:[cur + 'px', to + 'px']}, {duration:320, easing:'cubic-bezier(.3,.7,.2,1)'});
      d._a.onfinish = () => { d._a = null; if (!opening) d.open = false; };
      d._a.oncancel = () => { d._a = null; };
    });
  });

  /* Нижняя панель на телефоне: прячем рядом с главными кнопками, формой и подвалом */
  const bar = document.getElementById('mobileBar');
  if (bar && 'IntersectionObserver' in window){
    const seen = new Set();
    const barIO = new IntersectionObserver(es => {
      es.forEach(e => e.isIntersecting ? seen.add(e.target) : seen.delete(e.target));
      bar.classList.toggle('is-hidden', seen.size > 0);
    }, {threshold:.1});
    ['.hero-actions','#resultCta','#quizCard','#booking','.footer'].forEach(q => { const t = document.querySelector(q); t && barIO.observe(t); });
  }
})();
