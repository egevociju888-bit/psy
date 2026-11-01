<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
$concepts = array_values( array_filter( gdpsy_selected( 'concepts' ), function ( $post ) { return gdpsy_field( 'case-type', $post->ID ) === 'consept'; } ) );
$gdpsy_rows = static function ( $n ) { return max( 1, (int) ceil( $n / 2 ) ); };
$gdpsy_rows_closed = $gdpsy_rows( min( count( $concepts ), 5 ) );
$gdpsy_rows_open = $gdpsy_rows( count( $concepts ) );
$slots = array( 'gz-concept-teen', 'gz-concept-family', 'gz-concept-academic', 'gz-concept-haven', 'gz-concept-clarity', 'gz-concept-extra-1', 'gz-concept-extra-2', 'gz-concept-extra-3' );
?>
<section class="gz-gallery gz-inner" id="gz-concepts" aria-labelledby="gz-concepts-title">
<div class="gz-screen-meta"><div class="gz-index">Разные настроения</div><div class="gz-screen-caption">Разные подходы</div></div>
<div class="gz-concept-stage"><div class="gz-concept-grid<?php echo count( $concepts ) > 5 ? ' is-expanded' : ''; ?>" id="gz-concepts-grid" style="--gz-concept-rows: <?php echo absint( $gdpsy_rows_closed ); ?>; --gz-concept-rows-open: <?php echo absint( $gdpsy_rows_open ); ?>;">
<div class="gz-concept-side">
<div class="gz-concept-intro">
              <h2 id="gz-concepts-title" class="gz-screen-title">
                <span class="gz-title-main">Примеры сайтов:</span>
                <span class="gz-title-accent">готовые концепты для разных практик</span>
              </h2>
              <p>Концепт — это готовый дизайн сайта, который я придумала заранее. Выберите близкий вам по настроению: я заменю тексты и фото на ваши и при необходимости добавлю до трёх блоков в том же стиле.</p>
            </div>
<aside class="gz-concept-owner" aria-labelledby="gz-owner-title">
              <h3 class="gz-owner-heading" id="gz-owner-title">
                <span>Один концепт —</span>
                <em>один владелец.</em>
              </h3>
              <p>После покупки снимаю концепт с продажи, другого сайта в таком дизайне не будет.</p>
              <?php if ( count( $concepts ) > 5 ) : ?>
<button class="gz-concepts-action gz-text-action" id="gz-concepts-more" type="button" aria-expanded="false" aria-controls="gz-concepts-grid" data-archive="<?php echo esc_url( add_query_arg( 'case-type', 'consept', gdpsy_archive_url() ) ); ?>" hidden>Показать ещё концепты</button>
<?php else : ?>
<a class="gz-concepts-action gz-text-action" href="<?php echo esc_url( add_query_arg( 'case-type', 'consept', gdpsy_archive_url() ) ); ?>">Все концепты</a>
<?php endif; ?>
            </aside>
</div>

