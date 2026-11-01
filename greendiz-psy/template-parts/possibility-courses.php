<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$gdpsy_full = ! post_password_required() && (int) get_query_var( 'page' ) < 2;
$gdpsy_intro = $gdpsy_full ? (string) get_post_field( 'post_excerpt', get_the_ID(), 'raw' ) : '';
if ( $gdpsy_full && trim( wp_strip_all_tags( $gdpsy_intro ) ) === '' ) {
	$gdpsy_intro = 'Продажа программ, доступ к урокам и личный кабинет на вашем сайте.';
}
$gdpsy_course_slides = array(
	array(
		'image'       => 'course-program-v2',
		'title'       => 'Выбор программы',
		'alt'         => 'Пример программы «Бережно к себе»: темы уроков и выбор курса.',
		'heading'     => 'Выбирает программу',
		'description' => 'Читает, для кого она, какие темы включает и как проходит обучение. Знакомится с форматом участия и условиями покупки.',
	),
	array(
		'image'       => 'course-access-v2',
		'title'       => 'Оплата и доступ',
		'alt'         => 'Пример личного кабинета: оплата прошла, программа доступна, можно перейти к урокам.',
		'heading'     => 'Оплачивает и получает доступ',
		'description' => 'Покупает программу и переходит к её материалам. Способ оплаты и порядок доступа подбираются под ваш формат работы.',
	),
	array(
		'image'       => 'course-learning-v2',
		'title'       => 'Уроки и материалы',
		'alt'         => 'Пример урока «Заметить своё состояние»: видео, аудиопрактика и памятка в PDF.',
		'heading'     => 'Проходит уроки',
		'description' => 'Смотрит видео, слушает аудио, скачивает материалы. Возвращается в личный кабинет, чтобы продолжить обучение.',
	),
);
$gdpsy_toc = array(
	'gz-courses-site'     => 'Чтобы зарабатывать не только сессиями',
	'gz-courses-path'     => 'От покупки до урока',
	'gz-courses-features' => 'Уроки и материалы',
	'gz-courses-cabinet'  => 'Личный кабинет',
	'gz-courses-survey'   => 'Скидка за участие в опросе',
);
?>
<div class="gz-inner"><div class="gz-breadcrumbs">
	<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => gdpsy_page_breadcrumbs( get_the_ID() ) ) ); ?>
