(function () {
  'use strict';
  const hosts = ['gdd-host', 'gdd-foot-host'].map(id => document.getElementById(id)).filter(Boolean);
  if (!hosts.length) return;
  hosts.forEach(host => {
    if (host.shadowRoot) return;
    const template = host.querySelector('template[shadowrootmode]');
    if (!template || !host.attachShadow) return;
    host.attachShadow({ mode: 'open' }).append(template.content);
    template.remove();
  });
  const roots = hosts.map(host => host.shadowRoot).filter(Boolean);
  if (roots.length !== hosts.length) return;
  window.GdpsyThemeScope = {
    roots,
    querySelector(selector) {
      for (const root of roots) {
        const node = root.querySelector(selector);
        if (node) return node;
      }
      return null;
    },
    querySelectorAll(selector) {
      return roots.flatMap(root => Array.from(root.querySelectorAll(selector)));
    },
    getElementById(id) {
      for (const root of roots) {
        const node = root.getElementById(id);
        if (node) return node;
      }
      return null;
    },
    contains(node) {
      return roots.some(root => root.contains(node));
    },
    addEventListener(name, handler, options) {
      roots.forEach(root => root.addEventListener(name, handler, options));
    },
    get activeElement() {
      let node = document.activeElement;
      while (node?.shadowRoot?.activeElement) node = node.shadowRoot.activeElement;
      return node;
    },
  };
})();
