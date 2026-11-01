<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GDPSY_VERSION', '2.0.0' );

foreach ( array( 'setup', 'helpers', 'analytics', 'navigation', 'faq', 'content', 'pages', 'possibilities', 'interactive', 'blog', 'article', 'submissions', 'concepts', 'concept-filters', 'concept-reservation', 'projects', 'enqueue' ) as $gdpsy_module ) {
	require_once get_theme_file_path( 'inc/' . $gdpsy_module . '.php' );
}
unset( $gdpsy_module );



