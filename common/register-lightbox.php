<?php global $page_register, $signup_errors, $signup_step; ?>

<div class="wdg-lightbox-ref">

	<form action="<?php echo wp_unslash( $_SERVER['REQUEST_URI'] ); ?>#register" name="signup_form" id="signup_form" class="db-form form-register" method="post" enctype="multipart/form-data">
		<?php if ( $signup_step == 'request-details' ) : ?>
			<h2><?php _e('Inscription', 'yproject'); ?></h2>

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
					<label for="signup_username_login"><?php _e( 'Identifiant', 'yproject' ); ?> *</label>
					<span class="complement">compos&eacute; de lettres non-accentu&eacute;es, de chiffres ou des caract&egrave;res suivants : . - @</span>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_username_login" id="signup_username_login" value="<?php if (!empty($_POST['signup_username'])) { echo $_POST['signup_username']; } ?>" />
						</span>
					</div>
				</div>

				<div class="field">
					<label for="signup_email"><?php _e( 'Adresse e-mail', 'yproject' ); ?> *</label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="signup_email" id="signup_email" value="<?php if (!empty($_POST['signup_email'])) { echo $_POST['signup_email']; } ?>" />
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
				<?php endif; ?>

				<label for="validate-terms-check"><input type="checkbox" name="validate-terms-check" /> J&apos;accepte <a href="<?php echo home_url().'/cgu';  ?>"  target="_blank">les conditions g&eacute;n&eacute;rales d&apos;utilisation</a></label><br />

				<?php wp_nonce_field( 'register_form_posted' ); ?>

				<?php if (isset($page_register) && $page_register == TRUE): ?>
				<input type="hidden" name="redirect-home" value="1" />
				<?php endif; ?>

				<div class="submit box_connection_buttons red">
					<input type="submit" name="signup_submit" id="signup_submit" value="Cr&eacute;er mon compte" />
				</div>
			</div>

			<div class="connexion_facebook_container box_connection_buttons blue">
				<?php
				$fb = new Facebook\Facebook([
					'app_id' => YP_FB_APP_ID,
					'app_secret' => YP_FB_SECRET,
					'default_graph_version' => 'v2.8',
				]);
				$helper = $fb->getRedirectLoginHelper();
				$permissions = ['email'];
				$loginUrl = $helper->getLoginUrl( home_url( '/connexion/?fbcallback=1' ) , $permissions);
				?>
				<a href="<?php echo $loginUrl; ?>" class="social_connect_login_facebook"><span>&nbsp;S&apos;inscrire avec Facebook</span></a>
			</div>

			<div class="align-center box_connection_buttons red" id="signin-button"><a href="#connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion">J&apos;ai d&eacute;j&agrave; un compte</a></div>

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
	
</div>