<?php
/**
 * @var WDG_Page_Controler_Validation_Email
 */
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<div class="activate-account">
	<h1><?php _e( 'validation.WELCOME_TO_WEDOGOOD', 'yproject' ); ?></h1>

	<div class="info">
		<?php _e( 'validation.HELLO_WELCOME_TO_WEDOGOOD', 'yproject' ); ?><br>
		<?php _e( 'validation.ACCOUNT_IS_CREATED', 'yproject' ); ?><br>
		<?php _e( 'validation.YOU_NEED_TO_COMPLETE', 'yproject' ); ?>
	</div>

	<div class="timer">
		<?php _e( 'validation.YOU_CAN_LEAVE_WHEN_YOU_WANT', 'yproject' ); ?>
	</div>

	<div class="db-form v3 full">
		<a href="<?php echo $page_controler->get_current_redirect_link(); ?>" class="button red"><?php _e( 'validation.COMPLETE_MY_PROFILE', 'yproject' ); ?></a>
	</div>
</div>