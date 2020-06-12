<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $login_init;
$login_init = $page_controler->get_login_init();
?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<h1><?php _e( 'login.INTRO', 'yproject' ); ?></h1>
			
			<?php if ( $page_controler->get_display_alert_project() ): ?>
				<br>
				<?php _e( 'login.PROJECT_ALERT', 'yproject' ); ?>
				<a href="<?php echo home_url( '/inscription/' ); ?>"><?php _e( 'login.DONT_HAVE_ACCOUNT', 'yproject' ); ?></a>.
			<?php endif; ?>
			
			<div class="errors align-center" style="padding: 20px 0px;">
				<?php echo $page_controler->get_login_error_reason(); ?>
			</div>

			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
			
		</div>
	</div>
</div>