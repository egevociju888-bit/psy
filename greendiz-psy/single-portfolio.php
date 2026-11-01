<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( gdpsy_render_concept( get_queried_object_id() ) ) {
	return;
}
require get_theme_file_path( 'index.php' );
