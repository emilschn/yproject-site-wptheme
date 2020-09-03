<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php _e( 'invest.mean-payment.error.WALLET_TRANSFER', 'yproject' ); ?><br>
<?php _e( 'invest.mean-payment.error.WALLET_TRANSFER_CONTACT', 'yproject' ); ?> <a href="<?php echo $page_controler->get_restart_link(); ?>"><?php _e( 'invest.mean-payment.error.WALLET_TRANSFER_RESTART', 'yproject' ); ?></a>.