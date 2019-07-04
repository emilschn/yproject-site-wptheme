<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $declaration;
$adjustments = $declaration->get_adjustments();
$today_date = new DateTime();
$date_due = new DateTime( $declaration->date_due );
$date_interval = $today_date->diff( $date_due );
$is_future = $date_due > $today_date && $date_interval->format( '%a' ) > $date_due->format( 'd' );
if ( $is_future ) {
	$class_status = 'single-line';
	$label_status = __( "A venir", 'yproject' );
} else {
	$class_status = ( $date_due < $today_date ) ? 'error' : '';
	$label_status = ( $date_due < $today_date ) ? __( "En retard", 'yproject' ) : __( "En cours", 'yproject' );
	
	if ( $declaration->get_status() == WDGROIDeclaration::$status_payment ) {
		$label_status .= "<br>" . __( "En attente de paiement", 'yproject' );
	} elseif ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ) {
		$label_status .= "<br>" . __( "En attente de virement", 'yproject' );
	} elseif ( $declaration->get_status() == WDGROIDeclaration::$status_transfer ) {
		$label_status .= "<br>" . __( "En cours de versement", 'yproject' );
	} else {
		$class_status .= ' single-line';
	}
}
?>
			
<?php
$nb_fields = $page_controler->get_campaign()->get_turnover_per_declaration();
$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
?>

