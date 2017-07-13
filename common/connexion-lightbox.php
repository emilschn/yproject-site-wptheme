<?php global $signup_errors, $stylesheet_directory_uri; $has_register_errors = ($signup_errors->get_error_message() != ""); ?>

<div id="connect-form" class="align-center wdg-lightbox-ref <?php if ($has_register_errors): ?>specific-hidden<?php endif; ?>">
	<?php if (WDGUser::has_login_errors()): ?>
	<div class="errors">
		<?php echo WDGUser::display_login_errors(); ?>
	</div>
	<?php endif; ?>
    
        <form method="post" name="login-form" class="sidebar-login-form db-form form-register">
			<h2><?php _e('Inscription et connexion', 'yproject'); ?></h2>
			
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
	    
            <div>
				<a href="<?php echo home_url( '/mot-de-passe-oublie' ); ?>" >(<?php _e("Mot de passe oubli&eacute;", 'yproject'); ?>)</a>
            </div>

			
			<div class="field">
				<input id="signin_rememberme" type="checkbox" name="rememberme" value="forever" />
				<label for="signin_rememberme" style="width: auto;"><?php _e( 'Se souvenir de moi', 'yproject' ); ?></label>
			</div>
            
            <div class="box_connection_buttons red submit-center">
                <input type="submit" name="wp-submit" id="sidebar-wp-submit-lightbox" class="connect" value="<?php _e('Connexion', 'yproject'); ?>" />
                <input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
				<input type="hidden" name="login-form" value="1" />
            </div>
        </form>
	
        <div class="connexion_facebook_container box_connection_buttons blue">
            <a href="#" class="social_connect_login_facebook">&nbsp;<?php _e("Se connecter avec Facebook", 'yproject'); ?></a>
        </div>
		<div class="social_connect_login_facebook_loading align-center hidden">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" />
		</div>

        <div class="box_connection_buttons red">
			<div id="submenu_item_connection_register">
				<a href="#register" class="wdg-button-lightbox-open" data-lightbox="register"><?php _e("Cr&eacute;er mon compte", 'yproject'); ?></a>
			</div>
        </div>

</div>

