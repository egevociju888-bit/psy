<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gdpsy_asset_version( $path ) {
	$file = get_theme_file_path( $path );
	return is_file( $file ) ? (string) filemtime( $file ) : GDPSY_VERSION;
}

function gdpsy_shadow_css( $set, $names ) {
	static $compiled = array();
	if ( isset( $compiled[ $set ] ) ) {
		return $compiled[ $set ];
	}
	$css = '';
	foreach ( $names as $name ) {
		$path = get_theme_file_path( 'css/' . $name . '.css' );
		if ( is_readable( $path ) ) {
			$css .= file_get_contents( $path ) . "\n";
		}
	}
	$css = strtr( $css, array( 'Onest' => 'GDPSY Onest', 'Lora' => 'GDPSY Lora', 'Playfair Display' => 'GDPSY Playfair Display' ) );
	preg_match_all( '/@font-face\s*\{[^}]+\}/s', $css, $faces );
	$fonts = str_replace( 'url(../fonts/', 'url(' . esc_url( get_theme_file_uri( 'fonts/' ) ), implode( "\n", $faces[0] ) );
	$css = preg_replace( '/@font-face\s*\{[^}]+\}/s', '', $css );
	$css = preg_replace( '/html\[data-serif=(["\']?)lora\1\]/', ':host', $css );
	$css = preg_replace( '/\bhtml(?=\s*[{,])/', ':host', $css );
	$compiled[ $set ] = array( 'css' => $css, 'fonts' => $fonts, 'url' => gdpsy_shadow_css_file( $set, $css ) );
	return $compiled[ $set ];
}

function gdpsy_shadow_css_file( $set, $css ) {
	$uploads = wp_upload_dir( null, false );
	if ( ! empty( $uploads['error'] ) || ! preg_match( '/^[a-z]+$/', $set ) ) {
		return '';
	}
	$dir = trailingslashit( $uploads['basedir'] ) . 'gdpsy-cache';
	$name = $set . '-' . substr( md5( $css ), 0, 12 ) . '.css';
	$file = $dir . '/' . $name;
	if ( ! is_file( $file ) ) {
		if ( ! wp_mkdir_p( $dir ) ) {
			return '';
		}
		$tmp = $file . '.' . wp_generate_password( 8, false, false ) . '.tmp';
		if ( file_put_contents( $tmp, $css, LOCK_EX ) === false || ! rename( $tmp, $file ) ) {
			if ( is_file( $tmp ) ) {
				unlink( $tmp );
			}
			return '';
		}
		$old = glob( $dir . '/' . $set . '-*.css' ) ?: array();
		usort( $old, function ( $a, $b ) {
			return filemtime( $b ) <=> filemtime( $a );
		} );
		foreach ( array_slice( $old, 2 ) as $stale ) {
			if ( is_file( $stale ) ) {
				unlink( $stale );
			}
		}
	}
	return set_url_scheme( trailingslashit( $uploads['baseurl'] ) . 'gdpsy-cache/' . $name );
}

