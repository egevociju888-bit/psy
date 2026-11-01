<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_current = get_the_ID();
$gdpsy_pages = array_values( array_filter( gdpsy_possibility_pages(), static function ( $page ) use ( $gdpsy_current ) {
	return $page->ID !== $gdpsy_current && gdpsy_possibility_key( $page->ID ) === 'blog';
} ) );
if ( ! $gdpsy_pages ) {
	return;
}
$gdpsy_overview = gdpsy_page_url( 'possibilities' );
?>
<section class="gz-articles gz-inner gz-related-possibilities" id="gz-articles" data-item-label="Возможность" aria-labelledby="gz-articles-title">
	<div class="gz-screen-meta"><span class="gz-screen-caption">Возможности сайта</span></div>
	<div class="gz-article-intro">
		<h2 class="gz-screen-title" id="gz-articles-title"><span class="gz-title-main">Другие</span><em class="gz-title-accent">возможности</em></h2>
		<?php if ( $gdpsy_overview !== '' ) : ?><a class="gz-inline-link gz-button gz-button--white" href="<?php echo esc_url( $gdpsy_overview ); ?>">Все возможности</a><?php endif; ?>
	</div>
	<div class="gz-articles-carousel">
		<div class="gz-articles-viewport" id="gz-articles-viewport" tabindex="0" role="region" aria-label="Другие возможности сайта: горизонтальная лента">
			<div class="gz-articles-track">
				<?php foreach ( $gdpsy_pages as $gdpsy_page ) : $gdpsy_summary = gdpsy_possibility_summary( $gdpsy_page->ID ); ?>
				<a class="gz-article" href="<?php echo esc_url( get_permalink( $gdpsy_page ) ); ?>">
					<div class="gz-eyebrow">Возможность сайта</div>
					<h3><?php echo esc_html( get_the_title( $gdpsy_page ) ); ?></h3>
					<?php if ( $gdpsy_summary !== '' ) : ?><p><?php echo esc_html( wp_trim_words( $gdpsy_summary, 24 ) ); ?></p><?php endif; ?>
					<span>Подробнее</span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="gz-articles-controls" hidden>
			<div class="gz-articles-progress" aria-hidden="true"><span class="gz-articles-progress-thumb"></span></div>
			<div class="gz-articles-arrows">
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-prev aria-label="Предыдущая возможность" aria-controls="gz-articles-viewport" disabled><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button>
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-next aria-label="Следующая возможность" aria-controls="gz-articles-viewport"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7"/></svg></button>
			</div>
		</div>
		<p class="gz-sr-only" id="gz-articles-status" aria-live="polite" aria-atomic="true"></p>
	</div>
</section>

