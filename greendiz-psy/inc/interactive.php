<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once __DIR__ . '/interactive-relations.php';

function gdpsy_is_interactive() {
	return is_post_type_archive( 'interactive' ) || is_singular( 'interactive' );
}

function gdpsy_interactive_archive_url() {
	return get_post_type_archive_link( 'interactive' ) ?: home_url( '/interactive/' );
}

function gdpsy_interactive_goals() {
	return array(
		'pause'   => 'Сделать паузу',
		'notice'  => 'Заметить состояние',
		'express' => 'Найти слова',
		'reflect' => 'Разобраться в ситуации',
	);
}

function gdpsy_interactive_filter_definitions() {
	return array(
		'goal' => array( 'taxonomy' => 'gdpsy_i_goal', 'label' => 'Задача', 'all' => 'Все задачи' ),
		'topic' => array( 'taxonomy' => 'gdpsy_i_topic', 'label' => 'Тема', 'all' => 'Все темы' ),
		'type' => array( 'taxonomy' => 'gdpsy_i_type', 'label' => 'Тип', 'all' => 'Все типы' ),
		'approach' => array( 'taxonomy' => 'gdpsy_i_approach', 'label' => 'Подход и техники', 'all' => 'Все подходы и техники' ),
	);
}

function gdpsy_interactive_filter_options() {
	static $options = null;
	if ( $options !== null ) {
		return $options;
	}
	global $wpdb;
	$legacy_goals = $wpdb->get_col( $wpdb->prepare( "SELECT DISTINCT gdpsy_m.meta_value FROM {$wpdb->postmeta} gdpsy_m INNER JOIN {$wpdb->posts} gdpsy_p ON gdpsy_p.ID = gdpsy_m.post_id WHERE gdpsy_m.meta_key = %s AND gdpsy_p.post_type = %s AND gdpsy_p.post_status = %s", 'interactive_details_goal', 'interactive', 'publish' ) );
	$options = array();
	foreach ( gdpsy_interactive_filter_definitions() as $key => $definition ) {
		$options[ $key ] = array();
		if ( ! taxonomy_exists( $definition['taxonomy'] ) ) {
			continue;
		}
		$terms = get_terms( array( 'taxonomy' => $definition['taxonomy'], 'hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC' ) );
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( (int) $term->count > 0 || ( $key === 'goal' && in_array( $term->slug, $legacy_goals, true ) ) ) {
					$options[ $key ][ $term->slug ] = $term->name;
				}
			}
		}
	}
	return $options;
}

function gdpsy_interactive_filters() {
	$filters = array();
	foreach ( gdpsy_interactive_filter_options() as $key => $options ) {
		$raw = isset( $_GET[ $key ] ) ? wp_unslash( $_GET[ $key ] ) : array();
		$raw = is_array( $raw ) ? $raw : array( $raw );
		$values = array();
		foreach ( array_slice( $raw, 0, 50 ) as $value ) {
			if ( is_string( $value ) && isset( $options[ $value ] ) && ! in_array( $value, $values, true ) ) {
				$values[] = $value;
			}
		}
		$filters[ $key ] = $values;
	}
	return $filters;
}

function gdpsy_interactive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'interactive' ) ) {
		return;
	}
	$query->set( 'posts_per_page', 12 );
	if ( gdpsy_interactive_is_selection() ) {
		$ids = gdpsy_interactive_saved_ids();
		$query->set( 'post__in', $ids ?: array( 0 ) );
	}
	$query->set( 'post_status', 'publish' );
	$query->set( 'has_password', false );
	$query->set( 'orderby', array( 'title' => 'ASC', 'ID' => 'ASC' ) );
	$query->set( '_gdpsy_interactive_catalog', true );
	$query->set( '_gdpsy_interactive_filters', gdpsy_interactive_filters() );
}
add_action( 'pre_get_posts', 'gdpsy_interactive_query' );

function gdpsy_interactive_tax_match_sql( $taxonomy, $slug ) {
	global $wpdb;
	return $wpdb->prepare( "EXISTS (SELECT 1 FROM {$wpdb->term_relationships} gdpsy_tr INNER JOIN {$wpdb->term_taxonomy} gdpsy_tt ON gdpsy_tt.term_taxonomy_id = gdpsy_tr.term_taxonomy_id INNER JOIN {$wpdb->terms} gdpsy_term ON gdpsy_term.term_id = gdpsy_tt.term_id WHERE gdpsy_tr.object_id = {$wpdb->posts}.ID AND gdpsy_tt.taxonomy = %s AND gdpsy_term.slug = %s)", $taxonomy, $slug );
}

