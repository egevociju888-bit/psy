<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_is_blog_archive() {
	return is_home() || is_category();
}

function gdpsy_blog_archive_content() {
	$title = 'Блог';
	$intro = '';
	$seo = '';
	if ( is_category() ) {
		$term = get_queried_object();
		$title = single_cat_title( '', false );
		$intro = term_description( $term->term_id, 'category' );
		$seo = gdpsy_field( 'gdpsy_blog_seo', 'category_' . $term->term_id );
	} else {
		$page_id = (int) get_option( 'page_for_posts' );
		$page = $page_id ? get_post( $page_id ) : null;
		if ( $page instanceof WP_Post ) {
			$title = get_the_title( $page );
			if ( $page->post_status === 'publish' && ! post_password_required( $page ) ) {
				$intro = wpautop( $page->post_excerpt );
				$seo = apply_filters( 'the_content', $page->post_content );
			}
		}
	}
	return array(
		'title' => gdpsy_text( $title ),
		'intro' => is_string( $intro ) ? wp_kses_post( $intro ) : '',
		'seo'   => is_string( $seo ) ? wp_kses_post( $seo ) : '',
	);
}

function gdpsy_blog_breadcrumbs() {
	$items = array( array( 'title' => 'Главная', 'url' => home_url( '/' ) ) );
	if ( ! is_category() ) {
		$items[] = array( 'title' => 'Полезные статьи', 'url' => '' );
		return $items;
	}
	$items[] = array( 'title' => 'Полезные статьи', 'url' => gdpsy_blog_url() );
	$term = get_queried_object();
	foreach ( array_reverse( get_ancestors( $term->term_id, 'category', 'taxonomy' ) ) as $id ) {
		$parent = get_term( $id, 'category' );
		if ( ! $parent instanceof WP_Term ) {
			continue;
		}
		$url = get_term_link( $parent );
		if ( ! is_wp_error( $url ) ) {
			$items[] = array( 'title' => $parent->name, 'url' => $url );
		}
	}
	$items[] = array( 'title' => $term->name, 'url' => '' );
	return $items;
}
