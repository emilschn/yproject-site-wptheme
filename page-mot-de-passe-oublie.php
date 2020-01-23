<?php
if ( is_user_logged_in() ) {
	wp_redirect( home_url() );
}
$page_forgot_password = home_url( '/mot-de-passe-oublie/' );
$init_username = '';
if ( isset( $_POST[ 'user_login' ] ) ) {
	$init_username = $_POST['user_login'];
}

if ( !empty( $init_username ) ) {
	$user = get_user_by( 'login', $init_username);
	if ( !isset( $user, $user->user_login, $user->user_status ) ) {
		$username = str_replace( '&', '&amp;', stripslashes( $init_username ) );
		$user = get_user_by( 'email', $username );
		if ( isset( $user, $user->user_login, $user->user_status ) ) {
			$username = $user->user_login;
		}
	} else {
		$username = $init_username;
	}

	$error = array();
	if (isset($user, $user->user_login)) {
		$facebook_meta = $user->get( 'social_connect_facebook_id' );
		if (!isset($facebook_meta) || $facebook_meta == "") {
			global $wpdb;
			$user_login = $user->user_login;
			$user_email = $user->user_email;
			$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
			if ( empty($key) ) {
				$key = wp_generate_password(20, false);
				do_action('retrieve_password_key', $user_login, $key);
				$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
			}

			$message = "Quelqu'un a demandé à changer votre mot de passe sur le site ".ATCF_CrowdFunding::get_platform_name()." pour l'utilisateur suivant :\r\n\r\n";
			$message .= $user_login . "\r\n\r\n";
			$message .= "Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous. Sinon, ignorez simplement ce message.\r\n\r\n";
			$message .= get_permalink($page_forgot_password->ID) . "?action=rp&key=$key&login=" . rawurlencode($user_login);

			if (FALSE == wp_mail($user_email, sprintf(__('[%s] Password Reset'), get_option('blogname')), $message)) {
				array_push( $error, "Problème d'envoi : l'e-mail de réinitialisation n'a pas été envoyé." );
			}
			$feedback = "Un message a &eacute;t&eacute; envoy&eacute; &agrave; votre adresse e-mail.";
		} else {
			array_push( $error, "Cet utilisateur est lié par son compte Facebook et nous ne pouvons donc pas renouveler son mot de passe. Merci de nous contacter par e-mail, &agrave; l'adresse investir@wedogood.co, si vous souhaitez d&eacute;lier le compte Facebook." );
		}
	} else {
		array_push( $error, "Nous n'avons pas trouvé l'utilisateur correspondant sur le site." );
	}	
	
} else if (isset($_POST["new_password"]) && isset($_POST["new_password_confirm"]) && isset($_POST["login"]) && isset($_POST["key"])) {
	$feedback = '';
	if ($_POST["new_password"] != '' && $_POST["new_password"] == $_POST["new_password_confirm"]) {
		$key = preg_replace('/[^a-z0-9]/i', '', $_POST["key"]);
		$user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $_POST["login"]));
		if (isset($user, $user->user_login)) {
			wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $_POST["new_password"], 'user_status' => 0, 'user_activation_key' => '' ) );
		} else {
			array_push( $error, "La clé ne correspond pas à cet utilisateur." );
		}
		$feedback = "Votre mot de passe a été mis à jour.";
	} else {
		array_push( $error, "Erreur de saisie des mots de passe." );
	}
}
?>


<?php get_header(); ?>

<div id="content" class="login-page-container">
	<div class="padder_more">
		<div class="center_small margin-height">

			<h1><?php _e( "R&eacute;cup&eacute;ration de mot de passe", 'yproject' ); ?></h1>

			<?php if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'rp' ): ?>
				<?php if ( $feedback != '' ): ?>
					Votre mot de passe a &eacute;t&eacute; mis &agrave; jour.<br />
					Vous pouvez &agrave; pr&eacute;sent vous <a href="<?php echo home_url( '/connexion/' ); ?>">connecter</a>.<br /><br />

				<?php else: ?>
					<div class="login_fail">
						<?php if ( isset( $error ) && is_array( $error ) && count( $error ) > 0 ): ?>
							<?php foreach( $error as $e ): ?>
								<div class="wdg-message error">
									<?php echo $e; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<form method="post" action="<?php echo home_url( '/mot-de-passe-oublie/?action=rp' ); ?>" name="resetpasswordform" id="resetpasswordform" class="sidebar-login-form db-form v3 full bg-white">
						<div class="field">
							<label for="new_password"><?php _e( "Nouveau mot de passe :", 'yproject' ); ?> *</label>
							<div class="field-container">
								<span class="field-value">
									<input type="password" name="new_password" id="new_password">
								</span>
							</div>
						</div>

						<div class="field">
							<label for="new_password_confirm"><?php _e( "Confirmer le nouveau mot de passe :", 'yproject' ); ?> *</label>
							<div class="field-container">
								<span class="field-value">
									<input type="password" name="new_password_confirm" id="new_password">
								</span>
							</div>
						</div>

						<input type="hidden" name="login" value="<?php echo $_REQUEST[ 'login' ]; ?>">
						<input type="hidden" name="key" value="<?php echo $_REQUEST[ 'key' ]; ?>">

						<button class="button save red" type="submit"><?php _e( "Enregistrer le nouveau mot de passe", 'yproject' ); ?></button>
					</form>
				<?php endif; ?>


			<?php else: ?>
					
				<?php _e( "Vous avez oubli&eacute; votre mot de passe ?", 'yproject' ); ?>
				<?php _e( "Pas d'inqui&eacute;tude : indiquez votre adresse mail de connexion ci-dessous et vous recevrez les instructions par mail afin de le r&eacute;initialiser.", 'yproject' ); ?>
				<br><br>
				<?php _e( "Si vous ne vous souvenez plus de l'adresse mail utilis&eacute;e pour vous connecter, contactez-nous via le chat en ligne ou le formulaire de contact afin que l'on puisse vous guider.", 'yproject' ); ?>
				<br><br>
					
				<?php if ( isset( $_POST[ 'user_login' ] ) ): ?>
					<?php if (isset($error) && is_array($error) && count($error) > 0): ?>
						<?php foreach( $error as $e ): ?>
							<div class="wdg-message error">
								<?php echo $e; ?>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="wdg-message confirm">
							<?php echo $feedback; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<form method="post" action="" name="lostpasswordform" id="lostpasswordform" class="sidebar-login-form db-form v3 full bg-white">
					<div class="field">
						<label for="user_login"><?php _e( "Adresse e-mail :", 'yproject' ); ?> *</label>
						<div class="field-container">
							<span class="field-value">
								<input type="email" name="user_login" id="new_password" value="<?php echo $init_username; ?>">
							</span>
						</div>
					</div>

					<button class="button save red" type="submit"><?php _e( "R&eacute;initialiser le mot de passe", 'yproject' ); ?></button>
				</form>
				<br><br>

			<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>