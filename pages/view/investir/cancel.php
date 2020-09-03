<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 full">
	<p class="align-justify">
	<?php echo sprintf( __( 'invest.cancel.YOU_CHOSE_CANCEL_PROJECT', 'yproject' ), $page_controler->get_current_campaign()->data->post_title ); ?> 
	<?php _e( 'invest.cancel.ARE_YOU_SURE', 'yproject' ); ?>
	<br><br>
	<?php _e( 'invest.cancel.IF_SURE', 'yproject' ); ?>
	</p>
	<br>
	<a href="<?php echo home_url( '/terminer-preinvestissement/?confirm_cancel=1' ) . '&investment_id=' . $page_controler->get_current_investment()->get_id(); ?>" class="button transparent"><?php _e( 'invest.cancel.CANCEL_INVESTMENT', 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo home_url( '/terminer-preinvestissement/?validate=1' ) . '&investment_id=' . $page_controler->get_current_investment()->get_id(); ?>" class="button red"><?php _e( 'invest.cancel.CONFIRM_INVESTMENT', 'yproject' ); ?></a>
</div>
