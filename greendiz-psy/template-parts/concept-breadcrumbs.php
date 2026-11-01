<div class="gz-inner"><div class="gz-breadcrumbs"><?php get_template_part( 'template-parts/breadcrumbs', null, array( 'items' => array(
	array( 'title' => 'Главная', 'url' => home_url( '/' ) ),
	array( 'title' => 'Концепты', 'url' => add_query_arg( 'case-type', 'consept', gdpsy_archive_url() ) ),
) ) ); ?><?php if ( ! empty( $args['all'] ) ) : ?><a class="gz-breadcrumbs-all" href="<?php echo esc_url( $args['all'] ); ?>">Все концепты</a><?php endif; ?></div></div>

