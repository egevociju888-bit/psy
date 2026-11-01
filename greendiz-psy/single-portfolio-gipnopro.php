<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="gz-project-main" id="gz-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'gz-gipnopro-article' ); ?>>
			<?php if ( post_password_required() || (int) get_query_var( 'page' ) > 1 ) : ?>
				<div class="gz-inner gz-page-main">
					<?php get_template_part( 'template-parts/page-header', null, array( 'title' => 'Gipnopro.ru' ) ); ?>
					<div class="gz-entry"><?php the_content(); ?></div>
					<?php wp_link_pages(); ?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/project-gipnopro' ); ?>
				<?php get_template_part( 'template-parts/portfolio-interactives' ); ?>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
