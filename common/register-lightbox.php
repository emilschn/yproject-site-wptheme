<?php global $page_register, $signup_errors, $signup_step, $stylesheet_directory_uri; ?>

<div class="wdg-lightbox-ref">
	<br><br>

	<form action="<?php echo home_url( "/inscription" ); ?>" name="signup_form" id="signup_form" class="db-form v3 full form-register" method="post" enctype="multipart/form-data">
		<?php if ( $signup_step == 'request-details' ) : ?>
			<div class="warning">
				La cr&eacute;ation d&apos;un compte de Membre sur <?php echo ATCF_CrowdFunding::get_platform_name(); ?> est exclusivement r&eacute;serv&eacute;e aux personnes physiques.
				Chaque membre ne peut b&eacute;n&eacute;ficier que d&apos;un seul compte &agrave; son nom.<br /><br />
				Si vous souhaitez investir ou porter un projet pour une organisation, vous pourrez l&apos;indiquer au moment de l&apos;investissement ou dans les param&egrave;tres du projet.
				Vous recevrez automatiquement la newsletter de <?php echo ATCF_CrowdFunding::get_platform_name(); ?> et pourrez vous en d&eacute;sinscrire &agrave; tout moment.
			</div>

			<div class="errors">
				<?php echo $signup_errors->get_error_message(); ?>
			</div>

			<div class="register-section" id="basic-details-section">
				<div class="field">
					<label for="signup_email"><?php _e( 'Adresse e-mail', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_email" id="signup_email" value="<?php if (!empty($_POST['signup_email'])) { echo $_POST['signup_email']; } ?>" />
						</span>
					</div>
				</div>
				
				<div class="field">
					<label for="signup_firstname"><?php _e( "Pr&eacute;nom", 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_firstname" id="signup_firstname" value="<?php if ( !empty( $_POST[ 'signup_firstname' ] ) ) { echo $_POST[ 'signup_firstname' ]; } ?>" />
						</span>
					</div>
				</div>
				
				<div class="field">
					<label for="signup_lastname"><?php _e( "Nom de famille", 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_lastname" id="signup_lastname" value="<?php if ( !empty( $_POST[ 'signup_lastname' ] ) ) { echo $_POST[ 'signup_lastname' ]; } ?>" />
						</span>
					</div>
				</div>

				<div class="field">
					<label for="signup_password"><?php _e( 'Mot de passe', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="password" name="signup_password" id="signup_password" value="" />
						</span>
					</div>
				</div>

				<div class="field">
					<label for="signup_password_confirm"><?php _e( 'Confirmation du mot de passe', 'yproject' ); ?> *</label>
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
					<label for="validate-terms-check-register" id="label-validate-terms-check-register"><input type="checkbox" id="validate-terms-check-register" name="validate-terms-check" /><span></span> J&apos;accepte <a href="<?php echo home_url().'/cgu';  ?>" target="_blank">les conditions g&eacute;n&eacute;rales d&apos;utilisation</a></label><br />
				</div>

				<?php wp_nonce_field( 'register_form_posted' ); ?>

				<?php if (isset($page_register) && $page_register == TRUE): ?>
				<input type="hidden" name="redirect-home" value="1" />
				<?php endif; ?>

				<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
				<input type="hidden" name="signup_submit" value="1" />
				<button class="button save red" type="submit"><?php _e( "Cr&eacute;er mon compte", 'yproject' ); ?></button>
				<br><br>
	
				<button type="button" class="button blue-facebook social_connect_login_facebook" data-redirect="<?php echo WDGUser::get_login_redirect_page(); ?>"><?php _e( "Se connecter avec Facebook", 'yproject' ); ?></button>
				<div class="social_connect_login_facebook_loading align-center hidden">
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
				</div>
				<br><br>

			</div>
		<?php endif; // request-details signup step ?>



		<?php if ( $signup_step == 'completed-confirmation' ) : ?>
			<?php if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ): ?>
			<h2><?php _e( 'Bienvenue chez WE DO GOOD !', 'yproject' ); ?> :)</h2>
			<?php else: ?>
			<h2><?php _e( 'Bienvenue !', 'yproject' ); ?> :)</h2>
			<?php endif; ?>

			<?php _e( 'Votre compte est cr&eacute;&eacute; et vous &ecirc;tes connect&eacute;.', 'yproject' ); ?><br /><br />

			<?php if (isset($page_register) && $page_register == TRUE): ?>
			<?php _e('Rendez-vous sur la page des projets en cliquant sur '); ?><a href="<?php $page_project_list = get_page_by_path('les-projets'); echo get_permalink($page_project_list->ID); ?>"><?php _e('ce lien'); ?></a>.
			<?php endif; ?>

		<?php endif; // completed-confirmation signup step ?>
	</form>
			
	<form method="post" action="<?php echo home_url( "/connexion" ); ?>" name="login-form" class="sidebar-login-form db-form v3 full form-register">
		<div>
			<input type="hidden" class="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
			<button class="button transparent" type="submit"><?php _e( "J&apos;ai d&eacute;j&agrave; un compte", 'yproject' ); ?></button>
		</div>
	</form>
	
</div>