<?php foreach ( $concepts as $i => $concept ) : $info = gdpsy_portfolio_info( $concept->ID ); ?>
<article class="gz-work <?php echo esc_attr( $slots[ $i ] ?? 'gz-concept-extra-auto' ); ?>"<?php echo $i >= 5 ? ' data-extra-concept' : ''; ?>>
<a class="gz-archive-card" href="<?php echo esc_url( get_permalink( $concept ) ); ?>" aria-labelledby="gz-concept-card-<?php echo absint( $concept->ID ); ?>">
<div class="gz-archive-preview<?php echo has_post_thumbnail( $concept ) ? '' : ' gz-archive-preview--pending'; ?>">
<?php if ( has_post_thumbnail( $concept ) ) : echo get_the_post_thumbnail( $concept, 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); else : ?><span>Обложка готовится</span><?php endif; ?>
</div><div class="gz-work-caption"><div class="gz-archive-card-meta"><span>Концепт</span><?php $gdpsy_reserved = gdpsy_concept_reserved_text( $concept->ID ); if ( $gdpsy_reserved !== '' ) : ?><span class="gz-reserved"><?php echo esc_html( $gdpsy_reserved ); ?></span><?php endif; ?></div>
<h3 id="gz-concept-card-<?php echo absint( $concept->ID ); ?>"><?php echo esc_html( get_the_title( $concept ) ); ?></h3>
<?php $gdpsy_specialty = implode( ' · ', gdpsy_concept_terms( $concept->ID )['specialty'] ); if ( $gdpsy_specialty !== '' ) : ?><p class="gz-work-kicker"><?php echo esc_html( $gdpsy_specialty ); ?></p><?php endif; ?>
<?php if ( gdpsy_text( $info['opisanie'] ?? '' ) !== '' ) : ?><p class="gz-work-detail"><?php echo esc_html( gdpsy_text( $info['opisanie'] ) ); ?></p><?php endif; ?>
<?php $gdpsy_cost = gdpsy_concept_cost( $concept->ID ); ?>
<div class="gz-archive-card-footer"><div class="gz-archive-card-more"><?php if ( $gdpsy_cost !== '' ) : ?><span class="gz-archive-card-price"><?php echo esc_html( $gdpsy_cost ); ?></span><?php endif; ?><span aria-hidden="true">Подробнее</span></div></div>
</div></a></article>
<?php endforeach; ?>

</div></div><p class="gz-sr-only" id="gz-concepts-status" role="status" aria-live="polite" aria-atomic="true"></p>
<?php foreach ( gdpsy_home_projects() as $project ) : $info = gdpsy_portfolio_info( $project->ID ); $review = gdpsy_review( $project->ID ); $gdpsy_is_gipnopro = $project->post_name === 'gipnopro'; ?>
<div class="gz-case"><div class="gz-case-intro">
<?php if ( $gdpsy_is_gipnopro ) : ?>
<div class="gz-case-label">РЕАЛИЗОВАННЫЙ ПРОЕКТ GIPNOPRO.RU</div>
<h3 class="gz-case-title"><span>Сайт психолога</span><em>Ольги Филатовой</em></h3>
<p class="gz-case-specialty">клинический психолог, ЭОТ-терапевт, гипнолог · Москва и онлайн</p>
<?php else : ?>
<div class="gz-case-label">РЕАЛИЗОВАННЫЙ ПРОЕКТ</div>
<h3 class="gz-case-title"><em><?php echo esc_html( get_the_title( $project ) ); ?></em></h3>
<?php if ( gdpsy_text( $info['speczializacziya'] ?? '' ) !== '' ) : ?><p class="gz-case-specialty"><?php echo esc_html( gdpsy_text( $info['speczializacziya'] ) ); ?></p><?php endif; ?>
<?php endif; ?>
</div>
<div class="gz-case-story">
<?php if ( $gdpsy_is_gipnopro ) : ?>
<p>Редизайн: новый стиль и новый многостраничный сайт, разработанный с нуля под ключ. Спокойная морская палитра, удобная навигация по услугам и кабинетам, блог для дальнейшего SEO-продвижения.</p>
<?php elseif ( gdpsy_text( $info['opisanie'] ?? '' ) !== '' ) : ?><p><?php echo esc_html( gdpsy_text( $info['opisanie'] ) ); ?></p><?php endif; ?>
<div class="gz-case-links"><?php if ( gdpsy_text( $review['text'] ?? '' ) !== '' ) : ?><button class="gz-inline-link gz-case-review gz-text-action" type="button" data-open-dialog="gz-review-<?php echo absint( $project->ID ); ?>" aria-haspopup="dialog" aria-controls="gz-review-<?php echo absint( $project->ID ); ?>">Посмотреть отзыв</button><?php endif; ?>
<a class="gz-inline-link gz-text-action" href="<?php echo esc_url( get_permalink( $project ) ); ?>">Посмотреть историю проекта</a></div></div></div>
<?php endforeach; ?>
</section>
