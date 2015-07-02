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
		    //Gestion Hubmode
		    $campaign_id = 4492;
		    $amount = 9967.28;
		    $amount_fees = 1232.72;
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
				    $transfer = ypcf_mangopay_transfer_project_to_user($organisation_object->get_creator(), $campaign_id, $amount, $amount_fees);
				    print_r($transfer);
			    }
		    }
		     *
		    //Gestion Navlab
		    $campaign_id = 4171;
		    $amount = 9950;
		    $amount_fees = 1200;
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
				    $transfer = ypcf_mangopay_transfer_project_to_user($organisation_object->get_creator(), $campaign_id, $amount, $amount_fees);
				    print_r($transfer);
			    }
		    }
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