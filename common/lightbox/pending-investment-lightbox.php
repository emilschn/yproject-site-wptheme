<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$WDGUserPendingPreinvestment = $page_controler->get_show_user_pending_investment();
?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<p class="align-justify">
		<?php echo sprintf( __( 'invest.lightbox.investment.INTRO', 'yproject' ), $WDGUserPendingPreinvestment->get_saved_amount(), $WDGUserPendingPreinvestment->get_saved_campaign()->data->post_title ); ?>
	</p>
	
	<p class="align-justify">
		<?php _e( 'invest.lightbox.investment.DESCRIPTION', 'yproject' ); ?><br>
	</p>
	
	<form class="db-form v3 button-list">
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?init_invest=' .$WDGUserPendingPreinvestment->get_saved_amount() . '&campaign_id=' .$WDGUserPendingPreinvestment->get_saved_campaign()->ID; ?>" class="button transparent half"><?php _e( 'invest.lightbox.OTHER_AMOUNT', 'yproject' ); ?></a>
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?init_with_id=' .$WDGUserPendingPreinvestment->get_id(). '&campaign_id=' .$WDGUserPendingPreinvestment->get_saved_campaign()->ID; ?>" class="button red half"><?php _e( 'invest.lightbox.CONFIRM', 'yproject' ); ?></a>
		<br><br>
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?init_with_id=' .$WDGUserPendingPreinvestment->get_id(). '&campaign_id=' .$WDGUserPendingPreinvestment->get_saved_campaign()->ID. '&cancel=1'; ?>"><?php _e( 'invest.lightbox.CANCEL', 'yproject' ); ?></a>
		<br><br>
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="pending-investment" title="'.__( 'invest.lightbox.investment.TITLE', 'yproject' ).'" autoopen="1" catchclick="0" save-close="1"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
