<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center db-form v3 full">
	<p class="align-justify">
		<?php echo sprintf( __( "Votre intention d'investissement sur le projet %s a bien &eacute;t&eacute; annul&eacute;e.", 'yproject' ), $page_controler->get_current_campaign()->data->post_title ); ?><br>
		<?php _e( "Vous pourrez investir &agrave; tout moment sur le projet en cliquant sur le bouton INVESTIR sur la page du projet." ); ?>
	</p>
	
	<a href="<?php echo home_url( '/mon-compte/' ); ?>" class="button blue"><?php _e( "Mon compte", 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>" class="button blue"><?php _e( "Voir le projet", 'yproject' ); ?></a>
	<br><br>
</div>
