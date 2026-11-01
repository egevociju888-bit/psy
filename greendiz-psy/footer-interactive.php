<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
	<div class="gz-interactive-dark">
		<?php get_template_part( 'template-parts/contact', null, array( 'dark' => true, 'popup_source' => true, 'message' => gdpsy_interactive_contact_message() ) ); ?>
		<?php get_template_part( 'template-parts/site-footer', null, array( 'dark' => true ) ); ?>
	</div>
	<?php get_template_part( 'template-parts/navigation' ); ?>
	<?php get_template_part( 'template-parts/contact-dialog' ); ?>
	<?php get_template_part( 'template-parts/cookie' ); ?>
</div>
</div>
</template>
<div slot="interactive-demo" data-gdpsy-interactive-demo><?php echo gdpsy_interactive_document( get_queried_object_id() )['body']; ?></div>
</div>
<?php wp_footer(); ?>
</body>
</html>

