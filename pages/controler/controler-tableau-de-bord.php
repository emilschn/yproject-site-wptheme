<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Project_Dashboard() );

class WDG_Page_Controler_Project_Dashboard extends WDG_Page_Controler {
	
	private $campaign_id;
	private $campaign_url;
	private $campaign_stats;
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
		wp_enqueue_script( 'campaign-dashboard-chart-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-chart.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_script( 'campaign-dashboard-d3-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-d3.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_script( 'campaign-dashboard-graphs-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-graphs.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_style( 'campaign-dashboard-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/campaign-dashboard.min.css', null, ASSETS_VERSION, 'all' );
		wp_enqueue_style( 'campaign-dashboard-stats-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/campaign-dashboard-stats.min.css', null, ASSETS_VERSION, 'all' );
		
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
		$this->init_stats();
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
			$this->campaign_organization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
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
	
/******************************************************************************/
// PREPARATION DES DONNEES DE STATS
/******************************************************************************/
	private function init_stats() {
		$this->campaign_stats = array();
		
		$this->campaign_stats[ 'name' ] = $this->campaign->get_name();
		$this->campaign_stats[ 'url' ] = '/' .$this->campaign->get_url();
		$this->campaign_stats[ 'goal' ] = $this->campaign->minimum_goal();
		$this->campaign_stats[ 'average_median_for_campaign' ] = 40000;
		
		
		// ***************
		// Stats des votes
		$vote_results = WDGCampaignVotes::get_results( $this->campaign_id );
		$this->campaign_stats[ 'vote' ] = array();
		$date_begin = new DateTime( $this->campaign->get_begin_vote_str() );
		$this->campaign_stats[ 'vote' ][ 'start' ] = $date_begin->format( 'Y-m-d\Th:i' );
		$date_end = new DateTime( $this->campaign->get_end_vote_str() );
		$this->campaign_stats[ 'vote' ][ 'end' ] = $date_end->format( 'Y-m-d\Th:i' );
		
		// Objectifs
		$this->campaign_stats[ 'vote' ][ 'nb' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'current' ] = max( 0, $vote_results[ 'count_voters' ] );
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'min' ] = 50;
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'average' ] = 60; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'median' ] = 55; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'current' ] = max( 0, $vote_results[ 'count_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'min' ] = 25;
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'average' ] = 35; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'median' ] = 30; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ] = array();
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'current' ] = max( 0, $vote_results[ 'sum_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'min' ] = 40000; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'average' ] = 55000; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'median' ] = 50000; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'current' ] = max( 0, $vote_results[ 'count_preinvestments' ] );
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'min' ] = 15; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'average' ] = 25; // TODO
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'median' ] = 20; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ] = array();
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'current' ] = max( 0, $vote_results[ 'amount_preinvestments' ] );
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'min' ] = 10000; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'average' ] = 25000; // TODO
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'median' ] = 20000; // TODO
		$this->campaign_stats[ 'vote' ][ 'average_intent' ] = max( 0, $vote_results[ 'average_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'percent_intent' ] = max( 0, $vote_results[ 'count_invest_ready' ] ) / $vote_results[ 'count_voters' ] * 100;
		
		// Liste
		$this->campaign_stats[ 'vote' ][ 'list_vote' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'current' ] = array();
		foreach ( $vote_results[ 'list_sum_by_date' ] as $vote_result ) {
			$vote_item = array(
				'date' => $vote_result[ 'date' ],
				'sum' => max( 0, $vote_result[ 'sum' ] )
			);
			array_push( $this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'current' ], $vote_item );
		}
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ] = array(); // TODO
		
		// Préinvestissements
		$this->campaign_stats[ 'vote' ][ 'list_preinvestement' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_preinvestement' ][ 'current' ] = array();
		foreach ( $vote_results[ 'list_preinvestments' ] as $preinvestment ) {
			$preinvestment_item = array(
				'date' => $preinvestment[ 'date' ],
				'sum' => $preinvestment[ 'sum' ]
			);
			array_push( $this->campaign_stats[ 'vote' ][ 'list_preinvestement' ][ 'current' ], $preinvestment_item );
		}
		$this->campaign_stats[ 'vote' ][ 'list_preinvestement' ][ 'target' ] = array(); // TODO
		
		// Notes
		$this->campaign_stats[ 'vote' ][ 'rates' ] = array();
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'economy' ] = $vote_results[ 'average_impact_economy' ];
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'environment' ] = $vote_results[ 'average_impact_environment' ];
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'social' ] = $vote_results[ 'average_impact_social' ];
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'others' ] = $vote_results[ 'list_impact_others_string' ];
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ] = array();
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '1' ] = max( 0, $vote_results[ 'rate_project_list' ][ 1 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '2' ] = max( 0, $vote_results[ 'rate_project_list' ][ 2 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '3' ] = max( 0, $vote_results[ 'rate_project_list' ][ 3 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '4' ] = max( 0, $vote_results[ 'rate_project_list' ][ 4 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '5' ] = max( 0, $vote_results[ 'rate_project_list' ][ 5 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ 'average' ] = max( 0, $vote_results[ 'rate_project_average' ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ 'positive_percent' ] = max( 0, $vote_results[ 'percent_project_not_validated' ] );
		
		// Risque
		$this->campaign_stats[ 'vote' ][ 'risk' ] = array();
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '1' ] = max( 0, $vote_results[ 'risk_list' ][ 1 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '2' ] = max( 0, $vote_results[ 'risk_list' ][ 2 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '3' ] = max( 0, $vote_results[ 'risk_list' ][ 3 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '4' ] = max( 0, $vote_results[ 'risk_list' ][ 4 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '5' ] = max( 0, $vote_results[ 'risk_list' ][ 5 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ 'average' ] = max( 0, $vote_results[ 'average_risk' ] );
		
		// Plus d'infos
		$this->campaign_stats[ 'vote' ][ 'more_info' ] = array();
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'impact' ] = max( 0, $vote_results[ 'count_more_info_impact' ] );
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'product' ] = max( 0, $vote_results[ 'count_more_info_service' ] );
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'team' ] = max( 0, $vote_results[ 'count_more_info_team' ] );
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'finance' ] = max( 0, $vote_results[ 'count_more_info_finance' ] );
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'other' ] = max( 0, $vote_results[ 'count_more_info_other' ] );
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'others' ] = array();
		foreach ( $vote_results[ 'list_more_info_other' ] as $more_info_other_item ) {
			$userdata = get_userdata( $more_info_other_item[ 'user_id' ] );
			$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'others' ][ $userdata->user_email ] = $more_info_other_item[ 'text' ];
		}
		
		
		// ***************
		// Stats des investissements
		$investment_results = WDGCampaignInvestments::get_list( $this->campaign_id );
		$this->campaign_stats[ 'funding' ] = array();
		$this->campaign_stats[ 'funding' ][ 'start' ] = $this->campaign->begin_collecte_date( 'Y-m-d\Th:i' );
		$this->campaign_stats[ 'funding' ][ 'end' ] = $this->campaign->end_date( 'Y-m-d\Th:i' );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ] = array();
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'current' ] = max( 0, $investment_results[ 'count_validate_investments' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'current_different' ] = max( 0, $investment_results[ 'count_validate_investors' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'not_validated' ] = max( 0, $investment_results[ 'count_not_validate_investments' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'average' ] = 75;
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'median' ] = 70;
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ] = array();
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'current' ] = $this->campaign->current_amount( FALSE );
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'average' ] = 55000;
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'median' ] = 50000;
		
		// Liste
		$this->campaign_stats[ 'funding' ][ 'list_investement' ] = array();
		$this->campaign_stats[ 'funding' ][ 'list_investement' ][ 'current' ] = array();
		foreach ( $investment_results[ 'payments_data' ] as $investment_result ) {
			$investment_date = new DateTime( $investment_result[ 'date' ] );
			$investment_item = array(
				'date' => $investment_date->format( 'Y-m-d\Th:i' ),
				'sum' => max( 0, $investment_result[ 'amount' ] )
			);
			array_push( $this->campaign_stats[ 'funding' ][ 'list_investement' ][ 'current' ], $investment_item );
		}
		$this->campaign_stats[ 'funding' ][ 'list_investement' ][ 'target' ] = array();
		
		// Stats
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'age' ] = max( 0, $investment_results[ 'average_age' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'percent_men' ] = max( 0, $investment_results[ 'percent_male' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'percent_women' ] = max( 0, $investment_results[ 'percent_female' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_average' ] = max( 0, $investment_results[ 'average_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_min' ] = max( 0, $investment_results[ 'min_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_median' ] = max( 0, $investment_results[ 'median_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_max' ] = max( 0, $investment_results[ 'max_invest' ] );
		
		
		setcookie( 'campaign_url', json_encode( $this->campaign_stats[ 'url' ] ) );
		setcookie( 'vote', json_encode( $this->campaign_stats[ 'vote' ] ) );
		setcookie( 'funding', json_encode( $this->campaign_stats[ 'funding' ] ) );
	}
	
	public function get_campaign_stats() {
		return $this->campaign_stats;
	}
}