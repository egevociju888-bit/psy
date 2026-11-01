<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="gz-block gz-formats gz-inner" id="gz-formats" aria-labelledby="gz-formats-title">
        <div class="gz-screen-meta gz-screen-meta--lead">
          <div class="gz-index">Главная страница</div>
          <div class="gz-screen-caption">основа вашего сайта</div>
        </div>
        <div class="gz-formats-layout">
          <div class="gz-formats-heading">
            <h2 id="gz-formats-title" class="gz-screen-title">Два пути разработки</h2>
          </div>
          <article class="gz-format gz-format-new" aria-labelledby="gz-format-new-title">
            <div class="gz-format-head">
              <div class="gz-format-labels">
                <div class="gz-eyebrow">Индивидуальная разработка</div>
                <span class="gz-format-badge">классический подход</span>
              </div>
              <h3 id="gz-format-new-title">Начинаем с чистого листа</h3>
            </div>
            <p class="gz-format-description">Подойдёт, если нужен собственный визуальный язык или особый состав сайта.</p>
            <div class="gz-format-price">
              <b><?php echo esc_html( gdpsy_money( 'base' ) ); ?></b>
              <details class="gz-format-note"><summary>Что входит в цену</summary><p>Цена за лендинг на 5–10 блоков, включая контактную форму и 1 интерактив по желанию. Дополнительные страницы, интеграции и новые механики обсуждаем отдельно.</p></details>
            </div>
            <a class="gz-primary gz-button gz-button--navy gz-format-action" href="#gz-contact" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить индивидуальную разработку сайта." data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить свою идею</a>
          </article>
          <article class="gz-format gz-format-concept" aria-labelledby="gz-format-concept-title">
            <div class="gz-format-head">
              <div class="gz-format-labels">
                <div class="gz-eyebrow">Авторский концепт</div>
                <span class="gz-format-badge">сразу знаете, как будет выглядеть сайт</span>
              </div>
              <h3 id="gz-format-concept-title">На основе моей идеи</h3>
            </div>
            <div class="gz-format-body">
              <p class="gz-format-description">Выбираете готовый дизайн и получаете сайт на его основе.</p>
              <ul class="gz-format-list">
                <li>Адаптирую тексты и фото под вас</li>
                <li>Добавлю до трёх информационных блоков в том же дизайне</li>
                <li>Сниму концепт с продажи: другого сайта в таком дизайне не будет</li>
              </ul>
            </div>
            <div class="gz-format-price">
              <b><?php echo esc_html( gdpsy_price_label( 'concept', 'от ' ) ); ?></b>
              <details class="gz-format-note"><summary>Что входит в цену</summary><p>Цена за лендинг на 5–10 блоков, включая существующий интерактив и контактную форму. Объём адаптации уточним при обсуждении. Дополнительные страницы, интеграции и новые механики обсуждаем отдельно.</p></details>
            </div>
            <a class="gz-button gz-format-action" href="#gz-concepts">Посмотреть концепты</a>
          </article>
        </div>
      </section>
