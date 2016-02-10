<?php
global $campaign, $payment_url;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign) && is_user_logged_in()):
	$campaign_url  = get_permalink($campaign->ID);
	?>
		
	<?php
	global $current_breadcrumb_step; $current_breadcrumb_step = 5;
	locate_template( 'invest/breadcrumb.php', true );
	?>
	
	<div class="projects_preview projects_current projects_current_temp" style="margin-left: 370px;">
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


				<div class="project_preview_item_progress">
					<?php
					$percent = min(100, $campaign->percent_minimum_completed(false));
					$width = 150 * $percent / 100;
					$width_min = 0;
					if ($percent >= 100 && $campaign->is_flexible()) {
						$percent_min = $campaign->percent_minimum_to_total();
						$width_min = 150 * $percent_min / 100;
					}
					?>
					<a href="<?php echo $campaign_url; ?>">
						<div class="project_preview_item_progressbg" style="margin-top: 14px;">
							<div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">
								<?php if ($width_min > 0): ?>
								<div style="width: <?php echo $width_min; ?>px; height: 20px; border: 0px; border-right: 1px solid white;">&nbsp;</div>
								<?php else: ?>
								&nbsp;
								<?php endif; ?>
							</div>
						</div>
						<span class="project_preview_item_progressprint"><?php echo $campaign->percent_minimum_completed(); ?></span>
						<span class="project_preview_item_progressprint"><?php echo $campaign->current_amount(true)?></span>
					</a>
				</div>
			</div>
		</div>
		<div style="clear: both"></div>
	</div>
	<div style="clear: both"></div>
		

	<center>
	<?php if (class_exists('Sharing_Service')) {
	    //Liens pour partager
	    echo ypcf_fake_sharing_display();
	} else {
	    _e("Le service de partage est momentan&eacute;ment d&eacute;sactiv&eacute;.", 'yproject');
	} ?>
	</center><br /><br />
	&lt;&lt; <a href="<?php echo $campaign_url; ?>"><?php _e("Retour au projet", 'yproject'); ?></a><br /><br />
	
<?php
endif;