<?php 
if ( current_user_can('manage_options') ) {
	/*$param_list = array( 
		'wallet' => 'SC', 
		'amountTot' => '5.00', 
		'amountCom' => '0.00',
		'comment' => 'Pour gerer les commissions',
		'cardType' => '',
		'cardNumber' => '',
		'cardCrypto' => '',
		'cardDate' => ''
	);

	$return_lw = LemonwayLib::call('MoneyIn', $param_list);*/
}
/**
 * Affichage de la liste des paiements : seulement aux utilisateurs admin
 */ 
if ( !current_user_can('manage_options') ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
}
global $disable_logs;
//$disable_logs = TRUE;

require_once EDD_PLUGIN_DIR . 'includes/admin/payments/class-payments-table.php';
get_header(); 
?>

    <div id="content" style="margin-top: 80px;">
	<div class="padder">
	    <div class="center">
		<?php 
		if ( current_user_can('manage_options') ) :
		?>
		
		    <h1>Infos LW</h1>
			
			<?php //Ajouter un RIB à un utilisateur
			/*$id_user = 0;
			$user = new WDGUser($id_user);
			$holder_name = "";
			$iban = "";
			$bic = "";
			$address1 = "";
			$user->save_iban( $holder_name, $iban, $bic, $address1 );
			LemonwayLib::wallet_register_iban( $user->get_lemonway_id(), $holder_name, $iban, $bic, $address1 );*/
			?>
			
			<?php // Refaire des transferts de ROI
			//$roi_declaration = new WDGROIDeclaration(1); $roi_declaration->redo_transfers(); 
//			$roi_declaration = new WDGROIDeclaration(14);
//			$roi_declaration->make_transfer(false);
			?>
			
			<?php //Relancer un ROI seul
//			$roi = new WDGROI(2798); $roi->retry();
			?>
			
			<?php // Infos sur une transaction 
		/*	$transactionId = 'TRANSID12709';
			echo '<br>id : '.$transactionId.'<br>';
			echo '<br>transactionMerchantToken<br>';
			$lw_transaction_result = LemonwayLib::get_transaction_by_id( $transactionId, 'moneyIn' ); print_r($lw_transaction_result); 
			echo '<br>transactionId<br>';
			$lw_transaction_result2 = LemonwayLib::get_transaction_by_id( $transactionId, 'transactionId' ); print_r($lw_transaction_result2); 
			echo '<br>payment<br>';
			$lw_transaction_result2 = LemonwayLib::get_transaction_by_id( $transactionId, 'payment' ); print_r($lw_transaction_result2); 
		*/	?>
			
			<?php // Init user sur LW
			//$wdgUser = new WDGUser(4155); $wdgUser->register_lemonway(); 
			?>
			
			<?php // Regénération d'un contrat d'investissement
//			getNewPdfToSign($project_id, $payment_id, $user_id);
//			ypcf_create_contract($payment_id, $download_id, $current_user->ID);
//			getNewPdfToSign(22310, 22465, 12609);
//			ypcf_create_contract(22465, 22310, 12609);
//			getNewPdfToSign(11182, 11740, 4246);
//			getNewPdfToSign(11182, 11595, 4055);
			?>
			
			<?php // Annuler des ROI
//			WDGROI::cancel_list( 1173 );
			?>
			
			<?php
