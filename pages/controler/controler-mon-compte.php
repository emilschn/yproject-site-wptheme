<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_User_Account() );

class WDG_Page_Controler_User_Account extends WDG_Page_Controler {
	
	/**
	 * @var WDGUser 
	 */
	private $current_user;
	private $current_user_organizations;
	private $current_user_authentication;
	private $current_user_authentication_info;
	private $user_id;
	private $user_name;
	private $user_project_list;
	private $user_data;
	private $wallet_to_bankaccount_result;
	private $form_user_details;
	private $form_user_password;
	private $form_user_identitydocs;
	private $form_user_bank;
	private $form_user_notifications;
	private $form_user_feedback;
	private $form_user_tax_exemption;
	private $list_intentions_to_confirm;
	private $tax_documents;
	
	public function __construct() {
		parent::__construct();
		define( 'SKIP_BASIC_HTML', TRUE );
		
		if ( !is_user_logged_in() ) {
			wp_redirect( home_url( '/connexion/' ) . '?redirect-page=mon-compte' );
		}
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'user-password' );
		$core->include_form( 'user-unlink-facebook' );
		$core->include_form( 'user-identitydocs' );
		$core->include_form( 'user-bank' );
		$core->include_form( 'user-notifications' );
		
		// Si on met à jour le RIB, il faut recharger l'utilisateur en cours
		$reload = WDGFormUsers::register_rib();
		$this->wallet_to_bankaccount_result = WDGFormUsers::wallet_to_bankaccount();
		$this->init_current_user( $reload );
		$this->init_project_list();
		$this->init_intentions_to_confirm();
		$this->init_form_user_details();
		$this->init_form_user_identitydocs();
		$this->init_form_user_bank();
		$this->init_form_user_notifications();
		$this->init_form_user_tax_exemption();
		$this->init_tax_documents();
		
		wp_enqueue_style( 'dashboard-investor-css', dirname( get_bloginfo( 'stylesheet_url' ) ).'/_inc/css/dashboard-investor.css', null, ASSETS_VERSION, 'all');
		wp_enqueue_script( 'wdg-user-account', dirname( get_bloginfo('stylesheet_url')).'/_inc/js/wdg-user-account.js', array('jquery', 'jquery-ui-dialog'), ASSETS_VERSION);
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
	
