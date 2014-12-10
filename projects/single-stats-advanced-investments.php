<?php 
global $disable_logs; $disable_logs = TRUE;

if (YPProjectLib::current_user_can_edit($_GET['campaign_id'])) {
	locate_template( array("requests/investments.php"), true );
	locate_template( array("projects/stats-investments-public.php"), true ); 
	$investments_list = wdg_get_project_investments($_GET['campaign_id'], TRUE);
	print_investments($investments_list);
?>
		
<h3>Liste des investisseurs</h3>
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
	$invest_mail_list = '';
	$i = -1;
	foreach ( $investments_list['payments_data'] as $item ) {
	    if ($item['status'] == 'publish' || $item['status'] == 'refunded') {
		$i++;
		$bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";

		$post_invest = get_post($item['ID']);
		$mangopay_is_succeeded = (isset($item['mangopay_contribution']->IsSucceeded) && $item['mangopay_contribution']->IsSucceeded) ? 'Oui' : 'Non';
		$user_data = get_userdata($item['user']);
		$invest_mail_list .= ', ' . $user_data->user_email;
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

<?php if (current_user_can('manage_options')): ?>
<div style="max-width: 500px">
	<?php echo '<br /><br />'.$invest_mail_list; ?>
</div>
<?php endif; ?>

<?php
}
?>