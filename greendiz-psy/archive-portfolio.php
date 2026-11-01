<?php
get_header();
global $wp_query;
$items = array_values( array_filter( $wp_query->posts, function ( $post ) {
	return in_array( gdpsy_field( 'case-type', $post->ID ), array( 'project', 'consept' ), true );
} ) );
$type = gdpsy_case_filter();
$offers = gdpsy_archive_offers();
$offer = $offers[ $type ];
$gdpsy_filter_defs = gdpsy_concept_filter_definitions();
$gdpsy_filter_options = gdpsy_concept_filter_options( array_filter( $items, function ( $post ) {
	return gdpsy_field( 'case-type', $post->ID ) === 'consept';
} ) );
$gdpsy_filter_options = array_filter( $gdpsy_filter_options );
$count = count( array_filter( $items, function ( $post ) use ( $type ) {
	return $type === 'all' || gdpsy_field( 'case-type', $post->ID ) === $type;
} ) );
?>
<main id="gz-main">
<div class="gz-inner"><div class="gz-breadcrumbs"><?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => array(
	array( 'title' => 'Главная', 'url' => home_url( '/' ) ),
	array( 'title' => 'Примеры сайтов', 'url' => '' ),
) ) ); ?><span>Разные практики — разный характер</span></div></div>
<section class="gz-archive gz-inner" aria-labelledby="gz-archive-title" data-offers="<?php echo esc_attr( wp_json_encode( $offers ) ); ?>">
<div class="gz-archive-intro"><h1 id="gz-archive-title">Примеры сайтов</h1><p>Реальные проекты и авторские концепты для разных практик. Посмотрите, какой подход близок вам.</p></div>
<div class="gz-archive-toolbar"><nav class="gz-archive-types" aria-label="Тип работы">
<?php foreach ( array( 'all' => 'Все', 'project' => 'Проекты', 'consept' => 'Концепты' ) as $key => $label ) : ?>
<a href="<?php echo esc_url( $key === 'all' ? gdpsy_archive_url() : add_query_arg( 'case-type', $key, gdpsy_archive_url() ) ); ?>" data-case-filter="<?php echo esc_attr( $key ); ?>"<?php echo $type === $key ? ' aria-current="true"' : ''; ?> aria-controls="gz-archive-grid"><?php echo esc_html( $label ); ?></a>
<?php endforeach; ?>
</nav><p class="gz-archive-count" id="gz-archive-status" role="status" aria-live="polite" aria-atomic="true">Показано работ: <?php echo absint( $count ); ?></p></div>
<div class="gz-archive-layout">
<aside class="gz-archive-side" aria-label="Подбор и помощь с выбором">
<?php if ( $gdpsy_filter_options ) : ?>
<details class="gz-quiet-details gz-archive-disclosure gz-archive-filter" id="gz-archive-filter" data-details-static="(min-width: 1101px)" open hidden>
<summary><span>Подобрать концепт <small class="gz-archive-filter-count" data-filter-badge hidden>0</small></span><span class="gz-menu-chevron" aria-hidden="true"></span></summary>
<div class="gz-quiet-details-content">
<form class="gz-archive-filter-form" id="gz-archive-filter-form">
<?php $gdpsy_first_group = true; foreach ( $gdpsy_filter_options as $gdpsy_key => $gdpsy_terms ) : ?>
<details class="gz-archive-group"<?php echo $gdpsy_first_group || $gdpsy_key === 'mood' ? ' open' : ''; $gdpsy_first_group = false; ?>>
<summary><?php echo esc_html( $gdpsy_filter_defs[ $gdpsy_key ]['label'] ); ?></summary>
<fieldset class="gz-archive-options">
<legend class="gz-sr-only"><?php echo esc_html( $gdpsy_filter_defs[ $gdpsy_key ]['label'] ); ?></legend>
<?php foreach ( $gdpsy_terms as $gdpsy_slug => $gdpsy_name ) : ?>
<label class="gz-archive-option"><input type="checkbox" name="<?php echo esc_attr( $gdpsy_key ); ?>" value="<?php echo esc_attr( $gdpsy_slug ); ?>"><span><?php echo esc_html( $gdpsy_name ); ?></span></label>
<?php endforeach; ?>
</fieldset>
</details>
<?php endforeach; ?>
<button class="gz-text-action gz-archive-filter-reset" type="button" data-filter-clear hidden>Сбросить подбор</button>
</form>
</div></details>
<?php endif; ?>
<details class="gz-quiet-details gz-archive-disclosure" id="gz-archive-picker" data-details-static="(min-width: 1101px)" open<?php echo $type === 'project' ? ' hidden' : ''; ?>>
<summary><span>Помочь выбрать концепт</span><span class="gz-menu-chevron" aria-hidden="true"></span></summary>
<div class="gz-quiet-details-content"><p>Расскажите о своей практике и пожеланиях. Помогу выбрать близкий по характеру концепт и обсудить адаптацию.</p><a class="gz-primary gz-button" href="#gz-contact" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Помогите выбрать концепт под мою практику.">Обсудить выбор ↗</a></div></details>
<details class="gz-quiet-details gz-archive-disclosure" id="gz-archive-custom" data-details-static="(min-width: 1101px)" open<?php echo $type !== 'project' ? ' hidden' : ''; ?>>
<summary><span>Сайт под вашу практику</span><span class="gz-menu-chevron" aria-hidden="true"></span></summary>
<div class="gz-quiet-details-content"><p>Если нужен собственный визуальный язык или особый состав сайта, разработаю его с нуля.</p><a class="gz-primary gz-button" href="#gz-contact" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить индивидуальный сайт под мою практику.">Обсудить сайт ↗</a></div></details>
</aside>
<div class="gz-archive-results" id="gz-archive-results">
<div class="gz-archive-grid" id="gz-archive-grid"<?php echo $count === 0 ? ' hidden' : ''; ?>>
<?php foreach ( $items as $item ) : $case_type = gdpsy_field( 'case-type', $item->ID ); $info = gdpsy_portfolio_info( $item->ID ); ?>
<?php $gdpsy_kicker = $case_type === 'consept' ? implode( ' · ', gdpsy_concept_tag_list( $item->ID ) ) : gdpsy_text( $info['speczializacziya'] ?? '' ); ?>
<article class="gz-work" data-case-type="<?php echo esc_attr( $case_type ); ?>"<?php echo $case_type === 'consept' ? gdpsy_concept_filter_attrs( $item->ID ) : ''; ?><?php echo $type !== 'all' && $type !== $case_type ? ' hidden' : ''; ?>>
<a class="gz-archive-card" href="<?php echo esc_url( get_permalink( $item ) ); ?>" aria-labelledby="gz-archive-card-<?php echo absint( $item->ID ); ?>">
<div class="gz-archive-preview<?php echo has_post_thumbnail( $item ) ? '' : ' gz-archive-preview--pending'; ?>">
<?php if ( has_post_thumbnail( $item ) ) : echo get_the_post_thumbnail( $item, 'large', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); else : ?><span>Обложка готовится</span><?php endif; ?>
</div><div class="gz-work-caption"><div class="gz-archive-card-meta"><span><?php echo $case_type === 'consept' ? 'Концепт' : 'Проект'; ?></span><?php $gdpsy_reserved = $case_type === 'consept' ? gdpsy_concept_reserved_text( $item->ID ) : ''; if ( $gdpsy_reserved !== '' ) : ?><span class="gz-reserved"><?php echo esc_html( $gdpsy_reserved ); ?></span><?php endif; ?></div>
<h3 id="gz-archive-card-<?php echo absint( $item->ID ); ?>"><?php echo esc_html( get_the_title( $item ) ); ?></h3>
<?php if ( $gdpsy_kicker !== '' ) : ?><p class="gz-work-kicker"><?php echo esc_html( $gdpsy_kicker ); ?></p><?php endif; ?>
<?php if ( gdpsy_text( $info['opisanie'] ?? '' ) !== '' ) : ?><p class="gz-work-detail"><?php echo esc_html( gdpsy_text( $info['opisanie'] ) ); ?></p><?php endif; ?>
<?php $gdpsy_cost = $case_type === 'consept' ? gdpsy_concept_cost( $item->ID ) : ''; ?>
<div class="gz-archive-card-footer"><div class="gz-archive-card-more"><?php if ( $gdpsy_cost !== '' ) : ?><span class="gz-archive-card-price"><?php echo esc_html( $gdpsy_cost ); ?></span><?php endif; ?><span aria-hidden="true">Подробнее</span></div></div>
</div></a></article>
<?php endforeach; ?>
</div>
<div class="gz-archive-empty" id="gz-archive-empty"<?php echo $count > 0 ? ' hidden' : ''; ?>><h2 tabindex="-1" data-empty-title>Работ этого типа пока нет</h2><p data-empty-copy>Посмотрите другие примеры или расскажите о своей задаче.</p><a class="gz-inline-link gz-text-action" href="<?php echo esc_url( gdpsy_archive_url() ); ?>" data-filter-reset>Посмотреть все работы ↗</a> <button class="gz-inline-link gz-text-action" type="button" data-filter-clear hidden>Сбросить подбор</button></div>
</div></div>
<section class="gz-archive-offer" aria-labelledby="gz-archive-offer-title"><div><p class="gz-eyebrow">ВАШ СЛЕДУЮЩИЙ ШАГ</p><h2 id="gz-archive-offer-title"><?php echo esc_html( $offer[0] ); ?></h2></div>
<div><p id="gz-archive-offer-copy"><?php echo esc_html( $offer[1] ); ?></p><a class="gz-primary gz-button" id="gz-archive-discuss" href="#gz-contact" data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog" data-contact-preset="<?php echo esc_attr( $offer[3] ); ?>"><?php echo esc_html( $offer[2] ); ?> ↗</a></div></section>
</section></main>
<?php get_footer(); ?>

