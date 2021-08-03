<?php
    global $stylesheet_directory_uri;
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    $WDGSubscriptionForm = $page_controler->get_subscription_form();
    $WDGUser_displayed = $page_controler->get_current_user();
    $form_feedback = $page_controler->get_user_form_feedback(); 
?>
<h2><?php _e( 'account.subscriptions.SUBSCRIPTIONS_OF', 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p class="align-center">
	<?php _e( 'account.subscriptions.INFORMATION_SUBSCRIPTIONS', 'yproject' ); ?><br>
    <?php _e( 'account.subscriptions.SECOND_INFORMATION_SUBSCRIPTIONS', 'yproject' ); ?>
</p>

<div class="db-form v3 ">
<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
	    <div class="wdg-message error">
        <?php _e( 'form.subscription.error.AMOUNT_MINIMUM', 'yproject' ); ?>
		</div>
<?php endif; ?>

<?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
        <div class="wdg-message confirm">
        <?php _e( 'form.subscription.SUCCESS', 'yproject' ); ?>
        </div>
<?php endif; ?>

<a class="button red add-subscription">
	    <span class="button-text">
	        <?php _e( 'account.subscriptions.ADD_SUBSCRIPTIONS', 'yproject' ); ?>
	    </span>
    </a>
<div class="form hidden">
<?php 
	locate_template( array( 'pages/view/common/form-subscription.php'  ), true );
?>
</div>
</div>

