(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;
  const root = window.GdpsyThemeScope || document.getElementById('greendiz-design-study');
  if (!root) return;
  const contact = root.querySelector('#gz-contact');
  function closeDialog(dialog) {
    if (dialog && dialog.open) dialog.close();
  }
  const contactDialog = root.querySelector('#gz-contact-dialog');
  const contactForm = contact?.querySelector('#gz-contact-form');
  const quizDialog = root.querySelector('#gz-quiz');
  const page = document.documentElement;
  function lockPage() {
    if (page.classList.contains('gz-contact-modal-open')) return;
    page.style.setProperty('--gz-scrollbar-width', Math.max(0, window.innerWidth - page.clientWidth) + 'px');
    page.classList.add('gz-contact-modal-open');
  }
  function unlockPage() {
    if (contactDialog?.open || quizDialog?.open) return;
    page.classList.remove('gz-contact-modal-open');
    page.style.removeProperty('--gz-scrollbar-width');
  }
  quizDialog?.addEventListener('close', unlockPage);
  const contactPlaceholder = document.createElement('div');
  contactPlaceholder.className = 'gz-contact-placeholder';
  contactPlaceholder.hidden = true;
  contactPlaceholder.setAttribute('aria-hidden', 'true');
  contactForm?.before(contactPlaceholder);
  let contactReturn = null;
  function restoreContactForm(returnToSource = true) {
    if (!contactReturn) return;
    const state = contactReturn;
    contactReturn = null;
    contactPlaceholder.after(contactForm);
    contactPlaceholder.hidden = true;
    if (!(returnToSource && state.dialog?.isConnected && !root.querySelector('dialog[open]'))) unlockPage();
    contact.dispatchEvent(new CustomEvent('gz:contact-layout', { detail: { modal: false } }));
    window.scrollTo({ left: state.x, top: state.y, behavior: 'instant' });
    if (!returnToSource || root.querySelector('dialog[open]')) return;
    if (state.dialog?.isConnected) {
      state.dialog.showModal();
      state.dialog.scrollTop = state.dialogScroll;
    }
    const target = state.opener?.isConnected ? state.opener : contact.querySelector('#gz-contact-title');
    if (target && !target.closest('[inert]')) {
      if (target.tagName === 'H2') target.tabIndex = -1;
      target.focus({ preventScroll: true });
    }
  }
  contactDialog?.addEventListener('close', () => {
    if (!contactDialog.open) restoreContactForm();
  });
  function openDialog(id, opener = scope.activeElement) {
    const dialog = scope.getElementById(id);
    if (dialog && !root.contains(dialog)) return;
    if (!dialog) return;
    if (dialog === contactDialog && !contactForm) return;
    if (typeof dialog.showModal !== 'function') {
      if (dialog === contactDialog) {
        contact.hidden = false;
        contact.scrollIntoView({ behavior: 'smooth', block: 'start' });
        contactForm.querySelector(contactForm.dataset.contactComplete === 'true' ? '#gz-contact-success-title' : 'textarea')?.focus({ preventScroll: true });
      }
      return;
    }
    if (dialog.open) return;
    const position = { x: window.scrollX, y: window.scrollY };
    const previous = root.querySelector('dialog[open]');
    const previousScroll = previous?.scrollTop || 0;
    root.querySelectorAll('dialog[open]').forEach((opened) => opened.close());
    if (contactReturn) restoreContactForm(false);
    if (dialog === contactDialog) {
      contactReturn = {
        x: position.x,
        y: position.y,
        opener: previous?.id === 'gz-menu' ? root.querySelector('.gz-menu-toggle') : opener,
        dialog: previous && previous.id !== 'gz-menu' && previous !== contactDialog ? previous : null,
        dialogScroll: previousScroll,
      };
      contactPlaceholder.style.height = contactForm.getBoundingClientRect().height + 'px';
      contactPlaceholder.hidden = false;
      contactDialog.querySelector('[data-contact-mount]').append(contactForm);
      const copy = contact.querySelector('.gz-contact-copy').cloneNode(true);
      const sourceTitle = copy.querySelector('.gz-screen-title');
      const title = document.createElement('h2');
      title.className = sourceTitle.className;
      sourceTitle.replaceWith(title);
      title.id = 'gz-contact-dialog-title';
      title.tabIndex = -1;
      title.textContent = contact.querySelector('#gz-contact-title').textContent;
      contactDialog.querySelector('[data-contact-copy-mount]').replaceChildren(copy);
      const direct = contact.querySelector('.gz-contact-direct').cloneNode(true);
      direct.querySelector('.gz-contact-social')?.remove();
      direct.querySelector('h3').id = 'gz-contact-dialog-direct-title';
      direct.setAttribute('aria-labelledby', 'gz-contact-dialog-direct-title');
      contactDialog.querySelector('[data-contact-direct-mount]').replaceChildren(direct);
      lockPage();
    }
    if (dialog === quizDialog) lockPage();
    if (dialog === contactDialog) {
      contact.dispatchEvent(new CustomEvent('gz:contact-layout', { detail: { modal: true } }));
    }
    dialog.dispatchEvent(new CustomEvent('gz:dialog-prepare', { bubbles: true }));
    dialog.showModal();
    if (dialog === contactDialog) {
      contact.dispatchEvent(new CustomEvent('gz:contact-layout', { detail: { modal: true } }));
      contactDialog.scrollTop = 0;
      const target = contactForm.dataset.contactComplete === 'true' ? contactForm.querySelector('#gz-contact-success-title') : contactDialog.querySelector('h2');
      target.focus({ preventScroll: true });
    }
    dialog.dispatchEvent(new CustomEvent('gz:dialog-open', { bubbles: true }));
  }
  root.querySelectorAll('[data-open-dialog]').forEach((trigger) =>
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      if (trigger.dataset.contactPreset) {
        contact?.dispatchEvent(new CustomEvent('gz:contact-preset', { detail: { message: trigger.dataset.contactPreset } }));
      }
      openDialog(trigger.dataset.openDialog, trigger);
    }),
  );
  root.querySelectorAll('a[href]:not([data-open-dialog])').forEach((link) => {
    let url;
    try {
      url = new URL(link.getAttribute('href'), document.baseURI);
    } catch {
      return;
    }
    if (url.hash !== '#gz-contact' || url.origin !== window.location.origin ||
        url.pathname !== window.location.pathname || url.search !== window.location.search) return;
    link.addEventListener('click', (event) => {
      if (event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey ||
          event.shiftKey || event.altKey || link.hasAttribute('download') ||
          (link.target && link.target !== '_self') || !contactDialog || !contactForm) return;
      event.preventDefault();
      openDialog('gz-contact-dialog', link);
    });
  });
  root
    .querySelectorAll('[data-close-dialog]')
    .forEach((button) => button.addEventListener('click', () => closeDialog(button.closest('dialog'))));
  root.querySelectorAll('dialog').forEach((dialog) =>
    dialog.addEventListener('click', (event) => {
      if (event.target !== dialog) return;
      const rect = dialog.getBoundingClientRect();
      if (
        event.clientX < rect.left ||
        event.clientX > rect.right ||
        event.clientY < rect.top ||
        event.clientY > rect.bottom
      )
        dialog.close();
    }),
  );
  root.addEventListener('gz:open-dialog', (event) => {
    if (typeof event.detail?.id === 'string') openDialog(event.detail.id, event.detail.opener);
  });
})();

