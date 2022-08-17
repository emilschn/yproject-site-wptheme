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