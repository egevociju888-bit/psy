<?php
/* Template Name: Контакты */
get_header(); ?>
<main class="gz-page-main gz-page-main--shell gz-contacts-page" id="gz-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php if ( post_password_required() ) : ?>
			<div class="gz-inner"><?php get_template_part( 'template-parts/page-content' ); ?></div>
		<?php else : ?>
			<div class="gz-inner"><div class="gz-breadcrumbs gz-page-breadcrumbs">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_page_breadcrumbs( get_the_ID() ) ) ); ?>
			</div></div>
			<?php get_template_part( 'template-parts/contact', null, array( 'light' => true, 'page' => true ) ); ?>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
