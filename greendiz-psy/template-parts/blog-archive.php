<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$content = gdpsy_blog_archive_content();
$category_id = is_category() ? get_queried_object_id() : 0;
?>
<main class="gz-blog-archive" id="gz-main">
<div class="gz-inner"><div class="gz-breadcrumbs">
<?php get_template_part( 'template-parts/blog-breadcrumbs' ); ?>
<span>Статьи о сайте для вашей практики</span>
</div></div>
<div class="gz-inner gz-blog-content">
<header class="gz-blog-intro<?php echo trim( wp_strip_all_tags( $content['intro'] ) ) !== '' ? ' gz-blog-intro--split' : ''; ?>"><h1><?php echo esc_html( $content['title'] ); ?></h1>
<?php if ( trim( wp_strip_all_tags( $content['intro'] ) ) !== '' ) : ?><div class="gz-blog-intro-copy"><?php echo $content['intro']; ?></div><?php endif; ?>
</header>
<div class="gz-blog-layout">
<aside class="gz-blog-sidebar" aria-label="Рубрики блога">
<details class="gz-blog-categories gz-quiet-details" data-details-static="(min-width: 1101px)" open>
<summary>Рубрики<span aria-hidden="true">+</span></summary>
<div class="gz-quiet-details-content"><nav aria-label="Категории статей">
<ul class="gz-blog-category-list"><li><a href="<?php echo esc_url( gdpsy_blog_url() ); ?>"<?php echo is_home() ? ' aria-current="page"' : ''; ?>>Все статьи</a></li>
<?php wp_list_categories( array( 'title_li' => '', 'show_count' => false, 'hide_empty' => false, 'current_category' => $category_id ) ); ?>
</ul>
</nav></div>
</details>
</aside>
<div class="gz-blog-results">
<?php if ( have_posts() ) : global $wp_query; ?>
<p class="gz-blog-count">Статей: <?php echo absint( $wp_query->found_posts ); ?></p>
<div class="gz-blog-list"><?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/blog-card' ); endwhile; ?></div>
<?php $pagination = paginate_links( array( 'type' => 'list', 'mid_size' => 1, 'end_size' => 1, 'prev_text' => '← Предыдущая', 'next_text' => 'Следующая →', 'before_page_number' => '<span class="gz-sr-only">Страница </span>' ) ); if ( $pagination ) : ?><nav class="gz-blog-pagination" aria-label="Страницы статей"><?php echo wp_kses_post( $pagination ); ?></nav><?php endif; ?>
<?php else : ?>
<div class="gz-blog-empty"><h2><?php echo is_category() ? 'В этой рубрике пока нет статей' : 'Статей пока нет'; ?></h2><?php if ( is_category() ) : ?><a class="gz-text-action" href="<?php echo esc_url( gdpsy_blog_url() ); ?>">Все статьи</a><?php endif; ?></div>
<?php endif; ?>
</div>
</div>
<?php if ( trim( wp_strip_all_tags( $content['seo'] ) ) !== '' ) : ?>
<section class="gz-blog-seo" aria-label="<?php echo is_category() ? 'О рубрике' : 'О блоге'; ?>">
<div class="gz-blog-seo-meta gz-breadcrumbs"><p><?php echo is_category() ? 'О рубрике' : 'О блоге'; ?></p><?php get_template_part( 'template-parts/blog-breadcrumbs' ); ?></div>
<div class="gz-blog-seo-copy gz-entry"><?php echo $content['seo']; ?></div>
</section>
<?php endif; ?>
</div>
</main>
