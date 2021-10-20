<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_AccountSignin() );

class WDG_Page_Controler_AccountSignin extends WDG_Page_Controler {
	private $redirect_url_by_language;

	public function __construct() {
		parent::__construct();


		//*****
		// Gestion des redirections en cas de login
		if ( is_user_logged_in() ) {
			ypcf_debug_log( 'WDG_Page_Controler_AccountSignin::is_user_logged_in' );
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
				ypcf_debug_log( 'WDG_Page_Controler_AccountSignin::login_facebook > mon-compte' );
				wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ) . '#' );
			} else {
				ypcf_debug_log( 'WDG_Page_Controler_AccountSignin::login_facebook > #' );
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
		//*****


		define( 'SKIP_BASIC_HTML', TRUE );
		$this->init_redirect_url_by_language();

		// on récupère le composant Vue
		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_account_signin );

		$this->guid = filter_input( INPUT_GET, 'guid' );
	}

	public function get_init_locale() {
		return WDG_Languages_Helpers::get_current_locale_id();
	}

	private function init_redirect_url_by_language() {
		global $force_language_to_translate_to;
		$this->redirect_url_by_language = array();

		$force_language_to_translate_to = 'fr';
		WDG_Languages_Helpers::switch_to_temp_language( 'fr' );
		$this->redirect_url_by_language[ 'fr' ] = WDGUser::get_login_redirect_page();

		WDG_Languages_Helpers::switch_to_temp_language( 'en' );
		$force_language_to_translate_to = 'en';
		$this->redirect_url_by_language[ 'en' ] = WDGUser::get_login_redirect_page();

		WDG_Languages_Helpers::switch_back_to_display_language();
	}

	public function get_redirect_url_by_language( $language ) {
		return $this->redirect_url_by_language[ $language ];
	}

	/**
	 * Si il y a un code de validation transmis via l'URL, c'est qu'on vient de la page d'activation de compte en étant déconnecté
	 * On transmet l'information à Vue pour que l'app redirige directement vers la page d'activation, au lieu de renvoyer un mail
	 */
	public function get_param_validation_code() {
		$input_code = filter_input( INPUT_GET, 'validation-code' );
		return !empty( $input_code ) ? '1' : '0';
	}
}