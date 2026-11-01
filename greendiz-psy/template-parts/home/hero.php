<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_from = array_filter( array( gdpsy_price( 'concept' ), gdpsy_price( 'base' ) ) );
$gdpsy_from_label = $gdpsy_from ? 'от ' . number_format( min( $gdpsy_from ), 0, '', "\u{00A0}" ) . "\u{00A0}₽" : '';
?>
<section class="gz-hero gz-inner" id="gz-first" aria-labelledby="gz-hero-title">
        <div class="gz-hero-core">
          <div class="gz-hero-intro">
            <h1 class="gz-hero-headline" id="gz-hero-title">
              <span class="gz-hero-word">Сайт</span>
              <span class="gz-hero-message">
                <span class="gz-hero-audience"><span>для психолога,</span> <span>психотерапевта, коуча</span><span class="gz-sr-only">,</span></span>
                <span>с которым клиенту</span>
                <span>легче сделать</span>
                <span>первый шаг</span>
              </span>
            </h1>
            <svg class="gz-hero-slash" viewBox="0 0 100 180" fill="none" aria-hidden="true">
              <path d="M1 179 99 1" stroke="currentColor" stroke-width="1" vector-effect="non-scaling-stroke"></path>
            </svg>
          </div>
          <div class="gz-hero-foot">
            <?php if ( has_nav_menu( 'primary_right' ) ) : ?>
            <div class="gz-hero-navigation">
              <span class="gz-hero-caption sub" aria-hidden="true">Человек → Структура → Сайт</span>
              <nav class="gz-hero-nav" aria-label="Разделы страницы"><?php gdpsy_menu( 'primary_right', 'hero', 1 ); ?></nav>
            </div>
            <?php endif; ?>
            <section class="gz-hero-quiz" aria-labelledby="gz-hero-quiz-title">
              <h2 id="gz-hero-quiz-title">Не знаете, что вам нужно?</h2>
              <div class="gz-hero-quiz-info" data-open-dialog="gz-quiz">
                <ol class="gz-hero-quiz-steps" aria-hidden="true">
                  <li><span>1</span></li>
                  <li><span>2</span></li>
                  <li><span>3</span></li>
                  <li><span>4</span></li>
                  <li><span>5</span></li>
                  <li><span class="gz-hero-quiz-result"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="m5 12 5 5 9-10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>состав и цена</span></li>
                </ol>
                <p>5 вопросов — рекомендация сразу, <span class="gz-hero-quiz-tail">без контактов</span></p>
              </div>
              <button class="gz-primary gz-button gz-button--large gz-hero-cta" type="button" data-open-dialog="gz-quiz" aria-haspopup="dialog" aria-controls="gz-quiz">Узнать, какой сайт мне нужен<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-6-6 6 6-6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></button>
            </section>
          </div>
          <div class="gz-hero-meta">
            <?php if ( $gdpsy_from_label ) : ?><p><?php echo esc_html( 'Лендинг ' . $gdpsy_from_label . " · запуск за 7\u{00A0}дней" ); ?></p><?php endif; ?>
            <p>С нуля или по авторскому концепту</p>
          </div>
        </div>
      </section>
      <aside class="gz-block gz-pain gz-inner" id="gz-help" aria-labelledby="gz-help-title">
        <div class="gz-help-grid">
          <div>
            <h2 class="gz-help-heading" id="gz-help-title">
              <span>Вы помогаете людям</span>
              <span>распутывать сложное.</span>
              <span>Я помогу вам</span>
              <span>распутать сайт.</span>
            </h2>
          </div>
          <ul class="gz-help-list">
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p>Задам вопросы и соберу тексты из ваших ответов</p>
            </li>
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p>Помогу с фото, ретушью и графикой</p>
            </li>
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p>Возьму на себя техническую часть и покажу, как пользоваться сайтом</p>
            </li>
            <?php if ( $gdpsy_from_label ) : ?>
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p><?php echo esc_html( $gdpsy_from_label ); ?>, фиксируем на старте, без доплат</p>
            </li>
            <?php endif; ?>
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p>7 дней от брифа до запуска лендинга</p>
            </li>
            <li>
              <span class="gz-help-check" aria-hidden="true">✓</span>
              <p>152-ФЗ: согласия, политика, сервер в РФ</p>
            </li>
          </ul>
        </div>
      </aside>



