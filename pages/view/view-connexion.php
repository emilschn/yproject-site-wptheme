<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<h1><?php _e( "Connexion sur WE DO GOOD", 'yproject' ); ?></h1>
			
			<div class="errors align-center" style="padding: 20px 0px;">
				<?php echo $page_controler->get_login_error_reason(); ?>
			</div>

			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
			
		</div>
	</div>
</div>