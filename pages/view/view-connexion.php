<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">

	<?php if ( $page_controler->get_display_alert_project() ): ?>
		<div class="alert-connect-project">
			<?php _e( "Il est n&eacute;cessaire d'&ecirc;tre identifi&eacute; avec son compte WE DO GOOD pour &eacute;valuer ou investir sur un projet.", 'yproject' ); ?>
			<a href="<?php echo home_url( '/inscription/' ); ?>"><?php _e( "Je n'ai pas de compte" ); ?></a>.
		</div>
	<?php endif; ?>

	<div id="app" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-currentview="Signin"></div>

</div>