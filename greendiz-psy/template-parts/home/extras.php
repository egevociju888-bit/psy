<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="gz-block gz-extras gz-inner" id="gz-cost" aria-labelledby="gz-extras-title">
  <div class="gz-extras-head">
    <h2 class="gz-screen-title" id="gz-extras-title">
      <span class="gz-title-main">Если нужно больше</span>
      <span class="gz-title-accent">одной страницы</span>
    </h2>
    <div class="gz-extras-intro">
      <div class="gz-screen-meta">
        <div class="gz-index">ДОПОЛНИТЕЛЬНЫЕ ВОЗМОЖНОСТИ</div>
        <div class="gz-screen-caption">СРАЗУ ИЛИ ПОЗЖЕ</div>
      </div>
      <p>Если у вас есть нестандартная идея — давайте обсудим!<br />
        Ниже предложение для традиционного многостраничного сайта.</p>
    </div>
  </div>
  <div class="gz-extras-grid">
    <article class="gz-extra gz-extra--pages" aria-labelledby="gz-extra-pages-title">
      <div class="gz-extra-label">ПОДРОБНЕЕ О ВАШЕЙ ПРАКТИКЕ</div>
      <h3 id="gz-extra-pages-title">Дополнительные страницы</h3>
      <p class="gz-extra-copy">Подробнее о вашем подходе и методах, образовании и опыте, специальных предложениях или авторских курсах. Выстрою структуру и оформлю материалы в общем стиле сайта.</p>
      <?php if ( gdpsy_price( 'pages' ) > 0 ) : ?>
      <p class="gz-extra-price"><b><?php echo esc_html( gdpsy_price_label( 'pages', 'от ' ) ); ?></b><span> / страница</span></p>
      <?php endif; ?>
      <a class="gz-button gz-button--navy" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Меня заинтересовало ваше предложение о сайте с дополнительными страницами. Давайте обсудим." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить страницы</a>
    </article>
    <article class="gz-extra gz-extra--blog" aria-labelledby="gz-extra-blog-title">
      <div class="gz-extra-label">ЧТОБЫ ВАС НАХОДИЛИ И УЗНАВАЛИ</div>
      <h3 id="gz-extra-blog-title">Блог / каталог статей</h3>
      <p class="gz-extra-copy">Статьи и материалы, через которые с вами знакомятся ещё до первой встречи, а также находят сайт в поиске и ответах AI.</p>
      <?php if ( gdpsy_price( 'blog' ) > 0 ) : ?>
      <p class="gz-extra-price"><b><?php echo esc_html( gdpsy_money( 'blog' ) ); ?></b></p>
      <?php endif; ?>
      <a class="gz-button gz-button--navy" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Меня заинтересовало ваше предложение о сайте с блогом или каталогом статей. Давайте обсудим." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить блог</a>
    </article>
    <article class="gz-extra gz-extra--tools" aria-labelledby="gz-extra-tools-title">
      <div class="gz-extra-label">ПОЛЬЗА ДЛЯ КЛИЕНТОВ</div>
      <h3 id="gz-extra-tools-title">Тесты, квизы, упражнения</h3>
      <p class="gz-extra-copy">Виджет или тест-квиз из каталога — для короткой практики, самонаблюдения или знакомства с вашим подходом. Подберём подходящий инструмент и оформим его в стиле вашего сайта. Один или несколько.</p>
      <?php if ( gdpsy_price( 'toolkit' ) > 0 ) : ?>
      <p class="gz-extra-price"><b><?php echo esc_html( gdpsy_price_label( 'toolkit', 'от ' ) ); ?></b><span> / шт</span></p>
      <?php endif; ?>
      <a class="gz-button gz-button--outline" href="#gz-breathe-demo">Попробовать пример <span aria-hidden="true">↓</span></a>
    </article>
  </div>
</section>

