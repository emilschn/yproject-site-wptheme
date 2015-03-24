<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
	locate_template( array("requests/investments.php"), true );
	locate_template( array("projects/stats-investments-public.php"), true ); 
	$investments_list = wdg_get_project_investments($_GET['campaign_id'], TRUE);
	$campaign = $investments_list['campaign'];
	$is_campaign_over = ($campaign->campaign_status() == 'funded' || $campaign->campaign_status() == 'archive');
	print_investments($investments_list);
?>
		
<h3>Liste des investisseurs</h3>
<table class="wp-list-table" cellspacing="0">
    <thead style="background-color: #CCC;">
    <tr>
	<td>Utilisateur</td>
	<td>Date</td>
	<td>Montant</td>
	<td>Type de paiement</td>
	<td>Etat du paiement</td>
	<td>Signature</td>
	<?php if ($is_campaign_over) { ?><td>Investissement</td><?php } ?>
    </tr>
    </thead>

    <tfoot style="background-color: #CCC;">
    <tr>
	<td>Utilisateur</td>
	<td>Date</td>
	<td>Montant</td>
	<td>Type de paiement</td>
	<td>Etat du paiement</td>
	<td>Signature</td>
	<?php if ($is_campaign_over) { ?><td>Investissement</td><?php } ?>
    </tr>
    </tfoot>

    <tbody id="the-list">
	<?php
	$invest_mail_list = '';
	$i = -1;
	foreach ( $investments_list['payments_data'] as $item ) {
//	    if ($item['status'] == 'publish' || $item['status'] == 'refunded') {
		$i++;
		$bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";

		$post_invest = get_post($item['ID']);
		$mangopay_id = edd_get_payment_key($item['ID']);
		$payment_type = 'Carte';
		$payment_state = edd_get_payment_status( $post_invest, true );;
		if (strpos($mangopay_id, 'wire_') !== FALSE) {
			$payment_type = 'Virement';
			$contribution_id = substr($mangopay_id, 5);
			$mangopay_contribution = ypcf_mangopay_get_withdrawalcontribution_by_id($contribution_id);
			$mangopay_is_completed = (isset($mangopay_contribution));
			$mangopay_is_succeeded = (isset($mangopay_contribution) && $mangopay_contribution->Status == 'ACCEPTED');
//			if ($mangopay_is_succeeded) $payment_state = 'Validé';
//			else if ($mangopay_is_completed) $payment_state = 'Echoué';
		} else {
			$mangopay_contribution = ypcf_mangopay_get_contribution_by_id($mangopay_id);
			$mangopay_is_completed = (isset($mangopay_contribution->IsCompleted) && $mangopay_contribution->IsCompleted);
			$mangopay_is_succeeded = (isset($mangopay_contribution->IsSucceeded) && $mangopay_contribution->IsSucceeded);
//			if ($mangopay_is_succeeded) $payment_state = 'Validé';
//			else if ($mangopay_is_completed) $payment_state = 'Echoué';
		}
		$investment_state = 'Validé';
		if ($campaign->campaign_status() == 'archive') {
		    $investment_state = 'Annulé';
			
		    $refund_id = get_post_meta($item['ID'], 'refund_id', TRUE);
		    if (isset($refund_id) && !empty($refund_id)) {
			$refund_obj = ypcf_mangopay_get_refund_by_id($refund_id);
			$investment_state = 'Remboursement en cours';
			if ($refund_obj->IsCompleted) {
			    if ($refund_obj->IsSucceeded) {
				$investment_state = 'Remboursé';
			    } else {
				$investment_state = 'Remboursement échoué';
			    }
			}
			
		    } else {
			$refund_id = get_post_meta($item['ID'], 'refund_wire_id', TRUE);
			if (isset($refund_id) && !empty($refund_id)) {
			    $investment_state = 'Remboursé';
			}
		    }
		}
		
		$user_data = get_userdata($item['user']);
		$invest_mail_list .= ', ' . $user_data->user_email;
		?>
		<tr style="background-color: <?php echo $bgcolor; ?>">
		    <td><?php echo bp_core_get_userlink($item['user']); ?></td>
		    <td><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $item['ID'] ) ) ); ?></td>
		    <td><?php echo $item['amount']; ?>&euro;</td>
		    <td><?php echo $payment_type; ?></td>
		    <td><?php echo $payment_state; ?></td>
		    <td <?php if ($item['signsquid_status'] != 'Agreed') echo 'style="background-color: #EF876D"'; ?>><?php echo $item['signsquid_status_text']; ?></td>
		    <?php if ($is_campaign_over) { ?><td><?php echo $investment_state; ?></td><?php } ?>
		</tr>
		<?php
//	    }
	}
	?>
    </tbody>
</table>

<?php if (current_user_can('manage_options')): ?>
<h3>E-mails des investisseurs (uniquement visible par WDG)</h3>
<div style="max-width: 500px">
	<?php echo '<br /><br />'.$invest_mail_list; ?>
</div>
<?php endif; ?>

<?php
}
?>