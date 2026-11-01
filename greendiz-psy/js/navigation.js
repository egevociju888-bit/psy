(function () {
  'use strict';
  const root = window.GdpsyThemeScope || document.getElementById('greendiz-design-study');
  if (!root) return;
  const dialog = root.querySelector('#gz-menu');
  const toggle = root.querySelector('.gz-menu-toggle');
  const fallback = root.querySelector('.gz-navigation-fallback');
  if (dialog && toggle && typeof dialog.showModal === 'function') {
    toggle.hidden = false;
    if (fallback) fallback.hidden = true;
  } else if (fallback) {
    fallback.hidden = false;
  }
  root.querySelectorAll('.gz-wp-menu .menu-item-has-children').forEach((item) => {
    const submenu = Array.from(item.children).find((child) => child.classList.contains('sub-menu'));
    const link = Array.from(item.children).find((child) => child.tagName === 'A');
    if (!submenu || !link || !item.id) return;
    const button = document.createElement('button');
    const mark = document.createElement('span');
    const wide = Boolean(item.closest('#gz-menu, .gz-navigation-fallback'));
    button.type = 'button';
    button.className = 'gz-submenu-toggle ' + (wide ? 'gz-submenu-toggle--row' : 'gz-icon-action');
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', 'Подразделы: ' + link.textContent.trim());
    submenu.id = item.id + '-children';
    button.setAttribute('aria-controls', submenu.id);
    mark.setAttribute('aria-hidden', 'true');
    if (wide) {
      const line = document.createElement('span');
      line.className = 'gz-submenu-line';
      line.setAttribute('aria-hidden', 'true');
      button.append(line);
      mark.className = 'gz-menu-chevron';
      item.classList.add('gz-has-submenu--row');
    } else {
      mark.textContent = '+';
    }
    button.append(mark);
    link.after(button);
    submenu.hidden = true;
    item.classList.add('gz-has-submenu');
    let animation = null;
    let opened = false;
    function setOpen(next, focus = false) {
      const height = submenu.hidden ? 0 : submenu.getBoundingClientRect().height;
      if (animation) {
        animation.cancel();
        animation = null;
      }
      opened = next;
      button.setAttribute('aria-expanded', String(opened));
      if (!wide) mark.textContent = opened ? '−' : '+';
      if (!opened && (focus || submenu.contains((window.GdpsyThemeScope || document).activeElement))) button.focus({ preventScroll: true });
      submenu.inert = !opened;
      submenu.hidden = false;
      if (typeof submenu.animate !== 'function') {
        submenu.hidden = !opened;
        return;
      }
      const current = submenu.animate(
        [{ height: height + 'px', opacity: height ? 1 : 0 }, { height: opened ? submenu.scrollHeight + 'px' : '0px', opacity: opened ? 1 : 0 }],
        { duration: 360, easing: 'cubic-bezier(.25,.1,.25,1)' }
      );
      animation = current;
      item.classList.add('gz-submenu-animating');
      current.onfinish = () => {
        if (animation !== current) return;
        submenu.hidden = !opened;
        item.classList.remove('gz-submenu-animating');
        animation = null;
      };
    }
    button.addEventListener('click', () => setOpen(!opened));
    submenu.addEventListener('keydown', (event) => {
      if (opened && event.key === 'Escape') {
        event.preventDefault();
        event.stopPropagation();
        setOpen(false, true);
      }
    });
  });
})();
