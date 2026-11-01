<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$articles = $args['articles'] ?? array();
$related = ! empty( $args['related'] );
$gdpsy_articles_number = is_front_page() ? '08' : '09';
if ( ! $articles ) {
	return;
}
?>
<section class="gz-articles gz-articles-cards gz-inner" id="gz-articles" <?php echo $related ? '' : 'data-screen-number="' . esc_attr( $gdpsy_articles_number ) . '"'; ?> aria-labelledby="gz-articles-title">
        <div class="gz-screen-meta<?php echo $related ? ' gz-breadcrumbs gz-articles-meta' : ''; ?>">
          <?php if ( $related ) : ?>
          <?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_article_breadcrumbs( get_the_ID() ) ) ); ?>
          <span class="gz-screen-caption">ПОЛЕЗНОЕ</span>
          <?php else : ?>
          <div class="gz-index"><span><?php echo esc_html( $gdpsy_articles_number ); ?></span><span class="gz-screen-slash" aria-hidden="true">/</span><span>ПОЛЕЗНОЕ</span></div>
          <div class="gz-screen-caption">СТАТЬИ О САЙТЕ</div>
          <?php endif; ?>
        </div>
        <div class="gz-article-intro">
          <h2 class="gz-screen-title" id="gz-articles-title">
            <span class="gz-title-main"><?php echo $related ? 'Другие' : 'О сайте для'; ?></span>
            <em class="gz-title-accent"><?php echo $related ? 'статьи' : 'вашей практики'; ?></em>
          </h2>
          <a class="gz-inline-link gz-button gz-button--white" href="<?php echo esc_url( gdpsy_blog_url() ); ?>">Все
            статьи</a>
        </div>
        <div class="gz-articles-carousel">
          <div class="gz-articles-viewport" id="gz-articles-viewport" tabindex="0" role="region" aria-label="Статьи о сайте: горизонтальная лента">
            <div class="gz-articles-track">
              <?php foreach ( $articles as $article ) : $categories = get_the_category( $article->ID ); $title_id = 'gz-article-' . absint( $article->ID ); ?>
<a class="gz-article gz-related-card" href="<?php echo esc_url( get_permalink( $article ) ); ?>" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
<div class="gz-related-project-preview"><?php if ( has_post_thumbnail( $article ) ) : echo get_the_post_thumbnail( $article, 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '', 'sizes' => '(min-width: 1201px) 45vw, (min-width: 1001px) 66vw, 76vw' ) ); else : ?><span>Обложка готовится</span><?php endif; ?></div>
<div class="gz-related-project-copy">
<div class="gz-reserved-row"><span class="gz-related-project-type"><?php echo esc_html( $categories ? $categories[0]->name : 'Статья' ); ?></span></div>
<h3 id="<?php echo esc_attr( $title_id ); ?>"><?php echo esc_html( get_the_title( $article ) ); ?></h3>
<p><?php echo esc_html( wp_trim_words( gdpsy_text( get_the_excerpt( $article ) ), 24 ) ); ?></p>
<div class="gz-related-project-footer"><div class="gz-related-project-more"><span aria-hidden="true">Читать</span></div></div>
</div></a>
<?php endforeach; ?>
            </div>
          </div>
          <div class="gz-articles-controls" hidden="">
            <div class="gz-articles-progress" aria-hidden="true"><span class="gz-articles-progress-thumb"></span></div>
            <div class="gz-articles-arrows">
              <button class="gz-articles-arrow gz-icon-action" type="button" data-articles-prev="" aria-label="Предыдущая статья" aria-controls="gz-articles-viewport" disabled="">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7" /></svg>
              </button>
              <button class="gz-articles-arrow gz-icon-action" type="button" data-articles-next="" aria-label="Следующая статья" aria-controls="gz-articles-viewport">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7" /></svg>
              </button>
            </div>
          </div>
          <p class="gz-sr-only" id="gz-articles-status" aria-live="polite" aria-atomic="true"></p>
        </div>
      </section>
