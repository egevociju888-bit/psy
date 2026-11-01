<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_is_concept() {
	return is_singular( 'portfolio' ) && gdpsy_field( 'case-type', get_queried_object_id() ) === 'consept';
}

function gdpsy_concept_file( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'portfolio' || gdpsy_field( 'case-type', $post_id ) !== 'consept' || post_password_required( $post ) ) {
		return '';
	}
	return gdpsy_demo_file( 'concepts', rawurldecode( $post->post_name ) );
}

function gdpsy_concept_page() {
	$saved = gdpsy_option( 'concept-page' );
	$defaults = array(
		'notice_text' => 'Дальше концепт сайта: имена, тексты и контакты условные',
		'offer_note' => 'После покупки он уходит из портфолио — такого сайта больше ни у кого не будет.',
	);
	foreach ( $defaults as $key => $default ) {
		$value = is_array( $saved ) ? gdpsy_text( $saved[ $key ] ?? '' ) : '';
		$defaults[ $key ] = $value !== '' ? $value : $default;
	}
	return $defaults;
}

function gdpsy_concept_cost( $post_id ) {
	$info = gdpsy_portfolio_info( $post_id );
	$cost = gdpsy_text( $info['cost'] ?? '' );
	$amount = (int) preg_replace( '/\D/u', '', $cost );
	if ( $amount <= 0 || preg_match( '/[-−–—]/u', $cost ) ) {
		return $cost;
	}
	return ( preg_match( '/^\s*от(?=\s|\d)/iu', $cost ) ? 'от ' : '' ) . number_format( $amount, 0, '', "\u{00A0}" ) . "\u{00A0}₽";
}

function gdpsy_concept_theme_css() {
	return gdpsy_shadow_css( 'concept', array( 'styles', 'theme', 'projects', 'concept' ) );
}

function gdpsy_concept_styles( $linked = false ) {
	$compiled = gdpsy_concept_theme_css();
	if ( $linked && $compiled['url'] !== '' ) {
		echo '<link rel="stylesheet" href="' . esc_url( $compiled['url'] ) . '">';
		return;
	}
	echo '<style>' . $compiled['css'] . '</style>';
}

function gdpsy_concept_insert( $html, $pattern, $insert ) {
	if ( ! preg_match( $pattern, $html, $match, PREG_OFFSET_CAPTURE ) ) {
		return false;
	}
	$position = $match[0][1] + strlen( $match[0][0] );
	return substr( $html, 0, $position ) . $insert . substr( $html, $position );
}

function gdpsy_concept_adminbar() {
	if ( ! is_admin_bar_showing() ) {
		return array( '', '' );
	}
	if ( class_exists( 'QM_Dispatchers' ) ) {
		$dispatcher = QM_Dispatchers::get( 'html' );
		if ( $dispatcher ) {
			$dispatcher->did_footer = true;
		}
	}
	wp_enqueue_style( 'admin-bar' );
	wp_enqueue_script( 'admin-bar' );
	ob_start();
	wp_print_styles( array( 'admin-bar' ) );
	echo '<style>html{margin-top:32px!important}@media(max-width:782px){html{margin-top:46px!important}}</style>';
	$head = ob_get_clean();
	ob_start();
	wp_admin_bar_render();
	wp_print_scripts( array( 'admin-bar' ) );
	return array( $head, ob_get_clean() );
}

function gdpsy_concept_is_raw() {
	return isset( $_GET['gdd-raw'] ) && is_string( $_GET['gdd-raw'] ) && wp_unslash( $_GET['gdd-raw'] ) === '1';
}

function gdpsy_concept_seo( $post_id ) {
	$info = gdpsy_portfolio_info( $post_id );
	$url = get_permalink( $post_id );
	$title = wp_get_document_title();
	$description = gdpsy_text( get_post_meta( $post_id, 'rank_math_description', true ) );
	if ( $description === '' || strpos( $description, '%' ) !== false ) {
		$description = gdpsy_text( $info['opisanie'] ?? '' );
	}
	if ( $description === '' && has_excerpt( $post_id ) ) {
		$description = gdpsy_text( get_the_excerpt( $post_id ) );
	}
	$description = $description !== '' ? wp_html_excerpt( $description, 300, '…' ) : '';
	$image = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';
	$html = '<title>' . esc_html( $title ) . '</title>';
	$html .= '<link rel="canonical" href="' . esc_url( $url ) . '">';
	$meta = array(
		array( 'property', 'og:locale', 'ru_RU' ),
		array( 'property', 'og:type', 'website' ),
		array( 'property', 'og:site_name', get_bloginfo( 'name' ) ),
		array( 'property', 'og:title', $title ),
		array( 'property', 'og:url', $url ),
		array( 'name', 'twitter:card', $image ? 'summary_large_image' : 'summary' ),
	);
	if ( $description !== '' ) {
		array_unshift( $meta, array( 'name', 'description', $description ) );
		$meta[] = array( 'property', 'og:description', $description );
	}
	if ( $image ) {
		$meta[] = array( 'property', 'og:image', $image );
	}
	foreach ( $meta as $item ) {
		$html .= '<meta ' . $item[0] . '="' . esc_attr( $item[1] ) . '" content="' . esc_attr( $item[2] ) . '">';
	}
	return $html;
}

