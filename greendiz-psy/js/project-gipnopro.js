(() => {
      'use strict';

      const caseRoot = document.getElementById('gipnopro-case');
      if (!caseRoot) return;

      function initTrafficChart() {
        const data = {
          "search": {
            "name": "Поиск",
            "value": "1 116",
            "share": "46,2%"
          },
          "ads": {
            "name": "Реклама",
            "value": "1 023",
            "share": "42,3%"
          },
          "social": {
            "name": "Соцсети",
            "value": "159",
            "share": "6,6%"
          },
          "internal": {
            "name": "Внутренние переходы",
            "value": "94",
            "share": "3,9%"
          },
          "recommend": {
            "name": "Рекомендательные системы",
            "value": "21",
            "share": "0,87%"
          },
          "saved": {
            "name": "Сохранённые страницы",
            "value": "3",
            "share": "0,12%"
          },
          "chatgpt": {
            "name": "ChatGPT",
            "value": "2",
            "share": "0,08%"
          }
        };
        const controls = [...caseRoot.querySelectorAll('[data-source]')];
        const value = caseRoot.querySelector('#traffic-value');
        const label = caseRoot.querySelector('#traffic-label');
        const share = caseRoot.querySelector('#traffic-share');
        if (!value || !label || !share || !controls.length) return;
        let selected = null;
        function show(key) {
          const row = data[key];
          value.textContent = row ? row.value : '2 418';
          label.textContent = row ? row.name : 'визитов за год';
          share.textContent = row ? row.share + ' визитов' : 'из разных источников';
          controls.forEach(el => {
            el.classList.toggle('is-active', Boolean(row) && el.dataset.source === key);
            el.classList.toggle('is-muted', Boolean(row) && el.dataset.source !== key);
            el.setAttribute('aria-pressed', String(el.dataset.source === selected));
          });
        }
        function select(key) {
          selected = selected === key ? null : key;
          show(selected);
        }
        controls.forEach(el => {
          const key = el.dataset.source;
          el.addEventListener('pointerenter', () => show(key));
          el.addEventListener('pointerleave', () => show(selected));
          el.addEventListener('focus', () => show(key));
          el.addEventListener('blur', () => show(selected));
          el.addEventListener('click', () => select(key));
          if (el.tagName.toLowerCase() !== 'button') {
            el.addEventListener('keydown', event => {
              if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                select(key);
              }
            });
          }
        });
      }

      function initDetailsAnchors() {
        function revealHash(hash, scroll) {
          let id;
          try {
            id = decodeURIComponent(hash.replace(/^#/, ''));
          } catch {
            return;
          }
          const target = document.getElementById(id);
          if (!target || !caseRoot.contains(target)) return;
          const details = target.closest('details');
          if (details) {
            details.open = true;
            if (scroll) target.scrollIntoView({block: 'start'});
          }
        }
        caseRoot.querySelectorAll('a[href^="#"]').forEach(a => {
          a.addEventListener('click', () => revealHash(a.getAttribute('href'), false));
        });
        window.addEventListener('hashchange', () => revealHash(location.hash, true));
        revealHash(location.hash, true);
      }


      function initSwipe(viewport, changeSlide, isEnabled = () => true) {
        let gesture = null;
        viewport.addEventListener('touchstart', event => {
          if (!isEnabled() || event.touches.length !== 1) {
            gesture = null;
            return;
          }
          const touch = event.touches[0];
          gesture = { id: touch.identifier, x: touch.clientX, y: touch.clientY, axis: null };
        }, { passive: true });
        viewport.addEventListener('touchmove', event => {
          if (!gesture || !isEnabled() || event.touches.length !== 1) {
            gesture = null;
            return;
          }
          const touch = event.touches[0];
          if (touch.identifier !== gesture.id) return;
          const dx = Math.abs(touch.clientX - gesture.x);
          const dy = Math.abs(touch.clientY - gesture.y);
          if (!gesture.axis && Math.max(dx, dy) > 8) gesture.axis = dx > dy * 1.2 ? 'horizontal' : 'vertical';
          if (gesture.axis === 'horizontal' && event.cancelable) event.preventDefault();
        }, { passive: false });
        viewport.addEventListener('touchend', event => {
          if (!gesture) return;
          const touch = [...event.changedTouches].find(touch => touch.identifier === gesture.id);
          if (!touch) return;
          const dx = touch.clientX - gesture.x;
          const dy = touch.clientY - gesture.y;
          const axis = gesture.axis;
          gesture = null;
          if (!isEnabled() || axis === 'vertical' || Math.abs(dx) < 32 || Math.abs(dx) <= Math.abs(dy) * 1.2) return;
          changeSlide(dx < 0 ? 1 : -1);
        }, { passive: true });
        viewport.addEventListener('touchcancel', () => { gesture = null; }, { passive: true });
      }

      function initScreenshotFrames() {
        const desktop = window.matchMedia('(min-width: 761px)');
        const frames = [...caseRoot.querySelectorAll('.card_3 .fs, .card_4 .fs')];
        const attributes = frames.map(frame => ({
          tabindex: frame.getAttribute('tabindex'),
          describedby: frame.getAttribute('aria-describedby'),
          label: frame.getAttribute('aria-label')
        }));
        function update() {
          frames.forEach((frame, index) => {
            const original = attributes[index];
            const scrollable = desktop.matches;
            if (!scrollable) {
              frame.removeAttribute('tabindex');
              frame.removeAttribute('aria-describedby');
              if (original.label) frame.setAttribute('aria-label', original.label.replace(', прокручивается', ''));
            } else {
              ['tabindex', 'aria-describedby', 'aria-label'].forEach((name, item) => {
                const value = [original.tabindex, original.describedby, original.label][item];
                if (value !== null) frame.setAttribute(name, value);
              });
            }
          });
        }
        update();
        desktop.addEventListener('change', update);
      }

      function initMobileSlider() {
        const root = caseRoot.querySelector('.mobile-card');
        if (!root) return;
        const track = root.querySelector('.mobile-devices');
        const template = track?.querySelector('.mobile-device');
        const desktop = window.matchMedia('(min-width: 761px)');
        const slides = [
          {
            title: "С чем я могу помочь",
            description: "Запросы собраны в понятные карточки: эмоции, отношения, травмы и другие трудности, с которыми работает психолог.",
            src: "mob_1.webp"
          },
          {
            title: "Результаты терапии",
            description: "Возможные изменения описаны простым языком: лучше понимать себя, справляться с эмоциями и выстраивать отношения.",
            src: "mob_2.webp"
          },
          {
            title: "Формат и стоимость",
            description: "Этапы работы, стоимость сессии и варианты консультаций собраны рядом с кнопкой записи.",
            src: "mob_3.webp"
          },
          {
            title: "Ответы на вопросы",
            description: "Частые вопросы о консультациях раскрываются по нажатию — удобно читать с телефона.",
            src: "mob_4.webp"
          },
          {
            title: "Страница метода",
            description: "Подробная страница об эмоционально-образной терапии: подход, процесс работы и запись на консультацию.",
            src: "mob_5.webp"
          }
        ];
        const copy = root.querySelector('.mobile-slide-copy');
        const details = [...root.querySelectorAll('.mobile-slide-detail')];
        const counter = root.querySelector('#mobile-count');
        const previousButton = root.querySelector('#mobile-prev');
        const nextButton = root.querySelector('#mobile-next');
        if (!copy || !track || !template || details.length !== 1 || !counter || !previousButton || !nextButton) return;
        root.classList.add('is-enhanced');
        root.setAttribute('aria-roledescription', 'карусель');
        root.setAttribute('tabindex', '0');
        function reserveTextSpace() {
          const width = copy.getBoundingClientRect().width;
          if (!width) return;
          const probe = details[0].cloneNode(true);
          probe.removeAttribute('aria-live');
          probe.removeAttribute('id');
          probe.setAttribute('aria-hidden', 'true');
          Object.assign(probe.style, { position: 'absolute', visibility: 'hidden', pointerEvents: 'none', width: width + 'px', left: '0', top: '0' });
          const title = probe.querySelector('h3');
          const description = probe.querySelector('p');
          [title, description].forEach(node => {
            node.removeAttribute('id');
            node.style.minHeight = '0';
          });
          copy.parentNode.appendChild(probe);
          let titleHeight = 0;
          let descriptionHeight = 0;
          slides.forEach(slide => {
            title.textContent = slide.title;
            description.textContent = slide.description;
            titleHeight = Math.max(titleHeight, title.getBoundingClientRect().height);
            descriptionHeight = Math.max(descriptionHeight, description.getBoundingClientRect().height);
          });
          probe.remove();
          details.forEach(detail => {
            detail.style.setProperty('--mobile-title-height', Math.ceil(titleHeight) + 'px');
            detail.style.setProperty('--mobile-description-height', Math.ceil(descriptionHeight) + 'px');
          });
        }
        reserveTextSpace();
        if (document.fonts) document.fonts.ready.then(reserveTextSpace);
        let resizeFrame;
        window.addEventListener('resize', () => {
          cancelAnimationFrame(resizeFrame);
          resizeFrame = requestAnimationFrame(reserveTextSpace);
        });
        const phones = slides.map(slide => {
          const phone = template.cloneNode(true);
          const screen = phone.querySelector('.mobile-screen');
          const image = screen.querySelector('img');
          image.src = (track.dataset.imageBase || '') + slide.src;
          image.alt = slide.title;
          screen.setAttribute('aria-label', slide.title + ', можно прокручивать');
          return phone;
        });
        const devices = [phones[slides.length - 1].cloneNode(true), ...phones, phones[0].cloneNode(true), phones[1].cloneNode(true)];
        track.replaceChildren(...devices);
        let current = 0;
        let position = 1;
        let moving = false;
        let pending = 0;
        function updateCopy() {
          const slide = slides[current];
          details[0].querySelector('h3').textContent = slide.title;
          details[0].querySelector('p').textContent = slide.description;
          counter.textContent = (current + 1) + ' / ' + slides.length;
        }
        function updateVisibility() {
          const visible = desktop.matches ? 2 : 1;
          devices.forEach((device, index) => {
            const hidden = index < position || index >= position + visible;
            device.inert = hidden;
            device.setAttribute('aria-hidden', String(hidden));
          });
        }
        function place(animate) {
          const step = devices[1].offsetLeft - devices[0].offsetLeft;
          if (!animate) track.style.transition = 'none';
          track.style.transform = 'translateX(' + (-position * step) + 'px)';
          if (!animate) {
            void track.offsetWidth;
            track.style.removeProperty('transition');
          }
          updateVisibility();
        }
        function move(direction) {
          if (moving) {
            pending += direction;
            return;
          }
          moving = true;
          current = (current + direction + slides.length) % slides.length;
          position += direction;
          devices[position].querySelector('.mobile-screen').scrollTop = 0;
          updateCopy();
          place(true);
        }
        track.addEventListener('transitionend', event => {
          if (event.target !== track || event.propertyName !== 'transform') return;
          moving = false;
          if (position === 0 || position === slides.length + 1) {
            position = current + 1;
            place(false);
          }
          if (pending) {
            const direction = Math.sign(pending);
            pending -= direction;
            move(direction);
          }
        });
        function resizeTrack() {
          moving = false;
          pending = 0;
          position = current + 1;
          place(false);
        }
        window.addEventListener('resize', resizeTrack);
        desktop.addEventListener('change', resizeTrack);
        updateCopy();
        place(false);
        initSwipe(root.querySelector('.mobile-viewport'), move);
        previousButton.addEventListener('click', () => move(-1));
        nextButton.addEventListener('click', () => move(1));
        root.addEventListener('keydown', event => {
          if (event.target.closest('.mobile-screen, input, textarea, select, [contenteditable]')) return;
          if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            event.preventDefault();
            move(event.key === 'ArrowRight' ? 1 : -1);
          }
        });
      }


      function initCredentialsSlider() {
        const root = caseRoot.querySelector('.credentials-slider');
        if (!root) return;
        const desktop = window.matchMedia('(min-width: 761px)');
        const viewport = root.querySelector('.credentials-viewport');
        const track = root.querySelector('.credentials-track');
        const slides = [...root.querySelectorAll('.credentials-slide')];
        const controls = root.querySelector('.credentials-controls');
        const counter = root.querySelector('.credentials-count');
        const previous = root.querySelector('.credentials-prev');
        const next = root.querySelector('.credentials-next');
        if (!viewport || !track || slides.length < 2 || !controls || !counter || !previous || !next) return;
        root.classList.add('is-enhanced');
        let current = 0;
        function update() {
          controls.hidden = desktop.matches;
          if (!desktop.matches) {
            root.setAttribute('role', 'region');
            root.setAttribute('aria-roledescription', 'карусель');
            root.setAttribute('aria-label', 'Образование и дипломы');
            root.setAttribute('tabindex', '0');
            track.style.transform = 'translateX(' + (-current * 100) + '%)';
          } else {
            ['role', 'aria-roledescription', 'aria-label', 'tabindex'].forEach(name => root.removeAttribute(name));
            track.style.removeProperty('transform');
            if (controls.contains(document.activeElement) || document.activeElement === root) document.activeElement.blur();
          }
          counter.textContent = (current + 1) + ' / ' + slides.length;
          slides.forEach((slide, index) => {
            const hidden = !desktop.matches && index !== current;
            slide.inert = hidden;
            slide.setAttribute('aria-hidden', String(hidden));
          });
        }
        function move(direction) {
          if (desktop.matches) return;
          current = (current + direction + slides.length) % slides.length;
          update();
        }
        previous.addEventListener('click', () => move(-1));
        next.addEventListener('click', () => move(1));
        root.addEventListener('keydown', event => {
          if (desktop.matches || event.target.closest('input, textarea, select, [contenteditable]')) return;
          if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
          event.preventDefault();
          move(event.key === 'ArrowRight' ? 1 : -1);
        });
        initSwipe(viewport, move, () => !desktop.matches);
        desktop.addEventListener('change', update);
        update();
      }

      initScreenshotFrames();
      initCredentialsSlider();
      initTrafficChart();
      initDetailsAnchors();
      initMobileSlider();
    })();
