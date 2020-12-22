<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Invest() );

class WDG_Page_Controler_Invest extends WDG_Page_Controler {
	/**
	 * @var ATCF_Campaign
	 */
	private $current_campaign;
	/**
	 * @var WDGOrganization
	 */
	private $current_campaign_organization;
	/**
	 * @var WDGInvestment
	 */
	private $current_investment;
	
	private $current_step;
	private $form;
	private $form_display_success;
	private $form_display_file_sent;
	private $display_session_lost;
	
	public function __construct() {
		parent::__construct();
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'invest-input' );
		$core->include_form( 'invest-user-details' );
		$core->include_form( 'invest-contract' );
		$core->include_form( 'user-identitydocs' );
		
		date_default_timezone_set( "Europe/London" );
		define( 'SKIP_BASIC_HTML', TRUE );
		
		$this->init_current_campaign();
		if ( empty( $this->current_campaign ) ) {
			WDG_Redirect_Engine::override_redirect( 'les-projets' );
			exit();
		}
		WDGRoutes::redirect_invest_if_not_logged_in();
		WDGRoutes::redirect_invest_if_project_not_investable();
		WDGRoutes::redirect_invest_if_investment_not_initialized();
		
		$this->init_current_investment();
		$this->init_current_step();
		$this->init_show_lost_session();
		$this->init_form_redirect();
	}
	
/******************************************************************************/
// CURRENT CAMPAIGN
/******************************************************************************/
	private function init_current_campaign() {
		$this->current_campaign = atcf_get_current_campaign();
	}
	
	public function get_current_campaign() {
		return $this->current_campaign;
	}
	
	public function get_campaign_min_part() {
		$buffer = ceil( $this->get_campaign_min_amount() / $this->current_campaign->part_value() );
		return $buffer;
	}
	
	public function get_campaign_max_amount() {
		return ypcf_get_max_value_to_invest();
	}
	
	public function get_campaign_min_amount() {
		return ypcf_get_min_value_to_invest();
	}
	
	public function get_campaign_name() {
		return $this->current_campaign->data->post_title;
	}
	
	public function get_campaign_organization_name() {
		if ( !isset( $this->current_campaign_organization ) ) {
			$campaign_organization = $this->current_campaign->get_organization();
			$this->current_campaign_organization = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );
		}
		return $this->current_campaign_organization->get_name();
	}
	
	public function get_campaign_investors_number() {
		$current_number = $this->current_campaign->backers_count();
		return $current_number + 1;
	}
	
	public function get_campaign_current_amount() {
		return $this->current_campaign->current_amount( FALSE );
	}
	
/******************************************************************************/
// CURRENT INVESTMENT
/******************************************************************************/
	/**
	 * Surcharge de WDG_Page_Controler	
	*/
	public function init_show_user_pending_investment() {
		$this->show_user_pending_investment = false;
	}

	/**
	 * Surcharge de WDG_Page_Controler	
	*/
	public function init_show_user_pending_preinvestment() {
		$this->show_user_pending_preinvestment = false;
	}
	
	private function init_current_investment() {
		$input_init_with_id = filter_input( INPUT_GET, 'init_with_id' );
		if ( !empty( $input_init_with_id ) ) {
			$this->current_investment = new WDGInvestment( $input_init_with_id );
			$this->current_investment->init_session_with_saved_values();
		} else {
			$this->current_investment = WDGInvestment::current();
		}
	}
	
	public function get_current_investment() {
		return $this->current_investment;
	}
	
	public function get_current_investment_contract_preview() {
		$current_user = wp_get_current_user();
		$campaign = $this->current_campaign;
		$part_value = $campaign->part_value();
		$amount = $_SESSION[ 'redirect_current_amount_part' ];
        $amount_part = ( $amount === FALSE ) ? 0 : $amount / $part_value;
		
		$invest_data = array(
			"amount_part"					=> $amount_part,
			"amount"						=> $amount,
			"total_parts_company"			=> $campaign->total_parts(),
			"total_minimum_parts_company"	=> $campaign->total_minimum_parts(),
			"ip"							=> filter_input( INPUT_SERVER, 'REMOTE_ADDR' )
		);
		
		$organization = false;
		if ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ) {
			$organization = new WDGOrganization( $_SESSION[ 'redirect_current_user_type' ] );
		}
		return fillPDFHTMLDefaultContent( $current_user, $campaign, $invest_data, $organization, true );
	}
	
