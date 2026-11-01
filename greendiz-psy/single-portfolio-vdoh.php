<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main id="gz-main" class="gz-vdoh-main">
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	$gdpsy_case_title = trim( get_the_title() ) !== '' ? get_the_title() : 'Вдох';
	$gdpsy_first_page = (int) get_query_var( 'page' ) < 2;
	$gdpsy_case_intro = (string) get_post_field( 'post_excerpt', get_the_ID(), 'raw' );
	if ( trim( wp_strip_all_tags( $gdpsy_case_intro ) ) === '' ) {
		$gdpsy_case_intro = 'Сайт-приложение, в котором можно сделать паузу, заметить своё состояние и найти подходящую практику.';
	}
	?>
	<article <?php post_class( 'gz-vdoh-case' ); ?>>
		<header class="gz-vdoh-hero gz-vdoh-dark gz-inner">
			<div class="gz-breadcrumbs">
				<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => array(
					array( 'title' => 'Главная', 'url' => home_url( '/' ) ),
					array( 'title' => 'Проекты', 'url' => add_query_arg( 'case-type', 'project', gdpsy_archive_url() ) ),
					array( 'title' => $gdpsy_case_title, 'url' => '' ),
				) ) ); ?>
				<span>Кейс</span>
			</div>
			<div class="gz-vdoh-hero-grid">
				<div>
					<p class="gz-eyebrow">Некоммерческий проект · сайт-приложение</p>
					<h1><?php echo esc_html( $gdpsy_case_title ); ?></h1>
					<?php if ( ! post_password_required() && $gdpsy_first_page ) : ?><div class="gz-vdoh-lead"><?php echo wp_kses_post( wpautop( $gdpsy_case_intro ) ); ?></div><?php endif; ?>
				</div>
				<?php if ( ! post_password_required() && $gdpsy_first_page ) : ?>
				<aside class="gz-vdoh-summary" aria-label="О проекте">
					<dl>
						<div><dt>Задача</dt><dd>Психологическая поддержка, доступная с телефона</dd></div>
						<div><dt>Моя работа</dt><dd>Концепция, контент, UX/UI, иллюстрации и разработка</dd></div>
						<div><dt>Формат</dt><dd>Mobile-first PWA · HTML, CSS, JavaScript</dd></div>
					</dl>
					<a class="gz-button" href="https://vdoh.greendiz.ru/" target="_blank" rel="noopener noreferrer">Открыть «Вдох» ↗</a>
				</aside>
				<?php endif; ?>
			</div>
			<?php if ( ! post_password_required() && $gdpsy_first_page ) : ?>
			<nav class="gz-vdoh-jump" aria-label="Разделы кейса"><a href="#vdoh-experience">Опыт</a><a href="#vdoh-design">Дизайн</a><a href="#vdoh-cabinet">Личный кабинет</a><a href="#vdoh-result">Результат</a></nav>
			<?php endif; ?>
		</header>
		<?php if ( post_password_required() || ! $gdpsy_first_page ) : ?>
			<div class="gz-inner gz-vdoh-section"><div class="gz-entry"><?php the_content(); ?></div><?php wp_link_pages(); ?></div>
		<?php else : ?>
		<section class="gz-vdoh-section gz-inner gz-vdoh-intent" aria-labelledby="vdoh-task">
			<div><p class="gz-vdoh-kicker">01 / Идея</p><h2 id="vdoh-task">Поддержка начинается<br>с маленького действия.</h2></div>
			<div class="gz-vdoh-copy"><p>Когда трудно, длинная инструкция может оказаться лишней нагрузкой. «Вдох» предлагает начать с простого: подышать вместе с персонажем, отметить состояние одним касанием или выбрать короткую практику.</p><p>Я собрала в одном приложении практики саморегуляции, тесты в формате переписки и личное пространство для наблюдения за собой. Без регистрации и рекламы.</p></div>
			<dl class="gz-vdoh-facts"><div><dt>53</dt><dd>практики саморегуляции</dd></div><div><dt>5</dt><dd>тестов состояния</dd></div><div><dt>Моё</dt><dd>кабинет с дневником и статистикой</dd></div></dl>
		</section>
		<?php if ( trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) ) !== '' ) : ?>
		<div class="gz-inner gz-vdoh-editor"><div class="gz-entry"><?php the_content(); ?></div><?php wp_link_pages(); ?></div>
		<?php endif; ?>
		<section class="gz-vdoh-section gz-inner gz-vdoh-experience" id="vdoh-experience" aria-labelledby="vdoh-experience-title">
			<div class="gz-vdoh-story">
				<p class="gz-vdoh-kicker">02 / Взаимодействие</p><h2 id="vdoh-experience-title">Здесь можно<br><em>не торопиться.</em></h2>
				<p>В центре — человек и его состояние. Интерфейс помогает сделать следующий небольшой шаг.</p>
				<ol class="gz-vdoh-steps"><li><strong>Задать ритм</strong><span>Дыхание с кругом и персонажем.</span></li><li><strong>Заметить состояние</strong><span>Поле «Как ты сейчас?» вместо длинной анкеты.</span></li><li><strong>Найти практику</strong><span>Полки, подбор и короткие понятные шаги.</span></li><li><strong>Проверить себя</strong><span>Тест как переписка и объяснение результата.</span></li></ol>
				<a class="gz-text-action" href="https://vdoh.greendiz.ru/" target="_blank" rel="noopener noreferrer">Попробовать в приложении ↗</a>
			</div>
			<div class="gz-vdoh-demo-stage"><?php get_template_part( 'template-parts/vdoh-demo' ); ?></div>
		</section>
		<section class="gz-vdoh-section gz-inner" aria-labelledby="vdoh-practices-title">
			<div class="gz-vdoh-section-head"><p class="gz-vdoh-kicker">03 / Практики</p><h2 id="vdoh-practices-title">От слов —<br>к собственному опыту.</h2><p>Дыхание, работа с вниманием, телесные и письменные практики. У каждой — свой образ и понятное действие.</p></div>
			<div class="gz-vdoh-practices">
				<figure><img src="<?php echo esc_url( gdpsy_vdoh_media( 'practices-current.jpg' ) ); ?>" width="704" height="844" loading="lazy" decoding="async" alt="Актуальный каталог «Вдоха»: полки с категориями и карточки практик"><figcaption>Практики собраны по задачам</figcaption></figure>
				<figure><img src="<?php echo esc_url( gdpsy_vdoh_media( 'needs-result.jpg' ) ); ?>" width="592" height="844" loading="lazy" decoding="async" alt="Финальный экран «Чекапа потребностей»: круговая карта и рекомендации"><figcaption>«Чекап потребностей» — увидеть, чего не хватает</figcaption></figure>
				<figure><img src="<?php echo esc_url( gdpsy_vdoh_media( 'pmr-process.jpg' ) ); ?>" width="592" height="844" loading="lazy" decoding="async" alt="Процесс «Напрячь и отпустить»: группа мышц, отсчёт и смена напряжения расслаблением"><figcaption>«Напрячь и отпустить» — почувствовать разницу</figcaption></figure>
			</div>
		</section>
		<section class="gz-vdoh-section gz-inner gz-vdoh-test" aria-labelledby="vdoh-test-title">
			<div class="gz-vdoh-copy"><p class="gz-vdoh-kicker">04 / Проверить себя</p><h2 id="vdoh-test-title">Разговор<br>вместо анкеты.</h2><p>Вопросы появляются по одному, как сообщения в переписке. Можно вернуться к ответу, а после теста — прочитать пояснение и перейти к подходящим практикам.</p><p>Результат помогает сориентироваться в состоянии. Он не подменяет диагностику и обращение к специалисту.</p></div>
			<figure class="gz-vdoh-video-phone"><video controls muted playsinline loop preload="none" poster="<?php echo esc_url( gdpsy_vdoh_media( 'chat.png' ) ); ?>" aria-label="Тест «Батарейка» в формате переписки"><source src="<?php echo esc_url( gdpsy_vdoh_media( 'test-chat.mp4' ) ); ?>" type="video/mp4"><a href="<?php echo esc_url( gdpsy_vdoh_media( 'test-chat.mp4' ) ); ?>">Открыть видео теста</a></video><figcaption>Вопрос → ответ → следующий шаг</figcaption></figure>
			<figure class="gz-vdoh-result-phone"><img src="<?php echo esc_url( gdpsy_vdoh_media( 'result.png' ) ); ?>" width="1170" height="2532" loading="lazy" decoding="async" alt="Экран результата теста с объяснением и подборкой практик"><figcaption>Объяснение и практики под ответы</figcaption></figure>
		</section>
		<section class="gz-vdoh-section gz-inner gz-vdoh-design" id="vdoh-design" aria-labelledby="vdoh-design-title">
			<div class="gz-vdoh-section-head"><p class="gz-vdoh-kicker">05 / Визуальный язык</p><h2 id="vdoh-design-title">Бумага, графит<br>и один акцент.</h2><p>Тёплый фон, линейная графика и ультрамарин для действия. Lora задаёт характер заголовков, Onest помогает читать интерфейс, Caveat добавляет живые рукописные акценты.</p></div>
			<ul class="gz-vdoh-palette" aria-label="Палитра приложения"><li><i style="background:#f4efe6"></i>Бумага <span>#F4EFE6</span></li><li><i style="background:#1b1b1b"></i>Графит <span>#1B1B1B</span></li><li><i style="background:#2b44d6"></i>Ультрамарин <span>#2B44D6</span></li><li><i style="background:#161614"></i>Ночь <span>#161614</span></li></ul>
			<section class="gz-vdoh-character" aria-labelledby="vdoh-character-title">
				<div class="gz-vdoh-character-copy">
					<h3 id="vdoh-character-title">Спутник, который дышит<br>вместе с тобой.</h3>
					<p>Персонаж помогает задать ритм дыхания и сопровождает телесные практики. Теперь рядом с ним — кот Соломон: он задаёт вопросы в чатах и помогает замечать своё состояние.</p>
					<figure class="gz-vdoh-character-chat">
						<div class="gz-vdoh-chat-message"><img src="<?php echo esc_url( gdpsy_vdoh_media( 'solomon.svg' ) ); ?>" width="64" height="64" loading="lazy" decoding="async" alt="Чёрно-белый кот Соломон — собеседник в чатах «Вдоха»"><p>Как часто было весело и хорошо на душе?</p></div>
						<figcaption>Соломон задаёт вопросы в чатах</figcaption>
					</figure>
				</div>
				<figure class="gz-vdoh-character-breath"><img src="<?php echo esc_url( gdpsy_vdoh_media( 'companion-circles.png' ) ); ?>" width="404" height="386" loading="lazy" decoding="async" alt="Персонаж «Вдоха» с закрытыми глазами и рукой на животе"><figcaption>Дышит вместе с тобой</figcaption></figure>
			</section>
			<div class="gz-vdoh-theme-pair"><figure><img src="<?php echo esc_url( gdpsy_vdoh_media( 'wave-light.jpg' ) ); ?>" width="704" height="772" loading="lazy" decoding="async" alt="Режим дыхания «Волна» в светлой теме"><figcaption>Светлая тема</figcaption></figure><figure><img src="<?php echo esc_url( gdpsy_vdoh_media( 'wave-dark.jpg' ) ); ?>" width="704" height="772" loading="lazy" decoding="async" alt="Режим дыхания «Волна» в тёмной теме"><figcaption>Тёмная тема</figcaption></figure><div class="gz-vdoh-copy"><h3>Тот же характер.<br>Другой свет.</h3><p>Режим «Волна» показывает ритм дыхания плавной линией. Тёмная тема сохраняет композицию и ритм приложения. Акцент становится мягче, фон — глубже.</p></div></div>
		</section>
		<section class="gz-vdoh-section gz-inner gz-vdoh-cabinet-section" id="vdoh-cabinet" aria-labelledby="vdoh-cabinet-title">
			<div class="gz-vdoh-section-head"><p class="gz-vdoh-kicker">06 / Личное пространство</p><h2 id="vdoh-cabinet-title">Моё.<br>Больше, чем дневник.</h2><p>Отдельный кабинет без регистрации: записи о состоянии, статистика, история тестов, сохранённые материалы и личные заметки.</p></div>
			<?php get_template_part( 'template-parts/vdoh-cabinet' ); ?>
			<div class="gz-vdoh-cabinet-notes"><section><h3>Записать, как ты</h3><p>Настроение, запас сил, чувства, сон, еда и вода. Можно оставить только то, чем хочется поделиться с собой, и добавить заметку.</p></section><section><h3>Увидеть изменения</h3><p>Настроение, силы и сон на одном графике за 7 или 30 дней. Линии включаются независимо; пропуски остаются пропусками.</p></section><section><h3>Вернуться к важному</h3><p>История и сравнение результатов тестов, письма, планы и находки из практик — в своих разделах.</p></section></div>
			<p class="gz-vdoh-privacy">Личные записи хранятся в браузере на устройстве. Для переноса есть выгрузка в файл; данные можно загрузить обратно или удалить в разделе управления данными.</p>
			<a class="gz-text-action" href="https://vdoh.greendiz.ru/moe/" target="_blank" rel="noopener noreferrer">Посмотреть кабинет в приложении ↗</a>
		</section>
		<section class="gz-vdoh-section gz-inner gz-vdoh-outcome" id="vdoh-result" aria-labelledby="vdoh-result-title"><p class="gz-vdoh-kicker">07 / Результат</p><h2 id="vdoh-result-title">Приложение, которым<br>можно просто поделиться.</h2><div class="gz-vdoh-copy"><p>Из набора техник получился самостоятельный продукт: с единым визуальным языком, интерактивными практиками и личным пространством для наблюдения за собой.</p><p>Концепция, контент, UX/UI-дизайн, иллюстрации и разработка — Анастасия Гриндиз. Разработка в паре с ИИ-ассистентом Claude.</p></div><a class="gz-button" href="https://vdoh.greendiz.ru/" target="_blank" rel="noopener noreferrer">Открыть приложение ↗</a></section>
		<section class="gz-vdoh-section gz-inner gz-vdoh-dark gz-vdoh-offer" aria-labelledby="vdoh-offer-title"><div><p class="gz-eyebrow">Для вашей практики</p><h2 id="vdoh-offer-title">Что человек сможет<br>попробовать на вашем сайте?</h2><p>Короткую практику, интерактив или собственную библиотеку — под ваш подход и задачи.</p></div><a class="gz-button" href="#gz-contact" data-open-dialog="gz-contact-dialog" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить сайт или интерактив по примеру «Вдоха» для своей практики.">Обсудить идею ↗</a></section>
		<?php get_template_part( 'template-parts/portfolio-interactives' ); ?>
		<?php endif; ?>
	</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>


