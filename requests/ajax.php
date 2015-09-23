<?php
/**
 * Classe de gestion des appels Ajax
 * TODO : centraliser ici
 */
class YPAjaxLib {
	/**
	 * Ajoute une action WordPress à exécuter en Ajax
	 * @param string $action_name
	 */
	public static function add_action($action_name) {
		add_action('wp_ajax_' . $action_name, array('YPAjaxLib', $action_name));
		add_action('wp_ajax_nopriv_' . $action_name, array('YPAjaxLib', $action_name));
	}
    
	/**
	 * Affiche la liste des utilisateurs d'un projet qui doivent récupérer de l'argent de leur investissement
	 */
	public static function display_roi_user_list() {
		//Récupération des éléments à traiter
		$campaign_id = filter_input(INPUT_POST, 'campaign_id');
		$payment_item_id = filter_input(INPUT_POST, 'payment_item');
		$campaign = new ATCF_Campaign($campaign_id);
		$payment_list = $campaign->payment_list();
		
		//Calculs des montants à reverser
		$total_amount = $campaign->current_amount(FALSE);
		$roi_amount = $payment_list[$payment_item_id];
		$total_roi = 0;
		$total_fees = 0;
		$investments_list = $campaign->payments_data(TRUE);
		foreach ($investments_list as $investment_item) {
			//Calcul de la part de l'investisseur dans le total
			$investor_proportion = $investment_item['amount'] / $total_amount; //0.105
			//Calcul du montant à récupérer en roi
			$investor_proportion_amount = floor($roi_amount * $investor_proportion * 100) / 100; //10.50
			//Calcul de la commission sur le roi de l'utilisateur
			$fees_total = $investor_proportion_amount * YP_ROI_FEES / 100; //10.50 * 1.8 / 100 = 0.189
			//Et arrondi
			$fees = round($fees_total * 100) / 100; //0.189 * 100 = 18.9 = 19 = 0.19
			$total_fees += $fees;
			//Reste à verser pour l'investisseur
			$investor_proportion_amount_remaining = $investor_proportion_amount - $fees;
			$total_roi += $investor_proportion_amount_remaining; 
			//Transfert vers utilisateur
			$user_data = get_userdata($investment_item['user']);
			echo '<tr><td>'.$user_data->first_name.' '.$user_data->last_name.'</td><td>'.$investment_item['amount'].'&euro;</td><td>'.$investor_proportion_amount_remaining.'&euro;</td><td>'.$fees.'&euro;</td></tr>';
//			ypcf_mangopay_transfer_user_to_user($current_organisation->organisation_wpref, $investment_item['user'], $investor_proportion_amount_remaining, $fees);
		}
		
		echo '<tr><td><strong>Total</strong></td><td>'.$total_amount.'&euro;</td><td>'.$total_roi.'&euro;</td><td>'.$total_fees.'&euro;</td></tr>';
		
		exit();
	}
}