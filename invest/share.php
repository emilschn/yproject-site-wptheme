<?php
global $campaign, $payment_url;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign) && is_user_logged_in()):
	$campaign_url = get_permalink($campaign->ID);
	?>
		
	<?php
	global $current_breadcrumb_step; $current_breadcrumb_step = 5;
	locate_template( 'invest/breadcrumb.php', true );
	?>
	
	<div class="projects_preview projects_current projects_current_temp">
	    <div class="preview_item_<?php echo $campaign->ID; ?> project_preview_item" style="width: 220px;">
			<div class="project_preview_item_part">
				<div class="project_preview_item_pictos">
					<div class="project_preview_item_picto">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/france.png" />
						<?php 
							$campaign_location = $campaign->location();
							$exploded = explode(' ', $campaign_location);
							if (count($exploded) > 1) $campaign_location = $exploded[0];
							echo (($campaign_location != '') ? $campaign_location : 'France'); 
						?>
					</div>
					<div class="project_preview_item_picto">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
						<?php echo $campaign->time_remaining_str(); ?>
					</div>
					<div class="project_preview_item_picto">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
						<?php echo $campaign->minimum_goal(true); ?>
					</div>
					<div class="project_preview_item_picto">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png" />
						<?php echo $campaign->get_jycrois_nb(); ?>
					</div>
					<div style="clear: both"></div>
				</div>
			</div>
		</div>
		<div style="clear: both"></div>
	</div>
	<div style="clear: both"></div>

	<div class="align-center">
		<?php locate_template( 'projects/common/progressbar.php', true ); ?>
	</div>
	<br /><br /><br />
		
	<div class="align-center">
		<?php _e("Partager", 'yproject'); ?><br /><br />
		<?php locate_template( 'projects/common/share-buttons.php', true ); ?>
	</div>
	<br /><br />
	
	
	&lt;&lt; <a href="<?php echo $campaign_url; ?>"><?php _e("Retour au projet", 'yproject'); ?></a><br /><br />
	
<?php
endif;