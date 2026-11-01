(function () {
  'use strict';
  const section = (window.GdpsyThemeScope || document).getElementById('gz-contact');
  if (!section || !section.querySelector('#gz-contact-form')) return;
  const form = section.querySelector('#gz-contact-form');
  const inputs = form.querySelector('.gz-contact-inputs');
  const success = form.querySelector('.gz-contact-success');
  let complete = form.dataset.contactComplete === 'true';
  let submitting = false;
  form.noValidate = true;
  const message = section.querySelector('#gz-message');
  if (section.dataset.contactMessage) {
    message.defaultValue = section.dataset.contactMessage;
    message.value = section.dataset.contactMessage;
  }
  let presetMessage = message.defaultValue;
  let messageEdited = false;
  const replyField = section.querySelector('#gz-reply-contact');
  const replyLabel = section.querySelector('#gz-reply-label');
  const status = section.querySelector('#gz-contact-status');
  const toggle = section.querySelector('.gz-contact-toggle');
  const reveal = section.querySelector('#gz-contact-reveal');
  const fields = section.querySelector('.gz-contact-fields');
  const mobile = window.matchMedia('(max-width: 1000px)');
  let mobileOpen = false;
  let inDialog = false;
  let dialogOpen = false;
  function setExpanded(open) {
    toggle.setAttribute('aria-expanded', String(open));
    reveal.classList.toggle('is-open', open);
    reveal.inert = !open;
    fields.disabled = !open;
  }
  function syncLayout() {
    const dialog = (window.GdpsyThemeScope || document).getElementById('gz-contact-dialog');
    if (dialog) {
      dialog.classList.toggle('is-complete', complete);
      dialog.setAttribute('aria-labelledby', complete && inDialog ? 'gz-contact-success-title' : 'gz-contact-dialog-title');
    }
    if (complete) {
      fields.disabled = true;
      return;
    }
    setExpanded(inDialog ? dialogOpen : !mobile.matches || mobileOpen);
  }
  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') !== 'true';
    if (inDialog) dialogOpen = open;
    else mobileOpen = open;
    setExpanded(open);
    if (!open) return;
    const first = fields.querySelector('input[name="reply"]:checked') || fields.querySelector('input');
    first?.focus({ preventScroll: true });
    window.requestAnimationFrame(() => fields.scrollIntoView({ behavior: 'smooth', block: 'nearest' }));
  });
  mobile.addEventListener('change', syncLayout);
  section.addEventListener('gz:contact-layout', (event) => {
    const nextDialog = event.detail?.modal === true;
    if (nextDialog && !inDialog) dialogOpen = false;
    inDialog = nextDialog;
    syncLayout();
    fitMessage();
  });
  syncLayout();
  window.requestAnimationFrame(() => section.classList.add('is-ready'));
  function syncReply() {
    const value = form.querySelector('input[name="reply"]:checked').value;
    replyField.type = value === 'email' ? 'email' : value === 'phone' ? 'tel' : 'text';
    replyField.autocomplete = value === 'email' ? 'email' : value === 'phone' ? 'tel' : 'off';
    replyField.placeholder = value === 'email' ? 'name@example.ru' : value === 'phone' ? '+7 …' : '@username или номер телефона';
    replyLabel.textContent = value === 'email' ? 'Email' : value === 'phone' ? 'Номер телефона' : value === 'telegram' ? 'Telegram' : 'MAX';
    replyField.setCustomValidity('');
  }
  form.querySelectorAll('input[name="reply"]').forEach((input) => input.addEventListener('change', syncReply));
  replyField.addEventListener('input', () => replyField.setCustomValidity(''));
  function fitMessage() {
    if (section.dataset.contactVariant !== 'main') return;
    message.style.height = 'auto';
    if (message.scrollHeight) message.style.height = (message.scrollHeight + message.offsetHeight - message.clientHeight) + 'px';
  }
  message.addEventListener('input', () => {
    messageEdited = true;
    message.setCustomValidity('');
    fitMessage();
  });
  section.addEventListener('gz:contact-preset', (event) => {
    if (complete) return;
    if (typeof event.detail?.message !== 'string') return;
    presetMessage = event.detail.message;
    if (!messageEdited) {
      message.value = presetMessage;
      message.setCustomValidity('');
      status.textContent = '';
      fitMessage();
    }
  });
  window.addEventListener('resize', fitMessage);
  fitMessage();
  window.addEventListener('pageshow', () => {
    syncReply();
    syncLayout();
  });
  syncReply();
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (complete || submitting) return;
    if (fields.disabled) {
      if (inDialog) dialogOpen = true;
      else mobileOpen = true;
      syncLayout();
      return;
    }
    message.setCustomValidity(message.value.trim() ? '' : 'Напишите сообщение.');
    replyField.setCustomValidity(replyField.value.trim() ? '' : 'Укажите контакт для ответа.');
    const reply = form.querySelector('input[name="reply"]:checked').value;
    if (reply === 'phone' && replyField.value.replace(/\D/g, '').length < 10) {
      replyField.setCustomValidity('Укажите номер телефона целиком.');
    }
    if (!form.reportValidity()) return;
    submitting = true;
    try {
      const result = await window.GdpsyForms.submit(form, status, { announceSuccess: false });
      if (result?.success !== true) return;
      complete = true;
      form.dataset.contactComplete = 'true';
      inputs.hidden = true;
      success.hidden = false;
      syncLayout();
      if (!form.closest('[hidden], dialog:not([open])')) {
        success.querySelector('h3').focus({ preventScroll: true });
        success.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    } finally {
      submitting = false;
    }
  });
  section.querySelectorAll('[data-message-action]').forEach((button) => {
    button.addEventListener('click', async () => {
      const action = button.dataset.messageAction;
      status.textContent = '';
      if (action === 'clear' || action === 'default') {
        message.value = action === 'clear' ? '' : presetMessage;
        messageEdited = action === 'clear';
        fitMessage();
        message.setCustomValidity('');
        message.focus();
        return;
      }
      if (!message.value.trim()) {
        status.textContent = 'Добавьте текст сообщения.';
        message.focus();
        return;
      }
      try {
        if (!navigator.clipboard) throw new Error('clipboard');
        await navigator.clipboard.writeText(message.value);
        status.textContent = 'Сообщение скопировано.';
      } catch (error) {
        message.focus();
        message.select();
        status.textContent = 'Текст выделен — его можно скопировать.';
      }
    });
  });
  if (complete && !section.closest('[hidden]')) success.querySelector('h3').focus({ preventScroll: true });
  if (section.hasAttribute('data-popup-source') && !complete) section.hidden = true;
})();

