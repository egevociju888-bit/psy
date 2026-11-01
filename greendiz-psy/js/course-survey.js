(function () {
  'use strict';
  const form = document.getElementById('gz-survey-form');
  if (!form) return;
  const steps = Array.from(form.querySelectorAll('[data-course-step]'));
  if (!steps.length) return;
  const dialog = form.closest('dialog');
  const controls = form.querySelector('[data-course-controls]');
  const back = form.querySelector('[data-course-back]');
  const next = form.querySelector('[data-course-next]');
  const submit = form.querySelector('[type="submit"]');
  const status = form.querySelector('#gz-survey-status');
  const progress = form.querySelector('[data-course-progress]');
  const stepLabel = form.querySelector('[data-course-step-label]');
  const intro = dialog.querySelector('[data-course-intro]');
  const heading = dialog.querySelector('.gz-survey-heading');
  const successPanel = dialog.querySelector('[data-course-success]');
  const successTitle = successPanel.querySelector('h3');
  let current = 0;
  let pending = false;

  function render(focus = false) {
    steps.forEach((step, index) => { step.hidden = index !== current; });
    intro.hidden = current !== 0;
    back.disabled = current === 0;
    next.hidden = current === steps.length - 1;
    submit.hidden = !next.hidden;
    stepLabel.textContent = 'Шаг ' + (current + 1) + ' из ' + steps.length;
    progress.setAttribute('aria-valuenow', String(current + 1));
    progress.setAttribute('aria-valuetext', stepLabel.textContent);
    progress.style.setProperty('--gz-quiz-progress', ((current + 1) / steps.length * 100) + '%');
    if (focus) {
      steps[current].tabIndex = -1;
      steps[current].focus({ preventScroll: true });
      steps[current].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
  }
  function validate(step) {
    return Array.from(step.querySelectorAll('input, textarea')).every(input => input.disabled || input.reportValidity());
  }
  function advance() {
    if (pending || !validate(steps[current])) return;
    current = Math.min(current + 1, steps.length - 1);
    render(true);
  }
  next.addEventListener('click', advance);
  back.addEventListener('click', () => {
    if (pending) return;
    current = Math.max(0, current - 1);
    render(true);
  });
  form.addEventListener('submit', async event => {
    event.preventDefault();
    if (pending || form.dataset.complete === 'true') return;
    if (current < steps.length - 1) {
      advance();
      return;
    }
    const invalidStep = steps.findIndex(step => Array.from(step.querySelectorAll('input, textarea')).some(input => !input.disabled && !input.checkValidity()));
    if (invalidStep !== -1) {
      current = invalidStep;
      render(true);
      validate(steps[current]);
      return;
    }
    pending = true;
    back.disabled = true;
    try {
      const result = await window.GdpsyForms.submit(form, status);
      if (result.success) {
        form.dataset.complete = 'true';
        form.querySelectorAll('input, textarea, button').forEach(input => { input.disabled = true; });
        form.hidden = true;
        intro.hidden = true;
        heading.hidden = true;
        successPanel.hidden = false;
        dialog.classList.add('is-complete');
        dialog.setAttribute('aria-labelledby', 'gz-course-success-title');
        dialog.setAttribute('aria-describedby', 'gz-course-success-copy');
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
