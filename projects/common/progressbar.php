<?php
global $campaign, $stylesheet_directory_uri, $is_progressbar_shortcode;
$time_remaining_str = $campaign->time_remaining_str();
$campaign_status = $campaign->campaign_status();
?>

<?php if ( $campaign_status === ATCF_Campaign::$campaign_status_vote ): ?>
<div class="evaluation-container <?php if ( !empty( $is_progressbar_shortcode ) ) {
	echo 'shortcode-context';
} ?>">
	<div class="evaluation-bar"></div>
	<span class="vote-status" style="min-width:100%"><p>
		<?php if ($time_remaining_str != '-'): ?>
		<?php _e("projet en cours d'&eacute;valuation", "yproject"); ?>
		<?php else: ?>
		<?php _e("&eacute;valuation termin&eacute;e", "yproject"); ?>
		<?php endif; ?>
	</p></span>
</div>

<?php elseif ( $campaign->get_minimum_goal_display() == ATCF_Campaign::$key_minimum_goal_display_option_minimum_as_step ): ?>
	<?php
	$maximum_goal = $campaign->goal( false );
	$minimum_goal = $campaign->minimum_goal( false );
	$percent_minimum_completed = $campaign->percent_minimum_completed( false );
	$percent_minimum_display = min( 100, $percent_minimum_completed );
	if ( isset($maximum_goal) && $maximum_goal != 0 ) {
		$width_to_minimum_goal = $minimum_goal / $maximum_goal * 100;
	} else {
		$width_to_minimum_goal = 0;
	}

	$width_to_minimum_completed = $percent_minimum_display * $width_to_minimum_goal / 100;
	$file_check = 'minimum-goal-empty.png';
	$width_to_completed = 0;
	if ( $percent_minimum_completed >= 100 ) {
		$width_to_completed = ( $percent_minimum_completed - 100 ) * $width_to_minimum_goal / 100;
		$file_check = 'minimum-goal-full.png';
		$bar_width = $width_to_minimum_goal + $width_to_completed;
	} else {
		$bar_width = $percent_minimum_completed * $width_to_minimum_goal / 100;
	}
	?>
<div class="progress-bar-container minimum-as-step <?php if ( !empty( $is_progressbar_shortcode ) ) {
		echo 'shortcode-context';
	} ?>">
	<div class="progress-bar project-page-bar">
		<div class="current-bar" style="min-width:<?php echo $bar_width; ?>%"></div>
	</div>
	<div class="progress-data project-page-data">
		<span class="current-amount"><span><?php echo $campaign->current_amount(); ?></span>&nbsp;</span>
		<span class="progress-percent"><span><?php echo $campaign->percent_minimum_completed(); ?></span></span>
	</div>
	<span class="progress-bar-separator" style="margin-left: <?php echo $width_to_minimum_goal; ?>%;">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/<?php echo $file_check; ?>" width="20" height="20">
		<hr>
	</span>
</div>

<?php else: ?>
	<?php
	$percent = min( 100, $campaign->percent_minimum_completed( false ) );
	$width = 100 * $percent / 100; // taille maxi de la barre est à 100%
	?>

	<div class="progress-bar-container <?php if ( !empty( $is_progressbar_shortcode ) ) {
		echo 'shortcode-context';
	} ?>">
		<div class="progress-bar project-page-bar">
			<div class="current-bar" style="min-width:<?php echo $width; ?>%"></div>
		</div>
		<div class="progress-data project-page-data">
			<span class="current-amount"><span><?php echo $campaign->current_amount(); ?></span>&nbsp;</span>
			<span class="progress-percent"><span><?php echo $campaign->percent_minimum_completed(); ?></span></span>
		</div>
	</div>

<?php endif;
