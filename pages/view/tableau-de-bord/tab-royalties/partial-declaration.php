<?php
global $declaration;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
			
<?php
$nb_fields = $page_controler->get_campaign()->get_turnover_per_declaration();
$declaration_message = $declaration->get_message();
?>
<div>
	<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>

	<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
		<form action="" method="POST" id="turnover-declaration"
				data-roi-percent="<?php echo $page_controler->get_campaign()->roi_percent_remaining(); ?>"
				data-costs-orga="<?php echo $page_controler->get_campaign()->get_costs_to_organization(); ?>"
				data-adjustment="<?php echo $declaration->get_adjustment_value(); ?>">
			<?php if ($nb_fields > 1): ?>
				<ul>
					<?php
					$date_due = new DateTime($declaration->date_due);
					$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
					?>
					<?php for ($i = 0; $i < $nb_fields; $i++): ?>
						<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <input type="text" name="turnover-<?php echo $i; ?>" id="turnover-<?php echo $i; ?>" /> &euro; HT</li>
						<?php $date_due->add(new DateInterval('P1M')); ?>
					<?php endfor; ?>
				</ul>

			<?php else: ?>
				<input type="text" name="turnover-total" id="turnover-total" />
			<?php endif; ?>
			<br /><br />
			
			<?php if ( $declaration->get_adjustment_validated() ): ?>
				<?php _e( "Suite &agrave; l'ajustement des comptes, nous avons constat&eacute; un diff&eacute;rentiel de chiffre d'affaires de"); ?> <strong><?php echo $declaration->get_adjustment_turnover_difference(); ?> &euro; HT</strong>.<br />
				<?php _e( "Vous avez donc un solde de" ); ?> <strong><?php echo $declaration->get_adjustment_value(); ?> &euro;</strong>.<br />
				<?php echo $declaration->get_adjustment_message( 'author' ); ?><br /><br />
			<?php endif; ?>

			Montant à régler : <strong><span class="amount-to-pay"><?php echo $declaration->get_adjustment_value(); ?></span> &euro;</strong>.
			<br /><br />

			<?php _e("Informez vos investisseurs de l'&eacute;tat d'avancement de votre projet et de votre chiffre d'affaires, ", 'yproject'); ?>
			<?php _e("et exprimez-leur clairement quels sont vos enjeux du moment.", 'yproject'); ?>
			<?php _e("Eux aussi sont int&eacute;ress&eacute;s &agrave; la r&eacute;ussite de votre projet et peuvent vous soutenir de nouveau pour avancer !", 'yproject'); ?>
			<?php _e("Nous leur transmettrons la nouvelle lors du versement des royalties.", 'yproject'); ?><br /><br />
			<textarea name="declaration-message"></textarea>
			<br /><br />
			
			<?php _e( "Ces informations sont utilis&eacute;es exclusivement &agrave; des fins statistiques. WE DO GOOD s'engage &agrave; ne pas les communiquer &agrave; des tiers.", 'yproject' ); ?><br />
			<?php _e( "Nombre de salari&eacute;s :", 'yproject' ); ?> <input type="number" name="employees-number" id="employees-number" value="0" /><br />
			<?php _e( "Autres financements :", 'yproject' ); ?><br />
			<textarea name="other-fundings"></textarea>
			<br /><br />

			<input type="hidden" name="action" value="save-turnover-declaration" />
			<input type="hidden" name="declaration-id" value="<?php echo $declaration->id; ?>" />
			<button type="submit" class="button red">Enregistrer la déclaration</button>
		</form>

	<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_payment ): ?>
		Chiffre d'affaires déclaré :
		<?php $declaration_turnover = $declaration->get_turnover(); ?>
		<?php if ($nb_fields > 1): ?>
			<ul>
				<?php
				$date_due = new DateTime($declaration->date_due);
				$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
				?>
				<?php for ($i = 0; $i < $nb_fields; $i++): ?>
					<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <?php echo UIHelpers::format_number( $declaration_turnover[$i] ); ?> &euro; HT</li>
					<?php $date_due->add(new DateInterval('P1M')); ?>
				<?php endfor; ?>
			</ul><br />

		<?php else: ?>
			<?php echo UIHelpers::format_number( $declaration_turnover[0] ); ?> &euro;<br />
		<?php endif; ?>

		<b>Total de chiffre d'affaires déclaré : </b><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro; HT<br /><br />

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro; (<?php echo UIHelpers::format_number( $page_controler->get_campaign()->roi_percent_remaining() ); ?> %)<br />
		<?php if ( $declaration->get_adjustment_validated() ): ?>
			<b>Ajustement : </b><?php echo UIHelpers::format_number( $declaration->get_adjustment_value() ); ?> &euro;<br />
		<?php endif; ?>
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br />
		<b>Montant à régler : </b><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message ne sera envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>
		
		Nombre de salari&eacute;s : <?php echo $declaration->employees_number; ?><br />
		Autres financements :<br />
		<?php echo $declaration->get_other_fundings(); ?><br /><br />

		<form action="" method="POST" enctype="">
			<input type="hidden" name="action" value="proceed_roi" />
			<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
			<input type="submit" name="payment_card" class="button red" value="<?php _e('Payer par carte', 'yproject'); ?>" />
		</form>
		<br />
		
		<?php
		$saved_mandates_list = $page_controler->get_campaign_organization()->get_lemonway_mandates();
		$last_mandate_status = '';
		if ( !empty( $saved_mandates_list ) ) {
			$last_mandate = end( $saved_mandates_list );
			$last_mandate_status = $last_mandate[ "S" ];
		}
		?>
		<?php if ( $last_mandate_status == 0 ): ?>
			<hr />
			<?php _e( "Afin de pouvoir payer par pr&eacute;l&eacute;vement automatique :", 'yproject' ); ?><br /><br />
			<form action="<?php echo admin_url( 'admin-post.php?action=organization_sign_mandate'); ?>" method="post" class="align-center">
				<input type="hidden" name="organization_id" value="<?php echo $page_controler->get_campaign_organization()->get_wpref(); ?>" />
				<button type="submit" class="button red"><?php _e( "Je signe l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
			</form>
			<br />
		<?php elseif ( $last_mandate_status == 5 || $last_mandate_status == 6 ): ?>
			<hr />
			<form action="" method="POST" enctype="">
				<input type="hidden" name="action" value="proceed_roi" />
				<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
				<input type="submit" name="payment_mandate" class="button red" value="<?php _e( "Payer par pr&eacute;l&eacute;vement automatique", 'yproject' ); ?>" />
			</form>
			<br />
		<?php endif; ?>

		<?php if ( $declaration->can_pay_with_wire() || $page_controler->can_access_admin() ): ?>
		<hr />

		Si vous souhaitez payer par virement bancaire, voici les informations dont vous aurez besoin :
		<ul>
			<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> LEMON WAY</li>
			<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
			<li><strong>BIC :</strong> BNPAFRPPIFE</li>
			<li>
				<strong><?php _e("Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject'); ?></strong> wedogood-<?php echo $page_controler->get_campaign_organization()->get_lemonway_id(); ?><br />
				<ul>
					<li><?php _e("Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject'); ?></li>
				</ul>
			</li>
		</ul>
		<br />

		Ensuite, cliquez sur "Payer par virement bancaire", et nous validerons ce paiement une fois la somme réceptionnée par notre prestataire.<br />
		<br />

		<form action="" method="POST" enctype="">
			<input type="hidden" name="action" value="proceed_roi" />
			<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
			<input type="submit" name="payment_bank_transfer" class="button red" value="<?php _e('Payer par virement bancaire', 'yproject'); ?>" />
		</form>
		<?php endif; ?>


	<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_transfer ||  $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
		Chiffre d'affaires déclaré :
		<?php $declaration_turnover = $declaration->get_turnover(); ?>
		<?php if ($nb_fields > 1): ?>
			<ul>
				<?php
				$date_due = new DateTime($declaration->date_due);
				$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
				?>
				<?php for ($i = 0; $i < $nb_fields; $i++): ?>
					<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <?php echo UIHelpers::format_number( $declaration_turnover[$i] ); ?> &euro;</li>
					<?php $date_due->add(new DateInterval('P1M')); ?>
				<?php endfor; ?>
			</ul><br />

		<?php else: ?>
			<?php echo UIHelpers::format_number( $declaration_turnover[0] ); ?> &euro;<br />
		<?php endif; ?>

		<b>Total de chiffre d'affaires déclaré : </b><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;<br /><br />

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro; (<?php echo UIHelpers::format_number( $page_controler->get_campaign()->roi_percent_remaining() ); ?> %)<br />
		<?php if ( $declaration->get_adjustment_validated() ): ?>
			<b>Ajustement : </b><?php echo UIHelpers::format_number( $declaration->get_adjustment_value() ); ?> &euro;<br />
		<?php endif; ?>
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message ne sera envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>
		
		Nombre de salari&eacute;s : <?php echo $declaration->employees_number; ?><br />
		Autres financements :<br />
		<?php echo $declaration->get_other_fundings(); ?><br /><br />

		<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
		Nous attendons la réception de la somme par notre prestataire de paiement et procèderons au versement par la suite.
		
		<?php if ( $page_controler->can_access_admin() ): ?>
			<br /><br />
			<form action="<?php echo admin_url( 'admin-post.php?action=roi_mark_transfer_received'); ?>" method="POST" class="align-center admin-theme-block">
				<input type="hidden" name="roi_declaration_id" value="<?php echo $declaration->id; ?>" />
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
				<button class="button"><?php _e( "Marquer le virement comme re&ccedil;u", 'yproject' ); ?></button>
			</form>

		<?php endif; ?>
		
		<?php else: ?>
			<?php if ( $declaration->get_amount_with_commission() > 0 ): ?>
			Votre paiement de <?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
			Le versement vers vos investisseurs est en cours.<br /><br />
			<?php $declaration->make_payment_certificate(); ?>
			<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue">Télécharger l'attestation de paiement</a>
			<?php else: ?>
			La cloture de votre déclaration est en cours.
			<?php endif; ?>
		
		<?php if ( $page_controler->can_access_admin() ): ?>
			<br /><br />
			<div class="align-center admin-theme-block">
				<a class="button transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>">Procéder aux versements</a>
			</div>

			<?php ob_start(); ?>
			<?php $previous_remaining_amount = $declaration->get_previous_remaining_amount(); ?>
			<h3><?php _e('Reverser aux utilisateurs', 'yproject'); ?></h3>
			<div id="lightbox-content">
				<div class="loading-image align-center"><img id="ajax-email-loader-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading.gif" alt="chargement" /></div>
				<div class="loading-content"></div>
				<div class="loading-form align-center hidden">
					<form action="" method="POST" id="proceed_roi_transfers_form">
						<label for="check_send_notifications"><input type="checkbox" name="send_notifications" class="field" id="check_send_notifications" data-id="check_send_notifications" data-type="check" value="1" <?php checked( !has_term( 'actifs', 'download_category', $page_controler->get_campaign_id() ) ); ?> /> Envoyer un mail automatique aux investisseurs (laisser décocher pour les projets d'actifs)</label><br />
						<?php if ( $previous_remaining_amount > 0 ): ?>
						<label for="check_transfer_remaining_amount"><input type="checkbox" name="transfer_remaining_amount" class="field" id="check_transfer_remaining_amount" data-id="check_transfer_remaining_amount" data-type="check" value="1" /> Verser les reliquats précédents (<?php echo $previous_remaining_amount; ?> &euro;)</label><br />
						<?php endif; ?>
						<br />
						<input type="hidden" id="hidden-roi-id" name="roi_id" class="field" data-id="roi_id" data-type="hidden" value="" />
						<input type="hidden" id="hidden-campaign-id" name="campaign_id" class="field" data-id="campaign_id" data-type="hidden" value="<?php echo $page_controler->get_campaign_id(); ?>" />
						
						<p id="proceed_roi_transfers_percent" class="align-center"></p>
						<?php DashboardUtility::create_save_button( 'proceed_roi_transfers', $page_controler->can_access_admin(), "Verser", "Versement", true ); ?>
					</form>
				</div>
			</div>
			<?php
			$lightbox_content = ob_get_contents();
			ob_end_clean();
			echo do_shortcode('[yproject_lightbox id="transfer-roi"]' . $lightbox_content . '[/yproject_lightbox]');
			?>

		<?php endif; ?>
		<?php endif; ?>

	<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
		Chiffre d'affaires déclaré :
		<?php $declaration_turnover = $declaration->get_turnover(); ?>
		<?php if ($nb_fields > 1): ?>
			<ul>
				<?php
				$date_due = new DateTime($declaration->date_due);
				$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
				?>
				<?php for ($i = 0; $i < $nb_fields; $i++): ?>
					<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <?php echo UIHelpers::format_number( $declaration_turnover[$i] ); ?> &euro;</li>
					<?php $date_due->add(new DateInterval('P1M')); ?>
				<?php endfor; ?>
			</ul><br />

		<?php else: ?>
			<?php echo UIHelpers::format_number( $declaration_turnover[0] ); ?> &euro;<br />
		<?php endif; ?>

		<b>Total de chiffre d'affaires déclaré : </b><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;<br /><br />

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro;<br />
		<?php if ( $declaration->get_adjustment_validated() ): ?>
			<b>Ajustement : </b><?php echo UIHelpers::format_number( $declaration->get_adjustment_value() ); ?> &euro;<br />
		<?php endif; ?>
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message n'a été envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>
		
		Nombre de salari&eacute;s : <?php echo $declaration->employees_number; ?><br />
		Autres financements :<br />
		<?php echo $declaration->get_other_fundings(); ?><br /><br />

		<?php if ( $declaration->get_turnover_total() > 0 ): ?>
			Votre paiement de <?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
			Vos investisseurs ont bien reçu leur retour sur investissement.<br /><br />
			<?php $declaration->make_payment_certificate(); ?>
			<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue">Télécharger l'attestation de paiement</a>
		<?php else: ?>
			Aucun paiement effectué.
		<?php endif; ?>

	<?php endif; ?>
</div>