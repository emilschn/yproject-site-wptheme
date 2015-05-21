<?php 
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
		
		    <h1>Stats Mangopay</h1>
		
		    <?php
			/*
			//Virement wallet milgoulle (mpid 2054791) vers matthieu (wpid 63 ; mpid 2054788) & vers wdg (wpid 60 ; mpid 3554029)
			//Pour Milgoulle : 5302 - 30 (existant) = 5272
			//Pour WDG : 638
			if (false) {
			    $current_user = get_user_by('id', 60);
			    $campaign_id = 1241;
			    ypcf_mangopay_transfer_project_to_user($current_user, $campaign_id, 638);
			    //TODO : faire autrement la prochaine fois - gérer les client amount fees
			}
			 * 
			 */
		    ?>
		    
		    <?php /*
		    //Remboursements GdB
		    $user = get_userdata(18);
		    $download_id = 3268;
		    $amount = 100;
		    $payment_id = 3433;
		    $new_transfer = ypcf_mangopay_transfer_project_to_user($user, $download_id, $amount);
		    update_post_meta($payment_id, 'refund_wire_id', $new_transfer->ID);
		    print_r($new_transfer);
		    */ ?>
		
		    <?php
		    /*
		    //Gestion MCB
		    print_r("--------------");
		    $id_wallet_old = 10350063;
		    $mp_wallet_old = ypcf_mangopay_get_wallet_by_id(10350063);
		    print_r($mp_wallet_old);
		    
		    $campaign_id = 3410;
		    $amount = 2500;
		    $amount_fees = 250;
		    $api_project_id = BoppLibHelpers::get_api_project_id($campaign_id);
		    echo '$api_project_id ' . $api_project_id . '<br />';
		    $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
		    print_r($current_organisations);
		    if (count($current_organisations) > 0) {
			    echo 'hop<br />';
			    $current_organisation = $current_organisations[0];
			    print_r($current_organisation);
			    $organisation_object = new YPOrganisation($current_organisation->organisation_wpref);
			    $mangopay_new_user_id = ypcf_init_mangopay_user($organisation_object->get_creator(), TRUE);
			    echo '$mangopay_new_user_id ' . $mangopay_new_user_id . '<br />';
			    
			    if (isset($mangopay_new_user_id)) {
				    echo 'hop2<br />';
				    $transfer = ypcf_mangopay_transfer_project_to_user($organisation_object->get_creator(), $campaign_id, $amount, $amount_fees);
				    print_r($transfer);
			    }
		    }
		    print_r("--------------");
		     * 
		     */
		    
//					    "PayerID" : 10350060, 
//					    "PayerWalletID" : 10350063,
//					    "BeneficiaryID" : 15573055,
//					    "BeneficiaryWalletID" : 0,
		    /*$mangopay_newtransfer = request('transfers', 'POST', '{ 
					"PayerID" : 10350060, 
					"PayerWalletID" : 10350063,
					"BeneficiaryID" : 15573055,
					"BeneficiaryWalletID" : 0,
					"Amount" : 250000,
					"ClientFeeAmount" : 250
				    }');
		    $mangopay_newtransfer = request('transfers', 'POST', '{ 
					"PayerID" : 10350060, 
					"PayerWalletID" : 10350063,
					"BeneficiaryID" : 15573055,
					"BeneficiaryWalletID" : 0,
					"Amount" : 0,
					"ClientFeeAmount" : 24750
				    }');*/
		    
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
			$posts = $wpdb->get_results( $wpdb->prepare("SELECT `meta_value` FROM ".$wpdb->postmeta." WHERE `meta_key` = 'mangopay_wallet_id'", "" ) );
			foreach ($posts as $post) {
			    $mp_wallet = ypcf_mangopay_get_wallet_by_id($post->meta_value);
			    ?>
			    <li>
				<?php 
				    echo 'wp' . $post->ID . ' - ' . $mp_wallet->ID . ' - ' . $mp_wallet->Name . ' - ' . $mp_wallet->Amount . ' sur ' . $mp_wallet->RaisingGoalAmount;
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