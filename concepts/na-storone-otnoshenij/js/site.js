(function(){
  "use strict";
  const {$, $$, say, copyText, FORMAT} = App;

  /* ——— первый экран ——— */
  requestAnimationFrame(() => $(".hero").classList.add("hero-ready"));

  /* ——— режим читателя ——— */
  function setReader(mode){
    document.body.dataset.reader = mode;
    $$("[data-reader-btn]").forEach(b => b.setAttribute("aria-pressed", String(b.dataset.readerBtn === mode)));
    const active = $(mode === "doubt" ? ".for-doubt" : ".for-ready");
    $$(".letter").forEach(letter => letter.classList.toggle("is-active", letter === active));
  }
  $$("[data-reader-btn]").forEach(b => b.addEventListener("click", () => setReader(b.dataset.readerBtn)));
  $$(".letter").forEach(l => l.addEventListener("click", () => {
    if (!l.classList.contains("is-active")) setReader(l.classList.contains("for-doubt") ? "doubt" : "ready");
  }));
  setReader(new URLSearchParams(location.search).get("reader") === "doubt" ? "doubt" : "ready");

  /* ——— кнопка записи в шапке видна, только когда рядом нет другой главной кнопки записи ——— */
  const top = $(".top"), watch = [$(".hero-cta .btn"), $("#booking")].filter(Boolean), seen = new Set();
  if (watch.length && "IntersectionObserver" in window){
    top.classList.add("cta-wait");
    const io = new IntersectionObserver(es => {
      es.forEach(e => e.isIntersecting ? seen.add(e.target) : seen.delete(e.target));
      top.classList.toggle("cta-wait", seen.size > 0);
    }, {rootMargin: "-72px 0px 0px 0px"});
    watch.forEach(el => io.observe(el));
  }

  /* ——— мобильное меню ——— */
  const burger = $(".burger"), nav = $("#nav");
  burger.addEventListener("click", () => {
    burger.setAttribute("aria-expanded", String(nav.classList.toggle("open")));
  });
  const closeNav = () => {
    nav.classList.remove("open");
    burger.setAttribute("aria-expanded", "false");
  };
  $$("#nav a").forEach(a => a.addEventListener("click", closeNav));
  document.addEventListener("keydown", e => {
    if (e.key === "Escape" && nav.classList.contains("open")){ closeNav(); burger.focus(); }
  });

  /* ——— плавное раскрытие блоков <details> ——— */
  const intent = new WeakMap(), running = new WeakMap();
  function setOpen(d, open){
    const sum = d.querySelector("summary"), cs = getComputedStyle(d);
    const from = d.offsetHeight;
    running.get(d)?.cancel();
    intent.set(d, open);
    d.style.overflow = "hidden";
    if (open) d.open = true;
    const to = open ? d.offsetHeight : sum.offsetHeight + parseFloat(cs.borderTopWidth) + parseFloat(cs.borderBottomWidth);
    const anim = d.animate({height: [from + "px", to + "px"]}, {duration: 380, easing: "cubic-bezier(.2,.7,.1,1)"});
    running.set(d, anim);
    const done = finished => {
      if (running.get(d) !== anim) return;
      running.delete(d);
      d.style.overflow = "";
      if (finished && !open) d.open = false;
    };
    anim.onfinish = () => done(true);
    anim.oncancel = () => done(false);
  }
  const isOpen = d => intent.has(d) ? intent.get(d) : d.open;
  $$(".faq details").forEach(d => d.querySelector("summary").addEventListener("click", e => {
    e.preventDefault();
    setOpen(d, !isOpen(d));
  }));

  /* ——— темы запросов ——— */
  const note = $("#chip-note"), noteContent = $("#chip-content"), noteBtn = $("#chip-book");
  const narrowTopics = matchMedia("(max-width:820px)");
  let activeTopic = $('.chip[aria-pressed="true"]');
  function placeTopic(){
    if (narrowTopics.matches) activeTopic.after(note);
    else $(".scale").append(note);
  }
  $$(".chip").forEach(ch => ch.addEventListener("click", () => {
    activeTopic = ch;
    $$(".chip").forEach(c => c.setAttribute("aria-pressed", String(c === ch)));
    note.setAttribute("aria-labelledby", ch.id);
    noteContent.replaceChildren($("#topic-copy-" + ch.dataset.topic).content.cloneNode(true));
    noteBtn.dataset.book = ch.dataset.f;
    noteBtn.textContent = "Записаться: " + FORMAT[ch.dataset.f].name;
    $("#topic-format").textContent = "Подходящий формат: " + FORMAT[ch.dataset.f].name;
    placeTopic();
    noteContent.animate([{opacity: .4, transform: "translateY(6px)"}, {opacity: 1, transform: "translateY(0)"}], {duration: 280, easing: "ease-out"});
  }));
  $$(".topic-group").forEach(group => {
    group.querySelector("summary").addEventListener("click", event => {
      event.preventDefault();
      const opening = !isOpen(group);
      $$(".topic-group").forEach(other => { if (other !== group && isOpen(other)) setOpen(other, false); });
      setOpen(group, opening);
      if (opening && !group.contains(activeTopic)) group.querySelector(".chip").click();
    });
  });
  narrowTopics.addEventListener("change", placeTopic);
  placeTopic();

  /* ——— вкладки форматов ——— */
  const tabs = $$(".tab"), tabsBox = $(".tabs");
  const ind = document.createElement("span");
  ind.className = "tab-ind";
  ind.setAttribute("aria-hidden", "true");
  tabsBox.append(ind);
  function moveInd(){
    const on = tabs.find(t => t.getAttribute("aria-selected") === "true");
    ind.style.setProperty("--x", on.offsetLeft + "px");
    ind.style.setProperty("--w", on.offsetWidth + "px");
    tabsBox.classList.add("has-ind");
  }
  function selectTab(tab, focus){
    tabs.forEach(t => {
      const on = t === tab;
      t.setAttribute("aria-selected", String(on));
      t.tabIndex = on ? 0 : -1;
      $("#" + t.getAttribute("aria-controls")).hidden = !on;
    });
    moveInd();
    if (focus) tab.focus();
  }
  moveInd();
  addEventListener("resize", moveInd);
  if (document.fonts) document.fonts.ready.then(moveInd);
  tabs.forEach((t, i) => {
    t.addEventListener("click", () => selectTab(t));
    t.addEventListener("keydown", e => {
      const target = {
        Home: tabs[0],
        End: tabs[tabs.length - 1],
        ArrowRight: tabs[(i + 1) % tabs.length],
        ArrowLeft: tabs[(i - 1 + tabs.length) % tabs.length]
      }[e.key];
      if (!target) return;
      e.preventDefault();
      selectTab(target, true);
    });
  });

  /* ——— запуск тестов ——— */
  $$("[data-test]").forEach(button => button.addEventListener("click", () => {
    const run = App.tests[button.dataset.test];
    if (run){ run(); return; }
    $("#test-status").textContent = "Тест «" + button.closest(".test").querySelector("h3").textContent + "» пока недоступен.";
    say("Этот тест пока недоступен");
  }));

  /* ——— быстрый выход: Esc дважды ——— */
  function leave(){
    document.title = "Погода";
    location.replace("https://yandex.ru/pogoda");
  }
  let lastEsc = 0;
  document.addEventListener("keydown", e => {
    if (e.key !== "Escape") return;
    const now = Date.now();
    if (now - lastEsc < 700) leave();
    lastEsc = now;
  });

  /* ——— штамп на соглашении «ставится», когда документ виден ——— */
  const docEl = $(".doc");
  if (docEl && "IntersectionObserver" in window){
    new IntersectionObserver((es, o) => es.forEach(e => {
      if (e.isIntersecting){ docEl.classList.add("in"); o.disconnect(); }
    }), {threshold: .5}).observe(docEl);
  } else if (docEl) docEl.classList.add("in");
})();
