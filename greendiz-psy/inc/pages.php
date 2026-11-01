<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_is_price_page() {
	return is_page() && get_page_template() === get_theme_file_path( 'page-price.php' );
}

function gdpsy_page_breadcrumb_title( $post_id ) {
	return get_page_template_slug( $post_id ) === 'page-possibilities.php' || get_post_field( 'post_name', $post_id, 'raw' ) === 'possibilities' ? 'Возможности' : get_the_title( $post_id );
}

function gdpsy_page_breadcrumbs( $post_id ) {
	$items = array( array( 'title' => 'Главная', 'url' => home_url( '/' ) ) );
	$front_id = (int) get_option( 'page_on_front' );
	foreach ( array_reverse( get_post_ancestors( $post_id ) ) as $parent_id ) {
		if ( $parent_id === $front_id || get_post_status( $parent_id ) !== 'publish' ) {
			continue;
		}
		$items[] = array( 'title' => gdpsy_page_breadcrumb_title( $parent_id ), 'url' => get_permalink( $parent_id ) );
	}
	$items[] = array( 'title' => gdpsy_page_breadcrumb_title( $post_id ), 'url' => '' );
	return $items;
}

