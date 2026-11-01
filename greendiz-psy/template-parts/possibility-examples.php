<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$key = $args['key'] ?? '';
$examples = array();
if ( $key === 'library' ) {
	$examples[] = array( 'title' => 'Посмотреть «Вдох»', 'url' => 'https://vdoh.greendiz.ru/', 'external' => true );
} elseif ( $key === 'interactions' ) {
	foreach ( gdpsy_selected( 'concepts' ) as $concept ) {
		if ( gdpsy_field( 'case-type', $concept->ID ) === 'consept' ) {
			$examples[] = array( 'title' => get_the_title( $concept ), 'url' => get_permalink( $concept ), 'external' => false );
		}
		if ( count( $examples ) === 3 ) {
			break;
		}
	}
}
if ( ! $examples ) {
	return;
}
?>
<section class="gz-possibility-examples" aria-labelledby="gz-possibility-examples-title">
	<h2 id="gz-possibility-examples-title">Посмотреть пример</h2>
	<ul><?php foreach ( $examples as $example ) : ?><li><a class="gz-text-action" href="<?php echo esc_url( $example['url'] ); ?>"<?php if ( $example['external'] ) : ?> target="_blank" rel="noopener noreferrer"<?php endif; ?>><?php echo esc_html( $example['title'] ); ?><?php if ( $example['external'] ) : ?> <span aria-hidden="true">↗</span><?php endif; ?></a></li><?php endforeach; ?></ul>
</section>
