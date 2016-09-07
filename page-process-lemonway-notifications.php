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
 * Ex : jkdc
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
		// - Parcourir ses paiements et trouver un investissement en attente correspondant au montant et de type virement
		// - Faire le transfert vers le porte-monnaie du porteur de projet
		// - Créer le contrat pdf
		// - Envoyer validation d'investissement par mail
		
	}
	
	
} else {
?>
	Coucou !
<?php	
}