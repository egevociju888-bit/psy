<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_is_vdoh_case() {
	return is_singular( 'portfolio' ) && get_queried_object()->post_name === 'vdoh';
}

function gdpsy_is_gipnopro_case() {
	return is_singular( 'portfolio' ) && get_queried_object()->post_name === 'gipnopro';
}

function gdpsy_is_project_case() {
	return gdpsy_is_vdoh_case() || gdpsy_is_gipnopro_case();
}

function gdpsy_project_contact_message() {
	$example = gdpsy_is_vdoh_case() ? '«Вдоха»' : 'GipnoPro';
	return 'Здравствуйте, Анастасия! Хочу обсудить сайт или интерактив по примеру ' . $example . ' для своей практики.';
}

function gdpsy_vdoh_media( $name ) {
	return get_theme_file_uri( 'media/vdoh/' . $name );
}

function gdpsy_related_projects( $post_id, $types = array( 'project', 'consept' ) ) {
	$items = array();
	$exclude = array( absint( $post_id ) );
	$base = array(
		'post_type'           => 'portfolio',
		'post_status'         => 'publish',
		'has_password'        => false,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'orderby'            => array( 'date' => 'DESC', 'ID' => 'DESC' ),
		'meta_key'           => 'case-type',
	);
	foreach ( $types as $type ) {
		$remaining = 9 - count( $items );
		if ( $remaining <= 0 ) {
			break;
		}
		$query = new WP_Query( array_merge( $base, array(
			'posts_per_page' => $remaining,
			'post__not_in'   => $exclude,
			'meta_value'     => $type,
		) ) );
		$items = array_merge( $items, $query->posts );
		$exclude = array_merge( $exclude, wp_list_pluck( $query->posts, 'ID' ) );
	}
	return $items;
}

