<?php
/* Template Name: Стоимость — калькулятор */
get_header();
?>
<main class="gz-page-main gz-page-main--shell gz-price-page" id="gz-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'gz-page-article' ); ?>>
			<div class="gz-inner">
				<div class="gz-breadcrumbs gz-page-breadcrumbs">
					<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_page_breadcrumbs( get_the_ID() ) ) ); ?>
				</div>
				<header class="gz-page-heading gz-price-heading">
					<h1 id="gz-price-title"><?php echo esc_html( get_the_title() ?: 'Стоимость' ); ?></h1>
					<?php if ( ! post_password_required() && (int) get_query_var( 'page' ) < 2 ) : ?>
						<div class="gz-page-intro">
							<p>Сначала — основа. Дальше — по задачам. Дополнительные возможности можно подключить сразу или позже, когда практика до них дорастёт.</p>
						</div>
					<?php endif; ?>
				</header>
				<div class="gz-entry"><?php the_content(); ?></div>
				<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
			</div>
			<?php if ( ! post_password_required() && (int) get_query_var( 'page' ) < 2 ) : ?>
				<?php get_template_part( 'template-parts/home/cost', null, array( 'hide_heading' => true ) ); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
