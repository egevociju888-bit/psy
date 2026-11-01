<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_concept_filter_definitions() {
	return array(
		'specialty' => array( 'taxonomy' => 'gdpsy_c_specialty', 'label' => 'Специализация' ),
		'mood'      => array( 'taxonomy' => 'gdpsy_c_mood', 'label' => 'Настроение' ),
		'approach'  => array( 'taxonomy' => 'gdpsy_c_approach', 'label' => 'Подходы и техники' ),
		'itype'     => array( 'taxonomy' => 'gdpsy_i_type', 'label' => 'Тип интерактива', 'derived' => true ),
	);
}

function gdpsy_concept_default_terms() {
	return array(
		'gdpsy_c_specialty' => array(
			'depression-burnout' => array( 'Депрессия и выгорание', 'депрессия, апатия и упадок сил, эмоциональное выгорание, потеря интереса к жизни, потеря мотивации и смысла' ),
			'children-teens' => array( 'Дети и подростки', 'тревожность и страхи у детей, трудности поведения, школьная адаптация и учебные трудности, травля, подростковые кризисы, отношения со сверстниками, сепарация' ),
			'addictions' => array( 'Зависимости', 'химические зависимости, игровая и цифровая зависимость, зависимое поведение, любовная зависимость, созависимость, помощь близким зависимых' ),
			'health-body' => array( 'Здоровье и тело', 'психосоматика, хронические болезни и реабилитация, хроническая боль, нарушения сна, расстройства пищевого поведения, образ тела и отношение к весу' ),
			'career' => array( 'Карьера и самореализация', 'выбор профессии и профориентация, смена работы и карьерные кризисы, конфликты на работе, лидерство, цели и мотивация, прокрастинация, отношение к деньгам, предпринимательство' ),
			'relationships' => array( 'Отношения', 'пары и семья, подготовка к браку, конфликты и общение, ревность и измена, расставание и развод, отношения с родителями и родственниками, одиночество и поиск партнёра, дружба и социальные навыки' ),
			'parenting' => array( 'Родительство', 'воспитание и отношения с детьми, подготовка к родительству, беременность и послеродовой период, бесплодие и репродуктивные трудности, родительское выгорание, развод и ребёнок, усыновление и опека' ),
			'self-esteem' => array( 'Самооценка и самопознание', 'самооценка и самоуважение, личные границы, самоанализ, ценности и поиск смысла, перфекционизм, внутренний критик, идентичность и самоопределение, личностный рост' ),
			'sexuality' => array( 'Сексуальность', 'сексуальные трудности и близость, разное желание в паре, снижение влечения, нарушения возбуждения и оргазма, боль при близости, стыд и телесность, сексуальное просвещение' ),
			'stress-life' => array( 'Стресс и жизненные трудности', 'стресс и перегрузка, утрата и горе, релокация и адаптация, возрастные кризисы, выход на пенсию, тяжёлая болезнь близкого, жизненные переходы и неопределённость' ),
			'trauma' => array( 'Травматический опыт', 'психологическая травма и ПТСР, детская травма, последствия насилия и абьюза, последствия аварий и чрезвычайных событий, эмоционально небезопасные отношения' ),
			'anxiety-fears' => array( 'Тревога и страхи', 'тревожность, панические атаки, фобии и страхи, навязчивые мысли и ритуалы, социальная тревожность, тревога о здоровье, страх публичных выступлений' ),
			'emotions-neurodiversity' => array( 'Эмоциональная регуляция и нейроотличия', 'гнев и раздражительность, перепады настроения, эмоциональная чувствительность, импульсивность, СДВГ и аутистический спектр у взрослых, трудности концентрации и организации' ),
		),
		'gdpsy_c_mood' => array(
			'glossy' => array( 'Глянцевое', 'крупные портреты эксперта, пудровая, кремовая или насыщенная палитра, декоративные акценты (курсив, золото, скругления), эффектные блоки в духе соцсетей' ),
			'dynamic' => array( 'Динамичное', 'движение, контраст, активные акценты, энергичный ритм блоков' ),
			'playful' => array( 'Игривое', 'яркие цвета, лёгкий тон, неожиданные формы, иллюстрации' ),
			'minimal' => array( 'Минималистичное', 'мало элементов, строгая иерархия, много воздуха, упор на текст' ),
			'editorial' => array( 'Редакционное', 'журнальная подача: выразительная типографика, крупные заголовки, цитаты, выверенная сетка; допускает тёмную палитру' ),
			'calm' => array( 'Спокойное', 'приглушённая палитра, плавные переходы, много воздуха, природные мотивы, без резких акцентов' ),
			'strict' => array( 'Строгое', 'деловой и клинический тон: чёткая сетка, сдержанные цвета (графит, тёмно-синий или светлая холодная гамма), засечки или нейтральный гротеск, акцент на метод, регалии и опыт, минимум декора' ),
			'warm' => array( 'Тёплое', 'мягкие натуральные цвета, природные фактуры, живые фотографии, личный и доверительный тон' ),
			'artistic' => array( 'Художественное', 'авторская графика, рукотворные текстуры (акварель, коллаж), творческая подача' ),
		),
		'gdpsy_c_approach' => array(
			'art-therapy' => array( 'Арт-терапия', 'арт-терапия, игровая терапия, песочная терапия, сказкотерапия, метафорические ассоциативные карты (МАК), нейрографика, музыкотерапия, драматерапия, психодрама' ),
			'gestalt' => array( 'Гештальт-терапия', 'гештальт-терапия, гештальт-подход в консультировании, семейная гештальт-терапия' ),
			'hypnosis' => array( 'Гипноз', 'эриксоновский гипноз, клинический гипноз, трансовые техники, самогипноз' ),
			'groups' => array( 'Группы', 'групповая психотерапия, терапевтические группы, группы поддержки, тренинговые группы' ),
			'humanistic' => array( 'Гуманистическая терапия', 'клиент-центрированный (персоноцентрированный) подход, экзистенциальная терапия, логотерапия, позитивная психотерапия, психосинтез, фокусинг, позитивная психология' ),
			'diagnostics' => array( 'Диагностика', 'психологическая диагностика, профориентационная диагностика, клинико-психологическое консультирование' ),
			'emdr' => array( 'ДПДГ', 'десенсибилизация и переработка движениями глаз (ДПДГ / EMDR), работа с ресурсными состояниями' ),
			'cbt' => array( 'КПТ', 'когнитивно-поведенческая терапия, когнитивная терапия А. Бека, рационально-эмотивная поведенческая терапия (РЭПТ), поведенческая терапия, поведенческая активация, экспозиционная терапия, травмофокусированная КПТ, метакогнитивная терапия' ),
			'brief-therapy' => array( 'Краткосрочная терапия', 'краткосрочная терапия, ориентированная на решение, стратегическая краткосрочная терапия, личный, карьерный и жизненный коучинг, коучинг лидерства' ),
			'crisis' => array( 'Кризисная помощь', 'кризисное консультирование, экстренная психологическая помощь, терапия горевания, работа с утратой' ),
			'motivational' => array( 'Мотивационное консультирование', 'мотивационное консультирование, мотивационное интервьюирование, профилактика срывов, работа с изменением привычек' ),
			'neuropsychology' => array( 'Нейропсихология', 'нейропсихологическая диагностика, нейропсихологическая коррекция (детская и взрослая), нейрогимнастика, БОС-терапия, нейрофидбэк, сенсорная интеграция' ),
			'nlp' => array( 'НЛП', 'нейролингвистическое программирование, работа с якорями и субмодальностями, моделирование, рефрейминг' ),
			'imagery' => array( 'Образная терапия', 'символдрама, кататимно-имагинативная психотерапия (КИП), эмоционально-образная терапия (ЭОТ), работа с внутренними образами и метафорами, направленное воображение' ),
			'mindfulness' => array( 'Осознанность', 'mindfulness, MBSR, когнитивная терапия на основе осознанности (MBCT), медитативные практики, дыхательные практики, прогрессивная мышечная релаксация, аутогенная тренировка' ),
			'psychoanalysis' => array( 'Психоанализ', 'психоанализ, психодинамическая терапия, аналитическая психология (юнгианский анализ), терапия объектных отношений, работа со сновидениями' ),
			'psychoeducation' => array( 'Психообразование', 'психообразование, гигиена сна, дневники самонаблюдения, навыки самопомощи' ),
			'systemic' => array( 'Системная терапия', 'системная семейная терапия, структурная и стратегическая семейная терапия, миланский подход, нарративная терапия, работа с генограммой, системные семейные расстановки' ),
			'schema-therapy' => array( 'Схема-терапия', 'схема-терапия, работа с ранними дезадаптивными схемами, работа с режимами' ),
			'body-oriented' => array( 'Телесная терапия', 'телесно-ориентированная терапия, биоэнергетика, танцевально-двигательная терапия, соматическое переживание (Somatic Experiencing), сенсомоторная терапия, TRE, работа с телесными блоками' ),
			'couples-therapy' => array( 'Терапия пар', 'эмоционально-фокусированная терапия пар (ЭФТ), метод Готтмана, интегративная поведенческая терапия пар (IBCT), имаго-терапия, сексуальная терапия, сенсатный фокус, модель PLISSIT' ),
			'transactional-analysis' => array( 'Транзактный анализ', 'транзактный анализ, анализ эго-состояний, анализ сценариев и игр' ),
			'skills-training' => array( 'Тренинг навыков', 'тренинг коммуникативных навыков, обучение родителей, ассертивность, управление гневом, тренинг эмоциональных и социальных навыков' ),
			'third-wave' => array( 'Третья волна', 'терапия принятия и ответственности (ACT), фокусированная ACT (FACT), диалектическая поведенческая терапия (ДБТ), терапия, сфокусированная на сострадании (CFT)' ),
		),
	);
}

