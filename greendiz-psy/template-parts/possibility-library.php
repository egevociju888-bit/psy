<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_full = ! post_password_required() && (int) get_query_var( 'page' ) < 2;
$gdpsy_tools = gdpsy_interactive_offer();
$gdpsy_toc = array(
	'gz-library-tools' => 'Интерактивы на сайте',
	'gz-library-adaptation' => 'Подключение и адаптация',
	'gz-library-app' => 'Приложение на телефоне',
);
?>
<div class="gz-inner"><div class="gz-breadcrumbs"><?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_page_breadcrumbs( get_the_ID() ) ) ); ?></div></div>
<article <?php post_class( 'gz-article-single gz-inner gz-possibility-detail' ); ?> aria-labelledby="gz-possibility-title">
	<header class="gz-blog-intro">
		<div class="gz-article-heading"><h1 id="gz-possibility-title"><?php the_title(); ?></h1></div>
		<?php if ( $gdpsy_full ) : ?><div class="gz-blog-intro-copy"><p><?php echo esc_html( $gdpsy_tools['summary'] ); ?></p></div><?php endif; ?>
	</header>
	<?php if ( $gdpsy_full ) : ?>
	<div class="gz-article-layout">
		<aside class="gz-article-sidebar" aria-label="Навигация по странице"><div class="gz-article-sticky">
			<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
				<summary id="gz-article-toc-title">Содержание<span aria-hidden="true">+</span></summary>
				<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-toc-title"><ol class="gz-article-toc">
					<?php foreach ( $gdpsy_toc as $gdpsy_id => $gdpsy_label ) : ?><li><a href="#<?php echo esc_attr( $gdpsy_id ); ?>"><?php echo esc_html( $gdpsy_label ); ?></a></li><?php endforeach; ?>
				</ol></nav></div>
			</details>
		</div></aside>
		<div class="gz-article-body"><div class="gz-entry gz-possibility-copy">
			<?php if ( trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) ) !== '' ) : ?><div class="gz-possibility-editor"><?php the_content(); ?></div><?php endif; ?>
			<section class="gz-possibility-section">
				<h2 id="gz-library-tools" tabindex="-1">Интерактивы на вашем сайте</h2>
				<p><?php echo esc_html( $gdpsy_tools['story'][0] ); ?></p>
				<p>Короткая практика может дополнить знакомство с вами, а тест или инструмент для самонаблюдения — стать материалом, которым вы делитесь с клиентами между встречами.</p>
				<p><a class="gz-text-action" href="<?php echo esc_url( home_url( '/interactive/' ) ); ?>">Каталог интерактивов</a></p>
			</section>
			<section class="gz-possibility-section">
				<h2 id="gz-library-adaptation" tabindex="-1">Подключение и адаптация</h2>
				<p><?php echo esc_html( $gdpsy_tools['features'][0]['text'] ); ?></p>
				<?php $gdpsy_price = gdpsy_price_label( 'toolkit' ); if ( $gdpsy_price !== '' ) : ?><p class="gz-possibility-price"><?php echo esc_html( $gdpsy_price ); ?>&nbsp;/ дополнительный интерактив</p><?php else : ?><p>Стоимость уточняется при подборе.</p><?php endif; ?>
				<p><?php echo esc_html( $gdpsy_tools['note'] ); ?></p>
				<p>Один интерактив предусмотрен в составе лендинга. Количество дополнительных инструментов и организацию отдельного каталога согласуем до начала работ.</p>
			</section>
			<section class="gz-possibility-section">
				<h2 id="gz-library-app" tabindex="-1">Приложение на телефоне</h2>
				<p><?php echo esc_html( $gdpsy_tools['features'][2]['text'] ); ?></p>
				<p>Примеры практик можно попробовать в приложении «Вдох». Это демонстрация возможных сценариев; состав вашего сайта определим отдельно.</p>
				<p><a class="gz-text-action" href="https://vdoh.greendiz.ru/" target="_blank" rel="noopener noreferrer">Посмотреть «Вдох» <span aria-hidden="true">↗</span></a></p>
			</section>
		</div>
		<section class="gz-article-cta gz-possibility-cta" aria-labelledby="gz-possibility-cta-title">
			<div><h2 id="gz-possibility-cta-title">Подберём инструменты <em>для вашей практики</em></h2><p>Расскажите, что клиенту важно попробовать на сайте и к каким материалам он будет возвращаться.</p></div>
			<a class="gz-button gz-button--navy" href="<?php echo esc_url( home_url( '/#gz-contact' ) ); ?>" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу подобрать интерактивы для моего сайта." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить подключение</a>
		</section></div>
	</div>
	<?php else : ?><div class="gz-entry"><?php the_content(); ?></div><?php endif; ?>
	<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
</article>
