<?php
    $page_forgot_password = get_page_by_path('mot-de-passe-oublie');
    if (is_user_logged_in()) wp_redirect(home_url());
    $init_username = '';
    if (isset($_POST['user_login'])) $init_username = $_POST['user_login'];
    if ( !empty( $init_username ) ) {
	$user = get_user_by( 'login', $init_username);
	if ( !isset( $user, $user->user_login, $user->user_status ) ) {
	    $username = str_replace( '&', '&amp;', stripslashes( $init_username ) );
	    $user = get_user_by( 'email', $username );
	    if ( isset( $user, $user->user_login, $user->user_status ) )
		$username = $user->user_login;
	} else {
	    $username = $init_username;
	}
	
	$error = array();
	if (isset($user, $user->user_login)) {
	    if (strpos($user->user_url, 'facebook.com') === false) {
		global $wpdb;
		$user_login = $user->user_login;
		$user_email = $user->user_email;
		$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
		if ( empty($key) ) {
		    $key = wp_generate_password(20, false);
		    do_action('retrieve_password_key', $user_login, $key);
		    $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
		}

		$message = "Quelqu'un a demandé à changer votre mot de passe sur le site WEDOGOOD pour l'utilisateur suivant :\r\n\r\n";
		$message .= $user_login . "\r\n\r\n";
		$message .= "Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous. Sinon, ignorez simplement ce message.\r\n\r\n";
		$message .= get_permalink($page_forgot_password->ID) . "?action=rp&key=$key&login=" . rawurlencode($user_login);
    
		if (FALSE == wp_mail($user_email, sprintf(__('[%s] Password Reset'), get_option('blogname')), $message))
		    $error[] = "Problème d'envoi : l'e-mail de réinitialisation n'a pas été envoyé.";
		$feedback = "Un message a &eacute;t&eacute; envoy&eacute; &agrave; votre adresse e-mail.";
	    } else {
		$error[] = "Cet utilisateur est lié par son compte Facebook et nous ne pouvons donc pas renouveller son mot de passe.";
	    }
	} else {
	    $error[] = "Nous n'avons pas trouvé l'utilisateur correspondant sur le site.";
	}	
    } else if (isset($_POST["new_password"]) && isset($_POST["new_password_confirm"]) && isset($_POST["login"]) && isset($_POST["key"])) {
	$feedback = '';
	if ($_POST["new_password"] != '' && $_POST["new_password"] == $_POST["new_password_confirm"]) {
	    $key = preg_replace('/[^a-z0-9]/i', '', $_POST["key"]);
	    $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $key, $_POST["login"]));
	    if (isset($user, $user->user_login)) {
		wp_update_user( array ( 'ID' => $user->ID, 'user_pass' => $_POST["new_password"], 'user_status' => 0, 'user_activation_key' => '' ) );
	    } else {
		$error[] = "La clé ne correspond pas à cet utilisateur.";
	    }
	    $feedback = "Votre mot de passe a été mis à jour.";
	} else {
	    $error[] = "Erreur de saisie des mots de passe.";
	}
    }
    
?>
<?php get_header(); ?>
<?php require_once("common.php"); ?>

    <div id="content">
	<div class="padder">
	    <?php printMiscPagesTop("Mot de passe oubli&eacute;"); ?>
	    
    <div id="post_bottom_bg">
	<div id="post_bottom_content" class="center_small">
	    <div class="left post_bottom_desc_small">
		
		<?php 
		if (have_posts()) : while (have_posts()) : the_post();
		the_content();
		endwhile; endif; 
		
		if ($_GET["action"] == "rp"):
		    if ($feedback != '') {
			$page_connexion = get_page_by_path('connexion');
			?>
			Votre mot de passe a &eacute;t&eacute; mis &agrave; jour.<br />
			Vous pouvez &agrave; pr&eacute;sent vous <a href="<?php echo get_permalink($page_connexion->ID); ?>">connecter</a>.<br /><br />
			<?php
		    } else {
		?>
		<div class="login_fail">
		    <?php
		    if (isset($error) && is_array($error) && count($error) > 0){
			foreach($error as $e) {
			    echo $e . '<br />';
			}
		    }
		    ?>
		</div>
		<form name="resetpasswordform" id="resetpasswordform" action="<?php echo get_permalink($page_forgot_password->ID); ?>?action=rp" method="post">
		    <label for="new_password">Nouveau mot de passe :
		    <input type="password" name="new_password" id="new_password" class="input" size="20"></label><br />
		    <label for="new_password_confirm">Confirmer le nouveau mot de passe :
		    <input type="password" name="new_password_confirm" id="new_password_confirm" class="input" size="20"></label><br />
		    <input type="hidden" name="login" value="<?php echo $_REQUEST['login'];?>" />
		    <input type="hidden" name="key" value="<?php echo $_REQUEST['key'];?>" />
		    <input type="submit" value="Enregistrer le nouveau mot de passe" />
		</form>
		<?php
		    }
		else:
		?>
		
		<?php if (isset($_POST['user_login'])): ?>
		<div class="login_fail">
		    <?php
		    if (isset($error) && is_array($error) && count($error) > 0){
			foreach($error as $e) {
			    echo $e . '<br />';
			}
		    } else {
			echo $feedback . '<br />'; 
		    }
		    ?>
		</div>
		<?php endif; ?>
		
		<form name="lostpasswordform" id="lostpasswordform" action="" method="post">
		    <label for="user_login">Identifiant ou e-mail :
		    <input type="text" name="user_login" id="user_login" class="input" value="<?php echo $init_username; ?>" size="20"></label><br />
		    <input type="submit" value="R&eacute;initialiser le mot de passe" />
		</form>
		
		<?php
		endif;
		?>
	    </div>

	    <div style="clear: both"></div>
	</div>
    </div>
	</div>
    </div>

<?php get_footer(); ?>