<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$WDGUserPendingPreinvestment = $page_controler->get_show_user_pending_investment();
?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<p class="align-center">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-stat-loupe.png" width="150">
	</p>
	
	<p class="align-justify">
		<?php echo sprintf( __( "Vous aviez l'intention d'investir %s &euro; sur le projet %s.", 'yproject' ), $WDGUserPendingPreinvestment->get_saved_amount(), $WDGUserPendingPreinvestment->get_saved_campaign()->data->post_title ); ?>
	</p>
	
	<p class="align-justify">
		<?php _e( "Votre compte a &eacute;t&eacute; authentifi&eacute; par notre prestataire de paiement. Nous vous proposons donc de reprendre votre d&eacute;marche d'investissement.", 'yproject' ); ?><br>
	</p>
	
	<form class="db-form v3">
		<a href="<?php echo home_url( '/investir/?init_with_id=' .$WDGUserPendingPreinvestment->get_id(). '&campaign_id=' .$WDGUserPendingPreinvestment->get_saved_campaign()->ID ); ?>" class="button red"><?php _e( "Confirmer mon investissement", 'yproject' ); ?></a>
		<br><br>
		<a href="<?php echo home_url( '/investir/?init_with_id=' .$WDGUserPendingPreinvestment->get_id(). '&campaign_id=' .$WDGUserPendingPreinvestment->get_saved_campaign()->ID. '&cancel=1' ); ?>" class="button transparent"><?php _e( "Annuler mon investissement", 'yproject' ); ?></a>
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="pending-investment" title="'.__( "Confirmation de votre investissement", 'yproject' ).'" autoopen="1" catchclick="0"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
