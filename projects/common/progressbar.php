<?php 
global $campaign;
$campaign_status = $campaign->campaign_status();
$percent = min(100, $campaign->percent_minimum_completed(false));
$width = 100 * $percent / 100; // taille maxi de la barre est à 100%
?>

<?php if ($campaign_status === ATCF_Campaign::$campaign_status_vote): ?>
<div class="progress-bar">
	<span class="vote-status" style="min-width:100%">&nbsp;<p><?php _e("projet en cours de vote", "yproject"); ?></p>&nbsp;</span>        
</div>
<?php else: ?>
<div class="progress-bar">
	<span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;<p><?php echo $campaign->current_amount(); ?></p>&nbsp;</span>
	<span class="progress-percent"><p><?php echo $campaign->percent_minimum_completed(); ?></p></span>          
</div>
<?php endif; ?>