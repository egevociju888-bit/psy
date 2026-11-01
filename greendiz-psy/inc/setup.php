<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_post_type_support( 'page', 'excerpt' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	register_nav_menus( array(
		'header'        => 'Меню шапки',
		'primary_left'  => 'Основное меню — левая колонка',
		'primary_right' => 'Основное меню — правая колонка',
		'footer'        => 'Меню футера',
		'footer_legal'  => 'Юридическая информация',
	) );
}
add_action( 'after_setup_theme', 'gdpsy_setup' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );


function gdpsy_favicon() {
	if ( has_site_icon() ) {
		return;
	}
	$version = rawurlencode( gdpsy_asset_version( 'img/favicon.svg' ) );
	echo '<link rel="icon" href="' . esc_url( get_theme_file_uri( 'img/favicon.ico' ) ) . '?ver=' . $version . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( get_theme_file_uri( 'img/favicon.svg' ) ) . '?ver=' . $version . '" type="image/svg+xml">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( get_theme_file_uri( 'img/apple-touch-icon.png' ) ) . '?ver=' . $version . '">' . "\n";
}
add_action( 'wp_head', 'gdpsy_favicon', 5 );
