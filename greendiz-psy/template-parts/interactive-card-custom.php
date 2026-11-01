<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$gdpsy_cover = 'img/custom-interactive.webp';
?>
<article class="gz-interactive-card gz-interactive-card--custom">
	<?php if ( file_exists( get_theme_file_path( $gdpsy_cover ) ) ) : ?><figure class="gz-interactive-card-image"><img src="<?php echo esc_url( get_theme_file_uri( $gdpsy_cover ) ); ?>" alt="" decoding="async"></figure><?php endif; ?>
	<div class="gz-interactive-card-copy">
		<h2>Интерактив под вашу методику</h2>
		<p>Тест, квиз, упражнение или любой другой формат под вашу методику или потребность. Разработка с нуля и под ключ.</p>
	</div>
	<div class="gz-interactive-card-order"><button class="gz-button gz-button--white" type="button" data-interactive-custom data-contact-preset="Здравствуйте, Анастасия! Хочу заказать интерактив под свою методику." aria-haspopup="dialog" aria-controls="gz-contact-dialog">Обсудить</button></div>
</article>
