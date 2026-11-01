(function () {
  'use strict';
  const root = document.getElementById('greendiz-design-study');
  if (!root) return;
  const plan = root.querySelector('#gz-plan');
  if (!plan || !window.GdpsyQuiz) return;
  const { money, estimate, baseLabels, dependencies } = window.GdpsyQuiz;
  const baseInputs = Array.from(root.querySelectorAll('input[name="gz-base"]'));
  const moduleInputs = Array.from(root.querySelectorAll('[data-gz-module]'));
  const modules = moduleInputs.map((input) => ({
    id: input.dataset.gzModule,
    label: input.dataset.label,
    price: Math.max(0, Number(input.dataset.cost) || 0),
    counted: input.type === 'number',
    minimum: input.dataset.minimum === 'true',
    requires: (input.dataset.requires || '').split(',').filter(Boolean),
    exclusive: input.dataset.exclusive || '',
    input,
  }));
  const baseName = root.querySelector('#gz-base-name');
  const basePrice = root.querySelector('#gz-base-price');
  const baseNote = root.querySelector('#gz-plan-base-note');
  const selectedLines = root.querySelector('#gz-plan-modules');
  const planSum = root.querySelector('#gz-plan-sum');
  const planEmpty = root.querySelector('#gz-plan-empty');
  const planSummary = root.querySelector('#gz-plan-summary');
  const planReset = root.querySelector('#gz-plan-reset');
  const contact = root.querySelector('#gz-contact');
  const defaultContactMessage = contact.querySelector('#gz-message').defaultValue;
  const mini = root.querySelector('#gz-mini-plan');
  const miniSum = root.querySelector('#gz-mini-sum');
  let miniDismissed = false;
  let planVisible = false;
  let miniFrame = null;
  const quantity = (module) => module.counted
    ? Math.max(0, Math.min(Number(module.input.max) || 99, Math.floor(Number(module.input.value) || 0)))
    : Number(module.input.checked);
  const setQuantity = (module, count) => {
    if (module.counted) module.input.value = String(Math.max(0, Math.min(Number(module.input.max) || 99, count)));
    else module.input.checked = count > 0;
  };
  const selectModules = (selection) => modules.forEach((module) => {
    const selected = selection.has(module.id);
    if (selected !== (quantity(module) > 0)) setQuantity(module, selected ? 1 : 0);
  });
  const selectedModules = () => modules.filter((module) => quantity(module) > 0);
  const moduleLabel = (module, count = quantity(module)) => module.label + (module.counted ? ': ' + count + ' шт.' : '');
  const modulePrice = (module, count = quantity(module)) => estimate(module.price * count, module.minimum);
  const minimumTotal = () => conceptSelected() || selectedModules().some((module) => module.minimum);
  const totalLabel = () => estimate(totalPrice(), minimumTotal());
  const hasBase = () => baseInputs.some((input) => input.checked);
  const conceptSelected = () => baseInputs.find((input) => input.checked)?.value === 'concept';
  const baseLabel = () => baseLabels[conceptSelected() ? 'concept' : 'scratch'];
  const previewBasePrice = () => {
    const base = Number(plan.dataset.base) || 0;
    const concept = Number(plan.dataset.concept) || 0;
    return conceptSelected() ? concept : base;
  };
  const currentBasePrice = () => hasBase() ? previewBasePrice() : 0;
  const totalPrice = () => currentBasePrice() + selectedModules().reduce((sum, module) => sum + module.price * quantity(module), 0);
  function ensureBase() {
    if (!hasBase()) baseInputs.find((input) => input.value === 'scratch').checked = true;
  }
  const { include: includeParents, dropOrphans } = dependencies(modules);
  function applyDependencies() {
    const selected = new Set(selectedModules().map((module) => module.id));
    modules.forEach((module) => {
      if (selected.has(module.id) && module.exclusive) {
        modules.forEach((item) => {
          if (item.id !== module.id && item.exclusive === module.exclusive) selected.delete(item.id);
        });
      }
    });
    dropOrphans(selected);
    selectModules(selected);
  }
  function setContactPreset(text) {
    contact.dispatchEvent(new CustomEvent('gz:contact-preset', { detail: { message: text } }));
  }
  function updateMini() {
    miniFrame = null;
    const away = !hasBase() || miniDismissed || planVisible || Boolean(root.querySelector('dialog[open]')) || document.body.classList.contains('lock');
    mini.hidden = !hasBase();
    mini.classList.toggle('is-away', away);
    mini.inert = away;
    mini.setAttribute('aria-hidden', String(away));
    const cookie = document.getElementById('cookie');
    const offset = cookie && !cookie.hidden ? cookie.offsetHeight + 24 : 14;
    mini.style.setProperty('--gz-mini-bottom', offset + 'px');
  }
  function scheduleMini() {
    if (miniFrame === null) miniFrame = window.requestAnimationFrame(updateMini);
  }
  function scrollToPlan() {
    plan.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  root.querySelector('#gz-mini-go').addEventListener('click', scrollToPlan);
  root.querySelector('#gz-mini-close').addEventListener('click', () => {
    miniDismissed = true;
    updateMini();
  });
  if ('IntersectionObserver' in window) {
    new IntersectionObserver((entries) => {
      planVisible = entries[0].isIntersecting;
      updateMini();
    }, { threshold: 0.2 }).observe(plan);
  } else {
    const measurePlan = () => {
      const rect = plan.getBoundingClientRect();
      const visible = Math.max(0, Math.min(rect.bottom, window.innerHeight) - Math.max(rect.top, 0));
      planVisible = rect.height > 0 && visible / rect.height >= 0.2;
      scheduleMini();
    };
    window.addEventListener('scroll', measurePlan, { passive: true });
    window.addEventListener('resize', measurePlan);
    measurePlan();
  }
  if ('MutationObserver' in window) {
    const observer = new MutationObserver(scheduleMini);
    root.querySelectorAll('dialog').forEach((dialog) => observer.observe(dialog, { attributes: true, attributeFilter: ['open'] }));
    observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    const cookie = document.getElementById('cookie');
    if (cookie) observer.observe(cookie, { attributes: true, attributeFilter: ['hidden', 'class', 'style'] });
  }
  window.addEventListener('resize', scheduleMini);
  window.addEventListener('pageshow', scheduleMini);
  document.addEventListener('gd:layout', scheduleMini);
  function renderPlan() {
    applyDependencies();
    if (selectedModules().length) ensureBase();
    const fromConcept = conceptSelected();
    planEmpty.hidden = hasBase();
    planSummary.hidden = !hasBase();
    if (planReset) planReset.hidden = !hasBase();
    root.querySelectorAll('#gz-cost .gz-module').forEach((card) => {
      const selected = selectedModules().some((module) => card.contains(module.input));
      card.classList.toggle('is-on', selected || Boolean(card.querySelector('input[name="gz-base"]:checked')));
    });
    baseName.textContent = baseLabel();
    basePrice.textContent = estimate(currentBasePrice(), fromConcept);
    baseNote.textContent = fromConcept
      ? 'Адаптация текстов и фото, до трёх информационных блоков. Механики концепта сохраняются.'
      : 'Индивидуальная разработка лендинга';
    selectedLines.replaceChildren();
    if (!selectedModules().length) {
      const empty = document.createElement('p');
      empty.className = 'gz-footnote';
      empty.textContent = 'Дополнительные модули не выбраны';
      selectedLines.appendChild(empty);
    }
    selectedModules().forEach((module) => {
      const line = document.createElement('div');
      line.className = 'gz-plan-line';
      const label = document.createElement('span');
      label.textContent = moduleLabel(module);
      const price = document.createElement('b');
      price.textContent = modulePrice(module);
      if (module.requires.length) {
        line.classList.add('gz-plan-line--sub');
        const dependency = document.createElement('small');
        dependency.className = 'gz-plan-dependency';
        dependency.textContent = 'К модулю «' + module.requires.map((id) => modules.find((item) => item.id === id)?.label).filter(Boolean).join('», «') + '»';
        label.appendChild(dependency);
      }
      if (selectedModules().some((item) => item.requires.includes(module.id))) {
        line.classList.add('gz-plan-line--parent');
      }
      line.append(label, price);
      selectedLines.appendChild(line);
    });
    planSum.textContent = totalLabel();
    miniSum.textContent = totalLabel();
    setContactPreset(hasBase() ? planMessage() : defaultContactMessage);
    updateMini();
  }
  function chooseBase(value) {
    baseInputs.forEach((input) => {
      input.checked = input.value === value;
    });
    miniDismissed = false;
    renderPlan();
  }
  function addModules(ids, base, counts) {
    const selected = new Set(selectedModules().map((module) => module.id));
    ids.forEach((id) => includeParents(selected, id));
    selectModules(selected);
    counts?.forEach((value, id) => {
      const module = modules.find((item) => item.id === id);
      if (module?.counted && ids.includes(id)) setQuantity(module, value);
    });
    if (base) baseInputs.forEach((input) => { input.checked = input.value === base; });
    ensureBase();
    miniDismissed = false;
    renderPlan();
  }
  baseInputs.forEach((input) => input.addEventListener('change', () => {
    if (input.checked) {
      chooseBase(input.value);
      return;
    }
    modules.forEach((module) => setQuantity(module, 0));
    miniDismissed = false;
    renderPlan();
  }));
  planReset?.addEventListener('click', () => {
    baseInputs.forEach((input) => { input.checked = false; });
    modules.forEach((module) => setQuantity(module, 0));
    miniDismissed = false;
    renderPlan();
    baseInputs[0]?.focus();
  });
  function changeModule(module) {
    setQuantity(module, quantity(module));
    if (quantity(module) > 0) {
      const selected = new Set(selectedModules().map((item) => item.id).filter((id) => id !== module.id));
      includeParents(selected, module.id);
      selectModules(selected);
      ensureBase();
    }
    miniDismissed = false;
    renderPlan();
  }
  modules.forEach((module) => {
    module.input.addEventListener('change', () => changeModule(module));
    if (module.counted) module.input.addEventListener('input', () => {
      miniDismissed = false;
      renderPlan();
    });
  });
  root.querySelectorAll('[data-quantity-step]').forEach((button) => {
    button.addEventListener('click', () => {
      const module = modules.find((item) => item.id === button.dataset.quantityTarget);
      if (!module) return;
      setQuantity(module, quantity(module) + Number(button.dataset.quantityStep));
      changeModule(module);
    });
  });
  function closeDialog(dialog) {
    if (dialog?.open) dialog.close();
  }
  function openDialog(id, opener = document.activeElement) {
    root.dispatchEvent(new CustomEvent('gz:open-dialog', { detail: { id, opener } }));
  }
  root.querySelectorAll('[data-add-modules]').forEach((button) =>
    button.addEventListener('click', () => {
      addModules(button.dataset.addModules.split(','));
      closeDialog(button.closest('dialog'));
      plan.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }),
  );
  function goToContact(text, opener) {
    setContactPreset(text);
    openDialog('gz-contact-dialog', opener);
  }
  function planMessage() {
    const lines = ['Здравствуйте, Анастасия!', 'Вот что у меня получилось в калькуляторе:', ''];
    if (hasBase()) {
      lines.push('Основа: ' + baseLabel() + (currentBasePrice() > 0 ? ' — ' + estimate(currentBasePrice(), conceptSelected()) : ''));
    } else {
      lines.push('Помогите выбрать основу и состав сайта.');
    }
    selectedModules().forEach((module) => lines.push(moduleLabel(module) + (module.price > 0 ? ' — ' + modulePrice(module) : '')));
    if (hasBase() && totalPrice() > 0) lines.push('', 'Предварительно: ' + totalLabel());
    else if (selectedModules().length && totalPrice() > 0) lines.push('', 'Дополнительные возможности: ' + totalLabel());
    if (selectedModules().some((module) => module.id === 'pages' || module.id === 'toolkit')) {
      lines.push('Для страниц и интерактивов указана минимальная стоимость. Организация каталога и PWA оцениваются отдельно.');
    }
    if (modules.some((module) => module.id === 'toolkit' && quantity(module) >= 5)) lines.push('Скидка за пять и более дополнительных интерактивов не учтена; условия согласуем при подборе.');
    lines.push('Давайте обсудим!');
    return lines.join('\n');
  }
  root.querySelector('#gz-plan-discuss').addEventListener('click', (event) => goToContact(planMessage(), event.currentTarget));
  const quizDialog = root.querySelector('#gz-quiz');
  window.GdpsyQuiz.create({
    root,
    dialog: quizDialog,
    texts: { intro: 'Это стартовый набор по вашим ответам. Меняйте что угодно: цена пересчитается сразу. При добавлении к сайту уже выбранные возможности сохранятся.' },
    discuss: goToContact,
    actions: (quiz) => [
      quiz.action('Добавить к сайту', 'gz-primary gz-button', () => {
        addModules(Array.from(quiz.picked), quiz.base, quiz.counts);
        closeDialog(quizDialog);
        plan.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }),
      quiz.action('Обсудить с Анастасией', 'gz-inline-button gz-text-action', (event) => quiz.discuss(quiz.message(), event.currentTarget)),
      quiz.action('Пройти ещё раз', 'gz-inline-button gz-text-action', quiz.restart),
    ],
  });
  window.addEventListener('pageshow', () => {
    applyDependencies();
    renderPlan();
  });
  applyDependencies();
  renderPlan();
  if (window.location.hash === '#gz-quiz') {
    window.history.replaceState(null, '', window.location.pathname + window.location.search);
    openDialog('gz-quiz');
  }
})();






