<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="padder">
		
		<?php locate_template( array( 'pages/view/paiement-effectue/' .$page_controler->get_current_view(). '.php'  ), true ); ?>
		
	</div>
	
</div><!-- #content -->