<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_menu( $location, $instance, $depth = 0 ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	wp_nav_menu( array(
		'theme_location' => $location,
		'container'      => false,
		'fallback_cb'    => false,
		'menu_id'        => 'gz-menu-' . sanitize_html_class( $instance ),
		'menu_class'     => 'gz-wp-menu',
		'depth'          => $depth,
		'gdpsy_instance' => sanitize_html_class( $instance ),
	) );
}

function gdpsy_menu_item_id( $id, $item, $args ) {
	return ! empty( $args->gdpsy_instance ) ? 'gz-' . sanitize_html_class( $args->gdpsy_instance ) . '-item-' . absint( $item->ID ) : $id;
}
add_filter( 'nav_menu_item_id', 'gdpsy_menu_item_id', 10, 3 );

function gdpsy_menu_skip_hero_start( $items, $args ) {
	if ( 'hero' !== ( $args->gdpsy_instance ?? '' ) ) {
		return $items;
	}
	return array_values( array_filter( $items, static fn( $item ) => ! str_ends_with( (string) $item->url, '#gz-first' ) ) );
}
add_filter( 'wp_nav_menu_objects', 'gdpsy_menu_skip_hero_start', 10, 2 );

function gdpsy_menu_link_attributes( $attributes, $item, $args ) {
	$href = $attributes['href'] ?? '';
	if ( ( $args->theme_location ?? '' ) === 'primary_right' && ! is_front_page() && is_string( $href ) && strlen( $href ) > 1 && $href[0] === '#' && $href !== '#gz-contact' ) {
		$attributes['href'] = home_url( '/' ) . $href;
	}
	if ( ! empty( $args->gdpsy_instance ) && ( $attributes['target'] ?? '' ) === '_blank' ) {
		$rel = preg_split( '/\s+/', trim( $attributes['rel'] ?? '' ) );
		$attributes['rel'] = implode( ' ', array_unique( array_filter( array_merge( $rel, array( 'noopener', 'noreferrer' ) ) ) ) );
	}
	return $attributes;
}
add_filter( 'nav_menu_link_attributes', 'gdpsy_menu_link_attributes', 10, 3 );

function gdpsy_rank_math_breadcrumbs_enabled( $enabled ) {
	return is_home() || is_category() || is_post_type_archive( 'portfolio' ) || is_singular( 'post' ) ? false : $enabled;
}
add_filter( 'rank_math/json_ld/breadcrumbs_enabled', 'gdpsy_rank_math_breadcrumbs_enabled' );

function gdpsy_theme_breadcrumb_page() {
	if ( gdpsy_is_project_case() || gdpsy_is_interactive() ) {
		return true;
	}
	if ( ! is_page() || is_front_page() ) {
		return false;
	}
	$template = get_page_template();
	foreach ( array( 'page.php', 'page-contacts.php', 'page-faq.php' ) as $file ) {
		if ( $template === get_theme_file_path( $file ) ) {
			return true;
		}
	}
	return gdpsy_is_possibilities_page() || gdpsy_is_price_page();
}

function gdpsy_strip_schema_breadcrumbs( $data ) {
	if ( ! gdpsy_theme_breadcrumb_page() ) {
		return $data;
	}
	foreach ( $data as $key => $entity ) {
		if ( in_array( 'BreadcrumbList', (array) ( $entity['@type'] ?? array() ), true ) ) {
			unset( $data[ $key ] );
		} elseif ( isset( $entity['breadcrumb'] ) ) {
			unset( $data[ $key ]['breadcrumb'] );
		}
	}
	return $data;
}
add_filter( 'rank_math/json_ld', 'gdpsy_strip_schema_breadcrumbs', 99 );

