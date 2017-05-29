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
		
		
			<script type="text/javascript">
				jQuery(document).ready( function($) {
					// Ajoute mise en page et interactions du tableau
					// Ajoute un champ de filtre à chaque colonne dans le footer
					$('#wdg-timetable tfoot td').each( function () {
						$(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
					} );

					// Ajoute les actions de filtrage
					$("#wdg-timetable tfoot input").on( 'keyup change', function () {
						table
							.column( $(this).parent().index()+':visible' )
							.search( this.value )
							.draw();
					} );

					//Récupère le tri par défaut 
					sortColumn = 0;

					var table = $('#wdg-timetable').DataTable({
						order: [[ sortColumn, "asc" ]], //Colonne à trier (date)

						dom: 'RC<"clear">lfrtip',
						lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
						iDisplayLength: 50,//nombre d'éléments par défaut

						//Boutons de sélection de colonnes
						colVis: {
							buttonText: "Afficher/cacher colonnes",
							restore: "Restaurer",
							showAll: "Tout afficher",
							showNone: "Tout cacher",
							overlayFade: 100
						},
						language: {
							"sProcessing":     "Traitement en cours...",
							"sSearch":         "Rechercher&nbsp;:",
							"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
							"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
							"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
							"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
							"sInfoPostFix":    "",
							"sLoadingRecords": "Chargement en cours...",
							"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
							"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
							"oPaginate": {
								"sFirst":      "Premier",
								"sPrevious":   "Pr&eacute;c&eacute;dent",
								"sNext":       "Suivant",
								"sLast":       "Dernier"
							},
							"oAria": {
								"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
								"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
							}
						}
					});
				});
			</script>
		
		<?php endif; ?>
		
	<?php endif; ?>
</div>