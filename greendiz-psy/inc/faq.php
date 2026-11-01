<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_faq_sections( $post_id ) {
	$rows = gdpsy_field( 'razdely', $post_id );
	$sections = array();
	$used = array( 'gz-main', 'gz-menu', 'greendiz-design-study', 'gz-menu-title' );
	foreach ( is_array( $rows ) ? $rows : array() as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$questions = array();
		foreach ( isset( $row['faq'] ) && is_array( $row['faq'] ) ? $row['faq'] : array() as $question ) {
			if ( ! is_array( $question ) ) {
				continue;
			}
			$q = isset( $question['vopros'] ) && is_scalar( $question['vopros'] ) ? trim( wp_strip_all_tags( (string) $question['vopros'] ) ) : '';
			$a = isset( $question['otvet'] ) && is_scalar( $question['otvet'] ) ? trim( wp_kses_post( (string) $question['otvet'] ) ) : '';
			if ( $q !== '' && $a !== '' ) {
				$questions[] = array( 'question' => $q, 'answer' => $a );
			}
		}
		if ( ! $questions ) {
			continue;
		}
		$title = isset( $row['zagolovok'] ) && is_scalar( $row['zagolovok'] ) ? trim( wp_strip_all_tags( (string) $row['zagolovok'] ) ) : '';
		$anchor = isset( $row['yakor'] ) && is_scalar( $row['yakor'] ) ? sanitize_title( ltrim( (string) $row['yakor'], '#' ) ) : '';
		$anchor = $anchor !== '' ? rawurldecode( $anchor ) : 'faq-section-' . ( count( $sections ) + 1 );
		if ( preg_match( '/^(gz-|wp-)/', $anchor ) ) {
			$anchor = 'faq-' . $anchor;
		}
		$unique = $anchor;
		$suffix = 2;
		while ( in_array( $unique, $used, true ) ) {
			$unique = $anchor . '-' . $suffix++;
		}
		$used[] = $unique;
		$sections[] = array( 'id' => $unique, 'title' => $title, 'questions' => $questions );
	}
	return $sections;
}

function gdpsy_home_faq_sections() {
	return array(
		array(
			'title' => 'До начала работы',
			'questions' => array(
				array(
					'question' => 'Зачем мне сайт, если уже есть соцсети и профиль на агрегаторе?',
					'answer' => 'Сайт соберёт важное о вашей практике в одном месте: подход, услуги, условия встреч и способы связи. Человеку не придётся искать это среди публикаций. Из соцсетей и профилей можно вести на сайт тех, кто хочет узнать о вас подробнее.',
				),
				array(
					'question' => 'Чем авторский концепт отличается от сайта с нуля?',
					'answer' => 'Концепт — моя готовая идея дизайна, которую вы можете заранее посмотреть и попробовать. Я адаптирую её под вашу практику. При разработке с нуля создаём структуру и оформление под вашу задачу. В обоих случаях помогу с содержанием и запуском.',
				),
				array(
					'question' => 'А если у меня нет готовых текстов и профессиональных фото?',
					'answer' => 'Да. Задам вопросы о вашей практике и соберу тексты из ответов — достаточно рассказать о работе своими словами. Фотосессия не обязательна: подберём оформление с теми материалами, которые есть, или сделаем акцент на графике. Содержание согласуем с вами.',
				),
				array(
					'question' => 'Можно ли начать с одной страницы, а блог добавить позже?',
					'answer' => 'Да. Можно запустить страницу для знакомства и записи, а позже добавить блог, страницы услуг или интерактивы. Расскажите о планах в начале — учту их при создании сайта. Заказывать всё сразу не нужно.',
				),
			),
		),
		array(
			'title' => 'После запуска',
			'questions' => array(
				array(
					'question' => 'Поможет ли сайт привлекать клиентов?',
					'answer' => 'Сайт поможет человеку понять ваш подход, условия работы и сделать первый шаг. Но сам по себе запуск не гарантирует обращения: важно, откуда приходят посетители и подходит ли им ваше предложение. Ссылкой можно делиться в соцсетях, профилях и по рекомендации; поиск и реклама требуют отдельной работы.',
				),
				array(
					'question' => 'Смогу ли я самостоятельно менять тексты, фотографии и цены?',
					'answer' => 'Да. Покажу, как менять тексты, фотографии и цены, а если подключим блог — публиковать статьи. Знать программирование не нужно. Запись нашей встречи останется у вас, чтобы можно было вернуться к инструкции.',
				),
				array(
					'question' => 'Какие расходы останутся после запуска сайта?',
					'answer' => 'Нужно продлевать домен — адрес сайта — и хостинг, где он размещён. Обязательной абонентской платы мне за пользование сайтом нет. Если понадобятся платные сервисы или дальнейшая помощь, расходы обсудим заранее. Ориентир по домену и хостингу можно посмотреть в калькуляторе на странице стоимости.',
				),
				array(
					'question' => 'Что входит в поддержку после запуска?',
					'answer' => 'В течение месяца бесплатно исправляю свои технические недочёты и отвечаю на вопросы об использовании сайта. Можно написать, если что-то непонятно или работает не так, как мы договорились. Новые страницы, функции и другие изменения обсуждаем отдельно.',
				),
			),
		),
	);
}

