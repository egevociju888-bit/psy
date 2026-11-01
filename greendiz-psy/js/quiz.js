(function () {
  'use strict';
  const formatter = new Intl.NumberFormat('ru-RU');
  const money = (value) => (value > 0 ? formatter.format(value) + ' ₽' : '');
  const estimate = (value, minimum) => (value > 0 ? (minimum ? 'от ' : '') + money(value) : '');
  const baseLabels = { scratch: 'Лендинг под ключ', concept: 'Лендинг на основе концепта' };
  const baseCards = {
    scratch: { title: 'Индивидуальная разработка' },
    concept: { title: 'На основе готового концепта' },
  };
  const baseIncluded = '5–10 блоков, один интерактив, контактная форма, помощь с текстами и фото, инструкция и месяц поддержки. Всё, что ниже, добавляется к ней.';
  const footnote = 'Расчёт предварительный, тк некоторые позиции требуют уточнения деталей. Окончательную стоимость, включая скидки, скажу сразу после обсуждения подробностей.';
  const moduleOrder = ['blog', 'autopost', 'toolkit', 'pages', 'mail'];
  const moduleCopy = {
    blog: { title: 'Блог', why: 'приводит людей из поиска и знакомит с вашим подходом. Пять стартовых статей входят в подключение.' },
    autopost: { title: 'Автопубликация', why: 'экономит время: достаточно опубликовать статью на сайте, анонс сам уйдёт в Telegram, VK и MAX.' },
    toolkit: { title: 'Интерактивы', pack: 'Интерактивы: пакет', stepper: 'шт. сверх входящего в лендинг', why: 'клиент продолжает работать между сессиями. Один интерактив входит в лендинг, остальные — отдельно, от пяти — со скидкой.' },
    pages: { title: 'Страница курса', why: 'люди сразу видят программу, даты, условия и оставляют заявку сами. Закрытый доступ с личным кабинетом обсуждается и оценивается отдельно.' },
    mail: { title: 'Email-рассылка', lead: 'Когда нужна:', why: 'помогает бережно напоминать о себе тем, кто уже подписался: новости, материалы, анонсы.' },
  };
  const booking = {
    title: 'Онлайн-запись и приём платежей',
    price: 'оценим отдельно',
    lead: 'Когда нужна:',
    why: 'если клиенты пишут в мессенджер и ищут время вручную: запись и оплата избавят от переписки. Готового модуля пока нет, оценим отдельно, в сумму не входит.',
  };
  const questions = [
    {
      q: 'Что для вас важнее?',
      short: 'Приоритет',
      bare: true,
      impact: 'Этот ответ определяет основу сайта. «С нуля» — дизайн и структура собираются под вашу практику. Остальные три ответа ведут к готовому концепту: его оформление можно посмотреть до начала работы, остаётся адаптировать тексты и фото, поэтому быстрее и дешевле. На итоговом экране основу можно переключить.',
      answers: [
        { text: 'Чтобы сайт был собран под меня с нуля', summary: 'сайт с нуля, под меня', base: 'scratch', mods: [], why: 'вам важно, чтобы сайт был собран именно под вашу практику: дизайн и структура создаются с нуля под ваши задачи.' },
        { text: 'Запуститься побыстрее', summary: 'запуститься побыстрее', base: 'concept', mods: [], why: 'вам важно запуститься побыстрее, в готовом концепте дизайн уже готов, вы видите, как будет выглядеть сайт, остаётся только добавить свои тексты и фото.' },
        { text: 'Потратить меньше', summary: 'желательно потратить меньше', base: 'concept', mods: [], why: 'вам важно потратить меньше: адаптация готового концепта обходится дешевле индивидуальной разработки, потому что дизайн уже есть, остаётся добавить свои тексты и фото.' },
        { text: 'Сразу увидеть, как будет выглядеть мой сайт', summary: 'сразу увидеть, как будет выглядеть сайт', base: 'concept', mods: [], why: 'вам важно сразу увидеть, как будет выглядеть сайт: концепт можно посмотреть до начала работы, остаётся адаптировать его под ваши тексты и фото.' },
      ],
    },
    {
      q: 'Пишете ли вы посты или статьи?',
      short: 'Канал или соцсети',
      impact: 'Блог — это раздел со статьями на сайте. Если вы пишете или хотите начать, я добавлю его в рекомендацию. Если ведёте канал или соцсети, добавлю и автопубликацию: анонс новой статьи сам уйдёт в подключённые каналы. Пять стартовых статей входят в подключение. Если блог сейчас не нужен, его можно добавить позже.',
      answers: [
        { text: 'Да, веду канал или соцсети', summary: 'веду', mods: ['blog', 'autopost'] },
        { text: 'Хочу начать', summary: 'хочу начать', mods: ['blog'] },
        { text: 'Нет, это не моё', summary: 'не веду', mods: [] },
      ],
    },
    {
      q: 'Даёте ли вы клиентам упражнения или опросники между сессиями?',
      short: 'Материалы',
      impact: 'Интерактивные, красиво оформленные тесты, упражнения и практики можно разместить прямо у вас на сайте. Один такой интерактив уже входит в стоимость лендинга. Если нужны ещё один-два, они появятся в рекомендации отдельными пунктами. Если хотите собрать подборку, предложу пакет интерактивов с общим каталогом и скидкой за количество. Организацию каталога обсудим отдельно.',
      answers: [
        { text: 'Да, один-два: тест или упражнение', summary: 'один-два: тест или упражнение', mods: ['toolkit'], counts: { toolkit: 1 } },
        { text: 'Да, хочу собрать подборку', summary: 'хочу собрать подборку', mods: ['toolkit'], counts: { toolkit: 5 } },
        { text: 'Нет, пока не даю', summary: 'нет, пока не даю', mods: [] },
      ],
    },
    {
      q: 'Как клиенты записываются к вам сейчас?',
      short: 'Запись на сессии',
      impact: 'Этот ответ не меняет состав и цену сайта: он попадёт в ваше сообщение, когда вы решите обсудить сайт. Если у вас уже есть сервис записи, на сайте будет кнопка, которая ведёт в него. Онлайн-запись с оплатой обсуждается и оценивается отдельно.',
      answers: [
        { text: 'Через сервис записи', summary: 'через сервис записи', mods: [] },
        { text: 'Пишут в мессенджер, мы подбираем день и время', summary: 'пишут в мессенджер, подбираем день и время', mods: [] },
        { text: 'Я только начинаю практику', summary: 'только начинаю практику', mods: [] },
      ],
    },
    {
      q: 'Проводите ли вы группы, курсы или интенсивы?',
      short: 'Курсы',
      impact: 'Для живых групп и интенсивов я добавлю страницу курса: программа, даты и заявка. Если нужен закрытый доступ к урокам с полноценным личным кабинетом на сайте, это нужно будет обсудить и оценить отдельно. Если курсов нет, ничего не добавится.',
      answers: [
        { text: 'Да, живые группы или интенсивы', summary: 'живые группы или интенсивы', mods: ['pages'] },
        { text: 'Да, уроки в записи с доступом после оплаты', summary: 'уроки в записи с доступом после оплаты', mods: ['pages'] },
        { text: 'Пока только планирую', summary: 'пока только планирую', mods: ['pages'] },
        { text: 'Нет, и не планирую', summary: 'нет, не планирую', mods: [] },
      ],
    },
  ];

  const baseAlt = {
    scratch: questions[0].answers[0].why,
    concept: 'вам важно запуститься побыстрее, потратить меньше или сразу увидеть, как будет выглядеть сайт: в готовом концепте дизайн уже есть, остаётся добавить свои тексты и фото.',
  };
  const optionalCopy = {
    blog: { lead: 'Когда нужен:', why: 'если вы пишете или хотите начать писать статьи: блог приводит людей из поиска и знакомит с вашим подходом. Пять стартовых статей входят в подключение.' },
    autopost: { lead: 'Когда нужна:', why: 'если ведёте канал или соцсети: достаточно опубликовать статью на сайте, анонс сам уйдёт в Telegram, VK и MAX. Работает вместе с блогом.' },
    toolkit: { lead: 'Когда нужны:', why: 'если даёте клиентам тесты, упражнения или практики между сессиями. Один интерактив входит в лендинг, остальные — отдельно, от пяти — со скидкой.' },
    pages: { lead: 'Когда нужна:', why: 'если проводите живые группы, курсы или интенсивы: программа, даты, условия и заявка на одной странице. Закрытый доступ с личным кабинетом обсуждается и оценивается отдельно.' },
  };

  function dependencies(modules) {
    function include(selection, id) {
      const module = modules.find((item) => item.id === id);
      if (!module || selection.has(id)) return;
      if (module.exclusive) {
        modules.forEach((item) => {
          if (item.id !== id && item.exclusive === module.exclusive) selection.delete(item.id);
        });
      }
      selection.add(id);
      module.requires.forEach((parent) => include(selection, parent));
    }
    function dropOrphans(selection) {
      let changed = true;
      while (changed) {
        changed = false;
        modules.forEach((module) => {
          if (selection.has(module.id) && module.requires.some((parent) => !selection.has(parent))) {
            selection.delete(module.id);
            changed = true;
          }
        });
      }
    }
    return { include, dropOrphans };
  }

  function node(tag, className, text) {
    const element = document.createElement(tag);
    if (className) element.className = className;
    if (text !== undefined) element.textContent = text;
    return element;
  }

  function action(text, className, callback) {
    const button = node('button', className, text);
    button.type = 'button';
    button.addEventListener('click', callback);
    return button;
  }

  function icon(path) {
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    const line = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('aria-hidden', 'true');
    line.setAttribute('d', path);
    svg.appendChild(line);
    return svg;
  }

  function create(options) {
    const { root, dialog } = options;
    if (!root || !dialog) return null;
    let config;
    try {
      config = JSON.parse(dialog.dataset.quizConfig || '');
    } catch {
      return null;
    }
    const texts = options.texts || {};
    const modules = config.modules.map((module) => ({ requires: [], exclusive: '', ...module }));
    const prices = config.prices;
    const deps = dependencies(modules);
    const content = dialog.querySelector('#gz-quiz-content');
    const scroller = dialog.querySelector('.gz-quiz-scroll');
    const controls = dialog.querySelector('#gz-quiz-controls');
    const summary = dialog.querySelector('#gz-quiz-summary');
    const hint = dialog.querySelector('#gz-quiz-hint');
    const hintText = dialog.querySelector('#gz-quiz-hint-text');
    const back = dialog.querySelector('#gz-quiz-back');
    const next = dialog.querySelector('#gz-quiz-next');
    const status = dialog.querySelector('#gz-quiz-status');
    const progress = dialog.querySelector('.gz-quiz-progress');
    const title = dialog.querySelector('#gz-quiz-title');
    const defaultTitle = [...(title?.children || [])].map((part) => part.textContent);
    let step = 0;
    const answers = [];
    let state = null;
    let hintOpen = false;
    let progressFrame = null;
    let totalObserver = null;

    const chosen = (question, index) => question.answers[answers[index]];
    const recommendedBase = () => {
      for (let index = 0; index < questions.length; index += 1) {
        const answer = chosen(questions[index], index);
        if (answer?.base) return { key: answer.base, why: answer.why };
      }
      return { key: 'scratch', why: '' };
    };
    const moduleById = (id) => modules.find((module) => module.id === id);
    const moduleLabel = (module, count) => module.label + (module.counted ? ': ' + count + ' шт.' : '');
    const count = (module) => (module.counted ? state.counts.get(module.id) || 1 : 1);
    const modulePrice = (module) => estimate(module.price * count(module), module.minimum);
    const basePrice = (key) => (key === 'concept' ? prices.concept : prices.scratch);
    const recommendations = () => {
      const ids = new Set();
      questions.forEach((question, index) => chosen(question, index)?.mods.forEach((id) => deps.include(ids, id)));
      return moduleOrder.filter((id) => ids.has(id));
    };
    const createState = (ids) => {
      const counts = new Map();
      questions.forEach((question, index) => Object.entries(chosen(question, index)?.counts || {}).forEach(([id, value]) => counts.set(id, value)));
      return { picked: new Set(ids), counts, base: recommendedBase().key, booking: false };
    };
    const total = () => {
      const list = modules.filter((module) => state.picked.has(module.id));
      const sum = basePrice(state.base) + list.reduce((value, module) => value + module.price * count(module), 0);
      return { list, sum, minimum: state.base === 'concept' || list.some((module) => module.minimum) };
    };
    const discuss = options.discuss || ((text, opener) => {
      root.querySelector('#gz-contact')?.dispatchEvent(new CustomEvent('gz:contact-preset', { detail: { message: text } }));
      root.dispatchEvent(new CustomEvent('gz:open-dialog', { detail: { id: 'gz-contact-dialog', opener } }));
    });

    back.addEventListener('click', () => {
      if (step < 1 || step >= questions.length) return;
      step -= 1;
      render();
    });
    next.addEventListener('click', () => {
      if (step >= questions.length || !Number.isInteger(answers[step])) return;
      step += 1;
      render();
    });
    hint.querySelector('summary').addEventListener('click', () => {
      hintOpen = !hintOpen;
    });
    root.addEventListener('gz:dialog-prepare', (event) => {
      if (event.target === dialog) render();
    });
    root.addEventListener('gz:dialog-open', (event) => {
      if (event.target === dialog) focus();
    });

    function setTitle(parts, inline) {
      [...(title?.children || [])].forEach((part, index) => {
        part.textContent = parts[index] || '';
      });
      title?.classList.toggle('gz-quiz-title-inline', Boolean(inline));
    }

    function focus() {
      if (!dialog.open) return;
      const heading = content.querySelector('h3') || title;
      if (!heading) return;
      heading.tabIndex = -1;
      heading.focus({ preventScroll: true });
      heading.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function updateProgress() {
      const complete = step >= questions.length;
      const value = complete ? 100 : ((step + 0.5) / questions.length) * 100;
      progress.setAttribute('aria-valuenow', String(value));
      progress.setAttribute('aria-valuetext', complete ? 'Подбор завершён' : 'Вопрос ' + (step + 1) + ' из ' + questions.length);
      if (progressFrame !== null) window.cancelAnimationFrame(progressFrame);
      progressFrame = window.requestAnimationFrame(() => {
        progress.style.setProperty('--gz-quiz-progress', value + '%');
        progressFrame = null;
      });
    }

    function renderQuestion() {
      const question = questions[step];
      const heading = node('h3', 'gz-quiz-question', question.q);
      heading.id = 'gz-quiz-question';
      content.append(node('p', 'gz-quiz-step', 'Вопрос ' + (step + 1) + ' из ' + questions.length), heading);
      const list = node('div', 'gz-quiz-answers');
      list.setAttribute('role', 'radiogroup');
      list.setAttribute('aria-labelledby', heading.id);
      question.answers.forEach((answer, index) => {
        const label = node('label', 'gz-quiz-answer');
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'gz-quiz-answer';
        input.value = String(index);
        input.checked = answers[step] === index;
        input.addEventListener('change', () => {
          if (!input.checked) return;
          answers[step] = index;
          state = null;
          next.disabled = false;
        });
        label.append(input, node('span', '', answer.text));
        list.appendChild(label);
      });
      content.appendChild(list);
      hintText.textContent = question.impact;
      hint.open = hintOpen;
      hint.dispatchEvent(new Event('gz-details-reset'));
      hint.hidden = false;
      status.textContent = 'Вопрос ' + (step + 1) + ' из ' + questions.length + ': ' + question.short;
    }

    function message() {
      const lines = ['Здравствуйте, Анастасия! Хочу обсудить создание сайта.', '', 'О моей практике и предпочтениях:'];
      questions.forEach((question, index) => lines.push('• ' + (question.bare ? '' : question.short + ': ') + chosen(question, index).summary));
      lines.push('', 'Рекомендованный состав:', '• ' + baseLabels[state.base]);
      const { list, sum, minimum } = total();
      list.forEach((module) => lines.push('• ' + moduleLabel(module, count(module)) + (module.price > 0 ? ' — ' + modulePrice(module) : '')));
      if (state.booking) lines.push('• ' + booking.title + ' — ' + booking.price);
      if (sum > 0) lines.push('Предварительно: ' + estimate(sum, minimum));
      lines.push('', 'Давайте обсудим!');
      return lines.join('\n');
    }

    function group(text, id) {
      const heading = node('h4', 'gz-quiz-group', text);
      heading.id = id;
      return heading;
    }

    function labelled(lead, text, className) {
      const paragraph = node('p', className);
      paragraph.append(node('b', '', lead), ' ' + text);
      return paragraph;
    }

    function renderResult() {
      const ids = recommendations();
      if (!state) state = createState(ids);
      const recommended = recommendedBase();
      const refreshers = [];
      const result = node('div', 'gz-quiz-result');
      setTitle(ids.length ? ['Что я', 'рекомендую'] : ['Начнём', 'с лендинга'], true);
      const intro = ids.length ? texts.intro : 'Остальное можно добавить позже, когда появится потребность.';
      if (intro) result.appendChild(node('p', 'gz-quiz-intro', intro));

      result.appendChild(group('Основа сайта — главная страница', 'gz-quiz-base-title'));
      result.appendChild(node('p', 'gz-quiz-included', baseIncluded));
      const bases = node('div', 'gz-quiz-modules');
      bases.setAttribute('role', 'radiogroup');
      bases.setAttribute('aria-labelledby', 'gz-quiz-base-title');
      Object.keys(baseCards).forEach((key) => {
        const label = node('label', 'gz-quiz-module gz-quiz-base');
        const input = document.createElement('input');
        input.type = 'radio';
        input.name = 'gz-quiz-base';
        input.value = key;
        input.addEventListener('change', () => {
          if (!input.checked) return;
          state.base = key;
          refresh();
        });
        label.append(input, node('span', 'gz-quiz-module-title', baseCards[key].title), node('b', 'gz-quiz-module-price', estimate(basePrice(key), key === 'concept')));
        refreshers.push(() => { input.checked = state.base === key; });
        bases.appendChild(label);
      });
      result.appendChild(bases);
      const reasonLead = node('b');
      const reasonText = document.createTextNode('');
      const reasonLink = node('a', '', 'Посмотреть концепты');
      reasonLink.href = 'https://psy.greendiz.ru/portfolio/?case-type=consept';
      reasonLink.target = '_blank';
      reasonLink.rel = 'noopener';
      const reason = node('p', 'gz-quiz-reason');
      reason.append(reasonLead, reasonText, reasonLink);
      refreshers.push(() => {
        const own = state.base === recommended.key;
        const text = own ? recommended.why : baseAlt[state.base];
        reasonLead.textContent = own ? 'Почему советую этот вариант:' : 'Подойдёт лучше, если:';
        reasonText.textContent = ' ' + (text || '') + ' ';
        reasonLink.hidden = state.base !== 'concept';
        reason.hidden = !text;
      });
      result.appendChild(reason);

      const moduleRow = (module, copy) => {
        const label = node('label', 'gz-quiz-module');
        const input = document.createElement('input');
        input.type = 'checkbox';
        input.name = 'gz-quiz-module';
        input.value = module.id;
        input.addEventListener('change', () => {
          if (input.checked) deps.include(state.picked, module.id);
          else {
            state.picked.delete(module.id);
            deps.dropOrphans(state.picked);
          }
          refresh();
        });
        const title = node('span', 'gz-quiz-module-title');
        const price = node('b', 'gz-quiz-module-price');
        label.append(input, title, price, labelled(copy.lead || 'Почему советую:', copy.why, 'gz-quiz-module-why'));
        let output = null;
        let minus = null;
        if (copy.stepper) {
          output = node('output');
          minus = action('−', 'gz-quiz-qty-button', () => change(-1));
          minus.setAttribute('aria-label', 'Меньше');
          const plus = action('+', 'gz-quiz-qty-button', () => change(1));
          plus.setAttribute('aria-label', 'Больше');
          const qty = node('div', 'gz-quiz-qty');
          qty.append(node('div', 'gz-quiz-qty-control'), node('span', '', copy.stepper));
          qty.firstChild.append(minus, output, plus);
          label.appendChild(qty);
        }
        function change(delta) {
          state.counts.set(module.id, Math.max(1, Math.min(99, count(module) + delta)));
          deps.include(state.picked, module.id);
          refresh();
        }
        refreshers.push(() => {
          input.checked = state.picked.has(module.id);
          title.textContent = copy.pack && count(module) >= 5 ? copy.pack : copy.title;
          price.textContent = modulePrice(module);
          if (output) {
            output.textContent = String(count(module));
            minus.disabled = count(module) <= 1;
          }
        });
        return label;
      };

      const added = moduleOrder.map(moduleById).filter((module) => module && ids.includes(module.id));
      if (added.length) {
        result.appendChild(group('Добавлено по вашим ответам', 'gz-quiz-added-title'));
        const list = node('div', 'gz-quiz-modules');
        added.forEach((module) => list.appendChild(moduleRow(module, moduleCopy[module.id])));
        result.appendChild(list);
      }
      result.appendChild(group('Можно добавить', 'gz-quiz-optional-title'));
      const optional = node('div', 'gz-quiz-modules');
      moduleOrder.map(moduleById).filter((module) => module && !ids.includes(module.id)).forEach((module) => optional.appendChild(moduleRow(module, { ...moduleCopy[module.id], ...optionalCopy[module.id] })));
      const bookingRow = node('label', 'gz-quiz-module');
      const bookingInput = document.createElement('input');
      bookingInput.type = 'checkbox';
      bookingInput.name = 'gz-quiz-module';
      bookingInput.addEventListener('change', () => {
        state.booking = bookingInput.checked;
        refresh();
      });
      bookingRow.append(bookingInput, node('span', 'gz-quiz-module-title', booking.title), node('b', 'gz-quiz-module-price gz-quiz-module-price--muted', booking.price), labelled(booking.lead, booking.why, 'gz-quiz-module-why'));
      refreshers.push(() => { bookingInput.checked = state.booking; });
      optional.appendChild(bookingRow);
      result.appendChild(optional);
      const totalTitle = group('Состав вашего сайта', 'gz-quiz-total-title');
      const totalList = node('div', 'gz-quiz-total-list');
      const totalCard = node('div', 'gz-quiz-total');
      totalCard.setAttribute('role', 'group');
      totalCard.setAttribute('aria-labelledby', 'gz-quiz-total-title');
      totalCard.append(totalTitle, totalList);
      result.appendChild(totalCard);
      const totalRow = (label, value, className) => {
        const row = node('div', 'gz-quiz-total-row' + (className ? ' ' + className : ''));
        row.append(node('span', '', label), node('b', value === booking.price ? 'gz-quiz-module-price--muted' : '', value));
        return row;
      };
      const totalLabel = (module) => {
        const copy = moduleCopy[module.id];
        const number = count(module);
        if (copy.pack && number >= 5) return copy.pack + ', ' + number + ' шт.';
        return copy.title + (module.counted && number > 1 ? ': ' + number + ' шт.' : '');
      };
      refreshers.push(() => {
        const { list, sum, minimum } = total();
        const rows = [totalRow(baseCards[state.base].title, estimate(basePrice(state.base), state.base === 'concept'))];
        moduleOrder.map(moduleById).filter((module) => list.includes(module)).forEach((module) => rows.push(totalRow(totalLabel(module), modulePrice(module))));
        if (state.booking) rows.push(totalRow(booking.title, booking.price));
        rows.push(totalRow('Итого', estimate(sum, minimum), 'gz-quiz-total-sum'));
        totalList.replaceChildren(...rows);
        totalCard.hidden = sum <= 0;
      });
      result.appendChild(node('p', 'gz-footnote', (prices.annual > 0 ? '+ домен и хостинг — около ' + money(prices.annual) + ' в год.' : 'Домен и хостинг оплачиваются отдельно.') + ' Предоплата 50%. ' + footnote));

      const sumValue = node('b');
      sumValue.setAttribute('aria-live', 'polite');
      const actions = node('div', 'gz-quiz-actions');
      result.appendChild(actions);
      const main = action('', 'gz-mini-main', () => [dialog, scroller].forEach((box) => box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' })));
      main.setAttribute('aria-label', 'Перейти к итогу');
      main.append(node('span', '', 'Состав вашего сайта'), sumValue, icon('M5 12h14m-6-6 6 6-6 6'));
      const close = action('', 'gz-mini-close gz-icon-action', () => { summary.hidden = true; });
      close.setAttribute('aria-label', 'Скрыть расчёт');
      close.appendChild(icon('m6 6 12 12M18 6 6 18'));
      function refresh() {
        refreshers.forEach((update) => update());
        const { sum, minimum } = total();
        sumValue.textContent = estimate(sum, minimum);
        summary.hidden = sum <= 0;
      }
      const context = {
        dialog,
        action,
        discuss,
        message,
        get base() { return state.base; },
        get picked() { return state.picked; },
        get counts() { return state.counts; },
        edit: () => {
          step = 0;
          render();
        },
        restart: () => {
          step = 0;
          answers.length = 0;
          state = null;
          render();
        },
      };
      actions.append(...options.actions(context));
      summary.replaceChildren(main, close);
      refresh();
      content.appendChild(result);
      if ('IntersectionObserver' in window) {
        totalObserver = new IntersectionObserver(([entry]) => {
          summary.classList.toggle('is-away', entry.isIntersecting);
          summary.inert = entry.isIntersecting;
        });
        totalObserver.observe(totalCard);
      }
      const names = added.map((module) => module.label).join(', ');
      status.textContent = ids.length ? 'Результат: рекомендую ' + names : 'Результат: начнём с лендинга';
    }

    function render() {
      totalObserver?.disconnect();
      totalObserver = null;
      summary.classList.remove('is-away');
      summary.inert = false;
      controls.hidden = step >= questions.length;
      summary.hidden = true;
      summary.replaceChildren();
      hint.hidden = true;
      back.disabled = step < 1;
      next.disabled = !Number.isInteger(answers[step]);
      updateProgress();
      content.replaceChildren();
      setTitle(defaultTitle, false);
      if (step < questions.length) renderQuestion();
      else renderResult();
      focus();
    }

    return { render };
  }

  window.GdpsyQuiz = { questions, baseLabels, money, estimate, dependencies, create };
})();
