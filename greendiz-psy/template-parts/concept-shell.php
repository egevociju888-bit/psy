<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$info = gdpsy_field('consept-info', $post_id);
$info = is_array($info) ? $info : array();
$palette = !empty($info['palette']) && is_array($info['palette']) ? $info['palette'] : array();
$title = get_the_title($post_id);
$colors = array();
foreach ($palette as $swatch) {
    $color = sanitize_hex_color(is_array($swatch) ? gdpsy_text($swatch['color'] ?? '') : gdpsy_text($swatch));
    if ($color) {
        $colors[] = $color;
    }
}
$groups = gdpsy_concept_term_groups($post_id);
$card_groups = array();
foreach (array('specialty', 'approach', 'mood', 'itype') as $key) {
    if (isset($groups[$key])) {
        $card_groups[] = $groups[$key];
    }
}
$description = gdpsy_text($info['opisanie'] ?? '');
$cost = gdpsy_concept_cost($post_id);
$has_card = $card_groups || $colors;
$page = gdpsy_concept_page();
$offer = gdpsy_concept_offer($post_id);
$preset = $offer['preset'];
$reserved = gdpsy_concept_reserved_text($post_id);
$question = 'Здравствуйте, Анастасия! У меня вопрос по концепту «' . $title . '»: ';
$portfolio_url = get_post_type_archive_link('portfolio') ?: home_url('/portfolio/');
?>
<div id="gdd-host">
<template shadowrootmode="open">
<?php gdpsy_concept_styles(); ?>
<div class="gz-page gz-concept-page" id="greendiz-design-study">
<div id="gz-concept-head">
<?php get_template_part( 'template-parts/site-header', null, array( 'dark' => true ) ); ?>
<main id="gz-main">
<?php get_template_part('template-parts/concept-breadcrumbs', null, array('post_id' => $post_id)); ?>
<section class="gz-concept-intro gz-inner<?php echo $has_card ? ' has-card' : ''; ?>" aria-labelledby="gz-concept-title">
<div class="gz-concept-main">
<div class="gz-concept-label"><p class="gz-eyebrow">Авторский концепт</p><?php if ($reserved !== '') : ?><span class="gz-concept-reserved"><?php echo esc_html($reserved); ?></span><?php endif; ?></div>
<h1 class="gz-screen-title" id="gz-concept-title"><?php echo esc_html($title); ?></h1>
<?php if (!empty($args['has_demo']) && $description !== '') : ?><p class="gz-concept-lead"><?php echo esc_html($description); ?></p><?php endif; ?>
</div>
<?php if ($has_card) : ?>
<aside class="gz-concept-card" aria-label="Характеристики концепта">
<?php foreach ($card_groups as $group) : ?><div><p class="gz-eyebrow"><?php echo esc_html($group['label']); ?></p><ul class="gz-concept-tags"><?php foreach ($group['terms'] as $name) : ?><li><?php echo esc_html($name); ?></li><?php endforeach; ?></ul></div><?php endforeach; ?>
<?php if ($colors) : ?><div><p class="gz-eyebrow">Палитра</p><div class="gz-concept-palette" aria-hidden="true"><?php foreach ($colors as $color) : ?><i style="background:<?php echo esc_attr($color); ?>"></i><?php endforeach; ?></div></div><?php endif; ?>
</aside>
<?php endif; ?>
<div class="gz-concept-actions">
<?php if ($cost !== '') : ?><p class="gz-concept-cost"><span class="gz-eyebrow">Стоимость</span><strong><?php echo esc_html($cost); ?></strong></p><?php endif; ?>
<a class="gz-button" href="#gdd-foot-host" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($offer['label']); ?></a>
</div>
</section>
<?php if (!empty($args['has_demo'])) : ?>
<div class="gz-concept-notice gz-inner" id="hint"><p><?php echo esc_html($page['notice_text']); ?></p><button class="gz-icon-action" type="button" data-hint aria-label="Закрыть уведомление">×</button></div>
<?php else : ?>
<section class="gz-concept-fallback gz-inner" aria-label="Описание концепта">
<?php if (has_post_thumbnail($post_id)) : echo get_the_post_thumbnail($post_id, 'large', array('decoding' => 'async')); endif; ?>
<?php if ($description !== '') : ?><p><?php echo esc_html($description); ?></p><?php endif; ?>
<div class="gz-entry"><?php echo apply_filters('the_content', get_post_field('post_content', $post_id)); ?></div>
<a class="gz-button" href="#gdd-foot-host" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($offer['reserved'] ? $offer['label'] : 'Забрать концепт'); ?></a>
</section>
<?php endif; ?>
</main>
</div>
<div class="gz-concept-fab is-hidden" id="fab">
<div class="gz-concept-pop" id="pop" aria-label="О концепте" hidden>
<a class="gz-button" href="#gdd-foot-host" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($offer['label']); ?></a>
<button class="gz-button gz-button--sand" type="button" data-goto="gz-concept-change">Что можно на нём изменить?</button>
<a class="gz-button gz-button--sand" href="#gdd-foot-host" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr($question); ?>">Задать вопрос</a>
</div>
<div class="gz-concept-fab-row"><button class="gz-button gz-concept-plate" id="plate" type="button" aria-expanded="false" aria-controls="pop">Про концепт</button><div class="gz-concept-view" role="group" aria-label="Версия концепта" data-view-bar hidden><button type="button" data-view="desktop" aria-pressed="true" hidden>Десктоп</button><button type="button" data-view="mobile" aria-pressed="false" hidden>Мобильная</button></div></div>
</div>
<?php get_template_part('template-parts/navigation'); ?>
</div>
</template>
</div>
