(function(){
  "use strict";
  const {$, mk, band, grow, copyText} = App;

  const CODE_ERR_TAIL = "Не\u00a0получилось прочитать код. Он выглядит так: ";

  /* ——— движок диалога-опросника ———
     Ожидает в разметке элементы #<p>-dlg, -intro, -quiz, -result, -bar, -count,
     -legend, -opts, -q, -back, -eyebrow, -close; остальные (start, again, copy, cmp…) необязательны. */
  App.quiz = function(cfg){
    const el = n => $("#" + cfg.p + "-" + n);
    const on = (n, ev, fn) => { const e = el(n); if (e) e.addEventListener(ev, fn); };
    const dlg = el("dlg");
    const panels = Object.fromEntries((cfg.panels || ["intro", "quiz", "result"]).map(n => [n, el(n)]));
    const total = cfg.items.length;
    let step = 0, answers = [], timer = 0;

    const show = name => {
      Object.keys(panels).forEach(k => { panels[k].hidden = k !== name; });
      dlg.scrollTop = 0;
    };

    function reset(){
      clearTimeout(timer);
      answers = cfg.items.map(() => null);
      step = 0;
      (cfg.clear || []).forEach(n => { el(n).textContent = ""; });
      const partner = el("partner");
      if (partner) partner.value = "";
      el("bar").style.width = "0%";
      if (cfg.onReset) cfg.onReset();
    }
    function open(){
      reset();
      el("eyebrow").textContent = "Тест, каждый отдельно";
      show("intro");
      if (!dlg.open) dlg.showModal();
      el(cfg.focus || "start").focus();
    }

    function renderQ(){
      const it = cfg.items[step];
      el("eyebrow").textContent = cfg.eyebrow(it);
      el("count").textContent = (cfg.prefix ? cfg.prefix() : "") + "Вопрос " + (step + 1) + " из\u00a0" + total;
      el("bar").style.width = (step / total * 100) + "%";
      el("legend").textContent = it.t;
      el("opts").replaceChildren(...cfg.scale.map((label, i) => {
        const lab = mk("label", "sopt");
        const inp = document.createElement("input");
        inp.type = "radio";
        inp.name = cfg.p + "-a";
        inp.value = String(i + 1);
        inp.checked = answers[step] === i + 1;
        inp.addEventListener("change", () => {
          answers[step] = i + 1;
          clearTimeout(timer);
          timer = setTimeout(advance, 280);
        });
        const badge = mk("i", "", cfg.badges ? cfg.badges[i] : String(i + 1));
        badge.setAttribute("aria-hidden", "true");
        const sp = mk("span");
        sp.append(badge, document.createTextNode(label));
        lab.append(inp, sp);
        return lab;
      }));
      el("back").disabled = step === 0;
      el("q").animate([{opacity: 0, transform: "translateX(18px)"}, {opacity: 1, transform: "translateX(0)"}], {duration: 300, easing: "ease-out"});
      el("legend").focus({preventScroll: true});
    }
    function advance(){
      if (!dlg.open || answers[step] == null) return;
      if (step < total - 1){ step++; renderQ(); }
      else cfg.onFinish(answers);
    }
    function start(){
      answers = cfg.items.map(() => null);
      step = 0;
      show("quiz");
      renderQ();
    }
    function showResult(){
      el("eyebrow").textContent = "Результат";
      el("bar").style.width = "100%";
      show("result");
      el("rtitle").focus({preventScroll: true});
    }

    on("start", "click", start);
    on("close", "click", () => dlg.close());
    on("again", "click", cfg.againOpens ? open : start);
    on("back", "click", () => { clearTimeout(timer); if (step > 0){ step--; renderQ(); } });
    on("copy", "click", async () => {
      el("copy-st").textContent = (await copyText(el("code").textContent, dlg))
        ? "Код скопирован."
        : "Не\u00a0удалось скопировать. Выделите код и\u00a0скопируйте вручную.";
    });
    if (cfg.compare){
      on("cmp", "click", cfg.compare);
      on("partner", "keydown", e => { if (e.key === "Enter"){ e.preventDefault(); cfg.compare(); } });
    }
    dlg.addEventListener("close", reset);
    dlg.addEventListener("click", e => { if (e.target.closest("[data-book], a[href^='#']")) dlg.close(); });
    dlg.addEventListener("mousedown", e => {
      if (e.target !== dlg) return;
      const r = dlg.getBoundingClientRect();
      if (e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom) dlg.close();
    });

    return {dlg, el, open, start, show, showResult, get answers(){ return answers; }};
  };

  /* ——— общие кирпичики результатов ——— */
  const bar = (fills, v, cls = "") => {
    const t = mk("div", "track");
    t.setAttribute("aria-hidden", "true");
    const f = mk("span", ("fill " + cls).trim());
    t.append(f);
    fills.push([f, v]);
    return t;
  };

  App.scores = (count, items, answers) => Array.from({length: count}, (_, d) => {
    let sum = 0, n = 0;
    items.forEach((it, k) => { if (it.d === d){ sum += answers[k]; n++; } });
    return Math.round((sum - n) / (n * 4) * 100);
  });

  App.parseScores = (text, prefix, count) => {
    const re = new RegExp("^" + prefix + "-" + Array(count).fill("(\\d{1,3})").join("-") + "$");
    const m = re.exec(String(text).trim().toUpperCase());
    if (!m) return null;
    const v = m.slice(1).map(Number);
    return v.every(n => n <= 100) ? v : null;
  };

  App.renderBands = (box, names, sc, words, notes) => {
    const fills = [];
    box.replaceChildren(...names.map((name, i) => {
      const b = band(sc[i]);
      const r = mk("div", "rrow");
      const h = mk("div", "rhead");
      h.append(mk("b", "", name), mk("span", "rlabel", words[b]));
      r.append(h, bar(fills, sc[i]), mk("p", "rnote", notes[i][b]), mk("span", "sr-only", "Ваш результат: " + sc[i] + " из\u00a0100"));
      return r;
    }));
    grow(fills);
  };

  App.renderCompare = (out, names, mine, other, labelFn) => {
    const fills = [];
    names.forEach((name, i) => {
      const r = mk("div", "rrow");
      const h = mk("div", "rhead");
      h.append(mk("b", "", name), mk("span", "rlabel", labelFn(mine[i], other[i])));
      r.append(h);
      [["Вы", mine[i], ""], ["Партнёр", other[i], "b"]].forEach(([who, v, cls]) => {
        const line = mk("div", "pline");
        line.append(mk("span", "", who), bar(fills, v, cls), mk("span", "sr-only", who + ": " + v + " из\u00a0100"));
        r.append(line);
      });
      out.append(r);
    });
    grow(fills);
  };

  App.vitem = (text, tags) => {
    const li = mk("li", "vitem");
    const m = mk("div", "vmeta");
    tags.forEach(([cls, t]) => m.append(mk("span", ("tag " + cls).trim(), t)));
    li.append(mk("p", "", text), m);
    return li;
  };
  App.vlist = entries => {
    const ul = mk("ul", "vlist");
    ul.append(...entries);
    return ul;
  };

  /* ——— тест с шкалами 0–100 по сферам и кодом для сравнения (удовлетворённость, готовность к браку) ——— */
  App.bandedTest = function(cfg){
    const names = cfg.domains.map(d => d.name);
    const items = cfg.domains.flatMap((d, di) => d.items.map(t => ({d: di, t})));
    let mine = null;

    const q = App.quiz({
      p: cfg.p, items, scale: cfg.scale,
      eyebrow: it => names[it.d],
      clear: ["rows", "cmp-out", "opts", "code", "copy-st", "cmp-err", "overall", "start-hint"],
      onReset: () => { mine = null; },
      onFinish: finish,
      compare
    });

    function finish(answers){
      const sc = App.scores(names.length, items, answers);
      mine = sc;
      q.el("overall").textContent = cfg.overall[band(Math.round(sc.reduce((a, b) => a + b, 0) / sc.length))];
      App.renderBands(q.el("rows"), names, sc, cfg.words, cfg.notes);
      const low = sc.indexOf(Math.min(...sc));
      q.el("start-hint").textContent = sc[low] < 75
        ? "С\u00a0чего начать разговор: «" + names[low] + "»."
        : cfg.allClear;
      const flagged = answers[cfg.safeIndex] <= 2;
      q.el("safe").hidden = !flagged;
      q.el("share").hidden = flagged;
      q.el("code").textContent = cfg.prefix + "-" + sc.join("-");
      if (cfg.extra) cfg.extra(q, answers);
      q.showResult();
    }

    function gap(a, b){
      const g = Math.abs(a - b);
      if (g <= 15){
        if (a < 50 && b < 50) return cfg.gap.low;
        if (a >= 75 && b >= 75) return cfg.gap.high;
        return "Оценки близки";
      }
      return g <= 30 ? "Есть разница" : "Заметно расходятся";
    }

    function compare(){
      const err = q.el("cmp-err"), out = q.el("cmp-out");
      err.textContent = "";
      out.replaceChildren();
      const other = App.parseScores(q.el("partner").value, cfg.prefix, names.length);
      if (!other || !mine){ err.textContent = CODE_ERR_TAIL + cfg.codeHint + "."; return; }
      App.renderCompare(out, names, mine, other, gap);
      out.append(mk("p", "hint", cfg.compareHint));
    }

    return q.open;
  };
})();
