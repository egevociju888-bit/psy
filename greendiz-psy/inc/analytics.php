<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_metrika_parts() {
	static $parts = null;
	if ( $parts !== null ) {
		return $parts;
	}
	$code = gdpsy_option_text( 'yandeks_metrika' );
	$parts = array( 'head' => $code, 'body' => '' );
	$pattern = '#<noscript\b[^>]*>.*?</noscript\s*>#is';
	if ( $code !== '' && preg_match_all( $pattern, $code, $matches ) ) {
		$parts['head'] = preg_replace( $pattern, '', $code );
		$parts['body'] = implode( "\n", $matches[0] );
	}
	return $parts;
}

function gdpsy_metrika_head() {
	$parts = gdpsy_metrika_parts();
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted analytics code from administrator settings.
	echo $parts['head'];
}
add_action( 'wp_head', 'gdpsy_metrika_head', 20 );

function gdpsy_metrika_body() {
	$parts = gdpsy_metrika_parts();
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Trusted analytics code from administrator settings.
	echo $parts['body'];
}
add_action( 'wp_body_open', 'gdpsy_metrika_body' );
