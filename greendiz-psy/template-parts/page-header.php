<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$title = get_the_title();
if ( trim( wp_strip_all_tags( $title ) ) === '' ) {
	$title = $args['title'] ?? '';
}
$breadcrumbs = gdpsy_page_breadcrumbs( get_the_ID() );
if ( trim( wp_strip_all_tags( $breadcrumbs[ count( $breadcrumbs ) - 1 ]['title'] ) ) === '' ) {
	$breadcrumbs[ count( $breadcrumbs ) - 1 ]['title'] = $title;
}
$intro = post_password_required() || (int) get_query_var( 'page' ) > 1 ? '' : (string) get_post_field( 'post_excerpt', get_the_ID(), 'raw' );
if ( ! post_password_required() && (int) get_query_var( 'page' ) < 2 && trim( wp_strip_all_tags( $intro ) ) === '' ) {
	$intro = $args['intro'] ?? '';
}
?>
<div class="gz-breadcrumbs gz-page-breadcrumbs">
	<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => $breadcrumbs ) ); ?>
</div>
<header class="gz-page-heading">
	<h1><?php echo esc_html( $title ); ?></h1>
	<?php if ( trim( wp_strip_all_tags( $intro ) ) !== '' ) : ?>
		<div class="gz-page-intro"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div>
	<?php endif; ?>
</header>

