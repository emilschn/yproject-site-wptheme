<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify padding-top padding-bottom">

	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>

		<?php if ( !$page_controler->has_contract_errors() ): ?>

			<?php _e( 'invest.mean-payment.success.TWO_EMAILS', 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?>
			<?php _e( 'invest.mean-payment.success.TWO_EMAILS_CHECK_SPAM', 'yproject' ); ?><br><br>
			
			- <?php _e( 'invest.mean-payment.success.TWO_EMAILS_LIST_1', 'yproject' ); ?><br><br>
			- <?php _e( 'invest.mean-payment.success.TWO_EMAILS_LIST_2', 'yproject' ); ?><br><br>
			<center><img src="<?php echo $stylesheet_directory_uri; ?>/images/eversign.png" width="150" height="40" /></center><br>

		<?php else: ?>
			<?php _e( 'invest.mean-payment.success.TWO_EMAILS_ERROR_1', 'yproject' ); ?><br>
			<span class="errors"><?php _e( 'invest.mean-payment.success.TWO_EMAILS_ERROR_2', 'yproject' ); ?></span><br><br>
			
		<?php endif; ?>

	<?php else: ?>
		<div class="align-center">
			<?php _e( 'invest.mean-payment.success.ONE_EMAIL_1', 'yproject' ); ?><br>
			<?php _e( 'invest.mean-payment.success.ONE_EMAIL_2', 'yproject'); ?> <?php echo $page_controler->get_current_user_email(); ?> <?php _e("(pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable).", 'yproject'); ?><br>
			<?php _e( 'invest.mean-payment.success.ONE_EMAIL_3', 'yproject'); ?><br><br>
		</div>
			
	<?php endif; ?>
			
	<?php if ( $page_controler->is_preinvestment() ): ?>
		<?php _e( 'invest.mean-payment.success.PREINVESTMENT_ALERT_1', 'yproject' ); ?><br>
		<?php _e( "invest.mean-payment.success.PREINVESTMENT_ALERT_2", 'yproject' ); ?><br>
		<?php _e( "invest.mean-payment.success.PREINVESTMENT_ALERT_3", 'yproject' ); ?><br><br>
	<?php endif; ?>

	<div class="db-form full v3 investment-form">
		<a class="button half right transparent investment-button" href="<?php echo $page_controler->get_success_next_link(); ?>">
			<span class="button-text">
				<?php _e( 'common.NEXT', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" />
			</span>
		</a>
		<div class="clear"></div>
	</div>
</div>
