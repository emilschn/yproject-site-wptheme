<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>

<?php _e( 'invest.pending.WIRE', 'yproject' ); ?><br><br>

<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
	<?php _e( 'invest.pending.WIRE_WHEN_VALIDATED', 'yproject' ); ?><br><br>
	
	- <?php _e( 'invest.mean-payment.success.TWO_EMAILS_LIST_1', 'yproject' ); ?><br><br>
	- <?php _e( 'invest.mean-payment.success.TWO_EMAILS_LIST_2', 'yproject' ); ?><br><br>

<?php else: ?>
	<?php _e( 'invest.pending.WIRE_WHEN_VALIDATED_ONE_EMAIL', 'yproject' ); ?><br><br>

<?php endif; ?>

<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
<?php _e( 'invest.pending.PLEASE_GO_1', 'yproject' ); ?> <a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ); ?>"><?php _e( 'invest.pending.PLEASE_GO_2', 'yproject' ); ?></a> <?php _e( 'invest.pending.PLEASE_GO_3', 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="db-form full v3 investment-form">
	<a class="button red investment-button" href="<?php echo $page_controler->get_pending_next_link(); ?>">
		<span class="button-text">
			<?php _e( 'common.NEXT', 'yproject' ); ?>
		</span>
		<span class="button-loading loading align-center hidden">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" />
		</span>
	</a>
</div>
<br><br>
</div>