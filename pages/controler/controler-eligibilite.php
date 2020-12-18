<?php
$template_engine = WDG_Templates_Engine::instance();
$template_engine->set_controler( new WDG_Page_Controler_ProspectSetup() );

class WDG_Page_Controler_ProspectSetup extends WDG_Page_Controler {

	private $guid;
	
	public function __construct() {
		parent::__construct();
		
		define( 'SKIP_BASIC_HTML', TRUE );

		// Analyse d'un retour éventuel de LW
		$input_is_success = filter_input( INPUT_GET, 'is_success' );
		$input_is_error = filter_input( INPUT_GET, 'is_error' );
		$input_is_canceled = filter_input( INPUT_GET, 'is_canceled' );
		ypcf_debug_log( 'WDG_Page_Controler_ProspectSetup::__construct', FALSE );
		if ( !empty( $input_is_success ) || !empty( $input_is_error ) || !empty( $input_is_canceled ) ) {
			$input_guid = filter_input( INPUT_GET, 'guid' );
			$api_result = WDGWPREST_Entity_Project_Draft::get( $input_guid );
			$metadata_decoded = json_decode( $api_result->metadata );

			// Succès de paiement
			$payment_token = filter_input( INPUT_GET, 'response_wkToken' );
			if ( $input_is_success === '1' ) {
				if ( !empty( $payment_token ) ) {
					if ( $api_result->authorization != 'can-create-db' ) {
						// Données à enregistrer en double
						$new_status = 'paid';
						$new_step = 'project-complete';
						$new_authorization = 'can-create-db';

						// Doublon de données
						$metadata_decoded = json_decode( $api_result->metadata );
						$metadata_decoded->status = $new_status;
						$metadata_decoded->step = $new_step;
						$metadata_decoded->authorization = $new_authorization;
	
						// Notif réception de paiement par carte
						$datetime = new DateTime();
						$amount = 0;
						$lw_transaction_result = LemonwayLib::get_transaction_by_id( $payment_token );
						$amount = $lw_transaction_result->CRED;
						NotificationsAPI::prospect_setup_payment_method_received_card( $api_result->email, $metadata_decoded->user->name, $amount, $datetime->format( 'd/m/Y H:i:s' ), $metadata_decoded->organization->name );
	
						// Transfert vers le compte bancaire de WDG
						$transfer_message = 'PROSPECT_SETUP_PAYMENT_CARD ' . $metadata_decoded->user->name . ' - ' . $metadata_decoded->organization->name;
						$result_transfer = LemonwayLib::ask_transfer_to_iban( 'SC', $amount, 0, 0, $transfer_message );						
                        if ($result_transfer->TRANS->HPAY->ID) {
							$metadata_decoded->package->paymentTransferedOnAccount = TRUE;
						}else{
							$metadata_decoded->package->paymentTransferedOnAccount = $result_transfer->TRANS->HPAY->MSG;
						}

						// Mise à jour du type de paiement						
						$metadata_decoded->package->paymentMethod = 'card';						
						$metadata_decoded->package->paymentStatus = 'complete';

						// Mise à jour date de paiement
						date_default_timezone_set("Europe/Paris");
						$metadata_decoded->package->paymentDate = $datetime->format( 'Y-m-d H:i:s' );
						$api_result->metadata = json_encode( $metadata_decoded );
						WDGWPREST_Entity_Project_Draft::update( $input_guid, $api_result->id_user, $api_result->email, $new_status, $new_step, $new_authorization, $api_result->metadata );
	
						// Envoi notif à Zapier
						$api_result = WDGWPREST_Entity_Project_Draft::get( $input_guid );
						NotificationsZapier::send_prospect_setup_payment_received( $api_result );

						// Ajout test dans 3 jours si TBPP créé
						WDGQueue::add_notifications_dashboard_not_created( $api_result->id );
					}
				}
			
			// Erreur de paiement
			} elseif ( $input_is_error === '1' || $input_is_canceled === '1' ) {
				$draft_url = home_url( '/financement/eligibilite/?guid=' . $guid );
				NotificationsAPI::prospect_setup_payment_method_error_card( $api_result->email, $metadata_decoded->user->name, $draft_url );
					
			}
		}
		
		// on récupère le composant Vue
		$WDG_Vue_Components = WDG_Vue_Components::instance();
		$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_prospect_setup );

		$this->guid = filter_input( INPUT_GET, 'guid' );
	}

	public function has_init_guid() {
		return ( !empty( $this->guid ) );
	}

	public function get_init_guid() {
		return $this->guid;
	}

	public function get_init_locale() {
		global $locale;
		if ( empty( $locale ) ) {
			return 'fr_FR';
		}
		return $locale;
	}
	
}