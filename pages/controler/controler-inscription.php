<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Register() );

class WDG_Page_Controler_Register extends WDG_Page_Controler {
	private $signup_email_init;
	private $signup_firstname_init;
	private $signup_lastname_init;

	public function __construct() {
		parent::__construct();

		if ( is_user_logged_in() ) {
			ypcf_debug_log( 'WDG_Page_Controler_Register::is_user_logged_in' );
			wp_redirect( WDGUser::get_login_redirect_page( '#' ) );
			exit();
		}

		ypcf_session_start();
		$_SESSION[ 'login-fb-referer' ] = WDGUser::get_login_redirect_page();

		$this->signup_email_init = filter_input( INPUT_POST, 'signup_email' );
		if ( !empty( $this->signup_email_init ) ) {
			$this->signup_email_init = stripslashes( htmlentities( $this->signup_email_init, ENT_QUOTES | ENT_HTML401 ) );
		}
		$this->signup_firstname_init = filter_input( INPUT_POST, 'signup_firstname' );
		if ( !empty( $this->signup_firstname_init ) ) {
			$this->signup_firstname_init = stripslashes( htmlentities( $this->signup_firstname_init, ENT_QUOTES | ENT_HTML401 ) );
		}
		$this->signup_lastname_init = filter_input( INPUT_POST, 'signup_lastname' );
		if ( !empty( $this->signup_lastname_init ) ) {
			$this->signup_lastname_init = stripslashes( htmlentities( $this->signup_lastname_init, ENT_QUOTES | ENT_HTML401 ) );
		}
	}

	public function get_signup_email_init() {
		return $this->signup_email_init;
	}

	public function get_signup_firstname_init() {
		return $this->signup_firstname_init;
	}

	public function get_signup_lastname_init() {
		return $this->signup_lastname_init;
	}
}