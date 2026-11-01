<?php
/* Template Name: FAQ */
get_header();
?>
<main class="gz-article-page gz-faq-page" id="gz-main">
<?php while ( have_posts() ) : the_post();
	$post_id = get_the_ID();
	$protected = post_password_required( $post_id );
	$first_page = (int) get_query_var( 'page' ) < 2;
	$sections = $protected || ! $first_page ? array() : gdpsy_faq_sections( $post_id );
	$toc = array_values( array_filter( $sections, function ( $section ) {
		return $section['title'] !== '';
	} ) );
	$intro = $protected || ! $first_page ? '' : (string) get_post_field( 'post_excerpt', $post_id, 'raw' );
	if ( ! $protected && $first_page && trim( wp_strip_all_tags( $intro ) ) === '' ) {
		$intro = 'С чего начать, что подготовить и как пользоваться сайтом после запуска. Ответы на вопросы, которые помогают определиться до начала работы.';
	}
?>
<div class="gz-inner"><div class="gz-breadcrumbs">
	<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_page_breadcrumbs( $post_id ) ) ); ?>
</div></div>
<article <?php post_class( 'gz-article-single gz-inner' ); ?> aria-labelledby="gz-faq-title">
	<header class="gz-blog-intro">
		<div class="gz-article-heading"><h1 id="gz-faq-title"><?php the_title(); ?></h1></div>
		<?php if ( trim( wp_strip_all_tags( $intro ) ) !== '' ) : ?>
			<div class="gz-blog-intro-copy"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div>
		<?php endif; ?>
	</header>
	<div class="gz-article-layout<?php echo $toc ? '' : ' gz-faq-layout--plain'; ?>">
		<?php if ( $toc ) : ?>
			<aside class="gz-article-sidebar" aria-label="Навигация по вопросам"><div class="gz-article-sticky">
				<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
					<summary id="gz-article-toc-title">Содержание<span aria-hidden="true">+</span></summary>
					<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-toc-title"><ol class="gz-article-toc">
						<?php foreach ( $toc as $section ) : ?>
							<li><a href="<?php echo esc_url( '#' . rawurlencode( $section['id'] ) ); ?>"><?php echo esc_html( preg_replace( '/^\d+(?:\.\d+)*[.)]\s+/u', '', $section['title'] ) ); ?></a></li>
						<?php endforeach; ?>
					</ol></nav></div>
				</details>
			</div></aside>
		<?php endif; ?>
		<div class="gz-article-body">
			<div class="gz-entry"><?php the_content(); ?></div>
			<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы вопросов">', 'after' => '</nav>' ) ); ?>
			<div class="gz-faq-page-list">
				<?php foreach ( $sections as $section ) : ?>
					<section class="gz-faq-section" id="<?php echo esc_attr( $section['id'] ); ?>" tabindex="-1">
						<?php if ( $section['title'] !== '' ) : ?><h2><?php echo esc_html( $section['title'] ); ?></h2><?php endif; ?>
						<?php foreach ( $section['questions'] as $question ) : ?>
							<details class="gz-quiet-details gz-faq-question">
								<summary><?php echo esc_html( $question['question'] ); ?><span aria-hidden="true">+</span></summary>
								<div class="gz-faq-answer gz-entry"><?php echo wp_kses_post( wpautop( $question['answer'] ) ); ?></div>
							</details>
						<?php endforeach; ?>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</article>
<?php if ( ! $protected && $first_page ) : ?>
	<div class="gz-article-cta-wrap gz-inner">
		<section class="gz-article-cta" aria-labelledby="gz-faq-cta-title">
			<div><span class="sub">О вашей задаче</span>
				<h2 id="gz-faq-cta-title">Не нашли <em>ответ?</em></h2>
				<p>Задайте свой вопрос — обсудим, что нужно сайту вашей практики.</p>
			</div>
			<a class="gz-primary gz-button gz-button--large" href="<?php echo esc_url( home_url( '/#gz-contact' ) ); ?>" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog">Задать вопрос</a>
		</section>
	</div>
<?php endif; ?>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
