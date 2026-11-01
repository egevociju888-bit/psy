<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_full = ! post_password_required() && (int) get_query_var( 'page' ) < 2;
$gdpsy_intro = $gdpsy_full ? (string) get_post_field( 'post_excerpt', get_the_ID(), 'raw' ) : '';
if ( $gdpsy_full && trim( wp_strip_all_tags( $gdpsy_intro ) ) === '' ) {
	$gdpsy_intro = 'Блог с пятью стартовыми статьями, анонсы в ваших каналах и письма подписчикам. Можно подключить вместе или выбрать нужное.';
}
$gdpsy_toc = array(
	'gz-possibility-blog'      => 'Блог на вашем сайте',
	'gz-possibility-materials' => 'Стартовые материалы',
	'gz-possibility-search'    => 'Поиск и нейросети',
	'gz-possibility-autopost'  => 'Автопубликация',
	'gz-possibility-mail'      => 'Письма подписчикам',
	'gz-possibility-prices'    => 'Состав и стоимость',
	'gz-possibility-process'   => 'Как начнём работу',
);
$gdpsy_contact_url = home_url( '/#gz-contact' );
$gdpsy_contact_message = 'Здравствуйте, Анастасия! Хочу обсудить блог и продвижение на моём сайте.';
$gdpsy_price_page = get_page_by_path( 'price' );
$gdpsy_price_url = $gdpsy_price_page && $gdpsy_price_page->post_status === 'publish' && $gdpsy_price_page->post_password === '' ? get_permalink( $gdpsy_price_page ) : '';
$gdpsy_features = gdpsy_possibilities();
$gdpsy_breadcrumbs = gdpsy_page_breadcrumbs( get_the_ID() );
array_pop( $gdpsy_breadcrumbs );
?>
<article <?php post_class( 'gz-inner gz-possibilities-article gz-possibilities-design gz-possibility-detail--blog' ); ?> aria-labelledby="gz-possibility-title">
	<div class="gz-breadcrumbs gz-page-breadcrumbs">
		<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => $gdpsy_breadcrumbs ) ); ?>
	</div>
	<header class="gz-page-heading">
		<h1 id="gz-possibility-title"><?php the_title(); ?></h1>
		<?php if ( $gdpsy_intro !== '' ) : ?><div class="gz-page-intro"><?php echo wp_kses_post( wpautop( $gdpsy_intro ) ); ?></div><?php endif; ?>
	</header>
	<?php if ( $gdpsy_full ) : ?>
	<div class="gz-article-layout">
		<aside class="gz-article-sidebar" aria-label="Навигация по странице">
			<div class="gz-article-sticky">
				<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
					<summary id="gz-article-toc-title">На этой странице<span aria-hidden="true">+</span></summary>
					<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-toc-title"><ol class="gz-article-toc">
						<?php foreach ( $gdpsy_toc as $gdpsy_id => $gdpsy_label ) : ?>
						<li><a href="#<?php echo esc_attr( $gdpsy_id ); ?>"><?php echo esc_html( $gdpsy_label ); ?></a></li>
						<?php endforeach; ?>
					</ol></nav></div>
				</details>
			</div>
		</aside>
		<div class="gz-article-body">
			<div class="gz-overview-body">
				<?php if ( trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) ) !== '' ) : ?><div class="gz-entry gz-overview-editor"><?php the_content(); ?></div><?php endif; ?>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-blog">
					<p class="gz-eyebrow">01 / Знакомство с вашим подходом</p>
					<h2 id="gz-possibility-blog" tabindex="-1">Блог на вашем сайте</h2>
					<p>Статьи помогают человеку понять, как вы смотрите на знакомую ему проблему, чего ждать от работы с вами и с каким вопросом можно обратиться. Ссылкой на материал удобно поделиться в канале или отправить клиенту после встречи.</p>
					<p>В блоге материалы собраны в каталог: можно выбрать рубрику, воспользоваться поиском и найти публикацию по интересующей теме.</p>
					<div class="gz-possibility-split">
						<figure class="gz-possibility-visual">
							<img src="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/blog.webp' ) ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/blog-small.webp' ) ); ?> 640w, <?php echo esc_url( get_theme_file_uri( 'media/possibilities/blog.webp' ) ); ?> 1200w" sizes="(min-width: 1100px) 20vw, (min-width: 760px) 28vw, calc(100vw - 88px)" width="1200" height="800" alt="" decoding="async" loading="lazy">
						</figure>
						<div>
							<p>Я оформлю раздел в стиле вашего сайта: каталог, рубрики, поиск и страницы статей. Подготовлю стартовые материалы и настрою техническую основу для представления публикаций в поиске.</p>
						</div>
					</div>
					<ul class="gz-overview-list">
						<li><strong>Статьи</strong><span>Отдельная страница для каждого материала и ссылка, которой удобно поделиться.</span></li>
						<li><strong>Рубрики</strong><span>Публикации объединены по темам вашей практики.</span></li>
						<li><strong>Поиск</strong><span>Помогает найти нужный материал по словам из запроса.</span></li>
					</ul>
					<p>После передачи сайта вы сможете редактировать тексты и публиковать новые статьи самостоятельно. Я покажу, как это делать.</p>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-materials">
					<p class="gz-eyebrow">02 / Пять стартовых статей</p>
					<h2 id="gz-possibility-materials" tabindex="-1">С чего начать наполнение</h2>
					<p>В подключение блога входят пять статей. Я могу подготовить новые материалы, адаптировать ваши тексты из канала или сочетать оба варианта.</p>
					<dl class="gz-overview-rows">
						<div><dt>Новые материалы</dt><dd>Я подготовлю тексты с учётом тем вашей практики и вопросов будущих читателей. От вас нужны факты, ответы на уточняющие вопросы и проверка готового содержания.</dd></div>
						<div><dt>Ваши тексты из канала</dt><dd>Вы можете передать свои публикации. Я адаптирую их для сайта: выстрою структуру, подготовлю заголовки и оформлю материалы для чтения.</dd></div>
					</dl>
					<p>Темы и формат согласуем перед началом работы. Важно, чтобы статьи точно передавали вашу профессиональную позицию. Дополнительные материалы можно заказать отдельно.</p>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-search">
					<p class="gz-eyebrow">03 / Техническая основа</p>
					<h2 id="gz-possibility-search" tabindex="-1">Подготовка для поиска и нейросетей</h2>
					<p>Статьи на сайте могут находить через поиск, а ссылки на них могут появляться в ответах нейросетей.</p>
					<p>Я настрою понятные адреса страниц и SEO-плагин — инструмент, который помогает управлять представлением сайта в поиске. Это базовая подготовка блога. Появление конкретной статьи в поиске или ответе нейросети зависит также от её содержания, запроса пользователя и работы самого сервиса.</p>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-autopost">
					<p class="gz-eyebrow">04 / Связь с вашими каналами</p>
					<h2 id="gz-possibility-autopost" tabindex="-1">Анонсы в ваших каналах</h2>
					<div class="gz-possibility-split gz-possibility-split--visual-end">
						<div>
							<p>Автопубликация связывает блог с вашими каналами. После выхода новой статьи анонс со ссылкой отправляется в подключённые Telegram, VK и MAX. Читатель видит публикацию в привычном канале и может перейти на сайт за полным текстом.</p>
							<p>Для подключения этой возможности нужен блог. Какие каналы использовать, определим при обсуждении вашего сайта.</p>
							<p class="gz-overview-note">Автопубликация распространяет готовые материалы. Подготовка новых статей и ведение каналов — отдельные задачи.</p>
						</div>
						<figure class="gz-possibility-visual">
							<img src="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/autopost.webp' ) ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/autopost-small.webp' ) ); ?> 640w, <?php echo esc_url( get_theme_file_uri( 'media/possibilities/autopost.webp' ) ); ?> 1200w" sizes="(min-width: 1100px) 20vw, (min-width: 760px) 28vw, calc(100vw - 88px)" width="1200" height="800" alt="" loading="lazy" decoding="async">
						</figure>
					</div>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-mail">
					<p class="gz-eyebrow">05 / Прямой контакт с читателями</p>
					<h2 id="gz-possibility-mail" tabindex="-1">Письма подписчикам</h2>
					<div class="gz-possibility-split gz-possibility-split--visual-end">
						<div>
							<p>Email-рассылка позволяет отправлять материалы людям, которые подписались на ваши письма. Это могут быть новые статьи, тематические подборки или сообщения о ваших программах.</p>
							<p>Я добавлю форму подписки и подготовлю шаблоны писем в стиле сайта. Рассылку можно подключить отдельно от блога.</p>
							<p>Способ работы подберём под ваши задачи: какие письма вы хотите отправлять и как часто. До подключения обсудим удобный сервис, порядок отправки и возможные расходы.</p>
						</div>
						<figure class="gz-possibility-visual">
							<img src="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/mail.webp' ) ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/mail-small.webp' ) ); ?> 640w, <?php echo esc_url( get_theme_file_uri( 'media/possibilities/mail.webp' ) ); ?> 1200w" sizes="(min-width: 1100px) 20vw, (min-width: 760px) 28vw, calc(100vw - 88px)" width="1200" height="800" alt="" loading="lazy" decoding="async">
						</figure>
					</div>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-prices">
					<p class="gz-eyebrow">06 / Выберите нужное</p>
					<h2 id="gz-possibility-prices" tabindex="-1">Что входит и сколько стоит</h2>
					<p>Можно начать с блога, добавить автоматическую публикацию анонсов или подключить email-рассылку отдельно. Возможности доступны при создании сайта и для его дальнейшего развития.</p>
					<dl class="gz-overview-rows">
						<?php foreach ( $gdpsy_features['blog']['features'] as $gdpsy_item ) : $gdpsy_price = gdpsy_price_label( $gdpsy_item['price'] ); ?>
						<div>
							<dt><?php echo esc_html( $gdpsy_item['title'] ); ?></dt>
							<dd><p><?php echo esc_html( $gdpsy_item['text'] ); ?></p>
							<p class="<?php echo $gdpsy_price !== '' ? 'gz-overview-price-inline' : 'gz-overview-note'; ?>"><?php if ( $gdpsy_price !== '' ) : ?><span class="gz-sr-only">Стоимость подключения: </span><?php echo esc_html( $gdpsy_price ); ?><?php else : ?>Стоимость уточняется<?php endif; ?></p></dd>
						</div>
						<?php endforeach; ?>
					</dl>
					<p>Окончательный состав работ согласуем до начала. Если для выбранного решения потребуется платный внешний сервис, заранее обсудим его условия и расходы.</p>
					<?php if ( $gdpsy_price_url !== '' ) : ?>
					<p>Предварительный состав сайта можно собрать на странице <a href="<?php echo esc_url( $gdpsy_price_url ); ?>">«Стоимость»</a>. Дополнительные статьи, ведение каналов и другие работы обсуждаем отдельно.</p>
					<?php else : ?>
					<p>Дополнительные статьи, ведение каналов и другие работы обсуждаем отдельно.</p>
					<?php endif; ?>
				</section>
				<section class="gz-overview-section" aria-labelledby="gz-possibility-process">
					<p class="gz-eyebrow">07 / От задачи до запуска</p>
					<h2 id="gz-possibility-process" tabindex="-1">Как начнём работу</h2>
					<ol class="gz-possibility-steps">
						<li><h3>Обсудим задачи</h3><p>Вы расскажете о своей практике, материалах и каналах, которые уже ведёте. Я помогу определить, какие возможности пригодятся сейчас.</p></li>
						<li><h3>Подготовлю содержание</h3><p>Согласуем темы и формат пяти стартовых статей. Я подготовлю новые тексты или адаптирую ваши материалы, а вы проверите содержание и профессиональные формулировки.</p></li>
						<li><h3>Подключу и покажу</h3><p>Настрою согласованные возможности и покажу, как пользоваться ими после передачи сайта.</p></li>
					</ol>
				</section>
			</div>
			<section class="gz-overview-section gz-possibility-cta" aria-labelledby="gz-possibility-cta-title">
				<div><p class="gz-eyebrow">Возможности для вашей практики</p><h2 id="gz-possibility-cta-title">Обсудим подключение?</h2><p>Расскажите, что хотите добавить на сайт и какие материалы у вас уже есть.</p></div>
				<a class="gz-button gz-button--navy" href="<?php echo esc_url( $gdpsy_contact_url ); ?>" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog" data-contact-preset="<?php echo esc_attr( $gdpsy_contact_message ); ?>">Обсудить подключение</a>
			</section>
		</div>
	</div>
	<?php else : ?>
	<div class="gz-entry"><?php the_content(); ?></div>
	<?php endif; ?>
	<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
</article>