	private function init_current_user( $reload ) {
		$WDGUser_current = WDGUser::current();
		if ( $reload ) {
			$WDGUser_current->construct_with_api_data();
		}
		$this->current_user = $WDGUser_current;
		if ( $WDGUser_current->is_admin() ) {
			$input_user_id = filter_input( INPUT_GET, 'override_current_user' );
			if ( !empty( $input_user_id ) ) {
				$this->current_user = new WDGUser( $input_user_id );
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
		} else {
			$this->current_user_authentication_info = ''; //
		}
		
		// Vérifications pour niveau 3 : le RIB est validé
		if ( $this->current_user_authentication == 2 && $this->current_user->is_lemonway_registered() ) {
			$this->current_user_authentication = 3;
		} else {
			$this->current_user_authentication_info = ''; //
		}
	}
	
	public function init_show_user_needs_authentication() {
		$this->show_user_needs_authentication = false;
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
	
	public function get_user_data( $data_key ) {
		$buffer = '';
		if ( !empty( $data_key ) ) {
			if ( empty( $this->user_data[ $data_key ] ) ) {
				if ( isset( $this->current_user->wp_user->{ 'user_' . $data_key } ) ) {
					$this->user_data[ $data_key ] = $this->current_user->wp_user->{ 'user_' . $data_key };
				} else if ( isset( $this->current_user->wp_user->{ $data_key } ) ) {
					$this->user_data[ $data_key ] = $this->current_user->wp_user->{ $data_key };
				} else {
					$this->user_data[ $data_key ] = $this->current_user->wp_user->get( 'user_' . $data_key );
					if ( empty( $this->user_data[ $data_key ] ) ) {
						$this->user_data[ $data_key ] = $this->current_user->wp_user->get( $data_key );
					}
				}
				
			}
			$buffer = $this->user_data[ $data_key ];
		}
		return $buffer;
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
	}
	
	public function get_user_identitydocs_form() {
		return $this->form_user_identitydocs;
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
		$tax_exemption_filename = get_user_meta( $this->current_user->get_wpref(), 'tax_exemption_' .$date_today->format( 'Y' ), TRUE );
		
		return ( empty( $tax_exemption_filename ) && $this->get_can_ask_tax_exemption() );
	}
	
	public function get_tax_exemption_preview() {
		$core = ATCF_CrowdFunding::instance();
		$core->include_control( 'templates/pdf/form-tax-exemption' );
		$user_name = $this->current_user->get_firstname(). ' ' .$this->current_user->get_lastname();
		$user_address = $this->current_user->get_full_address_str(). ' ' .$this->current_user->get_postal_code( TRUE ). ' ' .$this->current_user->get_city();
		$form_ip_address = $_SERVER[ 'REMOTE_ADDR' ];
		$date_today = new DateTime();
		$form_date = $date_today->format( 'd/m/Y' );
		return WDG_Template_PDF_Form_Tax_Exemption::get( $user_name, $user_address, $form_ip_address, $form_date );
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
	
	public function has_tax_documents( $orga_id = FALSE ) {
		if ( empty( $orga_id ) ) {
			$orga_id = 'user';
		}
		return !empty( $this->tax_documents[ $orga_id ] );
	}
	
	public function get_tax_documents( $orga_id = FALSE ) {
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
				$WDGOrganization = new WDGOrganization( $campaign_organization->wpref );
				$project = array(
					'link'	=> home_url( '/tableau-de-bord/' ) . '?campaign_id=' . get_the_ID(),
					'name'	=> get_the_title(),
					'authentified'	=> $WDGOrganization->is_registered_lemonway_wallet()
				);
				array_push( $this->user_project_list, $project );
			}
		}
		wp_reset_query();
		
		$api_user_id = $this->current_user->get_api_id();
		$project_list = WDGWPREST_Entity_User::get_projects_by_role( $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
		if ( !empty( $project_list ) ) {
			foreach ($project_list as $project) {
				$campaign = new ATCF_Campaign( $project->wpref );
				$campaign_organization = $campaign->get_organization();
				$WDGOrganization = new WDGOrganization( $campaign_organization->wpref );
				$project = array(
					'link'	=> home_url( '/tableau-de-bord/' ) . '?campaign_id=' . $project->wpref,
					'name'	=> $project->name,
					'authentified'	=> $WDGOrganization->is_registered_lemonway_wallet()
				);
				array_push( $this->user_project_list, $project );
			}
		}
	}
	
/******************************************************************************/
// INTENTIONS D'INVESTISSEMENT SANS INVESTISSEMENT
/******************************************************************************/
	private function init_intentions_to_confirm() {
		$this->list_intentions_to_confirm = array();
		
		if ( $this->current_user->is_lemonway_registered() ) {
			
			$list_campaign_funding = ATCF_Campaign::get_list_funding( 0, '', true );
			foreach ( $list_campaign_funding as $project_post ) {
				$amount_voted = $this->current_user->get_amount_voted_on_campaign( $project_post->ID );
				if ( $amount_voted > 0 && !$this->current_user->has_invested_on_campaign( $project_post->ID ) ) {
					$intention_item = array(
						'campaign_name'	=> $project_post->post_title,
						'campaign_id'	=> $project_post->ID,
						'vote_amount'	=> $amount_voted,
						'status'		=> ATCF_Campaign::$campaign_status_collecte
					);
					array_push( $this->list_intentions_to_confirm, $intention_item );
				}
			}

			$list_campaign_vote = ATCF_Campaign::get_list_vote( 0, '', true );
			foreach ( $list_campaign_vote as $project_post ) {
				$amount_voted = $this->current_user->get_amount_voted_on_campaign( $project_post->ID );
				if ( $amount_voted > 0 && !$this->current_user->has_invested_on_campaign( $project_post->ID ) ) {
					$intention_item = array(
						'campaign_name'	=> $project_post->post_title,
						'campaign_id'	=> $project_post->ID,
						'vote_amount'	=> $amount_voted,
						'status'		=> ATCF_Campaign::$campaign_status_vote
					);
					array_push( $this->list_intentions_to_confirm, $intention_item );
				}
			}
			
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
	
}