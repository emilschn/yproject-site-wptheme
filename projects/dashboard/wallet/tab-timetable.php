<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
?>

<div id="tab-wallet-timetable" class="tab-content">
	<?php if ($campaign->funding_type() == 'fundingdonation'): ?>
		Ce projet n'est pas concerné.
		
	<?php else: ?>

		<?php if ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded): ?>
			<h2><?php _e('Reverser aux investisseurs', 'yproject'); ?></h2>

				
			<h3>Dates de vos versements :</h3>
			<?php
			$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign->ID );
			?>
			<?php if ($declaration_list): ?>
				<ul class="payment-list">
					<?php foreach ( $declaration_list as $declaration_item ): ?>
						<?php global $declaration; $declaration = $declaration_item; ?>
						<?php locate_template( array("projects/dashboard/wallet/partial-declaration.php"), true, false ); ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		<?php endif; ?>
		
	<?php endif; ?>
</div>