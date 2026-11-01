(function () {
  'use strict';
  const form = document.getElementById('gz-cabinet-survey-form');
  if (!form) return;
  const steps = Array.from(form.querySelectorAll('[data-cabinet-step]'));
  const controls = form.querySelector('[data-cabinet-controls]');
  const back = form.querySelector('[data-cabinet-back]');
  const next = form.querySelector('[data-cabinet-next]');
  const submit = form.querySelector('[type="submit"]');
  const status = form.querySelector('[data-cabinet-status]');
  const progress = form.querySelector('[data-cabinet-progress]');
  const stepLabel = form.querySelector('[data-cabinet-step-label]');
  const dialog = form.closest('dialog');
  const intro = dialog.querySelector('[data-cabinet-intro]');
  const heading = dialog.querySelector('.gz-survey-heading');
  const successPanel = dialog.querySelector('[data-cabinet-success]');
  const successTitle = successPanel.querySelector('h3');
  const clientStep = steps.find(step => step.dataset.cabinetStep === 'client_features');
  let current = steps[0];
  let pending = false;
  const draftToken = form.querySelector('[name="draft_token"]');
  let draftChain = Promise.resolve();
  let draftTimer = null;
  let lastDraft = '';
  if (draftToken && window.crypto?.getRandomValues) {
    const bytes = new Uint8Array(32);
    window.crypto.getRandomValues(bytes);
    draftToken.value = Array.from(bytes, byte => byte.toString(16).padStart(2, '0')).join('');
  }

  function saveDraft() {
    window.clearTimeout(draftTimer);
    if (pending || form.dataset.complete === 'true' || !draftToken?.value) return draftChain;
    const source = new FormData(form);
    if (!source.getAll('tools[]').length) return draftChain;
    const data = new FormData();
    ['action', 'gdpsy_nonce', 'website'].forEach(key => data.set(key, source.get(key) || ''));
    data.set('kind', 'cabinet_draft');
    data.set('draft_token', draftToken.value);
    data.set('draft_step', current.dataset.cabinetStep);
    ['tools[]', 'cabinet_features[]', 'client_features[]', 'client_login', 'comment'].forEach(key => {
      source.getAll(key).forEach(value => data.append(key, value));
    });
    const snapshot = JSON.stringify(Array.from(data.entries()));
    if (snapshot === lastDraft) return draftChain;
    lastDraft = snapshot;
    draftChain = draftChain.then(async () => {
      const controller = new AbortController();
      const timeout = window.setTimeout(() => controller.abort(), 8000);
      try {
        try {
          data.set('gdpsy_nonce', await window.GdpsyForms.nonce());
        } catch (error) {}
        const response = await window.fetch(window.GdpsyFormConfig.url, {
          method: 'POST', body: data, credentials: 'same-origin', keepalive: true,
          signal: controller.signal, headers: { Accept: 'application/json' },
        });
        const result = await response.json();
        if (!response.ok || result.success !== true) throw new Error('Draft not saved');
      } catch (error) {
        if (lastDraft === snapshot) lastDraft = '';
      } finally {
        window.clearTimeout(timeout);
      }
    });
    return draftChain;
  }
  function scheduleDraft() {
    window.clearTimeout(draftTimer);
    if (!pending && form.dataset.complete !== 'true') draftTimer = window.setTimeout(saveDraft, 600);
  }

  function activeSteps() {
    const skip = form.querySelector('input[name="client_login"]:checked')?.value === 'no';
    clientStep.disabled = skip;
    return steps.filter(step => step !== clientStep || !skip);
  }
  function render(focus = false) {
    const active = activeSteps();
    if (!active.includes(current)) current = active[active.length - 1];
    const index = active.indexOf(current);
    steps.forEach(step => { step.hidden = step !== current; });
    intro.hidden = current !== steps[0];
    back.disabled = index === 0;
    next.hidden = index === active.length - 1;
    submit.hidden = !next.hidden;
    stepLabel.textContent = 'Шаг ' + (index + 1) + ' из ' + active.length;
    progress.setAttribute('aria-valuenow', String(index + 1));
    progress.setAttribute('aria-valuemax', String(active.length));
    progress.setAttribute('aria-valuetext', stepLabel.textContent);
    progress.style.setProperty('--gz-quiz-progress', ((index + 1) / active.length * 100) + '%');
    if (focus) {
      current.tabIndex = -1;
      current.focus({ preventScroll: true });
      current.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }
  function validate() {
    if ('cabinetMultiple' in current.dataset) {
      const inputs = Array.from(current.querySelectorAll('input[type="checkbox"]'));
      inputs[0].setCustomValidity(inputs.some(input => input.checked) ? '' : 'Выберите хотя бы один вариант.');
    }
    return Array.from(current.querySelectorAll('input, textarea')).every(input => input.disabled || input.reportValidity());
  }
  function advance() {
    if (pending || !validate()) return;
    const active = activeSteps();
    current = active[Math.min(active.indexOf(current) + 1, active.length - 1)];
    render(true);
    saveDraft();
  }
  next.addEventListener('click', advance);
  back.addEventListener('click', () => {
    if (pending) return;
    const active = activeSteps();
    current = active[Math.max(0, active.indexOf(current) - 1)];
    render(true);
    saveDraft();
  });
  form.addEventListener('change', () => {
    if (form.dataset.complete === 'true') return;
    steps.filter(step => 'cabinetMultiple' in step.dataset).forEach(step => {
      const inputs = Array.from(step.querySelectorAll('input[type="checkbox"]'));
      if (inputs.some(input => input.checked)) inputs[0].setCustomValidity('');
    });
    render();
    scheduleDraft();
  });
  form.addEventListener('input', scheduleDraft);
  dialog.addEventListener('close', saveDraft);
  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (pending || form.dataset.complete === 'true') return;
    const active = activeSteps();
    if (current !== active[active.length - 1]) {
      advance();
      return;
    }
    if (!validate() || !form.reportValidity()) return;
    pending = true;
    window.clearTimeout(draftTimer);
    back.disabled = true;
    try {
      await draftChain;
      const result = await window.GdpsyForms.submit(form, status);
      if (result.success) {
        form.dataset.complete = 'true';
        steps.forEach(step => { step.hidden = true; });
        controls.hidden = true;
        submit.hidden = true;
        progress.hidden = true;
        stepLabel.hidden = true;
        form.querySelectorAll('input, textarea, button').forEach(input => { input.disabled = true; });
        form.hidden = true;
        intro.hidden = true;
        heading.hidden = true;
        successPanel.hidden = false;
        dialog.classList.add('is-complete');
        dialog.setAttribute('aria-labelledby', 'gz-cabinet-success-title');
        dialog.setAttribute('aria-describedby', 'gz-cabinet-success-copy');
        successTitle.focus({ preventScroll: true });
        successTitle.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }
    } finally {
      pending = false;
      if (form.dataset.complete !== 'true') render();
    }
  });
  dialog.addEventListener('gz:dialog-open', () => {
    if (form.dataset.complete === 'true') successTitle.focus({ preventScroll: true });
    else render(true);
  });
  controls.hidden = false;
  progress.hidden = false;
  stepLabel.hidden = false;
  form.noValidate = true;
  render();
})();