function gdpsy_faq_schema_context() {
	if ( is_admin() || is_feed() ) {
		return '';
	}
	if ( is_front_page() ) {
		return 'home';
	}
	return is_page() && get_page_template() === get_theme_file_path( 'page-faq.php' ) ? 'page' : '';
}

function gdpsy_faq_schema() {
	$context = gdpsy_faq_schema_context();
	if ( $context === '' || is_preview() || is_paged() || (int) get_query_var( 'page' ) > 1 ) {
		return array();
	}
	$post = get_queried_object();
	if ( $post instanceof WP_Post && ( $post->post_status !== 'publish' || $post->post_password !== '' ) ) {
		return array();
	}
	$sections = $context === 'home' ? gdpsy_home_faq_sections() : gdpsy_faq_sections( get_queried_object_id() );
	$entities = array();
	foreach ( $sections as $section ) {
		foreach ( $section['questions'] as $question ) {
			$name = trim( wp_strip_all_tags( $question['question'] ) );
			$text = wp_kses_post( wpautop( $question['answer'] ) );
			if ( $name === '' || trim( html_entity_decode( wp_strip_all_tags( $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) === '' ) {
				continue;
			}
			$entities[] = array(
				'@type' => 'Question',
				'name' => $name,
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $text ),
			);
		}
	}
	if ( ! $entities ) {
		return array();
	}
	$url = $context === 'home' ? home_url( '/' ) : get_permalink( get_queried_object_id() );
	return array(
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'@id' => $url . '#webpage',
		'url' => $url,
		'mainEntity' => $entities,
	);
}

function gdpsy_faq_rank_math_schema( $data ) {
	if ( gdpsy_faq_schema_context() === '' || ! is_array( $data ) ) {
		return $data;
	}
	$schema = gdpsy_faq_schema();
	$page_key = null;
	foreach ( $data as $key => $entity ) {
		if ( is_array( $entity ) && in_array( 'WebPage', (array) ( $entity['@type'] ?? array() ), true ) ) {
			$page_key = $key;
			break;
		}
	}
	foreach ( $data as $key => $entity ) {
		if ( ! is_array( $entity ) || ! in_array( 'FAQPage', (array) ( $entity['@type'] ?? array() ), true ) ) {
			continue;
		}
		$types = array_values( array_diff( (array) $entity['@type'], array( 'FAQPage' ) ) );
		if ( ! $types ) {
			unset( $data[ $key ] );
		} else {
			$data[ $key ]['@type'] = count( $types ) === 1 ? $types[0] : $types;
			unset( $data[ $key ]['mainEntity'] );
		}
	}
	if ( ! $schema ) {
		return $data;
	}
	if ( $page_key !== null ) {
		$data[ $page_key ]['@type'] = array_values( array_unique( array_merge( (array) $data[ $page_key ]['@type'], array( 'FAQPage' ) ) ) );
		$data[ $page_key ]['mainEntity'] = $schema['mainEntity'];
	} else {
		unset( $schema['@context'] );
		$data['gdpsyFAQPage'] = $schema;
	}
	$GLOBALS['gdpsy_faq_schema_in_rank_math'] = true;
	return $data;
}
add_filter( 'rank_math/json_ld', 'gdpsy_faq_rank_math_schema', 100 );

function gdpsy_faq_schema_output() {
	if ( ! empty( $GLOBALS['gdpsy_faq_schema_in_rank_math'] ) || ! empty( $GLOBALS['gdpsy_faq_schema_printed'] ) ) {
		return;
	}
	$schema = gdpsy_faq_schema();
	if ( ! $schema ) {
		return;
	}
	$json = wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
	if ( $json !== false ) {
		echo '<script type="application/ld+json" class="gdpsy-faq-schema">' . $json . '</script>' . "\n";
		$GLOBALS['gdpsy_faq_schema_printed'] = true;
	}
}
add_action( 'wp_head', 'gdpsy_faq_schema_output', PHP_INT_MAX );

