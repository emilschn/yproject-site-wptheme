<?php
/**
 * @var WDG_Page_Controler_Validation_Email
 */
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<?php locate_template( array( 'pages/view/activer-compte/content-' . $page_controler->get_current_view() . '.php'  ), true ); ?>

		</div>
	</div>
</div>