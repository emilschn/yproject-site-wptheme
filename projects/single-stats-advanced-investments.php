<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {?>
<h2>Statistiques des investissements</h2>
    <?php
	locate_template( array("projects/stats-investments-public.php"), true );
        print_investments($campaign->ID, true);
}
?>