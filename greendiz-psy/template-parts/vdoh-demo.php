<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<figure class="vd" id="vdDemo">
    <p class="visually-hidden">Короткая демонстрация «Вдоха» из пяти экранов: дыхательный плеер, поле «Как ты
      сейчас?», полки с практиками, тест в формате переписки и мягкий результат с подобранными практиками.</p>
    <svg width="0" height="0" style="position:absolute" aria-hidden="true">
      <symbol id="vd-ava" viewBox="70 56 100 100">
        <g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M78 170c2-14 16-24 42-24s40 10 42 24" fill="var(--a-card)" />
          <path d="M112 134v16h16v-16" fill="var(--a-card)" />
          <path d="M100 150c4 6 12 9 20 9s16-3 20-9" stroke-width="1.8" />
          <path d="M141 100c11 5 16 17 14 30-1 9-5 17-10 22 2-9 2-17-1-24-2-6-5-10-7-15z" fill="currentColor" />
          <ellipse cx="142" cy="103" rx="4" ry="5.5" transform="rotate(-25 142 103)" fill="var(--a-ultra)"
            stroke="none" />
          <path d="M96 104c0-20 10-33 24-33s24 13 24 33c0 23-11 40-24 40s-24-17-24-40z" fill="var(--a-card)" />
          <path d="M96 110c-6-2-9 3-7 8s6 7 9 5" fill="var(--a-card)" />
          <path
            d="M92 118c-9-32 4-56 31-56 27 0 37 24 31 54-1-9-4-15-8-19-3 7-9 10-15 10 3-4 4-8 3-13-7 8-20 12-32 11-3 4-6 8-7 13z"
            fill="currentColor" />
          <path d="M107 112q5 4 10 0M123 112q5 4 10 0M114 132q6 4 12 0" stroke-width="2.2" />
          <circle cx="105" cy="124" r="4.5" fill="var(--a-ultra)" stroke="none" opacity=".25" />
          <circle cx="135" cy="124" r="4.5" fill="var(--a-ultra)" stroke="none" opacity=".25" />
        </g>
      </symbol>
      <symbol id="vd-logo" viewBox="0 0 32 32">
        <circle cx="16" cy="16" r="12" fill="none" stroke="currentColor" stroke-width="1.6"
          stroke-linecap="round" stroke-dasharray="2 4.2" />
        <circle cx="16" cy="16" r="6.5" fill="var(--a-ultra)" />
      </symbol>
      <symbol id="vd-close" viewBox="0 0 24 24">
        <path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="1.6"
          stroke-linecap="round" />
      </symbol>
    </svg>
    <div class="vd-phone" aria-hidden="true">
      <div class="vd-glass">
        <div class="vd-screen">
          <div class="vd-status">
            <b>9:41</b>
            <span class="vd-island"></span>
            <span class="vd-batt">
              <i></i>
            </span>
          </div>
          <div class="vd-scene vd-s1 is-on">
            <div class="vd-hd">
              <span class="vd-logo">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-logo" />
                </svg>
                Вдох
              </span>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="8.5" r="3.6" />
                <path d="M4.8 20c.8-3.8 3.6-6 7.2-6s6.4 2.2 7.2 6" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="4.2" />
                <path
                  d="M12 2.2v2.2M12 19.6v2.2M2.2 12h2.2M19.6 12h2.2M5.1 5.1l1.5 1.5M17.4 17.4l1.5 1.5M5.1 18.9l1.5-1.5M17.4 6.6l1.5-1.5" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 9h16M4 15h16" />
              </svg>
            </div>
            <div class="vd-h1">Сделай вдох.</div>
            <div class="vd-hand">
              здесь можно не торопиться
              <svg viewBox="0 0 190 12" aria-hidden="true" focusable="false">
                <path d="M3 8c30-5 62-6 94-3s62 3 90-2" />
              </svg>
            </div>
            <div class="vd-ring">
              <svg class="vd-ring-dash" viewBox="0 0 100 100" aria-hidden="true" focusable="false">
                <circle cx="50" cy="50" r="48.5" />
              </svg>
              <span class="vd-disk"></span>
              <svg class="vd-ring-ava" aria-hidden="true" focusable="false">
                <use href="#vd-ava" />
              </svg>
            </div>
            <div class="vd-say">
              <span class="vd-say-in">Вдох носом…</span>
              <span class="vd-say-out">…и медленный выдох</span>
            </div>
            <div class="vd-ctrl">
              <span class="vd-cb">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M4.6 10v4h3l4.2 3.6V6.4L7.6 10z" />
                  <path d="M15.6 10l4 4M19.6 10l-4 4" />
                </svg>
              </span>
              <span class="vd-cb vd-cb--main">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M9 7v10M15 7v10" />
                </svg>
              </span>
              <span class="vd-cb">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M4 8.5h8.6M17.4 8.5H20M4 15.5h2.6M11.4 15.5H20" />
                  <circle cx="15" cy="8.5" r="2.4" />
                  <circle cx="9" cy="15.5" r="2.4" />
                </svg>
              </span>
            </div>
            <div class="vd-btns">
              <span class="vd-btn vd-btn--main">Как ты сейчас?</span>
              <span class="vd-btn">Практики</span>
            </div>
          </div>
          <div class="vd-scene vd-s2">
            <div class="vd-hd">
              <span class="vd-logo">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-logo" />
                </svg>
                Вдох
              </span>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="8.5" r="3.6" />
                <path d="M4.8 20c.8-3.8 3.6-6 7.2-6s6.4 2.2 7.2 6" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="4.2" />
                <path
                  d="M12 2.2v2.2M12 19.6v2.2M2.2 12h2.2M19.6 12h2.2M5.1 5.1l1.5 1.5M17.4 17.4l1.5 1.5M5.1 18.9l1.5-1.5M17.4 6.6l1.5-1.5" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 9h16M4 15h16" />
              </svg>
            </div>
            <div class="vd-h2">Как ты сейчас?</div>
            <div class="vd-lead">Коснись поля там, где ты сейчас. Точность не нужна — можно передвинуть.</div>
            <div class="vd-field">
              <span class="vd-axis"></span>
              <span class="vd-q vd-q--tl">напряжение</span>
              <span class="vd-q vd-q--tr">подъём</span>
              <span class="vd-q vd-q--bl">спад</span>
              <span class="vd-q vd-q--br">спокойствие</span>
              <div class="vd-dots">
                <i style="left:33.5%;top:17.7%;--s:0.28em;--o:0.35"></i>
                <i style="left:78.7%;top:12.6%;--s:0.52em;--o:0.35"></i>
                <i style="left:86.8%;top:23.5%;--s:0.28em;--o:0.5"></i>
                <i style="left:42.1%;top:25.9%;--s:0.52em;--o:0.5"></i>
                <i style="left:9.4%;top:55.5%;--s:0.34em;--o:0.65"></i>
                <i style="left:61.1%;top:90.2%;--s:0.52em;--o:0.65"></i>
                <i style="left:40.1%;top:92.8%;--s:0.28em;--o:0.65"></i>
                <i style="left:82.1%;top:30.4%;--s:0.34em;--o:0.65"></i>
                <i style="left:14.7%;top:32.1%;--s:0.34em;--o:0.35"></i>
                <i style="left:56.9%;top:62.1%;--s:0.4em;--o:0.35"></i>
                <i style="left:53.8%;top:9.7%;--s:0.28em;--o:0.65"></i>
                <i style="left:22.7%;top:65.9%;--s:0.46em;--o:0.5"></i>
                <i style="left:46.4%;top:88.0%;--s:0.4em;--o:0.5"></i>
                <i style="left:26.6%;top:20.4%;--s:0.34em;--o:0.35"></i>
                <i style="left:56.3%;top:51.8%;--s:0.4em;--o:0.65"></i>
                <i style="left:44.8%;top:59.4%;--s:0.28em;--o:0.35"></i>
                <i style="left:50.6%;top:19.0%;--s:0.4em;--o:0.35"></i>
                <i style="left:88.9%;top:42.4%;--s:0.28em;--o:0.65"></i>
                <i style="left:56.1%;top:83.7%;--s:0.4em;--o:0.5"></i>
                <i style="left:67.3%;top:58.1%;--s:0.52em;--o:0.5"></i>
                <i style="left:10.3%;top:12.5%;--s:0.4em;--o:0.5"></i>
                <i style="left:67.4%;top:9.9%;--s:0.4em;--o:0.65"></i>
                <i style="left:56.6%;top:66.0%;--s:0.46em;--o:0.5"></i>
                <i style="left:69.2%;top:84.7%;--s:0.4em;--o:0.35"></i>
                <i style="left:89.6%;top:36.3%;--s:0.52em;--o:0.35"></i>
                <i style="left:48.9%;top:23.9%;--s:0.4em;--o:0.35"></i>
                <i style="left:71.2%;top:40.2%;--s:0.46em;--o:0.35"></i>
                <i style="left:19.1%;top:40.5%;--s:0.4em;--o:0.35"></i>
                <i style="left:78.6%;top:82.6%;--s:0.4em;--o:0.65"></i>
                <i style="left:41.8%;top:36.6%;--s:0.46em;--o:0.35"></i>
                <i style="left:17.7%;top:20.0%;--s:0.34em;--o:0.65"></i>
                <i style="left:25.2%;top:48.1%;--s:0.52em;--o:0.35"></i>
                <i style="left:27.9%;top:4.4%;--s:0.46em;--o:0.65"></i>
                <i style="left:37.6%;top:55.5%;--s:0.34em;--o:0.65"></i>
              </div>
              <div class="vd-sat">
                <i style="left:22%;top:62%"></i>
                <i style="left:35%;top:63%"></i>
                <i style="left:19%;top:76%"></i>
                <i style="left:34%;top:80%"></i>
                <i style="left:26%;top:86%"></i>
                <i style="left:16%;top:68%"></i>
                <i style="left:38%;top:72%"></i>
                <i style="left:24%;top:57%"></i>
              </div>
              <span class="vd-pt">
                <i></i>
              </span>
              <span class="vd-w" style="left:5%;top:23%;--i:0">ничего не хочется</span>
              <span class="vd-w" style="left:47%;top:35%;--i:1">как в тумане</span>
              <span class="vd-w" style="left:6%;top:44%;--i:2">пусто</span>
              <span class="vd-w" style="left:52%;top:50%;--i:3">одиноко</span>
              <span class="vd-w vd-w--on" style="left:47%;top:65%;--i:4">устало</span>
              <span class="vd-w" style="left:40%;top:81%;--i:5">грустно</span>
              <span class="vd-hint">
                Коснись поля там,<br>где ты сейчас
              </span>
              <svg class="vd-finger" viewBox="0 0 40 40" aria-hidden="true" focusable="false">
                <path
                  d="M15 36c-4-2-7-6-8-10l-2-5c-1-2 1-3 3-2l3 4V11c0-2 3-2 3 0v8-3c0-2 3-2 3 0v3-1c0-2 3-2 3 0v2c0-2 3-2 3 0v7c0 6-3 10-8 12z" />
              </svg>
            </div>
            <div class="vd-msg vd-msg--res">
              <span class="vd-ava">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-ava" />
                </svg>
              </span>
              <span class="vd-bub vd-bub--big">Похоже, сил сейчас мало</span>
            </div>
            <div class="vd-msg vd-msg--res2 vd-msg--cont">
              <span class="vd-ava"></span>
              <span class="vd-bub">Сейчас достаточно чего-то совсем небольшого.</span>
            </div>
            <div class="vd-rec vd-rec--mood">
              <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                <circle cx="12" cy="36" r="4" />
                <circle cx="26" cy="32" r="4" />
                <circle cx="40" cy="22" r="5" />
                <circle class="d" cx="40" cy="22" r="2.2" />
              </svg>
              <span>
                <b>Три хороших момента</b>
                <small>Помогает, когда тяжело на душе</small>
              </span>
              <em>3 мин</em>
            </div>
          </div>
          <div class="vd-scene vd-s3">
            <div class="vd-hd">
              <span class="vd-logo">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-logo" />
                </svg>
                Вдох
              </span>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="8.5" r="3.6" />
                <path d="M4.8 20c.8-3.8 3.6-6 7.2-6s6.4 2.2 7.2 6" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="12" cy="12" r="4.2" />
                <path
                  d="M12 2.2v2.2M12 19.6v2.2M2.2 12h2.2M19.6 12h2.2M5.1 5.1l1.5 1.5M17.4 17.4l1.5 1.5M5.1 18.9l1.5-1.5M17.4 6.6l1.5-1.5" />
              </svg>
              <svg class="vd-hd-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path d="M4 9h16M4 15h16" />
              </svg>
            </div>
            <div class="vd-row-h">
              <div class="vd-h2">Практики</div>
              <span class="vd-pick">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                  <path d="M4 8.5h8.6M17.4 8.5H20M4 15.5h2.6M11.4 15.5H20" />
                  <circle cx="15" cy="8.5" r="2.4" />
                  <circle cx="9" cy="15.5" r="2.4" />
                </svg>
                Подобрать
              </span>
            </div>
            <div class="vd-lead">Выбери полку, а внутри листай вбок. Каждая практика — пара минут и понятные
              шаги.</div>
            <div class="vd-tabs">
              <span class="vd-tab is-sel">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <circle cx="28" cy="28" r="20" stroke-dasharray="3 5" />
                  <circle cx="28" cy="28" r="9" />
                  <circle class="d" cx="28" cy="28" r="3" />
                </svg>
                <b>Начни с этого</b>
                <i>3</i>
              </span>
              <span class="vd-tab">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <circle cx="28" cy="28" r="21" />
                  <circle cx="28" cy="28" r="12" />
                  <circle class="d" cx="28" cy="28" r="4" />
                </svg>
                <b>Дыхание</b>
                <i>3</i>
              </span>
              <span class="vd-tab">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <circle cx="28" cy="11" r="6" />
                  <path d="M28 18v17M16 25l12 4 12-4M20 50l8-15 8 15" />
                  <circle class="d" cx="28" cy="29" r="2.4" />
                </svg>
                <b>Тело и движение</b>
                <i>8</i>
              </span>
              <span class="vd-tab">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <path d="M6 28c6-9 13-14 22-14s16 5 22 14c-6 9-13 14-22 14S12 37 6 28z" />
                  <circle cx="28" cy="28" r="7" />
                  <circle class="d" cx="28" cy="28" r="2.6" />
                </svg>
                <b>Органы чувств</b>
                <i>4</i>
              </span>
              <span class="vd-tab">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <path d="M8 34c5-7 10-7 15 0s10 7 15 0 8-6 10-3" />
                  <circle class="d" cx="23" cy="34" r="2.6" />
                </svg>
                <b>Наблюдать за собой</b>
                <i>5</i>
              </span>
              <span class="vd-tab">
                <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                  <path d="M16 38a10 10 0 0 1 2-20 12 12 0 0 1 22 2 8 8 0 0 1-2 18z" />
                  <path d="M22 46l-3 4M30 46l-3 4" />
                  <circle class="d" cx="28" cy="28" r="2.6" />
                </svg>
                <b>Разобраться с мыслями</b>
                <i>8</i>
              </span>
            </div>
            <div class="vd-folder">
              <div class="vd-folder-sub">Самое короткое и действенное</div>
              <div class="vd-cards-view">
                <div class="vd-cards">
                  <div class="vd-card">
                    <svg class="vd-g vd-card-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                      <circle cx="28" cy="28" r="22" />
                      <circle cx="28" cy="28" r="11" />
                      <circle class="d" cx="28" cy="17" r="2.6" />
                    </svg>
                    <svg class="vd-heart" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                      <path
                        d="M12 20.3s-7.6-4.6-7.6-10.2A4.3 4.3 0 0 1 12 7.6a4.3 4.3 0 0 1 7.6 2.5c0 5.6-7.6 10.2-7.6 10.2z" />
                    </svg>
                    <b class="vd-card-t">Длинный выдох</b>
                    <span class="vd-card-d">Выдох длиннее вдоха. Пульс замедляется, и тело получает сигнал, что
                      опасности нет.</span>
                    <span class="vd-card-m">
                      <span>3 мин</span>
                      <span class="q">незаметно</span>
                    </span>
                  </div>
                  <div class="vd-card">
                    <svg class="vd-g vd-card-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                      <path d="M6 42c3-8 6-11 9-8 2-9 6-14 11-14 10 0 17 10 24 24" />
                      <circle class="d" cx="26" cy="20" r="2.6" />
                    </svg>
                    <b class="vd-card-t">Двойной вдох</b>
                    <span class="vd-card-d">Два вдоха подряд и долгий выдох. Самый быстрый способ сбросить
                      острое напряжение.</span>
                    <span class="vd-card-m">
                      <span>1 мин</span>
                      <span class="q">незаметно</span>
                    </span>
                  </div>
                  <div class="vd-card">
                    <svg class="vd-g vd-card-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                      <circle cx="17" cy="28" r="12" />
                      <path d="M34 19h17M34 28h13M34 37h8" />
                      <circle class="d" cx="17" cy="28" r="2.4" />
                    </svg>
                    <b class="vd-card-t">Выгрузить мысли</b>
                    <span class="vd-card-d">Выписать всё, что крутится в голове, и разделить: что можно сделать,
                      а что стоит отпустить.</span>
                    <span class="vd-card-m">
                      <span>5 мин</span>
                      <span class="q">незаметно</span>
                    </span>
                  </div>
                </div>
              </div>
              <svg class="vd-finger vd-finger--swipe" viewBox="0 0 40 40" aria-hidden="true" focusable="false">
                <path
                  d="M15 36c-4-2-7-6-8-10l-2-5c-1-2 1-3 3-2l3 4V11c0-2 3-2 3 0v8-3c0-2 3-2 3 0v3-1c0-2 3-2 3 0v2c0-2 3-2 3 0v7c0 6-3 10-8 12z" />
              </svg>
            </div>
          </div>
          <div class="vd-scene vd-s4">
            <div class="vd-bar">
              <b>Батарейка</b>
              <span class="vd-x">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-close" />
                </svg>
              </span>
              <span class="vd-prog">
                <i></i>
              </span>
            </div>
            <div class="vd-chat">
              <div class="vd-msg" style="--t:.3s">
                <span class="vd-ava">
                  <svg aria-hidden="true" focusable="false">
                    <use href="#vd-ava" />
                  </svg>
                </span>
                <span class="vd-bub">Про усталость и силы. Сначала про тебя в целом, потом — если есть работа
                  или учёба — про неё.</span>
              </div>
              <div class="vd-msg vd-msg--cont" style="--t:1s">
                <span class="vd-ava">
                  <svg aria-hidden="true" focusable="false">
                    <use href="#vd-ava" />
                  </svg>
                </span>
                <span class="vd-bub">Ты сейчас работаешь или учишься?</span>
              </div>
              <div class="vd-msg vd-msg--me" style="--t:2.7s">
                <span class="vd-bub">Работаю</span>
              </div>
              <div class="vd-msg" style="--t:3.4s">
                <span class="vd-ava">
                  <svg aria-hidden="true" focusable="false">
                    <use href="#vd-ava" />
                  </svg>
                </span>
                <span class="vd-bub">Как часто чувствуется усталость?</span>
              </div>
              <div class="vd-msg vd-msg--me" style="--t:5.5s">
                <span class="vd-bub">Иногда</span>
              </div>
              <div class="vd-msg" style="--t:6.2s">
                <span class="vd-ava">
                  <svg aria-hidden="true" focusable="false">
                    <use href="#vd-ava" />
                  </svg>
                </span>
                <span class="vd-bub">Как часто бывает физическое истощение?</span>
              </div>
            </div>
            <div class="vd-dock">
              <div class="vd-ans vd-ans--a">
                <span class="vd-pill vd-pill--hit">Работаю</span>
                <span class="vd-pill">Учусь</span>
                <span class="vd-pill">Ни то ни другое</span>
              </div>
              <div class="vd-ans vd-ans--b">
                <span class="vd-pill">Всегда</span>
                <span class="vd-pill">Часто</span>
                <span class="vd-pill vd-pill--hit">Иногда</span>
                <span class="vd-pill">Редко</span>
                <span class="vd-pill">Почти никогда</span>
              </div>
              <div class="vd-tip">Передумалось? Нажми на свой ответ, чтобы вернуться к вопросу.</div>
            </div>
          </div>
          <div class="vd-scene vd-s5">
            <div class="vd-bar">
              <b>Батарейка</b>
              <span class="vd-x">
                <svg aria-hidden="true" focusable="false">
                  <use href="#vd-close" />
                </svg>
              </span>
              <span class="vd-prog vd-prog--full">
                <i></i>
              </span>
            </div>
            <div class="vd-res-h">
              <span>Результат</span>
              <span>в целом</span>
            </div>
            <div class="vd-res-ring">
              <svg viewBox="0 0 100 100" aria-hidden="true" focusable="false">
                <circle class="bg" cx="50" cy="50" r="44" />
                <circle class="fg" cx="50" cy="50" r="44" />
              </svg>
              <span class="vd-res-num">
                <b>58</b>
                <span>из 100</span>
              </span>
            </div>
            <div class="vd-res-t">Батарейка садится</div>
            <div class="vd-res-p">Истощение заметно: сил тратится больше, чем успевает восстановиться.</div>
            <div class="vd-parts">
              <div class="vd-part" style="--v:.58">
                <span>В целом</span>
                <b>58</b>
                <i></i>
              </div>
              <div class="vd-part" style="--v:.64">
                <span>От работы</span>
                <b>64</b>
                <i></i>
              </div>
            </div>
            <div class="vd-rec-h">Практики под твои ответы</div>
            <div class="vd-rec">
              <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                <circle cx="28" cy="28" r="22" />
                <circle cx="28" cy="28" r="11" />
                <circle class="d" cx="28" cy="17" r="2.6" />
              </svg>
              <span>
                <b>Длинный выдох</b>
                <small>когда устаёшь</small>
              </span>
              <em>3 мин</em>
            </div>
            <div class="vd-rec vd-rec--2">
              <svg class="vd-g" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
                <circle cx="17" cy="28" r="12" />
                <path d="M34 19h17M34 28h13M34 37h8" />
                <circle class="d" cx="17" cy="28" r="2.4" />
              </svg>
              <span>
                <b>Выгрузить мысли</b>
                <small>когда истощены чувства</small>
              </span>
              <em>5 мин</em>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="vd-nav">
      <div class="vd-segs" role="group" aria-label="Экраны демонстрации">
        <button class="vd-seg is-on" type="button" aria-label="Экран 1: дыхательный плеер" aria-current="true"
          style="--d:8s">
          <i>
            <b></b>
          </i>
        </button>
        <button class="vd-seg" type="button" aria-label="Экран 2: поле «Как ты сейчас?»" style="--d:8.5s">
          <i>
            <b></b>
          </i>
        </button>
        <button class="vd-seg" type="button" aria-label="Экран 3: практики по полкам" style="--d:7.5s">
          <i>
            <b></b>
          </i>
        </button>
        <button class="vd-seg" type="button" aria-label="Экран 4: тест в формате переписки" style="--d:9s">
          <i>
            <b></b>
          </i>
        </button>
        <button class="vd-seg" type="button" aria-label="Экран 5: результат теста" style="--d:8s">
          <i>
            <b></b>
          </i>
        </button>
      </div>
      <button class="vd-pp" type="button" aria-label="Поставить на паузу" aria-pressed="false">
        <svg class="vd-pp-pause" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M9 7v10M15 7v10" />
        </svg>
        <svg class="vd-pp-play" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M9 6.5l9 5.5-9 5.5z" />
        </svg>
      </button>
    </div>
    <figcaption class="vd-cap">
      <span class="is-on">Дыхательный плеер: круг задаёт ритм вдоха и выдоха</span>
      <span>Человек отмечает, как он сейчас, и сразу получает поддержку</span>
      <span>Практики по полкам — у каждой пара минут и понятные шаги</span>
      <span>Тест идёт как переписка, а не как анкета</span>
      <span>Мягкий результат без диагнозов и практики под ответы</span>
    </figcaption>
  </figure>

