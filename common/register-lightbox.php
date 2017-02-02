<?php global $page_register, $signup_errors, $signup_step; ?>

<form action="<?php echo wp_unslash( $_SERVER['REQUEST_URI'] ); ?>#register" name="signup_form" id="signup_form" class="standard-form form-register" method="post" enctype="multipart/form-data">
	<?php if ( $signup_step == 'request-details' ) : ?>
		<h2><?php _e('Inscription', 'yproject'); ?></h2>
    
		<div class="warning">
			La cr&eacute;ation d&apos;un compte de Membre sur WEDOGOOD.co est exclusivement r&eacute;serv&eacute;e aux personnes physiques.<br />
			Chaque membre ne peut b&eacute;n&eacute;ficier que d&apos;un seul compte &agrave; son nom.<br /><br />
			Si vous souhaitez investir ou porter un projet pour une organisation, vous pourrez l&apos;indiquer au moment de l&apos;investissement ou dans les param&egrave;tres du projet.<br />
			Vous recevrez automatiquement la newsletter de WE DO GOOD et pourrez vous en d&eacute;sinscrire &agrave; tout moment.
		</div>

		<div class="errors">
			<?php echo $signup_errors->get_error_message(); ?>
		</div>
    
		<div class="register-section" id="basic-details-section">
			<div class="on-focus">
				<input type="text" name="signup_username_login" placeholder="<?php _e( 'Identifiant', 'yproject' ); ?> *" id="signup_username_login" value="<?php if (!empty($_POST['signup_username'])) { echo $_POST['signup_username']; } ?>" autofocus />
				<div class="tool-tip slideIn right">Choisissez un Identifiant</div>
			</div>

			<div class="on-focus">
				<input type="text" name="signup_email" placeholder="<?php _e( 'Adresse e-mail', 'yproject' ); ?> *" id="signup_email" value="<?php if (!empty($_POST['signup_email'])) { echo $_POST['signup_email']; } ?>" />
				<div class="tool-tip slideIn right">Saisissez votre adresse e-mail </div>
			</div>

			<div class="on-focus">
				<input type="password" name="signup_password" placeholder="<?php _e( 'Mot de passe', 'yproject' ); ?> *" id="signup_password" value="" />
				<div class="tool-tip slideIn right">Saisissez un mot de passe</div>
			</div>

			<div class="on-focus">
				<input type="password" name="signup_password_confirm" placeholder="<?php _e( 'Confirmation du mot de passe', 'yproject' ); ?> *" id="signup_password_confirm" value="" />
				<div class="tool-tip slideIn right">Confirmez votre mot de passe</div>
			</div>
			
			<div class="g-recaptcha" data-sitekey="6LcoHRIUAAAAADwRb9TDAhshD3CZgIhx1M-MO84y"></div>
			
			<label for="validate-terms-check"><input type="checkbox" name="validate-terms-check" /> J&apos;accepte <a href="<?php echo home_url().'/cgu';  ?>"  target="_blank">les conditions g&eacute;n&eacute;rales d&apos;utilisation</a></label><br />
			
			<?php wp_nonce_field( 'register_form_posted' ); ?>
			
			<?php if (isset($page_register) && $page_register == TRUE): ?>
			<input type="hidden" name="redirect-home" value="1" />
			<?php endif; ?>
				    
			<div class="submit">
				<input type="submit" name="signup_submit" id="signup_submit" value="Cr&eacute;er mon compte" />
			</div>
		</div>
				
		<hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

		<div id="connexion_facebook_container"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" alt="logo facebook connexion" class="vert-align" width="25" height="25"/><span style=" font-size:12px;" >&nbsp;S&apos;inscrire avec Facebook</span></a></div>
		<div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
			    
			    
		<hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

		<div class="align-center"><a href="#connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion" style="background-color: #333333 !important;">J&apos;ai d&eacute;j&agrave; un compte</a></div>

		<br />
		
	<?php endif; // request-details signup step ?>

				    
				    
	<?php if ( $signup_step == 'completed-confirmation' ) : ?>

		<h2><?php _e( 'Bienvenue chez WE DO GOOD !', 'yproject' ); ?> :)</h2>

		<?php _e( 'Votre compte est cr&eacute;&eacute; et vous &ecirc;tes connect&eacute;.', 'yproject' ); ?><br /><br />
		
		<?php if (isset($page_register) && $page_register == TRUE): ?>
		<?php _e('Rendez-vous sur la page des projets en cliquant sur '); ?><a href="<?php $page_project_list = get_page_by_path('les-projets'); echo get_permalink($page_project_list->ID); ?>"><?php _e('ce lien'); ?></a>.
		<?php endif; ?>
		
	<?php endif; // completed-confirmation signup step ?>
</form>