(function(){
  "use strict";
  const {$, $$, mk, say, FORMAT} = App;

  const form = $("#booker");
  const val = n => (form.querySelector(`input[name="${n}"]:checked`) || {}).value;
  const fmtDay = (d, opts) => d.toLocaleDateString("ru-RU", opts);
  const LONG = {weekday: "long", day: "numeric", month: "long"};
  const SHORT = {day: "numeric", month: "long"};

  /* ——— формат, состав, цена ——— */
  function price(){
    const f = val("format"), w = val("who");
    if (f === "couple") return w === "pair" ? {min: 90, rub: "7\u00a0500"} : {min: 60, rub: "6\u00a0500"};
    if (f === "premarital") return {min: 90, rub: "6\u00a0000", extra: "первая встреча курса, весь курс 24\u00a0000\u00a0₽"};
    return w === "pair" ? {min: 90, rub: "8\u00a0000"} : {min: 60, rub: "6\u00a0500"};
  }
  function syncWho(){
    $("#solo-label").textContent = val("format") === "sex" ? "Приду один(а)" : "Сначала приду один(а)";
  }
  const CHANNELS = {
    tg: {label: "Ваш ник в\u00a0Telegram", ph: "@ник", type: "text", mode: "text", ac: "off"},
    wa: {label: "Ваш номер в\u00a0WhatsApp", ph: "+7(000)000-00-00", type: "tel", mode: "tel", ac: "tel"},
    mail: {label: "Ваша почта", ph: "Адрес почты", type: "email", mode: "email", ac: "email"}
  };
  function setChannel(){
    const c = CHANNELS[val("channel")], inp = $("#f-contact");
    $("#lbl-contact").textContent = c.label;
    inp.placeholder = c.ph;
    inp.type = c.type;
    inp.inputMode = c.mode;
    inp.autocomplete = c.ac;
    clearErr("contact");
  }

  /* ——— ошибки рядом с полем ——— */
  const fields = {name: "#f-name", contact: "#f-contact", consent: "#f-consent"};
  function showErr(key, msg){
    $("#e-" + key).textContent = msg;
    const f = fields[key] && $(fields[key]);
    if (f) f.setAttribute("aria-invalid", "true");
  }
  function clearErr(key){
    $("#e-" + key).textContent = "";
    const f = fields[key] && $(fields[key]);
    if (f) f.removeAttribute("aria-invalid");
  }
  $("#f-name").addEventListener("input", () => clearErr("name"));
  $("#f-contact").addEventListener("input", () => clearErr("contact"));
  $("#f-consent").addEventListener("change", () => clearErr("consent"));

  function contactError(kind, v){
    if (!v) return "Укажите контакт, чтобы я\u00a0могла подтвердить запись.";
    if (kind === "mail" && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return "Проверьте адрес почты: в\u00a0нём должен быть знак @ и\u00a0домен.";
    if (kind === "wa" && !/^\+?\d{10,15}$/.test(v.replace(/[\s()-]/g, ""))) return "Проверьте номер: нужно от\u00a010\u00a0цифр.";
    if (kind === "tg" && !/^@?[A-Za-z][A-Za-z0-9_]{4,31}$/.test(v) && !/^\+?\d{10,15}$/.test(v.replace(/[\s()-]/g, ""))) return "Проверьте ник: от\u00a05\u00a0до\u00a032\u00a0символов, латиница, цифры и\u00a0«_». Можно указать и\u00a0номер телефона.";
    return "";
  }
  function preselect(f){
    const r = form.querySelector(`input[name="format"][value="${f}"]`);
    if (r) r.checked = true;
    form.querySelector(`input[name="who"][value="${f === "sex" ? "solo" : "pair"}"]`).checked = true;
    syncWho();
    renderSummary();
    $("#booking").scrollIntoView({behavior: "smooth"});
    say("Формат выбран: " + FORMAT[f].name);
  }
  document.addEventListener("click", e => {
    const b = e.target.closest("[data-book]");
    if (b) preselect(b.dataset.book);
  });
  form.addEventListener("change", e => {
    if (e.target.name === "format") syncWho();
    if (e.target.name === "channel") setChannel();
    renderSummary();
  });

  /* ——— календарь ——— */
  const MONTHS = ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"];
  const WD = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];
  const TIMES = ["09:30", "11:00", "12:30", "15:00", "16:30", "18:00", "19:30", "21:00"];
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const addDays = (d, n) => { const x = new Date(d); x.setDate(x.getDate() + n); return x; };
  const minDate = addDays(today, 1), maxDate = addDays(today, 56);
  const sameMonth = (a, b) => a.getFullYear() === b.getFullYear() && a.getMonth() === b.getMonth();
  const sameDay = (a, b) => a && b && a.getTime() === b.getTime();
  let view = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
  let selDate = null, selTime = null;

  /* Расписание: источник данных подключается здесь */
  function slotsFor(d){
    const dow = d.getDay();
    if (dow === 0) return {closed: true, list: []};
    const h = d.getFullYear() * 401 + (d.getMonth() + 1) * 37 + d.getDate() * 13;
    if (h % 6 === 1) return {closed: false, list: []};
    const base = dow === 6 ? TIMES.slice(1, 5) : TIMES;
    return {closed: false, list: base.filter((t, i) => ((h + i * 5) % 4) !== 0)};
  }

  function renderCal(){
    const cal = $("#cal");
    const cells = WD.map(w => {
      const s = mk("span", "wd", w);
      s.setAttribute("aria-hidden", "true");
      return s;
    });
    for (let i = 0; i < (view.getDay() + 6) % 7; i++) cells.push(mk("span"));
    const days = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
    for (let n = 1; n <= days; n++){
      const d = new Date(view.getFullYear(), view.getMonth(), n);
      const b = mk("button", "day", String(n));
      b.type = "button";
      const s = slotsFor(d);
      const label = fmtDay(d, LONG);
      if (sameDay(d, today)) b.classList.add("today");
      if (d < minDate || d > maxDate){
        b.disabled = true;
        b.setAttribute("aria-label", label + ", запись недоступна");
      } else if (s.closed || !s.list.length){
        b.disabled = true;
        b.classList.add("full");
        b.setAttribute("aria-label", label + (s.closed ? ", нет приёма" : ", всё занято"));
      } else {
        b.classList.add("has");
        b.setAttribute("aria-label", label + ", свободно: " + s.list.length);
        b.addEventListener("click", () => { selDate = d; selTime = null; clearSlotErr(); renderCal(); renderSlots(); renderSummary(); });
      }
      if (sameDay(d, selDate)){ b.classList.add("sel"); b.setAttribute("aria-pressed", "true"); }
      cells.push(b);
    }
    cal.replaceChildren(...cells);
    $("#cal-month").textContent = MONTHS[view.getMonth()] + " " + view.getFullYear();
    $("#cal-prev").disabled = sameMonth(view, minDate);
    $("#cal-next").disabled = sameMonth(view, maxDate);
  }
  $("#cal-prev").addEventListener("click", () => { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); renderCal(); });
  $("#cal-next").addEventListener("click", () => { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); renderCal(); });

  const clearSlotErr = () => { $("#e-slot").textContent = ""; };
  function renderSlots(){
    const box = $("#slots");
    box.replaceChildren();
    if (!selDate){ $("#slots-title").textContent = "Выберите день в\u00a0календаре"; return; }
    $("#slots-title").textContent = fmtDay(selDate, LONG).replace(/^./, c => c.toUpperCase());
    slotsFor(selDate).list.forEach(t => {
      const b = mk("button", "slot", t);
      b.type = "button";
      b.setAttribute("aria-pressed", String(t === selTime));
      b.addEventListener("click", () => {
        selTime = t;
        clearSlotErr();
        $$(".slot", box).forEach(x => x.setAttribute("aria-pressed", String(x === b)));
        renderSummary();
      });
      box.append(b);
    });
  }

  function renderSummary(){
    const p = price();
    const who = val("who") === "pair" ? "вдвоём" : "один(а)";
    const place = val("place") === "online" ? "онлайн" : "очно";
    let when = "время не\u00a0выбрано";
    if (selDate && selTime) when = fmtDay(selDate, SHORT) + ", " + selTime + " (МСК)";
    else if (selDate) when = fmtDay(selDate, SHORT) + ", выберите время";
    const line = (...parts) => { const s = mk("span"); s.append(...parts); return s; };
    $("#summary").replaceChildren(
      line(mk("b", "", FORMAT[val("format")].name), ", " + who + ", " + place),
      line(when),
      line(p.min + "\u00a0минут, " + p.rub + "\u00a0₽" + (p.extra ? "\u00a0— " + p.extra : ""))
    );
    $("#submit-btn").textContent = selDate && selTime
      ? "Записаться на\u00a0" + fmtDay(selDate, SHORT) + ", " + selTime
      : "Записаться";
  }

  /* ——— отправка ——— */
  form.addEventListener("submit", e => {
    e.preventDefault();
    const name = $("#f-name").value.trim(), contact = $("#f-contact").value.trim();
    ["name", "contact", "consent"].forEach(clearErr);
    clearSlotErr();
    let first = null;
    const fail = (key, msg, target) => { showErr(key, msg); first = first || target; };
    if (!selDate || !selTime){
      $("#e-slot").textContent = !selDate ? "Выберите день в\u00a0календаре." : "Выберите время встречи.";
      first = $("#slots-title");
    }
    if (!name) fail("name", "Напишите, как к\u00a0вам обращаться\u00a0— достаточно имени.", $("#f-name"));
    const ce = contactError(val("channel"), contact);
    if (ce) fail("contact", ce, $("#f-contact"));
    if (!$("#f-consent").checked) fail("consent", "Отметьте согласие на\u00a0обработку контакта\u00a0— без\u00a0него я\u00a0не\u00a0смогу вам написать.", $("#f-consent"));
    if (first){
      first.focus();
      return;
    }

    const ch = {tg: "в\u00a0Telegram", wa: "в\u00a0WhatsApp", mail: "на\u00a0почту"}[val("channel")];
    $("#done-text").textContent =
      `${name}, ${FORMAT[val("format")].name.toLowerCase()}, ${fmtDay(selDate, LONG)} в\u00a0${selTime} (МСК), ${val("place") === "online" ? "онлайн" : "очно"}. Я\u00a0напишу ${ch} в\u00a0течение дня, чтобы подтвердить время.`;
    $("#form-body").hidden = true;
    const done = $("#done");
    done.hidden = false;
    done.focus();
  });
  $("#again").addEventListener("click", () => {
    $("#done").hidden = true;
    $("#form-body").hidden = false;
    $("#f-name").focus();
  });

  syncWho();
  setChannel();
  renderCal();
  renderSlots();
  renderSummary();
})();