function gdpsy_register_concept_taxonomies() {
	$names = array(
		'gdpsy_c_specialty' => array( 'Специализации', 'Специализация' ),
		'gdpsy_c_mood'      => array( 'Настроения сайта', 'Настроение сайта' ),
		'gdpsy_c_approach'  => array( 'Подходы и техники', 'Подход и техники' ),
	);
	foreach ( $names as $taxonomy => $label ) {
		register_taxonomy( $taxonomy, array( 'portfolio' ), array(
			'labels' => array(
				'name'          => $label[0],
				'singular_name' => $label[1],
				'menu_name'     => $label[0],
				'all_items'     => $label[0],
				'edit_item'     => 'Редактировать значение',
				'update_item'   => 'Обновить значение',
				'add_new_item'  => 'Добавить значение',
				'new_item_name' => 'Название нового значения',
				'search_items'  => 'Поиск значений',
				'not_found'     => 'Значения не найдены',
				'no_terms'      => 'Значения не выбраны',
			),
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'show_in_rest'       => false,
			'show_tagcloud'      => false,
			'show_in_quick_edit' => false,
			'show_admin_column'  => false,
			'meta_box_cb'        => false,
			'rewrite'            => false,
			'query_var'          => false,
			'capabilities'       => array(
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'edit_posts',
			),
		) );
	}
}
add_action( 'init', 'gdpsy_register_concept_taxonomies' );

