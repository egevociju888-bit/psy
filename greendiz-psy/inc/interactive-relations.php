<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_interactive_relation_ids( $value ) {
	$ids = array();
	foreach ( is_array( $value ) ? $value : array() as $item ) {
		$item = $item instanceof WP_Post ? $item->ID : $item;
		if ( ( is_int( $item ) || is_string( $item ) ) && preg_match( '/^[1-9][0-9]*$/D', (string) $item ) && (int) $item > 0 ) {
			$ids[] = (int) $item;
		}
	}
	return array_values( array_unique( $ids ) );
}

function gdpsy_interactive_relation_name( $kind ) {
	return is_string( $kind ) && in_array( $kind, array( 'consept', 'project' ), true ) ? 'concepts' : '';
}

function gdpsy_interactive_relation_metas() {
	return array( 'interactive_details_concepts', 'interactive_details_projects' );
}

function gdpsy_interactive_related_ids( $interactive_id ) {
	$ids = array();
	foreach ( gdpsy_interactive_relation_metas() as $meta ) {
		$ids = array_merge( $ids, gdpsy_interactive_relation_ids( get_post_meta( $interactive_id, $meta, true ) ) );
	}
	return array_values( array_unique( $ids ) );
}

function gdpsy_interactive_portfolio( $post_id, $kind ) {
	$items = array();
	if ( gdpsy_interactive_relation_name( $kind ) === '' ) {
		return $items;
	}
	foreach ( gdpsy_interactive_related_ids( $post_id ) as $id ) {
		$post = get_post( $id );
		if ( $post && $post->post_type === 'portfolio' && $post->post_status === 'publish' && $post->post_password === '' && get_post_meta( $id, 'case-type', true ) === $kind ) {
			$items[ $id ] = $post;
		}
	}
	return $items;
}

function gdpsy_portfolio_interactives( $post_id, $kind, $public = false ) {
	$post = is_numeric( $post_id ) ? get_post( (int) $post_id ) : null;
	if ( gdpsy_interactive_relation_name( $kind ) === '' || ! $post || $post->post_type !== 'portfolio' || get_post_meta( $post->ID, 'case-type', true ) !== $kind ) {
		return array();
	}
	$meta_query = array( 'relation' => 'OR' );
	foreach ( gdpsy_interactive_relation_metas() as $meta ) {
		$meta_query[] = array( 'key' => $meta, 'value' => '"' . $post->ID . '"', 'compare' => 'LIKE' );
		$meta_query[] = array( 'key' => $meta, 'value' => 'i:' . $post->ID . ';', 'compare' => 'LIKE' );
	}
	$args = array(
		'post_type' => 'interactive',
		'post_status' => $public ? 'publish' : 'any',
		'posts_per_page' => -1,
		'no_found_rows' => true,
		'orderby' => array( 'title' => 'ASC', 'ID' => 'ASC' ),
		'meta_query' => $meta_query,
	);
	if ( $public ) {
		$args['has_password'] = false;
	}
	$ids = array();
	foreach ( get_posts( $args ) as $interactive ) {
		if ( in_array( (int) $post->ID, gdpsy_interactive_related_ids( $interactive->ID ), true ) ) {
			$ids[] = (int) $interactive->ID;
		}
	}
	return $ids;
}

function gdpsy_portfolio_interactive_field( $field ) {
	return in_array( $field['key'] ?? '', array( 'field_gdpsy_portfolio_concept_interactives', 'field_gdpsy_portfolio_project_interactives' ), true )
		|| ( $field['name'] ?? '' ) === 'portfolio_interactives';
}

function gdpsy_portfolio_interactive_kind( $post_id ) {
	$kind = is_numeric( $post_id ) ? get_post_meta( (int) $post_id, 'case-type', true ) : '';
	return gdpsy_interactive_relation_name( $kind ) !== '' ? $kind : '';
}

function gdpsy_portfolio_interactive_load( $value, $post_id, $field ) {
	if ( ! gdpsy_portfolio_interactive_field( $field ) ) {
		return $value;
	}
	$ids = gdpsy_portfolio_interactives( $post_id, gdpsy_portfolio_interactive_kind( $post_id ) );
	return array_values( array_filter( $ids, function ( $id ) { return current_user_can( 'edit_post', $id ); } ) );
}

function gdpsy_portfolio_interactive_update( $value, $post_id, $field ) {
	if ( ! gdpsy_portfolio_interactive_field( $field ) ) {
		return $value;
	}
	$kind = gdpsy_portfolio_interactive_kind( $post_id );
	$name = gdpsy_interactive_relation_name( $kind );
	$post = is_numeric( $post_id ) ? get_post( (int) $post_id ) : null;
	if ( $name === '' || ! $post || $post->post_type !== 'portfolio' || get_post_meta( $post->ID, 'case-type', true ) !== $kind || ! current_user_can( 'edit_post', $post->ID ) ) {
		return array();
	}
	$selected = gdpsy_interactive_relation_ids( $value );
	$existing = gdpsy_portfolio_interactives( $post->ID, $kind );
	$meta_name = 'interactive_details_concepts';
	foreach ( array_unique( array_merge( $existing, $selected ) ) as $id ) {
		$interactive = get_post( $id );
		if ( ! $interactive || $interactive->post_type !== 'interactive' || ! current_user_can( 'edit_post', $id ) ) {
			continue;
		}
		$ids = gdpsy_interactive_relation_ids( get_post_meta( $id, $meta_name, true ) );
		$legacy = gdpsy_interactive_relation_ids( get_post_meta( $id, 'interactive_details_projects', true ) );
		$updated = array_values( array_unique( array_merge( $ids, $legacy ) ) );
		if ( in_array( $id, $selected, true ) ) {
			if ( ! in_array( (int) $post->ID, $updated, true ) ) {
				$updated[] = (int) $post->ID;
			}
		} else {
			$updated = array_values( array_diff( $updated, array( (int) $post->ID ) ) );
		}
		if ( $updated === $ids && ! in_array( (int) $post->ID, $legacy, true ) ) {
			continue;
		}
		update_post_meta( $id, $meta_name, array_map( 'strval', $updated ) );
		update_post_meta( $id, '_' . $meta_name, 'field_gdpsy_interactive_concepts' );
		if ( $legacy ) {
			delete_post_meta( $id, 'interactive_details_projects' );
			delete_post_meta( $id, '_interactive_details_projects' );
		}
		if ( function_exists( 'acf_flush_value_cache' ) ) {
			acf_flush_value_cache( $id, $meta_name );
			acf_flush_value_cache( $id, 'interactive_details' );
		}
	}
	return array();
}

function gdpsy_portfolio_interactive_query( $args, $field ) {
	if ( ! gdpsy_portfolio_interactive_field( $field ) ) {
		return $args;
	}
	$args['post_type'] = array( 'interactive' );
	$args['post_status'] = 'publish';
	$args['has_password'] = false;
	return $args;
}

add_filter( 'acf/load_value', 'gdpsy_portfolio_interactive_load', 10, 3 );
add_filter( 'acf/update_value', 'gdpsy_portfolio_interactive_update', 10, 3 );
add_filter( 'acf/fields/relationship/query', 'gdpsy_portfolio_interactive_query', 10, 2 );