function gdpsy_interactive_goal_match_sql( $slug ) {
	global $wpdb;
	$match = gdpsy_interactive_tax_match_sql( 'gdpsy_i_goal', $slug );
	$unassigned = $wpdb->prepare( "NOT EXISTS (SELECT 1 FROM {$wpdb->term_relationships} gdpsy_empty_tr INNER JOIN {$wpdb->term_taxonomy} gdpsy_empty_tt ON gdpsy_empty_tt.term_taxonomy_id = gdpsy_empty_tr.term_taxonomy_id WHERE gdpsy_empty_tr.object_id = {$wpdb->posts}.ID AND gdpsy_empty_tt.taxonomy = %s)", 'gdpsy_i_goal' );
	$legacy = $wpdb->prepare( "EXISTS (SELECT 1 FROM {$wpdb->postmeta} gdpsy_legacy WHERE gdpsy_legacy.post_id = {$wpdb->posts}.ID AND gdpsy_legacy.meta_key = %s AND gdpsy_legacy.meta_value = %s)", 'interactive_details_goal', $slug );
	return '(' . $match . ' OR (' . $unassigned . ' AND ' . $legacy . '))';
}

function gdpsy_interactive_priority_sql() {
	global $wpdb;
	return $wpdb->prepare( "COALESCE((SELECT CASE WHEN LENGTH(gdpsy_priority.meta_value) = 1 AND gdpsy_priority.meta_value IN ('0', '1', '2', '3') THEN CAST(gdpsy_priority.meta_value AS UNSIGNED) ELSE 1 END FROM {$wpdb->postmeta} gdpsy_priority WHERE gdpsy_priority.post_id = {$wpdb->posts}.ID AND gdpsy_priority.meta_key = %s ORDER BY gdpsy_priority.meta_id DESC LIMIT 1), 1)", 'interactive_details_priority' );
}

function gdpsy_interactive_catalog_clauses( $clauses, $query ) {
	if ( ! $query->get( '_gdpsy_interactive_catalog' ) ) {
		return $clauses;
	}
	global $wpdb;
	$filters = $query->get( '_gdpsy_interactive_filters' );
	foreach ( gdpsy_interactive_filter_definitions() as $key => $definition ) {
		$slugs = is_array( $filters ) && is_array( $filters[ $key ] ?? null ) ? $filters[ $key ] : array();
		$matches = array();
		foreach ( $slugs as $slug ) {
			if ( ! is_string( $slug ) || $slug === '' ) {
				continue;
			}
			$matches[] = $key === 'goal' ? gdpsy_interactive_goal_match_sql( $slug ) : gdpsy_interactive_tax_match_sql( $definition['taxonomy'], $slug );
		}
		if ( $matches ) {
			$clauses['where'] .= ' AND (' . implode( ' OR ', $matches ) . ')';
		}
	}
	$clauses['orderby'] = gdpsy_interactive_priority_sql() . " DESC, {$wpdb->posts}.post_title ASC, {$wpdb->posts}.ID ASC";
	return $clauses;
}
add_filter( 'posts_clauses', 'gdpsy_interactive_catalog_clauses', 10, 2 );

function gdpsy_interactive_goal_label( $post_id, $fallback ) {
	$terms = taxonomy_exists( 'gdpsy_i_goal' ) ? get_the_terms( $post_id, 'gdpsy_i_goal' ) : false;
	if ( ! $terms || is_wp_error( $terms ) ) {
		return $fallback;
	}
	$labels = wp_list_pluck( $terms, 'name' );
	sort( $labels, SORT_STRING );
	return implode( ' · ', $labels );
}

function gdpsy_interactive_portfolio_query( $args ) {
	$args['post_type'] = array( 'portfolio' );
	$args['post_status'] = 'publish';
	$args['has_password'] = false;
	$condition = array( 'key' => 'case-type', 'value' => array( 'consept', 'project' ), 'compare' => 'IN' );
	$args['meta_query'] = empty( $args['meta_query'] ) ? array( $condition ) : array( 'relation' => 'AND', $args['meta_query'], $condition );
	return $args;
}
add_filter( 'acf/fields/relationship/query/key=field_gdpsy_interactive_concepts', 'gdpsy_interactive_portfolio_query' );

