<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$WDGUserPendingPreinvestment = $page_controler->get_show_user_pending_preinvestment();
?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<p class="align-center">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-stat-loupe.png" width="150">
	</p>
	
	<p class="align-justify">
		<?php echo sprintf( __( 'invest.lightbox.preinvestment.INTRO', 'yproject' ), $WDGUserPendingPreinvestment->get_saved_amount(), $WDGUserPendingPreinvestment->get_saved_campaign()->data->post_title ); ?>
	</p>
	
	<p class="align-justify">
		<?php _e( 'invest.lightbox.preinvestment.DESCRIPTION', 'yproject' ); ?><br>
		<?php echo $WDGUserPendingPreinvestment->get_saved_campaign()->contract_modifications(); ?>
	</p>
	
	<form class="db-form v3">
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'terminer-preinvestissement' ) . '?validate=1&investment_id=' . $WDGUserPendingPreinvestment->get_id(); ?>" class="button red"><?php _e( 'invest.lightbox.CONFIRM', 'yproject' ); ?></a>
		<br><br>
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'terminer-preinvestissement' ) . '?cancel=1&investment_id=' . $WDGUserPendingPreinvestment->get_id(); ?>" class="button transparent"><?php _e( 'invest.lightbox.CANCEL', 'yproject' ); ?></a>
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="pending-preinvestment" title="'.__( 'invest.lightbox.preinvestment.TITLE', 'yproject' ).'" autoopen="1" catchclick="1" save-close="1"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
