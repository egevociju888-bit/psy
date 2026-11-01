(function () {
  'use strict';
  document.querySelectorAll('[data-course-slider]').forEach((slider) => {
    const viewport = slider.querySelector('[data-course-viewport]');
    const slides = Array.from(viewport?.querySelectorAll('[data-course-slide]') || []);
    const prev = slider.querySelector('[data-course-prev]');
    const next = slider.querySelector('[data-course-next]');
    const counter = slider.querySelector('[data-course-counter]');
    const controls = slider.querySelector('[data-course-controls]');
    if (!viewport || slides.length < 2 || !prev || !next || !counter || !controls) return;
    const steps = Array.from(slider.querySelectorAll('[data-course-step]'));
    const descriptions = Array.from(slider.querySelectorAll('[data-course-description]'));
    const hasSteps = steps.length === slides.length && descriptions.length === slides.length;
    let current = 0;
    let width = viewport.clientWidth;
    const update = () => {
      if (viewport.clientWidth > 0 && viewport.clientWidth === width) current = Math.max(0, Math.min(slides.length - 1, Math.round(viewport.scrollLeft / viewport.clientWidth)));
      prev.disabled = current === 0;
      next.disabled = current === slides.length - 1;
      counter.textContent = (current + 1) + ' / ' + slides.length;
      if (hasSteps) steps.forEach((step, index) => {
        step.setAttribute('aria-expanded', String(index === current));
        descriptions[index].hidden = index !== current;
      });
    };
    const go = (index) => {
      const target = Math.max(0, Math.min(slides.length - 1, index));
      viewport.scrollTo({ left: target * viewport.clientWidth, behavior: 'smooth' });
    };
    prev.addEventListener('click', () => go(current - 1));
    next.addEventListener('click', () => go(current + 1));
    viewport.addEventListener('scroll', update, { passive: true });
    viewport.addEventListener('keydown', (event) => {
      const targets = { ArrowLeft: current - 1, ArrowRight: current + 1, Home: 0, End: slides.length - 1 };
      if (!(event.key in targets)) return;
      event.preventDefault();
      go(targets[event.key]);
    });
    const align = () => {
      width = viewport.clientWidth;
      go(current);
    };
    if (typeof ResizeObserver === 'function') new ResizeObserver(align).observe(viewport);
    else window.addEventListener('resize', align);
    if (hasSteps) steps.forEach((step, index) => {
      step.disabled = false;
      step.addEventListener('click', () => go(index));
    });
    controls.hidden = false;
    update();
  });
})();