/******************************************************************************/
// CURRENT STEP
/******************************************************************************/
	private function init_current_step() {
		$this->current_step = 1;
	}
	public function get_current_step() {
		return $this->current_step;
	}

	private function init_show_lost_session() {
		$input_lost_session = filter_input( INPUT_GET, 'lost_session' );
		$this->display_session_lost = ( $input_lost_session == '1' );
	}
	
	public function get_display_session_lost() {
		return $this->display_session_lost;
	}
	
	/**
	 * Détermine si la fenêtre d'avertissement est visible :
	 * - sur la première page du processus
	 * - quand on y vient pour la première fois
	 * - si on est en collecte
	 * @return boolean
	 */
	public function is_warning_visible() {
		$invest_start = filter_input( INPUT_GET, 'invest_start' );
		return ( $this->current_step == 1 && $invest_start == '1' && $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte );
	}
	public function get_warning_content() {
		$edd_settings = get_option( 'edd_settings' );
		return wpautop( $edd_settings[ 'investment_generalities' ] );
	}
	
	public function get_contract_warning() {
		WDG_PDF_Generator::add_shortcodes();
		$edd_settings = get_option( 'edd_settings' );
		return wpautop( $edd_settings[ 'investment_terms' ] );
	}
	
	public function is_authentication_alert_visible() {
		$WDGUser_current = WDGUser::current();
		return ( $this->current_step == 1 && !$WDGUser_current->is_lemonway_registered() );
	}
	
