<?php
/* Template Name: Возможности — полный обзор */
get_header();
$gdpsy_features = gdpsy_possibilities();
$gdpsy_tools = $gdpsy_features['interactions'];
$gdpsy_toc = array(
	'gz-possibilities-base-title' => 'Главная страница',
	'gz-possibility-pages'       => 'Дополнительные страницы',
	'gz-possibility-blog'        => 'Блог, анонсы и рассылка',
	'gz-possibility-interactions' => 'Тесты и интерактивы',
	'gz-possibilities-further'   => 'Другие задачи',
);
$gdpsy_blog_url = gdpsy_possibility_url('blog');
$gdpsy_contact_url = home_url('/#gz-contact');
?>
<main class="gz-article-page gz-possibilities-overview gz-possibilities-design" id="gz-main">
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class('gz-inner gz-possibilities-article'); ?>>
			<?php get_template_part('template-parts/page-header', null, array('title' => 'Возможности вашего сайта', 'intro' => 'Начните с лендинга и добавляйте то, что нужно вашей практике. <br>Дополнительные страницы, блог и интерактивы можно подключить сразу или позже.')); ?>
			<?php if (! post_password_required() && (int) get_query_var('page') < 2) : ?>
				<div class="gz-article-layout">
					<aside class="gz-article-sidebar" aria-label="Навигация по странице">
						<div class="gz-article-sticky">
							<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
								<summary id="gz-article-toc-title">На этой странице<span aria-hidden="true">+</span></summary>
								<div class="gz-quiet-details-content">
									<nav aria-labelledby="gz-article-toc-title">
										<ol class="gz-article-toc">
											<?php foreach ($gdpsy_toc as $gdpsy_id => $gdpsy_label) : ?>
												<li><a href="#<?php echo esc_attr($gdpsy_id); ?>"><?php echo esc_html($gdpsy_label); ?></a></li>
											<?php endforeach; ?>
										</ol>
									</nav>
								</div>
							</details>
						</div>
					</aside>
					<div class="gz-article-body gz-overview-body">
						<?php if (trim((string) get_post_field('post_content', get_the_ID(), 'raw')) !== '') : ?><div class="gz-entry gz-overview-editor"><?php the_content(); ?></div><?php endif; ?>
						<section class="gz-overview-section" aria-labelledby="gz-possibilities-base-title">
							<p class="gz-eyebrow">01 / Основа сайта</p>
							<h2 id="gz-possibilities-base-title" tabindex="-1">Главная страница</h2>
							<p class="gz-overview-lead">Одна страница, на которой человек знакомится с вами, понимает условия работы и находит понятный способ обратиться. Собираем содержание вокруг вашей практики и вопросов будущих клиентов. Чтобы ещё до первой встречи было понятно, с чем к вам можно прийти, как проходит работа и какой шаг сделать дальше.</p>

							<h3>Что может быть на странице</h3>
							<ul class="gz-overview-list">
								<li><strong>Знакомство с вами</strong><span>Кто вы и кому помогаете.</span></li>
								<li><strong>Ваш подход</strong><span>Как вы работаете и на что опираетесь в практике.</span></li>
								<li><strong>Образование и опыт</strong><span>Профессиональный путь, подготовка и квалификация.</span></li>
								<li><strong>Запросы клиентов</strong><span>С чем к вам можно обратиться.</span></li>
								<li><strong>Формат встреч</strong><span>Как проходит работа: онлайн или очно, длительность и порядок встреч.</span></li>
								<li><strong>Стоимость и условия</strong><span>Цена консультации и условия работы.</span></li>
								<li><strong>Ответы на вопросы</strong><span>То, что важно узнать перед первым обращением.</span></li>
								<li><strong>Контакты и первый шаг</strong><span>Контактная форма и удобные способы связи.</span></li>
							</ul>
							<p class="gz-overview-note">Это пример состава, а не обязательный набор блоков. Структуру подберём под вашу практику.</p>
							<div class="gz-overview-formats" role="group" aria-labelledby="gz-possibilities-formats">
								<h3 id="gz-possibilities-formats" tabindex="-1">Два пути к дизайну сайта: с нуля или на основе готовой идеи</h3>
								<section class="gz-overview-format" aria-labelledby="gz-format-scratch-title">
									<div class="gz-overview-format-copy">
										<p class="gz-eyebrow">Индивидуальная разработка</p>
										<h4 id="gz-format-scratch-title">Начинаем с чистого листа</h4>
										<p>Подойдёт, если нужен собственный визуальный язык или особый состав страницы. Начинаем с ваших задач, затем собираем структуру, содержание и дизайн.</p>
									</div>
									<?php $gdpsy_price = gdpsy_price_label('base');
									if ($gdpsy_price !== '') : ?><p class="gz-overview-price"><?php echo esc_html($gdpsy_price); ?><small>за лендинг на 5–10 блоков</small></p><?php endif; ?>
									<a class="gz-button gz-button--navy" href="<?php echo esc_url($gdpsy_contact_url); ?>" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить разработку лендинга с нуля." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить свою идею</a>
								</section>
								<section class="gz-overview-format gz-overview-format--concept" aria-labelledby="gz-format-concept-title">
									<div class="gz-overview-format-copy">
										<p class="gz-eyebrow">Авторский концепт</p>
										<h4 id="gz-format-concept-title">Выбираем близкую вам идею</h4>
										<p>Можно заранее посмотреть дизайн и попробовать демонстрацию. Адаптирую тексты и фото под вас, добавлю до трёх информационных блоков в том же дизайне. После покупки сниму концепт с продажи.</p>
									</div>
									<?php $gdpsy_price = gdpsy_price_label('concept', 'от ');
									if ($gdpsy_price !== '') : ?><p class="gz-overview-price"><?php echo esc_html($gdpsy_price); ?><small>за адаптацию концепта</small></p><?php endif; ?>
									<a class="gz-button gz-button--outline" href="<?php echo esc_url(add_query_arg('case-type', 'consept', gdpsy_archive_url())); ?>">Посмотреть концепты</a>
								</section>
							</div>
							<p class="gz-overview-note">Точный состав адаптации, цену и срок при готовых материалах фиксируем для выбранного концепта. Дополнительные страницы, интеграции, новая механика интерактива и переработка дизайна обсуждаются отдельно.</p>
							<div class="gz-overview-launch">
								<h3>Помогу собрать и запустить</h3>
								<p>Задам вопросы и соберу тексты из ваших ответов. Помогу с фотографиями, ретушью и графикой, возьму на себя техническую часть: подключение домена и хостинга, аналитику и запуск.</p>
								<p>Домен и хостинг оформляются на ваше имя. Передам доступы, покажу, как менять тексты, фото и цены. Месяц поддержки после запуска включён; домен и хостинг оплачиваются отдельно.</p>
							</div>
						</section>
						<section class="gz-overview-section" aria-labelledby="gz-possibility-pages">
							<p class="gz-eyebrow">02 / Подробнее о вашей практике</p>
							<h2 id="gz-possibility-pages" tabindex="-1">Дополнительные страницы</h2>
							<p class="gz-overview-lead"><?php echo esc_html($gdpsy_features['pages']['summary']); ?></p>
							<dl class="gz-overview-rows">
								<?php foreach ($gdpsy_features['pages']['features'] as $gdpsy_item) : ?>
									<div>
										<dt><?php echo esc_html($gdpsy_item['title']); ?></dt>
										<dd><?php echo esc_html($gdpsy_item['text']); ?></dd>
									</div>
								<?php endforeach; ?>
							</dl>
							<p>Выстрою структуру и оформлю материалы в общем стиле сайта. Дополнительная страница может появиться сразу при запуске или позже.</p>
							<?php $gdpsy_price = gdpsy_price_label('pages', 'от ');
							if ($gdpsy_price !== '') : ?><p class="gz-overview-price-inline"><?php echo esc_html($gdpsy_price); ?><span>за страницу</span></p><?php endif; ?>
						</section>
						<section class="gz-overview-section" aria-labelledby="gz-possibility-blog">
							<p class="gz-eyebrow">03 / Знакомство до первой встречи</p>
							<h2 id="gz-possibility-blog" tabindex="-1">Блог, анонсы и рассылка</h2>
							<p class="gz-overview-lead">Через статьи человек может узнать ваш подход, найти ответы на свои вопросы и понять, хочет ли обратиться к вам.</p>
							<p>Ссылками удобно делиться в соцсетях и отправлять клиентам. Материалы могут находить через поиск, а ссылки на них могут появляться в ответах нейросетей. Техническая подготовка помогает представить содержание, но не гарантирует позиции или обращения.</p>
							<dl class="gz-overview-rows">
								<?php foreach ($gdpsy_features['blog']['features'] as $gdpsy_item) : ?>
									<div>
										<dt><?php echo esc_html($gdpsy_item['title']); ?></dt>
										<dd>
											<p><?php echo esc_html($gdpsy_item['text']); ?></p><?php $gdpsy_price = gdpsy_price_label($gdpsy_item['price']);
																									if ($gdpsy_price !== '') : ?><p class="gz-overview-price-inline"><?php echo esc_html($gdpsy_price); ?></p><?php endif; ?>
										</dd>
									</div>
								<?php endforeach; ?>
							</dl>
							<p>Пять стартовых статей подготовлю с вашим участием: можно адаптировать публикации из канала, написать новые материалы или совместить оба варианта. Вы проверите профессиональные формулировки. После запуска сможете публиковать статьи самостоятельно.</p>
							<p class="gz-overview-note">Ведение каналов, дополнительные статьи и регулярная подготовка писем обсуждаются отдельно. Если нужен платный сервис, его условия и расходы согласуем заранее.</p>
							<?php if ($gdpsy_blog_url !== '') : ?><a class="gz-text-action" href="<?php echo esc_url($gdpsy_blog_url); ?>">Подробнее о блоге и продвижении</a><?php endif; ?>
						</section>
						<section class="gz-overview-section" aria-labelledby="gz-possibility-interactions">
							<p class="gz-eyebrow">04 / Польза для клиентов</p>
							<h2 id="gz-possibility-interactions" tabindex="-1">Тесты и интерактивы</h2>
							<p class="gz-overview-lead"><?php echo esc_html($gdpsy_tools['summary']); ?></p>
							<p>Помочь сделать паузу, заметить своё состояние или найти слова для обращения. Например, предложить дыхательную практику, вопросы для самонаблюдения или конструктор первого сообщения.</p>
							<h3>Где разместить</h3>
							<ol class="gz-overview-placements">
								<li><strong>На главной</strong><span>Встроить инструмент в знакомство с вами и вашим подходом.</span></li>
								<li><strong>На дополнительной странице</strong><span>Дополнить описание услуги, направления работы или полезный материал.</span></li>
								<li><strong>В отдельном каталоге</strong><span>Собрать подборку на вашем сайте, чтобы клиентам было удобно возвращаться к инструментам.</span></li>
							</ol>
							<div class="gz-overview-connection">
								<h3>Что входит в подключение</h3>
								<p><?php echo esc_html($gdpsy_tools['features'][0]['text']); ?></p>
								<?php $gdpsy_price = gdpsy_price_label('toolkit', 'от ');
								if ($gdpsy_price !== '') : ?><p class="gz-overview-price-inline"><?php echo esc_html($gdpsy_price); ?><span>за дополнительный интерактив</span></p><?php endif; ?>
								<p class="gz-overview-discount"><?php echo esc_html($gdpsy_tools['note']); ?></p>
								<p>Один интерактив уже предусмотрен в лендинге: при разработке с нуля — по желанию, в концепте — включённый в выбранный дизайн. Здесь речь о дополнительных инструментах.</p>
							</div>
							<div class="gz-overview-pwa" id="gz-possibility-library">
								<h3>Можно ли сделать приложение?</h3>
								<p><?php echo esc_html($gdpsy_tools['features'][2]['text']); ?></p>
							</div>
							<a class="gz-button gz-button--outline" href="<?php echo esc_url(home_url('/interactive/')); ?>">Каталог интерактивов</a>
						</section>
						<section class="gz-overview-section" aria-labelledby="gz-possibilities-further">
							<p class="gz-eyebrow">05 / Обсудим вашу задачу</p>
							<h2 id="gz-possibilities-further" tabindex="-1">Если нужно больше</h2>
							<p class="gz-overview-lead">Ниже — направления возможной разработки. Готовых модулей записи, платежей и обучения пока нет.</p>
							<dl class="gz-overview-rows">
								<div id="gz-possibility-booking">
									<dt>Онлайн-запись</dt>
									<dd>Возможный сценарий: выбор времени встречи, подтверждения и напоминания. Связь с вашим сервисом записи требует отдельного обсуждения.</dd>
								</div>
								<div>
									<dt>Приём платежей</dt>
									<dd>Возможный сценарий: оплата консультаций, групп или материалов. Способы подключения и условия пока изучаю.</dd>
								</div>
								<div id="gz-possibility-courses">
									<dt>Курсы и интенсивы</dt>
									<dd>Страницу программы с заявкой можно заказать как дополнительную страницу. Оплата и доступ к урокам — идея будущего модуля.</dd>
								</div>
							</dl>
							<p class="gz-overview-note">Изучаю спрос и собираю пожелания. Состав модулей, стоимость и сроки запуска пока не определены.</p>
							<a class="gz-button gz-button--navy" href="<?php echo esc_url($gdpsy_contact_url); ?>" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Помогите выбрать состав сайта для моей практики." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить состав сайта</a>
						</section>
					</div>
				</div>
			<?php else : ?>
				<div class="gz-entry"><?php the_content(); ?></div>
			<?php endif; ?>
			<?php wp_link_pages(array('before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>')); ?>
		</article>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>


