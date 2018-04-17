<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Project_Dashboard() );

class WDG_Page_Controler_Project_Dashboard extends WDG_Page_Controler {
	
	private $campaign_id;
	private $campaign_url;
	private $can_access;
	private $can_access_admin;
	private $can_access_author;
	private $must_show_lightbox_welcome;
	private $return_lemonway_card;
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
		
		wp_enqueue_script( 'datatable-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/jquery.dataTables.min.js', array( 'jquery', 'wdg-script' ), true, true );
		wp_enqueue_style( 'datatable-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/jquery.dataTables.min.css', null, false, 'all' );

		wp_enqueue_script( 'datatable-colreorder-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.colReorder.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_style( 'datatable-colreorder-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/colReorder.dataTables.min.css', null, false, 'all' );

		wp_enqueue_script( 'datatable-select-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.select.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_style('datatable-select-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/select.dataTables.min.css', null, false, 'all' );

		wp_enqueue_script( 'datatable-buttons-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.buttons.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_script( 'datatable-buttons-colvis-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.colVis.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_script( 'datatable-buttons-html5-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.html5.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_script( 'datatable-buttons-print-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.print.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_script( 'datatable-jszip-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/jszip.min.js', array( 'datatable-script' ), true, true );
		wp_enqueue_style( 'datatable-buttons-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/buttons.dataTables.min.css', null, false, 'all' );
		
		$this->init_campaign_data();
		if ( !$this->can_access ) {
			wp_redirect( home_url() );
			exit();
		}
		$this->check_has_signed_mandate();
		$this->init_context();
		WDGFormProjects::form_submit_turnover();
		WDGFormProjects::form_submit_account_files();
		WDGFormProjects::form_submit_roi_payment();
		WDGFormProjects::form_approve_payment();
		WDGFormProjects::form_cancel_payment();
		$current_organization = $this->get_campaign_organization();
		$current_organization->send_kyc();
		$current_organization->submit_transfer_wallet_lemonway();
		$this->return_lemonway_card = WDGFormProjects::return_lemonway_card();
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
	
	public function can_access_author() {
		return $this->can_access_author;
	}
	
/******************************************************************************/
// DONNEES DE LA CAMPAGNE
/******************************************************************************/
	private function init_campaign_data() {
		$this->can_access = FALSE;
		$this->can_access_admin = FALSE;
		$this->can_access_author = FALSE;
		$this->campaign_id = filter_input(INPUT_GET, 'campaign_id');
		
		if ( !empty( $this->campaign_id ) && is_user_logged_in() ) {
			$this->current_user = WDGUser::current();
			$this->campaign = new ATCF_Campaign( $this->campaign_id );
			$this->author_user = new WDGUser( $this->campaign->data->post_author );
			$this->campaign_url = get_permalink( $this->campaign_id );
			$this->can_access = $this->campaign->current_user_can_edit();
			$this->can_access_admin = $this->current_user->is_admin();
			$this->can_access_author = ( $this->author_user->get_wpref() == $this->current_user->get_wpref() );
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
	public function get_campaign_url() {
		return $this->campaign_url;
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
	
	public function get_return_lemonway_card() {
		return $this->return_lemonway_card;
	}
	
/******************************************************************************/
// CONTROLE FORMULAIRES
/******************************************************************************/
	public function check_has_signed_mandate() {
		$input_has_signed_mandate = filter_input( INPUT_GET, 'has_signed_mandate' );
		if ( !empty( $input_has_signed_mandate ) ) {
			NotificationsEmails::campaign_sign_mandate_admin( $this->campaign_organization->get_wpref() );
			wp_redirect( home_url( 'tableau-de-bord' ) . '?campaign_id=' . $this->get_campaign_id() . '#contracts' );
			exit();
		}
	}
}