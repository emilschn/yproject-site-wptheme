<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$form_feedback = $page_controler->get_user_form_feedback();
$WDGUser_displayed = $page_controler->get_current_user();
?>
<h2><?php _e( 'account.subscriptions.SUBSCRIPTIONS_OF', 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p class="align-center">
	<?php echo wpautop( WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_subscription_description, 'subscription_description' ) ); ?>
</p>


<?php if ( !$WDGUser_displayed->is_lemonway_registered() ): ?>
	<div class="db-form v3">
		<div class="wdg-message error msg-authentication-alert">
			<?php if ( $pending_amount > 0 ): ?>
				<?php echo sprintf( __( 'account.wallet.AWAITING_AUTHENTICATION', 'yproject' ), UIHelpers::format_number( $pending_amount ) ); ?><br><br>
			<?php endif; ?>

			<?php _e( 'account.wallet.AUTHENTICATION_NECESSARY', 'yproject' ); ?>
		</div>

		<a href="#authentication" class="button red go-to-tab" data-tab="authentication"><?php _e( 'account.wallet.VIEW_AUTHENTICATION_STATUS', 'yproject' ); ?></a>
	</div>


<?php else: ?>

	<?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
		<div class="db-form v3">
			<div class="wdg-message confirm">
				<?php _e( 'form.subscription.SUCCESS', 'yproject' ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $page_controler->has_active_subscriptions() ): ?>
		<p class="align-center">
			<a href="#investments" class="go-to-tab" data-tab="investments"><?php _e( 'account.subscriptions.VIEW_INVESTMENTS', 'yproject' ); ?></a>
		</p>

		<div class="center">
			<?php global $subscription_item; ?>
			<?php $list_subscriptions = $page_controler->get_active_subscriptions_list(); ?>
			<?php foreach ( $list_subscriptions as $subscription_item ): ?>
				<?php locate_template( array( 'pages/view/mon-compte/partial-subscription.php'  ), true, false ); ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="db-form v3">
		<div class ="<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?> hidden <?php endif; ?>">
			<a class="button red add-subscription">
				<span class="button-text">
					<?php _e( 'account.subscriptions.ADD_SUBSCRIPTIONS', 'yproject' ); ?>
				</span>
			</a>
		</div>

		<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
			<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
				<div class="wdg-message error">
					<?php echo $error[ 'text' ]; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="form form-add-subscription <?php if ( empty( $form_feedback[ 'errors' ] ) ): ?> hidden <?php endif; ?>">
			<?php locate_template( array( 'pages/view/common/form-subscription.php'  ), true ); ?>
		</div>
	</div>

<?php endif; ?>