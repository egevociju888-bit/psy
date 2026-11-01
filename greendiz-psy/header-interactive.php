<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$gdpsy_document = gdpsy_interactive_document( get_queried_object_id() );
$gdpsy_styles = gdpsy_interactive_theme_css();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-serif="lora">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<?php echo $gdpsy_document['head']; ?>
	<link rel="stylesheet" href="<?php echo esc_url( get_theme_file_uri( 'css/concept-stage.css' ) ); ?>?ver=<?php echo esc_attr( gdpsy_asset_version( 'css/concept-stage.css' ) ); ?>">
	<style data-gdpsy-fonts><?php echo $gdpsy_styles['fonts']; ?></style>
	<style>
		body { margin: 0; }
		#gdd-host { display: block; }
		[data-gdpsy-interactive-demo] { display: block; min-width: 0; width: 100%; }
		html.gz-menu-open, html.gz-contact-modal-open { overflow: hidden; }
		html.gdd-mobile [data-gdpsy-interactive-demo] > :not(#gdd-stage):not(script):not(style):not(link):not(template):not(noscript) { display: none !important; }
	</style>
</head>
<body<?php echo $gdpsy_document['body_attributes']; ?>>
<?php wp_body_open(); ?>
<div id="gdd-host">
<template shadowrootmode="open">
<style><?php echo $gdpsy_styles['css']; ?></style>
<style>:host { display: block; } .gz-interactive-preview-frame slot { display: block; }</style>
<div <?php body_class(); ?>>
<div class="gz-page gz-standard-page gz-interactive-preview-shell" id="greendiz-design-study">
	<?php get_template_part( 'template-parts/site-header', null, array( 'dark' => true ) ); ?>