//			LemonwayLib::wallet_register_iban( 'SC', 'WE DO GOOD', 'FR7614445202000800123435043', 'CEPAFRPP444', '51 RUE SAINT HELIER 35000 RENNES' );
			?>
			
			<?php // Gérer des virements individuels
			/*
			$debit_orga = 2408;
			$credit_user = 3090;
			$amount = 0.22;
			$organization_obj = new WDGOrganization($debit_orga);
			$WDGUser = new WDGUser($credit_user);
			$WDGUser->register_lemonway();
			$transfer = LemonwayLib::ask_transfer_funds( $organization_obj->get_lemonway_id(), $WDGUser->get_lemonway_id(), $amount );
			if ($transfer != FALSE) {
//				$roi = new WDGROI(1011);
//				$roi->status = WDGROI::$status_transferred;
//				$roi->save();
				
				$invest_id = 9339;
				$project_id = 8435;
				$decla_id = 14;
				$date_now = new DateTime();
				$date_now_formatted = $date_now->format( 'Y-m-d' );
				WDGROI::insert( $invest_id, $project_id, $debit_orga, $credit_user, $decla_id, $date_now_formatted, $amount, $transfer->ID, WDGROI::$status_transferred);
			}
			 * 
			 */
			?>
			
			<?php //Validation de virement a posteriori
			/*
			$orga_id = 4904;
			$user_id = 5524;
			$amount = 500;
			$organization_obj = new WDGOrganization($orga_id);
			$WDGUser = new WDGUser($user_id);
			$WDGUser->register_lemonway();
			$transfer = LemonwayLib::ask_transfer_funds( $WDGUser->get_lemonway_id(), $organization_obj->get_lemonway_id(), $amount );
			
			$campaign_id = 12919;
			$payment_id = 14401;
			$new_contract_pdf_file = getNewPdfToSign($campaign_id, $payment_id, $user_id);
			NotificationsEmails::new_purchase_user_success_nocontract($payment_id, $new_contract_pdf_file);
			 * 
			 */
			?>
			
			<?php
			/*
			//Validation de paiement
			$save_user_id = 4440;
			$current_user = new WP_User($save_user_id);
			$post_campaign = get_post(11082);
			$campaign = atcf_get_campaign($post_campaign);
			$options_cart = array();
			$amount = 10;
			$purchase_key = '111532635G9gi3pCWSTL1nfC9JQJkBgolSZq';
			$user_info = array(
				'id'			=> $save_user_id,
				'gender'		=> $current_user->get('user_gender'),
				'email'			=> $current_user->user_email,
				'first_name'	=> $current_user->user_firstname,
				'last_name'		=> $current_user->user_lastname,
				'discount'		=> '',
				'address'		=> array()
			);

			$cart_details = array(
				array(
					'name'			=> $campaign->data->post_title,
					'id'			=> $campaign->ID,
					'item_number'	=> array(
						'id'			=> $campaign->ID,
						'options'		=> $options_cart
					),
					'price'			=> 1,
					'quantity'		=> $amount
				)
			);
			
			$status = 'publish';
			$payment_data = array( 
				'price'			=> $amount, 
				'date'			=> '2016-07-14 23:18:18', 
				'user_email'	=> $current_user->user_email,
				'purchase_key'	=> $purchase_key,
				'currency'		=> edd_get_currency(),
				'downloads'		=> array($campaign->ID),
				'user_info'		=> $user_info,
				'cart_details'	=> $cart_details,
				'status'		=> $status
			);
			$payment_id = edd_insert_payment( $payment_data );
			
			update_post_meta( $payment_id, '_edd_payment_ip', $_SERVER['REMOTE_ADDR'] );
			if ( isset($save_reward) ) {
				update_post_meta( $payment_id, '_edd_payment_reward', $save_reward );
			}
			edd_record_sale_in_log($campaign->ID, $payment_id);
			$new_contract_pdf_file = getNewPdfToSign($campaign->ID, $payment_id, $current_user->ID);
			NotificationsEmails::new_purchase_user_success_nocontract($payment_id, $new_contract_pdf_file);
			 * 
			 */
			?>
			
			<?php
			//refund
			/*
			$campaign = new ATCF_Campaign(11833);
			$campaign->refund();
			 *
			 */
			?>
			
			<?php
			/*
			// Récupérer la liste des mails d'utilisateurs qui ont eu des royalties en 2016
			global $wpdb;
			$query = "";
			$query .= "SELECT user.user_email FROM " .$wpdb->prefix.WDGROI::$table_name. " roi";
			$query .= " INNER JOIN " .$wpdb->users. " user ON (roi.id_user = user.ID)";
			$query .= " WHERE YEAR(roi.date_transfer) = 2016";
			$query .= " GROUP BY roi.id_user";

			$email_list = $wpdb->get_results( $query );
			foreach ( $email_list as $email_item ) {
				echo $email_item->user_email . ', ';
			}
			 * 
			 */
			?>
		    
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>