<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="gz-benefit gz-inner" id="gz-benefit" aria-labelledby="gz-benefit-title">
        <div class="gz-screen-head">
          <div class="gz-screen-meta gz-screen-meta--lead">
            <div class="gz-index">польза для клиентов ещё до первой встречи</div>
            <div class="gz-screen-caption">готовые или по вашей идее</div>
          </div>
          <h2 class="gz-screen-title" id="gz-benefit-title">Опросники и упражнения</h2>
        </div>
        <div class="gz-benefit-body">
          <div class="gz-benefit-copy">
            <div class="gz-benefit-intro">
              <p>
                Небольшие действия, через которые посетитель может почувствовать опору, понять, как может работать терапия и получить полезный опыт. Это вовлекает и повышает доверие.
              </p>
              <p>Механики подбираем вместе под вашу специализацию и подход. Возможна разработка новой механики под ваш запрос.</p>
            </div>
            <ol class="gz-outcomes">
              <li class="gz-outcome">
                <span class="gz-outcome-num" aria-hidden="true">01</span>
                <div>
                  <h3>Сделать паузу</h3>
                  <p>Дыхание, заземление или выгрузка мыслей — короткая практика прямо на сайте.</p>
                </div>
              </li>
              <li class="gz-outcome">
                <span class="gz-outcome-num" aria-hidden="true">02</span>
                <div>
                  <h3>Понять своё состояние</h3>
                  <p>Через образ или вопросы о самочувствии с подходящими подсказками.</p>
                </div>
              </li>
              <li class="gz-outcome">
                <span class="gz-outcome-num" aria-hidden="true">03</span>
                <div>
                  <h3>Найти слова</h3>
                  <p>
                    На основе ответов или выбора в виджетах сайт формирует черновик обращения — это может
                    помочь сделать первый шаг.
                  </p>
                </div>
              </li>
            </ol>
            <a class="gz-primary gz-button gz-benefit-action" href="https://psy.greendiz.ru/interactive/">
              Каталог интерактивов
            </a>
          </div>
          <div class="gz-benefit-example">
            <div class="gz-breathe-card" id="gz-breathe-demo" data-state="idle" aria-labelledby="gz-breathe-title">
              <div class="gz-breathe-header">
                <div class="gz-eyebrow">Попробуйте сами</div>
                <h3 id="gz-breathe-title">Дыхательная пауза</h3>
                <div class="gz-breathe-meta">
                  <p class="gz-breathe-subtitle">4-7-8</p>
                  <p class="gz-breathe-duration">~ 1 минута</p>
                </div>
              </div>
              <div class="gz-breathe-screens">
                <div class="gz-breathe-player" id="gz-breathe-player">
                  <div class="gz-breathe-figure" data-view="circle">
                    <svg class="gz-breathe-rings" viewBox="0 0 280 280" width="280" height="280"
                      fill="none" aria-hidden="true">
                      <g data-breathe-visual="circle">
                        <circle class="gz-breathe-guide" cx="140" cy="140" r="126"></circle>
                        <circle class="gz-breathe-disc" cx="140" cy="140" r="118"></circle>
                      </g>
                      <g data-breathe-visual="wave" hidden>
                        <path class="gz-breathe-wave-base" d="M14 220H266"></path>
                        <path class="gz-breathe-wave" d="M14 220C32 220 40 64 67 64H160C205 64 230 220 266 220"></path>
                        <circle class="gz-breathe-wave-dot" cx="14" cy="220" r="7"></circle>
                      </g>
                      <g data-breathe-visual="lungs" hidden>
                        <g class="gz-breathe-lungs">
                          <path d="M126 78C120 65 107 62 96 74C69 101 50 137 43 178C37 212 44 228 64 225L104 213C123 205 130 186 130 161V102C130 92 129 84 126 78Z"></path>
                          <path d="M154 78C160 65 173 62 184 74C211 101 230 137 237 178C243 212 236 228 216 225L176 213C157 205 150 186 150 161V102C150 92 151 84 154 78Z"></path>
                        </g>
                        <path class="gz-breathe-airway" d="M140 34V99M140 91L114 116M140 91L166 116"></path>
                        <path class="gz-breathe-branches" d="M115 115L82 189M103 142L76 140M92 168L110 184M165 115L198 189M177 142L204 140M188 168L170 184"></path>
                      </g>
                    </svg>
                    <span class="gz-breathe-count" aria-hidden="true"></span>
                  </div>
                  <div class="gz-breathe-status" id="gz-breathe-status" role="status" aria-live="polite" aria-atomic="true">
                    <p class="gz-breathe-phase">Выпрямите спину</p>
                    <p class="gz-breathe-hint">Расслабьте плечи и дышите без усилия</p>
                  </div>
                  <div class="gz-breathe-controls">
                    <button class="gz-breathe-icon-button gz-icon-action" id="gz-breathe-sound" type="button"
                      aria-label="Включить звук" aria-pressed="false">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4.6 10v4h3l4.2 3.6V6.4L7.6 10z"></path>
                        <path class="gz-breathe-muted-icon" d="M15.6 10l4 4M19.6 10l-4 4"></path>
                        <path class="gz-breathe-sound-icon" d="M15.2 9.8a3.2 3.2 0 0 1 0 4.4M17.8 7.6a6.4 6.4 0 0 1 0 8.8"></path>
                      </svg>
                    </button>
                    <button class="gz-primary gz-button gz-breathe-action" id="gz-breathe-button" type="button" aria-pressed="false">
                      <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path class="gz-breathe-play-icon" d="M8 5L19 12L8 19Z" fill="currentColor"></path>
                        <g class="gz-breathe-pause-icon" fill="currentColor">
                          <rect x="7" y="5.5" width="3.4" height="13" rx="1.2"></rect>
                          <rect x="13.6" y="5.5" width="3.4" height="13" rx="1.2"></rect>
                        </g>
                      </svg>
                      <span>Начать</span>
                    </button>
                    <button class="gz-breathe-icon-button gz-icon-action" id="gz-breathe-settings-button" type="button"
                      aria-label="Настройки дыхания" aria-controls="gz-breathe-settings" aria-expanded="false">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round" aria-hidden="true">
                        <path d="M4 8.5h8.6M17.4 8.5H20M4 15.5h2.6M11.4 15.5H20"></path>
                        <circle cx="15" cy="8.5" r="2.4"></circle>
                        <circle cx="9" cy="15.5" r="2.4"></circle>
                      </svg>
                    </button>
                  </div>
                  <p class="gz-breathe-left">Осталось <span id="gz-breathe-left">1:16</span></p>
                </div>
                <section class="gz-breathe-settings" id="gz-breathe-settings" aria-labelledby="gz-breathe-settings-title" hidden>
                  <h4 id="gz-breathe-settings-title" tabindex="-1">Настройки</h4>
                  <fieldset>
                    <legend>Как показывать</legend>
                    <div class="gz-breathe-choices gz-breathe-visuals">
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-visual" value="circle" checked />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="16" cy="16" r="12"></circle></svg><span>Круг</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-visual" value="wave" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M3 9Q9 3 16 9T29 9M3 16Q9 10 16 16T29 16M3 23Q9 17 16 23T29 23"></path></svg><span>Волна</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-visual" value="lungs" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M16 3V13M16 10L11 15M16 10L21 15M12 9C7 9 3 19 3 25C3 29 12 29 13 24V12ZM20 9C25 9 29 19 29 25C29 29 20 29 19 24V12Z"></path></svg><span>Лёгкие</span></span>
                      </label>
                    </div>
                  </fieldset>
                  <fieldset>
                    <legend>Звук</legend>
                    <div class="gz-breathe-choices gz-breathe-sounds">
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-sound" value="birds" checked />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M3 21L12 16L18 7Q22 4 26 10L30 11L25 13Q24 24 14 25L9 30H5L10 24Z"></path><circle cx="22" cy="10" r=".7" fill="currentColor"></circle></svg><span>Птицы</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-sound" value="relax" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M3 9Q9 3 16 9T29 9M3 16Q9 10 16 16T29 16M3 23Q9 17 16 23T29 23"></path></svg><span>Релакс</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-sound" value="rain" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M8 21H25C33 20 31 10 24 11C22 1 9 3 9 12C1 11 0 21 8 21ZM9 25L7 29M17 25L15 29M25 25L23 29"></path></svg><span>Дождь</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-sound" value="forest" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" aria-hidden="true"><path d="M16 2L8 12H12L5 21H11L6 28H26L21 21H27L20 12H24ZM16 28V32"></path></svg><span>Лес</span></span>
                      </label>
                      <label class="gz-breathe-choice">
                        <input type="radio" name="gz-breathe-sound" value="breath" />
                        <span><svg viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true"><path d="M2 10H21C29 10 29 1 22 3M2 16H27C34 16 32 26 25 24M2 22H15C23 22 23 31 16 29"></path></svg><span>Дыхание</span></span>
                      </label>
                    </div>
                  </fieldset>
                  <label class="gz-breathe-vibration">
                    <input id="gz-breathe-vibration" type="checkbox" role="switch" checked />
                    <span class="gz-breathe-switch" aria-hidden="true"></span>
                    <span>Вибрация при смене фаз<small id="gz-breathe-vibration-hint">Можно закрыть глаза</small></span>
                  </label>
                  <button class="gz-primary gz-button gz-button--navy gz-breathe-action" id="gz-breathe-settings-done" type="button">Готово</button>
                </section>
                <p class="gz-breathe-feedback" id="gz-breathe-feedback" role="status" hidden></p>
                <div class="gz-breathe-complete" id="gz-breathe-complete" hidden>
                  <svg class="gz-breathe-check" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="32" cy="32" r="29"></circle><path d="M19 32L28 41L45 23"></path></svg>
                  <p class="gz-breathe-complete-title" role="status">Минута для себя — уже забота.</p>
                  <div class="gz-breathe-complete-actions">
                    <button class="gz-primary gz-button gz-button--navy gz-breathe-action" id="gz-breathe-repeat" type="button">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10A8 8 0 1 0 20 15M20 4V10H14"></path></svg>
                      Повторить
                    </button>
                    <button class="gz-primary gz-button gz-button--outline gz-breathe-all" type="button" data-open-dialog="gz-module-interactions">
                      Все механики <span aria-hidden="true">↗</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="gz-practice-note">
              <p><span class="gz-practice-info" aria-hidden="true">i</span> Это демонстрация механики. <br>Сценарий и содержание для вашего сайта согласуем отдельно.</p>
            </div>
          </div>
        </div>
      </section>
