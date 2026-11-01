<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_template_part( 'template-parts/articles', null, array( 'articles' => gdpsy_selected( 'articles', 'post' ) ) );
