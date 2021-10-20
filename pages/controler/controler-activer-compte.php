<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Validation_Email() );

class WDG_Page_Controler_Validation_Email extends WDG_Page_Controler {
	private static $view_email_validated_old_account = 'email-validated-old-account';
	private static $view_email_validated = 'email-validated';
	private static $view_email_already_validated = 'email-already-validated';
	private static $view_email_validation_error = 'email-validation-error';

	/**
	 * String
	 * Vue en cours
	 */
	private $current_view;
	/**
	 * WDGUser
	 * Utilisateur identifié
	 */
	private $current_user;
	/**
	 * String
	 * URL vers laquelle on redirige l'utilisateur automatiquement
	 */
	private $current_auto_redirect_link;
	/**
	 * String
	 * Indique si c'est une création de compte ou pas
	 */
	private $current_user_is_new_account;

	/**
	 * Constructeur du controler
	 */
	public function __construct() {
		parent::__construct();

		$this->controler_name = 'activer-compte';
		$this->check_validation_action();
	}

	/**
	 * Vérifie les redirections et éléments à afficher
	 */
	private function check_validation_action() {
		$input_code = filter_input( INPUT_GET, 'validation-code' );
		$input_is_new_account = filter_input( INPUT_GET, 'is-new-account' );

		// Si l'utilisateur n'est pas connecté, on redirige vers le formulaire de connexion
		if ( !is_user_logged_in() ) {
			// Transmission du code de validation à Vue
			$params_validate = '';
			if ( !empty( $input_code ) ) {
				$params_validate = '&validation-code=' . $input_code;
				$params_validate .= '&is-new-account=' . $input_is_new_account;
			}
			// Redirection vers page de login
			wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'connexion' ) . '?redirect-page=activer-compte' . $params_validate );
			exit();
		}

		// Récupération de l'utilisateur connecté
		$this->current_user = WDGUser::current();

		// Si l'utilisateur est déjà validé (Exemple : une personne était déjà connectée, et une autre clique sur le lien dans un mail)
		if ( $this->current_user->is_email_validated() ) {
			// On affiche  "Le compte connecté correspond à l'adresse xxx@xxx.xx, cette adresse e-mail a déjà été validée, nous vous redirigeons"
			$this->current_view = self::$view_email_already_validated;
			// Et rediriger
			$this->current_auto_redirect_link = WDGUser::get_login_redirect_page();

			return;
		}

		$input_action = filter_input( INPUT_GET, 'action' );
		$this->current_user_is_new_account = $input_is_new_account;

		// Si l'utilisateur connecté n'est pas encore validé, qu'il demande la validation et que le code correspond
		$user_validation_code = $this->current_user->get_email_validation_code();
		if ( $input_action == 'validate' && !empty( $input_code ) && wp_is_uuid( $user_validation_code ) && $input_code == $user_validation_code ) {
			// On valide l'adresse e-mail
			$this->current_user->set_email_is_validated();
			// On affiche le message de confirmation (différent selon si c'est un nouveau ou un ancien compte)
			$this->current_view = self::$view_email_validated;
			if ( $input_is_new_account !== '1' ) {
				$this->current_view = self::$view_email_validated_old_account;
			} else {
				// Envoi de l'évènement à Analytics pour dire qu'un compte a été créé
				ypcf_session_start();
				$_SESSION['send_creation_event'] = 1;
			}

			// Redirection
			// On redirige vers la page où la personne était précédemment
			$this->current_auto_redirect_link = WDGUser::get_login_redirect_page();
			$meta_redirect = get_user_meta( $this->current_user->get_wpref(), 'redirect_url_after_validation', TRUE );
			if ( !empty( $meta_redirect ) ) {
				$this->current_auto_redirect_link = $meta_redirect;
				delete_user_meta( $this->current_user->get_wpref(), 'redirect_url_after_validation' );
			}

			// TODO : A venir :
			// Si c'est un nouvel inscrit, on le redirige vers le parcours d'authentification
			/*
			if ( $input_is_new_account === '1' ) {
			}
			*/
			return;
		}

		// Si on est ici, l'activation du mail n'a pas fonctionné
		// On affiche "Le compte connecté correspond à l'adresse xxx@xxx.xx, mais le code de validation ne correspond pas. Nous pouvons vous renvoyer un e-mail si vous cliquez sur le bouton ci-dessous."
		// On affiche un bouton pour renvoyer un mail de validation
		$this->current_view = self::$view_email_validation_error;
	}

	public function get_current_view() {
		return $this->current_view;
	}

	public function get_current_redirect_link() {
		return $this->current_auto_redirect_link;
	}

	public function get_current_user_email() {
		return $this->current_user->get_email();
	}

	public function get_current_user_sessionUID() {
		return $this->current_user->get_email_validation_code();
	}

	public function get_current_user_is_new_account() {
		return $this->current_user_is_new_account;
	}
}