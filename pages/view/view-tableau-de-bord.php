<?php
global $stylesheet_directory_uri;
/**
 * @var WDG_Page_Controler_Project_Dashboard
 */
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	<?php if ( $page_controler->can_access() ): ?>
		<?php locate_template( array( 'pages/view/tableau-de-bord/nav.php'  ), true ); ?>
		<?php locate_template( array( 'pages/view/tableau-de-bord/main.php'  ), true ); ?>
		<?php if ( $page_controler->get_show_lightbox_welcome() ) : ?>
			<?php locate_template( array( 'pages/view/tableau-de-bord/lightbox-welcome.php'  ), true ); ?>
		<?php endif; ?>
	
	<?php else: ?>
		<?php _e( "Vous ne pouvez pas acc&eacute;der au tableau de bord.", 'yproject' ); ?>
	
	<?php endif; ?>
</div>
