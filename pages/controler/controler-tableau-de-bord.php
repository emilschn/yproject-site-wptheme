<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Project_Dashboard() );

class WDG_Page_Controler_Project_Dashboard extends WDG_Page_Controler {
	
	private $campaign_id;
	private $can_access;
	private $can_access_admin;
	private $must_show_lightbox_welcome;
	/**
	 * @var ATCF_Campaign
	 */
	private $campaign;
	/**
	 * @var WDGUser
	 */
	private $current_user;
	/**
	 * @var WDGUser
	 */
	private $author_user;
	/**
	 * @var WDGOrganization
	 */
	private $campaign_organization;
	
	public function __construct() {
		parent::__construct();
		if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
			date_default_timezone_set("Europe/London");
			define( 'SKIP_BASIC_HTML', TRUE );
		}
		
		locate_template( array( 'projects/dashboard/dashboardutility.php' ), true );
		wp_enqueue_script( 'campaign-dashboard-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_style( 'campaign-dashboard-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/campaign-dashboard.min.css', null, ASSETS_VERSION, 'all' );
		
		$this->init_campaign_data();
		$this->init_context();
	}
	
/******************************************************************************/
// SECURISATION
/******************************************************************************/
	public function can_access() {
		return $this->can_access;
	}
	
	public function can_access_admin() {
		return $this->can_access_admin;
	}
	
/******************************************************************************/
// DONNEES DE LA CAMPAGNE
/******************************************************************************/
	private function init_campaign_data() {
		$this->can_access = FALSE;
		$this->can_access_admin = FALSE;
		$this->campaign_id = filter_input(INPUT_GET, 'campaign_id');
		
		if ( !empty( $this->campaign_id ) && is_user_logged_in() ) {
			$this->current_user = WDGUser::current();
			$this->campaign = new ATCF_Campaign( $this->campaign_id );
			$this->can_access = $this->campaign->current_user_can_edit();
			$this->can_access_admin = $this->current_user->is_admin();
			$this->author_user = new WDGUser( $this->campaign->post_author() );
			$campaign_organization_item = $this->campaign->get_organization();
			$this->campaign_organization = new WDGOrganization( $campaign_organization_item->wpref );
		}
	}
	public function get_campaign_id() {
		return $this->campaign_id;
	}
	public function get_campaign() {
		return $this->campaign;
	}
	public function get_campaign_name() {
		return $this->campaign->data->post_title;
	}
	public function get_campaign_status() {
		return $this->campaign->campaign_status();
	}
	public function get_campaign_author() {
		return $this->author_user;
	}
	public function get_campaign_organization() {
		return $this->campaign_organization;
	}
	
/******************************************************************************/
// CONTEXTE
/******************************************************************************/
	private function init_context() {
		$this->must_show_lightbox_welcome = FALSE;
		$hidenewprojectlightbox = filter_input( INPUT_COOKIE, 'hidenewprojectlightbox' );
		$input_lightbox = filter_input( INPUT_GET, 'lightbox' );
		if ( !empty( $input_lightbox ) ) {
			$this->must_show_lightbox_welcome = ( $input_lightbox == 'newproject' );
		}
	}
	public function get_show_lightbox_welcome() {
		return $this->must_show_lightbox_welcome;
	}
	
}