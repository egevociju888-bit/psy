(function(){
  "use strict";
  const {mk} = App;

  const DES = {
    scale: ["Хочу", "Может быть", "Нет"],
    marks: ["+", "?", "–"],
    groups: [
      {name: "Разговор", items: [
        "Говорить о\u00a0сексе спокойно: не\u00a0после ссоры и\u00a0не\u00a0в\u00a0постели.",
        "Говорить во\u00a0время близости, что нравится, а\u00a0что нет.",
        "Обсуждать фантазии, не\u00a0обязательно воплощая их.",
        "Договориться о\u00a0слове или знаке «пауза», который сразу всё останавливает.",
        "Планировать близость заранее: свидание, вечер, выходные.",
        "Откровенные сообщения друг другу в\u00a0течение дня."]},
      {name: "Нежность", items: [
        "Объятия, поцелуи и\u00a0прикосновения без\u00a0продолжения в\u00a0секс.",
        "Долгие поцелуи, как в\u00a0начале отношений.",
        "Массаж друг другу.",
        "Принимать душ или ванну вместе.",
        "Спать без\u00a0одежды.",
        "Ласки без\u00a0цели дойти до\u00a0оргазма."]},
      {name: "Ласки", items: [
        "Больше времени на\u00a0прелюдию.",
        "Ласки руками.",
        "Оральные ласки.",
        "Взаимная мастурбация.",
        "Мастурбация на\u00a0глазах у\u00a0партнёра.",
        "Масла и\u00a0смазки для\u00a0ласк.",
        "Секс-игрушки вдвоём."]},
      {name: "Разнообразие", items: [
        "Новые позы.",
        "Секс не\u00a0только в\u00a0спальне, но\u00a0и\u00a0в\u00a0других местах дома.",
        "Секс утром или днём, а\u00a0не\u00a0только вечером.",
        "Медленный секс без\u00a0спешки и\u00a0без\u00a0цели.",
        "Быстрый секс по\u00a0настроению.",
        "Секс при\u00a0свете или при\u00a0свечах.",
        "Анальные ласки."]},
      {name: "Игра и\u00a0фантазия", items: [
        "Ролевые игры и\u00a0сценарии.",
        "Эротическое бельё или костюмы.",
        "Повязка на\u00a0глаза.",
        "Лёгкое ограничение движений по\u00a0договорённости, с\u00a0возможностью остановиться в\u00a0любой момент.",
        "Игры во\u00a0власть: один ведёт, другой следует, роли можно менять.",
        "Читать или слушать эротические истории вместе.",
        "Смотреть эротику вместе.",
        "Близость в\u00a0поездке или в\u00a0отеле."]},
      {name: "Ритм и\u00a0условия", items: [
        "Заниматься сексом чаще, чем сейчас.",
        "Вечера без\u00a0телефонов и\u00a0экранов.",
        "Разговор и\u00a0объятия после близости.",
        "Право сказать «не\u00a0сегодня» без\u00a0объяснений и\u00a0обид.",
        "Пересматривать этот список раз в\u00a0несколько месяцев.",
        "Обсудить желания и\u00a0сложности со\u00a0специалистом."]}
    ]
  };

  const items = DES.groups.flatMap((g, gi) => g.items.map(t => ({g: gi, t})));
  const ROUNDS = ["Первый участник", "Второй участник"];

  let mode = "pair", round = 0, got = [[], []];

  const q = App.quiz({
    p: "des", items, scale: DES.scale, badges: DES.marks,
    panels: ["intro", "gate", "quiz", "handoff", "safe", "result"],
    focus: "age", againOpens: true,
    eyebrow: it => DES.groups[it.g].name,
    prefix: () => mode === "pair" ? ROUNDS[round] + " · " : "",
    clear: ["rows", "opts", "overall", "start-hint", "note", "age-err", "legend"],
    onReset(){
      mode = "pair"; round = 0; got = [[], []];
      q.el("age").checked = false;
    },
    onFinish(answers){
      got[round] = answers.slice();
      if (mode === "pair" && round === 0) handoff();
      else showResult();
    }
  });
  const {el} = q;

  function begin(m){
    const err = el("age-err");
    err.textContent = "";
    if (!el("age").checked){
      err.textContent = "Тест только для\u00a0совершеннолетних. Отметьте, что всем участникам есть 18\u00a0лет.";
      el("age").focus();
      return;
    }
    mode = m;
    round = 0;
    if (m === "pair") openGate(); else q.start();
  }
  function openGate(){
    el("eyebrow").textContent = "Перед началом";
    el("gate-title").textContent = ROUNDS[round];
    q.show("gate");
    el("gate-title").focus({preventScroll: true});
  }
  function handoff(){
    el("opts").replaceChildren();
    el("legend").textContent = "";
    el("eyebrow").textContent = "Передайте устройство";
    el("bar").style.width = "0%";
    q.show("handoff");
    el("handoff-title").focus({preventScroll: true});
  }
  function stop(){
    got = [[], []];
    el("eyebrow").textContent = "Безопасность";
    q.show("safe");
    el("safe-title").focus({preventScroll: true});
  }

  function section(title, rows){
    return [mk("h5", "vh", title), App.vlist(rows.map(r => App.vitem(r.it.t, [["", DES.groups[r.it.g].name]])))];
  }

  function showResult(){
    const out = el("rows");
    out.replaceChildren();
    const pair = mode === "pair";
    if (pair){
      const [a, b] = got;
      const ok = items.map((it, k) => ({it, x: a[k], y: b[k]})).filter(o => o.x !== 3 && o.y !== 3);
      const both = ok.filter(o => o.x === 1 && o.y === 1);
      const talk = ok.filter(o => !(o.x === 1 && o.y === 1));
      el("rtitle").textContent = "Что подходит вам обоим";
      el("overall").textContent = "Совпало пунктов: " + ok.length + " из\u00a0" + items.length + ".";
      if (both.length) out.append(...section("Оба хотят", both));
      if (talk.length) out.append(...section("Можно обсудить условия", talk));
      el("start-hint").textContent = both.length
        ? "С\u00a0чего начать: выберите один-два пункта из\u00a0первой группы и\u00a0поговорите о\u00a0них. Остальное не\u00a0к\u00a0спеху."
        : ok.length
          ? "С\u00a0чего начать: выберите один пункт и\u00a0обсудите, при\u00a0каких условиях он подошёл\u00a0бы вам обоим."
          : "Совпадающих пунктов нет. Это не\u00a0значит, что вам не\u00a0о\u00a0чем говорить: ответы меняются со\u00a0временем, а\u00a0список\u00a0— лишь часть близости. Это можно обсудить на\u00a0встрече.";
      el("note").textContent = "Пункты без\u00a0совпадения скрыты и\u00a0нигде не\u00a0сохраняются. Если вы отметили «хочу», а\u00a0пункта здесь нет, значит, партнёру это сейчас не\u00a0подходит. Это не\u00a0оценка вас.";
      el("book").dataset.book = "couple";
    } else {
      const rows = v => items.map((it, k) => ({it, v: got[0][k]})).filter(o => o.v === v);
      const want = rows(1), maybe = rows(2);
      el("rtitle").textContent = "Что интересно вам";
      el("overall").textContent = "Хочу: " + want.length + ". Может быть: " + maybe.length + ".";
      if (want.length) out.append(...section("Хочу", want));
      if (maybe.length) out.append(...section("Может быть, при\u00a0условиях", maybe));
      el("start-hint").textContent = want.length || maybe.length
        ? "С\u00a0чего начать: выберите пункт, о\u00a0котором проще всего заговорить, и\u00a0начните с\u00a0него."
        : "Вы не\u00a0отметили ни\u00a0одного пункта. Это нормально: ответы меняются со\u00a0временем, а\u00a0список не\u00a0обязан содержать ваше желание.";
      el("note").textContent = "Этот список видите только вы. Делиться им или нет\u00a0— решать вам.";
      el("book").dataset.book = "sex";
    }
    got = [[], []];
    q.showResult();
  }

  el("pair").addEventListener("click", () => begin("pair"));
  el("solo").addEventListener("click", () => begin("solo"));
  el("gate-yes").addEventListener("click", () => q.start());
  el("gate-no").addEventListener("click", stop);
  el("second").addEventListener("click", () => { round = 1; openGate(); });
  el("safe-close").addEventListener("click", () => q.dlg.close());

  App.tests.desires = q.open;
})();
