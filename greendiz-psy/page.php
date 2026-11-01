<?php get_header(); ?>
<main class="gz-page-main gz-page-main--shell gz-inner" id="gz-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php get_template_part( 'template-parts/page-content' ); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
