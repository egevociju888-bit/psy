<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
$post_id = absint( $args['post_id'] ?? 0 );
if ( post_password_required( $post_id ) ) { return; }
if ( gdpsy_interactive_document( $post_id ) ) : ?>
<slot name="interactive-demo"></slot>
<?php endif;
do_action( 'gdpsy_interactive_demo', $post_id );

