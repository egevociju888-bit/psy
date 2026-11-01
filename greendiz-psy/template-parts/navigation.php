<?php if ( has_nav_menu( 'primary_left' ) || has_nav_menu( 'primary_right' ) ) : ?>
	<dialog class="gz-menu" id="gz-menu" aria-labelledby="gz-menu-title">
		<div class="gz-menu-head"><h2 id="gz-menu-title">Навигация</h2></div>
		<div class="gz-menu-columns">
			<?php if ( has_nav_menu( 'primary_left' ) ) : ?>
				<nav class="gz-menu-site" aria-labelledby="gz-menu-site-title">
					<h3 class="gz-menu-label" id="gz-menu-site-title">Разделы сайта</h3>
					<?php gdpsy_menu( 'primary_left', 'left' ); ?>
				</nav>
			<?php endif; ?>
			<?php if ( has_nav_menu( 'primary_right' ) ) : ?>
				<details class="gz-menu-home gz-menu-collapse" open>
					<summary class="gz-menu-label">На главной <span class="line" aria-hidden="true"></span><span class="gz-menu-chevron" aria-hidden="true"></span></summary>
					<div class="gz-menu-collapse-content">
						<nav class="gz-menu-home-links" aria-label="Разделы главной"><?php gdpsy_menu( 'primary_right', 'right' ); ?></nav>
					</div>
				</details>
			<?php endif; ?>
		</div>
	</dialog>
<?php endif; ?>