(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;
  const menu = scope.getElementById('gz-menu');
  const toggle = scope.querySelector('#greendiz-design-study .gz-menu-toggle');
  const anchor = toggle?.closest('.gz-menu-anchor');
  if (!menu || !toggle || !anchor) return;
  const label = toggle.querySelector('.gz-sr-only');
  const compact = window.matchMedia('(max-width: 760px)');
  let positionFrame = null;
  function updatePosition() {
    positionFrame = null;
    const rect = anchor.getBoundingClientRect();
    toggle.style.setProperty('--gz-menu-top', Math.max(0, rect.top) + 'px');
    toggle.style.setProperty('--gz-menu-left', rect.left + 'px');
    toggle.classList.add('is-positioned');
  }
  function schedulePosition() {
    if (positionFrame === null) positionFrame = window.requestAnimationFrame(updatePosition);
  }
  function syncScrollLock() {
    document.documentElement.classList.toggle('gz-menu-open', menu.open && compact.matches);
  }
  function syncToggle() {
    syncScrollLock();
    toggle.setAttribute('aria-expanded', String(menu.open));
    label.textContent = menu.open ? 'Закрыть меню' : 'Открыть меню';
  }
  toggle.addEventListener('click', () => {
    if (menu.open) {
      menu.close();
      return;
    }
    updatePosition();
    menu.prepend(toggle);
    menu.showModal();
    toggle.getBoundingClientRect();
    syncToggle();
    toggle.focus({ preventScroll: true });
  });
  menu.addEventListener('close', () => {
    if (menu.open) return;
    anchor.append(toggle);
    toggle.getBoundingClientRect();
    syncToggle();
    updatePosition();
    if (!scope.querySelector('dialog[open]')) toggle.focus({ preventScroll: true });
  });
  window.addEventListener('scroll', schedulePosition, { passive: true });
  window.addEventListener('resize', schedulePosition, { passive: true });
  window.addEventListener('pageshow', schedulePosition);
  window.visualViewport?.addEventListener('resize', schedulePosition, { passive: true });
  if (typeof ResizeObserver === 'function') {
    const observer = new ResizeObserver(schedulePosition);
    observer.observe(anchor);
  }
  if (document.fonts) document.fonts.ready.then(schedulePosition);
  updatePosition();
  menu.querySelectorAll('a[href]:not([data-open-dialog])').forEach((link) => link.addEventListener('click', () => menu.close()));
  const home = menu.querySelector('.gz-menu-home');
  function syncHome() {
    syncScrollLock();
    if (!home) return;
    home.open = !compact.matches;
    home.dispatchEvent(new Event('gz-details-reset'));
  }
  home?.querySelector('summary').addEventListener('click', event => {
    if (!compact.matches) event.preventDefault();
  });
  compact.addEventListener('change', syncHome);
  syncHome();
})();

