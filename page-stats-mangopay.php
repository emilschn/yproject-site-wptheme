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
			
			<?php // Refaire des transferts de ROI
			//$roi_declaration = new WDGROIDeclaration(1); $roi_declaration->redo_transfers(); 
			?>
			
			<?php // Infos sur une transaction
			//$lw_transaction_result = LemonwayLib::get_transaction_by_id( 'INVU7C11182TS39068' ); print_r($lw_transaction_result); 
			?>
			
			<?php // Init user sur LW
			//$wdgUser = new WDGUser(4155); $wdgUser->register_lemonway(); 
			?>
			
			<?php // Virements reçus depuis 10 jours
			/*
			$date = new DateTime();
			$date->sub( new DateInterval('P10D') );
			$transactions_list = LemonwayLib::get_transactions_wire_since( $date->getTimestamp() );
			print_r($transactions_list);
			 *
			 */
			?>
			
			<?php // Regénération d'un contrat d'investissement
//			getNewPdfToSign($project_id, $payment_id, $user_id);
//			getNewPdfToSign(11182, 11943, 4298);
//			getNewPdfToSign(11182, 11740, 4246);
//			getNewPdfToSign(11182, 11595, 4055);
			?>
		    
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>