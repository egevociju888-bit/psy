<?php
$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$title = get_the_title($post_id);
$page = gdpsy_concept_page();
$offer = gdpsy_concept_offer($post_id);
$preset = $offer['preset'];
$reserved = gdpsy_concept_reserved_text($post_id);
$cost = gdpsy_concept_cost($post_id);
$concepts_url = add_query_arg('case-type', 'consept', gdpsy_archive_url());
$faq_url = apply_filters('gdpsy_concept_faq_url', home_url('/faq/'), $post_id);
$interactives = gdpsy_portfolio_interactives($post_id, 'consept', true);
$faq = function_exists('gdpsy_home_faq_sections') ? gdpsy_home_faq_sections() : array();
?>
<div id="gdd-foot-host">
<template shadowrootmode="open">
<?php gdpsy_concept_styles( true ); ?>
<div class="gz-page gz-concept-page" id="greendiz-design-study">
<div id="gz-concept-end">
<?php get_template_part('template-parts/concept-breadcrumbs', null, array('all' => $concepts_url)); ?>
<section class="gz-concept-section gz-inner" id="gz-concept-change" aria-labelledby="gz-concept-adapt-title">
<h2 class="gz-screen-title" id="gz-concept-adapt-title">Этот концепт<br>может стать вашим сайтом.</h2>
<div class="gz-concept-adapt"><div><p class="gz-eyebrow">Адаптация</p><p>Концепт можно адаптировать под вас: заменить фото и тексты, добавить информацию об образовании, специализации и формате работы. При необходимости подключу нужный функционал — от блога и онлайн-записи до оплаты и других интеграций.</p></div><aside><p class="gz-concept-note"><?php echo esc_html($page['offer_note']); ?></p></aside></div>
<div class="gz-concept-offer"><?php if ($reserved !== '') : ?><p class="gz-concept-reserved"><?php echo esc_html($reserved); ?></p><?php endif; ?><div class="gz-concept-actions"><?php if ($cost !== '') : ?><p class="gz-concept-cost"><span class="gz-eyebrow">Стоимость</span><strong><?php echo esc_html($cost); ?></strong></p><?php endif; ?><a class="gz-button" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="<?php echo esc_attr($preset); ?>"><?php echo esc_html($offer['label']); ?></a><a class="gz-button gz-button--sand" href="<?php echo esc_url(home_url('/price/#gz-cost')); ?>">Калькулятор дополнений</a></div></div>
</section>
<?php if ($interactives) : ?>
<section class="gz-concept-section gz-concept-interactives gz-inner" aria-labelledby="gz-concept-interactives-title">
<div><div class="gz-concept-section-head"><p class="gz-eyebrow">Интерактивы</p></div><h2 class="gz-concept-title" id="gz-concept-interactives-title">Интерактивы в стоимости концепта</h2><p>Эти интерактивы включены в стоимость этого концепта.</p></div>
<ul><?php foreach ($interactives as $interactive_id) : ?><li><a href="<?php echo esc_url(get_permalink($interactive_id)); ?>"><?php echo esc_html(get_the_title($interactive_id)); ?><span aria-hidden="true">→</span></a></li><?php endforeach; ?></ul>
</section>
<?php endif; ?>
<section class="gz-concept-section gz-inner" aria-labelledby="gz-concept-process-title">
<div class="gz-concept-section-head"><p class="gz-eyebrow">Процесс</p></div>
<h2 class="gz-concept-title" id="gz-concept-process-title">Что будет после покупки</h2>
<ol class="gz-concept-steps"><li><h3>Выбираете концепт</h3><p>Согласуем изменения и дополнения.</p></li><li><h3>Я адаптирую сайт</h3><p>Вношу ваши материалы и нужные правки.</p></li><li><h3>Настраиваю функционал</h3><p>Подключаю всё необходимое.</p></li><li><h3>Вы получаете готовый сайт</h3><p>Проверяем результат и запускаем.</p></li></ol>
</section>
<?php if ($faq) : ?>
<section class="gz-concept-section gz-inner" aria-labelledby="gz-concept-faq-title">
<div class="gz-concept-section-head"><p class="gz-eyebrow">Вопросы</p><a class="gz-text-action" href="<?php echo esc_url($faq_url); ?>">Все вопросы и ответы</a></div>
<h2 class="gz-concept-title" id="gz-concept-faq-title">Что хотите узнать дальше?</h2>
<div class="gz-concept-questions"><?php foreach ($faq as $group) : ?><div><p class="gz-eyebrow"><?php echo esc_html($group['title']); ?></p><?php foreach ($group['questions'] as $item) : ?><details class="gz-quiet-details"><summary><?php echo esc_html($item['question']); ?><span aria-hidden="true">+</span></summary><div class="gz-quiet-details-content"><p><?php echo esc_html($item['answer']); ?></p></div></details><?php endforeach; ?></div><?php endforeach; ?></div>
</section>
<?php endif; ?>
<?php get_template_part('template-parts/related-concepts', null, array('post_id' => $post_id)); ?>
<?php get_template_part('template-parts/contact', null, array('dark' => true, 'message' => $preset)); ?>
<?php get_template_part('template-parts/site-footer', null, array('dark' => true)); ?>
</div>
<?php get_template_part('template-parts/contact-dialog'); ?>
<?php get_template_part('template-parts/cookie'); ?>
</div>
</template>
</div>