function gdpsy_interactive_data( $post_id, $with_concepts = true ) {
	$raw = gdpsy_field( 'interactive_details', $post_id );
	$raw = is_array( $raw ) ? $raw : array();
	$data = array();
	foreach ( array( 'summary', 'goal' ) as $key ) {
		$data[ $key ] = gdpsy_text( $raw[ $key ] ?? '' );
	}
	$data['goal_label'] = gdpsy_interactive_goal_label( $post_id, gdpsy_interactive_goals()[ $data['goal'] ] ?? '' );
	foreach ( array( 'scenario', 'basis_ref' ) as $key ) {
		$value = $raw[ $key ] ?? '';
		$data[ $key ] = is_string( $value ) ? wp_kses_post( wpautop( $value ) ) : '';
		if ( trim( wp_strip_all_tags( $data[ $key ] ) ) === '' ) { $data[ $key ] = ''; }
	}
	$price = $raw['price'] ?? null;
	$data['price'] = is_scalar( $price ) && is_numeric( $price ) && is_finite( (float) $price ) && (float) $price >= 0 ? (float) $price : null;
	$data['price_from'] = in_array( $raw['price_from'] ?? false, array( true, 1, '1' ), true );
	$data['concepts'] = $with_concepts ? gdpsy_interactive_portfolio( $post_id, 'consept' ) : array();
	$data['projects'] = $with_concepts ? gdpsy_interactive_portfolio( $post_id, 'project' ) : array();
	return $data;
}

function gdpsy_interactive_price( $data ) {
	if ( $data['price'] === null ) {
		return 'Стоимость уточняется';
	}
	$decimals = floor( $data['price'] ) === $data['price'] ? 0 : 2;
	return ( $data['price_from'] ? 'от ' : '' ) . number_format_i18n( $data['price'], $decimals ) . ' ₽';
}

function gdpsy_interactive_contact_message() {
	return 'Здравствуйте, Анастасия! Хочу обсудить подключение интерактива «' . gdpsy_text( get_the_title( get_queried_object_id() ) ) . '» к моему сайту.';
}

function gdpsy_interactive_breadcrumbs( $post_id = 0 ) {
	$items = array( array( 'title' => 'Главная', 'url' => home_url( '/' ) ) );
	$items[] = array( 'title' => 'Интерактивы', 'url' => $post_id ? gdpsy_interactive_archive_url() : '' );
	if ( $post_id ) {
		$items[] = array( 'title' => get_the_title( $post_id ), 'url' => '' );
	}
	return $items;
}

function gdpsy_interactive_demo_file( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'interactive' || post_password_required( $post ) ) { return ''; }
	return gdpsy_demo_file( 'interactives', rawurldecode( $post->post_name ) );
}

function gdpsy_interactive_document( $post_id ) {
	static $documents = array();
	if ( post_password_required( $post_id ) ) { return false; }
	if ( array_key_exists( $post_id, $documents ) ) { return $documents[ $post_id ]; }
	$file = gdpsy_interactive_demo_file( $post_id );
	$html = $file !== '' ? gdpsy_demo_html( $file, 'interactives' ) : '';
	$documents[ $post_id ] = false;
	if ( ! is_string( $html ) || ! preg_match( '/<head\b[^>]*>(.*?)<\/head>/is', $html, $head ) || ! preg_match( '/<body\b([^>]*)>(.*?)<\/body>/is', $html, $body ) || trim( $body[2] ) === '' ) { return false; }
	$head = preg_replace( '#<title\b[^>]*>.*?</title>|<meta\b[^>]*>#is', '', $head[1] );
	$documents[ $post_id ] = array( 'head' => $head, 'body_attributes' => $body[1], 'body' => $body[2] );
	return $documents[ $post_id ];
}

