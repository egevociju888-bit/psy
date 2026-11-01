<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<main class="gz-page-main gz-inner gz-notfound" id="gz-main">
	<p class="gz-notfound-code" aria-hidden="true">Ошибка 404</p>
	<div class="gz-notfound-figure" aria-hidden="true">
		<span class="gz-notfound-digits">404</span>
		<svg class="gz-notfound-slash" viewBox="0 0 100 180" preserveAspectRatio="none" fill="none" focusable="false">
			<path d="M1 179 99 1" stroke="currentColor" stroke-width="2" vector-effect="non-scaling-stroke"></path>
		</svg>
	</div>
	<header class="gz-page-heading gz-notfound-heading">
		<h1>Страница <em>не найдена</em></h1>
		<div class="gz-page-intro">
			<p>Возможно, ссылка устарела или в адресе опечатка. Вернитесь на главную — там всё на месте.</p>
		</div>
	</header>
	<div class="gz-notfound-actions">
		<a class="gz-button gz-button--large" href="<?php echo esc_url( home_url( '/' ) ); ?>">На главную</a>
	</div>
</main>
<?php get_footer(); ?>
