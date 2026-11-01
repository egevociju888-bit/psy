(function(){
  "use strict";
  const {mk, band, grow} = App;

  const VAL = {
    scale: ["Точно не\u00a0так", "Скорее не\u00a0так", "Пока не\u00a0знаю", "Скорее так", "Точно так"],
    domains: [
      {name: "Деньги", items: [
        {t: "Все доходы лучше складывать в\u00a0общий бюджет."},
        {t: "У\u00a0каждого должны быть личные деньги, на\u00a0которые не\u00a0нужно отчитываться."},
        {t: "Крупные покупки нужно обсуждать, даже если человек платит из\u00a0своих денег."},
        {t: "Кредит или ипотека\u00a0— нормальный способ получить желаемое раньше."},
        {t: "Копить важнее, чем тратить на\u00a0удовольствия сейчас."},
        {t: "Тот, кто зарабатывает заметно больше, вправе сильнее влиять на\u00a0траты общих денег."}]},
      {name: "Дети", items: [
        {t: "Мне важно, чтобы в\u00a0нашей семье были дети\u00a0— первый или ещё один."},
        {t: "С\u00a0решением о\u00a0детях не\u00a0стоит затягивать."},
        {t: "Заботой о\u00a0ребёнке оба родителя должны заниматься в\u00a0равной мере."},
        {t: "Один из\u00a0родителей может на\u00a0несколько лет остаться дома с\u00a0ребёнком."},
        {t: "Детям нужны чёткие правила и\u00a0дисциплина."},
        {t: "Решение о\u00a0детях можно принимать, только когда оба полностью к\u00a0нему готовы."}]},
      {name: "Свобода", items: [
        {t: "Мне нужно регулярное время отдельно от\u00a0партнёра: для\u00a0себя, друзей, увлечений."},
        {t: "Отпуск лучше проводить вместе, а\u00a0не\u00a0порознь."},
        {t: "Я\u00a0могу сказать партнёру «нет» в\u00a0важном для\u00a0меня вопросе и\u00a0не\u00a0бояться его реакции.", safe: true},
        {t: "Дружба с\u00a0людьми, которые могли\u00a0бы быть романтически интересны, нормальна и\u00a0не\u00a0требует отчёта."},
        {t: "Доступ к\u00a0телефону и\u00a0аккаунтам друг друга\u00a0— нормальная часть отношений."},
        {t: "Ради общих целей можно на\u00a0время отложить личные планы."}]},
      {name: "Семья", items: [
        {t: "Мнение родителей важно при\u00a0крупных решениях нашей семьи."},
        {t: "Мы должны регулярно видеться с\u00a0родственниками: семейные обеды, праздники."},
        {t: "Жить отдельно от\u00a0родителей важнее, чем сэкономить на\u00a0жилье."},
        {t: "Семейные традиции и\u00a0праздники важно соблюдать."},
        {t: "Вера или религиозные традиции должны быть частью нашей семейной жизни."},
        {t: "Если родственникам трудно, мы обязаны помогать им деньгами или временем."}]},
      {name: "Карьера", items: [
        {t: "В\u00a0ближайшие годы работа и\u00a0профессиональный рост будут для\u00a0меня приоритетом."},
        {t: "Ради работы одного из\u00a0нас семья должна быть готова переехать."},
        {t: "Частые командировки или ненормированный график партнёра\u00a0— приемлемая цена за\u00a0карьеру."},
        {t: "Важно, чтобы оба партнёра работали и\u00a0были финансово независимы."},
        {t: "Один из\u00a0нас может на\u00a0время оставить работу ради учёбы или смены профессии, пока другой обеспечивает семью."},
        {t: "Рабочие дела не\u00a0должны занимать вечера и\u00a0выходные."}]}
    ]
  };

  const items = VAL.domains.flatMap((d, di) => d.items.map(it => ({d: di, t: it.t, safe: !!it.safe})));
  const SAFE = items.findIndex(it => it.safe);
  const LABELS = VAL.scale;
  const CODE_RE = new RegExp("^V1-" + VAL.domains.map(d => "([1-5]{" + d.items.length + "})").join("-") + "$");

  const q = App.quiz({
    p: "val", items, scale: LABELS,
    eyebrow: it => VAL.domains[it.d].name,
    clear: ["rows", "cmp-out", "opts", "code", "copy-st", "cmp-err", "overall", "start-hint"],
    onFinish: showResult,
    compare
  });

  const isOwn = x => !x.it.safe;

  function showResult(answers){
    const rows = VAL.domains.map((d, di) => {
      const own = items.map((it, k) => ({it, a: answers[k]})).filter(x => x.it.d === di && isOwn(x));
      return {name: d.name, strong: own.filter(x => x.a === 1 || x.a === 5), unsure: own.filter(x => x.a === 3)};
    });
    const strong = rows.reduce((n, r) => n + r.strong.length, 0);
    const unsure = rows.reduce((n, r) => n + r.unsure.length, 0);
    q.el("overall").textContent = "Принципиальных позиций: " + strong + ". Вопросов без\u00a0ответа: " + unsure + ".";

    q.el("rows").replaceChildren(...rows.map(r => {
      const box = mk("div", "rrow");
      const h = mk("div", "rhead");
      h.append(mk("b", "", r.name), mk("span", "rlabel", "позиций: " + r.strong.length + " · без\u00a0ответа: " + r.unsure.length));
      box.append(h);
      const picked = [...r.strong, ...r.unsure].sort((a, b) => items.indexOf(a.it) - items.indexOf(b.it));
      if (!picked.length) box.append(mk("p", "rnote", "Здесь вы не\u00a0категоричны: все ответы умеренные."));
      else box.append(App.vlist(picked.map(x => App.vitem(x.it.t, [[x.a === 3 ? "" : x.a === 5 ? "pair" : "solo", LABELS[x.a - 1]]]))));
      return box;
    }));

    const top = key => rows.map((r, i) => ({i, n: r[key].length})).sort((a, b) => b.n - a.n || a.i - b.i)[0];
    let hint;
    if (unsure) hint = "Больше всего вопросов без\u00a0ответа в\u00a0сфере «" + rows[top("unsure").i].name + "»: хорошая тема, чтобы подумать до\u00a0разговора с\u00a0партнёром.";
    else if (strong) hint = "Больше всего принципиальных позиций в\u00a0сфере «" + rows[top("strong").i].name + "»: о\u00a0них стоит сказать партнёру прямо.";
    else hint = "Все ваши ответы умеренные: нет вопросов, где вы категоричны.";
    q.el("start-hint").textContent = hint;

    const flagged = answers[SAFE] <= 2;
    q.el("safe").hidden = !flagged;
    q.el("share").hidden = flagged;
    let k = 0;
    q.el("code").textContent = "V1-" + VAL.domains.map(d => { const g = answers.slice(k, k + d.items.length).join(""); k += d.items.length; return g; }).join("-");
    q.showResult();
  }

  function parseCode(s){
    const norm = String(s).toUpperCase().replace(/[‐-―−]/g, "-").replace(/\s+/g, "");
    const m = CODE_RE.exec(norm);
    return m ? m.slice(1).join("").split("").map(Number) : null;
  }
  const side = x => x < 3 ? -1 : x > 3 ? 1 : 0;
  function judge(a, b){
    const sa = side(a), sb = side(b);
    if (sa && sa === sb) return "same";
    if (!sa && !sb) return "both";
    if (!sa || !sb) return "one";
    return "diff";
  }
  const pairEntry = x => App.vitem(x.it.t, [["pair", "Вы: " + LABELS[x.a - 1]], ["solo", "Партнёр: " + LABELS[x.b - 1]]]);

  function compare(){
    const err = q.el("cmp-err"), out = q.el("cmp-out");
    const answers = q.answers;
    err.textContent = "";
    out.replaceChildren();
    const other = parseCode(q.el("partner").value);
    if (!other || answers.some(a => a == null)){
      err.textContent = "Не\u00a0получилось прочитать код. Он выглядит так: V1-513425-245312-531452-325141-452513.";
      return;
    }
    const res = items.map((it, k) => ({it, a: answers[k], b: other[k], r: judge(answers[k], other[k]), g: Math.abs(answers[k] - other[k])})).filter(isOwn);
    const diff = res.filter(x => x.r === "diff").sort((x, y) => y.g - x.g);
    const unclear = res.filter(x => x.r === "one" || x.r === "both");
    const same = res.length - diff.length - unclear.length;
    out.append(mk("p", "roverall", "Совпадают: " + same + " из\u00a0" + res.length + ". Расходятся: " + diff.length + ". Нужно уточнить: " + unclear.length + "."));

    const fills = [];
    VAL.domains.forEach((d, di) => {
      const rs = res.filter(x => x.it.d === di);
      const nSame = rs.filter(x => x.r === "same").length;
      const nDiff = rs.filter(x => x.r === "diff").length;
      const nOpen = rs.filter(x => x.r === "one" || x.r === "both").length;
      const r = mk("div", "rrow");
      const h = mk("div", "rhead");
      h.append(mk("b", "", d.name), mk("span", "rlabel", "Совпадают " + nSame + " из\u00a0" + rs.length));
      const t = mk("div", "track");
      t.setAttribute("aria-hidden", "true");
      const f = mk("span", "fill");
      t.append(f);
      fills.push([f, Math.round(nSame / rs.length * 100)]);
      const parts = [];
      if (nDiff) parts.push("Расходятся: " + nDiff);
      if (nOpen) parts.push("Нужно уточнить: " + nOpen);
      r.append(h, t, mk("p", "rnote", parts.length ? parts.join(". ") + "." : "Явных расхождений и\u00a0открытых вопросов нет."));
      out.append(r);
    });

    if (diff.length) out.append(mk("h5", "vh", "Стоит обсудить в\u00a0первую очередь"), App.vlist(diff.map(pairEntry)));
    if (unclear.length) out.append(mk("h5", "vh", "Стоит уточнить"), App.vlist(unclear.map(pairEntry)));
    out.append(mk("p", "hint", "Разница в\u00a0ответах\u00a0— не\u00a0ошибка одного из\u00a0вас. Можно спросить друг друга: «Почему для\u00a0тебя это важно?»"));
    grow(fills);
  }

  App.tests.values = q.open;
})();
