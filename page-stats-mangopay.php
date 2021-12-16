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

			<?php
			/*	echo "STATS MANGOPAY*****************<br>";
				$campaign_id = 89569;
				$campaign = new ATCF_Campaign( $campaign_id );
				echo 'STATS MANGOPAY $campaign->get_public_url() = ' . $campaign->get_public_url() . " <br>";
				echo 'STATS MANGOPAY $campaign->get_name() = ' . $campaign->get_name() . " <br>";*/
			?>
			
			<?php
			//WDGCronActions::send_notifications();
			?>

			<?php
			// $WDGUser = new WDGUser( 68 );
			// NotificationsAPI::user_registered_without_investment( $WDGUser );
			?>

			<?php // envoi un mail de relevé de royalties
				// WDGQueue::execute_roi_transfer_message(68, '', '');
			?>

			<?php //envoi un mail de validation d'investissement
			/*	$payment_id = 78736;
				$post_campaign = atcf_get_campaign_post_by_payment_id($payment_id);
				$campaign = atcf_get_campaign($post_campaign);
		
				$payment_data = edd_get_payment_meta( $payment_id );
				$WDGInvestment = new WDGInvestment($payment_id);
				$payment_amount = edd_get_payment_amount( $payment_id );
				$email = $payment_data['email'];
				$user_data = get_user_by('email', $email);
				$WDGUser = new WDGUser($user_data->ID);
				$payment_key = edd_get_payment_key( $payment_id );

				echo 'email = '.$email.'<br>';
				echo 'language = '.$WDGUser->get_language().'<br>';
				echo 'payment_key = '.$payment_key.'<br>';

				NotificationsEmails::new_purchase_user( $payment_id, '' );*/
			?>
		
			<?php //Forcer un ajout d'investissement sur api
			/*	$id = 77110;
				$inv = new WDGInvestment($id); 
				$inv->save_to_api();*/

			?>
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
				/*$roi_declaration = new WDGROIDeclaration(1); $roi_declaration->redo_transfers(); 
				$roi_declaration = new WDGROIDeclaration(14);
				$roi_declaration->make_transfer(false);*/
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
			*/	
			?>
			
			<?php // Init user sur LW
			//$wdgUser = new WDGUser(4155); $wdgUser->register_lemonway(); 
			?>
			
			<?php // Regénération d'un contrat d'investissement
			/*	$approve_payment_id = 75157;
				$campaign_id = 68175;

				$user_info = edd_get_payment_meta_user_info( $approve_payment_id );
				$amount = edd_get_payment_amount( $approve_payment_id );
				$contribution_id = edd_get_payment_key($approve_payment_id);
				echo 'approve_payment_id = '.$approve_payment_id.'<br>';
				echo 'campaign_id = '.$campaign_id.'<br>';
				echo 'user_info = '.$user_info['email'].'<br>';
				echo 'amount = '.$amount.'<br>';
				echo 'contribution_id = '.$contribution_id.'<br>';
				$is_only_wallet = FALSE;
				if (strpos($contribution_id, 'wallet_') !== FALSE && strpos($contribution_id, '_wallet_') === FALSE) {
					$is_only_wallet = TRUE;
				}
				echo 'is_only_wallet = '.$is_only_wallet.'<br>';

				$campaign = new ATCF_Campaign( $campaign_id );
				echo 'campaign = '.$campaign->get_name().'<br>';
				if( $amount>= WDGInvestmentSignature::$investment_amount_signature_needed_minimum ) {
					echo 'on passe par eversign <br>';
					$WDGInvestmentSignature = new WDGInvestmentSignature( $approve_payment_id );
					$contract_id = $WDGInvestmentSignature->create_eversign();
					if( !empty( $contract_id ) ) {
						NotificationsEmails::new_purchase_user_success( $approve_payment_id,FALSE, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ), $is_only_wallet );
					}else{
						global $contract_errors;
						$contract_errors ='contract_failed';
						NotificationsEmails::new_purchase_user_error_contract( $approve_payment_id, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ), $is_only_wallet );
						NotificationsAsana::new_purchase_admin_error_contract( $approve_payment_id );

					}

				}else{
					echo 'contrat simple <br>';
					$new_contract_pdf_file = getNewPdfToSign( $campaign_id, $approve_payment_id, $user_info['id'] );
					NotificationsEmails::new_purchase_user_success_nocontract( $approve_payment_id, $new_contract_pdf_file,FALSE, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ), $is_only_wallet );
				}*/
			?>
			
			<?php // Annuler des ROI
