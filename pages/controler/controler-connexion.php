<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Connection() );

class WDG_Page_Controler_Connection extends WDG_Page_Controler {
	private $login_error_reason;
	private $display_alert_project;
	private $login_init;

	public function __construct() {
		parent::__construct();

		if ( is_user_logged_in() ) {
			ypcf_debug_log( 'WDG_Page_Controler_Connection::is_user_logged_in' );
			wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			exit();
		}

		//Cas particulier cause cache :
		// Si on se connecte par facebook sur la page d'accueil ou la liste des projets,
		// => la redirection directe ne fonctionne pas (rechargement de la page en cache)
		// Solution temporaire : dans ces cas spécifiques, on redirige vers Mon compte
		if ( WDGFormUsers::login_facebook() ) {
			$referer_url = wp_get_referer();
			if ( $referer_url == home_url( '/' ) || $referer_url == WDG_Redirect_Engine::override_get_page_url( 'les-projets' ) ) {
				ypcf_debug_log( 'WDG_Page_Controler_Connection::login_facebook > mon-compte' );
				wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ) . '#' );
			} else {
				ypcf_debug_log( 'WDG_Page_Controler_Connection::login_facebook > #' );
				wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			}
			exit();
		}

		ypcf_session_start();
		$_SESSION[ 'login-fb-referer' ] = WDGUser::get_login_redirect_page();
		$input_redirect_invest = filter_input( INPUT_GET, 'redirect-invest' );
		if ( !empty( $input_redirect_invest ) ) {
			$invest_url = WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?campaign_id=' .$input_redirect_invest. '&amp;invest_start=1';
			$_SESSION[ 'login-fb-referer' ] = $invest_url;
		}

		$this->login_init = '';
		$this->init_login_error_reason();

		$input_source = filter_input( INPUT_GET, 'source' );
		$this->display_alert_project = ( $input_source == 'project' );
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
			switch ( $error_reason ) {
				case 'empty_fields':
					$this->login_error_reason = __( 'login.ERROR_EMPTY_FIELD', 'yproject' );
					break;
				case 'orga_account':
					$this->login_error_reason = __( 'login.ERROR_ORGANIZATION_ACCOUNT', 'yproject' );
					break;
				default:
					$this->login_error_reason = __( 'login.ERROR_USER_NOT_FOUND', 'yproject' );
					$this->login_init = $error_reason;
					if ( !empty( $this->login_init ) ) {
						$this->login_init = stripslashes( htmlentities( $this->login_init, ENT_QUOTES | ENT_HTML401 ) );
						$this->login_init = str_replace( ' ', '+', $this->login_init );
					}
					break;
			}
		}
	}

	public function get_display_alert_project() {
		return $this->display_alert_project;
	}

	public function get_login_init() {
		return $this->login_init;
	}
}