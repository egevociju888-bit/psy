<!DOCTYPE html>
<html <?php language_attributes(); ?> data-serif="lora">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="gz-page <?php echo gdpsy_is_project_case() ? ( gdpsy_is_vdoh_case() ? 'gz-standard-page gz-vdoh-page' : 'gz-standard-page gz-gipnopro-page' ) : ( is_front_page() ? 'gz-home-page' : ( is_post_type_archive( 'portfolio' ) ? 'gz-portfolio-page' : 'gz-standard-page' ) ); ?>" id="greendiz-design-study">
	<?php if ( gdpsy_is_project_case() ) : ?><div class="gz-project-dark"><?php endif; ?>
	<?php get_template_part( 'template-parts/site-header', null, array( 'dark' => gdpsy_is_project_case() || is_singular( 'interactive' ) ) ); ?>
	<?php if ( gdpsy_is_project_case() ) : ?></div><?php endif; ?>


