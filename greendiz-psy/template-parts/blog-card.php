<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_id = get_the_ID();
$protected = post_password_required( $post_id );
$has_cover = ! $protected && has_post_thumbnail( $post_id );
$categories = get_the_category( $post_id );
?>
<article class="gz-blog-post">
<a class="gz-blog-card<?php echo $has_cover ? '' : ' gz-blog-card--text'; ?>" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" aria-labelledby="gz-blog-post-<?php echo absint( $post_id ); ?>">
<?php if ( $has_cover ) : ?><div class="gz-blog-cover"><?php echo get_the_post_thumbnail( $post_id, 'medium_large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '', 'sizes' => '(min-width: 1100px) 360px, (min-width: 761px) 36vw, calc(100vw - 48px)' ) ); ?></div><?php endif; ?>
<div class="gz-blog-card-copy">
<?php if ( $categories ) : ?><p class="gz-blog-category"><?php echo esc_html( $categories[0]->name ); ?></p><?php endif; ?>
<h2 id="gz-blog-post-<?php echo absint( $post_id ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></h2>
<?php $excerpt = $protected ? 'Для просмотра записи введите пароль.' : wp_trim_words( gdpsy_text( get_the_excerpt() ), 40 ); if ( $excerpt !== '' ) : ?><p class="gz-blog-excerpt"><?php echo esc_html( $excerpt ); ?></p><?php endif; ?>
<div class="gz-blog-card-footer" aria-hidden="true"><span class="gz-blog-card-more">Читать статью</span></div>
</div>
</a>
</article>
