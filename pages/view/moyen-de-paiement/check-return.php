<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	<br><br>
	<div class="grenade-festif-overlay"></div>
	<?php _e( 'invest.mean-payment.check-return.THANK_YOU', 'yproject' ); ?>
	<?php echo $page_controler->get_current_investment()->get_session_amount(); ?> &euro;
	<?php _e( 'invest.mean-payment.check-return.BY_CHECK', 'yproject' ); ?>
	<?php echo $page_controler->get_campaign_organization_name(); ?>.<br><br>

	<?php if ( $page_controler->get_check_return() == 'post_confirm_check' ): ?>
		<?php _e( 'invest.mean-payment.check-return.SEND_PICTURE', 'yproject' ); ?>
	<?php endif; ?>

	<?php _e( 'invest.mean-payment.check-return.VALIDATION_IF_RECEIVED', 'yproject' ); ?>
	<?php echo $page_controler->get_current_user_email(); ?>.<br><br>

	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
		<?php _e( 'invest.mean-payment.check-return.SIGN_CONTRACT', 'yproject' ); ?>
		<?php echo $page_controler->get_current_user_email(); ?>.
		<?php _e( 'invest.mean-payment.check-return.CHECK_SPAM', 'yproject' ); ?><br><br>
	<?php else: ?>
		<?php _e( 'invest.mean-payment.check-return.CONTRACT_IN_MAIL', 'yproject' ); ?><br><br>
	<?php endif; ?>

	<?php _e( 'invest.mean-payment.check-return.SEND_CHECK', 'yproject' ); ?>
	<?php echo $page_controler->get_current_investment()->get_session_amount(); ?> &euro;
	<?php _e( 'invest.mean-payment.check-return.ORDER', 'yproject' ); ?>
	<?php echo $page_controler->get_campaign_organization_name(); ?>
	<?php _e( 'invest.mean-payment.check-return.TO_THE_ADDRESS', 'yproject' ); ?><br>
	WE DO GOOD<br>
	40 rue de la tour d’Auvergne<br>
	44200 Nantes<br><br>

	<div class="db-form v3 full investment-form">
		<a class="button transparent investment-button" href="<?php echo $page_controler->get_success_next_link(); ?>">			
			<span class="button-text">
				<?php _e( 'common.NEXT', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" /><?php _e( 'common.NEXT', 'yproject' ); ?>...
			</span>
		</a>
	</div>
	<br><br>
</div>