function gdpsy_concept_icons() {
	ob_start();
	gdpsy_favicon();
	wp_site_icon();
	return ob_get_clean();
}

function gdpsy_concept_strip_icons( $html ) {
	return preg_replace_callback( '#<link\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>#i', function ( $match ) {
		if ( ! preg_match( '#\brel\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))#i', $match[0], $rel ) ) {
			return $match[0];
		}
		$tokens = preg_split( '#\s+#', strtolower( trim( $rel[1] . ( $rel[2] ?? '' ) . ( $rel[3] ?? '' ) ) ) );
		return array_intersect( $tokens, array( 'icon', 'apple-touch-icon', 'apple-touch-icon-precomposed', 'mask-icon', 'manifest' ) ) ? '' : $match[0];
	}, $html );
}

function gdpsy_render_concept( $post_id ) {
	if ( ! gdpsy_is_concept() || post_password_required( $post_id ) ) {
		return false;
	}
	$file = gdpsy_concept_file( $post_id );
	$html = $file !== '' ? gdpsy_demo_html( $file, 'concepts' ) : '';
	$has_demo = is_string( $html ) && trim( $html ) !== '';
	if ( ! $has_demo ) {
		$html = '<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head><body></body></html>';
	}
	$raw = $has_demo && gdpsy_concept_is_raw();
	$head = $raw ? '<meta name="robots" content="noindex, follow"><title>' . esc_html( get_the_title( $post_id ) ) . '</title><link rel="canonical" href="' . esc_url( get_permalink( $post_id ) ) . '"><style>html:root{scrollbar-width:none;overflow-x:clip}:root::-webkit-scrollbar{display:none}</style>' : gdpsy_concept_seo( $post_id );
	$shell = '';
	$footer = '';
	if ( ! $raw ) {
		foreach ( array( 'css/concept-stage.css' ) as $asset ) {
			$head .= '<link rel="stylesheet" href="' . esc_url( get_theme_file_uri( $asset ) ) . '?ver=' . esc_attr( gdpsy_asset_version( $asset ) ) . '">';
		}
		$compiled = gdpsy_concept_theme_css();
		$head .= '<style data-gdpsy-fonts>' . $compiled['fonts'] . '</style>';
		$config = array( 'url' => admin_url( 'admin-ajax.php' ), 'hasDemo' => $has_demo );
		$head .= '<script>window.GdpsyConceptConfig=' . wp_json_encode( $config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ';window.GdpsyFormConfig={url:window.GdpsyConceptConfig.url};</script>';
		foreach ( array( 'theme-scope', 'forms', 'contact', 'navigation', 'common', 'cookie', 'articles', 'concept-shell' ) as $name ) {
			$asset = 'js/' . $name . '.js';
			$head .= '<script src="' . esc_url( get_theme_file_uri( $asset ) ) . '?ver=' . esc_attr( gdpsy_asset_version( $asset ) ) . '" defer></script>';
		}
		ob_start();
		get_template_part( 'template-parts/concept-shell', null, array( 'post_id' => $post_id, 'has_demo' => $has_demo ) );
		$shell = ob_get_clean();
		ob_start();
		get_template_part( 'template-parts/concept-details', null, array( 'post_id' => $post_id ) );
		$footer = ob_get_clean();
		list( $bar_head, $bar_body ) = gdpsy_concept_adminbar();
		$metrika = gdpsy_metrika_parts();
		$head .= $bar_head . $metrika['head'];
		$shell = $metrika['body'] . $bar_body . $shell;
	}
	if ( ! preg_match( '/<head\b/i', $html ) || ! preg_match( '/<body\b/i', $html ) ) {
		$html = '<!DOCTYPE html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head><body>' . $html . '</body></html>';
	}
	$html = preg_replace( '#<title\b[^>]*>.*?</title>#is', '', $html );
	$html = preg_replace( '#<meta\b(?=[^>]*\b(?:name|property)\s*=\s*["\x27]?(?:robots|googlebot|bingbot|description|og:|twitter:))[^>]*>#i', '', $html );
	$html = preg_replace( '#<link\b(?=[^>]*\brel\s*=\s*["\x27]?canonical\b)[^>]*>#i', '', $html );
	$html = gdpsy_concept_strip_icons( $html );
	$html = gdpsy_concept_insert( $html, '/<head\b[^>]*>/i', $head . gdpsy_concept_icons() );
	$html = gdpsy_concept_insert( $html, '/<body\b[^>]*>/i', $shell );
	$position = strripos( $html, '</body>' );
	$html = $position !== false ? substr( $html, 0, $position ) . $footer . substr( $html, $position ) : $html . $footer;
	status_header( 200 );
	if ( is_user_logged_in() ) {
		nocache_headers();
	}
	header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
	if ( $raw ) {
		header( 'X-Robots-Tag: noindex, follow' );
	}
	echo $html;
	return true;
}
