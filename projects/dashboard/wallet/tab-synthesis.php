<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
$mandate_conditions = $campaign->mandate_conditions();
?>

<div id="tab-wallet-synthesis" class="tab-content">
	<h2><?php _e('Liste des op&eacute;rations bancaires', 'yproject'); ?></h2>
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
		Aucun transfert d&apos;argent.
	<?php endif; ?>
</div>