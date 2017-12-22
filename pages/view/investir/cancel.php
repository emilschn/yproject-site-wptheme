<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 full">
	<p class="align-justify">
	<?php echo sprintf( __( "Vous avez choisi d'annuler votre investissement, sur le projet %s.", 'yproject' ), $page_controler->get_current_campaign()->data->post_title ); ?> 
	<?php _e( "En &ecirc;tes-vous certain(e) ?", 'yproject' ); ?>
	<br><br>
	<?php _e( "Si oui, vous serez int&eacute;gralement rembours&eacute;(e).", 'yproject' ); ?>
	</p>
	<br>
	<a href="<?php echo home_url( '/terminer-preinvestissement?confirm_cancel=1' ) . '&investment_id=' . $page_controler->get_current_investment()->get_id(); ?>" class="button transparent"><?php _e( "Annuler mon investissement", 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo home_url( '/terminer-preinvestissement?validate=1' ) . '&investment_id=' . $page_controler->get_current_investment()->get_id(); ?>" class="button red"><?php _e( "Confirmer mon investissement", 'yproject' ); ?></a>
</div>
