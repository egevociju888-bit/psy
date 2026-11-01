<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$gdpsy_data = gdpsy_interactive_data( get_the_ID(), false );
$gdpsy_has_image = has_post_thumbnail();
$gdpsy_goals = array_filter( array_map( 'trim', explode( ' · ', $gdpsy_data['goal_label'] ) ) );
?>
<article class="gz-interactive-card<?php echo ! empty( $args['carousel'] ) ? ' gz-article' : ''; ?>">
	<a class="gz-interactive-card-link" href="<?php the_permalink(); ?>" aria-labelledby="gz-interactive-card-<?php the_ID(); ?>">
		<?php if ( $gdpsy_has_image ) : ?><figure class="gz-interactive-card-image"><?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); ?></figure><?php endif; ?>
		<div class="gz-interactive-card-copy">
			<h2 id="gz-interactive-card-<?php the_ID(); ?>"><?php the_title(); ?></h2>
			<?php if ( $gdpsy_data['summary'] !== '' ) : ?><p><?php echo esc_html( wp_trim_words( $gdpsy_data['summary'], 32 ) ); ?></p><?php endif; ?>
			<?php if ( $gdpsy_goals ) : ?><ul class="gz-interactive-card-tags"><?php foreach ( $gdpsy_goals as $gdpsy_goal ) : ?><li><?php echo esc_html( $gdpsy_goal ); ?></li><?php endforeach; ?></ul><?php endif; ?>
		</div>
	</a>
	<div class="gz-interactive-card-footer"><div class="gz-interactive-card-actions"><?php gdpsy_interactive_save_button( get_the_ID(), true ); ?></div><span class="gz-interactive-card-price"><?php echo esc_html( gdpsy_interactive_price( $gdpsy_data ) ); ?></span><span class="gz-interactive-card-more" aria-hidden="true">Подробнее</span></div>
</article>
