<article <?php post_class( 'gz-page-article' ); ?>>
	<?php get_template_part( 'template-parts/page-header' ); ?>
	<div class="gz-entry"><?php the_content(); ?></div>
	<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
	<?php if ( is_singular( 'portfolio' ) ) { get_template_part( 'template-parts/portfolio-interactives', null, array( 'compact' => true ) ); } ?>
</article>
