<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<div class="investment-form">
    <?php _e( 'invest.mean-payment.error.WALLET_TRANSFER', 'yproject' ); ?><br>
    <?php _e( 'invest.mean-payment.error.WALLET_TRANSFER_CONTACT', 'yproject' ); ?> 
    <a href="<?php echo $page_controler->get_restart_link(); ?>" class="investment-button">
        <span class="button-text">
            <?php _e( 'invest.mean-payment.error.WALLET_TRANSFER_RESTART', 'yproject' ); ?>
        </span>
        <span class="button-loading loading align-center hidden">
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" />
        </span>
    </a>.
</div>