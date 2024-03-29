<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Subscription_Contract() );

class WDG_Page_Controler_Subscription_Contract extends WDG_Page_Controler {
	private $campaign;
	private $subscription;
	private $current_user;
	private $id_subscription;

	private $form_user_subscription_contract;
	private $form_user_feedback;

	public function __construct() {
		parent::__construct();
		global $WDGSubscription;

		$id_subscription = filter_input(INPUT_GET,'id_subscription');
		$this->subscription = new WDGSUBSCRIPTION($id_subscription);
		$WDGSubscription  = $this->subscription;

		$id_project = $this->subscription->id_project;
		$this->campaign = new ATCF_Campaign(false, $id_project);
		
		$this->current_user = WDGUser::current();
		
		if ( empty( $this->subscription->id ) ) {
			ypcf_debug_log( 'WDG_Page_Controler_Subscription_Contract error empty $this->subscription->id' );
		} else {
			$this->init_form_subscription_contract();
		}
	}


	private function init_form_subscription_contract() {
		$this->form_user_subscription_contract = new WDG_Form_Subscription_Contract($this->current_user->get_wpref());
		if(isset($_SESSION[ 'account_organization_form_subscription_feedback_' . $this->current_user->get_wpref() ])){
			$this->form_user_feedback = $_SESSION[ 'account_organization_form_subscription_feedback_' . $this->current_user->get_wpref() ];
			$_SESSION[ 'account_organization_form_subscription_feedback_' . $this->current_user->get_wpref() ] = FALSE;
		}
	}

	public function get_contract_subscription_form() {
		return $this->form_user_subscription_contract;
	}

	public function get_user_form_feedback() {
		return $this->form_user_feedback;
	}

    /******************************************************************************/
	// RECAP ABONNEMENT
	/******************************************************************************/
	public function get_contract_warning() {
		WDG_PDF_Generator::add_shortcodes();
		$subscription_terms = WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_subscription_terms, 'subscription_terms' );

		return wpautop( $subscription_terms );
	}


    /******************************************************************************/
	// AFFICHAGE DU CONTRAT
	/******************************************************************************/
	public function get_current_investment_contract_preview() {
		$organization = FALSE;
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$organization = 'orga';
		}

		return fillPDFHTMLDefaultContent( 'user', $this->campaign, FALSE, $organization, true );
	}

    /******************************************************************************/
	// VALIDATION DU CONTRAT
	/******************************************************************************/
	public function get_form_action() {
		$this->id_subscription = filter_input( INPUT_GET, 'id_subscription' );
		return admin_url( 'admin-post.php?action=user_account_validate_contract_subscription&id_subscription='.$this->id_subscription);
	}
}