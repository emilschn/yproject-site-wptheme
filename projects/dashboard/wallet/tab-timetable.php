<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
?>

<div id="tab-wallet-timetable" class="tab-content-large">
	<?php if ($campaign->funding_type() == 'fundingdonation'): ?>
		Ce projet n'est pas concerné.
		
	<?php else: ?>

		<?php if ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded): ?>

			<?php $declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign->ID ); ?>
			
			<div style="text-align: center;">
				<div>
					<table id="wdg-timetable" width="100%">
						<thead>
							<tr>
								<td>Echéance</td>
								<td>Mois</td>
								<td>CA déclaré</td>
								<td>Royalties</td>
								<td>Message</td>
								<td>Etat</td>
								<td>Info ajustement</td>
								<td>Montant ajustement</td>
								<td>Justificatif</td>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td>Echéance</td>
								<td>Mois</td>
								<td>CA déclaré</td>
								<td>Royalties</td>
								<td>Message</td>
								<td>Etat</td>
								<td>Info ajustement</td>
								<td>Montant ajustement</td>
								<td>Justificatif</td>
							</tr>
						</tfoot>

						<tbody>
							<?php foreach ( $declaration_list as $declaration_item ): ?>
								<?php global $declaration; $declaration = $declaration_item; ?>
								<?php locate_template( array("projects/dashboard/wallet/partial-timetable-line.php"), true, false ); ?>
							<?php endforeach; ?>
						</tbody>

					</table>
				</div>
			</div>
		
		<?php endif; ?>
		
	<?php endif; ?>
</div>