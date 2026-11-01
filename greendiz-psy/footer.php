	<?php if ( gdpsy_is_project_case() ) : ?>
	<div class="gz-project-dark">
		<?php get_template_part( 'template-parts/related-projects', null, array( 'post_id' => get_queried_object_id() ) ); ?>
		<?php get_template_part( 'template-parts/contact', null, array( 'dark' => true, 'message' => gdpsy_project_contact_message() ) ); ?>
		<?php get_template_part( 'template-parts/site-footer', null, array( 'dark' => true ) ); ?>
	</div>
	<?php elseif ( is_singular( 'interactive' ) ) : ?>
	<div class="gz-interactive-dark">
		<?php get_template_part( 'template-parts/contact', null, array( 'dark' => true, 'popup_source' => true, 'message' => gdpsy_interactive_contact_message() ) ); ?>
		<?php get_template_part( 'template-parts/site-footer', null, array( 'dark' => true ) ); ?>
	</div>
	<?php else : ?>
		<?php get_template_part( 'template-parts/site-footer' ); ?>
	<?php endif; ?>
	<?php get_template_part( 'template-parts/navigation' ); ?>
	<?php if ( ! is_front_page() && ! gdpsy_is_project_case() && ! is_singular( 'interactive' ) && ( get_page_template() !== get_theme_file_path( 'page-contacts.php' ) || post_password_required() ) ) { get_template_part( 'template-parts/contact', null, array( 'popup_source' => true ) ); } ?>
	<?php get_template_part( 'template-parts/contact-dialog' ); ?>
	<?php if ( gdpsy_is_price_page() && ! post_password_required() && (int) get_query_var( 'page' ) < 2 ) { get_template_part( 'template-parts/cost-dialogs' ); } ?>
	<?php if ( gdpsy_is_courses_possibility() && ! post_password_required() && (int) get_query_var( 'page' ) < 2 ) { get_template_part( 'template-parts/course-survey' ); } ?>
	<?php if ( is_front_page() ) { get_template_part( 'template-parts/home/dialogs' ); } ?>
	<?php get_template_part( 'template-parts/cookie' ); ?>
</div>
<?php wp_footer(); ?>
</body>
</html>







