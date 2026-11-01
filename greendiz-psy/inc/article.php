<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_is_article() {
	return is_singular( 'post' );
}

function gdpsy_article_reading_minutes( $html, $intro = '' ) {
	$html = preg_replace( '/<\/(?:p|div|h[1-6]|li|td|th|tr|blockquote)>|<br\s*\/?>/i', ' ', $intro . ' ' . $html );
	$text = html_entity_decode( wp_strip_all_tags( strip_shortcodes( $html ) ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$words = preg_match_all( '/[\p{L}\p{N}]+(?:[\x{2019}\x{0027}-][\p{L}\p{N}]+)*/u', $text );
	return $words ? (int) ceil( $words / 200 ) : 0;
}

function gdpsy_article_document( $html, $post_id ) {
	$document = array( 'html' => $html, 'toc' => array() );
	if ( ! class_exists( 'WP_HTML_Tag_Processor' ) || ! method_exists( 'WP_HTML_Tag_Processor', 'next_token' ) ) {
		return $document;
	}
	$processor = new WP_HTML_Tag_Processor( $html );
	$ids = array();
	$headings = array();
	$current = null;
	$index = 0;
	while ( $processor->next_token() ) {
		if ( $processor->get_token_type() === '#tag' ) {
			$tag = $processor->get_tag();
			$closer = $processor->is_tag_closer();
			$id = $closer ? null : $processor->get_attribute( 'id' );
			if ( is_string( $id ) && $id !== '' ) {
				$ids[ $id ] = ( $ids[ $id ] ?? 0 ) + 1;
			}
			if ( $tag === 'BR' && ! $closer && $current !== null ) {
				$current['title'] .= ' ';
			}
			if ( ! in_array( $tag, array( 'H2', 'H3' ), true ) ) {
				continue;
			}
			if ( ! $closer ) {
				if ( $current !== null ) {
					$headings[] = $current;
				}
				$current = array( 'index' => $index++, 'id' => is_string( $id ) ? $id : '', 'tag' => $tag, 'title' => '' );
			} elseif ( $current !== null && $current['tag'] === $tag ) {
				$headings[] = $current;
				$current = null;
			}
		} elseif ( $processor->get_token_type() === '#text' && $current !== null ) {
			$current['title'] .= $processor->get_modifiable_text();
		}
	}
	if ( $current !== null ) {
		$headings[] = $current;
	}
	$reserved = array( 'greendiz-design-study' => true );
	$updates = array();
	foreach ( $headings as $heading ) {
		$title = trim( preg_replace( '/\s+/u', ' ', $heading['title'] ) );
		if ( $title === '' ) {
			continue;
		}
		$id = $heading['id'];
		if ( $id === '' || preg_match( '/\s/u', $id ) || ( $ids[ $id ] ?? 0 ) !== 1 || str_starts_with( $id, 'gz-' ) || isset( $reserved[ $id ] ) ) {
			$base = 'gdpsy-article-' . absint( $post_id ) . '-section-' . ( $heading['index'] + 1 );
			$id = $base;
			$suffix = 2;
			while ( isset( $ids[ $id ] ) || isset( $reserved[ $id ] ) ) {
				$id = $base . '-' . $suffix++;
			}
			$ids[ $id ] = 1;
		}
		$updates[ $heading['index'] ] = $id;
		$document['toc'][] = array( 'id' => $id, 'title' => $title, 'level' => $heading['tag'] === 'H3' ? 3 : 2 );
	}
	$processor = new WP_HTML_Tag_Processor( $html );
	$index = 0;
	while ( $processor->next_tag() ) {
		if ( ! in_array( $processor->get_tag(), array( 'H2', 'H3' ), true ) ) {
			continue;
		}
		if ( isset( $updates[ $index ] ) ) {
			$processor->set_attribute( 'id', $updates[ $index ] );
			if ( $processor->get_attribute( 'tabindex' ) === null ) {
				$processor->set_attribute( 'tabindex', '-1' );
			}
		}
		++$index;
	}
	$document['html'] = $processor->get_updated_html();
	return $document;
}

function gdpsy_article_category( $post_id ) {
	$categories = get_the_category( $post_id );
	$primary = (int) get_post_meta( $post_id, 'rank_math_primary_category', true );
	foreach ( $categories as $category ) {
		if ( $category->term_id === $primary ) {
			return $category;
		}
	}
	return $categories ? $categories[0] : null;
}

function gdpsy_article_breadcrumbs( $post_id ) {
	$items = array(
		array( 'title' => 'Главная', 'url' => home_url( '/' ) ),
		array( 'title' => 'Полезные статьи', 'url' => gdpsy_blog_url() ),
	);
	$category = gdpsy_article_category( $post_id );
	if ( ! $category ) {
		return $items;
	}
	$ids = array_merge( array_reverse( get_ancestors( $category->term_id, 'category', 'taxonomy' ) ), array( $category->term_id ) );
	foreach ( $ids as $id ) {
		$term = get_term( $id, 'category' );
		if ( ! $term instanceof WP_Term ) {
			continue;
		}
		$url = get_term_link( $term );
		if ( ! is_wp_error( $url ) ) {
			$items[] = array( 'title' => $term->name, 'url' => $url );
		}
	}
	return $items;
}

function gdpsy_related_articles( $post_id ) {
	$articles = array();
	$exclude = array( absint( $post_id ) );
	$categories = wp_get_post_categories( $post_id );
	$base = array( 'post_type' => 'post', 'post_status' => 'publish', 'has_password' => false, 'ignore_sticky_posts' => true, 'no_found_rows' => true, 'orderby' => array( 'date' => 'DESC', 'ID' => 'DESC' ) );
	if ( $categories && ! is_wp_error( $categories ) ) {
		$query = new WP_Query( array_merge( $base, array( 'posts_per_page' => 6, 'post__not_in' => $exclude, 'category__in' => $categories ) ) );
		$articles = $query->posts;
		$exclude = array_merge( $exclude, wp_list_pluck( $articles, 'ID' ) );
	}
	if ( count( $articles ) < 6 ) {
		$query = new WP_Query( array_merge( $base, array( 'posts_per_page' => 6 - count( $articles ), 'post__not_in' => $exclude ) ) );
		$articles = array_merge( $articles, $query->posts );
	}
	return $articles;
}
