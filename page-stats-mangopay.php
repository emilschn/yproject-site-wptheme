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

    <div id="content">
	<div class="padder">
	    <div class="center">
		<?php 
		if ( current_user_can('manage_options') ) :
		?>
		
		    <h1>Infos LW</h1>
			<?php // $roi_declaration = new WDGROIDeclaration(1); $roi_declaration->redo_transfers(); ?>
			
		    <h1>Stats Mangopay</h1>
			
			<?php /* $result = ypcf_mangopay_get_operations_by_wallet_id(36782013); print_r($result); */ ?>
			
			<?php /*
    $mangopay_newrefund = request('refunds', 'POST', '{
					    "ContributionID" : 42559862,
					    "UserID" : 42559571
					}'); */
			?>
			
			<?php /*
			// Validation investissement échoué
			$post_campaign = get_post(8435);
			$campaign = new ATCF_Campaign($post_campaign);
			$purchase_key = 42574232;
			$mangopay_contribution = ypcf_mangopay_get_contribution_by_id($purchase_key);
			$amount = $mangopay_contribution->Amount / 100;
			$save_user_id = 3580;
			$current_user = get_user_by('id', $save_user_id);
			$save_display_name = $current_user->display_name;
			
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

			$payment_data = array( 
				'price'			=> $amount, 
				'date'			=> date('Y-m-d H:i:s'), 
				'user_email'	=> $current_user->user_email,
				'purchase_key'	=> $purchase_key,
				'currency'		=> edd_get_currency(),
				'downloads'		=> array($campaign->ID),
				'user_info'		=> $user_info,
				'cart_details'	=> $cart_details,
				'status'		=> 'pending'
			);
			$payment_id = edd_insert_payment( $payment_data );
			update_post_meta( $payment_id, '_edd_payment_ip', $_SERVER['REMOTE_ADDR'] );
			edd_record_sale_in_log($campaign->ID, $payment_id);
			ypcf_get_updated_payment_status($payment_id); */
			?>
		
		    <?php
		    /*
		    //Naoden
		    $campaign_id = 6100;
		    $amount = 56541.82;
		    $amount_fees = 9818.18;
		    $api_project_id = BoppLibHelpers::get_api_project_id($campaign_id);
		    echo '$api_project_id ' . $api_project_id . '<br />';
		    $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
		    if (count($current_organisations) > 0) {
			    $current_organisation = $current_organisations[0];
			    print_r($current_organisation);
			    $organisation_object = new YPOrganisation($current_organisation->organisation_wpref);
			    $mangopay_new_user_id = ypcf_init_mangopay_user($organisation_object->get_creator(), TRUE);
			    echo '<br />$mangopay_new_user_id ' . $mangopay_new_user_id . '<br />';
			    
			    if (isset($mangopay_new_user_id)) {
				    $transfer = ypcf_mangopay_transfer_project_organisation_to_user($organisation_object->get_creator(), $campaign_id, $amount, $amount_fees);
				    print_r($transfer);
			    }
		    }
		     */
		    ?>
		    
		    <?php 
		    //Gestion Yearn : remboursement de projet loupé