function gdpsy_interactive_render_mobile_demo() {
	if ( ! is_singular( 'interactive' ) || ! isset( $_GET['gdpsy-interactive-demo'] ) || ! is_string( $_GET['gdpsy-interactive-demo'] ) || wp_unslash( $_GET['gdpsy-interactive-demo'] ) !== '1' ) { return; }
	$post_id = get_queried_object_id();
	$valid = (bool) gdpsy_interactive_document( $post_id );
	$file = $valid ? gdpsy_interactive_demo_file( $post_id ) : '';
	$html = $file !== '' ? gdpsy_demo_html( $file, 'interactives' ) : false;
	$valid = is_string( $html ) && trim( $html ) !== '';
	if ( ! $valid ) { $html = '<!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head><body><p>Демонстрация сейчас недоступна.</p></body></html>'; }
	status_header( $valid ? 200 : 404 );
	nocache_headers();
	header( 'Content-Type: text/html; charset=utf-8' );
	header( 'X-Robots-Tag: noindex, follow' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( "Content-Security-Policy: frame-ancestors 'self'", false );
	echo $html;
	exit;
}
add_action( 'template_redirect', 'gdpsy_interactive_render_mobile_demo', 0 );

function gdpsy_interactive_inline_demo() {
	return is_singular( 'interactive' ) && (bool) gdpsy_interactive_document( get_queried_object_id() );
}

function gdpsy_interactive_style_tag( $html, $handle ) {
	return gdpsy_interactive_inline_demo() && strpos( $handle, 'gdpsy-' ) === 0 ? '' : $html;
}
add_filter( 'style_loader_tag', 'gdpsy_interactive_style_tag', 10, 2 );

function gdpsy_interactive_theme_css() {
	return gdpsy_shadow_css( 'interactive', array( 'styles', 'theme', 'blog', 'article', 'interactive' ) );
}

function gdpsy_interactive_scope_scripts() {
	if ( ! gdpsy_interactive_inline_demo() ) { return; }
	wp_enqueue_script( 'gdpsy-theme-scope', get_theme_file_uri( 'js/theme-scope.js' ), array(), gdpsy_asset_version( 'js/theme-scope.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gdpsy-interactive-demo', get_theme_file_uri( 'js/interactive-demo.js' ), array( 'gdpsy-theme-scope' ), gdpsy_asset_version( 'js/interactive-demo.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gdpsy-interactive-preview', get_theme_file_uri( 'js/concept-shell.js' ), array( 'gdpsy-theme-scope' ), gdpsy_asset_version( 'js/concept-shell.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_add_inline_script( 'gdpsy-interactive-preview', 'window.GdpsyInteractivePreviewConfig={hasDemo:true};', 'before' );
	foreach ( wp_scripts()->registered as $handle => $script ) {
		if ( strpos( $handle, 'gdpsy-' ) === 0 && $handle !== 'gdpsy-theme-scope' ) {
			if ( ! in_array( 'gdpsy-theme-scope', $script->deps, true ) ) { $script->deps[] = 'gdpsy-theme-scope'; }
		}
	}
}
add_action( 'wp_enqueue_scripts', 'gdpsy_interactive_scope_scripts', 20 );

function gdpsy_interactive_saved_ids() {
	$value = isset( $_GET['selection_ids'] ) && is_string( $_GET['selection_ids'] ) ? wp_unslash( $_GET['selection_ids'] ) : '';
	if ( strlen( $value ) > 20000 || ! preg_match( '/^\d*(?:,\d+)*$/D', $value ) ) { return array(); }
	return array_slice( array_values( array_unique( array_filter( array_map( 'absint', explode( ',', $value ) ) ) ) ), 0, 1000 );
}

function gdpsy_interactive_is_selection() {
	return isset( $_GET['selection'] ) && is_string( $_GET['selection'] ) && $_GET['selection'] === '1';
}

function gdpsy_interactive_titles() {
	$titles = array();
	foreach ( get_posts( array( 'post_type' => 'interactive', 'post_status' => 'publish', 'has_password' => false, 'posts_per_page' => 500, 'no_found_rows' => true, 'orderby' => 'title', 'order' => 'ASC' ) ) as $post ) {
		$titles[ $post->ID ] = gdpsy_text( get_the_title( $post ) );
	}
	return $titles;
}

function gdpsy_interactive_save_button( $post_id, $icon = false ) {
	if ( $icon ) {
		printf( '<button class="gz-interactive-save gz-interactive-save--icon" type="button" data-interactive-save="%d" data-save-icon aria-label="В подборку" title="В подборку" aria-pressed="false" hidden><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg></button>', absint( $post_id ) );
		return;
	}
	printf( '<button class="gz-interactive-save gz-button gz-button--outline" type="button" data-interactive-save="%d" aria-pressed="false" hidden>В подборку</button>', absint( $post_id ) );
}
