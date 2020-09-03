<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	<br><br>
	
	<?php _e( 'invest.mean-payment.check-form.TAKE_PICTURE', 'yproject' ); ?>
	<?php echo $page_controler->get_campaign_organization_name(); ?> <?php _e( 'invest.mean-payment.check-form.SEND_PICTURE', 'yproject' ); ?><br><br>

	<?php _e( 'invest.mean-payment.check-form.SEND_CHECK', 'yproject' ); ?><br>
	WE DO GOOD<br>
	38 rue des Olivettes<br>
	44000 Nantes<br><br>

	<?php _e( 'invest.mean-payment.check-form.CHECK_CASHED_IF_SUCCESS', 'yproject' ); ?><br><br>

	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
		<?php _e( 'invest.mean-payment.check-form.CHECK_CONTRACT_IF_RECEIVED', 'yproject' ); ?><br><br>
	<?php endif; ?>

	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" enctype="multipart/form-data">
		<input type="file" name="check_picture" />
		<button type="submit" class="button red"><?php _e( 'common.SEND', 'yproject' ); ?></button>
		<input type="hidden" name="action" value="post_invest_check" />
		<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_current_campaign()->ID; ?>" />
	</form>

	<br><br>

	<?php _e( 'invest.mean-payment.check-form.LATER_1', 'yproject' ); ?>
	<?php _e( 'invest.mean-payment.check-form.LATER_2', 'yproject' ); ?>
	<?php _e( 'invest.mean-payment.check-form.LATER_3', 'yproject' ); ?>

	<br><br>

	<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" class="db-form v3 full">
		<button type="submit" class="button transparent"><?php _e( 'invest.mean-payment.check-form.SEND_LATER', 'yproject' ); ?></button>
		<input type="hidden" name="action" value="post_confirm_check" />
		<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_current_campaign()->ID; ?>" />
	</form>

	<br><br>
</div>
