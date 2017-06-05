<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author, $declaration;
?>
			
<?php
$nb_fields = $campaign->get_turnover_per_declaration();
$declaration_message = $declaration->get_message();
?>
<h4><?php echo $declaration->get_formatted_date(); ?></h4>
<div>
	<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>

	<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
		<form action="" method="POST" id="turnover-declaration"
				data-roi-percent="<?php echo $campaign->roi_percent(); ?>"
				data-costs-orga="<?php echo $campaign->get_costs_to_organization(); ?>"
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
				<?php _e( "Suite &agrave; l'ajustement des comptes, vous avez un solde de" ); ?> <?php echo $declaration->get_adjustment_value(); ?> &euro;.<br />
				<?php echo $declaration->get_adjustment_message( 'author' ); ?><br /><br />
			<?php endif; ?>

			Somme à verser : <span class="amount-to-pay"><?php echo $declaration->get_adjustment_value(); ?></span> &euro;.
			<br /><br />

			<?php _e("Informez vos investisseurs de l'&eacute;tat d'avancement de votre projet et de votre chiffre d'affaires, ", 'yproject'); ?>
			<?php _e("et exprimez-leur clairement quels sont vos enjeux du moment.", 'yproject'); ?>
			<?php _e("Eux aussi sont int&eacute;ress&eacute;s &agrave; la r&eacute;ussite de votre projet et peuvent vous soutenir de nouveau pour avancer !", 'yproject'); ?>
			<?php _e("Nous leur transmettrons la nouvelle lors du versement des royalties.", 'yproject'); ?><br /><br />
			<textarea name="declaration-message"></textarea>
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

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro; (<?php echo UIHelpers::format_number( $campaign->roi_percent() ); ?> %)<br />
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br />
		<b>Montant à verser : </b><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message ne sera envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>

		<form action="" method="POST" enctype="">
			<input type="hidden" name="action" value="proceed_roi" />
			<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
			<input type="submit" name="payment_card" class="button red" value="<?php _e('Payer par carte', 'yproject'); ?>" />
		</form>
		<br />


		<?php if ( $declaration->can_pay_with_wire() ): ?>
		<hr />

		Si vous souhaitez payer par virement bancaire, voici les informations dont vous aurez besoin :
		<ul>
			<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> LEMON WAY</li>
			<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
			<li><strong>BIC :</strong> BNPAFRPPIFE</li>
			<li>
				<strong><?php _e("Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject'); ?></strong> wedogood-<?php echo $organization_obj->get_lemonway_id(); ?><br />
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

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro; (<?php echo UIHelpers::format_number( $campaign->roi_percent() ); ?> %)<br />
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message ne sera envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>

		<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
		Nous attendons la réception de la somme par notre prestataire de paiement et procèderons au versement par la suite.
		<?php else: ?>
		Votre paiement de <?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
		Le versement vers vos investisseurs est en cours.<br /><br />
		<?php $declaration->make_payment_certificate(); ?>
		<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue">Télécharger l'attestation de paiement</a>

		<?php if ($is_admin): ?>
			<br /><br />
			<a href="#transfer-roi" class="button red transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>">Procéder aux versements</a>

			<?php ob_start(); ?>
			<h3><?php _e('Reverser aux utilisateurs', 'yproject'); ?></h3>
			<div id="lightbox-content">
				<div class="loading-image align-center"><img id="ajax-email-loader-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading.gif" alt="chargement" /></div>
				<div class="loading-content"></div>
				<div class="loading-form align-center hidden">
					<form action="" method="POST">
						<input type="checkbox" name="send_notifications" value="1" checked="checked" /> Envoyer un mail automatique aux investisseurs<br /><br />
						<input type="hidden" name="action" value="proceed_roi_transfers" />
						<input type="hidden" id="hidden-roi-id" name="roi_id" value="" />
						<input type="submit" class="button red" value="Transférer" />
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

		<b>Total du versement : </b><?php echo UIHelpers::format_number( $declaration->amount ); ?> &euro; (<?php echo UIHelpers::format_number( $campaign->roi_percent() ); ?> %)<br />
		<b>Frais de gestion : </b><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay() ); ?> &euro;<br /><br />

		<?php if ( empty( $declaration_message ) ): ?>
		Aucun message n'a été envoyé aux investisseurs.<br /><br />
		<?php else: ?>
		<b>Ce message sera envoyé à vos investisseurs :</b><br />
		<?php echo $declaration->get_message(); ?><br /><br />
		<?php endif; ?>

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