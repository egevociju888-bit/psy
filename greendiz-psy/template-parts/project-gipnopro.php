<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="gz-project-dark gz-inner">
  <div class="gz-breadcrumbs">
    <?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => array(
      array( 'title' => 'Главная', 'url' => home_url( '/' ) ),
      array( 'title' => 'Проекты', 'url' => add_query_arg( 'case-type', 'project', gdpsy_archive_url() ) ),
      array( 'title' => 'Gipnopro.ru', 'url' => '' ),
    ) ) ); ?>
    <span>Кейс</span>
  </div>
</div>
<div class="gipnopro-case" id="gipnopro-case">

    <div class="dk">
<section class="w s case-hero" aria-labelledby="case-hero-title">
        <div class="case-hero-layout">
          <div class="case-hero-copy">
            <span class="case-hero-label">ПРОЕКТ · САЙТ ДЛЯ ПСИХОЛОГА</span>
            <h1 id="case-hero-title">Gipnopro.ru</h1>
            <p class="case-hero-description">Сайт для частной практики Ольги Филатовой. <br>Спокойный визуальный стиль, понятная структура услуг и блог — чтобы познакомиться со специалистом и выбрать удобный способ обращения.</p>
            <p class="case-hero-subtitle">Редизайн и разработка под ключ.</p>
            <a class="case-hero-cta" href="#project-details">Смотреть проект <span aria-hidden="true">↓</span></a>
          </div>
          <div class="case-hero-project">
            <aside class="case-hero-summary" aria-label="О проекте">
              <dl>
                <div><dt>Задача</dt><dd>Знакомство со специалистом и выбор консультации</dd></div>
                <div><dt>Моя работа</dt><dd>Редизайн и разработка под ключ</dd></div>
                <div><dt>Состав</dt><dd>Услуги, методы, кабинеты и блог</dd></div>
              </dl>
              <a class="gz-button" href="<?php echo esc_url( 'https://gipnopro.ru/' ); ?>" target="_blank" rel="noopener noreferrer">Открыть сайт ↗</a>
            </aside>
            <div class="case-hero-visual">
              <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/img_1.webp' ) ); ?>" width="520" height="1094" alt="Мобильный экран сайта Ольги Филатовой" decoding="async">
            </div>
          </div>
        </div>
      </section>

    </div>

    <div class="gipnopro-content">
      <section id="project-details" class="s alt" aria-labelledby="project-details-title">
        <div class="w project-details-layout">
          <div class="project-details-heading">
            <h2 id="project-details-title">Задача<br>и решение</h2>
          </div>
          <ul class="project-details-list" aria-label="Задачи редизайна и их решения">
            <li class="project-details-item">
              <h3 class="project-details-task">Передать спокойный характер практики</h3>
              <p class="project-details-solution">Природная палитра, морские образы и единая типографика.</p>
            </li>
            <li class="project-details-item">
              <h3 class="project-details-task">Упростить знакомство с услугами</h3>
              <p class="project-details-solution">Основная информация — на главной. Методы и кабинеты — на отдельных страницах.</p>
            </li>
            <li class="project-details-item">
              <h3 class="project-details-task">Подготовить сайт к развитию блога</h3>
              <p class="project-details-solution">Рубрики по направлениям работы и шаблон публикаций.</p>
            </li>
          </ul>
        </div>
      </section>

      <section class="s alt visual-style" aria-labelledby="visual-style-title">
        <div class="w">
          <div class="vs">
            <div class="vs-copy">
              <h2 id="visual-style-title">Визуальный стиль</h2>
              <p class="vs-description">Море и камни дают образ устойчивости и покоя: они успокаивают
                и не повторяют привычные для гипноза клише вроде спиралей и маятников. Приглушённые сине-серые и
                песочные оттенки поддерживают ощущение безопасности и доверия.</p>
            </div>
            <div class="vs-board">
            <div class="vs-collage" role="group" aria-label="Море, пляж и камни — визуальные образы сайта">
              <figure class="vs-photo vs-photo--sea">
                <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/ref_1.webp' ) ); ?>" alt="Морские волны и прибрежные скалы" loading="lazy" decoding="async">
              </figure>
              <figure class="vs-photo vs-photo--beach">
                <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/ref_3.webp' ) ); ?>" alt="Песчаный пляж у моря" loading="lazy" decoding="async">
              </figure>
              <figure class="vs-photo vs-photo--stones">
                <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/ref_2.webp' ) ); ?>" alt="Гладкие морские камни" loading="lazy" decoding="async">
              </figure>
            </div>
              <div class="sw" role="img" aria-label="Палитра: тёмно-синий, сине-серый, светло-серый, кремовый, песочный и золотистый">
                <div style="background:#394B6C"></div>
                <div style="background:#5E7198"></div>
                <div style="background:#D7DEE9"></div>
                <div style="background:#F5EAD7"></div>
                <div style="background:#EDD8B1"></div>
                <div style="background:#A5834F"></div>
              </div>
              <div class="vrow">
                <div class="logo-box"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/logo.png' ) ); ?>" alt="Логотип Ольги Филатовой" loading="lazy" decoding="async"></div>
                <div>
                  <p class="vs-typeface">Rolleston Display</p>
                  <p class="mut vs-type-caption">заголовки · основной текст Cera Pro</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="s alt keystails">
	  <div class="w">
        <h2>Ключевые решения</h2>
        <div class="dl">

          <article class="card kd">
            <div class="dv mockup-with-hint">
              <div class="fr">
              <div class="fb" aria-hidden="true"><i></i><i></i><i></i></div>
              <div class="fs" tabindex="0" role="region" aria-label="Скриншот главной страницы" aria-describedby="mobile-scroll-hint-01">
			  <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/main_desc.webp' ) ); ?>" alt="Главная: блоки «С чем я могу помочь», «Что вы получите», методы работы, образование, формат и стоимость" width="756" height="1665" decoding="async" loading="lazy">
			  </div>
              </div>
              <p class="mobile-scroll-hint" id="mobile-scroll-hint-01"><svg viewBox="0 0 16 20" aria-hidden="true"><path d="M8 2v16M4 6l4-4 4 4M4 14l4 4 4-4"></path></svg>Экран внутри можно прокручивать</p>
            </div>
            <div>
              <p class="eyebrow">01 · Знакомство</p>
              <h3>Главная отвечает на вопросы клиента по порядку</h3>
              <p>С чем помогу, что получите, методы, образование, формат и стоимость, отзывы, частые вопросы. Стоимость
                указана открыто: 10 000 ₽ за сеанс, 2 часа.</p>
              <p><b>В итоге:</b> посетитель может быстро оценить, подходит ли ему специалист: запросы, подход, цена и
                условия собраны рядом. Это помогает принять решение о первом обращении.</p>
              <div class="kpi"><b>4,9%</b><span>посетителей из рекламы оформили заявку,<br>из поиска — 2,0%</span></div>
            </div>
          </article>

          <article class="card kd rev card_2">
            <div class="dv fr credentials-slider">
              <div class="credentials-viewport">
                <div class="credentials-track">
                  <figure class="credentials-slide"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/gipnopro1.webp' ) ); ?>" alt="Образование и квалификация Ольги Филатовой" loading="lazy" decoding="async"></figure>
                  <figure class="credentials-slide"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/gipnopro2.webp' ) ); ?>" alt="Дипломы и сертификаты психолога" loading="lazy" decoding="async"></figure>
                </div>
              </div>
              <div class="mobile-controls credentials-controls" hidden>
                <button type="button" class="credentials-prev" aria-label="Предыдущий слайд"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3l-9 9 9 9M5 12h17"/></svg></button>
                <span class="mobile-count credentials-count" aria-live="polite" aria-atomic="true">1 / 2</span>
                <button type="button" class="credentials-next" aria-label="Следующий слайд"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 3l9 9-9 9M19 12H2"/></svg></button>
              </div>
            </div>
            <div>
              <p class="eyebrow">02 · Профессиональная опора</p>
              <h3>Доверие: цифры, образование, отзывы</h3>
              <p>Важная информация на первом экране: 400+ часов обучения, 3000+ консультаций, 11 лет практики.
                Образование хронологией со ссылкой на дипломы. Анонимные, но подробные отзывы.</p>
              <p><b>В итоге:</b> квалификация видна без длинной биографии, а в отзывах человек находит свою ситуацию.
              </p>
              <div class="kpi"><b>~9 мин</b><span>примерно столько люди изучали информацию, а потом писали в
                  мессенджер</span></div>
            </div>
          </article>

          <article class="card kd card_3">
            <div class="dv mockup-with-hint">
              <div class="fr">
              <div class="fb" aria-hidden="true"><i></i><i></i><i></i></div>
              <div class="fs" tabindex="0" role="region" aria-label="Скриншот страницы кабинетов, прокручивается" aria-describedby="mobile-scroll-hint-03">
			  <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/gipnopro.ru_kabinety.webp' ) ); ?>" alt="Скриншот страницы кабинетов" width="756" height="1665" decoding="async" loading="lazy"></div>
              </div>
              <p class="mobile-scroll-hint" id="mobile-scroll-hint-03"><svg viewBox="0 0 16 20" aria-hidden="true"><path d="M8 2v16M4 6l4-4 4 4M4 14l4 4 4-4"></path></svg>Экран внутри можно прокручивать</p>
            </div>
            <div>
              <p class="eyebrow">03 · Подробности</p>
              <h3>Методы, кабинеты и контакты отдельными страницами</h3>
              <p>На главной три коротких блока о методах со ссылкой на подробные описания. Кабинеты вынесены на
                отдельную страницу с адресами, фото и картой.</p>
              <p><b>В итоге:</b> главная не перегружена, а нужные подробности находятся в один клик.</p>
              <div class="kpi"><b>242</b><span>раза за год смотрели страницу контактов с адресами и способами
                  связи</span></div>
            </div>
          </article>

          <article class="card kd rev card_4">
            <div class="dv mockup-with-hint">
              <div class="fr">
              <div class="fb" aria-hidden="true"><i></i><i></i><i></i></div>
              <div class="fs" tabindex="0" role="region" aria-describedby="mobile-scroll-hint-04">
			  <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/gipnopro_blog.webp' ) ); ?>" alt="Раздел «Статьи»: карточки публикаций, список рубрик, блок о специалисте и стоимости" width="756" height="1665" decoding="async" loading="lazy"></div>
              </div>
              <p class="mobile-scroll-hint" id="mobile-scroll-hint-04"><svg viewBox="0 0 16 20" aria-hidden="true"><path d="M8 2v16M4 6l4-4 4 4M4 14l4 4 4-4"></path></svg>Экран внутри можно прокручивать</p>
            </div>
            <div>
              <p class="eyebrow">04 · Блог и поиск</p>
              <h3>Статьи помогают познакомиться с подходом психолога</h3>
              <p>Блог организован по направлениям работы. Публикации дополняют страницы услуг: здесь можно подробнее
                раскрыть запросы, методы и подход специалиста.</p>
              <p><b>В итоге:</b> человек может начать знакомство со статьи по своему вопросу и затем перейти к
                информации о консультации. Также блог — пространство для профессиональных публикаций и основа для
                развития поискового трафика.</p>
              <div class="kpi"><b>94</b><span>материалов в блоге;<br>за год сайт получил 1116 визитов из поиска</span>
              </div>
            </div>
          </article>

          <article class="card kd card_5">
            <div class="img_group">
            <div class="item">
			<img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/card_5_1.webp' ) ); ?>" alt="Фрагмент формы записи на консультацию" loading="lazy" decoding="async">
			<img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/card_5_2.webp' ) ); ?>" alt="Фрагмент формы записи на консультацию" loading="lazy" decoding="async">
			</div>
            <div class="item item_2">
			<img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/card_5_3.webp' ) ); ?>" alt="Фрагмент формы записи на консультацию" loading="lazy" decoding="async">
			<img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/card_5_4.webp' ) ); ?>" alt="Фрагмент формы записи на консультацию" loading="lazy" decoding="async">
			</div>

            </div>
            <div>
              <p class="eyebrow">05 · Первый контакт</p>
              <h3>Запись в один шаг</h3>
              <p>Форма с тремя способами связи с быстрым оформлением заявки. Кнопки связи в шапке, в блоке стоимости и в
                футере.</p>
              <p><b>В итоге:</b> быстрый первый шаг. Посетитель выбирает привычный канал – порог первого шага снижается.
              </p>
              <div class="kpi"><b>76%</b><span>людей предпочли мессенджер, <br>а не звонки или форму.</span></div>
            </div>
          </article>

        </div></div>
      </section>

      <section class="w s mobile-showcase" aria-labelledby="mobile-heading">
        <div class="mobile-card case-split" role="region" aria-label="Мобильная версия сайта">
          <div class="mobile-copy case-section-heading">
            <h2 id="mobile-heading">На<br class="case-title-break"> телефоне</h2>
            <p class="mobile-label">Мобильная версия</p>
            <div class="mobile-slide-copy" aria-live="polite" aria-atomic="true">
              <div class="mobile-slide-detail">
              <h3 id="mobile-slide-title">С чем я могу помочь</h3>
              <p class="mobile-description" id="mobile-slide-description">Запросы собраны в понятные карточки: эмоции, отношения, травмы и другие трудности, с которыми работает психолог.</p>
              </div>
            </div>
            <div class="mobile-controls">
              <button type="button" id="mobile-prev" aria-label="Предыдущий экран"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3l-9 9 9 9M5 12h17"/></svg></button>
              <span class="mobile-count" id="mobile-count">1 / 5</span>
              <button type="button" id="mobile-next" aria-label="Следующий экран"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 3l9 9-9 9M19 12H2"/></svg></button>
            </div>
            <p class="mobile-scroll-hint" id="mobile-scroll-hint"><svg viewBox="0 0 16 20" aria-hidden="true"><path d="M8 2v16M4 6l4-4 4 4M4 14l4 4 4-4"/></svg>Экран внутри телефона можно прокручивать</p>
          </div>
          <div class="mobile-visual">
            <div class="mobile-carousel">
            <div class="mobile-viewport">
