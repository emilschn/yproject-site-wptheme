<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
$finished_declarations = $campaign->get_roi_declarations_by_status( WDGROIDeclaration::$status_finished );
$nb_finished_declarations = count( $finished_declarations );
$roi_percent = $campaign->roi_percent();
?>

<div id="tab-wallet-synthesis" class="tab-content">
	<h2><?php _e('Situation', 'yproject'); ?></h2>
	<ul>
		<li><strong><?php echo UIHelpers::format_number( $organization_obj->get_lemonway_balance() ); ?> €</strong> <?php _e( "dans votre porte-monnaie", 'yproject' ); ?></li>
		<li><strong><?php echo UIHelpers::format_number( $campaign->current_amount( false ) ); ?> €</strong> <?php _e( "lev&eacute;s", 'yproject' ); ?></li>
		
		<?php if ( $roi_percent > 0 ): ?>
		<li><strong><?php echo $campaign->roi_percent(); ?> %</strong> <?php _e( "du CA &agrave; verser pendant", 'yproject' ); ?> <strong><?php echo $campaign->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?></strong></li>
		<?php else: ?>
		<li><strong><?php echo $campaign->roi_percent_estimated(); ?> %</strong> <?php _e( "maximum du CA &agrave; verser pendant", 'yproject' ); ?> <strong><?php echo $campaign->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?></strong></li>
		<?php endif; ?>
		
		<li><strong><?php echo count( $finished_declarations ); ?> / <?php echo $campaign->get_roi_declarations_number(); ?></strong> <?php _e( "&eacute;ch&eacute;ances", 'yproject' ); ?></li>
		<li>
			<strong><?php echo $campaign->get_roi_declarations_total_turnover_amount(); ?> €</strong> <?php _e( "de CA d&eacute;clar&eacute;", 'yproject' ); ?>
			<?php /* <ul>
				<li><?php _e( "Maximum engag&eacute;", 'yproject' ); ?> : <strong>TODO €</strong></li>
				<li><?php _e( "Objectif", 'yproject' ); ?> : <strong>TODO €</strong></li>
				<li><?php _e( "Minimum", 'yproject' ); ?> : <strong>TODO €</strong></li>
			</ul> */ ?>
		</li>
		<li>
			<strong><?php echo $campaign->get_roi_declarations_total_roi_amount(); ?> €</strong> <?php _e( "de royalties vers&eacute;es", 'yproject' ); ?>
			<?php /* <ul>
				<li><?php _e( "Maximum :", 'yproject' ); ?> <strong>TODO €</strong></li>
				<li><?php _e( "Objectif :", 'yproject' ); ?> <strong>TODO €</strong></li>
				<li><?php _e( "Minimum :", 'yproject' ); ?> <strong>TODO €</strong></li>
			</ul> */ ?>
		</li>
	</ul>
			
	<?php if ( $campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>
	<div class="field align-center">
		<a href="<?php echo $campaign->get_funded_certificate_url(); ?>" download="attestation-levee-fonds.pdf" class="button red"><?php _e( "T&eacute;l&eacute;charger mon attestation de lev&eacute;e de fonds", 'yproject' ); ?></a>
	</div>
	<?php endif; ?>
	
	
	<h2><?php _e('Historique', 'yproject'); ?></h2>
	<?php if ( $nb_finished_declarations > 0 ): ?>
	
		<ul>
		<?php foreach( $finished_declarations as $declaration_item ): ?>
			<li>Déclaration du <?php echo $declaration_item->date_due; ?> : <?php echo $declaration_item->get_amount_with_adjustment(); ?> € de royalties
				<?php if ( $declaration_item->get_amount_with_adjustment() > 0 ): ?>
				payées le <?php echo $declaration_item->get_formatted_date( 'paid' ); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	
	<?php endif; ?>
	
	<?php $transfers = $organization_obj->get_transfers();
	if ($transfers) : ?>

		<h3>Transferts vers votre compte :</h3>
		<ul>
			<?php
			foreach ( $transfers as $transfer_post ) :
				$post_status = ypcf_get_updated_transfer_status($transfer_post);
				$transfer_post = get_post($transfer_post);
				$post_amount = $transfer_post->post_title;
				$post_date = new DateTime($transfer_post->post_date);
				// Les versements faits via Mangopay doivent être recalculés
				if ( $post_date < new DateTime('2016-07-01') ) {
					$post_amount /= 100;
				}
				$status_str = 'En cours';
				if ($post_status == 'publish') {
					$status_str = 'Termin&eacute;';
				} else if ($post_status == 'draft') {
					$status_str = 'Annul&eacute;';
				}
				?>
				<li id="<?php echo $transfer_post->post_content; ?>"><?php echo $transfer_post->post_date; ?> : <?php echo UIHelpers::format_number( $post_amount ); ?>&euro; -- Termin&eacute;</li>
				<?php
			endforeach;
			?>
		</ul>

	<?php else: ?>
		<?php _e( "Aucun transfert d&apos;argent.", 'yproject' ); ?>
	<?php endif; ?>
		
		
	<?php
	$saved_mandates_list = $organization_obj->get_lemonway_mandates();
	$last_mandate = end( $saved_mandates_list );
	if ( empty( $saved_mandates_list ) ) {
		$last_mandate_status = $last_mandate[ "S" ];
		if ( $last_mandate_status == 5 || $last_mandate_status == 6 ): ?>
			<?php _e( "Autorisation de pr&eacute;l&egrave;vement sign&eacute;.", 'yproject' ); ?>
		<?php endif;
	}
	?>
</div>