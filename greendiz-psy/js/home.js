(function () {
  'use strict';
  const root = document.getElementById('greendiz-design-study');
  if (!root) return;
  const stage = root.querySelector('.gz-concept-stage');
  const grid = root.querySelector('.gz-concept-grid');
  if (!stage || !grid) return;
  const owner = grid.querySelector('.gz-concept-owner');
  const button = grid.querySelector('#gz-concepts-more');
  const extraCards = Array.from(grid.querySelectorAll('[data-extra-concept]'));
  const status = root.querySelector('#gz-concepts-status');
  if (!button || !owner) return;
  grid.classList.remove('is-expanded');
  extraCards.forEach((card) => { card.hidden = true; });
  button.hidden = false;
  const archiveUrl = button.dataset.archive;
  let revealing = false;

  button.addEventListener('click', async function () {
    if (revealing) return;
    revealing = true;
    const retainFocus = document.activeElement === button;
    const previousHeight = stage.getBoundingClientRect().height;
    const previousOwner = owner.getBoundingClientRect();
    const animations = [];
    button.setAttribute('aria-busy', 'true');
    button.setAttribute('aria-expanded', 'true');
    stage.style.height = previousHeight + 'px';
    stage.style.overflow = 'clip';
    extraCards.forEach((card) => {
      card.hidden = false;
    });
    grid.classList.add('is-expanded');
    const nextHeight = grid.getBoundingClientRect().height;
    const nextOwner = owner.getBoundingClientRect();
    const timing = { duration: 720, easing: 'cubic-bezier(.22, .61, .36, 1)', fill: 'both' };

    if (typeof stage.animate === 'function') {
      animations.push(
        stage.animate([{ height: previousHeight + 'px' }, { height: nextHeight + 'px' }], timing),
      );
      animations.push(
        owner.animate(
          [
            {
              transform:
                'translate(' +
                (previousOwner.left - nextOwner.left) +
                'px, ' +
                (previousOwner.top - nextOwner.top) +
                'px)',
              width: previousOwner.width + 'px',
              height: previousOwner.height + 'px',
            },
            { transform: 'translate(0, 0)', width: nextOwner.width + 'px', height: nextOwner.height + 'px' },
          ],
          timing,
        ),
      );
      await Promise.all(animations.map((animation) => animation.finished.catch(function () {})));
    } else {
      stage.style.transition = 'height .72s cubic-bezier(.22, .61, .36, 1)';
      stage.getBoundingClientRect();
      stage.style.height = nextHeight + 'px';
      await new Promise((resolve) => window.setTimeout(resolve, 720));
    }

    stage.style.removeProperty('height');
    stage.style.removeProperty('overflow');
    stage.style.removeProperty('transition');
    animations.forEach((animation) => animation.cancel());
    const archiveLink = document.createElement('a');
    archiveLink.className = button.className;
    archiveLink.id = button.id;
    archiveLink.href = archiveUrl;
    archiveLink.target = '_blank';
    archiveLink.rel = 'noopener noreferrer';
    archiveLink.textContent = 'Все концепты';
    button.replaceWith(archiveLink);
    status.textContent = 'Показаны все выбранные концепты: ' + grid.querySelectorAll('.gz-work').length + '.';
    if (retainFocus && document.activeElement === document.body) archiveLink.focus({ preventScroll: true });
  });
})();

(function () {
  'use strict';
  const gallery = document.getElementById('gz-concepts');
  if (!gallery) return;
  gallery.addEventListener('click', function (event) {
    const link = event.target.closest('.gz-work a[href="#"]');
    if (link) event.preventDefault();
  });
})();

