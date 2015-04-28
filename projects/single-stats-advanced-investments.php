<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {?>
<h2>Statistiques des investissements</h2>
    <?php
	locate_template( array("requests/investments.php"), true );
	locate_template( array("projects/stats-investments-public.php"), true ); 
	$investments_list = wdg_get_project_investments($_GET['campaign_id'], TRUE);
	$campaign = $investments_list['campaign'];
	$is_campaign_over = ($campaign->campaign_status() == 'funded' || $campaign->campaign_status() == 'archive');
	print_investments($investments_list);
}
?>