<?php
get_header();
global $wp_query;
$gdpsy_filters = gdpsy_interactive_filters();
$gdpsy_filter_options = gdpsy_interactive_filter_options();
$gdpsy_filter_definitions = gdpsy_interactive_filter_definitions();
$gdpsy_active_filters = array_filter( $gdpsy_filters );
if ( gdpsy_interactive_is_selection() ) {
	$gdpsy_active_filters['selection'] = '1';
	$gdpsy_active_filters['selection_ids'] = implode( ',', gdpsy_interactive_saved_ids() );
}
$gdpsy_chip_url = static function ( $gdpsy_key, $gdpsy_slug = '' ) use ( $gdpsy_active_filters ) {
	$gdpsy_args = $gdpsy_active_filters;
	if ( $gdpsy_key === 'selection' ) {
		unset( $gdpsy_args['selection'], $gdpsy_args['selection_ids'] );
	} elseif ( isset( $gdpsy_args[ $gdpsy_key ] ) ) {
		$gdpsy_args[ $gdpsy_key ] = array_values( array_diff( $gdpsy_args[ $gdpsy_key ], array( $gdpsy_slug ) ) );
		if ( ! $gdpsy_args[ $gdpsy_key ] ) {
			unset( $gdpsy_args[ $gdpsy_key ] );
		}
	}
	return add_query_arg( urlencode_deep( $gdpsy_args ), gdpsy_interactive_archive_url() );
};
$gdpsy_chips = array();
foreach ( $gdpsy_filter_definitions as $gdpsy_filter_key => $gdpsy_definition ) {
	foreach ( $gdpsy_filters[ $gdpsy_filter_key ] ?? array() as $gdpsy_slug ) {
		$gdpsy_chips[] = array(
			'text' => $gdpsy_definition['label'] . ': ' . $gdpsy_filter_options[ $gdpsy_filter_key ][ $gdpsy_slug ],
			'url' => $gdpsy_chip_url( $gdpsy_filter_key, $gdpsy_slug ),
		);
	}
}
if ( isset( $gdpsy_active_filters['selection'] ) ) {
	$gdpsy_chips[] = array( 'text' => 'Моя подборка', 'url' => $gdpsy_chip_url( 'selection' ) );
}
$gdpsy_group_order = array( 'topic', 'approach', 'goal', 'type' );
$gdpsy_offer = gdpsy_interactive_offer();
$gdpsy_overview = gdpsy_page_url( 'possibilities' );
?>
<main class="gz-interactive-archive gz-inner" id="gz-main">
	<div class="gz-breadcrumbs"><?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_interactive_breadcrumbs() ) ); ?></div>
	<header class="gz-interactive-heading">
		<div><p class="gz-eyebrow">Инструменты для вашей практики</p><h1>Тесты и интерактивы</h1></div>
		<div class="gz-interactive-intro"><p>Короткая практика, тест или небольшое действие, через которое человек может познакомиться с вашим подходом и получить полезный опыт.</p><p>Выберите задачу, тему, тип или подход. В описании каждого инструмента — сценарий использования и стоимость подключения.</p></div>
	</header>
	<div class="gz-interactive-layout">
		<aside class="gz-interactive-sidebar" aria-label="Фильтры интерактивов">
			<details class="gz-interactive-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
				<summary><strong>Фильтры <small class="gz-interactive-panel-count"<?php echo $gdpsy_chips ? '' : ' hidden'; ?>><?php echo absint( count( $gdpsy_chips ) ); ?></small></strong><span aria-hidden="true">+</span></summary>
				<div class="gz-quiet-details-content">
					<form class="gz-interactive-filter" id="gz-interactive-filter" action="<?php echo esc_url( gdpsy_interactive_archive_url() ); ?>" method="get">
						<?php if ( ! get_option( 'permalink_structure' ) ) : ?><input type="hidden" name="post_type" value="interactive"><?php endif; ?>
						<input type="hidden" name="selection_ids" value="<?php echo esc_attr( implode( ',', gdpsy_interactive_saved_ids() ) ); ?>" disabled>
						<?php foreach ( $gdpsy_group_order as $gdpsy_filter_key ) : if ( empty( $gdpsy_filter_definitions[ $gdpsy_filter_key ] ) || empty( $gdpsy_filter_options[ $gdpsy_filter_key ] ) ) { continue; } $gdpsy_definition = $gdpsy_filter_definitions[ $gdpsy_filter_key ]; ?>
						<details class="gz-interactive-group"<?php echo $gdpsy_filter_key === 'topic' || $gdpsy_filters[ $gdpsy_filter_key ] ? ' open' : ''; ?>>
							<summary><?php echo esc_html( $gdpsy_definition['label'] ); ?></summary>
							<fieldset class="gz-interactive-options">
								<legend class="gz-sr-only"><?php echo esc_html( $gdpsy_definition['label'] ); ?></legend>
								<?php foreach ( $gdpsy_filter_options[ $gdpsy_filter_key ] as $gdpsy_slug => $gdpsy_label ) : ?>
								<label class="gz-interactive-option"><input type="checkbox" name="<?php echo esc_attr( $gdpsy_filter_key ); ?>[]" value="<?php echo esc_attr( $gdpsy_slug ); ?>" <?php checked( in_array( $gdpsy_slug, $gdpsy_filters[ $gdpsy_filter_key ], true ) ); ?>><span><?php echo esc_html( $gdpsy_label ); ?></span></label>
								<?php endforeach; ?>
							</fieldset>
						</details>
						<?php endforeach; ?>
						<noscript><button class="gz-button gz-button--outline" type="submit">Показать</button></noscript>
						<a class="gz-text-action gz-interactive-reset" href="<?php echo esc_url( gdpsy_interactive_archive_url() ); ?>"<?php echo $gdpsy_active_filters ? '' : ' hidden'; ?>>Сбросить всё</a>
					</form>
				</div>
			</details>
		</aside>
		<div class="gz-interactive-results" id="gz-interactive-results">
			<div class="gz-interactive-bar">
				<p class="gz-interactive-count">Найдено: <?php echo absint( $wp_query->found_posts ); ?></p>
				<div class="gz-interactive-selection-actions">
					<label class="gz-button gz-button--outline gz-interactive-selection" data-interactive-selection hidden><input type="checkbox" name="selection" value="1" form="gz-interactive-filter" <?php checked( gdpsy_interactive_is_selection() ); ?>><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/></svg><span>Моя подборка</span></label>
					<button class="gz-button gz-button--navy" type="button" data-interactive-order data-interactive-titles="<?php echo esc_attr( wp_json_encode( gdpsy_interactive_titles() ) ); ?>" data-open-dialog="gz-contact-dialog" data-contact-preset="" aria-haspopup="dialog" aria-controls="gz-contact-dialog" hidden>Заказать избранное</button>
				</div>
				<ul class="gz-interactive-chips" aria-label="Выбранные фильтры"<?php echo $gdpsy_chips ? '' : ' hidden'; ?>>
					<?php foreach ( $gdpsy_chips as $gdpsy_chip ) : ?>
					<li><a class="gz-interactive-chip" href="<?php echo esc_url( $gdpsy_chip['url'] ); ?>" aria-label="<?php echo esc_attr( 'Убрать фильтр: ' . $gdpsy_chip['text'] ); ?>"><?php echo esc_html( $gdpsy_chip['text'] ); ?><span aria-hidden="true">×</span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="gz-interactive-body">
			<?php if ( have_posts() ) : ?>
			<div class="gz-interactive-grid">
				<?php if ( ! $gdpsy_active_filters && ! is_paged() ) { get_template_part( 'template-parts/interactive-card-custom' ); } ?>
				<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template-parts/interactive-card' ); ?>
				<?php endwhile; ?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Назад', 'next_text' => 'Дальше →', 'screen_reader_text' => 'Страницы каталога интерактивов', 'add_args' => $gdpsy_active_filters ) ); ?>
			<?php else : ?>
			<section class="gz-interactive-empty" aria-labelledby="gz-interactive-empty-title">
				<h2 id="gz-interactive-empty-title"><?php echo $gdpsy_active_filters ? 'По этим условиям пока нет инструментов' : 'Каталог наполняется'; ?></h2>
				<p><?php echo $gdpsy_active_filters ? 'Измените условия, посмотрите все интерактивы или расскажите, что хотите предложить клиентам.' : 'Расскажите о своей практике — обсудим, какие инструменты подойдут вашему сайту.'; ?></p>
				<?php if ( $gdpsy_active_filters ) : ?><a class="gz-text-action" href="<?php echo esc_url( gdpsy_interactive_archive_url() ); ?>">Все интерактивы</a><?php endif; ?>
			</section>
			<?php endif; ?>
			</div>
			<p class="gz-sr-only" role="status" data-interactive-live></p>
		</div>
	</div>
	<section class="gz-interactive-guide" aria-labelledby="gz-interactive-guide-title">
		<div><p class="gz-eyebrow">Как использовать на сайте</p><h2 id="gz-interactive-guide-title">Один инструмент<br>или целая подборка</h2></div>
		<div><p><?php echo esc_html( $gdpsy_offer['story'][0] ); ?></p><p><?php echo esc_html( $gdpsy_offer['note'] ); ?></p><p>Для подборки можно отдельно обсудить приложение с установкой на телефон. Состав, сохранение результатов и работу без интернета согласуем под выбранные инструменты.</p><?php if ( $gdpsy_overview !== '' ) : ?><a class="gz-text-action" href="<?php echo esc_url( $gdpsy_overview . '#gz-possibility-interactions' ); ?>">О размещении и подключении</a><?php endif; ?></div>
	</section>
	<section class="gz-interactive-contact" aria-labelledby="gz-interactive-contact-title">
		<div><h2 id="gz-interactive-contact-title">Помогу выбрать</h2><p>Расскажите, что человеку важно попробовать на вашем сайте. Подберём инструмент и обсудим оформление.</p></div>
		<a class="gz-button gz-button--navy" href="<?php echo esc_url( home_url( '/#gz-contact' ) ); ?>" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Помогите подобрать интерактивы для моей практики." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить подборку</a>
	</section>
</main>
<?php get_footer(); ?>
