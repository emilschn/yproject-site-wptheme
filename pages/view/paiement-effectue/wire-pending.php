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
<?php _e( 'invest.pending.PLEASE_GO_1', 'yproject' ); ?> <a href="<?php echo home_url( '/mon-compte/' ); ?>"><?php _e( 'invest.pending.PLEASE_GO_2', 'yproject' ); ?></a> <?php _e( 'invest.pending.PLEASE_GO_3', 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="db-form full v3">
	<a class="button red" href="<?php echo $page_controler->get_pending_next_link(); ?>"><?php _e( "common.NEXT", 'yproject' ); ?></a>
</div>
<br><br>
</div>