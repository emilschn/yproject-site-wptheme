<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<?php if ( ATCF_CrowdFunding::get_platform_context() != 'wedogood' ): ?>
<div class="align-center" style="padding: 15px 0px; background: #FFFFFF;">
	<a href="<?php echo wp_logout_url(); ?>" class="button"><?php _e( "D&eacute;connexion", 'yproject' ); ?></a>
</div>
<?php endif; ?>

<div id="content">

	<?php if ( $page_controler->is_displayed_user_override_not_found() ): ?>
	<div class="wdg-message error">
		<?php _e( "L'utilisateur que vous essayez d'afficher n'existe pas...", 'yproject' ); ?>
	</div>
	
	<?php else: ?>
	<?php locate_template( array( 'pages/view/mon-compte/nav.php'  ), true ); ?>	
	<?php locate_template( array( 'pages/view/mon-compte/content.php'  ), true ); ?>
	<?php endif; ?>
</div>