<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
	locate_template( array("requests/investments.php"), true );
	$investments_list = wdg_get_project_investments($_GET['campaign_id'], TRUE);
	$campaign = $investments_list['campaign'];
	$is_campaign_over = ($campaign->campaign_status() == 'funded' || $campaign->campaign_status() == 'archive');
?>
		
<h3>Liste des investisseurs</h3>
<table class="wp-list-table" cellspacing="0">
    <thead style="background-color: #CCC;">
    <tr>
        <?php $colonnes = '<td>Utilisateur</td>
        <td>Nom</td>
        <td>Prénom</td>
        <td>Date de naissance</td>
        <td>Ville de naissance</td>
        <td>Nationalité</td>
        <td>Ville</td>
        <td>Adresse</td>
        <td>Code postal</td>
        <td>Pays</td>
        <td>Mail</td>
        <td>Téléphone</td>
	<td>Montant investi</td>
        <td>Date</td>
	<td>Type de paiement</td>
	<td>Etat du paiement</td>
	<td>Signature</td>';
        echo $colonnes;
        if ($is_campaign_over) { ?><td>Investissement</td><?php } ?>
    </tr>
    </thead>

    <tfoot style="background-color: #CCC;">
    <tr>
	<?php echo $colonnes;
        if ($is_campaign_over) { ?><td>Investissement</td><?php } ?>
    </tr>
    </tfoot>

    <tbody id="the-list">
	<?php
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
                
		?>
		<tr style="background-color: <?php echo $bgcolor; ?>">
                    <td><?php echo bp_core_get_userlink($item['user']); ?></td>
                    <td><?php echo $user_data->last_name;?></td>
                    <td><?php echo $user_data->first_name;?></td>
                    <td><?php echo $user_data->user_birthday_day.'/'.$user_data->user_birthday_month.'/'.$user_data->user_birthday_year;?></td>
                    <td><?php echo $user_data->user_birthplace;?></td>
                    <td><?php echo $user_data->user_nationality;?></td>
                    <td><?php echo $user_data->user_city;?></td>
                    <td><?php echo $user_data->user_address;?></td>
                    <td><?php echo $user_data->user_postal_code;?></td>
                    <td><?php echo $user_data->user_country;?></td>
                    <td><?php echo $user_data->user_email; ?></td>
                    <td><?php echo $user_data->user_mobile_phone;?></td>
                    <td><?php echo $item['amount']; ?>&euro;</td>
                    <td><?php echo date_i18n( /*get_option('date_format')*/ 'd/m/Y', strtotime( get_post_field( 'post_date', $item['ID'] ) ) ); ?></td>
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

<?php
}
?>