(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;

  scope.querySelectorAll('.gz-quiet-details, #gz-faq details, .gz-menu-collapse').forEach(details => {
    const summary = details.querySelector('summary');
    const content = details.querySelector('.gz-quiet-details-content, .gz-faq-answer, .gz-menu-collapse-content');
    if (!summary || !content || typeof details.animate !== 'function') return;

    let animation = null;
    let targetOpen = details.open;
    details.addEventListener('gz-details-reset', () => {
      const previous = animation;
      animation = null;
      if (previous) previous.cancel();
      targetOpen = details.open;
      details.style.removeProperty('height');
      details.classList.remove('is-animating');
      details.removeAttribute('data-closing');
    });

    summary.addEventListener('click', event => {
      event.preventDefault();
      if (details.dataset.detailsStatic && window.matchMedia(details.dataset.detailsStatic).matches) return;
      if (details.classList.contains('gz-menu-home') && window.matchMedia('(min-width: 761px)').matches) return;
      targetOpen = animation ? !targetOpen : !details.open;

      const fromHeight = details.getBoundingClientRect().height;
      if (animation) animation.cancel();
      details.style.height = `${fromHeight}px`;
      details.classList.add('is-animating');
      details.toggleAttribute('data-closing', !targetOpen);
      details.open = true;

      const borderHeight = details.offsetHeight - details.clientHeight;
      const toHeight = summary.getBoundingClientRect().height + borderHeight +
        (targetOpen ? content.getBoundingClientRect().height : 0);
      const current = details.animate(
        [{ height: `${fromHeight}px` }, { height: `${toHeight}px` }],
        { duration: 360, easing: 'cubic-bezier(0.25, 0.1, 0.25, 1)' }
      );
      animation = current;

      current.onfinish = () => {
        if (animation !== current) return;
        if (!targetOpen && content.contains(scope.activeElement)) {
          summary.focus({ preventScroll: true });
        }
        details.open = targetOpen;
        details.style.removeProperty('height');
        details.classList.remove('is-animating');
        details.removeAttribute('data-closing');
        animation = null;
      };
    });
  });
})();

(function () {
  'use strict';
  const scope = window.GdpsyThemeScope || document;
  const area = scope.querySelector('#greendiz-design-study .gz-footer-legal');
  const button = area?.querySelector('.gz-footer-legal-toggle');
  const links = area?.querySelector('.gz-footer-legal-list');
  if (!area || !button || !links) return;
  let opened = false;
  let animation = null;
  function setOpen(next, restoreFocus = false) {
    if (opened === next) return;
    const from = links.hidden ? { opacity: '0', transform: 'translateY(8px)' } : getComputedStyle(links);
    const start = { opacity: from.opacity, transform: from.transform };
    if (animation) animation.cancel();
    opened = next;
    button.setAttribute('aria-expanded', String(opened));
    if (restoreFocus || (!opened && links.contains(scope.activeElement))) {
      button.focus({ preventScroll: true });
    }
    links.hidden = false;
    if (typeof links.animate !== 'function') {
      links.hidden = !opened;
      return;
    }
    const current = links.animate(
      [start, { opacity: opened ? '1' : '0', transform: opened ? 'translateY(0)' : 'translateY(8px)' }],
      { duration: 200, easing: 'ease', fill: 'both' },
    );
    animation = current;
    current.finished.then(() => {
      if (animation !== current) return;
      links.hidden = !opened;
      animation = null;
      current.cancel();
    }).catch(() => {});
  }
  button.addEventListener('click', () => setOpen(!opened));
  document.addEventListener('pointerdown', (event) => {
    if (opened && !event.composedPath().includes(area)) setOpen(false);
  });
  document.addEventListener('keydown', (event) => {
    if (opened && event.key === 'Escape') {
      event.preventDefault();
      setOpen(false, true);
    }
  });
  area.addEventListener('focusout', (event) => {
    if (opened && event.relatedTarget && !area.contains(event.relatedTarget)) setOpen(false);
  });
  links.addEventListener('click', (event) => {
    if (event.target.closest('a')) setOpen(false);
  });
})();


