<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$item = $args['item'];
$is_concept = gdpsy_field( 'case-type', $item->ID ) === 'consept';
$info = gdpsy_portfolio_info( $item->ID );
$reserved = $is_concept ? gdpsy_concept_reserved_text( $item->ID ) : '';
$kicker = $is_concept ? implode( ' · ', gdpsy_concept_tag_list( $item->ID ) ) : gdpsy_text( $info['speczializacziya'] ?? '' );
$detail = gdpsy_text( $info['opisanie'] ?? '' );
$cost = $is_concept ? gdpsy_concept_cost( $item->ID ) : '';
$title_id = 'gz-related-' . absint( $item->ID );
?>
<a class="gz-article gz-related-card" href="<?php echo esc_url( get_permalink( $item ) ); ?>" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
	<div class="gz-related-project-preview">
		<?php if ( has_post_thumbnail( $item ) ) : echo get_the_post_thumbnail( $item, 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '', 'sizes' => '(min-width: 1201px) 45vw, (min-width: 1001px) 66vw, 76vw' ) ); else : ?><span>Обложка готовится</span><?php endif; ?>
	</div>
	<div class="gz-related-project-copy">
		<div class="gz-reserved-row"><span class="gz-related-project-type"><?php echo $is_concept ? 'Концепт' : 'Проект'; ?></span><?php if ( $reserved !== '' ) : ?><span class="gz-reserved"><?php echo esc_html( $reserved ); ?></span><?php endif; ?></div>
		<h3 id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></h3>
		<?php if ( $kicker !== '' ) : ?><p class="gz-related-project-detail"><?php echo esc_html( $kicker ); ?></p><?php endif; ?>
		<?php if ( $detail !== '' ) : ?><p><?php echo esc_html( wp_trim_words( $detail, 24 ) ); ?></p><?php endif; ?>
		<div class="gz-related-project-footer"><div class="gz-related-project-more"><?php if ( $cost !== '' ) : ?><span class="gz-related-project-price"><?php echo esc_html( $cost ); ?></span><?php endif; ?><span aria-hidden="true">Подробнее</span></div></div>
	</div>
</a>
