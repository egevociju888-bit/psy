<?php
$gdpsy_document = gdpsy_interactive_document( get_queried_object_id() );
get_header( $gdpsy_document ? 'interactive' : null );
?>
<main class="gz-interactive-single" id="gz-main">
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class( 'gz-interactive-detail' ); ?> aria-labelledby="gz-interactive-title">
		<div class="gz-interactive-dark" id="gz-concept-head">
			<div class="gz-inner gz-breadcrumbs"><?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_interactive_breadcrumbs( get_the_ID() ) ) ); ?></div>
			<?php if ( post_password_required() ) : ?>
			<header class="gz-blog-intro gz-inner"><h1 id="gz-interactive-title"><?php the_title(); ?></h1></header>
			<div class="gz-entry gz-inner"><?php echo get_the_password_form(); ?></div>
		</div>
			<?php else : ?>
			<?php
			$gdpsy_data = gdpsy_interactive_data( get_the_ID() );
			$gdpsy_offer = gdpsy_interactive_offer();
			ob_start();
			get_template_part( 'template-parts/interactive-demo', null, array( 'post_id' => get_the_ID() ) );
			$gdpsy_demo = trim( ob_get_clean() );
			?>
			<header class="gz-blog-intro gz-interactive-hero gz-inner">
				<div class="gz-article-heading">
					<?php if ( $gdpsy_data['goal_label'] !== '' ) : ?><p class="gz-eyebrow"><?php echo esc_html( $gdpsy_data['goal_label'] ); ?></p><?php endif; ?>
					<h1 id="gz-interactive-title"><?php the_title(); ?></h1>
				</div>
				<?php if ( $gdpsy_data['summary'] !== '' ) : ?><div class="gz-blog-intro-copy"><?php echo wpautop( esc_html( $gdpsy_data['summary'] ) ); ?></div><?php endif; ?>
				<div class="gz-interactive-demo-toolbar">
					<?php if ( $gdpsy_demo !== '' ) : ?><p>Рабочая демонстрация <span>Попробуйте прямо на странице</span></p><?php endif; ?>
					<div class="gz-interactive-demo-actions">
						<?php if ( $gdpsy_document ) : ?><button class="gz-button gz-button--outline" type="button" data-view hidden><span data-view-text>Посмотреть мобильную версию</span></button><?php endif; ?>
						<?php gdpsy_interactive_save_button( get_the_ID(), true ); ?>
					</div>
				</div>
			</header>
		</div>
		<?php if ( $gdpsy_demo !== '' ) : ?>
		<section class="gz-interactive-demo" data-interactive-preview aria-label="Демонстрация интерактива">
			<div class="gz-interactive-preview-frame"><?php echo $gdpsy_demo; ?></div>
		</section>
		<?php endif; ?>
		<div class="gz-interactive-dark" id="gz-concept-end">
			<div class="gz-inner gz-interactive-description">
				<div class="gz-interactive-details-grid<?php echo ( $gdpsy_data['scenario'] === '' && $gdpsy_data['basis_ref'] === '' ) ? ' gz-interactive-details-grid--single' : ''; ?>">
				<div class="gz-interactive-editorial">
				<?php if ( $gdpsy_data['scenario'] !== '' ) : ?>
				<section class="gz-interactive-usage" aria-labelledby="gz-interactive-scenario">
					<p class="gz-eyebrow">В вашей практике</p>
					<h2 id="gz-interactive-scenario">Где и зачем использовать</h2>
					<div class="gz-interactive-prose"><?php echo $gdpsy_data['scenario']; ?></div>
				</section>
				<?php endif; ?>
				<?php if ( $gdpsy_data['basis_ref'] !== '' ) : ?>
				<section aria-labelledby="gz-interactive-basis">
					<h2 id="gz-interactive-basis">Об основе инструмента</h2>
					<div class="gz-interactive-prose"><?php echo $gdpsy_data['basis_ref']; ?></div>
				</section>
				<?php endif; ?>
				</div>
				<section class="gz-interactive-offer" aria-labelledby="gz-interactive-cost">
					<h2 id="gz-interactive-cost">Подключение и стоимость</h2>
					<p class="gz-interactive-price"><?php echo esc_html( gdpsy_interactive_price( $gdpsy_data ) ); ?></p>
					<p>За один дополнительный интерактив при создании сайта.</p>
					<div class="gz-interactive-offer-adaptation">
						<h3 id="gz-interactive-adaptation">Адаптация под ваш сайт</h3>
						<p>Подберём цвета, шрифты и расположение. Изменения текстов, логики и дополнительные возможности согласуем до начала работы.</p>
					</div>
					<p class="gz-interactive-offer-note"><?php echo esc_html( $gdpsy_offer['note'] ); ?></p>
					<a class="gz-button" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr( 'Здравствуйте, Анастасия! Хочу обсудить подключение интерактива «' . gdpsy_text( get_the_title() ) . '» к моему сайту.' ); ?>" aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить подключение</a>
				</section>
				</div>
				<?php if ( $gdpsy_data['concepts'] || $gdpsy_data['projects'] ) : ?>
				<div class="gz-interactive-connections">
					<?php if ( $gdpsy_data['concepts'] ) : ?>
					<section aria-labelledby="gz-interactive-concepts">
					<h2 id="gz-interactive-concepts">Уже включён в концепт</h2>
					<p>Интерактив входит в стоимость выбранного концепта.</p><ul>
						<?php foreach ( $gdpsy_data['concepts'] as $gdpsy_concept ) : ?><li><a href="<?php echo esc_url( get_permalink( $gdpsy_concept ) ); ?>"><?php echo esc_html( get_the_title( $gdpsy_concept ) ); ?></a></li><?php endforeach; ?>
					</ul></section><?php endif; ?>
					<?php if ( $gdpsy_data['projects'] ) : ?>
					<section aria-labelledby="gz-interactive-projects">
					<h2 id="gz-interactive-projects">Примеры использования</h2>
					<p>Посмотрите, как инструмент используется в проекте.</p><ul>
						<?php foreach ( $gdpsy_data['projects'] as $gdpsy_project ) : ?><li><a href="<?php echo esc_url( get_permalink( $gdpsy_project ) ); ?>"><?php echo esc_html( get_the_title( $gdpsy_project ) ); ?></a></li><?php endforeach; ?>
					</ul></section><?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			<?php get_template_part( 'template-parts/interactive-related' ); ?>
		</div>
		<?php endif; ?>
	</article>
<?php endwhile; ?>
</main>
<?php
get_footer( $gdpsy_document ? 'interactive' : null );
?>
