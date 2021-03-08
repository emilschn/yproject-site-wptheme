<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Launch_Project() );

class WDG_Page_Controler_Launch_Project extends WDG_Page_Controler {
	private $draft_project_id_user;
	private $draft_project_email;
	private $draft_project_metadata;
	private $user_init;
	private $user_projects = array();
	private $user_organisations = array();

	public function __construct() {
		parent::__construct();
		// Si données en paramètres, on utilise les données de l'interface prospect
		$this->prepare_draft_project();
		// En fonction de ça, on initialise l'utilisateur
		$this->prepare_user_init();
		// Et on récupère les projets et organisations existantes associés à cet utilisateur
		$this->prepare_user_projects( $this->user_init );
		$this->prepare_user_organisations( $this->user_init );
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
			if ( $project_draft_data->authorization == 'can-create-db' ) {
				$this->draft_project_id_user = $project_draft_data->id_user;
				$this->draft_project_email = $project_draft_data->email; // c'est l'email de l'organisation qu'on veut, si elle existe
				$this->draft_project_metadata = json_decode( $project_draft_data->metadata);
			}
		}
	}

	private function get_draft_project_metadata() {
		return $this->draft_project_metadata;
	}

	/******************************************************************************/
	// Infos utilisateurs en cours
	/******************************************************************************/
	private function prepare_user_init() {
		if ( !empty( $this->draft_project_id_user ) ) {
			$this->user_init = WDGUser::get_by_api_id( $this->draft_project_id_user );
		} elseif ( !empty( $this->draft_project_email ) ) {
			$wpuser_temp = get_user_by( 'email', $this->draft_project_email );
			$this->user_init = new WDGUser( $wpuser_temp->ID );
		} else {
			$this->user_init = WDGUser::current();
		}
	}

	private function get_user_init() {
		return $this->user_init;
	}

	public function get_user_firstname() {
		$user_init = $this->get_user_init();
		if ( !empty( $user_init ) ) {
			return $user_init->get_firstname();
		} else {
			$draft_metadata = $this->get_draft_project_metadata();
			if ( !empty( $draft_metadata->user ) && !empty( $draft_metadata->user->name ) ) {
				$draft_user_name = $draft_metadata->user->name;
				$draft_user_name_exploded = explode( ' ', $draft_user_name );
				if ( count( $draft_user_name_exploded ) > 1 ) {
					return $draft_user_name_exploded[ 0 ];
				}
			}
		}

		return '';
	}

	public function get_user_lastname() {
		$user_init = $this->get_user_init();
		if ( !empty( $user_init ) ) {
			return $user_init->get_lastname();
		} else {
			$draft_metadata = $this->get_draft_project_metadata();
			if ( !empty( $draft_metadata->user ) && !empty( $draft_metadata->user->name ) ) {
				$draft_user_name = $draft_metadata->user->name;
				$draft_user_name_exploded = explode( ' ', $draft_user_name );
				if ( count( $draft_user_name_exploded ) > 1 ) {
					return $draft_user_name_exploded[ 1 ];
				}
			}
		}

		return '';
	}

	public function get_user_phone() {
		$user_init = $this->get_user_init();
		if ( !empty( $user_init ) ) {
			return $user_init->get_phone_number();
		} else {
			$draft_metadata = $this->get_draft_project_metadata();
			if ( !empty( $draft_metadata->user ) && !empty( $draft_metadata->user->phone ) ) {
				return $draft_metadata->user->phone;
			}
		}

		return '';
	}

	public function get_organization_name() {
		$draft_metadata = $this->get_draft_project_metadata();
		if ( !empty( $draft_metadata->organization ) && !empty( $draft_metadata->organization->name ) ) {
			return $draft_metadata->organization->name;
		}

		return '';
	}

	public function get_organization_email() {
		$draft_metadata = $this->get_draft_project_metadata();
		if ( !empty( $draft_metadata->organization ) && !empty( $draft_metadata->organization->email ) ) {
			return $draft_metadata->organization->email;
		}

		return '';
	}

	/******************************************************************************/
	// Projets de l'utilisateur
	/******************************************************************************/
	private function prepare_user_projects($user) {
		global $WDG_cache_plugin;
		if ( $WDG_cache_plugin == null ) {
			$WDG_cache_plugin = new WDG_Cache_Plugin();
		}
		$cache_project_list = $WDG_cache_plugin->get_cache( 'WDGUser::get_projects_by_id(' .$this->user_init->get_wpref(). ', TRUE)', 1 );
		if ( $cache_project_list !== FALSE ) {
			$project_list = json_decode( $cache_project_list );
		} else {
			$project_list = WDGUser::get_projects_by_id( $this->user_init->get_wpref(), TRUE );
			$WDG_cache_plugin->set_cache( 'WDGUser::get_projects_by_id(' .$user->get_wpref(). ', TRUE)', json_encode( $project_list ), 60*10, 1 ); //MAJ 10min
		}

		if ( !empty( $project_list ) ) {
			$existingprojects = array();
			$existingprojects["projects"] = array();
			$page_dashboard = WDG_Redirect_Engine::override_get_page_url( 'tableau-de-bord' );
			$project_string = '';
			foreach ( $project_list as $project_id ) {
				if ( !empty( $project_id ) ) {
					$project_campaign = new ATCF_Campaign( $project_id );
					if ( isset( $project_campaign ) && $project_campaign->get_name() != '' ) {
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
		$organizations_list = $this->user_init->get_organizations_list();
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
}