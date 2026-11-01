	<footer class="gz-footer gz-inner<?php echo ( ! is_front_page() ) ? ' gz-footer--dark' : ''; ?>">
		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="gz-footer-nav" aria-label="Разделы сайта в футере"><?php gdpsy_menu( 'footer', 'footer' ); ?></nav>
		<?php endif; ?>
		<a class="gz-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Greendiz — сайты для психологов"><span class="gz-wordmark">greendiz</span><span class="gz-brand-tag">САЙТЫ ДЛЯ<br>ПСИХОЛОГОВ</span></a>
		<?php if ( has_nav_menu( 'footer_legal' ) ) : ?>
			<div class="gz-footer-legal">
				<button class="gz-footer-legal-toggle gz-text-action" type="button" aria-expanded="false" aria-controls="gz-footer-legal-list">Юридическая информация</button>
				<nav class="gz-footer-legal-list" id="gz-footer-legal-list" aria-label="Юридическая информация" hidden><?php gdpsy_menu( 'footer_legal', 'footer-legal', 1 ); ?></nav>
			</div>
		<?php endif; ?>
		<span>© 2013–<?php echo esc_html( wp_date( 'Y' ) ); ?> · greendiz.ru</span>
	</footer>
