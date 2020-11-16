<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center db-form v3 full">
	<p class="align-justify">
		<?php echo sprintf( __( 'invest.cancel-not-validated.INTENTION_CANCELED', 'yproject' ), $page_controler->get_current_campaign()->data->post_title ); ?><br>
		<?php _e( 'invest.cancel-not-validated.YOU_CAN_INVEST', 'yproject' ); ?>
	</p>
	
	<a href="<?php echo home_url( '/mon-compte/' ); ?>" class="button blue"><?php _e( 'common.MY_ACCOUNT', 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>" class="button blue"><?php _e( 'invest.cancel-not-validated.VIEW_PROJECT', 'yproject' ); ?></a>
	<br><br>
</div>
