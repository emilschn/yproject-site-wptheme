<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $WDGOrganization;
?>
<h2><?php _e( "Investissements de", 'yproject' ); ?>  <?php echo $WDGOrganization->get_name(); ?></h2>
	
<div id="ajax-loader-<?php echo $WDGOrganization->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGOrganization->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

