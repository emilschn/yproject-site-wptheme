<?php 
global $campaign;
$percent = min(100, $campaign->percent_minimum_completed(false));
$min_width = 250 * $percent / 100;
?>
<div class="progressbar">
	<div class="progressbar-bg">
		<div class="progressbar-complete" style="min-width:<?php echo $min_width; ?>px">&nbsp;<?php echo $campaign->current_amount(); ?>&nbsp;</div>
	</div>
	<div class="progressbar-percent"><?php echo $campaign->percent_minimum_completed(); ?></div>
</div>