<?php 
	$post_campaign = get_post($_GET['campaign_id']);
	$campaign = atcf_get_campaign( $post_campaign );
	$payments_data = $campaign->payments_data();
	
	$count_validate_investments = 0;
	$count_age = 0;
	$count_female = 0;
	$count_invest = 0;
	$amounts_array = array();
	foreach ( $payments_data as $item ) {
	    if (($item['status'] == 'publish') && (isset($item['mangopay_contribution']->IsSucceeded) && $item['mangopay_contribution']->IsSucceeded) && $item['signsquid_status'] == 'Agreed') {
		$count_validate_investments++;
		$invest_user = get_user_by('id', $item['user']);
		$count_age += ypcf_get_age($invest_user->get('user_birthday_day'), $invest_user->get('user_birthday_month'), $invest_user->get('user_birthday_year'));
		if ($invest_user->get('user_gender') == "female") $count_female++;
		$count_invest += $item['amount'];
		$amounts_array[] = $item['amount'];
	    }
	}
	asort($amounts_array);
	
	$average_age = 0;
	$percent_female = 0;
	$percent_male = 0;
	$average_invest = 0;
	$median_invest = 0;
	if ($count_validate_investments) {
	    $average_age = round($count_age / $count_validate_investments, 1);
	    $percent_female = round($count_female / $count_validate_investments * 100);
	    $percent_male = 100 - $percent_female;
	    $average_invest = round($count_invest / $count_validate_investments, 2);
	    $median_invest = $amounts_array[0];
	    if ($count_validate_investments > 2) $median_invest = $amounts_array[round(($count_validate_investments + 1) / 2) - 1];
	}
	    
?>

<h2>G&eacute;n&eacute;ral</h2>
<strong><?php echo $count_validate_investments; ?></strong> investissements valid&eacute;s.<br />
Les investisseurs ont <strong><?php echo $average_age; ?></strong> ans de moyenne.<br />
Ce sont <strong><?php echo $percent_female; ?>%</strong> de femmes et <strong><?php echo $percent_male; ?>%</strong> d&apos;hommes.<br />
<strong><?php echo $campaign->days_remaining(); ?></strong> jours restants.<br />
Investissement moyen par personne : <strong><?php echo $average_invest; ?></strong>&euro;<br />
Investissement m&eacute;dian : <strong><?php echo $median_invest; ?></strong>&euro;


<h2>Liste des investisseurs</h2>
<table class="wp-list-table" cellspacing="0">
    <thead style="background-color: #CCC;">
    <tr>
	<td>Utilisateur</td>
	<td>Date</td>
	<td>Montant</td>
	<td>Paiement</td>
	<td>Paiement Mangopay</td>
	<td>Signature</td>
    </tr>
    </thead>

    <tfoot style="background-color: #CCC;">
    <tr>
	<td>Utilisateur</td>
	<td>Date</td>
	<td>Montant</td>
	<td>Paiement</td>
	<td>Paiement Mangopay</td>
	<td>Signature</td>
    </tr>
    </tfoot>

    <tbody id="the-list">
	<?php
	$i = -1;
	foreach ( $payments_data as $item ) {
	    if ($item['status'] == 'publish' || $item['status'] == 'refunded') {
		$i++;
		$bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";

		$post_invest = get_post($item['ID']);
		$mangopay_is_succeeded = (isset($item['mangopay_contribution']->IsSucceeded) && $item['mangopay_contribution']->IsSucceeded) ? 'Oui' : 'Non';

		?>
		<tr style="background-color: <?php echo $bgcolor; ?>">
		    <td><?php echo bp_core_get_userlink($item['user']); ?></td>
		    <td><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $item['ID'] ) ) ); ?></td>
		    <td><?php echo $item['amount']; ?>&euro;</td>
		    <td <?php if (edd_get_payment_status( $post_invest, true ) == "Echec") echo 'style="background-color: #EF876D"'; ?>><?php echo edd_get_payment_status( $post_invest, true ); ?></td>
		    <td <?php if (!(isset($item['mangopay_contribution']->IsSucceeded) && $item['mangopay_contribution']->IsSucceeded)) echo 'style="background-color: #EF876D"'; ?>><?php echo $mangopay_is_succeeded; ?></td>
		    <td <?php if ($item['signsquid_status'] != 'Agreed') echo 'style="background-color: #EF876D"'; ?>><?php echo $item['signsquid_status_text']; ?></td>
		</tr>
		<?php
	    }
	}
	?>
    </tbody>
</table>