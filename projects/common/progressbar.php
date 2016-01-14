<?php 
global $campaign;
$percent_minimum_completed = $campaign->percent_minimum_completed(false);
$percent_completed = $campaign->percent_completed(false);
$percent = ($percent_minimum_completed > 100) ? $percent_completed : min(100, $percent_minimum_completed);
$min_width = 250 * $percent / 100;
?>
<div class="progressbar">
	<div class="progressbar-bg">
		<div class="progressbar-complete" style="min-width:<?php echo $min_width; ?>px">&nbsp;<?php echo $campaign->current_amount(); ?>&nbsp;</div>
	</div>
	<div class="progressbar-percent"><?php echo $campaign->percent_minimum_completed(); ?></div>
</div>