<?php global $signup_errors, $stylesheet_directory_uri; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>

<div id="connect-form" class="align-center wdg-lightbox-ref <?php if ($has_register_errors): ?>specific-hidden<?php endif; ?>">
	<?php if (WDGFormUsers::has_login_errors()): ?>
	<div class="errors">
		<?php echo WDGFormUsers::display_login_errors(); ?>
	</div>
	<?php endif; ?>
    
        <form method="post" action="<?php echo home_url( "/connexion" ); ?>" name="login-form" class="sidebar-login-form db-form v3 full form-register">
			<div class="field">
				<label for="signin_username"><?php _e( 'Identifiant ou e-mail', 'yproject' ); ?> *</label>
				<div class="field-container">
					<span class="field-value">
						<input type="text" name="log" id="signin_username" value="<?php if (isset($_POST["log"])) echo $_POST["log"]; ?>" />
					</span>
				</div>
			</div>
			
			<div class="field">
				<label for="signin_password"><?php _e( 'Mot de passe', 'yproject' ); ?> *</label>
				<div class="field-container">
					<span class="field-value">
						<input type="password" name="pwd" id="signin_password" value="" />
					</span>
				</div>
			</div>
	    
            <div class="field">
				<a href="<?php echo home_url( '/mot-de-passe-oublie' ); ?>" class="forgotten">(<?php _e("Mot de passe oubli&eacute;", 'yproject'); ?>)</a>
            </div>

			<div class="field">
				<label for="signin_rememberme">
					<input id="signin_rememberme" type="checkbox" name="rememberme" value="forever" /><span></span>
					<?php _e( 'Se souvenir de moi', 'yproject' ); ?>
				</label>
			</div>
            
			<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
			<input type="hidden" name="login-form" value="1" />
			<button class="button save red" type="submit"><?php _e( "Connexion", 'yproject' ); ?></button>
			
			<hr class="login-separator">
			<div class="login-separator-label"><span><?php _e( "ou", 'yproject' ); ?></span></div>
	
			<button type="button" class="button blue-facebook social_connect_login_facebook"><?php _e( "Se connecter avec Facebook", 'yproject' ); ?></button>
			<div class="social_connect_login_facebook_loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
			
			<hr class="login-separator">
			<div class="login-separator-label"><span><?php _e( "ou", 'yproject' ); ?></span></div>
			
			<div>
				<a href="<?php echo home_url( '/inscription' ); ?>" class="box_connection_buttons button transparent"><?php _e( "Cr&eacute;er mon compte", 'yproject' ); ?></a>
			</div>
        </form>

</div>

