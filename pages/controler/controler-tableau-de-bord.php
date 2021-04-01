<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_Project_Dashboard() );

class WDG_Page_Controler_Project_Dashboard extends WDG_Page_Controler {
	private $campaign_id;
	private $campaign_url;
	private $campaign_stats;
	private $campaign_contracts_url;
	private $campaign_is_funded;
	private $can_access;
	private $can_access_admin;
	private $can_access_author;
	private $must_show_lightbox_welcome;
	private $declaration_list;
	private $user_kyc_duplicates;
	private $form_add_check;
	private $form_document;
	private $form_declaration_bill_list;
	private $form_adjustment;
	private $form_adjustment_edit_list;
	private $emails;

	private $form_user_details;
	private $form_user_feedback;
	private $form_user_identitydocs;

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
		wp_enqueue_script( 'campaign-dashboard-moment-script', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js', array( 'jquery' ) );
		wp_enqueue_script( 'campaign-dashboard-chart-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-chart.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_script( 'campaign-dashboard-d3-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-d3.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_script( 'campaign-dashboard-graphs-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/campaign-dashboard-graphs.min.js', array( 'jquery' ), ASSETS_VERSION );
		wp_enqueue_style( 'campaign-dashboard-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/campaign-dashboard.min.css', null, ASSETS_VERSION, 'all' );
		wp_enqueue_style( 'campaign-dashboard-stats-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/campaign-dashboard-stats.min.css', null, ASSETS_VERSION, 'all' );
		$this->enqueue_datatable();

		$this->init_campaign_data();

		if ( !is_user_logged_in() ) {
			wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'connexion' ) . '?redirect-page=tableau-de-bord&campaign_id='.$this->campaign_id  );
			exit();
		}

		if ( !$this->can_access ) {
			wp_redirect( home_url() );
			exit();
		}
		$this->check_has_signed_mandate();

		WDGFormProjects::form_submit_account_files();
		WDGFormProjects::form_approve_payment();
		WDGFormProjects::form_cancel_payment();

