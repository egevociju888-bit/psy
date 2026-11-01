<?php
/* Template Name: Возможность — подробное описание */
get_header();
if ( gdpsy_is_blog_possibility() || gdpsy_is_library_possibility() || gdpsy_is_courses_possibility() ) {
	?>
	<main class="gz-article-page gz-possibility-detail-page" id="gz-main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'template-parts/possibility-' . gdpsy_possibility_key( get_the_ID() ) ); ?>
			<?php if ( ! post_password_required() && (int) get_query_var( 'page' ) < 2 ) { get_template_part( 'template-parts/related-possibilities' ); } ?>
		<?php endwhile; ?>
	</main>
	<?php
	get_footer();
	return;
}
?>
<main class="gz-page-main gz-page-main--shell gz-inner gz-possibility-page" id="gz-main">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
		$gdpsy_key = gdpsy_possibility_key( get_the_ID() );
		$gdpsy_features = gdpsy_possibilities();
		$gdpsy_feature = $gdpsy_features[ $gdpsy_key ] ?? null;
		$gdpsy_first_page = (int) get_query_var( 'page' ) < 2;
		?>
		<article <?php post_class( 'gz-page-article' ); ?>>
			<?php get_template_part( 'template-parts/page-header', null, array( 'title' => $gdpsy_feature['title'] ?? 'Возможность', 'intro' => $gdpsy_feature['lead'] ?? '' ) ); ?>
			<?php if ( post_password_required() ) : ?>
				<div class="gz-entry"><?php the_content(); ?></div>
			<?php else : ?>
				<?php if ( ! empty( $gdpsy_feature['upcoming'] ) ) : ?><p class="gz-possibility-status">В разработке · дата запуска пока не определена</p><?php endif; ?>
				<?php if ( has_post_thumbnail() && $gdpsy_first_page ) : ?><figure class="gz-possibility-cover"><?php the_post_thumbnail( 'large' ); ?></figure><?php endif; ?>
				<div class="gz-entry gz-possibility-copy">
					<?php if ( trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) ) !== '' || ! $gdpsy_feature ) : ?>
						<?php the_content(); ?>
					<?php elseif ( $gdpsy_first_page ) : ?>
						<?php foreach ( $gdpsy_feature['story'] as $gdpsy_paragraph ) : ?><p><?php echo esc_html( $gdpsy_paragraph ); ?></p><?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
				<?php if ( $gdpsy_feature && $gdpsy_first_page ) : ?>
					<section class="gz-possibility-included" aria-labelledby="gz-possibility-included-title">
						<h2 id="gz-possibility-included-title"><?php echo ! empty( $gdpsy_feature['upcoming'] ) ? 'Планируемые возможности' : 'Что входит'; ?></h2>
						<div class="gz-possibility-features">
							<?php foreach ( $gdpsy_feature['features'] as $gdpsy_item ) : ?>
								<?php $gdpsy_price = isset( $gdpsy_item['price'] ) ? gdpsy_price_label( $gdpsy_item['price'] ) : ''; ?>
								<section class="gz-possibility-feature">
									<div class="gz-possibility-feature-head"><h3><?php echo esc_html( $gdpsy_item['title'] ); ?></h3><?php if ( $gdpsy_price !== '' ) : ?><p class="gz-possibility-price"><span class="gz-sr-only">Стоимость подключения: </span><?php echo esc_html( $gdpsy_price ); ?></p><?php endif; ?></div>
									<p><?php echo esc_html( $gdpsy_item['text'] ); ?></p>
								</section>
							<?php endforeach; ?>
						</div>
						<?php if ( ! empty( $gdpsy_feature['note'] ) ) : ?><p class="gz-possibility-note"><?php echo esc_html( $gdpsy_feature['note'] ); ?></p><?php endif; ?>
					</section>
					<?php get_template_part( 'template-parts/possibility-examples', null, array( 'key' => $gdpsy_key ) ); ?>
				<?php endif; ?>
				<?php if ( $gdpsy_feature ) : ?>
					<section class="gz-possibility-offer" aria-labelledby="gz-possibility-offer-title">
						<div><h2 id="gz-possibility-offer-title"><?php echo ! empty( $gdpsy_feature['upcoming'] ) ? 'Какие возможности нужны вашей практике?' : 'Обсудим вашу задачу'; ?></h2><p>Расскажите о своей практике и о том, что хотите добавить на сайт.</p></div>
						<a class="gz-primary gz-button gz-button--navy" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr( 'Здравствуйте, Анастасия! Хочу обсудить: ' . $gdpsy_feature['title'] . '.' ); ?>">Рассказать о задаче</a>
					</section>
				<?php endif; ?>
			<?php endif; ?>
			<?php $gdpsy_overview = gdpsy_page_url( 'possibilities' ); if ( $gdpsy_overview !== '' ) : ?><nav class="gz-possibility-back" aria-label="Обзор возможностей"><a class="gz-text-action" href="<?php echo esc_url( $gdpsy_overview ); ?>">← Все возможности сайта</a></nav><?php endif; ?>
		</article>
		<?php if ( ! post_password_required() && $gdpsy_first_page ) { get_template_part( 'template-parts/related-possibilities' ); } ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