</div></div>
<article <?php post_class( 'gz-article-single gz-inner gz-possibility-detail gz-possibility-detail--courses' ); ?> aria-labelledby="gz-possibility-title">
	<header class="gz-blog-intro">
		<div class="gz-article-heading"><h1 id="gz-possibility-title"><?php the_title(); ?></h1></div>
		<?php if ( $gdpsy_intro !== '' ) : ?><div class="gz-blog-intro-copy"><?php echo wp_kses_post( wpautop( $gdpsy_intro ) ); ?><?php if ( $gdpsy_full ) : ?><small class="gz-course-status">Готового модуля пока нет. Изучаю спрос; состав, стоимость и дата запуска не определены.</small><?php endif; ?></div><?php endif; ?>
	</header>
	<?php if ( $gdpsy_full ) : ?>
	<div class="gz-article-layout">
		<aside class="gz-article-sidebar" aria-label="Навигация по странице и возможностям">
			<div class="gz-article-sticky">
				<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
					<summary id="gz-article-toc-title">Содержание<span aria-hidden="true">+</span></summary>
					<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-toc-title"><ol class="gz-article-toc">
						<?php foreach ( $gdpsy_toc as $gdpsy_id => $gdpsy_label ) : ?>
						<li><a href="#<?php echo esc_attr( $gdpsy_id ); ?>"><?php echo esc_html( $gdpsy_label ); ?></a></li>
						<?php endforeach; ?>
					</ol></nav></div>
				</details>
				<?php $gdpsy_pages = gdpsy_possibility_pages(); if ( $gdpsy_pages ) : ?>
				<details class="gz-article-panel gz-quiet-details" data-details-static="(min-width: 1101px)" open>
					<summary id="gz-article-categories-title">Возможности сайта<span aria-hidden="true">+</span></summary>
					<div class="gz-quiet-details-content"><nav aria-labelledby="gz-article-categories-title"><ul class="gz-blog-category-list">
						<?php $gdpsy_overview = gdpsy_page_url( 'possibilities' ); if ( $gdpsy_overview !== '' ) : ?><li><a href="<?php echo esc_url( $gdpsy_overview ); ?>">Все возможности</a></li><?php endif; ?>
						<?php foreach ( $gdpsy_pages as $gdpsy_page ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $gdpsy_page ) ); ?>"<?php echo $gdpsy_page->ID === get_the_ID() ? ' aria-current="page"' : ''; ?>><?php echo esc_html( get_the_title( $gdpsy_page ) ); ?></a></li>
						<?php endforeach; ?>
					</ul></nav></div>
				</details>
				<?php endif; ?>
			</div>
		</aside>
		<div class="gz-article-body">
			<div class="gz-entry gz-possibility-copy">
				<?php if ( trim( (string) get_post_field( 'post_content', get_the_ID(), 'raw' ) ) !== '' ) : ?><div class="gz-possibility-editor"><?php the_content(); ?></div><?php endif; ?>
				<section class="gz-possibility-section">
					<h2 id="gz-courses-site" tabindex="-1">Чтобы зарабатывать не только сессиями</h2>
					<p>Курс или интенсив — ещё один формат работы с вашей аудиторией. Вы собираете знания и материалы в программу, которую можно продавать на своём сайте.</p>
					<p>Такая программа позволяет предложить клиентам самостоятельную работу с уроками или обучение с вашей обратной связью. А вам — получать доход от продажи материалов, дополняя индивидуальные встречи.</p>
					<ol class="gz-course-benefits">
						<li><span class="gz-course-benefit-number" aria-hidden="true">01</span><div><h3>Продавать свою программу</h3><p>Предложить записанный курс или интенсив по теме вашей практики.</p></div></li>
						<li><span class="gz-course-benefit-number" aria-hidden="true">02</span><div><h3>Использовать материалы повторно</h3><p>Одни и те же записанные уроки доступны разным участникам.</p></div></li>
						<li><span class="gz-course-benefit-number" aria-hidden="true">03</span><div><h3>Собрать обучение на своём сайте</h3><p>Описание программы, покупка, уроки и личный кабинет в одном месте.</p></div></li>
					</ol>
				</section>
				<section class="gz-possibility-section">
					<h2 id="gz-courses-path" tabindex="-1">От покупки до первого урока</h2>
					<p>Пример возможного сценария будущего модуля: клиент оплачивает программу и получает доступ к урокам. Путь участника мог бы выглядеть так:</p>
					<div class="gz-course-journey" data-course-slider>
						<ol class="gz-course-steps">
							<?php foreach ( $gdpsy_course_slides as $gdpsy_slide_index => $gdpsy_slide ) : ?>
							<li>
								<h3><button type="button" data-course-step aria-controls="gz-course-description-<?php echo esc_attr( $gdpsy_slide_index + 1 ); ?>" disabled><span class="gz-course-step-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $gdpsy_slide_index + 1 ) ); ?></span><span><?php echo esc_html( $gdpsy_slide['heading'] ); ?></span></button></h3>
								<p id="gz-course-description-<?php echo esc_attr( $gdpsy_slide_index + 1 ); ?>" data-course-description><?php echo esc_html( $gdpsy_slide['description'] ); ?></p>
							</li>
							<?php endforeach; ?>
						</ol>
						<div class="gz-course-demo">
							<p class="gz-course-demo-label">Пример интерфейса</p>
							<div class="gz-course-slider" role="region" aria-roledescription="карусель" aria-label="Пример продажи и прохождения курса">
								<div class="gz-course-slider-viewport" id="gz-course-slider-viewport" data-course-viewport tabindex="0" aria-label="Иллюстрации курса: листайте или используйте стрелки клавиатуры">
									<?php foreach ( $gdpsy_course_slides as $gdpsy_slide_index => $gdpsy_slide ) : ?>
									<figure id="gz-course-slide-<?php echo esc_attr( $gdpsy_slide_index + 1 ); ?>" class="gz-course-slide" data-course-slide role="group" aria-roledescription="слайд" aria-label="<?php echo esc_attr( ( $gdpsy_slide_index + 1 ) . ' из ' . count( $gdpsy_course_slides ) ); ?>">
										<img src="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/' . $gdpsy_slide['image'] . '.webp' ) ); ?>" srcset="<?php echo esc_url( get_theme_file_uri( 'media/possibilities/' . $gdpsy_slide['image'] . '-small.webp' ) ); ?> 720w, <?php echo esc_url( get_theme_file_uri( 'media/possibilities/' . $gdpsy_slide['image'] . '.webp' ) ); ?> 1440w" sizes="(min-width: 1100px) and (max-width: 1359px) 520px, (min-width: 800px) 460px, (min-width: 600px) 520px, calc(100vw - 40px)" width="1440" height="1080" alt="<?php echo esc_attr( $gdpsy_slide['alt'] ); ?>" loading="lazy" decoding="async">
										<figcaption><?php echo esc_html( $gdpsy_slide['title'] ); ?></figcaption>
									</figure>
									<?php endforeach; ?>
								</div>
								<div class="gz-course-slider-controls" data-course-controls hidden>
									<span data-course-counter aria-live="polite" aria-atomic="true">1 / 3</span>
									<div class="gz-course-slider-arrows">
										<button class="gz-icon-action" type="button" data-course-prev aria-label="Предыдущая иллюстрация" aria-controls="gz-course-slider-viewport" disabled><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 12H4m7-7-7 7 7 7"/></svg></button>
										<button class="gz-icon-action" type="button" data-course-next aria-label="Следующая иллюстрация" aria-controls="gz-course-slider-viewport"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 12h16m-7-7 7 7-7 7"/></svg></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section class="gz-possibility-section">
					<h2 id="gz-courses-features" tabindex="-1">Уроки, материалы и обратная связь</h2>
					<ul class="gz-course-materials">
						<li><strong>Видео, аудио и PDF</strong><p>Форматы можно сочетать внутри программы: объяснение в видеоуроке, аудиопрактика для самостоятельной работы и памятка для скачивания.</p></li>
						<li><strong>Сразу или по расписанию</strong><p>Уроки можно открывать целиком после покупки или постепенно, по расписанию. Вы определяете последовательность и темп программы.</p></li>
						<li><strong>Задания и обратная связь</strong><p>К урокам можно добавить задания, чтобы участники применяли материал на практике. Если вы хотите сопровождать обучение, можно предусмотреть отправку работ и обратную связь от вас.</p></li>
					</ul>
				</section>
				<section class="gz-possibility-section">
					<h2 id="gz-courses-cabinet" tabindex="-1">Личный кабинет участника</h2>
					<p>Покупки и прогресс — в одном месте. Участник находит приобретённые программы, видит пройденные уроки и возвращается к нужным материалам.</p>
					<p>Для регулярно пополняемой подборки можно предусмотреть подписку на материалы. Это ещё один способ организовать доступ к вашим программам.</p>
				</section>
			</div>
			<section class="gz-article-cta gz-course-offer" aria-labelledby="gz-courses-survey">
				<p class="gz-course-offer-value">20<span>%</span></p>
				<div class="gz-course-offer-content">
					<h2 id="gz-courses-survey" tabindex="-1">Скидка за участие <em>в опросе</em></h2>
					<p>Расскажите, нужна ли вам продажа курсов на своём сайте и какие возможности пригодятся. Ваши ответы помогут мне определить состав услуги.</p>
					<p class="gz-course-offer-terms">При запуске модуля скидка 20% будет действовать на его подключение. Скидка закрепляется за вашим email.</p>
					<button class="gz-primary gz-button gz-button--white" type="button" data-open-dialog="gz-course-survey" aria-haspopup="dialog" aria-controls="gz-course-survey">Рассказать о своих задачах</button>
					<p class="gz-course-offer-note">В опросе можно отдельно выбрать письмо о запуске.</p>
				</div>
			</section>
		</div>
	</div>
	<?php else : ?>
	<div class="gz-entry"><?php the_content(); ?></div>
	<?php endif; ?>
	<?php wp_link_pages( array( 'before' => '<nav class="gz-page-links" aria-label="Страницы материала">', 'after' => '</nav>' ) ); ?>
</article>



