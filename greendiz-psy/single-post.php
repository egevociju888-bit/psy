<?php
get_header();
?>
<main class="gz-article-page" id="gz-main">
<?php while ( have_posts() ) : the_post();
	$post_id = get_the_ID();
	$protected = post_password_required( $post_id );
	$content = apply_filters( 'the_content', get_the_content() );
	$document = $protected ? array( 'html' => $content, 'toc' => array() ) : gdpsy_article_document( $content, $post_id );
	$intro = $protected ? '' : get_post_field( 'post_excerpt', $post_id );
	$reading_minutes = $protected ? 0 : gdpsy_article_reading_minutes( $content, $intro );
	$tags = $protected ? array() : get_the_tags( $post_id );
	$current_category = gdpsy_article_category( $post_id );
?>
<div class="gz-inner"><div class="gz-breadcrumbs">
<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_article_breadcrumbs( $post_id ) ) ); ?>
<span>Статьи о сайте для вашей практики</span>
</div></div>
<article <?php post_class( 'gz-article-single gz-inner' ); ?> aria-labelledby="gz-article-title">
<header class="gz-blog-intro">
<div class="gz-article-heading">
<?php if ( $reading_minutes || ( $tags && ! is_wp_error( $tags ) ) ) : ?>
<div class="gz-article-meta">
<?php if ( $reading_minutes ) : ?><span class="gz-article-reading">≈ <?php echo absint( $reading_minutes ); ?> мин чтения</span><?php endif; ?>
<?php if ( $tags && ! is_wp_error( $tags ) ) : ?><ul class="gz-article-tags" aria-label="Теги статьи">
<?php foreach ( $tags as $tag ) : $tag_url = get_tag_link( $tag->term_id ); if ( is_wp_error( $tag_url ) ) { continue; } ?>
<li><a href="<?php echo esc_url( $tag_url ); ?>" rel="tag">#<?php echo esc_html( $tag->name ); ?></a></li>
<?php endforeach; ?>
</ul><?php endif; ?>
</div>
<?php endif; ?>
<h1 id="gz-article-title"><?php the_title(); ?></h1>
</div>
<?php if ( trim( wp_strip_all_tags( $intro ) ) !== '' ) : ?><div class="gz-blog-intro-copy"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div><?php endif; ?>
</header>
<div class="gz-article-layout">
<aside class="gz-article-sidebar" aria-label="Навигация по статье и блогу"><div class="gz-article-sticky">
<?php if ( $document['toc'] ) : ?>
<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
<summary id="gz-article-toc-title">Содержание<span aria-hidden="true">+</span></summary>
<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-toc-title"><ol class="gz-article-toc">
<?php foreach ( $document['toc'] as $item ) : ?><li<?php echo $item['level'] === 3 ? ' class="gz-article-toc-sub"' : ''; ?>><a href="<?php echo esc_url( '#' . rawurlencode( $item['id'] ) ); ?>"><?php echo esc_html( preg_replace( '/^\d+(?:\.\d+)*[.)]\s+/u', '', $item['title'] ) ); ?></a></li><?php endforeach; ?>
</ol></nav></div>
</details>
<?php endif; ?>
<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
<summary id="gz-article-categories-title">Рубрики<span aria-hidden="true">+</span></summary>
<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-categories-title"><ul class="gz-blog-category-list">
<li><a href="<?php echo esc_url( gdpsy_blog_url() ); ?>">Все статьи</a></li>
<?php wp_list_categories( array( 'title_li' => '', 'show_count' => false, 'hide_empty' => false, 'current_category' => $current_category ? $current_category->term_id : 0 ) ); ?>
</ul></nav></div>
</details>
</div></aside>
<div class="gz-article-body">
<?php if ( ! $protected && has_post_thumbnail( $post_id ) ) : ?><div class="gz-article-cover"><?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'eager', 'decoding' => 'async', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1800px) 1080px, (min-width: 1100px) 66vw, calc(100vw - 48px)' ) ); ?></div><?php endif; ?>
<div class="gz-entry" id="gz-article-content"><?php echo $document['html']; ?></div>
<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы статьи">', 'after' => '</nav>' ) ); ?>
</div>
</div>
</article>
<?php if ( ! $protected ) : ?>
<div class="gz-article-cta-wrap gz-inner">
<section class="gz-article-cta" aria-labelledby="gz-article-cta-title">
<div><span class="sub">От статьи — к вашему сайту</span>
<h2 id="gz-article-cta-title">Нужен сайт <em>для вашей практики?</em></h2>
<p>Обсудим, как применить эти идеи и что нужно вашему сайту.</p></div>
<a class="gz-primary gz-button gz-button--large" href="<?php echo esc_url( home_url( '/#gz-contact' ) ); ?>" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить сайт</a>
</section>
</div>
<?php endif; ?>
<?php get_template_part( 'template-parts/articles', null, array( 'articles' => gdpsy_related_articles( $post_id ), 'related' => true ) ); ?>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
