<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_User_Account() );

class WDG_Page_Controler_User_Account extends WDG_Page_Controler {
	
	private $user_id;
	private $user_name;
	private $user_project_list;
	private $wallet_to_bankaccount_result;
	
	public function __construct() {
		parent::__construct();
		define( 'SKIP_BASIC_HTML', TRUE );
		if (!is_user_logged_in()) {
			wp_redirect( home_url( '/connexion' ) . '?redirect-page=mon-compte' );
		}
		WDGFormUsers::register_rib();
		$this->wallet_to_bankaccount_result = WDGFormUsers::wallet_to_bankaccount();
		$this->init_user_id();
		$this->init_user_name();
		$this->init_project_list();
	}
	
/******************************************************************************/
// USER ID
/******************************************************************************/
	public function get_user_id() {
		return $this->user_id;
	}
	private function init_user_id() {
		$WDGUser_current = WDGUser::current();
		$this->user_id = $WDGUser_current->get_wpref();
	}
	
/******************************************************************************/
// USER NAME
/******************************************************************************/
	public function get_user_name() {
		return $this->user_name;
	}
	private function init_user_name() {
		$WDGUser_current = WDGUser::current();
		$first_name = $WDGUser_current->get_firstname();
		if ( !empty( $first_name ) ) {
			$this->user_name = $first_name;
		
		} else {
			
			$display_name = $WDGUser_current->wp_user->display_name;
			if ( !empty( $display_name ) ) {
				$this->user_name = $display_name;
				
			} else {
				$this->user_name = $WDGUser_current->wp_user->user_login;
				
			}
			
		}
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
		
		$WDGUser_current = WDGUser::current();
		$args = array(
			'post_type'		=> 'download',
			'author'		=> $WDGUser_current->wp_user->ID,
			'post_status'	=> 'publish'
		);
		query_posts($args);
		
		if (have_posts()) {
			while (have_posts()) {
				the_post();
				$project = array(
					'link'	=> home_url( '/tableau-de-bord' ) . '?campaign_id=' . get_the_ID(),
					'name'	=> get_the_title()
				);
				array_push( $this->user_project_list, $project );
			}
		}
		
		$api_user_id = $WDGUser_current->get_api_id();
		$project_list = WDGWPREST_Entity_User::get_projects_by_role( $api_user_id, WDGWPREST_Entity_Project::$link_user_type_team );
		if ( !empty( $project_list ) ) {
			foreach ($project_list as $project) {
				$project = array(
					'link'	=> home_url( '/tableau-de-bord' ) . '?campaign_id=' . $project->wpref,
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