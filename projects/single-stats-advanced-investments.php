<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()): ?>
	<h2>Statistiques des <?php echo $campaign->funding_type_vocabulary()['investor_action']?>s</h2>
    <?php
	locate_template( array("projects/common/stats-public-investments.php"), true );
	print_investments($campaign->ID, true);
endif;