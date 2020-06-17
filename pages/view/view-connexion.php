<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">

	<?php if ( $page_controler->get_display_alert_project() ): ?>
		<div class="alert-connect-project">
			<?php _e( 'login.PROJECT_ALERT', 'yproject' ); ?>
			<a href="<?php echo home_url( '/inscription/' ); ?>"><?php _e( 'login.DONT_HAVE_ACCOUNT', 'yproject' ); ?></a>.
		</div>
	<?php endif; ?>

	<div id="app" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-currentview="Signin"></div>

</div>