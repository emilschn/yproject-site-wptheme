<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
?>

<h2><?php _e( "Investissements de", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( "Les informations ci-dessous sont celles de votre compte personnel.", 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
	<?php _e( "Retrouvez celles de vos organisations en utilisant le menu.", 'yproject' ); ?>
	<?php endif; ?>
</p>
	
<div id="ajax-loader-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGUser_displayed->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

