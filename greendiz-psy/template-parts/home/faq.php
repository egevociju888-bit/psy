<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="gz-block gz-faq gz-inner" id="gz-faq" aria-labelledby="gz-faq-title">
        <div class="gz-section-head">
          <div class="gz-screen-meta">
            <div class="gz-index">важно знать</div>
            <div class="gz-screen-caption">ДО И ПОСЛЕ ЗАПУСКА</div>
          </div>

          <h2 class="gz-screen-title" id="gz-faq-title">
            <span class="gz-title-main">Частые</span>
            <span class="gz-title-accent">вопросы</span>
          </h2>
        </div>
        <div class="gz-faq-grid">
          <?php foreach ( gdpsy_home_faq_sections() as $gdpsy_faq_section ) : ?>
          <div>
            <div class="gz-faq-group"><?php echo esc_html( $gdpsy_faq_section['title'] ); ?></div>
            <?php foreach ( $gdpsy_faq_section['questions'] as $gdpsy_faq_question ) : ?>
            <details>
              <summary><?php echo esc_html( $gdpsy_faq_question['question'] ); ?></summary>
              <div class="gz-faq-answer">
                <p><?php echo esc_html( $gdpsy_faq_question['answer'] ); ?></p>
              </div>
            </details>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="gz-section-side gz-faq-contact">
        <?php $gdpsy_faq_url = gdpsy_page_url( 'faq' ); if ( $gdpsy_faq_url ) : ?><a class="gz-faq-all gz-inline-link gz-button gz-button--white" href="<?php echo esc_url( $gdpsy_faq_url ); ?>">Все вопросы и ответы</a><?php endif; ?>
        <a class="gz-faq-all gz-inline-link gz-button gz-button--white" href="<?php echo esc_url( gdpsy_blog_url() ); ?>">Полезные статьи</a>
        </div>
      </section>
