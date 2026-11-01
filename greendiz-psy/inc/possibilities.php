<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_possibilities() {
	return array(
		'pages' => array(
			'title' => 'Дополнительные страницы',
			'lead' => 'Больше места для важных подробностей о вашей практике.',
			'summary' => 'Отдельная страница помогает подробно раскрыть услугу, подход или программу и поделиться прямой ссылкой на неё.',
			'story' => array( 'Добавим страницы сразу или позже, сохранив общий стиль и понятную навигацию сайта.' ),
			'features' => array(
				array( 'title' => 'Услуга или направление', 'text' => 'Для кого подходят встречи, с какими запросами вы работаете, как проходит работа и как обратиться.' ),
				array( 'title' => 'О вас и вашем подходе', 'text' => 'Профессиональный путь, образование, методы и принципы работы — подробнее, чем на главной.' ),
				array( 'title' => 'Группа или мероприятие', 'text' => 'Тема, формат, даты, условия участия и способ записаться.' ),
				array( 'title' => 'Авторская программа', 'text' => 'Описание курса или интенсива, программа и заявка на участие. Оплата и доступ к урокам — отдельная задача.' ),
			),
		),
		'blog' => array(
			'title' => 'Блог, автопубликация и рассылка',
			'lead' => 'Статьи, анонсы и письма, через которые знакомятся с вашим подходом ещё до первой встречи.',
			'summary' => 'Статьи для знакомства с вашей практикой, анонсы в каналах и письма подписчикам. Ссылки на материалы могут появляться в поиске и ответах нейросетей.',
			'story' => array( 'Можно использовать материалы, уже написанные для канала, или подготовить новые. Тексты на сайте можно редактировать и публиковать самостоятельно.', 'Подключайте возможности сразу или позже, когда практика до них дорастёт.' ),
			'features' => array(
				array( 'title' => 'Блог / каталог статей', 'text' => 'Каталог статей, рубрики, поиск, оформление публикаций, пять стартовых статей, настройка адресов страниц и SEO-плагина.', 'price' => 'blog' ),
				array( 'title' => 'Автопубликация', 'text' => 'Анонсы новых статей отправляются в Telegram, VK и MAX. Для этой возможности нужен блог.', 'price' => 'autopost' ),
				array( 'title' => 'Email-рассылка', 'text' => 'Форма подписки и шаблоны писем. Рассылка может работать самостоятельно, без блога.', 'price' => 'mail' ),
			),
		),
		'booking' => array(
			'title' => 'Онлайн-запись и приём оплаты',
			'lead' => 'Онлайн-запись и оплата — направление возможной разработки. Готового модуля пока нет.',
			'summary' => 'Возможный сценарий для будущей разработки: выбор времени, подтверждения и приём платежей. Готового модуля пока нет; состав, стоимость и сроки запуска не определены.',
			'story' => array( 'Можно предусмотреть выбор времени из вашего календаря, подтверждение и напоминания. Возможности зависят от выбранного сервиса.', 'Если уже пользуетесь сервисом записи, можно обсудить, как связать его с сайтом.' ),
			'features' => array(
				array( 'title' => 'Онлайн-запись', 'text' => 'Возможные функции: выбор времени и формата встречи, подтверждения и напоминания.', 'discuss' => true ),
				array( 'title' => 'Приём оплаты', 'text' => 'Возможные способы оплаты и требования к подключению нужно исследовать под ваш статус и формат работы.', 'discuss' => true ),
			),
			'note' => 'Идея общего сценария: человек выбирает встречу и переходит к оплате. Состав, стоимость и сроки запуска пока не определены.',
		),
		'library' => gdpsy_interactive_offer(),
		'interactions' => gdpsy_interactive_offer(),
		'courses' => array(
			'title' => 'Курсы и интенсивы',
			'lead' => 'Ваш сайт может стать местом для продажи программ и работы с участниками. Сейчас изучаю спрос на такую возможность.',
			'summary' => 'Курсы и интенсивы на вашем сайте: возможные сценарии обучения. Изучаю спрос и собираю пожелания.',
			'upcoming' => true,
			'story' => array( 'Рассматриваю разработку модуля для продажи программ и работы с материалами. Опрос поможет понять, нужна ли такая услуга и что в ней предусмотреть.', 'Состав, стоимость и дата запуска пока не определены.' ),
			'features' => array(
				array( 'title' => 'Материалы и уроки', 'text' => 'Можно предусмотреть видео, аудио и PDF, открытие уроков сразу или по расписанию, задания и обратную связь.' ),
				array( 'title' => 'Личный кабинет', 'text' => 'Покупки и прогресс в одном месте. Подписка на материалы — ещё один возможный сценарий.' ),
				array( 'title' => 'Рассказать о своих задачах', 'text' => 'Ваши ответы помогут оценить спрос и определить состав будущей услуги.' ),
			),
		),
	);
}

