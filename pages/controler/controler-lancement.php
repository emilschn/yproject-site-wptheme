<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Launch_Project() ); 

class WDG_Page_Controler_Launch_Project extends WDG_Page_Controler {
	
	private $user_projects = array();
	private $user_organisations = array();
	private $draft_project_id_user;
	private $draft_project_email;
	private $draft_project_status;
	private $draft_project_step;
	private $draft_project_authorization;
	private $draft_project_metadata;

	public function __construct() {
		parent::__construct();    
		$user = WDGUser::current();
		$this->prepare_user_projects($user);
		$this->prepare_user_organisations($user);
		// quand l'interface prospect et la gestion de brouillon de projet sera effective
		// $this->prepare_draft_project();
	}
	/******************************************************************************/
	// Projets de l'utilisateur
	/******************************************************************************/
	private function prepare_user_projects($user) {
		global $WDG_cache_plugin;
		if ( $WDG_cache_plugin == null ) {
		  $WDG_cache_plugin = new WDG_Cache_Plugin();
		}
		$cache_project_list = $WDG_cache_plugin->get_cache( 'WDGUser::get_projects_by_id(' .$user->get_wpref(). ', TRUE)', 1 );
		if ( $cache_project_list !== FALSE ) {
		  $project_list = json_decode( $cache_project_list );      
		} else {
		  $project_list = WDGUser::get_projects_by_id( $user->get_wpref(), TRUE );
		  $WDG_cache_plugin->set_cache( 'WDGUser::get_projects_by_id(' .$user->get_wpref(). ', TRUE)', json_encode( $project_list ), 60*10, 1 ); //MAJ 10min
		}
		
		if ( !empty( $project_list ) ){
		  $existingprojects = array();
		  $existingprojects["projects"] = array();
		  $page_dashboard = home_url( '/tableau-de-bord/' );
		  $project_string = '';
		  foreach ( $project_list as $project_id ) {
			if ( !empty( $project_id ) ){
			  $project_campaign = new ATCF_Campaign( $project_id );
			  if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ){
				$campaign_dashboard_url = $page_dashboard. '?campaign_id=' .$project_id;
				$project = array('name' => $project_campaign->get_name() , 'url' => $campaign_dashboard_url );
				$existingprojects["projects"][] = $project;
			  }
			}
		  }
		  $this->user_projects = json_encode($existingprojects, JSON_HEX_APOS );
		}
	}

	public function get_user_projects() {
		return $this->user_projects;
	}
	/******************************************************************************/
	// Organisations de l'utilisateur
	/******************************************************************************/
	private function prepare_user_organisations($user) {
		$organizations_list = $user->get_organizations_list();    
		$this->user_organisations = array();
		$this->user_organisations["organisations"] = array();
		if ($organizations_list) {
			foreach ($organizations_list as $organization_item) {
				$WDGOrganization = new WDGOrganization( $organization_item->wpref );
				$orga = array('Id' => $organization_item->wpref , 'Text' => $WDGOrganization->get_name(), 'Mail' => $WDGOrganization->get_email() );
				$this->user_organisations["organisations"][] = $orga;
			}
			$this->user_organisations["organisations"][] = array('Id' => "new_orga" , 'Text' => "Une nouvelle organisation...", 'Mail' => 'new_email' );
			$this->user_organisations = json_encode($this->user_organisations, JSON_HEX_APOS );
		}
	}

	public function get_user_organisations() {
		return $this->user_organisations;
	}
	/******************************************************************************/
	// Brouillon de projet récupéré avec le guid
	/******************************************************************************/
	private function prepare_draft_project() {
		// on récupère le guid envoyé en GET    
		$input_guid = filter_input( INPUT_GET, 'guid' );
		if ( !empty( $input_guid ) ) {
			// grâce à ce guid, on récupère les données du brouillon de projet    
			$project_draft_data = WDGWPREST_Entity_Project_Draft::get( $input_guid );
			$this->draft_project_id_user = $project_draft_data->id_user;
			$this->draft_project_email = $project_draft_data->email; // c'est l'email de l'organisation qu'on veut, si elle existe
			$this->draft_project_status = $project_draft_data->status;
			$this->draft_project_step = $project_draft_data->step;
			$this->draft_project_authorization = $project_draft_data->authorization;
			$this->draft_project_metadata = json_decode( $project_draft_data->metadata) ;
		}
		// $user = new WDGUser( $this->draft_project_id_user);
	}

	public function get_draft_project_id_user() {
		return $this->draft_project_id_user;
	}

	public function get_draft_project_email() {
		return $this->draft_project_email;
	}

	public function get_draft_project_status() {
		return $this->draft_project_status;
	}

	public function get_draft_project_step() {
		return $this->draft_project_step;
	}

	public function get_draft_project_authorization() {
		return $this->draft_project_authorization;
	}

	public function get_draft_project_metadata() {
		return $this->draft_project_metadata;
	}
}