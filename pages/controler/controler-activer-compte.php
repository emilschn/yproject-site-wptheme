<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Validation_Email() );

class WDG_Page_Controler_Validation_Email extends WDG_Page_Controler {
	public function __construct() {
		parent::__construct();

		// TODO : on vérifie si l'utilisateur est connecté
		$this->controler_name = 'activer-compte';
		$redirect_page = filter_input( INPUT_GET, 'redirect-page' );
		if (is_user_logged_in()) {
			// s'il est connecté, alors on valide son email
			$WDGUser_current = WDGUser::current();
			$WDGUser_current->set_email_is_validated( TRUE );

		// si ça marche et que c'est un nouvel inscrit, on le redirige vers le parcours d'authentification

			// si ça marche et que c'est un ancien inscrit, on le redirige vers la page où il était avant de se connecter. Si on ne trouve pas cette page, on le redirige vers mon compte

			// si l'activation du mail ne marche pas, on propose un bouton pour renvoyer un mail de validation
		} else {
			// si l'utilisateur n'est pas connecté, je ne sais pas encore ce qu'on fait
			wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'connexion' ) . '?redirect-page=activer-compte' );
			exit();
		}
	}
}