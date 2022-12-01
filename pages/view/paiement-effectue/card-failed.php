<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>
<?php echo $page_controler->get_current_investment()->error_item->get_error_message(); ?><br>

<?php
_e( 'invest.done.error.CODE', 'yproject' );
echo $page_controler->get_current_investment()->error_item->get_error_code();
?><br>

<div class="align-center">
	<?php if ( $page_controler->get_error_link() != '' ): ?>
	<a class="button" href="<?php echo $page_controler->get_error_link(); ?>"><?php _e( 'common.NEXT', 'yproject' ); ?></a>

	<?php elseif ( $page_controler->get_error_restart_link() != '' ): ?>
	<br>
	<a href="<?php echo $page_controler->get_error_restart_link(); ?>"><?php _e( 'invest.mean-payment.error.WALLET_TRANSFER_RESTART', 'yproject' ); ?></a>

	<?php endif; ?>
</div><br><br>
</div>