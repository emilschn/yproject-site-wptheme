<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php _e( "Il y a eu une erreur lors du transfert d'argent entre porte-monnaies.", 'yproject' ); ?><br>
<?php _e( "Merci de nous contacter ou de", 'yproject' ); ?> <a href="<?php echo $page_controler->get_restart_link(); ?>"><?php _e( "Red&eacute;marrer l'investissement", 'yproject' ); ?></a>.