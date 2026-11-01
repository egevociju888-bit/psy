<?php
get_header();
?>
<main id="gz-main">
<?php
foreach ( array( 'hero', 'formats', 'concepts', 'extras', 'benefit', 'about', 'process', 'after', 'faq' ) as $gdpsy_part ) {
	get_template_part( 'template-parts/home/' . $gdpsy_part );
}
get_template_part( 'template-parts/contact' );
?>
</main>
<?php get_footer(); ?>