<div class="mobile-devices" data-image-base="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/' ) ); ?>">
<div class="mobile-device"><div class="mobile-screen" tabindex="0" role="region" aria-label="С чем я могу помочь, можно прокручивать" aria-describedby="mobile-scroll-hint"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/mob_1.webp' ) ); ?>" alt="С чем я могу помочь" decoding="async" loading="lazy"></div><p class="gp-mobile-caption">С чем я могу помочь — Запросы собраны в понятные карточки: эмоции, отношения, травмы и другие трудности, с которыми работает психолог.</p></div>
<div class="mobile-device"><div class="mobile-screen" tabindex="0" role="region" aria-label="Результаты терапии, можно прокручивать" aria-describedby="mobile-scroll-hint"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/mob_2.webp' ) ); ?>" alt="Результаты терапии" decoding="async" loading="lazy"></div><p class="gp-mobile-caption">Результаты терапии — Возможные изменения описаны простым языком: лучше понимать себя, справляться с эмоциями и выстраивать отношения.</p></div>
<div class="mobile-device"><div class="mobile-screen" tabindex="0" role="region" aria-label="Формат и стоимость, можно прокручивать" aria-describedby="mobile-scroll-hint"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/mob_3.webp' ) ); ?>" alt="Формат и стоимость" decoding="async" loading="lazy"></div><p class="gp-mobile-caption">Формат и стоимость — Этапы работы, стоимость сессии и варианты консультаций собраны рядом с кнопкой записи.</p></div>
<div class="mobile-device"><div class="mobile-screen" tabindex="0" role="region" aria-label="Ответы на вопросы, можно прокручивать" aria-describedby="mobile-scroll-hint"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/mob_4.webp' ) ); ?>" alt="Ответы на вопросы" decoding="async" loading="lazy"></div><p class="gp-mobile-caption">Ответы на вопросы — Частые вопросы о консультациях раскрываются по нажатию — удобно читать с телефона.</p></div>
<div class="mobile-device"><div class="mobile-screen" tabindex="0" role="region" aria-label="Страница метода, можно прокручивать" aria-describedby="mobile-scroll-hint"><img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/mob_5.webp' ) ); ?>" alt="Страница метода" decoding="async" loading="lazy"></div><p class="gp-mobile-caption">Страница метода — Подробная страница об эмоционально-образной терапии: подход, процесс работы и запись на консультацию.</p></div>
            </div>
            </div>
            </div>
          </div>
        </div>
      </section>

      <section id="site-map" class="s alt" aria-labelledby="sitemap-title">
        <div class="w sitemap-layout case-split">
          <header class="sitemap-heading case-section-heading">
            <h2 id="sitemap-title">Карта<br class="case-title-break"> сайта</h2>
            <div class="sitemap-subtitle">
              <a class="sitemap-project-link" href="https://gipnopro.ru/" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l10-10a4 4 0 0 1 5.66 5.66l-10 10a2 2 0 0 1-2.83-2.83l9.19-9.19"/></svg>
                <span>Gipnopro.ru</span>
              </a>
              <div class="sitemap-page-count"><strong>108</strong><span>страниц</span></div>
            </div>
          </header>
          <div class="sitemap-grid">
            <div class="sitemap-main">
              <div>
                <h3>Главная</h3>
                <p class="sitemap-note">Один длинный экран из блоков</p>
                <ul class="sitemap-home-list">
                  <li>Описание услуг</li>
                  <li>Методы работы</li>
                  <li>Образование</li>
                  <li>Формат и стоимость</li>
                  <li>Отзывы</li>
                  <li>Частые вопросы</li>
                </ul>
              </div>
              <div class="sitemap-section">
                <h3>О консультациях</h3>
                <ul>
                  <li>Как проходит работа</li>
                  <li>Кабинеты для очных сессий</li>
                </ul>
                <p class="sitemap-methods-label">Страницы методов</p>
                <ul>
                  <li>Эмоционально-образная терапия</li>
                  <li>Эриксоновская терапия</li>
                  <li>Регрессотерапия</li>
                </ul>
              </div>
              <div class="sitemap-section sitemap-bottom">
                <div>
                  <h3>О психологе</h3>
                  <ul>
                    <li>Образование и дипломы</li>
                    <li>Отзывы · 7 страниц</li>
                  </ul>
                </div>
                <div>
                  <h3>Контакты</h3>
                  <ul><li>Адреса, мессенджеры, форма</li></ul>
                </div>
              </div>
            </div>
            <div class="sitemap-sidebar">
              <div class="sitemap-blog">
                <div class="sitemap-blog-summary">
                  <div class="sitemap-blog-heading">
                    <h3>Блог</h3>
                    <p class="sitemap-blog-total"><strong>94</strong><span>материала</span></p>
                  </div>
                  <p class="sitemap-blog-share">87% всех страниц сайта</p>
                  <p class="sitemap-blog-description">Каждая категория усиливает свой метод в поиске.</p>
                </div>
                <div class="sitemap-blog-topics">
                  <p class="sitemap-blog-topics-label">Четыре категории</p>
                  <ul>
                    <li>Психологическая помощь</li>
                    <li>Психотерапия</li>
                    <li>Регрессотерапия</li>
                    <li>Эриксоновская терапия</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="result" class="w s" aria-labelledby="result-title">
        <div class="result-summary case-split">
          <header class="result-summary-heading case-section-heading">
            <h2 id="result-title">Результат<br class="case-title-break"> работы</h2>
            <p>Сайт представляет практику и ведёт к первому контакту</p>
          </header>
          <div class="result-summary-content">
          <div id="res-contact" class="result-summary-card">
            <p class="result-summary-label">Из рекламы и поиска</p>
            <div class="result-summary-top">
              <div class="result-summary-total">
                <strong>84</strong>
                <span>заявки за год</span>
              </div>
              <div class="result-summary-source">
                <h3>реклама</h3>
                <p><strong>1023</strong> <span>визита</span></p>
                <p><strong>5%</strong> <span>конверсия</span></p>
              </div>
              <div class="result-summary-source">
                <h3>из поиска</h3>
                <p><strong>1116</strong> <span>визитов</span></p>
                <p><strong>2%</strong> <span>конверсия</span></p>
              </div>
            </div>
            <div class="result-summary-bottom">
              <p><strong>64</strong> <span>перехода<br>в мессенджеры</span></p>
              <p><strong>20</strong> <span>кликов<br>по телефону</span></p>
              <p><strong>~7</strong> <span>действий<br>в месяц</span></p>
            </div>
          </div>
          <section id="res-search" class="traffic-reference" aria-labelledby="traffic-title">
            <h3 id="traffic-title">Откуда приходят на сайт</h3>
            <div class="traffic-reference-grid">
              <table class="traffic-source-table" aria-label="Источники трафика за год">
                <thead><tr><th scope="col">Источники</th><th scope="col">Визиты</th><th scope="col">Доля</th></tr></thead>
                <tbody>
                <tr>
                  <th scope="row"><button type="button" data-source="search" aria-pressed="false"><i style="background:#5E7198" aria-hidden="true"></i><span>Поиск</span></button></th>
                  <td class="traffic-visits">1 116</td><td>46,2%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="ads" aria-pressed="false"><i style="background:#8E9BB6" aria-hidden="true"></i><span>Реклама</span></button></th>
                  <td class="traffic-visits">1 023</td><td>42,3%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="social" aria-pressed="false"><i style="background:#D3DDE8" aria-hidden="true"></i><span>Соцсети</span></button></th>
                  <td class="traffic-visits">159</td><td>6,6%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="internal" aria-pressed="false"><i style="background:#AD9A79" aria-hidden="true"></i><span>Внутренние переходы</span></button></th>
                  <td class="traffic-visits">94</td><td>3,9%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="recommend" aria-pressed="false"><i style="background:#E6DFCF" aria-hidden="true"></i><span>Рекомендательные системы</span></button></th>
                  <td class="traffic-visits">21</td><td>0,87%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="saved" aria-pressed="false"><i style="background:#F2EEE6" aria-hidden="true"></i><span>Сохранённые страницы</span></button></th>
                  <td class="traffic-visits">3</td><td>0,12%</td>
                </tr>
                <tr>
                  <th scope="row"><button type="button" data-source="chatgpt" aria-pressed="false"><i style="background:#7D8080" aria-hidden="true"></i><span>ChatGPT</span></button></th>
                  <td class="traffic-visits">2</td><td>0,08%</td>
                </tr>
                </tbody>
              </table>
              <figure class="traffic-chart" aria-label="Распределение 2418 визитов по источникам">
                <div class="donut-wrap">
                  <svg class="traffic-donut" viewBox="0 0 340 340"
                  aria-label="2418 визитов из выбранных источников. Количества визитов указаны в легенде.">
                  <circle cx="170" cy="170" r="124" fill="none" stroke="#F5EEDF" stroke-width="48"></circle>
                  <circle class="traffic-segment" data-source="search" role="button" tabindex="0" aria-pressed="false"
                    aria-label="Поиск: 1 116 визитов, 46,2%. Выбрать источник." cx="170" cy="170" r="124" fill="none"
                    stroke="#5E7198" stroke-width="48" pathLength="100" stroke-dasharray="46.153846154 53.846153846"
                    stroke-dashoffset="0.000000000" transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="ads" role="button" tabindex="0" aria-pressed="false"
                    aria-label="Реклама: 1 023 визитов, 42,3%. Выбрать источник." cx="170" cy="170" r="124" fill="none"
                    stroke="#C5D0E0" stroke-width="48" pathLength="100" stroke-dasharray="42.307692308 57.692307692"
                    stroke-dashoffset="-46.153846154" transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="social" role="button" tabindex="0" aria-pressed="false"
                    aria-label="Соцсети: 159 визитов, 6,6%. Выбрать источник." cx="170" cy="170" r="124" fill="none"
                    stroke="#D3DDE8" stroke-width="48" pathLength="100" stroke-dasharray="6.575682382 93.424317618"
                    stroke-dashoffset="-88.461538462" transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="internal" role="button" tabindex="0" aria-pressed="false"
                    aria-label="Внутренние переходы: 94 визитов, 3,9%. Выбрать источник." cx="170" cy="170" r="124"
                    fill="none" stroke="#AD9A79" stroke-width="48" pathLength="100"
                    stroke-dasharray="3.887510339 96.112489661" stroke-dashoffset="-95.037220844"
                    transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="recommend" role="button" tabindex="0"
                    aria-pressed="false" aria-label="Рекомендательные системы: 21 визитов, 0,87%. Выбрать источник."
                    cx="170" cy="170" r="124" fill="none" stroke="#E6DFCF" stroke-width="48" pathLength="100"
                    stroke-dasharray="0.868486352 99.131513648" stroke-dashoffset="-98.924731183"
                    transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="saved" role="button" tabindex="0" aria-pressed="false"
                    aria-label="Сохранённые страницы: 3 визитов, 0,12%. Выбрать источник." cx="170" cy="170" r="124"
                    fill="none" stroke="#F2EEE6" stroke-width="48" pathLength="100"
                    stroke-dasharray="0.124069479 99.875930521" stroke-dashoffset="-99.793217535"
                    transform="rotate(-90 170 170)"></circle>
                  <circle class="traffic-segment" data-source="chatgpt" role="button" tabindex="0" aria-pressed="false"
                    aria-label="ChatGPT: 2 визитов, 0,08%. Выбрать источник." cx="170" cy="170" r="124" fill="none"
                    stroke="#7D8080" stroke-width="48" pathLength="100" stroke-dasharray="0.082712986 99.917287014"
                    stroke-dashoffset="-99.917287014" transform="rotate(-90 170 170)"></circle>
                </svg>
                  <div class="donut-center" role="status" aria-live="polite" aria-atomic="true">
                    <output id="traffic-value">2 418</output>
                    <span id="traffic-label">визитов за год</span>
                    <small id="traffic-share">из разных источников</small>
                  </div>
                </div>
              </figure>
            </div>
          </section>

        <details id="res-more" class="stats-more">
          <summary>Ещё больше статистики</summary>
          <div class="stats-more-body">
            <h3 id="res-detail" class="rs">Реклама и поиск в цифрах</h3>
            <p class="mut rsub">Какую долю визитов каждый канал превращает в действие: звонок, сообщение, открытие
              контактов, чтение 5 и более страниц.</p>
            <div class="chn">
              <div class="card">
                <h3>Реклама</h3>
                <p class="mut" style="font-size:14px;margin:2px 0 0">Яндекс Директ и реклама по меткам</p>
                <div class="mm">
                  <div><b>1&nbsp;023</b><span>визита</span></div>
                  <div><b>855</b><span>посетителей</span></div>
                  <div><b>54</b><span>действия для связи</span></div>
                  <div><b>4,9%</b><span>посетителей выполнили цель связи</span></div>
                  <div><b>23%</b><span>отказов</span></div>
                  <div><b>1:20</b><span>время на сайте</span></div>
                </div>
                <p class="lb mut" style="font-size:13px;text-transform:uppercase;letter-spacing:.34em;margin:29px 0 10px">
                  Доля визитов с действием</p>
                <div class="gb"><span>Переход в мессенджер</span><i><u class="cG" style="width:62.6%"></u></i><em>3,13%
                    <small>· 32</small></em></div>
                <div class="gb"><span>Читали 5+ страниц</span><i><u class="cG" style="width:74.2%"></u></i><em>3,71%
                    <small>· 38</small></em></div>
                <div class="gb"><span>Открыли контакты</span><i><u class="cG" style="width:58.6%"></u></i><em>2,93%
                    <small>· 30</small></em></div>
                <div class="gb"><span>Клик по телефону</span><i><u class="cG" style="width:23.4%"></u></i><em>1,17%
                    <small>· 12</small></em></div>
              </div>
              <div class="card">
                <h3>Поиск</h3>
                <p class="mut" style="font-size:14px;margin:2px 0 0">Яндекс, Google и другие поисковые системы</p>
                <div class="mm">
                  <div><b>1&nbsp;116</b><span>визитов</span></div>
                  <div><b>911</b><span>посетителей</span></div>
                  <div><b>30</b><span>действий для связи</span></div>
                  <div><b>2,0%</b><span>посетителей выполнили цель связи</span></div>
                  <div><b>46%</b><span>отказов</span></div>
                  <div><b>1:17</b><span>время на сайте</span></div>
                </div>
                <p class="lb mut" style="font-size:13px;text-transform:uppercase;letter-spacing:.34em;margin:29px 0 10px">
                  Доля визитов с действием</p>
                <div class="gb"><span>Переход в мессенджер</span><i><u class="cS" style="width:30.4%"></u></i><em>1,52%
                    <small>· 17</small></em></div>
                <div class="gb"><span>Читали 5+ страниц</span><i><u class="cS" style="width:96.8%"></u></i><em>4,84%
                    <small>· 54</small></em></div>
                <div class="gb"><span>Открыли контакты</span><i><u class="cS" style="width:44.8%"></u></i><em>2,24%
                    <small>· 25</small></em></div>
                <div class="gb"><span>Клик по телефону</span><i><u class="cS" style="width:9%"></u></i><em>0,45%
                    <small>· 5</small></em></div>
              </div>
            </div>
            <p class="fn" style="margin-top:12px">Цифра после процента — число визитов, в которых было это действие.
              Длина полосы: 5% = вся шкала.</p>

            <h3 id="res-engagement" class="rs" style="margin-top:36px">Изучение сайта в визитах с действием</h3>
            <p class="mut rsub">Средняя длительность визита, в котором человек совершил действие.</p>
            <div class="t3">
              <div class="stat">
                <p class="eyebrow">Переход в мессенджер через</p>
                <div class="num">~9 мин</div>
                <p class="mut">Средний визит с переходом в мессенджер.<br>4,2 страницы за визит · 80 визитов</p>
              </div>
              <div class="stat">
                <p class="eyebrow">Переход в контакты через</p>
                <div class="num">~5,5 мин</div>
                <p class="mut">Средний визит с просмотром контактов.<br>4,3 страницы за визит · 195 визитов</p>
              </div>
              <div class="stat">
                <p class="eyebrow">Прочитано 5+ страниц за</p>
                <div class="num">~15,5 мин</div>
                <p class="mut">Средний визит с просмотром 5+ страниц.<br>9,8 страницы за визит · 166 визитов</p>
              </div>
            </div>

            <div id="res-channel" class="result-pair result-section">
              <div class="card channel-card">
                <p class="eyebrow">Способ первого контакта</p>
                <div class="num">76%</div>
                <h3>действий для связи приходится на мессенджеры</h3>
                <p>В рекламе и поиске переходы в мессенджеры встречаются чаще кликов по телефону. Поэтому на сайте
                  предусмотрено несколько удобных каналов связи.</p>
                <div class="bar" role="img" aria-label="64 перехода в мессенджеры и 20 кликов по телефону"><span
                    style="width:76.19%;background:#394B6C">Мессенджеры · 64</span><span
                    style="width:23.81%;background:#EDD8B1;color:#1C1C1C">20</span></div>
                <div class="bar-labels"><span>Мессенджеры — 76,2%</span><span>Телефон — 23,8%</span></div>
                <div class="channel-mini">
                  <div><strong>3,13%</strong>
                    <p>рекламных визитов с переходом в мессенджер</p>
                  </div>
                  <div><strong>1,52%</strong>
                    <p>поисковых визитов с переходом в мессенджер</p>
                  </div>
                </div>
              </div>
              <div class="card journey-card">
                <p class="eyebrow">Изучение перед обращением</p>
                <h3>На сайте находят подробности о практике</h3>
                <p>Страницы методов, кабинетов, образования и ответы на вопросы дополняют первый экран. Отчёты
                  показывают посещения контактов и изучение нескольких страниц.</p>
                <ul class="journey-list">
                  <li><strong>55 визитов с просмотром контактов</strong>
                    <p>30 из рекламы и 25 из поиска. Всего 63 достижения этой цели.</p>
                  </li>
                  <li><strong>92 визита с просмотром 5 и более страниц</strong>
                    <p>38 из рекламы и 54 из поиска. Это отдельный срез вовлечённости, а не дополнительные обращения.
                    </p>
                  </li>
                  <li><strong>4,84% поисковых визитов — с просмотром 5 и более страниц</strong>
                    <p>У рекламного сегмента показатель составляет 3,71%.</p>
                  </li>
                </ul>
              </div>
            </div>

            <div class="stats-method">
              <h4>Как читать показатели</h4>
              <p>Действие для связи — клик по телефону или переход в мессенджер. Достижения целей, визиты с действием и
                посетители с действием — разные показатели.</p>
              <p>Конверсия посетителей в действие для связи: реклама — 4,91%, поиск — 1,98%. В карточке эти значения
                округлены до 5% и 2%.</p>
              <p>2 139 визитов — сумма поиска (1 116) и рекламы (1 023). Она не включает соцсети, переходы по ссылкам,
                рекомендательные системы и прямые заходы. </p>
              <p>Источник: Яндекс Метрика</p>
            </div>
          </div>
        </details>
          </div>
        </div>
      </section>

      <section id="res-build" class="w s project-outcome" aria-labelledby="project-outcome-title">
        <p class="project-outcome-label">Итог разработки</p>
        <h2 id="project-outcome-title">Что получила психолог</h2>
        <div class="project-outcome-grid">
          <ol class="project-outcome-list" aria-label="Результаты разработки">
              <li>
                <div>
                  <h3>Целостное представление практики</h3>
                  <p>Фирменный стиль, информация об образовании, опыте и методах объединены в понятную систему.</p>
                </div>
              </li>
              <li>
                <div>
                  <h3>Понятный путь к первому контакту</h3>
                  <p>Формат, стоимость, кабинеты и способы связи доступны на главной и в соответствующих разделах.</p>
                </div>
              </li>
              <li>
                <div>
                  <h3>Основа для дальнейшего продвижения</h3>
                  <p>Отдельные страницы методов и блог с категориями позволяют развивать содержание сайта.</p>
                </div>
              </li>
          </ol>
          <figure class="project-review" aria-labelledby="project-review-title">
            <h3 id="project-review-title" class="project-review-label">Отзыв заказчика</h3>
            <blockquote>
              <p>Когда встал вопрос о редизайне, было важно создать современный, но при этом
              спокойный и располагающий к доверию ресурс — ведь это сайт психолога, и первое впечатление здесь имеет
              огромное значение. Мне хотелось, чтобы визуал отражал профессиональный, бережный подход. Настя помогла
              определиться со структурой, продумала удобное разделение по услугам и кабинетам, организовала блог с
              категориями для дальнейшего SEO-продвижения. В результате получился не просто красивый, а действительно
              комфортный и удобный сайт, где вся важная информация находится на виду.</p>
            </blockquote>
            <figcaption class="project-review-author">
              <img src="<?php echo esc_url( get_theme_file_uri( 'media/gipnopro/photo.webp' ) ); ?>" alt="Ольга Филатова" width="104" height="104" loading="lazy" decoding="async">
              <div>
                <p class="project-review-name">Ольга Филатова</p>
                <p class="project-review-role">Клинический психолог, ЭОТ-терапевт, гипнолог</p>
                <a href="https://gipnopro.ru/" target="_blank" rel="noopener noreferrer">gipnopro.ru <span aria-hidden="true">↗</span></a>
              </div>
            </figcaption>
          </figure>
        </div>
      </section>

    </div>
</div>

