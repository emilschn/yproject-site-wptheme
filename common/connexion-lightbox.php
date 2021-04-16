<?php global $signup_errors, $stylesheet_directory_uri, $login_init; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>
<?php
	if ( empty( $login_init ) ) {
		$login_init = '';
	}
?>

<div id="connect-form" class="align-center wdg-lightbox-ref <?php if ($has_register_errors): ?>specific-hidden<?php endif; ?>">
	<?php if (WDGFormUsers::has_login_errors()): ?>
	<div class="errors">
		<?php echo WDGFormUsers::display_login_errors(); ?>
	</div>
	<?php endif; ?>
    
		<form method="post" action="<?php echo WDG_Redirect_Engine::override_get_page_url( "connexion" ); ?>" name="login-form" class="sidebar-login-form db-form v3 full form-register">
			<div class="field">
				<label for="signin_username"><?php _e( 'login.EMAIL_OR_LOGIN', 'yproject' ); ?> *</label>
				<div class="field-container">
					<span class="field-value">
						<input type="text" name="log" id="signin_username" value="<?php echo $login_init; ?>" />
					</span>
				</div>
			</div>
			
			<div class="field">
				<label for="signin_password"><?php _e( 'common.PASSWORD', 'yproject' ); ?> *</label>
				<div class="field-container">
					<span class="field-value">
						<input type="password" name="pwd" id="signin_password" value="" />
					</span>
				</div>
			</div>
	    
            <div class="field">
				<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mot-de-passe-oublie' ); ?>" class="forgotten">(<?php _e( 'login.FORGOTTEN_PASSWORD', 'yproject'); ?>)</a>
            </div>

			<div class="field">
				<label for="signin_rememberme" class="checkbox-parent">
					<input id="signin_rememberme" type="checkbox" name="rememberme" value="forever" /><span></span>
					<?php _e( 'login.REMEMBER_ME', 'yproject' ); ?>
				</label>
			</div>

			<p class="align-left">
				* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
			</p>
			
			<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
			<input type="hidden" name="login-form" value="1" />
			<button class="button save red" type="submit"><?php _e( 'login.LOG_IN', 'yproject' ); ?></button>
			
			<hr class="login-separator">
			<div class="login-separator-label"><span><?php _e( 'common.OR', 'yproject' ); ?></span></div>
	
			<button type="button" class="button blue-facebook social_connect_login_facebook" data-redirect="<?php echo WDGUser::get_login_redirect_page(); ?>"><?php _e( 'login.LOG_IN_FACEBOOK', 'yproject' ); ?></button>
			<div class="social_connect_login_facebook_loading align-center hidden">
				<br>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
			
			<hr class="login-separator">
			<div class="login-separator-label"><span><?php _e( 'common.OR', 'yproject' ); ?></span></div>
		</form>
			
		<form method="post" action="<?php echo WDG_Redirect_Engine::override_get_page_url( "inscription" ); ?>" name="login-form" class="sidebar-login-form db-form v3 full form-register">
			<div>
				<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
				<button class="button transparent" type="submit"><?php _e( 'login.CREATE_ACCOUNT', 'yproject' ); ?></button>
			</div>
		</form>

</div>

