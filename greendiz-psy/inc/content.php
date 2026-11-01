<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_has_landing() {
	return is_front_page() || is_post_type_archive( 'portfolio' );
}

function gdpsy_text( $value ) {
	return is_scalar( $value ) ? trim( wp_strip_all_tags( (string) $value ) ) : '';
}

function gdpsy_selected( $name, $type = 'portfolio' ) {
	$items = gdpsy_field( $name, (int) get_option( 'page_on_front' ) ?: get_queried_object_id() );
	$posts = array();
	foreach ( is_array( $items ) ? $items : array() as $item ) {
		$id = $item instanceof WP_Post ? $item->ID : ( is_numeric( $item ) ? absint( $item ) : 0 );
		$post = $id ? get_post( $id ) : null;
		if ( $post && $post->post_type === $type && $post->post_status === 'publish' && ! post_password_required( $post ) ) {
			$posts[ $id ] = $post;
		}
	}
	return array_values( $posts );
}

function gdpsy_portfolio_info( $id ) {
	$key = gdpsy_field( 'case-type', $id ) === 'consept' ? 'consept-info' : 'project-info';
	$info = gdpsy_field( $key, $id );
	return is_array( $info ) ? $info : array();
}

function gdpsy_home_projects() {
	return array_values( array_filter( gdpsy_selected( 'projects' ), function ( $post ) {
		return gdpsy_field( 'case-type', $post->ID ) === 'project';
	} ) );
}

function gdpsy_review( $id ) {
	$case = gdpsy_field( 'project-case', $id );
	$review = is_array( $case ) ? ( $case['review'] ?? array() ) : array();
	return is_array( $review ) ? $review : array();
}

function gdpsy_archive_url() {
	return get_post_type_archive_link( 'portfolio' ) ?: home_url( '/portfolio/' );
}

function gdpsy_blog_url() {
	$id = (int) get_option( 'page_for_posts' );
	return $id ? get_permalink( $id ) : home_url( '/blog/' );
}

function gdpsy_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page && $page->post_status === 'publish' ? get_permalink( $page ) : '';
}

function gdpsy_privacy_url() {
	return get_privacy_policy_url() ?: gdpsy_page_url( 'privacy-policy' );
}

function gdpsy_consent_url() {
	return gdpsy_page_url( 'request-data-consent' ) ?: gdpsy_privacy_url();
}

function gdpsy_price_label( $ids, $prefix = '' ) {
	$sum = 0;
	foreach ( (array) $ids as $id ) {
		$price = gdpsy_price( $id );
		if ( $price <= 0 ) {
			return '';
		}
		$sum += $price;
	}
	return $sum > 0 ? $prefix . number_format( $sum, 0, '', "\u{00A0}" ) . "\u{00A0}₽" : '';
}

function gdpsy_case_filter() {
	$value = isset( $_GET['case-type'] ) && is_string( $_GET['case-type'] ) ? wp_unslash( $_GET['case-type'] ) : '';
	return in_array( $value, array( 'project', 'consept' ), true ) ? $value : 'all';
}

function gdpsy_archive_offers() {
	return array(
		'all' => array( 'Пока не знаете, с чего начать?', 'Расскажите о своей практике и задаче. Вместе определим, что вам ближе: адаптация концепта или разработка с нуля.', 'Помогите выбрать', 'Здравствуйте, Анастасия! Помогите выбрать между концептом и разработкой сайта с нуля.' ),
		'project' => array( 'Начнём с вашей задачи', 'Расскажите о клиентах, подходе и планах. Предложу структуру и состав сайта, затем зафиксируем объём работ, стоимость и сроки.', 'Рассказать о задаче', 'Здравствуйте, Анастасия! Хочу обсудить разработку сайта с нуля: структуру, состав и этапы работы.' ),
		'consept' => array( 'Близкий по духу. Только ваш.', 'Адаптирую тексты и фото под вашу практику, добавлю до трёх информационных блоков в том же дизайне. После покупки сниму концепт с продажи.', 'Обсудить адаптацию', 'Здравствуйте, Анастасия! Хочу обсудить выбор и адаптацию концепта под мою практику.' ),
	);
}

function gdpsy_portfolio_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'post_status', 'publish' );
		$query->set( 'has_password', false );
		$query->set( 'orderby', array( 'date' => 'DESC', 'ID' => 'DESC' ) );
	}
}
add_action( 'pre_get_posts', 'gdpsy_portfolio_query' );