//			WDGROI::cancel_list( 1173 );
			?>
			
			<?php
//			LemonwayLib::wallet_register_iban( 'SC', 'WE DO GOOD', 'FR7614445202000800123435043', 'CEPAFRPP444', '51 RUE SAINT HELIER 35000 RENNES' );s
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
			
			// avoir les infos de wallets
			/*	$user_id = 27923 ;
				$WDGUser_invest_author = new WDGUser($user_id);

				echo $WDGUser_invest_author->get_firstname().' '.$WDGUser_invest_author->get_lastname().'<br>';

				$buffer = FALSE;
				$wallet_details = $WDGUser_invest_author->get_wallet_details();
				
				echo var_export($wallet_details, TRUE);
				echo '<br><br><br><br>'; 
				if ( isset( $wallet_details->IBANS->IBAN ) ) {
					if ( is_array( $wallet_details->IBANS->IBAN ) ) {
						$buffer = $wallet_details->IBANS->IBAN[ 0 ];
						echo 'premier HOLDER '.$buffer->HOLDER.'<br>'; 
						echo 'premier STATUS '.$buffer->S.'<br>'; 
						echo 'premier TYPE '.$buffer->TYPE.'<br>'; 
						echo 'premier DATA '.$buffer->DATA.'<br>'; 
						echo 'premier SWIFT '.$buffer->SWIFT.'<br>'; 
						echo '<br><br><br><br>'; 
						// Si le premier IBAN est désactivé, on va chercher dans la suite
						// de même si cet iban a LEMON WAY comme holder (viban)
						if ( count( $wallet_details->IBANS->IBAN ) > 1 && ( $buffer->S == WDGUser::$iban_status_disabled || $buffer->S == WDGUser::$iban_status_rejected || strtolower ( str_replace(' ', '', $buffer->HOLDER) ) == WDGUser::$iban_holder_lw ) ) {
							foreach ( $wallet_details->IBANS->IBAN as $iban_item ) {
								echo 'HOLDER '.$iban_item->HOLDER.'<br>'; 
								echo 'Status '.$iban_item->S.'<br>'; 
								echo 'TYPE '.$iban_item->TYPE.'<br>'; 
								echo 'DATA '.$iban_item->DATA.'<br>'; 
								echo 'SWIFT '.$iban_item->SWIFT.'<br>'; 
								echo 'HOLDER standardisé:'.strtolower ( str_replace(' ', '', $iban_item->HOLDER) ).':<br>'; 
								echo 'constante:'.WDGUser::$iban_holder_lw.':<br>'; 
								echo '<br><br><br><br>'; 
								if ( ( $iban_item->S == WDGUser::$iban_status_validated || $iban_item->S == WDGUser::$iban_status_waiting ) && strtolower ( str_replace(' ', '', $iban_item->HOLDER) ) != WDGUser::$iban_holder_lw ) {
									$buffer = $iban_item;
								}
							}
						}
					} else {
						$buffer = $wallet_details->IBANS->IBAN;
					}
					echo '<br><br><br><br>'; 
					echo 'du coup ----------------- <br>';
					echo $buffer->HOLDER.'<br>'; 
					echo $buffer->DATA.'<br>'; 
					echo $buffer->SWIFT.'<br>';
					echo '<br><br><br><br>'; 
	
				} else {
					echo 'RIEN <br>';
				}
		
				// avoir info de viban
				// $WDGUser_invest_author->get_viban();
				echo 'VIBAN ----------------- <br>';
				$iban_info = $WDGUser_invest_author->get_viban();

				if ( !empty( $iban_info ) ) {
					echo 'holder '.$iban_info[ 'holder' ].'<br>'; 
					echo 'iban '.$iban_info[ 'iban' ].'<br>'; 
					echo 'bic '.$iban_info[ 'bic' ].'<br>'; 
					if ( !empty( $iban_info[ 'backup' ] ) && !empty( $iban_info[ 'backup' ][ 'lemonway_id' ] ) ){
						echo 'backup lemonway_id  '.$iban_info[ 'backup' ][ 'lemonway_id' ].'<br>'; 
					}
				} else {
					echo 'ERROR';
				}
				echo '<br><br><br><br>'; 


				// ce que nous renvoient les fonctions existantes

				echo 'get_lemonway_iban ----------------- <br>';
				$WDGUser_lw_bank_info = $WDGUser_invest_author->get_lemonway_iban();
				echo 'HOLDER '.$WDGUser_lw_bank_info->HOLDER.'<br>'; 
				echo 'DATA '.$WDGUser_lw_bank_info->DATA.'<br>'; 
				echo 'SWIFT '.$WDGUser_lw_bank_info->SWIFT.'<br>'; 
				echo '<br><br><br><br>'; 

				echo 'get_lemonway_iban_status ----------------- <br>';
				echo 'status '.$WDGUser_invest_author->get_lemonway_iban_status().'<br>'; 
				echo '<br><br><br><br>'; 


				echo 'get_current_user_iban_document_status ----------------- <br>';
				echo 'status '.$WDGUser_invest_author->get_document_lemonway_status( LemonwayDocument::$document_type_bank ).'<br>'; 
				echo '<br><br><br><br>'; 

				// les documents

				echo '$wallet_details->DOCS ----------------- <br>';
				echo var_export($wallet_details->DOCS, TRUE);
				echo '<br><br><br><br>'; 
				
				if ( !empty( $wallet_details->DOCS ) && !empty( $wallet_details->DOCS->DOC ) ) {
					foreach( $wallet_details->DOCS->DOC as $document_object ) {
						echo 'TYPE '.$document_object->TYPE.' '.LemonwayDocument::get_document_type_str_by_type_id( $document_object->TYPE ).'<br>';
						echo 'STATUS '.$document_object->S.' '.LemonwayDocument::get_document_status_str_by_status_id( $document_object->S ).'<br>';
						echo 'COMMENT '.$document_object->C.'<br>';
						echo var_export($document_object, TRUE);
						echo '<br><br><br><br>'; 

						if ( isset( $document_object->DOCS->DOC->TYPE ) ) {
							echo 'document_object->DOCS->DOC->TYPE '.$document_object->DOCS->DOC->TYPE.' '.LemonwayDocument::get_document_type_str_by_type_id( $document_object->DOCS->DOC->TYPE ).'<br>';
							echo 'document_object->DOCS->DOC->STATUS '.$document_object->DOCS->DOC->S.' '.LemonwayDocument::get_document_status_str_by_status_id( $document_object->DOCS->DOC->S ).'<br>';
							echo 'document_object->DOCS->DOC->COMMENT '.$document_object->DOCS->DOC->C.'<br>';
							echo '<br><br><br><br>'; 
						}
					}
					
				}*/
			?>

			<?php
			// faire et valider un virement
		/*		$campaign_id = 60915;
				$user_id = 28015;
				$lemonway_posted_amount = 2000;
				$investment_id = 94497;
				$payment_key = 'wire_TEMP_87906';

				$WDGUser_invest_author = new WDGUser($user_id);


				if ( $investment_id != FALSE && $campaign_id != FALSE ) {
					// - Faire le transfert vers le porte-monnaie du porteur de projet
					$campaign = new ATCF_Campaign( $campaign_id );

					$campaign_organization = $campaign->get_organization();
					ypcf_debug_log( 'PROCESS -> $campaign_organization->wpref = ' . $campaign_organization->wpref, FALSE );
					$organization_obj = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );
					$invest_author = $WDGUser_invest_author;
					ypcf_debug_log( 'PROCESS -> $WDGUser_invest_author->wp_user->ID = ' . $WDGUser_invest_author->wp_user->ID, FALSE );
					ypcf_debug_log( 'PROCESS -> $invest_author = ' . $invest_author->wp_user->ID, FALSE );
					$lemonway_id = $WDGUser_invest_author->get_lemonway_id();

					$organization_obj->check_register_campaign_lemonway_wallet();
					LemonwayLib::ask_transfer_funds( $lemonway_id, $organization_obj->get_campaign_lemonway_id(), $lemonway_posted_amount );
					
					// Si la campagne n'est pas en cours d'évaluation, on peut valider l'investissement
					if ( $campaign->campaign_status() != ATCF_Campaign::$campaign_status_vote ) {
						$postdata = array(
							'ID'			=> $investment_id,
							'post_status'	=> 'publish',
							'edit_date'		=> current_time( 'mysql' )
						);
						wp_update_post($postdata);

					} else {
						add_post_meta( $investment_id, 'has_received_wire', '1' );
					}
					
					// - Créer le contrat pdf
					// - Envoyer validation d'investissement par mail
					if ( $lemonway_posted_amount >= WDGInvestmentSignature::$investment_amount_signature_needed_minimum ) {
						$WDGInvestmentSignature = new WDGInvestmentSignature( $investment_id );
						$contract_id = $WDGInvestmentSignature->create_eversign();
						if ( !empty( $contract_id ) ) {
							NotificationsEmails::new_purchase_user_success( $investment_id, FALSE, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) );
							
						} else {
							global $contract_errors;
							$contract_errors = 'contract_failed';
							NotificationsEmails::new_purchase_user_error_contract( $investment_id, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) );
							NotificationsAsana::new_purchase_admin_error_contract( $investment_id );
						}
						
					} else {
						$new_contract_pdf_file = getNewPdfToSign( $campaign_id, $investment_id, $WDGUser_invest_author->wp_user->ID );
						NotificationsEmails::new_purchase_user_success_nocontract( $investment_id, $new_contract_pdf_file, FALSE, ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote ) );
					}
					
					NotificationsSlack::send_new_investment( $campaign->get_name(), $lemonway_posted_amount, $invest_author->get_email() );
					NotificationsEmails::new_purchase_team_members( $investment_id );
					if ( $campaign->campaign_status() != ATCF_Campaign::$campaign_status_vote ) {
						$WDGInvestment = new WDGInvestment( $investment_id );
						$WDGInvestment->save_to_api();
					}

				} */
				
			?>

			<?php
				// regénère le zip des contrats
			/*	$campaign_id = 75238;
				$campaign = new ATCF_Campaign( $campaign_id );

				$zip = new ZipArchive;
				// $zip_path = dirname( __FILE__ ). '/../../files/contracts/' .$campaign_id. '-' .$campaign->data->post_name. '.zip';
				$zip_path = __DIR__ . '/../../plugins/appthemer-crowdfunding/files/contracts/' .$campaign_id. '-' .$campaign->data->post_name. '.zip' ;
				echo 'post.php :: generate_campaign_contracts_archive campaign_id = '.$campaign_id.' $zip_path = '.$zip_path .'<br>';
				if ( file_exists( $zip_path ) ) {
					echo 'post.php :: generate_campaign_contracts_archive il existe on le unlink'.'<br>';
					unlink( $zip_path );
				}
				$res = $zip->open( $zip_path, ZipArchive::CREATE );
				if ( $res === TRUE ) {
					echo 'post.php :: generate_campaign_contracts_archive archive crééée '.'<br>';
					// $exp = dirname( __FILE__ ). '/../pdf_files/' .$campaign_id. '_*.pdf';
					// $exp = site_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/pdf_files/' .'*.pdf' ;
					$exp = __DIR__ . '/../../plugins/appthemer-crowdfunding/includes/pdf_files/' .$campaign_id. '_*.pdf' ;
					// $exp = site_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/pdf_files/' .$campaign_id. '_*.pdf' ;
					
					echo 'post.php :: generate_campaign_contracts_archive fichiers dans  '.$exp.'<br>';
					print_r(glob($exp));
					$files = glob( $exp );
					foreach ( $files as $file ) {
						$file_path_exploded = explode( '/', $file );
						$contract_filename = $file_path_exploded[ count( $file_path_exploded ) - 1 ];
						echo 'post.php :: generate_campaign_contracts_archive contract_filename  '.$contract_filename.'<br>';
						$res_addFile = $zip->addFile( $file, $contract_filename );
						if ( $res_addFile !== TRUE ){
							echo 'post.php :: generate_campaign_contracts_archive > Error: Unable to add file '.$file.' $contract_filename = '.$contract_filename.'<br>';
						}
					}
					$zip->close();
				} else {
					echo 'post.php :: generate_campaign_contracts_archive archive non crééée '.'<br>';
					echo 'post.php :: generate_campaign_contracts_archive > Error: Unable to create zip file '.$zip_path.' $res = '.$res.'<br>';
				}*/
			?>
			
			<?php
			//Validation de paiement
			/*
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
			// Récupérer la liste des mails d'utilisateurs qui ont eu des royalties en 2016
			/*
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

			
			<?php //  rembourser un investissement
			/*	$id=73013;
				$inv = new WDGInvestment($id);
				echo $inv->get_id() . '<br>';
				echo $inv->get_amount() . '<br>';
				echo $inv->get_campaign()->get_name() . '<br>';
				echo $inv->get_saved_user_id() . '<br>';
				$inv->refund();
				$inv->cancel();	

				*/
			?>

			<?php //  rembourser plusieurs investissements
			/*	$invest_list = array(71862, 73046, 73051, 73619, 74425, 74561, 74855, 75844);
				foreach ( $invest_list as $id ) {
					$inv = new WDGInvestment($id);
					$inv->refund();
					$inv->cancel();
				}*/
			?>
			
			<?php //  annuler un investissement
			/*	$id=73031;
				$inv = new WDGInvestment($id);
				$inv->cancel();*/
			/*	$id=75818;
				$inv = new WDGInvestment($id);
				$inv->cancel();*/
			?>


			<?php //  récupérer des ROIs versés par erreur
		/*		$roi_list = array(1376, 68921, 68923, 68925);
				foreach ( $roi_list as $id ) {
					$roi = new WDGROI( $id ); 
					$roi->cancel();
				}*/
			?>

			<?php // envoi un mandat
			/*	echo "STATS MANGOPAY*****************<br>";
				$organization_id = 24047;
				$campaign_id = 65485;
				echo 'STATS MANGOPAY $organization_id = ' . $organization_id . " <br>";
				
				$campaign = new ATCF_Campaign( $campaign_id );
				$WDGOrganizationUpdated = new WDGOrganization( $organization_id );
				echo 'STATS MANGOPAY $campaign->get_name() = ' . $campaign->get_name() . " <br>";
				echo 'STATS MANGOPAY $WDGOrganizationUpdated->get_email() = ' . $WDGOrganizationUpdated->get_email() . " <br>";
				$is_orga = WDGOrganization::is_user_organization( $WDGOrganizationUpdated->get_wpref() );
				echo 'STATS MANGOPAY $is_orga   = ' . var_export($is_orga, true)  . " <br>" ;
				NotificationsAPI::mandate_to_send_to_bank( $WDGOrganizationUpdated, $WDGOrganizationUpdated->get_mandate_file_url(), $campaign->get_api_id() );*/
			?>

			<?php // affiche les backers et les montants d'une campagne
			/*	echo "STATS MANGOPAY*****************<br>";
				$campaign_id = 62832;
				$campaign = new ATCF_Campaign( $campaign_id );
				echo $campaign->get_name()."<br>";
				echo $campaign->current_amount()."<br>";
				echo $campaign->current_amount_with_check()."<br>";

				$backers = $campaign->backers();

				if ($backers > 0) {
					foreach ( $backers as $backer ) {
							$payment_id = get_post_meta( $backer->ID, '_edd_log_payment_id', true );
							$payment    = get_post( $payment_id );
							$payment_key = edd_get_payment_key( $payment_id );

						if ($payment_key == 'check') {
							echo "<br><br> <br>";
							echo "BACKER ---------- <br>";
							echo '$backer->ID  = '.$backer->ID."<br>";
							echo '$payment_id  = '.$payment_id."<br>";
							echo '$payment_key  = '.$payment_key."<br>";
							echo '$payment->post_status  = '.$payment->post_status."<br>";
							echo 'edd_get_payment_amount( $payment_id )  = '.edd_get_payment_amount( $payment_id )."<br>";

						}
						
					}
				}*/

			?>
		    
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>