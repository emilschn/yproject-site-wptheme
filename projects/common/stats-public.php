<?php 
global $disable_logs, $WDG_cache_plugin, $campaign, $stylesheet_directory_uri; 
$disable_logs = TRUE;
if (!isset($campaign) && isset($_GET["campaign_id"])) { $campaign = atcf_get_campaign($_GET["campaign_id"]); }

if (!empty($campaign)):
	$campaign_id = $campaign->ID;
	$status = $campaign->campaign_status();
?>

	<h2 class="expandator" data-target="votes"><?php _e('Votes', 'yproject'); ?> <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></h2>
	
    <div id="extendable-votes" class="expandable <?php if ($status==ATCF_Campaign::$campaign_status_vote){echo 'default-expanded';} ?>">
    <?php
            $post_campaign = get_post($campaign_id);
            $upload_dir = wp_upload_dir();
            if (file_exists($upload_dir['basedir'] . '/projets/' . $post_campaign->post_name . '-stats.jpg')) { 
				echo '<img src="'.$upload_dir['baseurl'] . '/projets/' . $post_campaign->post_name . '-stats.jpg" alt="Statistiques du projet" />';
            } else {
				//*******************
				//CACHE PROJECT PUBLIC STATS
				$cache_stats = $WDG_cache_plugin->get_cache('project-stats-public-votes-' . $campaign_id, 1);
				if ($cache_stats === FALSE) {
					ob_start();
                    locate_template( array("projects/common/stats-public-votes.php"), true );
                    $vote_results = WDGCampaignVotes::get_results($campaign_id);
                    print_vote_results($vote_results);
					$cache_stats = ob_get_contents();
					$WDG_cache_plugin->set_cache('project-stats-public-votes-' . $campaign_id, $cache_stats, 60*30, 1);
					ob_end_clean();
				}
				echo $cache_stats;
            }
    ?>
    </div>

	<?php if ( $status == ATCF_Campaign::$campaign_status_collecte 
	|| $status == ATCF_Campaign::$campaign_status_funded 
	|| $status == ATCF_Campaign::$campaign_status_archive): ?>
	<h2 class="expandator" data-target="investments"><?php echo ucfirst($campaign->funding_type_vocabulary()['investor_action']);?>s  <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus" /></h2>
	<div id="extendable-investments" class="expandable default-expanded">
	<?php 
			locate_template( array("projects/common/stats-public-investments.php"), true );
			print_investments($campaign_id, false);
	?>
	</div>
	<?php endif; ?>
	
<?php endif;
