<?php
global $campaign, $payment_url;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign) && is_user_logged_in()):
    ypcf_session_start();
    ypcf_check_is_project_investable();
	
    if (	isset($_REQUEST["ContributionID"]) || isset($_REQUEST["response_wkToken"])
			|| ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway && $_GET['meanofpayment'] == 'wire')
			|| ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway && $_GET['meanofpayment'] == 'wallet')
			): ?>
	
		<?php
		$purchase_key = '';
		$invest_type = $_SESSION['redirect_current_invest_type'];
		$amount_total = $_SESSION['redirect_current_amount_part'];
		if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway) {
			//Paiement par virement
			if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == 'wire') {
				$random = rand(10000, 99999);
				$purchase_key = 'wire_TEMP_' . $random;
				$amount = $_SESSION['amount_to_save'];
				
			//Paiement par porte-monnaie
			} else if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == 'wallet') {
				$amount = $_SESSION['amount_to_save'];
				$organization = $campaign->get_organisation();
				$organization_obj = new YPOrganisation($organization->organisation_wpref);
				if ($invest_type == 'user') {
					$WDGUser_current = WDGUser::current();
					if ($WDGUser_current->can_pay_with_wallet($amount, $campaign)) {
						$transfer_funds_result = LemonwayLib::ask_transfer_funds($WDGUser_current->get_lemonway_id(), $organization_obj->get_lemonway_id(), $amount);
					}
					
				} else {
					$organisation_debit = new YPOrganisation($invest_type);
					if ($organisation_debit->can_pay_with_wallet($amount, $campaign)) {
						$transfer_funds_result = LemonwayLib::ask_transfer_funds($organisation_debit->get_lemonway_id(), $organization_obj->get_lemonway_id(), $amount);
					}
				}
				$purchase_key = 'wallet_'. $transfer_funds_result->ID;
				
			//Paiement par carte
			} else {
				$purchase_key = $_REQUEST["response_wkToken"];
				$lw_transaction_result = LemonwayLib::get_transaction_by_id( $purchase_key );
				$amount = $lw_transaction_result->CRED;
				$lw_return = ( (isset($_GET['cancel']) && $_GET['cancel'] == '1') || (isset($_GET['error']) && $_GET['error'] == '1') ) ? "failed" : "ok";
					
				//Compléter avec Wallet
				if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == "cardwallet" && $lw_return != "failed" && ($lw_transaction_result->STATUS == 3 || $lw_transaction_result->STATUS == 0) && isset($_SESSION['need_wallet_completion']) && $_SESSION['need_wallet_completion'] > 0) {
					$amount_wallet = $_SESSION['need_wallet_completion'];
					$organization = $campaign->get_organisation();
					$organization_obj = new YPOrganisation($organization->organisation_wpref);
					if ($invest_type == 'user') {
						$WDGUser_current = WDGUser::current();
						if ($WDGUser_current->can_pay_with_wallet($amount_wallet, $campaign)) {
							$transfer_funds_result = LemonwayLib::ask_transfer_funds($WDGUser_current->get_lemonway_id(), $organization_obj->get_lemonway_id(), $amount_wallet);
						}

					} else {
						$organisation_debit = new YPOrganisation($invest_type);
						if ($organisation_debit->can_pay_with_wallet($amount_wallet, $campaign)) {
							$transfer_funds_result = LemonwayLib::ask_transfer_funds($organisation_debit->get_lemonway_id(), $organization_obj->get_lemonway_id(), $amount_wallet);
						}
					}
					
					
					if ( $transfer_funds_result != FALSE ) {
						$purchase_key .= '_wallet_'. $transfer_funds_result->ID;
						$amount += $amount_wallet;
					}
				}
			}
		}

		$paymentlist = edd_get_payments(array(
		    'number'	 => -1,
		    'download'   => $campaign->ID
		));
		foreach ($paymentlist as $payment) {
			if (edd_get_payment_key($payment->ID) == $purchase_key) {
				$page_investments = get_page_by_path('mes-investissements');
				_e("Le paiement a d&eacute;j&agrave; &eacute;t&eacute; pris en compte. Merci de vous rendre sur la page", 'yproject');
				?><a href="<?php echo get_permalink($page_investments->ID); ?>"><?php _e("Mes investissements", 'yproject'); ?></a>.<?php
				break;
			}
		}

		if ($buffer == ''):
			//Récupération du bon utilisateur
			$current_user = wp_get_current_user();
			$save_user_id = $current_user->ID;
			$save_display_name = $current_user->display_name;
			if (isset($_SESSION['redirect_current_invest_type']) && $_SESSION['redirect_current_invest_type'] != "user") {
				$invest_type = $_SESSION['redirect_current_invest_type'];
				$organisation = new YPOrganisation($invest_type);
				if ($organisation) {
					$current_user_organisation = $organisation->get_creator();
					$save_user_id = $current_user_organisation->ID;
					$save_display_name = $organisation->get_name();
				}
			}
			
			// GESTION DU PAIEMENT COTE EDD
			//On met à jour l'état de la campagne
			$options_cart = array();
			if ($campaign->funding_type() == 'fundingdonation') {
				//Gestion contreparties
				$rewards = atcf_get_rewards($campaign->ID);
				//Enregistre un achat au compteur
				$rewards->buy_a_reward($_SESSION['redirect_current_selected_reward']);
				$data_reward = $rewards->get_reward_from_ID($_SESSION['redirect_current_selected_reward']);

				$save_reward = array(
					'id'    => intval($data_reward['id']),
					'amount'=> intval($data_reward['amount']),
					'name'  => $data_reward['name'],
				);
				$options_cart['reward'] = $save_reward;
				unset($_SESSION['redirect_current_selected_reward']);
			}
			if (isset($_SESSION['redirect_current_amount_part'])) {
				unset($_SESSION['redirect_current_amount_part']);
			}
			if (isset($_SESSION['redirect_current_invest_type'])) {
				unset($_SESSION['redirect_current_invest_type']);
			}


			//Création d'un paiement pour edd
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

			$status = 'pending';
			if ( (isset($_GET['cancel']) && $_GET['cancel'] == '1') || (isset($_GET['error']) && $_GET['error'] == '1') ) {
				$status = 'failed';
				try {
					NotificationsEmails::new_purchase_admin_error( $current_user, $lw_transaction_result->INT_MSG );
				} catch (Exception $e) {
					
				}
			}
			$payment_data = array( 
				'price'			=> $amount, 
				'date'			=> date('Y-m-d H:i:s'), 
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
			if ($status != 'failed') {
				edd_record_sale_in_log($campaign->ID, $payment_id);
			}
			// FIN GESTION DU PAIEMENT COTE EDD

			// Vérifie le statut du paiement, envoie un mail de confirmation et crée un contrat si on est ok
			$payment_status = ypcf_get_updated_payment_status($payment_id);

			// Affichage en fonction du statut du paiement
			switch ($payment_status) {
				case 'pending' : 
					$invest_page = get_page_by_path('mes_investissements');
					$share_page = get_page_by_path('paiement-partager');
					?>
						
					<?php
					global $current_breadcrumb_step; $current_breadcrumb_step = 4;
					locate_template( 'invest/breadcrumb.php', true );
					?>
				
					<?php if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == 'wire'): ?>
						<?php NotificationsEmails::new_purchase_pending_wire_admin( $payment_id ); ?>
						<?php NotificationsEmails::new_purchase_pending_wire_user( $payment_id ); ?>
						<?php _e("Dans l'attente de votre virement, vous recevrez un e-mail rappelant les informations &agrave; nous fournir.", 'yproject'); ?><br /><br />
						
						<?php if ($campaign->funding_type() != 'fundingdonation' && $amount > 1500): ?>
							<?php _e("Une fois valid&eacute;, vous recevrez deux e-mails :", 'yproject'); ?><br /><br />
							- <?php _e("un e-mail envoy&eacute; par WEDOGOOD pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject'); ?><br /><br />
							- <?php _e("un e-mail envoy&eacute; par notre partenaire Signsquid. Cet e-mail contient un lien vous permettant de signer le pouvoir pour le contrat d&apos;investissement", 'yproject'); ?><br /><br />
							
						<?php else: ?>
							<?php _e("Une fois valid&eacute;, vous recevrez un e-mail confirmant votre paiement. Votre contrat d&apos;investissement sera joint &agrave; cet e-mail.", 'yproject'); ?><br /><br />
						<?php endif; ?>
							
					<?php else: ?>
						<?php _e("Transaction en cours.", 'yproject'); ?><br />
					<?php endif; ?>
						
					
					<?php _e("Merci de vous rendre sur la page", 'yproject'); ?> <a href="<?php echo get_permalink($invest_page->ID); ?>"><?php _e("Mes investissements", 'yproject'); ?></a> <?php _e("pour suivre l&apos;&eacute;volution de votre paiement.", 'yproject'); ?><br /><br />
					
					<center><a class="button" href="<?php echo get_permalink($share_page->ID); ?>'?campaign_id=<?php echo $campaign->ID; ?>"><?php _e("Suivant", 'yproject'); ?></a></center><br /><br />
					<?php
					break;

				case 'publish' :
					do_action('wdg_delete_cache', array(
						'project-header-right-'.$campaign->ID,
						'projects-current',
						'project-investments-data-'.$campaign->ID
					));
					$campaign_url = get_permalink($campaign->ID);
					$share_page = get_page_by_path('paiement-partager');
					
					//On affiche que tout s'est bien passé
					?>
						
					<?php
					global $current_breadcrumb_step; $current_breadcrumb_step = 4;
					locate_template( 'invest/breadcrumb.php', true );
					?>

					<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
						<?php if ($amount > 1500): ?>
					
							<?php global $contract_errors, $wpdb; ?>
							<?php if (!isset($contract_errors) || $contract_errors == ''): ?>
								<?php _e("Vous allez recevoir deux e-mails cons&eacute;cutifs &agrave; l&apos;adresse", 'yproject'); ?> <?php echo $current_user->user_email; ?>
								(<?php _e("pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject'); ?>) :<br /><br />
								- <?php _e("un e-mail envoy&eacute; par WEDOGOOD pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject'); ?><br /><br />
								- <?php _e("un e-mail envoy&eacute; par notre partenaire Signsquid. Cet e-mail contient un lien vous permettant de signer le pouvoir pour le contrat d&apos;investissement", 'yproject'); ?><br /><br />
								<center><img src="'. get_stylesheet_directory_uri() .'/images/signsquid.png" width="168" height="64" /></center><br />
								<?php if (ypcf_check_user_phone_format($current_user->get('user_mobile_phone'))): ?>
									<?php _e("Vous allez aussi recevoir un sms contenant le code au num&eacute;ro que vous nous avez indiqu&eacute; :", 'yproject'); ?> <?php echo $current_user->get('user_mobile_phone'); ?><br /><br />
								<?php endif; ?>

							<?php else: ?>
								<?php ypcf_debug_log("ypcf_shortcode_invest_return --- ERROR :: contract :: ".$contract_errors); ?>
								<?php _e("Vous allez recevoir un e-mail de confirmation de paiement.", 'yproject'); ?><br />
								<span class="errors"><?php _e("Cependant, il y a eu un probl&egrave;me lors de la g&eacute;n&eacute;ration du contrat. Nos &eacute;quipes travaillent &agrave; la r&eacute;solution de ce probl&egrave;me.", 'yproject'); ?></span><br /><br />
							<?php endif; ?>
								
						<?php else: ?>
							<div class="align-center">
								<?php _e("Votre investissement est valid&eacute;.", 'yproject'); ?><br />
								<?php _e("Vous allez recevoir un e-mail &agrave; l&apos;adresse", 'yproject'); ?> <?php echo $current_user->user_email; ?> (<?php _e("pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject'); ?>).<br />
								<?php _e("Votre contrat d&apos;investissement sera joint &agrave; cet e-mail.", 'yproject'); ?><br /><br />
							</div>
						<?php endif; ?>
								
					<?php else: ?>
						<div class="align-center">
							<?php _e("Votre paiement est valid&eacute;.", 'yproject'); ?><br />
							<?php _e("Vous allez recevoir un e-mail &agrave; l&apos;adresse", 'yproject'); ?> <?php echo $current_user->user_email; ?> (<?php _e("pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject'); ?>).<br /><br />
						</div>
					<?php endif; ?>
					<div class="align-center"><a class="button" href="<?php echo get_permalink($share_page->ID); ?>?campaign_id=<?php echo $campaign->ID; ?>"><?php _e("Suivant", 'yproject'); ?></a></div><br /><br />

					<?php 
					//Si un utilisateur investit, il croit au projet
					global $wpdb;
					$table_jcrois = $wpdb->prefix . "jycrois";
					$users = $wpdb->get_results( "SELECT user_id FROM $table_jcrois WHERE campaign_id = ". $campaign->ID. " AND user_id = " . $current_user->ID );
					if (!$users) {
						$wpdb->insert( $table_jcrois,
							array(
							'user_id'		=> $current_user->ID,
							'campaign_id'	=> $campaign->ID
							)
						);
					}

					// Construction des urls utilisés dans les liens du fil d'actualité
					// url d'une campagne précisée par son nom 
					$post_title = $campaign->data->post_title;
					$url_campaign = '<a href="'.$campaign_url.'">'.$post_title.'</a>';
					//url d'un utilisateur précis
					$url_profile = '<a href="' . bp_core_get_userlink($save_user_id, false, true) . '">' . $save_display_name . '</a>';

					bp_activity_add( array (
						'component' => 'profile',
						'type'      => 'invested',
						'action'    => $url_profile.' a investi sur le projet '.$url_campaign
					) );
					break;

				case 'failed' :
					_e("Il y a eu une erreur pendant la transaction.", 'yproject'); ?><br />
					
					<?php if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway): ?>
						<?php echo $lw_transaction_result->MSG . ' (' .$lw_transaction_result->INT_MSG. ')'; ?>
					<?php endif; ?><br />
					
					<?php _e("Si vous souhaitez de l'aide relative &agrave; ce probl&egrave;me, merci de nous contacter sur investir@wedogood.co en pr&eacute;cisant les informations ci-dessus.", 'yproject'); ?>
					<?php
					break;
			}

			edd_empty_cart();

		else:
			_e("Il y a eu une erreur pendant la transacton.", 'yproject');
		endif;
	
	else:
		_e("Erreur d'affichage (ERRPAYRET01).", 'yproject');
    endif;
endif;
