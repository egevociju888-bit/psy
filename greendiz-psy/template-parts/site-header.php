	<a class="gz-skip-link" href="#gz-main">Перейти к содержимому</a>
	<?php if ( isset( $_GET['gdpsy-sent'] ) && is_string( $_GET['gdpsy-sent'] ) && $_GET['gdpsy-sent'] === '1' ) : ?><p class="gz-inner" role="status">Спасибо! Обращение сохранено.</p><?php endif; ?>
	<header class="gz-top gz-inner<?php echo ! empty( $args['dark'] ) ? ' gz-top--dark' : ''; ?>">
		<a class="gz-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Greendiz — сайты для психологов">
			<span class="gz-wordmark">greendiz</span><span class="gz-brand-tag">САЙТЫ ДЛЯ<br>ПСИХОЛОГОВ</span>
		</a>
		<?php if ( ! is_front_page() && has_nav_menu( 'header' ) ) : ?>
			<nav class="gz-nav" aria-label="Основные разделы"><?php gdpsy_menu( 'header', 'header', 1 ); ?></nav>
		<?php elseif ( is_front_page() ) : ?>
			<nav class="gz-nav" aria-label="Связаться"><ul class="gz-wp-menu"><li><a href="#gz-contact" data-contact-preset="Здравствуйте, Анастасия! Хочу обсудить создание сайта." data-open-dialog="gz-contact-dialog" aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить сайт</a></li></ul></nav>
		<?php endif; ?>
		<?php if ( has_nav_menu( 'primary_left' ) || has_nav_menu( 'primary_right' ) ) : ?>
			<div class="gz-menu-anchor">
				<button class="gz-menu-toggle gz-icon-action<?php echo ! empty( $args['dark'] ) ? ' gz-menu-toggle--dark' : ''; ?>" type="button" aria-controls="gz-menu" aria-haspopup="dialog" aria-expanded="false" hidden><i aria-hidden="true"></i><i aria-hidden="true"></i><span class="gz-sr-only">Открыть меню</span></button>
			</div>
		<?php endif; ?>
	</header>
	<?php if ( has_nav_menu( 'primary_left' ) || has_nav_menu( 'primary_right' ) ) : ?>
		<noscript><style>#greendiz-design-study .gz-navigation-fallback[hidden]{display:block!important}</style></noscript>
		<details class="gz-navigation-fallback gz-inner" hidden>
			<summary>Меню сайта</summary>
			<nav aria-label="Разделы сайта"><?php gdpsy_menu( 'primary_left', 'fallback-left' ); ?></nav>
			<nav aria-label="Дополнительные разделы"><?php gdpsy_menu( 'primary_right', 'fallback-right' ); ?></nav>
		</details>
	<?php endif; ?>