//			ypcf_mangopay_refund_project_to_user(8267);
		    ?>
			
			<?php
			/*
			//Annulation d'investissement
			$payment_id = 10077;
			$downloads = edd_get_payment_meta_downloads($payment_id); 
			$download_id = '';
			if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
			else $download_id = $downloads[0];
			
			//On transfère la somme sur mangopay
			$new_transfer = ypcf_mangopay_refund_project_to_user($payment_id);
			update_post_meta($payment_id, 'refund_transfer_id', $new_transfer->ID);

			//On passe le statut du paiement en refund
			edd_undo_purchase( $download_id, $payment_id );
			wp_update_post( array( 'ID' => $payment_id, 'post_status' => 'refunded' ) );

			//On passe le log à refunded pour que ce soit bien pris en compte au niveau du décompte en cours du projet
			$log_payment_id = 0;
			query_posts( array(
				'post_type'  => 'edd_log',
				'meta_query' => array (array(
				'key'   => '_edd_log_payment_id',
				'value' => $payment_id
				))
			)); 
			if (have_posts()) : while (have_posts()) : the_post(); $log_payment_id = get_the_ID(); endwhile; endif;
			wp_reset_query();
			wp_update_post( array( 'ID' => $log_payment_id, 'post_status' => 'refunded' ) );
			 * 
			 */
			?>
		    
		    <?php /* ?>
		    <h2>Liste des utilisateurs</h2>
		    <ul>
		    <?php 
			$users = get_users(array('meta_key' => 'mangopay_user_id'));
			foreach ($users as $user) {
			    $mp_user_id = get_user_meta($user->ID, 'mangopay_user_id', true);
			    $mp_user = ypcf_mangopay_get_user_by_id($mp_user_id);
			    ?>
			    <li><?php echo 'wp' . $user->ID . ' - mp' .$mp_user->ID . ' -> ' . $mp_user->FirstName . ' ' . $mp_user->LastName . ' (' . $mp_user->Email . ') :: €'.$mp_user->PersonalWalletAmount; ?></li>
			    <?php
			    /*
			    // Matthieu Pires : $mp_user->ID 2054788
			    if (false && $mp_user->ID == 2054788) {
				?>
				<ul>
				    <?php 
				    $operations = ypcf_mangopay_get_operations_by_user_id($mp_user->ID);
				    foreach ($operations as $operation) {
					?>
					<li><?php print_r($operation); ?></li>
					<?php
				    }
				    ?>
				</ul>
				<?php
			    }
			}
		    ?>
		    </ul>
		    <?php */ ?>
		    
		    <?php /* ?>
		    <h2>Liste des porte-monnaie utilisateurs</h2>
		    <ul>
		    <?php 
			$users = get_users(array('meta_key' => 'mangopay_wallet_id'));
			foreach ($users as $user) {
			    $mp_wallet_id = get_user_meta($user->ID, 'mangopay_wallet_id', true);
			    $mp_wallet = ypcf_mangopay_get_wallet_by_id($mp_wallet_id);
			    ?>
			    <li>
				<?php 
				    echo $mp_wallet->ID . ' - ' . $mp_wallet->Name . ' - ' . $mp_wallet->CollectedAmount . ' sur ' . $mp_wallet->RaisingGoalAmount;
				    echo ' - appartient à ' . $mp_wallet->Owners[0];
				?>
			    </li>
			    <?php
			}
		    ?>
		    </ul>
		    <?php */ ?>
		    
		    <h2>Liste des porte-monnaie projets</h2>
		    <ul>
		    <?php 
			global $wpdb;
			$posts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM ".$wpdb->postmeta." WHERE `meta_key` = 'mangopay_wallet_id'", "" ) );
			foreach ($posts as $post) {
			    $mp_wallet = ypcf_mangopay_get_wallet_by_id($post->meta_value);
			    ?>
			    <li>
				<?php 
				    echo 'wp' . $post->post_id . ' - ' . $mp_wallet->ID . ' - ' . $mp_wallet->Name . ' - ' . $mp_wallet->Amount . ' sur ' . $mp_wallet->RaisingGoalAmount;
				    echo ' - appartient à ' . $mp_wallet->Owners[0];
				?>
			    </li>
			    <?php
			}
		    ?>
		    </ul>
		    
		    <?php /* ?>
		    <h2>Liste des contributions</h2>
		    <ul>
		    <?php 
			$payments_data = get_payments_data();
			foreach ( $payments_data as $item ) {
			    $mp_id = edd_get_payment_key($item['ID']);
			    if (isset($mp_id) && $mp_id != "") {
				$mp_contribution = ypcf_mangopay_get_contribution_by_id($mp_id);
				?>
				<li>
				    <?php
				    echo $mp_contribution->ID . ' - De ' . $mp_contribution->UserID . ' à ' . $mp_contribution->WalletID . ' - ' . $mp_contribution->Amount . ' ('.$mp_contribution->IsCompleted.' ; '.$mp_contribution->IsSucceeded.')';
				    ?>  
				</li>
			    <?php
			    }
			}
		    ?>
		    </ul>
		    <?php */ ?>
		    
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>