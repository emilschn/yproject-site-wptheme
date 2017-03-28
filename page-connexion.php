<?php
$do_fb_login = FALSE;
$fbcallback = filter_input( INPUT_GET, 'fbcallback' );
if ( !empty( $fbcallback ) ) {
	$fb = new Facebook\Facebook([
		'app_id' => YP_FB_APP_ID,
		'app_secret' => YP_FB_SECRET,
		'default_graph_version' => 'v2.8',
	]);

	$helper = $fb->getRedirectLoginHelper();

	try {
		$accessToken = $helper->getAccessToken();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		ypcf_debug_log( 'Graph returned an error: ' . $e->getMessage() );
		
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		ypcf_debug_log( 'Facebook SDK returned an error: ' . $e->getMessage() );
	}

	if (! isset($accessToken)) {
		if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			ypcf_debug_log( "Error: " . $helper->getError() . "\n"
			."Error Code: " . $helper->getErrorCode() . "\n"
			."Error Reason: " . $helper->getErrorReason() . "\n"
			."Error Description: " . $helper->getErrorDescription() . "\n" );
		} else {
			//header('HTTP/1.0 400 Bad Request');
			ypcf_debug_log( 'Bad request' );
		}
	}

	// Logged in
	//echo '<h3>Access Token</h3>';
	//var_dump($accessToken->getValue());

	// The OAuth 2.0 client handler helps us manage access tokens
	$oAuth2Client = $fb->getOAuth2Client();

	// Get the access token metadata from /debug_token
	$tokenMetadata = $oAuth2Client->debugToken($accessToken);
	$fbUserId = $tokenMetadata->getField("user_id");
	$sc_provider_identity_key = 'social_connect_facebook_id';
	
	global $wpdb;
	$sql = "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = '%s' AND meta_value = '%s'";
	$user_id = $wpdb->get_var( $wpdb->prepare( $sql, $sc_provider_identity_key, $fbUserId ) );
	
	// On a trouvé l'utilisateur correspondant
	if ( $user_id ) {
		$user_id += 0; // Transformation en entier
		
	} else {
		// On va chercher les infos de l'utilisateur en cours
		try {
			$response = $fb->get('/me?fields=id,email,first_name,last_name,link', $accessToken);
			$fb_user = $response->getGraphUser();

			$user_email = $fb_user['email'];
			$user_first_name = $fb_user['first_name'];
			$user_last_name = $fb_user['last_name'];
			$user_profile_url = $fb_user['link'];
			$user_login = strtolower( str_replace( ' ', '', $user_first_name . $user_last_name ) );
			
			$user_id = email_exists( $user_email );

			// On n'a pas trouvé l'utilisateur avec son id fb, mais il existe avec son mail
			if ( $user_id ) {
				update_user_meta( $user_id, $sc_provider_identity_key, $fbUserId );
				$user_data  = get_userdata( $user_id );
				$user_login = $user_data->user_login;

			// On crée l'utilisateur avec les infos recues depuis fb
			} else {
				$index = 0;
				$user_login_base = $user_login;
				while ( username_exists( $user_login ) ) {
					$index++;
					$user_login = $user_login_base . '-' . $index;
				}

				$userdata = array(
					'user_login'	=> $user_login,
					'user_email'	=> $user_email,
					'first_name'	=> $user_first_name,
					'last_name'		=> $user_last_name,
					'user_url'		=> $user_profile_url,
					'user_pass'		=> wp_generate_password()
				);

				// Create a new user
				$user_id = wp_insert_user( $userdata );

				if ( $user_id && is_integer( $user_id ) ) {
					update_user_meta( $user_id, $sc_provider_identity_key, $fbUserId );
				}
			}
			
			
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			/*echo 'Graph returned an error: ' . $e->getMessage();
			exit;*/
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			/*echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;*/
		}
	}
	
	if ( $user_id && is_integer( $user_id ) ) {
		wp_set_auth_cookie( $user_id );
		$do_fb_login = TRUE;
	}
}
?>

<?php
if ( $do_fb_login || is_user_logged_in() ) {
	wp_redirect( WDGUser::get_login_redirect_page() );
	exit();
}
?>

<?php get_header(); ?>

<div id="content" style="margin-top: 90px;">
	<div class="padder_more">
		<div class="center_small margin-height">
			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>