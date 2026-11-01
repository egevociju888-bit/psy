<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$gdpsy_related = new WP_Query( array(
	'post_type' => array( 'interactive' ), 'post_status' => 'publish', 'has_password' => false,
	'post__not_in' => array( get_the_ID() ), 'posts_per_page' => 6, 'no_found_rows' => true,
	'orderby' => array( 'title' => 'ASC', 'ID' => 'ASC' ),
) );
if ( ! $gdpsy_related->have_posts() ) { return; }
?>
<section class="gz-articles gz-interactive-related gz-inner" id="gz-articles" data-item-label="Интерактив" aria-labelledby="gz-interactive-related-title">
	<div class="gz-article-intro">
		<h2 class="gz-screen-title" id="gz-interactive-related-title"><span class="gz-title-main">Другие</span><em class="gz-title-accent">интерактивы</em></h2>
		<a class="gz-inline-link gz-button gz-button--outline" href="<?php echo esc_url( gdpsy_interactive_archive_url() ); ?>">Все интерактивы</a>
	</div>
	<div class="gz-articles-carousel">
		<div class="gz-articles-viewport" id="gz-articles-viewport" tabindex="0" role="region" aria-label="Другие интерактивы: горизонтальная лента">
			<div class="gz-articles-track">
				<?php while ( $gdpsy_related->have_posts() ) : $gdpsy_related->the_post(); get_template_part( 'template-parts/interactive-card', null, array( 'carousel' => true ) ); endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
		<div class="gz-articles-controls" hidden>
			<div class="gz-articles-progress" aria-hidden="true"><span class="gz-articles-progress-thumb"></span></div>
			<div class="gz-articles-arrows">
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-prev aria-label="Предыдущий интерактив" aria-controls="gz-articles-viewport" disabled><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button>
				<button class="gz-articles-arrow gz-icon-action" type="button" data-articles-next aria-label="Следующий интерактив" aria-controls="gz-articles-viewport"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7"/></svg></button>
			</div>
		</div>
		<p class="gz-sr-only" id="gz-articles-status" aria-live="polite" aria-atomic="true"></p>
	</div>
</section>
