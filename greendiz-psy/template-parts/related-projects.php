<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$items = gdpsy_related_projects( absint( $args['post_id'] ?? get_queried_object_id() ) );
if ( ! $items ) {
	return;
}
?>
<section class="gz-articles gz-articles-cards gz-inner gz-related-projects" id="gz-articles" data-item-label="Сайт" aria-labelledby="gz-articles-title">
	<div class="gz-screen-meta gz-breadcrumbs gz-articles-meta">
		<nav aria-label="Хлебные крошки"><ol><li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a></li><li><a href="<?php echo esc_url( add_query_arg( 'case-type', 'project', gdpsy_archive_url() ) ); ?>">Проекты</a></li></ol></nav>
		<span class="gz-screen-caption">ПРОЕКТЫ</span>
	</div>
	<div class="gz-article-intro">
		<h2 class="gz-screen-title" id="gz-articles-title"><span class="gz-title-main">Другие</span><em class="gz-title-accent">сайты</em></h2>
		<a class="gz-inline-link gz-button gz-button--white" href="<?php echo esc_url( gdpsy_archive_url() ); ?>">Все сайты ↗</a>
	</div>
	<div class="gz-articles-carousel">
		<div class="gz-articles-viewport" id="gz-articles-viewport" tabindex="0" role="region" aria-label="Другие сайты: горизонтальная лента">
			<div class="gz-articles-track">
				<?php foreach ( $items as $item ) : get_template_part( 'template-parts/related-card', null, array( 'item' => $item ) ); endforeach; ?>
			</div>
		</div>
		<div class="gz-articles-controls" hidden>
			<div class="gz-articles-progress" aria-hidden="true"><span class="gz-articles-progress-thumb"></span></div>
			<div class="gz-articles-arrows">
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-prev aria-label="Предыдущий сайт" aria-controls="gz-articles-viewport" disabled><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button>
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-next aria-label="Следующий сайт" aria-controls="gz-articles-viewport"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7"/></svg></button>
			</div>
		</div>
		<p class="gz-sr-only" id="gz-articles-status" aria-live="polite" aria-atomic="true"></p>
	</div>
</section>
