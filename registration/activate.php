<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<div class="page center" id="activate-page">

			<h3><?php if ( true /*yp_account_was_activated()*/ ) :
				_e( 'Compte activ&eacute;', 'yproject' );
			else :
				_e( 'Activer votre compte', 'yproject' );
			endif; ?></h3>

			<?php do_action( 'template_notices' ); ?>

			<?php if ( true /*yp_account_was_activated()*/ ) : ?>

				<?php if ( isset( $_GET['e'] ) ) : ?>
					<?php ypcf_debug_log('Account activated + mail'); ?>
					<p><?php _e( 'Votre compte a &eacute;t&eacute; activ&eacute ! Les d&eacute;tails de votre compte ont &eacute;t&eacute; envoy&eacute;s par e-mail.', 'yproject' ); ?></p>
				<?php else : ?>
					<?php ypcf_debug_log('Account activated + form'); ?>
					<p><?php printf( __( 'Votre compte a bien &eacute;t&eacute; activ&eacute; ! Vous pouvez maintenant <a href="%s">vous connecter</a> avec votre login et mot de passe.', 'yproject' ), home_url('/connexion') ); ?></p>

					<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
					    <label class="standard-label"><?php _e('Identifiant', 'yproject'); ?></label>
					    <input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" />
					    <br />

					    <label class="standard-label"><?php _e('Mot de passe', 'yproject'); ?></label>
					    <input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" />
					    <br />

					    <p class="forgetmenot">
						<input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
						<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e('Connexion', 'yproject'); ?>" />
					    </p>

					    <input type="hidden" name="testcookie" value="1" />
					</form>

					<div><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
				<?php endif; ?>

			<?php else : ?>

				<?php ypcf_debug_log('Provide a valid activation key'); ?>
				<p>
					<?php _e( "Merci de saisir la cl&eacute; d'activation.", 'yproject' ); ?><br />
					<?php _e( 'Essayez en copiant directement le lien que vous avez re&ccedil;u dans votre navigateur.', 'yproject' ); ?>
				</p>

				<form action="" method="get" class="standard-form" id="activation-form">

					<label for="key"><?php _e( "Cl&eacute; d'activation :", 'yproject' ); ?></label>
					<input type="text" name="key" id="key" value="" />

					<p class="submit">
						<input type="submit" name="submit" value="<?php _e( 'Activer', 'yproject' ); ?>" />
					</p>

				</form>

			<?php endif; ?>

		</div><!-- .page -->

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer();