(function () {
  'use strict';
  const root = document.getElementById('greendiz-design-study');
  if (!root) return;
  const card = root.querySelector('#gz-breathe-demo');
  if (!card) return;
  const button = card.querySelector('#gz-breathe-button');
  const buttonLabel = button.querySelector('span');
  const player = card.querySelector('#gz-breathe-player');
  const settings = card.querySelector('#gz-breathe-settings');
  const settingsButton = card.querySelector('#gz-breathe-settings-button');
  const complete = card.querySelector('#gz-breathe-complete');
  const repeat = card.querySelector('#gz-breathe-repeat');
  const soundButton = card.querySelector('#gz-breathe-sound');
  const vibration = card.querySelector('#gz-breathe-vibration');
  const figure = card.querySelector('.gz-breathe-figure');
  const rings = card.querySelector('.gz-breathe-rings');
  const disc = card.querySelector('.gz-breathe-disc');
  const wave = card.querySelector('.gz-breathe-wave');
  const waveDot = card.querySelector('.gz-breathe-wave-dot');
  const lungs = card.querySelector('.gz-breathe-lungs');
  const counter = card.querySelector('.gz-breathe-count');
  const phaseLabel = card.querySelector('.gz-breathe-phase');
  const hint = card.querySelector('.gz-breathe-hint');
  const left = card.querySelector('#gz-breathe-left');
  const visualInputs = Array.from(card.querySelectorAll('[name="gz-breathe-visual"]'));
  const soundInputs = Array.from(card.querySelectorAll('[name="gz-breathe-sound"]'));
  const storageKey = 'greendiz-breathe-settings';
  const phases = [
    { id: 'inhale', start: 0, duration: 4000, label: 'Вдох носом', hint: 'медленно и бесшумно' },
    { id: 'hold', start: 4000, duration: 7000, label: 'Пауза', hint: 'без напряжения' },
    { id: 'exhale', start: 11000, duration: 8000, label: 'Выдох ртом', hint: 'плавно и без усилия' },
  ];
  const cycle = phases.reduce((sum, phase) => sum + phase.duration, 0);
  const duration = Math.ceil(60000 / cycle) * cycle;
  let state = 'idle';
  let elapsed = 0;
  let startedAt = 0;
  let frame = null;
  let finishTimer = null;
  let currentPhase = null;
  let resumeAfterSettings = false;
  let soundEnabled = false;
  const audio = window.GzBreathAudio;
  const feedback = card.querySelector('#gz-breathe-feedback');
  const canVibrate = typeof navigator.vibrate === 'function';
  let audioRequest = 0;
  let audioMode = null;

  function syncAudio() {
    const request = ++audioRequest;
    const wanted = state === 'running' && soundEnabled && !document.hidden;
    if (!wanted) {
      audioMode = null;
      audio?.stop();
      return;
    }
    if (audioMode === card.dataset.sound) return;
    const mode = card.dataset.sound;
    audioMode = mode;
    if (!audio?.ok) return;
    audio.start(mode).then(started => {
      if (request !== audioRequest) return;
      if (!started) {
        audioMode = null;
        setSound(false);
        saveSettings();
        feedback.textContent = 'Не удалось включить звук. Попробуйте ещё раз.';
        feedback.hidden = false;
      } else {
        feedback.hidden = true;
      }
    });
  }

  function vibrate(pattern) {
    if (!canVibrate) return;
    try { navigator.vibrate(pattern); } catch (error) {}
  }

  function stopVibration() {
    if (canVibrate) vibrate(0);
  }

  function saveSettings() {
    try {
      window.localStorage.setItem(storageKey, JSON.stringify({
        visual: figure.dataset.view,
        sound: card.dataset.sound,
        soundEnabled,
        vibration: vibration.checked,
      }));
    } catch (error) {}
  }

  function setSound(enabled) {
    soundEnabled = enabled;
    soundButton.setAttribute('aria-pressed', String(enabled));
    soundButton.setAttribute('aria-label', enabled ? 'Выключить звук' : 'Включить звук');
    card.dataset.soundEnabled = String(enabled);
    syncAudio();
  }

  function setVisual(value) {
    figure.dataset.view = value;
    visualInputs.forEach(input => { input.checked = input.value === value; });
    rings.querySelectorAll('[data-breathe-visual]').forEach(visual => {
      visual.toggleAttribute('hidden', visual.dataset.breatheVisual !== value);
    });
  }

  function eased(value) {
    return (1 - Math.cos(Math.PI * value)) / 2;
  }

  function breathProgress(time) {
    if (time < 4000) return eased(time / 4000);
    if (time < 11000) return 1;
    return 1 - eased((time - 11000) / 8000);
  }

  function draw(time) {
    const progress = breathProgress(time);
    if (state === 'running' && soundEnabled) audio?.setBreath(progress);
    disc.style.transform = 'scale(' + (0.38 + progress * 0.62).toFixed(4) + ')';
    waveDot.setAttribute('cx', (14 + time / cycle * 252).toFixed(2));
    waveDot.setAttribute('cy', (220 - progress * 156).toFixed(2));
    lungs.style.transform = 'translate(140px, 140px) scale(' + (0.9 + progress * 0.1).toFixed(4) + ') translate(-140px, -140px)';
    lungs.style.fillOpacity = (0.3 + progress * 0.7).toFixed(4);
  }

  function updateButton() {
    const running = state === 'running';
    card.dataset.state = state;
    rings.classList.toggle('is-breathing', running);
    button.setAttribute('aria-pressed', String(running));
    buttonLabel.textContent = running ? 'Пауза' : state === 'idle' ? 'Начать' : state === 'finishing' ? 'Готово' : 'Продолжить';
  }

  function stopFrame() {
    if (frame !== null) window.cancelAnimationFrame(frame);
    frame = null;
  }

  function timeNow() {
    return elapsed + (state === 'running' ? performance.now() - startedAt : 0);
  }

  function finish() {
    if (state === 'finishing' || state === 'complete') return;
    const retainFocus = card.contains(document.activeElement);
    stopFrame();
    elapsed = duration;
    state = 'finishing';
    syncAudio();
    stopVibration();
    if (vibration.checked && !document.hidden) vibrate([40, 70, 80]);
    resumeAfterSettings = false;
    updateButton();
    const lastPhase = phases[phases.length - 1];
    currentPhase = lastPhase;
    card.dataset.phase = lastPhase.id;
    phaseLabel.textContent = lastPhase.label;
    hint.textContent = lastPhase.hint;
    counter.textContent = String(lastPhase.duration / 1000);
    draw(cycle);
    left.textContent = '0:00';
    button.disabled = true;
    settingsButton.disabled = true;
    finishTimer = window.setTimeout(() => {
      finishTimer = null;
      const focusStillHere = card.contains(document.activeElement) || document.activeElement === document.body;
      state = 'complete';
      updateButton();
      button.disabled = false;
      settingsButton.disabled = false;
      player.hidden = true;
      settings.hidden = true;
      complete.hidden = false;
      settingsButton.setAttribute('aria-expanded', 'false');
      if (retainFocus && focusStillHere && !document.hidden) repeat.focus({ preventScroll: true });
    }, 1000);
  }

  function render(time) {
    if (time >= duration) {
      finish();
      return;
    }
    const cycleTime = time % cycle;
    const phase = phases.find(item => cycleTime < item.start + item.duration);
    const seconds = Math.ceil((duration - time) / 1000);
    left.textContent = Math.floor(seconds / 60) + ':' + String(seconds % 60).padStart(2, '0');
    const count = String(Math.floor((cycleTime - phase.start) / 1000) + 1);
    if (counter.textContent !== count) counter.textContent = count;
    if (currentPhase !== phase) {
      if (state === 'running' && vibration.checked && !document.hidden) vibrate(40);
      currentPhase = phase;
      phaseLabel.textContent = phase.label;
      hint.textContent = phase.hint;
      card.dataset.phase = phase.id;
    }
    draw(cycleTime);
  }

  function tick() {
    frame = null;
    if (state !== 'running') return;
    render(timeNow());
    if (state === 'running') frame = window.requestAnimationFrame(tick);
  }

  function start() {
    if (state === 'finishing') return;
    window.clearTimeout(finishTimer);
    finishTimer = null;
    if (state === 'complete') {
      elapsed = 0;
      currentPhase = null;
      complete.hidden = true;
      player.hidden = false;
    }
    state = 'running';
    startedAt = performance.now();
    currentPhase = null;
    updateButton();
    syncAudio();
    tick();
  }

  function pause() {
    if (state !== 'running') return;
    elapsed = timeNow();
    stopFrame();
    render(elapsed);
    if (state !== 'running') return;
    state = 'paused';
    syncAudio();
    stopVibration();
    updateButton();
    phaseLabel.textContent = 'Пауза';
    hint.textContent = 'Нажмите, когда захотите продолжить';
  }

  function closeSettings() {
    if (settings.hidden) return;
    settings.hidden = true;
    player.hidden = false;
    settingsButton.setAttribute('aria-expanded', 'false');
    if (resumeAfterSettings) start();
    resumeAfterSettings = false;
    settingsButton.focus({ preventScroll: true });
  }

  visualInputs.forEach(input => input.addEventListener('change', () => {
    if (!input.checked) return;
    setVisual(input.value);
    saveSettings();
  }));
  soundInputs.forEach(input => input.addEventListener('change', () => {
    if (!input.checked) return;
    card.dataset.sound = input.value;
    audioMode = null;
    syncAudio();
    saveSettings();
  }));
  soundButton.addEventListener('click', () => {
    setSound(!soundEnabled);
    saveSettings();
  });
  vibration.addEventListener('change', () => {
    if (vibration.checked) vibrate(40);
    else stopVibration();
    saveSettings();
  });
  button.addEventListener('click', () => state === 'running' ? pause() : start());
  repeat.addEventListener('click', () => {
    start();
    button.focus({ preventScroll: true });
  });
  settingsButton.addEventListener('click', () => {
    if (state === 'finishing') return;
    resumeAfterSettings = state === 'running';
    pause();
    if (state === 'finishing' || state === 'complete') return;
    player.hidden = true;
    settings.hidden = false;
    settingsButton.setAttribute('aria-expanded', 'true');
    card.querySelector('#gz-breathe-settings-title').focus({ preventScroll: true });
  });
  card.querySelector('#gz-breathe-settings-done').addEventListener('click', closeSettings);
  card.addEventListener('keydown', event => {
    if (event.key === 'Escape' && !settings.hidden) {
      event.preventDefault();
      event.stopPropagation();
      closeSettings();
    }
  });
  function pauseWhenAway() {
    resumeAfterSettings = false;
    pause();
    syncAudio();
    stopVibration();
  }
  document.addEventListener('visibilitychange', () => {
    if (document.hidden) pauseWhenAway();
  });
  window.addEventListener('pagehide', pauseWhenAway);

  card.dataset.sound = 'birds';
  try {
    const saved = JSON.parse(window.localStorage.getItem(storageKey));
    if (saved && typeof saved === 'object') {
      if (visualInputs.some(input => input.value === saved.visual)) setVisual(saved.visual);
      const sound = soundInputs.find(input => input.value === saved.sound);
      if (sound) {
        sound.checked = true;
        card.dataset.sound = sound.value;
      }
      setSound(saved.soundEnabled === true);
      if (typeof saved.vibration === 'boolean') vibration.checked = saved.vibration;
    }
  } catch (error) {}
  if (!audio?.ok) {
    setSound(false);
    soundButton.disabled = true;
    soundButton.setAttribute('aria-label', 'Звук недоступен в этом браузере');
    soundInputs.forEach(input => { input.disabled = true; });
    feedback.textContent = 'Этот браузер не поддерживает звук виджета.';
    feedback.hidden = false;
  }
  if (!canVibrate) {
    vibration.checked = false;
    vibration.disabled = true;
    vibration.setAttribute('aria-describedby', 'gz-breathe-vibration-hint');
    card.querySelector('#gz-breathe-vibration-hint').textContent = 'Этот браузер не поддерживает вибрацию.';
  }
  const points = [];
  for (let index = 0; index <= 100; index++) {
    const time = index / 100 * cycle;
    points.push((index ? 'L' : 'M') + (14 + index / 100 * 252).toFixed(2) + ' ' + (220 - breathProgress(time) * 156).toFixed(2));
  }
  wave.setAttribute('d', points.join(''));
  const seconds = duration / 1000;
  left.textContent = Math.floor(seconds / 60) + ':' + String(seconds % 60).padStart(2, '0');
})();



