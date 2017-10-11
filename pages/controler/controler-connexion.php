<?php
global $page_controler;
$page_controler = new WDG_Page_Controler_Connection();

class WDG_Page_Controler_Connection extends WDG_Page_Controler {
	
	private $login_error_reason;
	
	public function __construct() {
		parent::__construct();
		
		if ( is_user_logged_in() ) {
			wp_redirect( WDGUser::get_login_redirect_page() . '#' );
			exit();
		}
		//Cas particulier cause cache :
		// Si on se connecte par facebook sur la page d'accueil ou la liste des projets, 
		// => la redirection directe ne fonctionne pas (rechargement de la page en cache)
		// Solution temporaire : dans ces cas spécifiques, on redirige vers Mon compte
		if ( WDGFormUsers::login_facebook() ) {
			$referer_url = wp_get_referer();
			if ( $referer_url == home_url( '/' ) || $referer_url == home_url( '/les-projets/' ) ) {
				wp_redirect( home_url( '/mon-compte#' ) );
			} else {
				wp_redirect( WDGUser::get_login_redirect_page() . '#' );
			}
			exit();
		}
		
		$this->init_login_error_reason();
	}
	
/******************************************************************************/
// LOGIN ERROR
/******************************************************************************/
	public function get_login_error_reason() {
		return $this->login_error_reason;
	}
	
	private function init_login_error_reason() {
		$error_reason = filter_input( INPUT_GET, 'error_reason' );
		if ( !empty( $error_reason ) ) {
			switch( $error_reason ) {
				case 'empty_fields':
					$this->login_error_reason = __( "Champs vides", 'yproject' );
					break;
				case 'orga_account':
					$this->login_error_reason = __( "Ce compte correspond &agrave; une organisation", 'yproject' );
					break;
				default:
					$this->login_error_reason = __( "Cet utilisateur n'existe pas ou le mot de passe ne correspond pas.", 'yproject' );
					break;
			}
		}
	}
		
	
}