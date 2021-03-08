<?php global $page_register, $signup_errors, $signup_step, $stylesheet_directory_uri, $signup_email_init, $signup_firstname_init, $signup_lastname_init; ?>
<?php
	if ( empty( $signup_email_init ) ) {
		$signup_email_init = '';
	}
	if ( empty( $signup_firstname_init ) ) {
		$signup_firstname_init = '';
	}
	if ( empty( $signup_lastname_init ) ) {
		$signup_lastname_init = '';
	}
?>

<div class="wdg-lightbox-ref">
	<br><br>

	<form action="<?php echo WDG_Redirect_Engine::override_get_page_url( "inscription" ); ?>" name="signup_form" id="signup_form" class="db-form v3 full form-register" method="post" enctype="multipart/form-data">
		<?php if ( $signup_step == 'request-details' ) : ?>
			<div class="warning">
				<?php _e( 'signup.WARNING_1', 'yproject' ); ?>
				<?php _e( 'signup.WARNING_2', 'yproject' ); ?><br><br>
				<?php _e( 'signup.WARNING_3', 'yproject' ); ?>
			</div>

			<div class="errors">
				<?php $error_list = $signup_errors->get_error_messages(); ?>
				<?php foreach ( $error_list as $error ): ?>
					<?php echo $error . '<br>'; ?>
				<?php endforeach; ?>
			</div>

			<div class="register-section" id="basic-details-section">
				<div class="field">
					<label for="signup_email"><?php _e( 'common.EMAIL', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="email" name="signup_email" id="signup_email" value="<?php echo $signup_email_init; ?>" />
						</span>
					</div>
				</div>
				
				<div class="field">
					<label for="signup_firstname"><?php _e( 'common.FIRST_NAME', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_firstname" id="signup_firstname" value="<?php echo $signup_firstname_init; ?>" />
						</span>
					</div>
				</div>
				
				<div class="field">
					<label for="signup_lastname"><?php _e( 'common.LAST_NAME', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_lastname" id="signup_lastname" value="<?php echo $signup_lastname_init; ?>" />
						</span>
					</div>
				</div>

				<div class="field">
					<label for="signup_password"><?php _e( 'common.PASSWORD', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="password" name="signup_password" id="signup_password" value="" />
						</span>
					</div>
				</div>

				<div class="field">
					<label for="signup_password_confirm"><?php _e( 'signup.PASSWORD_CONFIRMATION', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" />
						</span>
					</div>
				</div>

				<?php if (!WP_IS_DEV_SITE): ?>
				<div class="g-recaptcha" data-sitekey="6LcoHRIUAAAAADwRb9TDAhshD3CZgIhx1M-MO84y"></div>
				<br><br>
				<?php endif; ?>

				<div class="field">
					<label for="validate-terms-check-register" id="label-validate-terms-check-register" data-keepdefault="1" class="checkbox-parent">
						<input type="checkbox" id="validate-terms-check-register" name="validate-terms-check" /><span></span> <?php _e( 'signup.I_ACCEPT', 'yproject' ); ?> <a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'cgu' );  ?>" target="_blank"><?php _e( 'signup.THE_TERMS', 'yproject' ); ?></a> *
					</label>
				</div>
				<br>

				<p class="align-left">
					* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
				</p>

				<?php wp_nonce_field( 'register_form_posted' ); ?>

				<?php if (isset($page_register) && $page_register == TRUE): ?>
				<input type="hidden" name="redirect-home" value="1" />
				<?php endif; ?>

				<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
				<input type="hidden" name="signup_submit" value="1" />
				<button class="button save red" type="submit"><?php _e( 'login.CREATE_ACCOUNT', 'yproject' ); ?></button>
				<br><br>
	
				<button type="button" class="button blue-facebook social_connect_login_facebook" data-redirect="<?php echo WDGUser::get_login_redirect_page(); ?>"><?php _e( 'login.LOG_IN_FACEBOOK', 'yproject' ); ?></button>
				<div class="social_connect_login_facebook_loading align-center hidden">
					<br>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
				</div>
				<br><br>

			</div>
		<?php endif; // request-details signup step?>



		<?php if ( $signup_step == 'completed-confirmation' ) : ?>
			<h2><?php _e( 'signup.WELCOME_TO_WEDOGOOD', 'yproject' ); ?> :)</h2>

			<?php _e( 'signup.ACCOUNT_CREATED', 'yproject' ); ?><br><br>

			<?php if (isset($page_register) && $page_register == TRUE): ?>
				<?php _e( 'signup.GO_TO_PROJECT_LIST', 'yproject' ); ?> <a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'les-projets' ); ?>"><?php _e( 'signup.THIS_LINK', 'yproject' ); ?></a>.
			<?php endif; ?>

		<?php endif; // completed-confirmation signup step?>
	</form>
			
	<form method="post" action="<?php echo WDG_Redirect_Engine::override_get_page_url( "connexion" ); ?>" name="login-form" class="sidebar-login-form db-form v3 full form-register">
		<div>
			<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
			<button class="button transparent" type="submit"><?php _e( 'signup.ALREADY_HAVE_ACCOUNT', 'yproject' ); ?></button>
		</div>
	</form>
	
</div>