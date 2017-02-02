<?php
/**
 * NotifCategory : Type de notification :
- 10 : MoneyIn par virement
- 11 : MoneyIn par SDD
- 12 : MoneyIn par chéque
 * Ex : 10
 */
$lemonway_posted_category = filter_input( INPUT_POST, 'NotifCategory' );
/**
 * NotifDate : Date et heure de la creation de la notification. Heure de Paris. Format ISO8601
 * Ex : 2015-11-01T16:44:55.883
 */
$lemonway_posted_date = filter_input( INPUT_POST, 'NotifDate' );
/**
 * IntId : Identifiant interne du wallet
 * Ex : 32
 */
$lemonway_posted_id_internal = filter_input( INPUT_POST, 'IntId' );
/**
 * ExtId : Identifiant externe du wallet
 * Ex : USERW3987
 */
$lemonway_posted_id_external = filter_input( INPUT_POST, 'ExtId' );
/**
 * IdTransaction : Identifiant de la transaction
 * Ex : 204
 */
$lemonway_posted_id_transaction = filter_input( INPUT_POST, 'IdTransaction' );
/**
 * Amount : Montant à créditer au wallet (total moins la commission)
 * Ex : 10.00
 */
$lemonway_posted_amount = filter_input( INPUT_POST, 'Amount' );
/**
 * Status : Statut de la transaction
 * Ex : 0
 */
$lemonway_posted_status = filter_input( INPUT_POST, 'Status' );

if ( !empty( $lemonway_posted_category ) ) {
	
	// Gestion des virements reçus
	if ( $lemonway_posted_category == 10 ) {
		$content = 'Un virement a été reçu avec les infos suivantes :<br />';
		$content .= '$lemonway_posted_category :' .$lemonway_posted_category. '<br />';
		$content .= '$lemonway_posted_date :' .$lemonway_posted_date. '<br />';
		$content .= '$lemonway_posted_id_internal :' .$lemonway_posted_id_internal. '<br />';
		$content .= '$lemonway_posted_id_external :' .$lemonway_posted_id_external. '<br />';
		$content .= '$lemonway_posted_id_transaction :' .$lemonway_posted_id_transaction. '<br />';
		$content .= '$lemonway_posted_amount :' .$lemonway_posted_amount. '<br />';
		$content .= '$lemonway_posted_status :' .$lemonway_posted_status. '<br />';
		NotificationsEmails::send_mail( 'emilien@wedogood.co', 'Notif interne - Virement reçu', $content, true );
		
		// Algo à faire :
		// - Trouver l'utilisateur à partir de son identifiant externe
		$WDGUser_invest_author = WDGUser::get_by_lemonway_id( $lemonway_posted_id_external );
		if ( $WDGUser_invest_author !== FALSE ) {
			// - Parcourir ses paiements et trouver un investissement en attente correspondant au montant et de type virement
			$investment_id = FALSE;
			$investment_campaign_id = FALSE;
			$investments_by_campaign = $WDGUser_invest_author->get_pending_investments();
			$trace = '';
			foreach ( $investments_by_campaign as $campaign_id => $campaign_investments ) {
				$trace .= 'A';
				foreach ($campaign_investments as $campaign_investment_id) {
					$trace .= 'B';
					$payment_key = edd_get_payment_key( $campaign_investment_id );
					if ( strpos( $payment_key, 'wire_' ) !== FALSE ) {
						$trace .= 'C';
						$payment_amount = edd_get_payment_amount( $campaign_investment_id );
						if ( $payment_amount == $lemonway_posted_amount ) {
							$trace .= 'D';
							$investment_campaign_id = $campaign_id;
							$investment_id = $campaign_investment_id;
						}
					}
				}
			}
			
			if ( $investment_id != FALSE && $investment_campaign_id != FALSE ) {
				// - Faire le transfert vers le porte-monnaie du porteur de projet
				$post_campaign = get_post( $investment_campaign_id );
				$campaign = new ATCF_Campaign( $post_campaign );
				$campaign_organization = $campaign->get_organization();
				$organization_obj = new WDGOrganization( $campaign_organization->wpref );
				$invest_author = $WDGUser_invest_author;
				if ( WDGOrganization::is_user_organization($WDGUser_invest_author->wp_user->ID) ) {
					$invest_author = new WDGOrganization( $WDGUser_invest_author->wp_user->ID );
				}
				LemonwayLib::ask_transfer_funds( $invest_author->get_lemonway_id(), $organization_obj->get_lemonway_id(), $lemonway_posted_amount );
				
				// - Créer le contrat pdf
				// - Envoyer validation d'investissement par mail
				if ( $lemonway_posted_amount > 1500 ) {
					$contract_id = ypcf_create_contract( $investment_id, $investment_campaign_id, $WDGUser_invest_author->wp_user->ID );
					if ($contract_id != '') {
						$contract_infos = signsquid_get_contract_infos( $contract_id );
						NotificationsEmails::new_purchase_user_success( $investment_id, $contract_infos->{'signatories'}[0]->{'code'}, FALSE );
						NotificationsEmails::new_purchase_admin_success( $investment_id );
					} else {
						global $contract_errors;
						$contract_errors = 'contract_failed';
						NotificationsEmails::new_purchase_user_error_contract( $investment_id );
						NotificationsEmails::new_purchase_admin_error_contract( $investment_id );
					}
				} else {
					$new_contract_pdf_file = getNewPdfToSign( $investment_campaign_id, $investment_id, $WDGUser_invest_author->wp_user->ID );
					NotificationsEmails::new_purchase_user_success_nocontract( $investment_id, $new_contract_pdf_file, FALSE );
					NotificationsEmails::new_purchase_admin_success_nocontract( $investment_id, $new_contract_pdf_file );
				}
			} else {
				NotificationsEmails::send_mail( 'emilien@wedogood.co', 'Notif interne - Virement reçu - erreur', '$investment_id == FALSE || $investment_campaign_id == FALSE => ' . $trace, true );
			}
		} else {
			NotificationsEmails::send_mail( 'emilien@wedogood.co', 'Notif interne - Virement reçu - erreur', '$WDGUser_invest_author === FALSE', true );
		}
		
	}
	
	
} else {
?>
	Coucou !
<?php	
}