function gdpsy_enqueue() {
	$is_faq = get_page_template() === get_theme_file_path( 'page-faq.php' );
	wp_enqueue_style( 'gdpsy-design', get_theme_file_uri( 'css/styles.css' ), array(), gdpsy_asset_version( 'css/styles.css' ) );
	$theme_deps = array( 'gdpsy-design' );
	foreach ( array( 'home' => is_front_page(), 'price' => gdpsy_is_price_page() ) as $page => $active ) {
		if ( $active ) {
			wp_enqueue_style( 'gdpsy-page-' . $page, get_theme_file_uri( 'css/' . $page . '.css' ), array( 'gdpsy-design' ), gdpsy_asset_version( 'css/' . $page . '.css' ) );
			$theme_deps[] = 'gdpsy-page-' . $page;
		}
	}
	wp_enqueue_style( 'gdpsy-theme', get_theme_file_uri( 'css/theme.css' ), $theme_deps, gdpsy_asset_version( 'css/theme.css' ) );
	if ( is_404() ) {
		wp_enqueue_style( 'gdpsy-not-found', get_theme_file_uri( 'css/not-found.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/not-found.css' ) );
	}
	if ( is_front_page() ) {
		wp_enqueue_style( 'gdpsy-home-offers', get_theme_file_uri( 'css/home-offers.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/home-offers.css' ) );
	}
	if ( gdpsy_is_project_case() ) {
		wp_enqueue_style( 'gdpsy-projects', get_theme_file_uri( 'css/projects.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/projects.css' ) );
	}
	if ( gdpsy_is_gipnopro_case() ) {
		wp_enqueue_style( 'gdpsy-project-gipnopro', get_theme_file_uri( 'css/project-gipnopro.css' ), array( 'gdpsy-projects' ), gdpsy_asset_version( 'css/project-gipnopro.css' ) );
		wp_enqueue_script( 'gdpsy-project-gipnopro', get_theme_file_uri( 'js/project-gipnopro.js' ), array(), gdpsy_asset_version( 'js/project-gipnopro.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( gdpsy_is_vdoh_case() ) {
		wp_enqueue_style( 'gdpsy-vdoh-demo', get_theme_file_uri( 'css/vdoh-demo.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/vdoh-demo.css' ) );
		wp_enqueue_style( 'gdpsy-project-vdoh', get_theme_file_uri( 'css/project-vdoh.css' ), array( 'gdpsy-vdoh-demo' ), gdpsy_asset_version( 'css/project-vdoh.css' ) );
		wp_enqueue_script( 'gdpsy-project-vdoh', get_theme_file_uri( 'js/project-vdoh.js' ), array(), gdpsy_asset_version( 'js/project-vdoh.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	wp_enqueue_script( 'gdpsy-content', get_theme_file_uri( 'js/content.js' ), array(), gdpsy_asset_version( 'js/content.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	if ( gdpsy_is_possibilities_page() ) {
		wp_enqueue_style( 'gdpsy-possibilities', get_theme_file_uri( 'css/possibilities.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/possibilities.css' ) );
	}
	if ( is_singular( 'interactive' ) || gdpsy_is_possibilities_overview() || gdpsy_is_blog_archive() || gdpsy_is_article() || gdpsy_is_blog_possibility() || gdpsy_is_library_possibility() || gdpsy_is_courses_possibility() || $is_faq ) {
		wp_enqueue_style( 'gdpsy-blog', get_theme_file_uri( 'css/blog.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/blog.css' ) );
	}
	if ( gdpsy_is_blog_archive() ) {
		wp_enqueue_script( 'gdpsy-blog', get_theme_file_uri( 'js/blog.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/blog.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_singular( 'interactive' ) || gdpsy_is_possibilities_overview() || gdpsy_is_article() || gdpsy_is_blog_possibility() || gdpsy_is_library_possibility() || gdpsy_is_courses_possibility() || $is_faq ) {
		wp_enqueue_style( 'gdpsy-article', get_theme_file_uri( 'css/article.css' ), array( 'gdpsy-blog' ), gdpsy_asset_version( 'css/article.css' ) );
		wp_enqueue_script( 'gdpsy-article', get_theme_file_uri( 'js/article.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/article.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( $is_faq ) {
		wp_enqueue_style( 'gdpsy-faq', get_theme_file_uri( 'css/faq.css' ), array( 'gdpsy-article' ), gdpsy_asset_version( 'css/faq.css' ) );
	}
	if ( gdpsy_is_possibilities_overview() || gdpsy_is_blog_possibility() ) {
		wp_enqueue_style( 'gdpsy-possibilities-overview', get_theme_file_uri( 'css/possibilities-overview.css' ), array( 'gdpsy-article', 'gdpsy-possibilities' ), gdpsy_asset_version( 'css/possibilities-overview.css' ) );
	}
	if ( gdpsy_is_blog_possibility() ) {
		wp_enqueue_style( 'gdpsy-possibility-blog-overview', get_theme_file_uri( 'css/possibility-blog-overview.css' ), array( 'gdpsy-possibilities-overview' ), gdpsy_asset_version( 'css/possibility-blog-overview.css' ) );
	}
	if ( gdpsy_is_library_possibility() || gdpsy_is_courses_possibility() ) {
		wp_enqueue_style( 'gdpsy-possibility-blog', get_theme_file_uri( 'css/possibility-blog.css' ), array( 'gdpsy-article', 'gdpsy-possibilities' ), gdpsy_asset_version( 'css/possibility-blog.css' ) );
	}
	if ( gdpsy_is_interactive() ) {
		$dependencies = is_singular( 'interactive' ) ? array( 'gdpsy-article' ) : array( 'gdpsy-theme' );
		wp_enqueue_style( 'gdpsy-interactive', get_theme_file_uri( 'css/interactive.css' ), $dependencies, gdpsy_asset_version( 'css/interactive.css' ) );
		wp_enqueue_script( 'gdpsy-interactive', get_theme_file_uri( 'js/interactive.js' ), array(), gdpsy_asset_version( 'js/interactive.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( gdpsy_is_courses_possibility() ) {
		wp_enqueue_style( 'gdpsy-possibility-courses', get_theme_file_uri( 'css/possibility-courses.css' ), array( 'gdpsy-possibility-blog' ), gdpsy_asset_version( 'css/possibility-courses.css' ) );
		wp_enqueue_script( 'gdpsy-possibility-courses', get_theme_file_uri( 'js/possibility-courses.js' ), array(), gdpsy_asset_version( 'js/possibility-courses.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_singular( 'interactive' ) || gdpsy_is_article() || gdpsy_is_project_case() || is_page_template( 'page-possibility.php' ) ) {
		wp_enqueue_script( 'gdpsy-articles', get_theme_file_uri( 'js/articles.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/articles.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_post_type_archive( 'portfolio' ) || is_front_page() ) {
		wp_enqueue_style( 'gdpsy-work-card', get_theme_file_uri( 'css/work-card.css' ), array( 'gdpsy-theme' ), gdpsy_asset_version( 'css/work-card.css' ) );
	}
	if ( is_post_type_archive( 'portfolio' ) ) {
		wp_enqueue_style( 'gdpsy-portfolio', get_theme_file_uri( 'css/portfolio.css' ), array( 'gdpsy-work-card' ), gdpsy_asset_version( 'css/portfolio.css' ) );
	}
	wp_enqueue_script( 'gdpsy-forms', get_theme_file_uri( 'js/forms.js' ), array(), gdpsy_asset_version( 'js/forms.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_add_inline_script( 'gdpsy-forms', 'window.GdpsyFormConfig = ' . wp_json_encode( array( 'url' => admin_url( 'admin-ajax.php' ) ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . ';', 'before' );
	wp_enqueue_script( 'gdpsy-contact', get_theme_file_uri( 'js/contact.js' ), array( 'gdpsy-forms' ), gdpsy_asset_version( 'js/contact.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gdpsy-navigation', get_theme_file_uri( 'js/navigation.js' ), array(), gdpsy_asset_version( 'js/navigation.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_enqueue_script( 'gdpsy-common', get_theme_file_uri( 'js/common.js' ), array( 'gdpsy-navigation', 'gdpsy-contact' ), gdpsy_asset_version( 'js/common.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	if ( gdpsy_is_price_page() || gdpsy_is_courses_possibility() ) {
		wp_enqueue_script( 'gdpsy-course-survey', get_theme_file_uri( 'js/course-survey.js' ), array( 'gdpsy-common', 'gdpsy-forms' ), gdpsy_asset_version( 'js/course-survey.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_front_page() || gdpsy_is_price_page() ) {
		wp_enqueue_script( 'gdpsy-quiz', get_theme_file_uri( 'js/quiz.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/quiz.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( is_front_page() ) {
		wp_enqueue_script( 'gdpsy-home-quiz', get_theme_file_uri( 'js/home-quiz.js' ), array( 'gdpsy-quiz' ), gdpsy_asset_version( 'js/home-quiz.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	if ( gdpsy_is_price_page() ) {
		wp_enqueue_script( 'gdpsy-cost', get_theme_file_uri( 'js/cost.js' ), array( 'gdpsy-common', 'gdpsy-forms', 'gdpsy-quiz' ), gdpsy_asset_version( 'js/cost.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_enqueue_script( 'gdpsy-cabinet-survey', get_theme_file_uri( 'js/cabinet-survey.js' ), array( 'gdpsy-common', 'gdpsy-forms' ), gdpsy_asset_version( 'js/cabinet-survey.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}
	wp_enqueue_script( 'gdpsy-cookie', get_theme_file_uri( 'js/cookie.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/cookie.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	if ( gdpsy_has_landing() ) {
		if ( is_front_page() ) {
			wp_enqueue_script( 'gdpsy-audio', get_theme_file_uri( 'js/breathe-audio.js' ), array(), gdpsy_asset_version( 'js/breathe-audio.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
			wp_enqueue_script( 'gdpsy-home', get_theme_file_uri( 'js/home.js' ), array( 'gdpsy-common', 'gdpsy-audio' ), gdpsy_asset_version( 'js/home.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		} else {
			wp_enqueue_script( 'gdpsy-portfolio', get_theme_file_uri( 'js/portfolio.js' ), array( 'gdpsy-common' ), gdpsy_asset_version( 'js/portfolio.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'gdpsy_enqueue' );






