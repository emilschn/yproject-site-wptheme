<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$saved_mandates_list = $page_controler->get_campaign_organization()->get_lemonway_mandates();
$last_mandate_status = '';
$last_mandate_id = FALSE;
if ( !empty( $saved_mandates_list ) ) {
	$last_mandate = end( $saved_mandates_list );
	$last_mandate_status = $last_mandate[ "S" ];
	$last_mandate_id = $last_mandate[ "ID" ];
}
?>

<div>
<?php if ( $page_controler->get_return_lemonway_card() == TRUE ): ?>
	<?php
	$msg_validation_payment = __("Paiement effectu&eacute;", "yproject");
	echo do_shortcode('[yproject_lightbox id="msg-validation-payment" scrolltop="1" msgtype="valid" autoopen="1"]'.$msg_validation_payment.'[/yproject_lightbox]');
	?>
<?php elseif ( $page_controler->get_return_lemonway_card() !== FALSE ): ?>
	<?php
	$msg_error_payment = __("Il y a eu une erreur au cours de votre paiement.", "yproject");
	echo do_shortcode('[yproject_lightbox id="msg-validation-payment" scrolltop="1" msgtype="error" autoopen="1"]'.$msg_error_payment.'[/yproject_lightbox]');
	?>
<?php endif; ?>
</div>

<h2><?php _e( "Royalties", 'yproject' ); ?></h2>
<div id="tab-wallet-timetable" class="tab-content-large">
	<?php if ($page_controler->get_campaign()->funding_type() == 'fundingdonation'): ?>
		Ce projet n'est pas concerné.
		
	<?php else: ?>

		<?php if ($page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed): ?>
		
		
			<?php
			// On affiche toujours une information liée au planning des actions, au début de la page :
				// - signez votre autorisation de prélèvement automatique > à afficher dès la création du projet ?
				// - déclarez votre chiffre d’affaires du 1/--/---- au 10/--/---- et payez vos royalties” > à afficher une fois la campagne terminée et le prélèvement SEPA signé. (info lié à la date de début de prise en compte des royalties écrite dans le contrat)
				// - transmettez vos comptes ou votre attestation comptable pour le réajustement annuel de vos déclarations > à afficher toute l’année ? sauf entre la signature du prélèvement automatique et la 1ère déclaration.
			?>
			<?php // Si la signature du mandat n'est pas bloquante, on affiche la suite ?>
			<?php if ( !$page_controler->get_campaign()->is_forced_mandate() || $last_mandate_status == 5 || $last_mandate_status == 6 ): ?>

				<div class="db-form v3 full center bg-white">
					<?php // Si il y a une déclaration (ou plusieurs), on l'affiche ?>
					<?php if ( $page_controler->get_campaign()->has_current_roi_declaration() ): ?>
					<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/partial-declaration-info-form.php' ), true ); ?>

					<?php $declaration_list = $page_controler->get_campaign()->get_current_roi_declarations(); ?>
					<ul class="payment-list">
						<?php foreach ( $declaration_list as $declaration_item ): ?>
							<li>
							<?php global $declaration; $declaration = $declaration_item; ?>
							<h4><?php echo $declaration->get_formatted_date(); ?></h4>
							<?php if ( $page_controler->can_access_admin() || $declaration->get_adjustment_needed() ): ?>
							<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/partial-adjustment.php' ), true, false ); ?>
							<?php endif; ?>
							<?php if ( $page_controler->can_access_admin() || !$declaration->get_adjustment_needed() || $declaration->get_adjustment_validated() ): ?>
							<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/partial-declaration.php' ), true, false ); ?>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>


					<?php // Sans déclaration en cours, on affiche le formulaire permettant de procéder à l'ajustement de la prochaine ?>
					<?php elseif ( $page_controler->get_campaign()->has_next_roi_declaration() ): ?>
						<?php global $declaration; $declaration = $page_controler->get_campaign()->get_next_roi_declaration(); ?>

						<?php if ( !$page_controler->can_access_admin() && !$declaration->get_adjustment_needed() ): ?>
						<?php _e( "Vous &ecirc;tes &agrave; jour dans vos d&eacute;clarations.", 'yproject' ); ?>

						<?php else: ?>
						<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/partial-adjustment.php' ), true, false ); ?>

						<?php endif; ?>

					<?php endif; ?>
				</div>

			<?php endif; ?>
		
		
		
		
			<?php if ( $page_controler->can_access_admin() ): ?>
		
				<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=declaration_auto_generate'); ?>" class="align-center admin-theme-block">
					
					<br />
					<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>">
					<input type="hidden" name="month_count" value="3">
					Nombre de déclarations (ne rien préciser si procédure normale) : <input type="text" name="declarations_count"><br>
					<button type="submit" class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer les &eacute;ch&eacute;ances manquantes", 'yproject' ); ?></button>
					<br /><br />

				</form>
				<br /><br />
		
			<?php endif; ?>
			

			<?php $declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $page_controler->get_campaign_id() ); ?>
				
			<?php if ( count( $declaration_list ) == 0 ): ?>
				<?php _e( "Retrouvez prochainement ici le suivi de vos paiements de royalties.", 'yproject' ); ?>
				
			<?php else: ?>
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
									<td>Info ajustement ent.</td>
									<td>Info ajustement inv.</td>
									<td>Montant ajustement</td>
									<td>Justificatif</td>
									<td>Facture</td>
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
									<td>Info ajustement ent.</td>
									<td>Info ajustement inv.</td>
									<td>Montant ajustement</td>
									<td>Justificatif</td>
									<td>Facture</td>
								</tr>
							</tfoot>

							<tbody>
								<?php foreach ( $declaration_list as $declaration_item ): ?>
									<?php global $declaration; $declaration = $declaration_item; ?>
									<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/partial-royalties-line.php' ), true, false ); ?>
								<?php endforeach; ?>
							</tbody>

						</table>
					</div>
				</div>
			<?php endif; ?>
		
		<?php else: ?>
			<p class="align-center">
				<?php _e( "Retrouvez prochainement ici le suivi de vos paiements de royalties.", 'yproject' ); ?>
			</p>

		<?php endif; ?>
		
	<?php endif; ?>
</div>