<?php
class YPUsersLib {
	/**
	 * Définit la page vers laquelle il faudrait rediriger l'utilisateur lors de sa connexion
	 * @global type $post
	 * @return type
	 */
	public static function get_login_redirect_page() {
		global $post;
		$buffer = home_url();
		
		//Si on est sur la page de connexion ou d'identification,
		// il faut retrouver la page précédente et vérifier qu'elle est de WDG
		if ($post->post_name == 'connexion' || $post->post_name == 'register') {
			//Récupération de la page précédente
			$referer_url = wp_get_referer();
			//On vérifie que l'url appartient bien au site en cours (home_url dans referer)
			if (strpos($referer_url, $buffer) !== FALSE) {
				
				//Si la page précédente était déjà la page connexion ou enregistrement, 
				// on tente de voir si la redirection était passée en paramètre
				if (strpos($referer_url, '/connexion') !== FALSE || strpos($referer_url, '/register') !== FALSE) {
					$posted_redirect_page = filter_input(INPUT_POST, 'redirect-page');
					if (!empty($posted_redirect_page)) {
						$buffer = $posted_redirect_page;
					}
					
				//Sinon on peut effectivement rediriger vers la page précédente
				} else {
					$buffer = $referer_url;
				}
			}
			
		//Sur les autres pages, on tente de choper l'ID de la page en cours
		} else {
			if (isset($post->ID)) {
				$buffer = get_permalink($post->ID);
			}
		}
		
		return $buffer;
	}
    
	/**
	 * Tente de se connecter au site
	 * @return boolean
	 */
	public static function login() {
		//Pas la peine de tenter un login si l'utilisateur est déjà connecté
		if (is_user_logged_in()) { return FALSE; }
		//Pas la peine de tenter un login si on ne l'a pas demandé
		$posted_login_form = filter_input(INPUT_POST, 'login-form');
		if (empty($posted_login_form)) { return FALSE; }
		
		remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
		add_filter('authenticate', 'YPUsersLib::filter_login_email', 20, 3);
		add_action('wp_login', 'YPUsersLib::redirect_after_login');
//		add_action('wp_login_failed', 'YPUsersLib::redirect_after_login_failed'); 
		global $signon_errors;
		$signon_result = wp_signon('', is_ssl());
		if (is_wp_error($signon_result) && !isset($signon_errors)) {
			$signon_errors = $signon_result;
		}
	}
	
	/**
	 * permet d'autoriser l'identification par email
	 * @param type $user
	 * @param type $username
	 * @param type $password
	 * @return type
	 */
	public static function filter_login_email( $user, $username, $password ) {
		if ( is_a( $user, 'WP_User' ) ) return $user;
		
		if (empty($username) || empty($password)) {
			global $signon_errors;
			$signon_errors = new WP_Error();
			$signon_errors->add('empty_authentication', __('Champs vides', 'yproject'));
		}

		if ( !empty( $username ) ) {
			$username = str_replace( '&', '&amp;', stripslashes( $username ) );
			$user = get_user_by( 'email', $username );
			if ( isset( $user, $user->user_login, $user->user_status ) && 0 == (int) $user->user_status )
				$username = $user->user_login;
		}

		return wp_authenticate_username_password( null, $username, $password );
	}
	
	/**
	 * Détecte et gère l'affichage des erreurs de login
	 * @global type $signon_errors
	 * @return type
	 */
	public static function display_login_errors() {
		global $signon_errors;
		$buffer = '';
		if (is_wp_error($signon_errors)) {
			switch ($signon_errors->get_error_code()) {
				case 'empty_authentication':
				case 'empty_username':
				case 'empty_password':
					$buffer = __('Merci de saisir votre identifiant et votre mot de passe.', 'yproject');
					break;
				case 'invalid_username':
					$buffer = __('Cet utilisateur n&apos;existe pas.', 'yproject');
					break;
				case 'incorrect_password':
					$buffer = __('Le mot de passe saisi ne correspond pas.', 'yproject');
					break;
			}
		}
		return $buffer;
	}
	
	/**
	 * Retourne si il y a eu des erreurs pendant le login
	 * @global type $signon_errors
	 * @return type
	 */
	public static function has_login_errors() {
		global $signon_errors; return is_wp_error($signon_errors);
	}
	
	public static function register() {
		if (is_user_logged_in()) { return FALSE; }
	}
	
	/**
	 * Redirige après la connexion
	 */
	public static function redirect_after_login() {
		//Récupération de la page de redirection à appliquer
		$posted_redirect_page = filter_input(INPUT_POST, 'redirect-page');
		//Si ce n'est pas défini, on retourne à l'accueil
		if (empty($posted_redirect_page)) { wp_safe_redirect(home_url()); }
		
		//Vérification si l'url ne contient pas de liens vers l'admin
		if (strpos($posted_redirect_page, 'wp-admin') !== FALSE) {
			wp_safe_redirect(home_url());
		} else {
			wp_safe_redirect($posted_redirect_page);
		}
		
		/**
		 * TODO : reprendre les modifs d'Alexandre
		 * $page_invest = get_page_by_path('investir');
        $page_id = $_POST['redirect-page'];
        $page_type = $_POST['type-page']; 
        $page_redirection = $_POST['redirect-page-investir'];
        
        if (isset($_GET['login'])) {
            $page = get_permalink($page_invest->ID).'?campaign_id='.$page_id.'&invest_start=1';
	    wp_redirect($page);
	    
        } else {
            if (isset($page_id) && isset($page_type)) {
                if ($page_type == "download") {
                    if( isset($page_redirection) && $page_redirection == "true") {
                        $page = get_permalink($page_invest->ID).'?campaign_id='.$page_id.'&invest_start=1';
                        wp_redirect($page);  
                        
                    } else if (isset($page_redirection) && $page_redirection == "forum") {
                        $forum = get_page_by_path('forum');
                        $page = get_permalink($forum->ID).'?campaign_id='.$page_id;   
                        wp_redirect($page);
                        
                    } else {
                        $page = get_page($page_id);
                        wp_redirect(get_permalink($page).'#description_du_projet');
                    }
                } else {
                    $page = get_page($page_id);
                    wp_redirect(get_permalink($page)); 
                }
		
            } else {
		wp_redirect(home_url());
	    }
        }
	exit;
		 */
		
		exit();
	}
	
	/**
	 * Redirige après une connexion échouée
	 */
	public static function redirect_after_login_failed() {
		/*
		 * TODO : reprendre travail Alexandre sur lightbox
		$page = $_POST['redirect-page-error']; 
		if($_POST['redirect-page-investir'] == "true"){
		    wp_redirect($page.'/?login=failed&redirect=invest#connexion');
		} else if($_POST['redirect-page-investir'] == "forum") {
		     wp_redirect($page.'/?login=failed&redirect=forum#connexion');
		} else {
		    wp_redirect($page.'/?login=failed#connexion');
		}
		 */
	}
}