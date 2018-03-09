<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<h1><?php _e( "Connexion sur WE DO GOOD", 'yproject' ); ?></h1>
			
			<?php if ( $page_controler->get_display_alert_project() ): ?>
				<br>
				<?php _e( "Il est n&eacute;cessaire d'&ecirc;tre identifi&eacute; avec son compte WE DO GOOD pour voter ou investir sur un projet.", 'yproject' ); ?>
				<a href="<?php echo home_url( '/inscription' ); ?>"><?php _e( "Je n'ai pas de compte" ); ?></a>.
			<?php endif; ?>
			
			<div class="errors align-center" style="padding: 20px 0px;">
				<?php echo $page_controler->get_login_error_reason(); ?>
			</div>

			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
			
		</div>
	</div>
</div>