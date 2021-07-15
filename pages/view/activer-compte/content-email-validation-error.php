<?php
/**
 * @var WDG_Page_Controler_Validation_Email
 */
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php _e( 'validation.CURRENT_ACCOUNT_MATCHES_EMAIL', 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?>.<br><br>
<?php _e( 'validation.CODE_DOESNT_MATCH', 'yproject' ); ?><br>
<?php _e( 'validation.SEND_NEW_VALIDATION_EMAIL', 'yproject' ); ?><br>
<button id="send-email-validation-link" type="button" class="button red" data-email="<?php echo $page_controler->get_current_user_email(); ?>" data-sessionUID="<?php echo $page_controler->get_current_user_sessionUID(); ?>" data-isnewaccount="<?php echo $page_controler->get_current_user_is_new_account(); ?>"><?php _e( 'validation.SEND_NEW_VALIDATION_EMAIL_BUTTON', 'yproject' ); ?></button>