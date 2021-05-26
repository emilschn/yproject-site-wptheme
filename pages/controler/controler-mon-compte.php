<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_User_Account() );

class WDG_Page_Controler_User_Account extends WDG_Page_Controler_WDG {
	/**
	 * @var WDGUser
	 */
	private $current_user;
	private $current_admin_user;

	private $current_user_organizations;
	private $current_user_authentication;
	private $current_user_authentication_info;
	private $user_id;
	private $user_name;
	private $user_project_list;
	private $user_data;
	private $user_kyc_duplicates;
	private $display_user_override_not_found;
	private $display_user_override_organization_manager_mail;

	private $wallet_to_bankaccount_result;
	private $change_wire_amount_result;
	private $form_user_details;
	private $form_user_password;
	private $form_user_delete;
	private $form_user_identitydocs;
	private $form_user_bank;
	private $form_user_notifications;
	private $form_user_feedback;
	private $form_user_tax_exemption;
	private $form_user_change_investor_feedback;
	private $list_intentions_to_confirm;
	private $tax_documents;

	public function __construct() {
		parent::__construct();
		define( 'SKIP_BASIC_HTML', TRUE );

		if ( !is_user_logged_in() ) {
			wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'connexion' ) . '?redirect-page=mon-compte' );
			exit();
		}

		// Si l'utilisateur change la langue et l'enregistre,
		// Raccourci ici pour rediriger vers la bonne langue
		$action_posted = filter_input( INPUT_POST, 'action' );
		$input_language = filter_input( INPUT_POST, 'language' );
		global $locale;
		if ( !empty( $input_language ) && substr( $locale, 0, 2 ) != $input_language && $action_posted == WDG_Form_User_Details::$name ) {
			$this->init_current_user( FALSE );
			$form_user_details = new WDG_Form_User_Details( $this->current_user->get_wpref(), WDG_Form_User_Details::$type_extended );
			$form_user_details->postForm();

			global $post;
			$language_permalink = apply_filters( 'wpml_permalink', get_permalink( $post->ID ), $input_language );
			if ( !empty( $language_permalink ) ) {
				wp_redirect( $language_permalink );
				//exit();
			}
		}
		WDG_Languages_Helpers::load_languages();
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'user-password' );
		$core->include_form( 'user-unlink-facebook' );
		$core->include_form( 'user-identitydocs' );
		$core->include_form( 'user-bank' );
		$core->include_form( 'user-notifications' );
		$WDGUser_current = WDGUser::current();
		if ( $WDGUser_current->is_admin() ) {
			$core->include_form( 'user-delete' );
		}

		// Si on met à jour le RIB, il faut recharger l'utilisateur en cours
		$reload = WDGFormUsers::register_rib();
		$this->wallet_to_bankaccount_result = WDGFormUsers::wallet_to_bankaccount();
		$this->change_wire_amount_result = WDGFormUsers::change_wire_amount();
		$this->display_user_override_not_found = FALSE;
		$this->display_user_override_organization_manager_mail = FALSE;
		$this->init_current_user( $reload );
		$this->init_project_list();
		$this->init_intentions_to_confirm();
		$this->init_form_user_details();
		$this->init_form_change_investment_owner();
		$this->init_form_user_identitydocs();
		$this->init_form_user_bank();
		$this->init_form_user_notifications();
		$this->init_form_user_tax_exemption();
		$this->init_tax_documents();

		$this->controler_name = 'mon-compte';

		wp_enqueue_style( 'dashboard-investor-css', dirname( get_bloginfo( 'stylesheet_url' ) ).'/_inc/css/dashboard-investor.css', null, ASSETS_VERSION, 'all');
		wp_enqueue_script( 'wdg-user-account', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-user-account.js', array('jquery', 'jquery-ui-dialog'), ASSETS_VERSION);

		if ( $this->get_current_admin_user() ) {
			$this->enqueue_datatable( true );
		}
	}

	/******************************************************************************/
	// CURRENT USER
	/******************************************************************************/
	/**
	 * Retourne les informations de l'utilisateur en cours
	 * @return WDGUser
	 */
	public function get_current_user() {
		return $this->current_user;
	}
	/**
	 * Retourne les informations de l'utilisateur admin en cours (si override)
	 * @return WDGUser
	 */
	public function get_current_admin_user() {
		return $this->current_admin_user;
	}

	/**
	 * si l'utilisateur courant est un admin et qu'il prend le contrôle d'un autre utilisateur
	 * @return boolean
	 */
	public function admin_is_overriding_user() {
		if (($this->get_current_admin_user() && $this->get_current_admin_user()->is_admin() && $this->get_current_user() && $this->get_current_admin_user() != $this->get_current_user())) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_current_user_organizations() {
		return $this->current_user_organizations;
	}

	public function get_current_user_iban() {
		return $this->current_user->get_lemonway_iban();
	}

	public function get_current_user_iban_status() {
		return $this->current_user->get_lemonway_iban_status();
	}

	public function get_current_user_iban_document_status() {
		return $this->current_user->get_document_lemonway_status( LemonwayDocument::$document_type_bank );
	}

	public function get_current_user_authentication() {
		return $this->current_user_authentication;
	}

	public function get_current_user_authentication_info() {
		return $this->current_user_authentication_info;
	}

	private function init_current_user($reload) {
		$WDGUser_current = WDGUser::current();
		if ( $reload ) {
			$WDGUser_current->construct_with_api_data();
		}
		$this->current_user = $WDGUser_current;
		$this->current_admin_user = $WDGUser_current;

		// Si on surcharge avec un utilisateur passé en paramètre
		if ( $WDGUser_current->is_admin() ) {
			$input_user = filter_input( INPUT_GET, 'override_current_user' );
			if ( !empty( $input_user ) ) {
				// Test par e-mail
				$wpuser_by_email = get_user_by_email( $input_user );
				if ( !empty( $wpuser_by_email ) ) {
					$this->current_user = new WDGUser( $wpuser_by_email->ID );
				} else {
					// Test par ID
					$wpuser_by_id = get_user_by( 'ID', $input_user );
					if ( !empty( $wpuser_by_id ) ) {
						$this->current_user = new WDGUser( $input_user );
					} else {
						$this->display_user_override_not_found = TRUE;
					}
				}

				if ( WDGOrganization::is_user_organization( $this->current_user->get_wpref() ) ) {
					$this->display_user_override_organization_manager_mail = '---';
					$WDGOrganization = new WDGOrganization( $this->current_user->get_wpref() );
					$linked_users_creator = $WDGOrganization->get_linked_users( WDGWPREST_Entity_Organization::$link_user_type_creator );
					if ( !empty( $linked_users_creator ) ) {
						$WDGUser_creator = $linked_users_creator[ 0 ];
						$this->display_user_override_organization_manager_mail = $WDGUser_creator->get_email();
					}
				}
			}
		}

		$this->init_current_user_organizations();
		$this->init_current_user_authentication();
		$this->user_id = $this->current_user->get_wpref();
		$this->init_user_name();
	}

	private function init_current_user_organizations() {
		$this->current_user_organizations = array();
		$organizations_list = $this->current_user->get_organizations_list();
		if ( !empty( $organizations_list ) ) {
			$core = ATCF_CrowdFunding::instance();
			$core->include_form( 'organization-details' );
			foreach ( $organizations_list as $organization_item ) {
				$organization_obj = new WDGOrganization( $organization_item->wpref );

				// Au cas où, vérifie si les transferts de royalties en attente ont bien été effectués
				if ( $organization_obj->is_registered_lemonway_wallet() ) {
					$WDGUserInvestments = new WDGUserInvestments( $organization_obj );
					$WDGUserInvestments->try_transfer_waiting_roi_to_wallet();
				}

				array_push( $this->current_user_organizations, $organization_obj );
			}
		}
	}

	private function init_current_user_authentication() {
		$this->current_user_authentication = 0;
		$this->current_user_authentication_info = '';

		// Vérifications pour niveau 1 : les infos sont renseignées
		if ( $this->current_user->can_register_lemonway() ) {
			$this->current_user_authentication = 1;
		} else {
			$this->current_user_authentication_info = ''; //
		}

		// Vérifications pour niveau 2 : les documents sont vérifiés
		if ( $this->current_user_authentication == 1 && $this->current_user->is_lemonway_registered() ) {
			$this->current_user_authentication = 2;
			// Au cas où, vérifie si les transferts de royalties en attente ont bien été effectués
			$WDGUserInvestments = new WDGUserInvestments( $this->current_user );
			$WDGUserInvestments->try_transfer_waiting_roi_to_wallet();
		} else {
			$this->current_user_authentication_info = ''; //
			$this->current_user->send_kyc( FALSE );
		}

		// Vérifications pour niveau 3 : le RIB est validé
		if ( $this->current_user_authentication == 2 && $this->current_user->get_lemonway_iban_status() == WDGUser::$iban_status_validated ) {
			$this->current_user_authentication = 3;
		} else {
			$this->current_user_authentication_info = ''; //
		}
	}

	public function init_show_user_needs_authentication() {
		$this->show_user_needs_authentication = false;
	}

	/**
	 * Fonction de triche pour passer dans Mon compte
	 */
	public function get_campaign() {
		return FALSE;
	}

	/**
	 * Doit-on afficher une alerte si l'utilisateur qu'on essaie d'afficher n'existe pas ?
	 */
	public function is_displayed_user_override_not_found() {
		return $this->display_user_override_not_found;
	}

	/**
	 * Doit-on afficher une alerte si l'utilisateur qu'on essaie d'afficher est une organisation
	 */
	public function is_displayed_user_override_organization() {
		return ( $this->display_user_override_organization_manager_mail !== FALSE );
	}

	/**
	 * Récupération de l'adresse e-mail
	 */
	public function user_override_organization_manager_mail() {
		return $this->display_user_override_organization_manager_mail;
	}

	/******************************************************************************/
	// USER ID
	/******************************************************************************/
	public function get_user_id() {
		return $this->user_id;
	}

	/******************************************************************************/
	// USER NAME
	/******************************************************************************/
	public function get_user_name() {
		return $this->user_name;
	}
	private function init_user_name() {
		$first_name = $this->current_user->get_firstname();
		if ( !empty( $first_name ) ) {
			$this->user_name = $first_name;
		} else {
			$display_name = $this->current_user->wp_user->display_name;
			if ( !empty( $display_name ) ) {
				$this->user_name = $display_name;
			} else {
				$this->user_name = $this->current_user->wp_user->user_login;
			}
		}
	}

	/******************************************************************************/
	// USER DATA
	/******************************************************************************/
	private function init_form_user_details() {
		$this->form_user_details = new WDG_Form_User_Details( $this->current_user->get_wpref(), WDG_Form_User_Details::$type_extended );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Details::$name ) {
			$this->form_user_feedback = $this->form_user_details->postForm();
			$this->init_current_user( TRUE );
		}

		if ( $this->current_user->is_logged_in_with_facebook() ) {
			$this->form_unlink_facebook = new WDG_Form_User_Unlink_Facebook( $this->current_user->get_wpref() );
			if ( $action_posted == WDG_Form_User_Unlink_Facebook::$name ) {
				$this->form_user_feedback = $this->form_unlink_facebook->postForm();
			}
		} else {
			$this->form_user_password = new WDG_Form_User_Password( $this->current_user->get_wpref() );
			if ( $action_posted == WDG_Form_User_Password::$name ) {
				$this->form_user_feedback = $this->form_user_password->postForm();
			}
		}

		if ( $this->current_admin_user->is_admin() ) {
			$this->form_user_delete = new WDG_Form_User_Delete( $this->current_user->get_wpref() );
			if ( $action_posted == WDG_Form_User_Delete::$name ) {
				$this->form_user_feedback = $this->form_user_delete->postForm();
				$this->init_current_user( TRUE );
			}
		}
	}

	public function get_user_details_form() {
		return $this->form_user_details;
	}

	public function get_user_password_form() {
		return $this->form_user_password;
	}

	public function get_user_unlink_facebook_form() {
		return $this->form_unlink_facebook;
	}

	public function get_user_form_feedback() {
		return $this->form_user_feedback;
	}

	public function get_user_form_delete() {
		return $this->form_user_delete;
	}

	public function get_user_data($data_key) {
		$buffer = '';
		if ( !empty( $data_key ) ) {
			if ( empty( $this->user_data[ $data_key ] ) ) {
				if ( isset( $this->current_user->wp_user->{ 'user_' . $data_key } ) ) {
					$this->user_data[ $data_key ] = $this->current_user->wp_user->{ 'user_' . $data_key };
				} else {
					if ( isset( $this->current_user->wp_user->{ $data_key } ) ) {
						$this->user_data[ $data_key ] = $this->current_user->wp_user->{ $data_key };
					} else {
						$this->user_data[ $data_key ] = $this->current_user->wp_user->get( 'user_' . $data_key );
						if ( empty( $this->user_data[ $data_key ] ) ) {
							$this->user_data[ $data_key ] = $this->current_user->wp_user->get( $data_key );
						}
					}
				}
			}
			$buffer = $this->user_data[ $data_key ];
		}

		return $buffer;
	}

	/******************************************************************************/
	// INVESTMENT CHANGE INVESTOR
	/******************************************************************************/
	private function init_form_change_investment_owner() {
		if ( $this->get_current_user()->is_admin() ) {
			$core = ATCF_CrowdFunding::instance();
			$core->include_form( 'user-change-investment-owner' );
			$form = new WDG_Form_User_Change_Investment_Owner();
			$this->form_user_change_investor_feedback = $form->postForm();
		}
	}

	public function get_form_user_change_investor_feedback() {
		return $this->form_user_change_investor_feedback;
	}

	/******************************************************************************/
	// USER DOCUMENTS
	/******************************************************************************/
	private function init_form_user_identitydocs() {
		$this->form_user_identitydocs = new WDG_Form_User_Identity_Docs( $this->current_user->get_wpref() );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Identity_Docs::$name ) {
			$this->form_user_feedback = $this->form_user_identitydocs->postForm();
		}
		$this->user_kyc_duplicates = $this->form_user_identitydocs->getDuplicates();
	}

	public function get_user_identitydocs_form() {
		return $this->form_user_identitydocs;
	}

	public function has_kyc_duplicates() {
		return !empty( $this->user_kyc_duplicates );
	}

	public function get_kyc_duplicates() {
		return $this->user_kyc_duplicates;
	}

	/******************************************************************************/
	// USER BANK
	/******************************************************************************/
	private function init_form_user_bank() {
		$this->form_user_bank = new WDG_Form_User_Bank( $this->current_user->get_wpref() );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Bank::$name ) {
			$this->form_user_feedback = $this->form_user_bank->postForm();
		}
	}

	public function get_user_bank_form() {
		return $this->form_user_bank;
	}

	public function is_iban_validated() {
		$lw_iban_status = $this->current_user->get_lemonway_iban_status();
		$lw_doc_iban_status = $this->current_user->get_document_lemonway_status( LemonwayDocument::$document_type_bank );

		return ( $lw_iban_status == WDGUser::$iban_status_validated && $lw_doc_iban_status == LemonwayDocument::$document_status_accepted );
	}

	public function is_iban_waiting() {
		$lw_iban_status = $this->current_user->get_lemonway_iban_status();
		$lw_doc_iban_status = $this->current_user->get_document_lemonway_status( LemonwayDocument::$document_type_bank );

		return ( $lw_iban_status == WDGUser::$iban_status_waiting && ( $lw_doc_iban_status == LemonwayDocument::$document_status_waiting || $lw_doc_iban_status == LemonwayDocument::$document_status_waiting_verification ) );
	}

	/******************************************************************************/
	// USER NOTIFICATIONS
	/******************************************************************************/
	private function init_form_user_notifications() {
		$this->form_user_notifications = new WDG_Form_User_Notifications( $this->current_user->get_wpref() );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Notifications::$name ) {
			$this->form_user_feedback = $this->form_user_notifications->postForm();
		}
	}

	public function get_user_notifications_form() {
		return $this->form_user_notifications;
	}

	/******************************************************************************/
	// USER TAX EXEMPTION
	/******************************************************************************/
	private function init_form_user_tax_exemption() {
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'user-tax-exemption' );
		$this->form_user_tax_exemption = new WDG_Form_User_Tax_Exemption( $this->current_user->get_wpref() );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Tax_Exemption::$name ) {
			$this->form_user_feedback = $this->form_user_tax_exemption->postForm();
		}
	}

	public function get_user_tax_exemption_form() {
		return $this->form_user_tax_exemption;
	}

	public function get_can_ask_tax_exemption() {
		return $this->current_user->get_tax_country() == 'FR' || $this->current_user->get_tax_country() == '';
	}

	public function get_show_user_tax_exemption_form() {
		$date_today = new DateTime();
		$inprogress_year = $date_today->format( 'Y' );
		$next_year = $date_today->format( 'Y' )+1;
		$tax_exemption_filename_inprogress = get_user_meta( $this->current_user->get_wpref(), 'tax_exemption_' .$inprogress_year, TRUE );
		$tax_exemption_filename_next = get_user_meta( $this->current_user->get_wpref(), 'tax_exemption_' .$next_year, TRUE );

		return ( (empty( $tax_exemption_filename_inprogress ) || empty( $tax_exemption_filename_next )) && $this->get_can_ask_tax_exemption() );
	}

	public function get_tax_exemption_preview($year) {
		$core = ATCF_CrowdFunding::instance();
		$core->include_control( 'templates/pdf/form-tax-exemption' );
		$user_name = $this->current_user->get_firstname(). ' ' .$this->current_user->get_lastname();
		$user_address = $this->current_user->get_full_address_str(). ' ' .$this->current_user->get_postal_code( TRUE ). ' ' .$this->current_user->get_city();
		$form_ip_address = $_SERVER[ 'REMOTE_ADDR' ];
		$date_today = new DateTime();
		$form_date = $date_today->format( 'd/m/Y' ); // TODO à changer suivant l'année ?

		return WDG_Template_PDF_Form_Tax_Exemption::get( $user_name, $user_address, $form_ip_address, $form_date, $year );
	}

	/******************************************************************************/
	// USER TAX DOCUMENTS
	/******************************************************************************/
	private function init_tax_documents() {
		$this->tax_documents = array();

		$this->tax_documents[ 'user' ] = array();
		foreach ( $this->current_user_organizations as $WDGOrganization ) {
			$this->tax_documents[ $WDGOrganization->get_wpref() ] = array();
		}

		$date_today = new DateTime();
		$today_year = $date_today->format( 'Y' );
		for ( $year = 2019; $year <= $today_year; $year++ ) {
			$tax_document = $this->current_user->has_tax_document_for_year( $year );
			if ( $tax_document ) {
				$this->tax_documents[ 'user' ][ $year ] = $tax_document;
			}

			foreach ( $this->current_user_organizations as $WDGOrganization ) {
				$tax_document = $WDGOrganization->has_tax_document_for_year( $year );
				if ( $tax_document ) {
					$this->tax_documents[ $WDGOrganization->get_wpref() ][ $year ] = $tax_document;
				}
			}
		}
	}

	public function has_tax_documents($orga_id = FALSE) {
		if ( empty( $orga_id ) ) {
			$orga_id = 'user';
		}

		return !empty( $this->tax_documents[ $orga_id ] );
	}

	public function get_tax_documents($orga_id = FALSE) {
		if ( empty( $orga_id ) ) {
			$orga_id = 'user';
		}

		return $this->tax_documents[ $orga_id ];
	}

	/******************************************************************************/
	// PROJECT LIST
	/******************************************************************************/
	public function has_user_project_list() {
		return ( !empty( $this->user_project_list ) );
	}
	public function get_user_project_list() {
		return $this->user_project_list;
	}
	private function init_project_list() {
		$this->user_project_list = array();

		$args = array(
			'post_type'		=> 'download',
			'author'		=> $this->current_user->wp_user->ID,
			'post_status'	=> 'publish'
		);
		query_posts($args);

		if (have_posts()) {
			while (have_posts()) {
				the_post();
				$campaign = new ATCF_Campaign( get_the_ID() );
				$campaign_organization = $campaign->get_organization();
				if ( !empty( $campaign_organization->wpref ) ) {
					$WDGOrganization = new WDGOrganization( $campaign_organization->wpref );
					$project = array(
						'link'	=> WDG_Redirect_Engine::override_get_page_url( 'tableau-de-bord' ) . '?campaign_id=' . get_the_ID(),
						'name'	=> get_the_title(),
						'authentified'	=> $WDGOrganization->is_registered_lemonway_wallet()
					);
					array_push( $this->user_project_list, $project );
				}
			}
		}
		wp_reset_query();

		$api_user_id = $this->current_user->get_api_id();
		$project_list = WDGWPREST_Entity_User::get_projects_by_role( $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
		if ( !empty( $project_list ) ) {
			foreach ($project_list as $project) {
				$campaign = new ATCF_Campaign( $project->wpref );
				$campaign_organization = $campaign->get_organization();
				if ( !empty( $campaign_organization->wpref ) ) {
					$WDGOrganization = new WDGOrganization( $campaign_organization->wpref );
					$project = array(
						'link'	=> WDG_Redirect_Engine::override_get_page_url( 'tableau-de-bord' ) . '?campaign_id=' . $project->wpref,
						'name'	=> $project->name,
						'authentified'	=> $WDGOrganization->is_registered_lemonway_wallet()
					);
					array_push( $this->user_project_list, $project );
				}
			}
		}
	}

	/******************************************************************************/
	// INTENTIONS D'INVESTISSEMENT SANS INVESTISSEMENT
	/******************************************************************************/
	private function init_intentions_to_confirm() {
		$this->list_intentions_to_confirm = array();

		if ( $this->current_user ) {
			$this->list_intentions_to_confirm = $this->current_user->get_campaigns_current_voted();
		}	
	}

	public function get_intentions_to_confirm() {
		return $this->list_intentions_to_confirm;
	}

	/******************************************************************************/
	// TRANSFER TO BANK ACCOUNT
	/******************************************************************************/
	public function get_wallet_to_bankaccount_result() {
		return $this->wallet_to_bankaccount_result;
	}

	/******************************************************************************/
	// CHANGE WIRE AMOUNT
	/******************************************************************************/
	public function get_change_wire_amount_result() {
		return $this->change_wire_amount_result;
	}

	/******************************************************************************/
	// CONTEXTE
	/******************************************************************************/
	public function get_form_css_classes() {
		return 'db-form form-register v3 full bg-white';
	}
}