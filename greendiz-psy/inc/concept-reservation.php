<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const GDPSY_RESERVE_META = 'gdpsy_reserved_until';
const GDPSY_RESERVE_DAYS_KEY = 'field_gdpsy_concept_reserve_days';
const GDPSY_RESERVE_STATUS_KEY = 'field_gdpsy_concept_reserve_status';

function gdpsy_concept_reserved_until( $post_id ) {
	$post_id = absint( $post_id );
	if ( ! $post_id || get_post_meta( $post_id, 'case-type', true ) !== 'consept' ) {
		return '';
	}
	$date = get_post_meta( $post_id, GDPSY_RESERVE_META, true );
	if ( ! is_string( $date ) || ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
		return '';
	}
	return $date >= wp_date( 'Y-m-d' ) ? $date : '';
}

function gdpsy_concept_date_label( $date, $with_year = false ) {
	$months = array( 1 => 'янв.', 2 => 'февр.', 3 => 'мар.', 4 => 'апр.', 5 => 'мая', 6 => 'июн.', 7 => 'июл.', 8 => 'авг.', 9 => 'сент.', 10 => 'окт.', 11 => 'нояб.', 12 => 'дек.' );
	list( $year, $month, $day ) = array_map( 'intval', explode( '-', $date ) );
	return $day . ' ' . $months[ $month ] . ( $with_year ? ' ' . $year : '' );
}

function gdpsy_concept_reserved_text( $post_id ) {
	$date = gdpsy_concept_reserved_until( $post_id );
	return $date === '' ? '' : 'Забронирован до ' . gdpsy_concept_date_label( $date );
}

function gdpsy_concept_offer( $post_id ) {
	$title = get_the_title( $post_id );
	if ( gdpsy_concept_reserved_until( $post_id ) !== '' ) {
		return array(
			'reserved' => true,
			'label'    => 'Сообщите, если освободится',
			'preset'   => 'Здравствуйте, Анастасия! Концепт «' . $title . '» сейчас забронирован. Если он освободится, сообщите мне, пожалуйста.',
		);
	}
	return array(
		'reserved' => false,
		'label'    => 'Хочу такой сайт',
		'preset'   => 'Здравствуйте, Анастасия! Мне понравился концепт «' . $title . '», хочу обсудить его для себя.',
	);
}

function gdpsy_concept_reserve_save( $value, $post_id ) {
	if ( ! is_numeric( $post_id ) || get_post_type( (int) $post_id ) !== 'portfolio' || ! current_user_can( 'edit_post', (int) $post_id ) ) {
		return '0';
	}
	$post_id = (int) $post_id;
	$value = is_scalar( $value ) ? (string) $value : '0';
	if ( $value === 'clear' ) {
		delete_post_meta( $post_id, GDPSY_RESERVE_META );
	} elseif ( preg_match( '/^[1-7]$/', $value ) ) {
		update_post_meta( $post_id, GDPSY_RESERVE_META, current_datetime()->modify( '+' . $value . ' days' )->format( 'Y-m-d' ) );
	}
	return '0';
}
add_filter( 'acf/update_value/key=' . GDPSY_RESERVE_DAYS_KEY, 'gdpsy_concept_reserve_save', 10, 2 );

function gdpsy_concept_reserve_status( $field ) {
	$post = get_post();
	$date = $post ? gdpsy_concept_reserved_until( $post->ID ) : '';
	$field['message'] = $date === '' ? 'Брони нет.' : 'Забронирован до ' . esc_html( gdpsy_concept_date_label( $date, true ) ) . ' включительно.';
	return $field;
}
add_filter( 'acf/prepare_field/key=' . GDPSY_RESERVE_STATUS_KEY, 'gdpsy_concept_reserve_status' );
