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
	private $form_user_feedback;
	
	public function __construct() {
		parent::__construct();
		define( 'SKIP_BASIC_HTML', TRUE );
		
		if ( !is_user_logged_in() ) {
			wp_redirect( home_url( '/connexion/' ) . '?redirect-page=mon-compte' );
		}
		
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'user-password' );
		$core->include_form( 'user-identitydocs' );
		$core->include_form( 'user-bank' );
		
		// Si on met à jour le RIB, il faut recharger l'utilisateur en cours
		$reload = WDGFormUsers::register_rib();
		$this->wallet_to_bankaccount_result = WDGFormUsers::wallet_to_bankaccount();
		$this->init_current_user( $reload );
		$this->init_project_list();
		$this->init_form_user_details();
		$this->init_form_user_identitydocs();
		$this->init_form_user_bank();
		locate_template( array( 'country_list.php'  ), true );
		
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
		
		if ( !$this->current_user->is_logged_in_with_facebook() ) {
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
				$project = array(
					'link'	=> home_url( '/tableau-de-bord/' ) . '?campaign_id=' . get_the_ID(),
					'name'	=> get_the_title()
				);
				array_push( $this->user_project_list, $project );
			}
		}
		
		$api_user_id = $this->current_user->get_api_id();
		$project_list = WDGWPREST_Entity_User::get_projects_by_role( $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
		if ( !empty( $project_list ) ) {
			foreach ($project_list as $project) {
				$project = array(
					'link'	=> home_url( '/tableau-de-bord/' ) . '?campaign_id=' . $project->wpref,
					'name'	=> $project->name
				);
				array_push( $this->user_project_list, $project );
			}
		}
	}
	
/******************************************************************************/
// TRANSFER TO BANK ACCOUNT
/******************************************************************************/
	public function get_wallet_to_bankaccount_result() {
		return $this->wallet_to_bankaccount_result;
	}
	
}