function gdpsy_seed_concept_terms() {
	if ( ! is_admin() || ! current_user_can( 'manage_categories' ) || get_option( 'gdpsy_concept_terms_seeded_v2' ) === '1' ) {
		return;
	}
	$failed = false;
	foreach ( gdpsy_concept_default_terms() as $taxonomy => $terms ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			$failed = true;
			continue;
		}
		foreach ( $terms as $slug => $term ) {
			if ( term_exists( $slug, $taxonomy ) ) {
				continue;
			}
			if ( is_wp_error( wp_insert_term( $term[0], $taxonomy, array( 'slug' => $slug, 'description' => $term[1] ) ) ) ) {
				$failed = true;
			}
		}
	}
	if ( ! $failed ) {
		update_option( 'gdpsy_concept_terms_seeded_v2', '1', false );
	}
}
add_action( 'init', 'gdpsy_seed_concept_terms', 20 );

function gdpsy_concept_terms( $post_id ) {
	static $cache = array();
	$post_id = absint( $post_id );
	if ( isset( $cache[ $post_id ] ) ) {
		return $cache[ $post_id ];
	}
	$result = array();
	foreach ( gdpsy_concept_filter_definitions() as $key => $definition ) {
		$result[ $key ] = array();
		if ( ! empty( $definition['derived'] ) || ! taxonomy_exists( $definition['taxonomy'] ) ) {
			continue;
		}
		$terms = get_the_terms( $post_id, $definition['taxonomy'] );
		foreach ( $terms && ! is_wp_error( $terms ) ? $terms : array() as $term ) {
			$result[ $key ][ $term->slug ] = $term->name;
		}
	}
	if ( taxonomy_exists( 'gdpsy_i_type' ) && function_exists( 'gdpsy_portfolio_interactives' ) ) {
		foreach ( gdpsy_portfolio_interactives( $post_id, 'consept', true ) as $interactive_id ) {
			$terms = get_the_terms( $interactive_id, 'gdpsy_i_type' );
			foreach ( $terms && ! is_wp_error( $terms ) ? $terms : array() as $term ) {
				$result['itype'][ $term->slug ] = $term->name;
			}
		}
	}
	foreach ( $result as $key => $terms ) {
		asort( $terms, SORT_STRING );
		$result[ $key ] = $terms;
	}
	$cache[ $post_id ] = $result;
	return $result;
}

function gdpsy_concept_term_groups( $post_id ) {
	$terms = gdpsy_concept_terms( $post_id );
	$groups = array();
	foreach ( gdpsy_concept_filter_definitions() as $key => $definition ) {
		if ( ! empty( $terms[ $key ] ) ) {
			$groups[ $key ] = array( 'label' => $definition['label'], 'terms' => array_values( $terms[ $key ] ) );
		}
	}
	return $groups;
}

function gdpsy_concept_tag_list( $post_id ) {
	$groups = gdpsy_concept_term_groups( $post_id );
	return $groups['specialty']['terms'] ?? array();
}

function gdpsy_concept_filter_options( array $posts ) {
	$options = array();
	foreach ( gdpsy_concept_filter_definitions() as $key => $definition ) {
		$options[ $key ] = array();
	}
	foreach ( $posts as $post ) {
		foreach ( gdpsy_concept_terms( $post->ID ) as $key => $terms ) {
			$options[ $key ] += $terms;
		}
	}
	foreach ( $options as $key => $terms ) {
		asort( $terms, SORT_STRING );
		$options[ $key ] = $terms;
	}
	return $options;
}

function gdpsy_concept_filter_attrs( $post_id ) {
	$html = '';
	foreach ( gdpsy_concept_terms( $post_id ) as $key => $terms ) {
		$html .= ' data-filter-' . esc_attr( $key ) . '="' . esc_attr( implode( ' ', array_keys( $terms ) ) ) . '"';
	}
	return $html;
}
