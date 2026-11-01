<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_cost_has_heading = empty( $args['hide_heading'] );
$gdpsy_tools = gdpsy_interactive_offer();
$gdpsy_pages = gdpsy_possibilities()['pages'];
$gdpsy_modules = gdpsy_cost_modules();
?>
<section class="gz-block gz-cost gz-inner" id="gz-cost" data-screen-number="05" aria-labelledby="<?php echo $gdpsy_cost_has_heading ? 'gz-cost-title' : 'gz-price-title'; ?>">
        <?php if ( $gdpsy_cost_has_heading ) : ?>
        <div class="gz-section-head">
          <div class="gz-screen-meta">
            <div class="gz-index"><span>05</span><span class="gz-screen-slash" aria-hidden="true">/</span><span>СОСТАВ И СТОИМОСТЬ</span></div>
            <div class="gz-screen-caption">ДОПОЛНИТЕЛЬНЫЕ ВОЗМОЖНОСТИ</div>
          </div>
          <h2 class="gz-screen-title" id="gz-cost-title">
            <span class="gz-title-main">Сначала— основа.</span>
            <em class="gz-title-accent">Дальше — по задачам.</em>
          </h2>
          <div class="gz-section-side">Дополнительные возможности можно подключить сразу или позже, когда практика до них дорастёт.</div>
        </div>
        <?php endif; ?>
        <div class="gz-builder">
          <div class="gz-module-grid">
            <article class="gz-module gz-cost-base">
              <div class="gz-module-number">ОСНОВА</div>
              <h3>Лендинг под ключ</h3>
              <p class="gz-module-copy">Одна страница, чтобы познакомиться с вами, понять условия работы и записаться.</p>
              <fieldset class="gz-base-options gz-module-options">
                <legend class="gz-sr-only">Выберите один вариант основы сайта</legend>
                <label class="gz-module-option"><span><span class="gz-option-title">Индивидуальная разработка</span><small>Индивидуальный дизайн. Один интерактив по желанию.</small></span><b><?php echo esc_html( gdpsy_price_label( 'base' ) ); ?></b><input type="radio" name="gz-base" value="scratch" /></label>
                <label class="gz-module-option"><span><span class="gz-option-title">На основе готового концепта</span><small>Адаптация под ваши тексты и фото. Интерактив концепта сохраняется.</small></span><b><?php echo esc_html( gdpsy_price_label( 'concept', 'от ' ) ); ?></b><input type="radio" name="gz-base" value="concept" /></label>
              </fieldset>
              <p class="gz-base-included"><strong>Включено в оба варианта:</strong> 5–10 блоков, один интерактив, контактная форма, помощь с текстами и фото, инструкция и месяц поддержки.</p>
              <a class="gz-inline-link gz-text-action" href="#gz-base-details" data-open-dialog="gz-base-details">Что входит в создание лендинга</a>
            </article>
            <article class="gz-module gz-cost-blog">
              <div class="gz-module-number">БЛОГ / КАТАЛОГ СТАТЕЙ</div>
              <h3>Чтобы вас находили</h3>
              <p class="gz-module-copy">Статьи знакомят с вашим подходом ещё до первой встречи. Материалы могут находить через поиск, а ссылки на них могут появляться в ответах нейросетей.</p>
              <div class="gz-module-options">
                <label class="gz-module-option"><span><span class="gz-option-title">Блог / каталог статей</span> <small>рубрики, поиск и пять стартовых статей</small></span><b><?php echo esc_html( gdpsy_price_label( 'blog', '+' ) ); ?></b><input type="checkbox" data-gz-module="blog" data-cost="<?php echo esc_attr( gdpsy_price( 'blog' ) ); ?>" data-label="<?php echo esc_attr( $gdpsy_modules['blog']['label'] ); ?>" /></label>
                <label class="gz-module-option"><span><span class="gz-option-title">Автопубликация</span> <small>анонсы в Telegram, VK и MAX · нужен блог</small></span><b><?php echo esc_html( gdpsy_price_label( 'autopost', '+' ) ); ?></b><input type="checkbox" data-gz-module="autopost" data-requires="blog" data-cost="<?php echo esc_attr( gdpsy_price( 'autopost' ) ); ?>" data-label="<?php echo esc_attr( $gdpsy_modules['autopost']['label'] ); ?>" /></label>
                <label class="gz-module-option"><span><span class="gz-option-title">Email-рассылка</span> <small>форма подписки и шаблоны писем</small></span><b><?php echo esc_html( gdpsy_price_label( 'mail', '+' ) ); ?></b><input type="checkbox" data-gz-module="mail" data-cost="<?php echo esc_attr( gdpsy_price( 'mail' ) ); ?>" data-label="<?php echo esc_attr( $gdpsy_modules['mail']['label'] ); ?>" /></label>
              </div>
              <a class="gz-inline-link gz-text-action" href="#gz-module-blog" data-open-dialog="gz-module-blog">Подробнее</a>
            </article>
            <article class="gz-module gz-cost-pages">
              <div class="gz-module-number">ПОДРОБНЕЕ О ВАШЕЙ ПРАКТИКЕ</div>
              <h3>Дополнительные страницы</h3>
              <p class="gz-module-copy">Выстрою структуру и оформлю материалы в общем стиле сайта.</p>
              <ul class="gz-cost-page-types">
                <?php foreach ( $gdpsy_pages['features'] as $gdpsy_page_type ) : ?>
                <li><?php echo esc_html( $gdpsy_page_type['title'] ); ?></li>
                <?php endforeach; ?>
              </ul>
              <p class="gz-module-copy">Для программы — описание и заявка. Оплата и доступ к урокам относятся к возможной отдельной разработке.</p>
              <div class="gz-quantity-option">
                <label for="gz-pages-count">Количество страниц <small><?php echo esc_html( gdpsy_price_label( 'pages', 'от ' ) ); ?> за страницу</small></label>
                <div class="gz-quantity-control">
                  <button type="button" data-quantity-step="-1" data-quantity-target="pages" aria-label="Уменьшить количество страниц">−</button>
                  <input id="gz-pages-count" type="number" min="0" max="99" step="1" value="0" inputmode="numeric" data-gz-module="pages" data-minimum="true" data-cost="<?php echo esc_attr( gdpsy_price( 'pages' ) ); ?>" data-label="<?php echo esc_attr( $gdpsy_modules['pages']['label'] ); ?>" />
                  <button type="button" data-quantity-step="1" data-quantity-target="pages" aria-label="Увеличить количество страниц">+</button>
                </div>
              </div>
            </article>
            <article class="gz-module gz-cost-library">
              <div class="gz-module-number">ПОЛЬЗА ДЛЯ КЛИЕНТОВ</div>
              <h3>Тесты, квизы, упражнения</h3>
              <p class="gz-module-copy"><?php echo esc_html( $gdpsy_tools['summary'] ); ?></p>
              <p class="gz-module-copy">Один интерактив уже предусмотрен в лендинге. Здесь можно добавить готовые инструменты из каталога.</p>
              <div class="gz-quantity-option">
                <label for="gz-toolkit-count">Дополнительные интерактивы <small><?php echo esc_html( gdpsy_price_label( 'toolkit', 'от ' ) ); ?> за инструмент</small></label>
                <div class="gz-quantity-control">
                  <button type="button" data-quantity-step="-1" data-quantity-target="toolkit" aria-label="Уменьшить количество интерактивов">−</button>
                  <input id="gz-toolkit-count" type="number" min="0" max="99" step="1" value="0" inputmode="numeric" data-gz-module="toolkit" data-minimum="true" data-cost="<?php echo esc_attr( gdpsy_price( 'toolkit' ) ); ?>" data-label="<?php echo esc_attr( $gdpsy_modules['toolkit']['label'] ); ?>" />
                  <button type="button" data-quantity-step="1" data-quantity-target="toolkit" aria-label="Увеличить количество интерактивов">+</button>
                </div>
              </div>
              <p class="gz-module-copy"><?php echo esc_html( $gdpsy_tools['note'] ); ?> Организация каталога и PWA оцениваются отдельно.</p>
              <a class="gz-inline-link gz-text-action" href="#gz-module-library" data-open-dialog="gz-module-library">Подробнее</a>
            </article>
            <section class="gz-upcoming-grid" aria-labelledby="gz-upcoming-title">
              <header class="gz-upcoming-head">
                <h3 class="gz-upcoming-title" id="gz-upcoming-title">Обсудим отдельно</h3>
                <p>Это направления возможной разработки. Готовых модулей пока нет; состав, стоимость и сроки запуска не определены.</p>
              </header>
              <article class="gz-module gz-course-card">
                <h4>Онлайн-запись и платежи</h4>
                <p class="gz-module-copy">Выбор времени, подтверждения и переход к оплате — возможный сценарий для вашего сайта.</p>
                <a class="gz-inline-link gz-text-action" href="#gz-module-booking" data-open-dialog="gz-module-booking">Возможный сценарий</a>
              </article>
              <article class="gz-module gz-course-card">
                <h4>Курсы и интенсивы</h4>
                <p class="gz-module-copy">Оплата программы, доступ к урокам и кабинет участника — идеи для будущего модуля.</p>
                <a class="gz-inline-link gz-text-action" href="#gz-module-courses" data-open-dialog="gz-module-courses">Идея и опрос</a>
              </article>
              <article class="gz-module gz-course-card gz-cabinet-card">
                <h4>Кабинет специалиста</h4>
                <p class="gz-module-copy">Расписание, учёт оплат и рабочие заметки — изучаю, что было бы полезно собрать в одном месте.</p>
                <a class="gz-inline-link gz-text-action" href="#gz-module-cabinet" data-open-dialog="gz-module-cabinet">Идея и опрос</a>
              </article>
            </section>
          </div>
          <div class="gz-cost-sidebar">
            <aside class="gz-plan" id="gz-plan" data-base="<?php echo esc_attr( gdpsy_price( 'base' ) ); ?>" data-concept="<?php echo esc_attr( gdpsy_price( 'concept' ) ); ?>" data-annual="<?php echo esc_attr( gdpsy_price( 'annual' ) ); ?>" aria-labelledby="gz-plan-title">
              <div class="gz-eyebrow">СОСТАВ ВАШЕГО САЙТА</div>
              <h3 id="gz-plan-title">Калькулятор</h3>
              <div id="gz-plan-empty">
                <p>Выберите основу и нужные возможности — здесь появится стоимость. Расчёт сразу, без контактов.</p>
              </div>
              <div id="gz-plan-summary" hidden="">
                <div class="gz-plan-base-note" id="gz-plan-base-note"></div>
                <div class="gz-plan-lines">
                  <div class="gz-plan-line"><span id="gz-base-name"></span><b id="gz-base-price"></b></div>
                  <div id="gz-plan-modules"></div>
                </div>
                <div class="gz-plan-total"><span>Предварительно</span><output id="gz-plan-sum" aria-live="polite"></output></div>
              </div>
              <p class="gz-module-copy">Расчёт предварительный, по выбранному количеству. Для страниц и интерактивов указана минимальная стоимость. Скидка от пяти дополнительных интерактивов не учтена.</p>
              <p class="gz-module-copy"><?php if ( gdpsy_price( 'annual' ) > 0 ) : ?>Домен и хостинг — около <?php echo esc_html( gdpsy_money( 'annual' ) ); ?> в год.<br /><?php else : ?>Домен и хостинг оплачиваются отдельно.<br /><?php endif; ?>Предоплата 50%.</p>
              <button type="button" class="gz-inline-button gz-text-action gz-plan-reset" id="gz-plan-reset" hidden>Сбросить выбор</button>
              <button type="button" class="gz-primary gz-button" id="gz-plan-discuss">Обсудить состав</button>
              <div class="gz-plan-quiz">
                <button class="gz-inline-button gz-text-action" type="button" data-open-dialog="gz-quiz" aria-describedby="gz-plan-quiz-note">Не знаю, что выбрать</button>
                <p id="gz-plan-quiz-note">Пять вопросов о практике. Ответ сразу, без контактов.</p>
              </div>
            </aside>
            <aside class="gz-cost-quiz" aria-labelledby="gz-cost-quiz-title">
              <p class="gz-cost-quiz-eyebrow">ОТВЕТ СРАЗУ, БЕЗ КОНТАКТОВ</p>
              <h3 id="gz-cost-quiz-title">Не знаете, что выбрать?</h3>
              <p>Пять вопросов о вашей практике — и я подскажу, какие возможности пригодятся.</p>
              <button class="gz-inline-button gz-text-action" type="button" data-open-dialog="gz-quiz">Пройти тест</button>
            </aside>
          </div>
        </div>
        <dialog class="gz-dialog gz-detail-dialog" id="gz-base-details" aria-labelledby="gz-base-details-title" aria-describedby="gz-base-details-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-base-details-title">Что входит в создание лендинга</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-base-details-lead">Собираем содержание вокруг вашей практики: кому вы помогаете, как проходит работа и с чего начать. Для концепта адаптирую материалы, добавлю до трёх информационных блоков в том же дизайне и сниму его с продажи.</p>
        <div class="gz-detail-features"><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Содержание и интерактив</h3></div><p>5–10 блоков: кому и с чем вы помогаете, подход и образование, ход работы, формат и стоимость, вопросы и контакты. Один интерактив и контактная форма включены.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Техническая часть</h3></div><p>Аналитика, подключение домена и хостинга, инструкция по сайту и месяц поддержки. Домен и хостинг оформляются на ваше имя и оплачиваются отдельно.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>152-ФЗ</h3></div><p>Формы с согласиями на обработку данных, политика конфиденциальности и размещение сайта на сервере в России.</p></section></div>
      </div>
    </dialog>
      </section>






