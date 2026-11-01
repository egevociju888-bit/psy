<?php
$gdpsy_portfolio_id = isset( $args['post_id'] ) ? absint( $args['post_id'] ) : get_the_ID();
if ( post_password_required( $gdpsy_portfolio_id ) || (int) get_query_var( 'page' ) > 1 ) {
	return;
}
$gdpsy_kind = get_post_meta( $gdpsy_portfolio_id, 'case-type', true );
$gdpsy_interactive_ids = gdpsy_portfolio_interactives( $gdpsy_portfolio_id, $gdpsy_kind, true );
if ( ! $gdpsy_interactive_ids ) {
	return;
}
$gdpsy_included = $gdpsy_kind === 'consept';
?>
<section class="gz-entry<?php echo empty( $args['compact'] ) ? ' gz-inner gz-page-main' : ''; ?>" aria-labelledby="gz-portfolio-interactives-title">
	<h2 id="gz-portfolio-interactives-title"><?php echo $gdpsy_included ? 'Интерактивы в стоимости концепта' : 'Интерактивы в проекте'; ?></h2>
	<p><?php echo $gdpsy_included ? 'Эти интерактивы включены в стоимость этого концепта:' : 'Примеры использования интерактивов в этом проекте:'; ?></p>
	<ul>
		<?php foreach ( $gdpsy_interactive_ids as $gdpsy_interactive_id ) : ?>
		<li><a href="<?php echo esc_url( get_permalink( $gdpsy_interactive_id ) ); ?>"><?php echo esc_html( get_the_title( $gdpsy_interactive_id ) ); ?> <span aria-hidden="true">↗</span></a></li>
		<?php endforeach; ?>
	</ul>
</section>
