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
	<div class="align-center">
		<?php _e( "Chiffre d'affaires pr&eacute;visionnel :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_estimated_turnover() ); ?> &euro;</span>
	</div>
	<div class="align-center">
		<?php _e( "Montant pr&eacute;visionnel :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_estimated_amount() ); ?> &euro;</span>
	</div>
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

<?php endif; ?>
