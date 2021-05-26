<?php
/**
 * @var WDG_Page_Controler_Validation_Email
 */
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<span id="auto-redirect" data-redirect-link="<?php echo $page_controler->get_current_redirect_link(); ?>"></span>
<?php _e( 'validation.YOUR_EMAIL_IS_VALIDATED', 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?>.<br>
<?php _e( 'validation.WE_WILL_REDIRECT_IN_A_FEW_SECONDS', 'yproject' ); ?><br>
<?php _e( 'validation.CLICK_TO_GET_AWAY', 'yproject' ); ?> <a href="<?php echo $page_controler->get_current_redirect_link(); ?>"><?php _e( 'validation.THIS_LINK', 'yproject' ); ?></a>.