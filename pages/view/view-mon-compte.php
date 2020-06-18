<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<div id="content">

	<?php if ( $page_controler->is_displayed_user_override_not_found() ): ?>
	<div class="wdg-message error">
		<?php _e( 'account.OVERRIDE_USER_DOESNT_EXIST', 'yproject' ); ?>
	</div>

	<?php elseif ( $page_controler->is_displayed_user_override_organization() ): ?>
	<div class="wdg-message error">
		<?php _e( 'account.OVERRIDE_USER_IS_ORGANIZATION', 'yproject' ); ?><br>
		<?php _e( 'account.OVERRIDE_ORGANIZATION_MANAGER_EMAIL', 'yproject' ); ?> <?php echo $page_controler->user_override_organization_manager_mail(); ?>.
	</div>
	
	<?php else: ?>
	<?php locate_template( array( 'pages/view/mon-compte/nav.php'  ), true ); ?>	
	<?php locate_template( array( 'pages/view/mon-compte/content.php'  ), true ); ?>
	<?php endif; ?>
</div>