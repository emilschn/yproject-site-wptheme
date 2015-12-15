<?php 
global $disable_logs, $WDG_cache_plugin, $campaign, $stylesheet_directory_uri; 
$disable_logs = TRUE;
if (isset($_GET["campaign_id"])) {
	$campaign_id = $_GET["campaign_id"];
	$campaign = atcf_get_campaign($campaign_id);
}

if (!empty($campaign)) {
	$status = $campaign->campaign_status();

//*******************
//CACHE PROJECT PUBLIC STATS
$cache_stats = $WDG_cache_plugin->get_cache('project-stats-public-' . $campaign_id, 1);
if ($cache_stats !== FALSE) { echo $cache_stats; }
else {
	ob_start();
?>

<h2 class="expandator" data-target="votes">Votes <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></h2>
    <div id="extendable-votes" class="expandable <?php if ($status=='vote'){echo 'default-expanded';} ?>">
    <?php
            $post_campaign = get_post($campaign_id);
            $upload_dir = wp_upload_dir();
            if (file_exists($upload_dir['basedir'] . '/projets/' . $post_campaign->post_name . '-stats.jpg')) { 
                    echo '<img src="'.$upload_dir['baseurl'] . '/projets/' . $post_campaign->post_name . '-stats.jpg" alt="Statistiques du projet" />';
            } else {
                    locate_template( array("projects/common/stats-public-votes.php"), true );
                    $vote_results = WDGCampaignVotes::get_results($_GET['campaign_id']);
                    print_vote_results($vote_results);
            }
    ?>
    </div>

<?php if ($status=='collecte' || $status=='funded' || $status=='archive'){ ?>
<h2 class="expandator" data-target="investments"><?php echo ucfirst($campaign->funding_type_vocabulary()['investor_action']);?>s  <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus" /></h2>
    <div id="extendable-investments" class="expandable default-expanded">
    <?php 
            locate_template( array("projects/common/stats-public-investments.php"), true );
            print_investments($campaign_id, false);
    ?>
    </div>
<?php } ?>
<?php
	$cache_stats = ob_get_contents();
	$WDG_cache_plugin->set_cache('project-stats-public-' . $campaign_id, $cache_stats, 60*30, 1);
	ob_end_clean();
	echo $cache_stats;
}
//FIN CACHE MENU
//*******************

?>

<?php 
} 
