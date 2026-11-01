<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_field( $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

function gdpsy_option( $name ) {
	return gdpsy_field( $name, 'option' );
}

function gdpsy_option_text( $name ) {
	$value = gdpsy_option( $name );
	return is_scalar( $value ) ? trim( (string) $value ) : '';
}

function gdpsy_price( $id ) {
	$keys = array(
		'base'     => 'lending',
		'concept'  => 'concept',
		'pages'    => 'dop_straniczy',
		'blog'     => 'blog',
		'autopost' => 'auto-publishing',
		'mail'     => 'newsletter',
		'toolkit'  => 'knowledgebase_block',
		'annual'   => 'annual',
	);
	$prices = gdpsy_option( 'price' );
	$value = isset( $keys[ $id ] ) && is_array( $prices ) ? ( $prices[ $keys[ $id ] ] ?? '' ) : '';
	if ( ! is_scalar( $value ) || preg_match( '/[-−]/u', (string) $value ) ) {
		return 0;
	}
	return (int) preg_replace( '/\D/u', '', (string) $value );
}

function gdpsy_money( $id ) {
	$amount = gdpsy_price( $id );
	return $amount > 0 ? number_format( $amount, 0, '', "\u{00A0}" ) . "\u{00A0}₽" : '';
}

function gdpsy_cost_modules() {
	return array(
		'blog'     => array( 'label' => 'Блог', 'counted' => false, 'minimum' => false, 'requires' => array() ),
		'autopost' => array( 'label' => 'Автопубликация', 'counted' => false, 'minimum' => false, 'requires' => array( 'blog' ) ),
		'mail'     => array( 'label' => 'Email-рассылка', 'counted' => false, 'minimum' => false, 'requires' => array() ),
		'pages'    => array( 'label' => 'Дополнительные страницы', 'counted' => true, 'minimum' => true, 'requires' => array() ),
		'toolkit'  => array( 'label' => 'Дополнительные интерактивы', 'counted' => true, 'minimum' => true, 'requires' => array() ),
	);
}

function gdpsy_quiz_config() {
	$modules = array();
	foreach ( gdpsy_cost_modules() as $id => $module ) {
		$modules[] = array( 'id' => $id, 'price' => gdpsy_price( $id ) ) + $module;
	}
	return array(
		'modules' => $modules,
		'prices'  => array(
			'scratch' => gdpsy_price( 'base' ),
			'concept' => gdpsy_price( 'concept' ),
			'annual'  => gdpsy_price( 'annual' ),
		),
	);
}

function gdpsy_contact_month() {
	$today = current_datetime();
	if ( (int) $today->format( 't' ) - (int) $today->format( 'j' ) <= 10 ) {
		$today = $today->modify( 'first day of next month' );
	}
	$months = array( 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь' );
	return $months[ (int) $today->format( 'n' ) - 1 ];
}

function gdpsy_phone() {
	return gdpsy_option_text( 'phone' );
}

function gdpsy_tel_href() {
	$phone = gdpsy_phone();
	$digits = preg_replace( '/\D/u', '', $phone );
	return $digits !== '' ? 'tel:' . ( str_starts_with( $phone, '+' ) ? '+' : '' ) . $digits : '';
}

function gdpsy_email() {
	return sanitize_email( gdpsy_option_text( 'email' ) );
}

function gdpsy_messenger_url( $name ) {
	$bases = array( 'telegram' => 'https://t.me/', 'max' => 'https://max.ru/' );
	$value = gdpsy_option_text( $name );
	if ( ! isset( $bases[ $name ] ) || $value === '' ) {
		return '';
	}
	if ( preg_match( '#^https?://#i', $value ) ) {
		return esc_url_raw( $value, array( 'http', 'https' ) );
	}
	if ( ! preg_match( '/^@?[a-z0-9_.+-]+$/i', $value ) ) {
		return '';
	}
	return $bases[ $name ] . rawurlencode( ltrim( $value, '@' ) );
}

function gdpsy_contact_channels() {
	$rows = gdpsy_option( 'contact_channels' );
	$channels = array();
	foreach ( is_array( $rows ) ? $rows : array() as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$label = isset( $row['label'] ) && is_scalar( $row['label'] ) ? sanitize_text_field( (string) $row['label'] ) : '';
		$url = isset( $row['url'] ) && is_string( $row['url'] ) ? esc_url_raw( $row['url'], array( 'http', 'https' ) ) : '';
		if ( ! preg_match( '#^https?://#i', $url ) || ! wp_parse_url( $url, PHP_URL_HOST ) ) {
			continue;
		}
		if ( $label !== '' && $url !== '' ) {
			$channels[] = array(
				'label' => $label,
				'url'   => $url,
				'icon'  => in_array( $row['icon'] ?? '', array( 'tg', 'max', 'vk', 'pinterest', 'behance', 'dribbble', 'dzen', 'ext' ), true ) ? $row['icon'] : 'ext',
			);
		}
	}
	return $channels;
}


function gdpsy_demo_file( $directory, $slug ) {
	if ( ! preg_match( '/^[\p{L}\p{N}_-]+$/u', $slug ) ) {
		return '';
	}
	$root = realpath( WP_CONTENT_DIR . '/' . $directory );
	$file = $root ? realpath( $root . '/' . $slug . '/index.html' ) : false;
	return $file && dirname( $file, 2 ) === $root && is_file( $file ) && is_readable( $file ) && filesize( $file ) > 0 ? $file : '';
}

function gdpsy_demo_url( $url, $base ) {
	$url = trim( $url );
	if ( $url === '' || $url[0] === '#' || $url[0] === '/' || preg_match( '/^[a-z][a-z0-9+.-]*:/i', $url ) ) {
		return $url;
	}
	return $base . $url;
}

function gdpsy_demo_html( $file, $directory ) {
	$html = file_get_contents( $file );
	if ( ! is_string( $html ) ) {
		return '';
	}
	$base = content_url( $directory . '/' . rawurlencode( basename( dirname( $file ) ) ) . '/' );
	$tags = '/<(?:link|script|img|source|video|audio|track|iframe|embed|object|image|use|input)\b[^>]*>/i';
	$attributes = '/(\s)((?:xlink:)?(?:src|href|poster|data|srcset|imagesrcset|data-src|data-srcset))(\s*=\s*)(?:"([^"]*)"|\'([^\']*)\')/i';
	$html = preg_replace_callback( $tags, function ( $tag ) use ( $attributes, $base ) {
		return preg_replace_callback( $attributes, function ( $match ) use ( $base ) {
			$quote = isset( $match[5] ) ? "'" : '"';
			$value = isset( $match[5] ) ? $match[5] : $match[4];
			if ( stripos( $match[2], 'srcset' ) !== false ) {
				if ( stripos( $value, 'data:' ) === false ) {
					$value = preg_replace_callback( '/(^|,)(\s*)([^\s,]+)/', function ( $item ) use ( $base ) {
						return $item[1] . $item[2] . gdpsy_demo_url( $item[3], $base );
					}, $value );
				}
			} else {
				$value = gdpsy_demo_url( $value, $base );
			}
			return $match[1] . $match[2] . $match[3] . $quote . $value . $quote;
		}, $tag[0] );
	}, $html );
	return preg_replace_callback( '/url\(\s*(["\']?)([^)"\']*)\1\s*\)/i', function ( $match ) use ( $base ) {
		return 'url(' . $match[1] . gdpsy_demo_url( $match[2], $base ) . $match[1] . ')';
	}, $html );
}
