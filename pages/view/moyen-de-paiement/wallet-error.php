<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php _e( "Il y a eu une erreur lors du transfert d'argent entre porte-monnaies.", 'yproject' ); ?><br>
<?php _e( "Merci de nous contacter ou de", 'yproject' ); ?> <a href="<?php echo $page_controler->get_restart_link(); ?>"><?php _e( "Red&eacute;marrer l'investissement", 'yproject' ); ?></a>.