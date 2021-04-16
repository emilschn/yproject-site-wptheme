<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 full">
	<p class="align-justify">
		<?php _e( 'invest.confirm-cancel.INVESTMENT_CANCELED', 'yproject' ); ?><br />
		<?php _e( 'invest.confirm-cancel.IF_CARD', 'yproject' ); ?><br />
		<?php _e( 'invest.confirm-cancel.IF_WIRE_OR_WALLET', 'yproject' ); ?><br />
		<?php _e( 'invest.confirm-cancel.IF_CHECK', 'yproject' ); ?><br />
	</p>
	<br>
	<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'les-projets' ); ?>" class="button transparent"><?php _e( 'invest.confirm-cancel.VIEW_CURRENT_PROJECTS', 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?campaign_id=' .$page_controler->get_current_campaign()->ID. '&invest_start=1'; ?>" class="button red"><?php _e( 'invest.confirm-cancel.INVEST_ANOTHER_AMOUNT', 'yproject' ); ?></a>
</div>
