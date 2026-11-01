<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_template_part( 'template-parts/quiz-dialog' ); ?>
<dialog class="gz-dialog gz-detail-dialog" id="gz-module-interactions" aria-labelledby="gz-interactions-title" aria-describedby="gz-module-interactions-lead">
      <div class="gz-dialog-head"><div><h2 id="gz-interactions-title">Интерактивы и механики</h2></div><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть">×</button></div>
      <div class="gz-dialog-content">
        <p class="gz-detail-description" id="gz-module-interactions-lead">Подбираю сценарии под вашу специализацию, подход и язык. Готовый инструмент можно адаптировать под сайт; разработку новой механики и изменение логики согласуем отдельно.</p>
        <div class="gz-detail-features"><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Сделать паузу</h3></div><p>Дыхание, заземление 5–4–3–2–1, выгрузка мыслей и другие короткие практики.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Заметить состояние</h3></div><p>Образ погоды, вопросы о самочувствии с подсказками и ориентир для обращения к специалисту.</p></section><section class="gz-detail-feature"><div class="gz-detail-feature-head"><h3>Найти слова</h3></div><p>Конструктор первого сообщения, я-сообщения, другой смысл привычной фразы и памятки для знакомых ситуаций.</p></section></div><div class="gz-detail-followup"><nav aria-label="Примеры механик в концептах"><?php $gdpsy_examples = array_filter( gdpsy_selected( 'concepts' ), function ( $post ) { return gdpsy_field( 'case-type', $post->ID ) === 'consept'; } ); foreach ( array_slice( $gdpsy_examples, 0, 3 ) as $gdpsy_example ) : ?><a class="gz-text-action" href="<?php echo esc_url( get_permalink( $gdpsy_example ) ); ?>"><?php echo esc_html( get_the_title( $gdpsy_example ) ); ?> ↗</a><?php endforeach; ?></nav><button class="gz-primary gz-button" type="button" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить механику для моего сайта.">Обсудить механику для моего сайта ↗</button></div>
      </div>
    </dialog>
    <?php foreach ( gdpsy_home_projects() as $project ) : $review = gdpsy_review( $project->ID ); if ( gdpsy_text( $review['text'] ?? '' ) === '' ) { continue; } ?>
<dialog class="gz-dialog" id="gz-review-<?php echo absint( $project->ID ); ?>" aria-labelledby="gz-review-title-<?php echo absint( $project->ID ); ?>">
<div class="gz-dialog-head"><h2 id="gz-review-title-<?php echo absint( $project->ID ); ?>">Отзыв <em>о проекте</em></h2><button class="gz-dialog-close gz-icon-action" type="button" data-close-dialog aria-label="Закрыть отзыв">×</button></div>
<blockquote class="gz-review-quote"><?php echo wpautop( esc_html( gdpsy_text( $review['text'] ) ) ); ?></blockquote>
<div class="gz-review-author"><?php $photo = $review['photo'] ?? 0; if ( is_numeric( $photo ) && absint( $photo ) ) { echo wp_get_attachment_image( absint( $photo ), 'thumbnail', false, array( 'class' => 'gz-review-photo', 'alt' => '' ) ); } ?>
<div><p class="gz-review-name"><?php echo esc_html( gdpsy_text( $review['name'] ?? '' ) ); ?></p><p class="gz-review-role"><?php echo esc_html( gdpsy_text( $review['role'] ?? '' ) ); ?></p></div></div></dialog>
<?php endforeach; ?>

