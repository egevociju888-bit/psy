<?php get_header(); ?>
<main class="gz-page-main gz-inner" id="gz-main">
	<?php if ( is_singular() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/page-content' ); ?>
		<?php endwhile; ?>
	<?php else : ?>
		<?php
		if ( is_home() ) {
			$gdpsy_posts_page = (int) get_option( 'page_for_posts' );
			$gdpsy_title = $gdpsy_posts_page ? get_the_title( $gdpsy_posts_page ) : 'Статьи';
		} elseif ( is_search() ) {
			$gdpsy_title = 'Результаты поиска';
		} elseif ( is_404() ) {
			$gdpsy_title = 'Страница не найдена';
		} else {
			$gdpsy_title = wp_strip_all_tags( get_the_archive_title() );
		}
		?>
		<header class="gz-page-heading"><h1><?php echo esc_html( $gdpsy_title ); ?></h1></header>
		<?php if ( have_posts() ) : ?>
			<div class="gz-post-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<article <?php post_class(); ?>>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="gz-entry"><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p>Материалы не найдены.</p>
		<?php endif; ?>
	<?php endif; ?>
</main>
<?php get_footer(); ?>