		$this->init_context();
		$this->init_stats();

		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'dashboard-add-check' );
		$this->form_add_check = new WDG_Form_Dashboard_Add_Check( $this->campaign_id );
		$core->include_form( 'organization-details' );
		$core->include_form( 'user-details' );
		$core->include_form( 'user-identitydocs' );
		$core->include_form( 'user-bank' );

		$this->init_declarations();
		$this->init_form_document();
		if ( $this->can_access_admin() ) {
			$this->init_form_adjustment();
		}

		$current_organization = $this->get_campaign_organization();
		$input_authentify_lw = filter_input( INPUT_POST, 'authentify_lw' );
		if ( !empty( $input_authentify_lw ) ) {
			$current_organization->send_kyc();
		}
		$current_organization->submit_transfer_wallet_lemonway();

		$this->controler_name = 'tableau-de-bord';
	}

	/******************************************************************************/
	// USER DATA
	/******************************************************************************/
	public function get_current_user() {
		return $this->current_user;
	}

	private function init_form_user_details() {
		$this->form_user_details = new WDG_Form_User_Details( $this->campaign->post_author(), WDG_Form_User_Details::$type_extended );

		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Details::$name ) {
			$this->form_user_feedback = $this->form_user_details->postForm();
		}
	}

	public function get_user_details_form() {
		$this->init_form_user_details();

		return $this->form_user_details;
	}

	private function init_form_user_identitydocs() {
		$this->form_user_identitydocs = new WDG_Form_User_Identity_Docs( $this->campaign->post_author() );
		$action_posted = filter_input( INPUT_POST, 'action' );
		if ( $action_posted == WDG_Form_User_Identity_Docs::$name ) {
			$this->form_user_feedback = $this->form_user_identitydocs->postForm();
		}
		$this->user_kyc_duplicates = $this->form_user_identitydocs->getDuplicates();
	}

	public function get_user_identitydocs_form() {
		$this->init_form_user_identitydocs();

		return $this->form_user_identitydocs;
	}

	public function has_kyc_duplicates() {
		return !empty( $this->user_kyc_duplicates );
	}

	public function get_kyc_duplicates() {
		return $this->user_kyc_duplicates;
	}

	public function get_user_form_feedback() {
		return $this->form_user_feedback;
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
		$this->campaign_contracts_url = FALSE;

		if ( !empty( $this->campaign_id ) && is_user_logged_in() ) {
			$this->current_user = WDGUser::current();
			$this->campaign = new ATCF_Campaign( $this->campaign_id );
			$this->campaign_is_funded = $this->campaign->is_funded();
			$this->author_user = new WDGUser( $this->campaign->data->post_author );
			$this->campaign_url = get_permalink( $this->campaign_id );
			$this->can_access = $this->campaign->current_user_can_edit();
			$this->can_access_admin = $this->current_user->is_admin();
			$this->can_access_author = ( $this->author_user->get_wpref() == $this->current_user->get_wpref() );
			$campaign_organization_item = $this->campaign->get_organization();
			$this->campaign_organization = new WDGOrganization( $campaign_organization_item->wpref, $campaign_organization_item );
			$this->emails = WDGWPREST_Entity_Project::get_emails( $this->campaign->get_api_id() );

			if ( file_exists( __DIR__ . '/../../../../plugins/appthemer-crowdfunding/files/contracts/' . $this->campaign->ID . '-' . $this->campaign->data->post_name . '.zip' ) ) {
				$this->campaign_contracts_url = site_url( 'wp-content/plugins/appthemer-crowdfunding/files/contracts/' . $this->campaign->ID . '-' . $this->campaign->data->post_name . '.zip' );
			}
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
	public function get_campaign_contracts_url() {
		return $this->campaign_contracts_url;
	}
	public function get_campaign_emails() {
		$buffer = array();
		foreach ( $this->emails as $email ) {
			$item = array(
				'recipient'		=> $email->recipient,
				'date'			=> $email->date,
				'template_id'	=> $email->template,
				'template_str'	=> NotificationsAPI::$description_str_by_template_id[ $email->template ]
			);
			array_push( $buffer, $item );
		}

		return $buffer;
	}
	public function is_campaign_funded() {
		return $this->campaign_is_funded;
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

	public function get_form_css_classes() {
		return 'db-form v3 full center bg-white';
	}

	/******************************************************************************/
	// CONTROLE FORMULAIRES
	/******************************************************************************/
	public function check_has_signed_mandate() {
		$input_has_signed_mandate = filter_input( INPUT_GET, 'has_signed_mandate' );
		if ( !empty( $input_has_signed_mandate ) ) {
			NotificationsSlack::send_new_project_mandate( $this->campaign_organization->get_wpref() );
			NotificationsAsana::send_new_project_mandate( $this->campaign_organization->get_wpref() );
			wp_redirect( WDG_Redirect_Engine::override_get_page_url( 'tableau-de-bord' ) . '?campaign_id=' . $this->get_campaign_id() . '#contracts' );
			exit();
		}
	}

	/******************************************************************************/
	// GESTION DOCUMENTS
	/******************************************************************************/
	private function init_form_document() {
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'declaration-document' );
		$this->form_document = new WDG_Form_Declaration_Document( $this->campaign_id );
	}

	public function get_form_document() {
		return $this->form_document;
	}

	public function get_form_document_action() {
		$url = admin_url( 'admin-post.php?action=add_declaration_document' );

		return $url;
	}

	public function get_form_document_feedback_message() {
		$buffer = FALSE;

		$input_add_declaration_document_success = filter_input( INPUT_GET, 'add_declaration_document_success' );
		if ( !empty( $input_add_declaration_document_success ) ) {
			if ( $input_add_declaration_document_success == '1' ) {
				$buffer = 'success';
			} else {
				$buffer = 'error';
			}
		}

		return $buffer;
	}

	public function is_iban_validated() {
		$lw_iban_status = $this->campaign_organization->get_lemonway_iban_status();

		return ( $lw_iban_status == WDGUser::$iban_status_validated );
	}

	/******************************************************************************/
	// GESTION DECLARATIONS
	/******************************************************************************/
	private function init_declarations() {
		$this->declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $this->get_campaign_id(), '' );
		// Ordre par date de déclaration
		usort( $this->declaration_list, function ($item1, $item2) {
			$item1_date = new DateTime( $item1->date_due );
			$item2_date = new DateTime( $item2->date_due );

			return ( $item1_date > $item2_date );
		} );
		$first_declaration_to_pay = null;
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'declaration-bill' );
		$this->form_declaration_bill_list = array();
		foreach ( $this->declaration_list as $declaration ) {
			if ( $declaration->get_status() == WDGROIDeclaration::$status_finished ) {
				$new_form = new WDG_Form_Declaration_Bill( $declaration->id );
				$this->form_declaration_bill_list[ $declaration->id ] = $new_form;
			} else if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration || $declaration->get_status() == WDGROIDeclaration::$status_payment ) {
				if ( $first_declaration_to_pay == null ){
					$declaration->set_is_first_declaration_to_pay();
					$first_declaration_to_pay = $declaration;
				}	
				ypcf_debug_log( 'controler-tableau-de-bord.php :: $declaration->set_is_first_declaration_to_pay() '.json_encode($declaration->is_first_declaration_to_pay()));			

			}
		}
	}

	public function get_declaration_list() {
		return $this->declaration_list;
	}

	public function get_form_declaration_bill($id_declaration) {
		$buffer = FALSE;
		if ( !empty( $this->form_declaration_bill_list[ $id_declaration ] ) ) {
			$buffer = $this->form_declaration_bill_list[ $id_declaration ];
		}

		return $buffer;
	}

	public function get_form_declaration_bill_action() {
		return admin_url( 'admin-post.php?action=save_declaration_bill' );
	}

	/******************************************************************************/
	// GESTION AJUSTEMENTS
	/******************************************************************************/
	public function get_adjustment_list() {
		return $this->campaign->get_adjustments();
	}

	private function init_form_adjustment() {
		$core = ATCF_CrowdFunding::instance();
		$core->include_form( 'adjustment' );
		$this->form_adjustment = new WDG_Form_Adjustement( $this->campaign_id );

		$this->form_adjustment_edit_list = array();
		$adjustment_list = $this->get_adjustment_list();
		foreach ( $adjustment_list as $adjustment_item ) {
			$form_adjustment_edit = new WDG_Form_Adjustement( $this->campaign_id, $adjustment_item );
			$this->form_adjustment_edit_list[ $adjustment_item->id ] = $form_adjustment_edit;
		}
	}

	public function get_form_adjustment($id_adjustment = FALSE) {
		if ( !empty( $id_adjustment ) && isset( $this->form_adjustment_edit_list[ $id_adjustment ] ) ) {
			return $this->form_adjustment_edit_list[ $id_adjustment ];
		} else {
			return $this->form_adjustment;
		}
	}

	public function get_form_adjustment_add_action() {
		$url = admin_url( 'admin-post.php?action=add_adjustment' );

		return $url;
	}

	public function get_form_adjustment_edit_action() {
		$url = admin_url( 'admin-post.php?action=edit_adjustment' );

		return $url;
	}

	public function get_form_adjustment_feedback_message() {
		$buffer = FALSE;

		$input_add_adjustment_success = filter_input( INPUT_GET, 'add_adjustement_success' );
		if ( !empty( $input_add_adjustment_success ) ) {
			if ( $input_add_adjustment_success == '1' ) {
				$buffer = 'success';
			} else {
				$buffer = 'error';
			}
		}

		return $buffer;
	}

	/******************************************************************************/
	// GESTION CHEQUES
	/******************************************************************************/
	public function get_form_add_check() {
		return $this->form_add_check;
	}

	public function can_add_check() {
		$buffer = FALSE;
		// Bouton d'ajout de chèque disponible si une des conditions suivantes :
		if (
			// - ADMIN
				$this->can_access_admin()
			// - avant la levée
				|| ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_validated )
				|| ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote )
			// - en cours de levée
				|| ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte && $this->campaign->is_remaining_time() )
			// - pas validé + dans les 14 jours qui suivent la levée de fonds
				|| ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_archive && !$this->campaign->has_retraction_passed() )
		) {
			$buffer = TRUE;
		}

		return $buffer;
	}

	/******************************************************************************/
	// PREPARATION DES DONNEES DE STATS
	/******************************************************************************/
	private function init_stats() {
		$this->campaign_stats = array();

		$this->campaign_stats[ 'name' ] = $this->campaign->get_name();
		$this->campaign_stats[ 'url' ] = '/' .$this->campaign->get_url(). '/';
		$this->campaign_stats[ 'goal' ] = $this->campaign->minimum_goal();
		$this->campaign_stats[ 'average_median_for_campaign' ] = $this->campaign_stats[ 'goal' ];

		// ***************
		// Stats des votes
		$vote_results = WDGCampaignVotes::get_results( $this->campaign_id );
		$this->campaign_stats[ 'vote' ] = array();
		$date_begin = new DateTime( $this->campaign->get_begin_vote_str() );
		$date_end = new DateTime( $this->campaign->get_end_vote_str() );
		// la date de début d'évaluation n'étant pas toujours recalculée (levée de fond privée), on s'assure qu'elle ne soit pas postérieure à la date de fin
		if ( $date_begin > $date_end ) {
			$date_begin = $date_end;
		}
		$this->campaign_stats[ 'vote' ][ 'start' ] = $date_begin->format( 'Y-m-d' );
		$this->campaign_stats[ 'vote' ][ 'end' ] = $date_end->format( 'Y-m-d' );

		// Stocks des références pour calculer les bonnes données, avec le bon ratio
		// Pour moyennes
		$reference_for_average_ratio_goal = 38750;
		$campaign_ratio_to_average = $this->campaign_stats[ 'goal' ] / $reference_for_average_ratio_goal;
		$reference_for_average_ratio_nb = 118;
		$reference_for_average_ratio_nb_intent = 107;
		$reference_for_average_ratio_amount_intent_percent = 95;
		$reference_for_average_ratio_nb_preinvestment = 29;
		$reference_for_average_ratio_amount_preinvestment_percent = 43;
		$reference_for_average_ratio_nb_investment = 122;
		$reference_for_average_ratio_amount_investment = 55398;
		// Pour médianes
		$reference_for_median_ratio_goal = 30000;
		$campaign_ratio_to_median = $this->campaign_stats[ 'goal' ] / $reference_for_median_ratio_goal;
		$reference_for_median_ratio_nb = 107;
		$reference_for_median_ratio_nb_intent = 85;
		$reference_for_median_ratio_amount_intent_percent = 120;
		$reference_for_median_ratio_nb_preinvestment = 28;
		$reference_for_median_ratio_amount_preinvestment_percent = 26;
		$reference_for_median_ratio_nb_investment = 123;
		$reference_for_median_ratio_amount_investment = 41594;

		// Objectifs
		$this->campaign_stats[ 'vote' ][ 'nb' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'current' ] = max( 0, $vote_results[ 'count_voters' ] );
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'min' ] = 100;
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'average' ] = round( $reference_for_average_ratio_nb * $campaign_ratio_to_average );
		$this->campaign_stats[ 'vote' ][ 'nb' ][ 'median' ] = round( $reference_for_median_ratio_nb * $campaign_ratio_to_median );
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'current' ] = max( 0, $vote_results[ 'count_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'min' ] = 80;
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'average' ] = round( $reference_for_average_ratio_nb_intent * $campaign_ratio_to_average );
		$this->campaign_stats[ 'vote' ][ 'nb_intent' ][ 'median' ] = round( $reference_for_median_ratio_nb_intent * $campaign_ratio_to_median );
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ] = array();
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'current' ] = max( 0, $vote_results[ 'sum_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'min' ] = $this->campaign_stats[ 'goal' ];
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'average' ] = round( $reference_for_average_ratio_amount_intent_percent * $this->campaign_stats[ 'goal' ] / 100 );
		$this->campaign_stats[ 'vote' ][ 'amount_intent' ][ 'median' ] = round( $reference_for_median_ratio_amount_intent_percent * $this->campaign_stats[ 'goal' ] / 100 );
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ] = array();
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'current' ] = max( 0, $vote_results[ 'count_preinvestments' ] );
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'min' ] = 25;
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'average' ] = round( $reference_for_average_ratio_nb_preinvestment * $campaign_ratio_to_average );
		$this->campaign_stats[ 'vote' ][ 'nb_preinvestment' ][ 'median' ] = round( $reference_for_median_ratio_nb_preinvestment * $campaign_ratio_to_median );
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ] = array();
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'current' ] = max( 0, $vote_results[ 'amount_preinvestments' ] );
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'min' ] = round( $this->campaign_stats[ 'goal' ] / 4 );
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'average' ] = round( $reference_for_average_ratio_amount_preinvestment_percent * $this->campaign_stats[ 'goal' ] / 100 );
		$this->campaign_stats[ 'vote' ][ 'amount_preinvestment' ][ 'median' ] = round( $reference_for_median_ratio_amount_preinvestment_percent * $this->campaign_stats[ 'goal' ] / 100 );
		$this->campaign_stats[ 'vote' ][ 'average_intent' ] = max( 0, $vote_results[ 'average_invest_ready' ] );
		$this->campaign_stats[ 'vote' ][ 'percent_intent' ] = 0;
		if ( $this->campaign_stats[ 'goal' ] > 0 ) {
			$this->campaign_stats[ 'vote' ][ 'percent_intent' ] = max( 0, round( $vote_results[ 'sum_invest_ready' ] / $this->campaign_stats[ 'goal' ] * 100, 2 ) );
		}

		// Liste
		$this->campaign_stats[ 'vote' ][ 'list_vote' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'current' ] = array();
		foreach ( $vote_results[ 'list_votes' ] as $vote_result ) {
			$vote_date = $vote_result->date;
			if ( $vote_date == 'NULL' || $vote_date == null || $vote_date == '0000-00-00' ) {
				$vote_date = $this->campaign->end_vote();
			}

			$vote_item = array(
				'date' => $vote_date,
				'sum' => $vote_result->invest_sum
			);
			array_push( $this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'current' ], $vote_item );
		}
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ][ $this->campaign_stats[ 'vote' ][ 'start' ] ] = round( $this->campaign_stats[ 'goal' ] * 8 / 100 ); // J0
		$date_target_vote = new DateTime( $this->campaign_stats[ 'vote' ][ 'start' ] );
		$date_target_vote->add( new DateInterval( 'P2D' ) );
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ][ $date_target_vote->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 16 / 100 ); // J2
		$date_target_vote->add( new DateInterval( 'P5D' ) );
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ][ $date_target_vote->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 49 / 100 ); // J7
		$this->campaign_stats[ 'vote' ][ 'list_vote' ][ 'target' ][ $this->campaign_stats[ 'vote' ][ 'end' ] ] = $this->campaign_stats[ 'goal' ];

		// Préinvestissements
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'current' ] = array();
		foreach ( $vote_results[ 'list_preinvestments' ] as $preinvestment ) {
			$date_preinvestment = new DateTime( $preinvestment[ 'date' ] );
			$preinvestment_item = array(
				'date' => $date_preinvestment->format( 'Y-m-d\Th:i' ),
				'sum' => $preinvestment[ 'sum' ]
			);
			if ( $date_preinvestment > $date_end ) {
				$preinvestment_item[ 'date' ] = $date_end->format( 'Y-m-d\Th:i' );
			}
			array_push( $this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'current' ], $preinvestment_item );
		}
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'target' ] = array();
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'target' ][ $this->campaign_stats[ 'vote' ][ 'start' ] ] = 0; // J0
		$date_target_preinv = new DateTime( $this->campaign_stats[ 'vote' ][ 'start' ] );
		$date_target_preinv->add( new DateInterval( 'P2D' ) );
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'target' ][ $date_target_preinv->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 1 / 100 ); // J2
		$date_target_preinv->add( new DateInterval( 'P5D' ) );
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'target' ][ $date_target_preinv->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 5 / 100 ); // J7
		$this->campaign_stats[ 'vote' ][ 'list_preinvestment' ][ 'target' ][ $this->campaign_stats[ 'vote' ][ 'end' ] ] = round( $this->campaign_stats[ 'goal' ] / 3 );

		// Notes
		$this->campaign_stats[ 'vote' ][ 'rates' ] = array();
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'economy' ] = round( $vote_results[ 'average_impact_economy' ], 2 );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'environment' ] = round( $vote_results[ 'average_impact_environment' ], 2 );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'social' ] = round( $vote_results[ 'average_impact_social' ], 2 );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ] = array();
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '1' ] = max( 0, $vote_results[ 'rate_project_list' ][ 1 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '2' ] = max( 0, $vote_results[ 'rate_project_list' ][ 2 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '3' ] = max( 0, $vote_results[ 'rate_project_list' ][ 3 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '4' ] = max( 0, $vote_results[ 'rate_project_list' ][ 4 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ '5' ] = max( 0, $vote_results[ 'rate_project_list' ][ 5 ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ 'average' ] = max( 0, $vote_results[ 'rate_project_average' ] );
		$this->campaign_stats[ 'vote' ][ 'rates' ][ 'project' ][ 'positive_percent' ] = max( 0, $vote_results[ 'percent_project_validated' ] );

		// Risque
		$this->campaign_stats[ 'vote' ][ 'risk' ] = array();
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '1' ] = max( 0, $vote_results[ 'risk_list' ][ 1 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '2' ] = max( 0, $vote_results[ 'risk_list' ][ 2 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '3' ] = max( 0, $vote_results[ 'risk_list' ][ 3 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '4' ] = max( 0, $vote_results[ 'risk_list' ][ 4 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ '5' ] = max( 0, $vote_results[ 'risk_list' ][ 5 ] );
		$this->campaign_stats[ 'vote' ][ 'risk' ][ 'average' ] = max( 0, round( $vote_results[ 'average_risk' ], 2 ) );

		// Plus d'infos
		$this->campaign_stats[ 'vote' ][ 'more_info' ] = array();
		// Valeur à 1 car pose problème si 0 pour construire le graph => provoque une division par 0 quelque part difficile à comprendre. Le passage à 1 évite les soucis...
		$this->campaign_stats[ 'vote' ][ 'more_info' ][ 'impact' ] = max( 1, $vote_results[ 'count_more_info_impact' ] );
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
		$date_begin = $this->campaign->begin_collecte_date( 'Y-m-d' );
		$date_end = $this->campaign->end_date( 'Y-m-d' );
		$this->campaign_stats[ 'funding' ][ 'start' ] = $date_begin;
		$this->campaign_stats[ 'funding' ][ 'end' ] = $date_end;
		if ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing || $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_validated || $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) {
			$this->campaign_stats[ 'funding' ][ 'start' ] = $this->campaign_stats[ 'vote' ][ 'end' ];
			$this->campaign_stats[ 'funding' ][ 'end' ] = $this->campaign_stats[ 'vote' ][ 'end' ];
		}
		if ( $this->campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte && $this->campaign->can_invest_until_contract_start_date() ) {
			$end_date_when_can_invest_until_contract_start_date = $this->campaign->get_end_date_when_can_invest_until_contract_start_date();
			$this->campaign_stats[ 'funding' ][ 'end' ] = $end_date_when_can_invest_until_contract_start_date->format( 'Y-m-d' );
		}

		// la date de début d'investissement n'étant pas toujours recalculée, on s'assure qu'elle ne soit pas postérieure à la date de fin
		$datetime_begin = new DateTime( $this->campaign_stats[ 'funding' ][ 'start' ] );
		$datetime_end = new DateTime( $this->campaign_stats[ 'funding' ][ 'end' ] );
		if ( $datetime_begin > $datetime_end ) {
			$this->campaign_stats[ 'funding' ][ 'start' ] = $this->campaign_stats[ 'funding' ][ 'end' ];
		}

		$this->campaign_stats[ 'funding' ][ 'nb_investment' ] = array();
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'current' ] = max( 0, $investment_results[ 'count_validate_investments' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'current_different' ] = max( 0, $investment_results[ 'count_validate_investors' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'not_validated' ] = max( 0, $investment_results[ 'count_not_validate_investments' ] );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'average' ] = round( $reference_for_average_ratio_nb_investment * $campaign_ratio_to_average );
		$this->campaign_stats[ 'funding' ][ 'nb_investment' ][ 'median' ] = round( $reference_for_median_ratio_nb_investment * $campaign_ratio_to_median );
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ] = array();
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'current' ] = $this->campaign->current_amount( FALSE );
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'not_validated' ] = max( 0, $investment_results[ 'amount_not_validate_investments' ] );
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'average' ] = round( $reference_for_average_ratio_amount_investment * $campaign_ratio_to_average );
		$this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'median' ] = round( $reference_for_median_ratio_amount_investment * $campaign_ratio_to_median );

		// Liste
		$this->campaign_stats[ 'funding' ][ 'list_investment' ] = array();
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'current' ] = array();
		foreach ( $investment_results[ 'payments_data' ] as $investment_result ) {
			if ( $investment_result[ 'status' ] == 'publish' ) {
				$investment_date = new DateTime( $investment_result[ 'date' ] );
				$investment_item = array(
					'date' => $investment_date->format( 'Y-m-d\Th:i' ),
					'sum' => max( 0, $investment_result[ 'amount' ] )
				);
				array_push( $this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'current' ], $investment_item );
			}
		}
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'target' ] = array();
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'target' ][ $this->campaign_stats[ 'funding' ][ 'start' ] ] = round( $this->campaign_stats[ 'goal' ] * 35 / 100 ); // J0
		$date_target = new DateTime( $this->campaign_stats[ 'funding' ][ 'start' ] );
		$date_target->add( new DateInterval( 'P2D' ) );
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'target' ][ $date_target->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 45 / 100 ); // J2
		$date_target->add( new DateInterval( 'P5D' ) );
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'target' ][ $date_target->format( 'Y-m-d' ) ] = round( $this->campaign_stats[ 'goal' ] * 60 / 100 ); // J7
		$this->campaign_stats[ 'funding' ][ 'list_investment' ][ 'target' ][ $this->campaign_stats[ 'funding' ][ 'end' ] ] = min( $this->campaign_stats[ 'funding' ][ 'amount_investment' ][ 'average' ], $this->campaign->goal( FALSE ) );

		// Stats
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'age' ] = max( 0, $investment_results[ 'average_age' ] );
		if ( is_nan( $this->campaign_stats[ 'funding' ][ 'stats' ][ 'age' ] ) ) {
			$this->campaign_stats[ 'funding' ][ 'stats' ][ 'age' ] = 0;
		}
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'percent_men' ] = max( 0, $investment_results[ 'percent_male' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'percent_women' ] = max( 0, $investment_results[ 'percent_female' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_average' ] = max( 0, $investment_results[ 'average_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_min' ] = max( 0, $investment_results[ 'min_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_median' ] = max( 0, $investment_results[ 'median_invest' ] );
		$this->campaign_stats[ 'funding' ][ 'stats' ][ 'invest_max' ] = max( 0, $investment_results[ 'max_invest' ] );

		setcookie( 'campaign_url', json_encode( $this->campaign_stats[ 'url' ] ) );
	}

	public function get_campaign_stats() {
		return $this->campaign_stats;
	}
}