(function(){
  "use strict";

  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const mk = (tag, cls, text) => {
    const n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text != null) n.textContent = text;
    return n;
  };

  const toast = $("#toast");
  let toastTimer;
  function say(msg){
    toast.textContent = msg;
    toast.classList.add("show");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("show"), 2600);
  }

  const band = p => p >= 75 ? 2 : p >= 50 ? 1 : 0;
  const grow = pairs => requestAnimationFrame(() => requestAnimationFrame(() => pairs.forEach(([f, v]) => { f.style.width = v + "%"; })));

  async function copyText(text, host = document.body){
    try { await navigator.clipboard.writeText(text); return true; }
    catch(_) {
      const ta = mk("textarea");
      ta.value = text;
      ta.setAttribute("readonly", "");
      ta.style.cssText = "position:fixed;opacity:0";
      host.appendChild(ta);
      ta.select();
      let ok = false;
      try { ok = document.execCommand("copy"); } catch(__) {}
      ta.remove();
      return ok;
    }
  }

  const FORMAT = {
    couple: {name: "Пара"},
    premarital: {name: "Добрачный курс"},
    sex: {name: "Сексология"}
  };

  window.App = {$, $$, mk, say, band, grow, copyText, FORMAT, tests: {}};
})();