function gdpsy_possibility_url( $key ) {
	$items = gdpsy_possibilities();
	if ( ! isset( $items[ $key ] ) ) {
		return '';
	}
	foreach ( gdpsy_possibility_pages() as $page ) {
		if ( gdpsy_possibility_key( $page->ID ) === $key ) {
			return get_permalink( $page );
		}
	}
	return '';
}

function gdpsy_possibility_key( $post_id ) {
	$slug = (string) get_post_field( 'post_name', $post_id, 'raw' );
	return array( 'blog-i-prodvizhenie' => 'blog', 'kursy-i-intensivy' => 'courses', 'praktiki-i-prilozhenie' => 'library' )[ $slug ] ?? $slug;
}

function gdpsy_is_blog_possibility() {
	return is_page_template( 'page-possibility.php' ) && gdpsy_possibility_key( get_queried_object_id() ) === 'blog';
}

function gdpsy_is_library_possibility() {
	return is_page_template( 'page-possibility.php' ) && gdpsy_possibility_key( get_queried_object_id() ) === 'library';
}

function gdpsy_is_courses_possibility() {
	return is_page_template( 'page-possibility.php' ) && gdpsy_possibility_key( get_queried_object_id() ) === 'courses';
}

function gdpsy_possibility_pages() {
	static $pages = null;
	if ( $pages !== null ) {
		return $pages;
	}
	$parent = get_page_by_path( 'possibilities' );
	$pages = $parent && $parent->post_status === 'publish' && $parent->post_password === '' ? get_posts( array(
		'post_type'      => 'page',
		'post_parent'    => $parent->ID,
		'post_status'    => 'publish',
		'has_password'   => false,
		'posts_per_page' => -1,
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-possibility.php',
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	) ) : array();
	return $pages;
}

function gdpsy_possibility_summary( $post_id ) {
	$excerpt = gdpsy_text( get_post_field( 'post_excerpt', $post_id, 'raw' ) );
	$items = gdpsy_possibilities();
	return $excerpt !== '' ? $excerpt : ( $items[ gdpsy_possibility_key( $post_id ) ]['summary'] ?? '' );
}

function gdpsy_is_possibilities_page() {
	return is_page() && ! is_front_page() && in_array( get_page_template(), array( get_theme_file_path( 'page-possibilities.php' ), get_theme_file_path( 'page-possibility.php' ) ), true );
}


function gdpsy_interactive_offer() {
	return array(
		'title' => 'Тесты, квизы, упражнения',
		'lead' => 'Небольшое действие, с которого может начаться знакомство с вашим подходом.',
		'summary' => 'Короткая практика, тест-квиз или инструмент для самонаблюдения. Выбираем один или несколько под задачи вашей практики и оформляем в стиле сайта.',
		'story' => array( 'Интерактив можно встроить в главную, разместить на дополнительной странице или собрать несколько инструментов в отдельном каталоге на вашем сайте.' ),
		'features' => array(
			array( 'title' => 'Один дополнительный интерактив', 'text' => 'Готовый инструмент из каталога с адаптацией оформления под сайт. Допустимые изменения текста уточняем для выбранного инструмента. Новая механика и изменение логики оцениваются отдельно.', 'price' => 'toolkit', 'unit' => ' / шт.' ),
			array( 'title' => 'Подборка инструментов', 'text' => 'Можно выбрать несколько интерактивов и разместить их в отдельном разделе. Состав подборки и организацию каталога согласуем до начала работ.' ),
			array( 'title' => 'Приложение на телефоне', 'text' => 'Для подборки интерактивов можно обсудить формат PWA с установкой на телефон. Работа без интернета и сохранение результатов зависят от выбранных инструментов и согласуются отдельно. Приложение не подключается автоматически при покупке нескольких интерактивов.' ),
		),
		'note' => 'При заказе от пяти дополнительных интерактивов действует скидка. Условия обсудим при подборе.',
	);
}

function gdpsy_is_possibilities_overview() {
	return is_page() && ! is_front_page() && get_page_template() === get_theme_file_path( 'page-possibilities.php' );
}