/******************************************************************************/
// CURRENT FORM
/******************************************************************************/
	/**
	 * On fait les redirections avant que les headers commencent à être envoyés
	 */
	private function init_form_redirect() {
		$action_posted = filter_input( INPUT_POST, 'action' );

		switch ( $action_posted ) {
			// Analyse formulaire validation authentification
			case WDG_Form_User_Identity_Docs::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav != 'previous' ) {
					$this->current_step = 2.5;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form_redirect >> current_step = 2.5 >> WDG_Form_User_Identity_Docs::$name POSTED' );
					if ( !isset( $_SESSION[ 'redirect_current_user_type' ] ) ) {
						wp_redirect( $this->get_form_action() . '&lost_session=1' );
						exit();
					}
				}
				break;

			// Analyse formulaire validation contrat
			case WDG_Form_Invest_Contract::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav != 'previous' ) {
					$this->current_step = 3;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 3 >> WDG_Form_Invest_Contract::$name POSTED' );
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> GOTO moyen-de-paiement' );
						wp_redirect( home_url( '/moyen-de-paiement/' ) . '?campaign_id=' . $this->current_campaign->ID );
						exit();
					}
				}
				break;
		}
	}

	/**
	 * On initialise les formulaires en eux-mêmes un peu plus tard pour avoir la bonne langue chargée
	 * (fonction appelée dans la vue)
	 */
	public function init_form() {
		$current_investment = WDGInvestment::current();
		
		// Récupération d'un éventuel post de formulaire
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted != WDG_Form_Invest_Input::$name && !$current_investment->is_session_correct() ) {
			$action_posted = FALSE;
			$this->current_step = 1;
			ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 1 >> $action_posted != WDG_Form_Invest_Input::$name && !$current_investment->is_session_correct()' );
		}
		
		$this->form_display_success = FALSE;
		$this->form_display_file_sent = FALSE;
		$reload_form = FALSE;
		$input_init_with_id = filter_input( INPUT_GET, 'init_with_id' );
		if ( !empty( $input_init_with_id ) ) {
			$input_cancel = filter_input( INPUT_GET, 'cancel' );
			if ( empty( $input_cancel ) ) {
				if ( $this->current_investment->get_session_amount() >= 10 ) {
					$this->current_step = 3;
					$reload_form = TRUE;
				}
			} else {
				$this->current_step = -1;
				$WDGUser_current = WDGUser::current();
				// Seul l'investisseur peut annuler, et seulement si c'est un investissement non-démarré
				$investment_to_cancel = new WDGInvestment( $input_init_with_id );
				if ( $investment_to_cancel->get_contract_status() == WDGInvestment::$contract_status_not_validated ) {
					$can_cancel = ( $investment_to_cancel->get_saved_user_id() == $WDGUser_current->get_wpref() );
					if ( !$can_cancel ) {
						$user_organizations_list = $WDGUser_current->get_organizations_list();
						if ( $user_organizations_list ) {
							foreach ( $user_organizations_list as $organization_item ) {
								$can_cancel = ( $investment_to_cancel->get_saved_user_id() == $organization_item->wpref );
							}
						}
					}
					if ( $can_cancel ) {
						$investment_to_cancel->cancel();
					}
				}
			}
		}
		
		switch ( $action_posted ) {
			// Analyse formulaire saisie montant
			case WDG_Form_Invest_Input::$name:
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign );
				if ( $this->form->postForm() ) {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_Input::$name POSTED' );
					$reload_form = TRUE;
				}
				break;
			
			// Analyse formulaire saisie infos
			case WDG_Form_Invest_User_Details::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 1;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 1 >> WDG_Form_Invest_User_Details::$name PREVIOUS' );
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_User_Details::$name POSTED' );
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID, $current_investment->get_session_amount() );
					if ( $this->form->postForm() ) {
						$is_investor_registered = FALSE;
						$current_user_type = $_SESSION[ 'redirect_current_user_type' ];
						if ( $current_user_type == 'user' ) {
							$is_investor_registered = $WDGCurrent_User->is_lemonway_registered();
						} else {
							$WDGCurrent_Organization = new WDGOrganization( $current_user_type );
							$is_investor_registered = $WDGCurrent_Organization->is_registered_lemonway_wallet();
						}
						
						if ( $is_investor_registered ) {
							$this->current_step = 3;
							ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 3 >> GOTO WDG_Form_Invest_Contract' );
							$reload_form = TRUE;
						} else {
							$this->current_step = 2.5;
							ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2.5 >> GOTO WDG_Form_User_Identity_Docs' );
							$reload_form = TRUE;
						}
					}
				}
				break;
				
			// Analyse formulaire validation authentification
			case WDG_Form_User_Identity_Docs::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_User_Identity_Docs::$name PREVIOUS' );
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 2.5;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2.5 >> WDG_Form_User_Identity_Docs::$name POSTED' );
					if ( !isset( $_SESSION[ 'redirect_current_user_type' ] ) ) {
						// Normalement, on ne passe plus ici
						wp_redirect( $this->get_form_action() . '&lost_session=1' );
						exit();
					}
					$WDGCurrent_User = WDGUser::current();
					$identity_docs_user_id = ( $_SESSION[ 'redirect_current_user_type' ] == 'user' ) ? $WDGCurrent_User->get_wpref() : $_SESSION[ 'redirect_current_user_type' ];
					$this->form = new WDG_Form_User_Identity_Docs( $identity_docs_user_id, ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ), $this->current_campaign );
					if ( $this->form->postForm() ) {
						ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> GOTO success' );
						$this->form_display_success = TRUE;
						if ( $this->form->getNbFileSent() > 0 ) {
							$this->form_display_file_sent = TRUE;
						}
						// Si l'investisseur n'a pas encore envoyé tous ses documents malgré la validation du formulaire, on lui envoie un mail immédiatement
						if ( WDGOrganization::is_user_organization( $identity_docs_user_id ) ) {
							$WDGEntity = new WDGOrganization( $identity_docs_user_id );
							$user_name = $WDGEntity->get_name();
						} else {
							$WDGEntity = new WDGUser( $identity_docs_user_id );
							$user_name = $WDGEntity->get_firstname();
						}
						if ( !$WDGEntity->has_sent_all_documents() ) {
							NotificationsAPI::investment_authentication_needed( $WDGEntity->get_email(), $user_name, $this->current_campaign->get_name(), $this->current_campaign->get_api_id() );
							WDGQueue::add_investment_authentication_needed_reminder( $WDGEntity->get_wpref(), $WDGEntity->get_email(), $user_name, $this->current_campaign->get_name(), $this->current_campaign->get_api_id() );
						}
					}
				}
				break;
				
			// Analyse formulaire validation contrat
			case WDG_Form_Invest_Contract::$name:
				$input_nav = filter_input( INPUT_POST, 'nav' );
				if ( $input_nav == 'previous' ) {
					$this->current_step = 2;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 2 >> WDG_Form_Invest_Contract::$name PREVIOUS' );
					$reload_form = TRUE;
					
				} else {
					$this->current_step = 3;
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> current_step = 3 >> WDG_Form_Invest_Contract::$name POSTED' );
					$WDGCurrent_User = WDGUser::current();
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					if ( $this->form->postForm() ) {
						// Normalement, on ne passe plus ici
						ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> GOTO moyen-de-paiement' );
						$page_url = WDG_Redirect_Engine::override_get_page_url( 'moyen-de-paiement' );
						wp_redirect( $page_url. '?campaign_id=' . $this->current_campaign->ID );
						exit();
					}
				}
				break;
				
			// Chargement formulaire saisie montant, si rien en cours
			default:
				$init_invest = FALSE;
				if ( $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) {
					$WDGCurrent_User = WDGUser::current();
					$init_invest = $WDGCurrent_User->get_amount_voted_on_campaign( $this->current_campaign->ID );
				} else {
					$init_invest = filter_input( INPUT_GET, 'init_invest' );
				}
				ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> START >> WDG_Form_Invest_Input::$name' );
				$this->form = new WDG_Form_Invest_Input( $this->current_campaign, $init_invest );
				break;
		}
		
		// Chargement du formulaire à afficher
		if ( $reload_form ) {
			$WDGCurrent_User = WDGUser::current();
			switch ( $this->current_step ) {
				case 1:
					$amount_voted_on_campaign = FALSE;
					if ( $this->current_campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) {
						$WDGCurrent_User = WDGUser::current();
						$amount_voted_on_campaign = $WDGCurrent_User->get_amount_voted_on_campaign( $this->current_campaign->ID );
					}
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_Input' );
					$this->form = new WDG_Form_Invest_Input( $this->current_campaign, $amount_voted_on_campaign );
					break;
				case 2:
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_User_Details' );
					$this->form = new WDG_Form_Invest_User_Details( $this->current_campaign, $WDGCurrent_User->wp_user->ID, $current_investment->get_session_amount() );
					break;
				case 2.5:
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_User_Identity_Docs' );
					$identity_docs_user_id = ( $_SESSION[ 'redirect_current_user_type' ] == 'user' ) ? $WDGCurrent_User->get_wpref() : $_SESSION[ 'redirect_current_user_type' ];
					$this->form = new WDG_Form_User_Identity_Docs( $identity_docs_user_id, ( $_SESSION[ 'redirect_current_user_type' ] != 'user' ), $this->current_campaign );
					break;
				case 3:
					ypcf_debug_log( 'WDG_Page_Controler_Invest::init_form >> LOAD WDG_Form_Invest_Contract' );
					$this->form = new WDG_Form_Invest_Contract( $this->current_campaign, $WDGCurrent_User->wp_user->ID );
					break;
			}
		}
		
	}
	
	public function get_form() {
		return $this->form;
	}
	
	public function get_form_errors() {
		return $this->form->getPostErrors();
	}
	
	public function is_form_success_displayed() {
		return $this->form_display_success;
	}
	
	public function is_form_file_sent_displayed() {
		return $this->form_display_file_sent;
	}
	
	public function get_form_action() {
		$url = WDG_Redirect_Engine::override_get_page_url( 'investir' );
		$url .= '?campaign_id=' . $this->current_campaign->ID;
		return $url;
	}
	
}