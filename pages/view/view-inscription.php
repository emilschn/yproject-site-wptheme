<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<h1><?php _e( "Inscription sur WE DO GOOD", 'yproject' ); ?></h1>
			
			<?php locate_template( 'common/register-lightbox.php', TRUE, FALSE ); ?>
			
		</div>
	</div>
</div>