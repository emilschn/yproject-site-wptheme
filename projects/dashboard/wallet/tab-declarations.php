<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
?>

<div id="tab-wallet-declarations" class="tab-content">
	<?php if ($campaign->funding_type() == 'fundingdonation'): ?>
		Ce projet n'est pas concerné.
		
	<?php else: ?>
		
		<?php
		// On affiche toujours une information liée au planning des actions, au début de la page :
			// - signez votre autorisation de prélèvement automatique > à afficher dès la création du projet ?
			// - déclarez votre chiffre d’affaires du 1/--/---- au 10/--/---- et payez vos royalties” > à afficher une fois la campagne terminée et le prélèvement SEPA signé. (info lié à la date de début de prise en compte des royalties écrite dans le contrat)
			// - transmettez vos comptes ou votre attestation comptable pour le réajustement annuel de vos déclarations > à afficher toute l’année ? sauf entre la signature du prélèvement automatique et la 1ère déclaration.

		?>
		<?php locate_template( array("projects/dashboard/wallet/partial-mandate.php"), true ); ?>
		
		
		<?php global $last_mandate_status; // Si la signature du mandat n'est pas bloquante, on affiche la suite ?>
		<?php if ( !$campaign->is_forced_mandate() || $last_mandate_status == 5 || $last_mandate_status == 6 ): ?>
		
			<?php // Si il y a une déclaration (ou plusieurs), on l'affiche ?>
			<?php if ( $campaign->has_current_roi_declaration() ): ?>
			<?php locate_template( array("projects/dashboard/wallet/partial-declaration-info-form.php"), true ); ?>

			<?php $declaration_list = $campaign->get_current_roi_declarations(); ?>
			<ul class="payment-list">
				<?php foreach ( $declaration_list as $declaration_item ): ?>
					<li>
					<?php global $declaration; $declaration = $declaration_item; ?>
					<h4><?php echo $declaration->get_formatted_date(); ?></h4>
					<?php if ( $is_admin || $declaration->get_adjustment_needed() ): ?>
					<?php locate_template( array("projects/dashboard/wallet/partial-adjustment.php"), true, false ); ?>
					<?php endif; ?>
					<?php if ( $is_admin || !$declaration->get_adjustment_needed() || $declaration->get_adjustment_validated() ): ?>
					<?php locate_template( array("projects/dashboard/wallet/partial-declaration.php"), true, false ); ?>
					<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

		
			<?php // Sans déclaration en cours, on affiche le formulaire permettant de procéder à l'ajustement de la prochaine ?>
			<?php elseif ( $campaign->has_next_roi_declaration() ): ?>
				<?php global $declaration; $declaration = $campaign->get_next_roi_declaration(); ?>
		
				<?php if ( !$is_admin && !$declaration->get_adjustment_needed() ): ?>
				<?php _e( "Vous &ecirc;tes &agrave; jour dans vos d&eacute;clarations.", 'yproject' ); ?>
		
				<?php else: ?>
				<?php locate_template( array("projects/dashboard/wallet/partial-adjustment.php"), true, false ); ?>

				<?php endif; ?>
		
			<?php endif; ?>
		
		<?php endif; ?>

	<?php endif; ?>
</div>