<div id="declaration-item-<?php echo $declaration->id; ?>" class="declaration-item">
	<div class="single-line">
		<?php echo $declaration->get_formatted_date( 'due' ); ?>
	</div>
	<div class="align-center <?php echo $class_status; ?>">
		<?php echo $label_status; ?>
	</div>
	
	<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_transfer || $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
	<div class="align-center">
		<?php _e( "Chiffre d'affaires d&eacute;clar&eacute; :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;</span>
	</div>
	<div class="align-center">
		<?php _e( "Montant pay&eacute; :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;</span>
	</div>
	<?php else: ?>
	<div class="align-center">
		<?php _e( "Chiffre d'affaires pr&eacute;visionnel :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_estimated_turnover() ); ?> &euro;</span>
	</div>
	<div class="align-center">
		<?php _e( "Montant pr&eacute;visionnel :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_estimated_amount() ); ?> &euro;</span>
	</div>
	<?php endif; ?>
	
	<?php if ( !$is_future ): ?>
		<?php if ( $declaration->get_status() != WDGROIDeclaration::$status_transfer && $declaration->get_status() != WDGROIDeclaration::$status_waiting_transfer ): ?>
		<div class="single-line">
			<a href="<?php echo home_url( '/declarer-chiffre-daffaires/?campaign_id=' .$page_controler->get_campaign()->ID. '&declaration_id=' .$declaration->id ); ?>" class="button red force-size"><?php _e( "D&eacute;clarer" ); ?></a>
		</div>
		<?php elseif ( $page_controler->can_access_admin() ): ?>
			<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_transfer ): ?>
			<div class="single-line">
				<a href="#" class="button admin-theme transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>" data-refund="0"><?php _e( "Verser" ); ?></a><br>
				<a href="#" class="button admin-theme transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>" data-refund="1"><?php _e( "Rembourser" ); ?></a>
			</div>
			<?php elseif ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
			<div class="single-line">
				<form action="<?php echo admin_url( 'admin-post.php?action=roi_mark_transfer_received'); ?>" method="POST" class="admin-theme-block">
					<input type="hidden" name="roi_declaration_id" value="<?php echo $declaration->id; ?>" />
					<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
					<button class="button"><?php _e( "Valider la r&eacute;ception du paiement" ); ?></button>
				</form>
			</div>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ( $page_controler->can_access_admin() && ( $declaration->get_status() == WDGROIDeclaration::$status_transfer || $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ) ): ?>
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
				<input type="hidden" id="hidden-isrefund" name="isrefund" class="field" data-id="isrefund" data-type="hidden" value="" />
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



	<div id="declaration-item-more-<?php echo $declaration->id; ?>" class="declaration-item-more hidden">
		<hr>

		<div class="db-form v3 center align-left">

			<?php $declared_by_info = $declaration->get_declared_by(); ?>
			<?php if ( !empty( $declared_by_info ) && isset( $declared_by_info[ 'name' ] ) ): ?>
				<?php _e( "D&eacute;claration r&eacute;alis&eacute;e par :", 'yproject' ); ?> <?php echo $declaration->get_declared_by()[ 'name' ]. ' (' .$declaration->get_declared_by()[ 'status' ]. ')'; ?>
			<?php endif; ?>
			<br><br>

			<strong><?php echo sprintf( __( "Chiffre d'affaires d&eacute;clar&eacute; (pr&eacute;visionnel : %s &euro;) :", 'yproject' ), $declaration->get_estimated_turnover() ); ?></strong><br>

			<table>
				<?php $declaration_turnover = $declaration->get_turnover(); ?>
				<?php if ( $nb_fields > 1 ): ?>
					<?php
					$date_due = new DateTime( $declaration->date_due );
					$date_due->sub( new DateInterval( 'P' .$nb_fields. 'M' ) );
					?>
					<?php for ( $i = 0; $i < $nb_fields; $i++ ): ?>
						<tr>
							<td><?php echo ucfirst( __( $months[ $date_due->format( 'm' ) - 1 ] ) ); ?> :</td>
							<td><?php echo UIHelpers::format_number( $declaration_turnover[ $i ] ); ?> &euro;</td>
						</tr>
						<?php $date_due->add( new DateInterval( 'P1M' ) ); ?>
					<?php endfor; ?>
				<?php endif; ?>

				<tr class="strong">
					<td><?php _e( "Total du chiffre d'affaires d&eacute;clar&eacute; :", 'yproject' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;</td>
				</tr>
				<?php if ( !empty( $adjustments ) ): ?>
					<tr>
						<td><?php echo sprintf( __( "Royalties sur le chiffre d'affaires d&eacute;clar&eacute; (%s) :", 'yproject' ), UIHelpers::format_number( $page_controler->get_campaign()->roi_percent_remaining() ) . ' %' ); ?></td>
						<td><?php echo UIHelpers::format_number( $declaration->get_amount_royalties() ); ?> &euro;</td>
					</tr>
				<?php endif; ?>
			</table>
			<br><br>

			<?php if ( !empty( $adjustments ) ): ?>
				<strong><?php _e( "Ajustement", 'yproject' ); ?></strong><br>
				<?php foreach ( $adjustments as $adjustment_obj ): ?>
					<?php echo $adjustment_obj->message_organization; ?><br>
					<table>
						<tr>
							<td><?php _e( "Diff&eacute;rentiel de CA constat&eacute; lors de l'ajustement :", 'yproject' ); ?></td>
							<td><?php echo UIHelpers::format_number( $adjustment_obj->turnover_difference ); ?> &euro;</td>
						</tr>
						<tr>
							<td><?php _e( "Montant de l'ajustement :", 'yproject' ); ?></td>
							<td><?php echo UIHelpers::format_number( $adjustment_obj->amount ); ?> &euro;</td>
						</tr>
					</table>
					<br>
				<?php endforeach; ?>
				<br>
			<?php endif; ?>

			<table>
				<tr class="strong">
					<td><?php _e( "Total de royalties vers&eacute;es :", 'yproject' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->get_amount_royalties() ); ?> &euro;</td>
				</tr>
				<?php if ( $declaration->get_commission_to_pay() > 0 ): ?>
					<tr>
						<td>
							<?php _e( "Frais de gestion (", 'yproject' ); ?>
							<?php echo ( $declaration->percent_commission / 1.2 ). ' % HT'; ?>
							<?php if ( $page_controler->get_campaign()->get_minimum_costs_to_organization() > 0 ): ?>
								<?php echo ', min. ' .( $page_controler->get_campaign()->get_minimum_costs_to_organization() / 1.2 ). ' &euro;'; ?>
							<?php endif; ?>
							<?php _e( ") :", 'yproject' ); ?>
						</td>
						<td><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay_without_tax() ); ?> &euro;</td>
					</tr>
					<tr>
						<td><?php _e( "TVA sur frais de gestion (20 %) :", 'yproject' ); ?></td>
						<td><?php echo UIHelpers::format_number( $declaration->get_commission_tax() ); ?> &euro;</td>
					</tr>
				<?php endif; ?>
				<tr class="strong">
					<td><?php _e( "Montant total pay&eacute; :", 'yproject' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;</td>
				</tr>
				<tr>
					<td><?php _e( "Reliquat non vers&eacute; aux investisseurs :", 'yproject' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->remaining_amount ); ?> &euro;</td>
				</tr>
			</table>
			<br><br>

			<strong><?php _e( "Message transmis aux investisseurs :", 'yproject' ); ?></strong><br>
			<?php if ( empty( $declaration_message ) ): ?>
				Aucun message n'a été envoyé aux investisseurs.
			<?php else: ?>
				<?php echo $declaration->get_message(); ?>
			<?php endif; ?>
			<br><br>

			<strong><?php _e( "Nombre de salari&eacute;s :", 'yproject' ); ?></strong><br>
			<?php echo $declaration->employees_number; ?>
			<br><br>

			<strong><?php _e( "Autres financements :", 'yproject' ); ?></strong><br>
			<?php echo $declaration->get_other_fundings(); ?>
			<br><br>

			<strong><?php _e( "Justificatifs :", 'yproject' ); ?></strong><br>
			<?php _e( "Vous retrouverez le d&eacute;tail des versements par personne dans le justificatif." ); ?>
			<br><br>

			<?php if ( $declaration->get_turnover_total() > 0 ): ?>
				<?php $declaration->make_payment_certificate(); ?>
				<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue-pale" download="justificatif-<?php echo $declaration->date_due; ?>"><?php _e( "T&eacute;l&eacute;charger le justificatif" ); ?></a>
			<?php else: ?>
				Aucun paiement effectué.
			<?php endif; ?>
			<br><br>

		</div>
	</div>

	<div id="declaration-item-more-btn-<?php echo $declaration->id; ?>" class="declaration-item-more-btn align-center">
		<button class="button transparent" data-declaration="<?php echo $declaration->id; ?>">+</button>
	</div>

<?php endif; ?>
