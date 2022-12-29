<?php
global $campaign, $stylesheet_directory_uri, $is_progressbar_shortcode;
$time_remaining_str = $campaign->time_remaining_str();
$campaign_status = $campaign->campaign_status();
?>

<!-- PROJET EN EVALUATION -->
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
<!-- PROJET LEVEE DE FOND TERMINEE -->
<?php elseif ( $campaign_status == ATCF_Campaign::$campaign_status_funded || $campaign_status == ATCF_Campaign::$campaign_status_closed ): ?>

	<?php
	$width = 100; // taille maxi de la barre est à 100%

	$container_classes = '';
	if ( !empty( $is_progressbar_shortcode ) ) {
		$container_classes .= ' shortcode-context';
	}
	$progress_data_class = '';
	if ( $campaign->has_duplicate_campaigns() ) {
		$progress_data_class .= ' has-duplicate';
	}
	?>

	<div class="progress-bar-container <?php echo $container_classes; ?>">
		<div class="progress-bar project-page-bar">
			<div class="current-bar" style="min-width:<?php echo $width; ?>%"></div>
		</div>
		<div class="progress-data project-page-data <?php echo $progress_data_class; ?>">
			<span class="current-amount"><span><?php echo $campaign->get_duplicate_campaigns_total_amount(); ?></span>&nbsp;<?php _e( 'progressbar.RAISED', 'yproject' ); ?></span>
			<span class="progress-percent"><span>
				<?php 
					//le pourcentage par rapport au minimum de la première levée
					echo $campaign->percent_on_first_minimum_goal(); 				
				?>			
			</span></span>
		</div>
	</div>
<!-- PROJET AVEC OPTION minimum_as_step : Afficher l'objectif maximum et un seuil de validation -->
<?php elseif ( $campaign->get_minimum_goal_display() == ATCF_Campaign::$key_minimum_goal_display_option_minimum_as_step  && $campaign_status === ATCF_Campaign::$campaign_status_collecte): ?>

	<?php
	$maximum_goal = $campaign->get_maximum_goal_total( false );
	// c'est le minimum_goal de la première campagne qu'on récupère
	$minimum_goal = $campaign->get_original_minimum_goal( false );
	$percent_minimum_completed = $campaign->percent_minimum_completed( false );
	if ( isset($maximum_goal) && $maximum_goal != 0 ) {
		$width_to_minimum_goal = $minimum_goal / $maximum_goal * 100;
	} else {
		$width_to_minimum_goal = 0;
	}

	$file_check = 'minimum-goal-empty.png';
	if ( $percent_minimum_completed >= 100 ) {
		$file_check = 'minimum-goal-full.png';
	} 

	$width_to_next_step = $campaign->get_next_goal( false ) * 100 / $maximum_goal;
	$bar_width = $campaign->get_duplicate_campaigns_total_amount( false ) * 100 / $maximum_goal;	

	$file_next_step = 'minimum-goal-empty.png';

	$container_classes = '';
	if ( !empty( $is_progressbar_shortcode ) ) {
		$container_classes .= 'shortcode-context';
	}
	$progress_data_class = '';
	if ( $campaign->has_duplicate_campaigns() ) {
		$progress_data_class .= ' has-duplicate';
	}
	?>
	<div class="progress-bar-container minimum-as-step <?php echo $container_classes; ?>">
		<div class="progress-bar project-page-bar">
			<div class="current-bar" style="min-width:<?php echo $bar_width; ?>%"></div>
		</div>
		<div class="progress-data project-page-data <?php echo $progress_data_class; ?>">
			<span class="current-amount"><span><?php echo $campaign->get_duplicate_campaigns_total_amount(); ?></span>&nbsp;<?php _e( 'progressbar.RAISED', 'yproject' ); ?></span>
			<span class="progress-percent"><span><?php 		
				if ( $campaign->has_duplicate_campaigns() ) {
					echo $campaign->current_amount() . ' / ' . $campaign->minimum_goal(). ' € ';
					_e( 'progressbar.CURRENTLY_RAISING', 'yproject' );
					echo ' - ';
				}
				echo $campaign->percent_minimum_completed(); ?>
			</span></span>
		</div>
		<span class="progress-bar-separator" style="margin-left: <?php echo $width_to_minimum_goal; ?>%;">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/<?php echo $file_check; ?>" width="20" height="20">
			<hr>
		</span>
		<span class="progress-bar-separator" style="margin-left: <?php echo $width_to_next_step; ?>%; margin-top: -73px;">
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project/minimum-alpha.png" width="20" height="20">
			<hr>
		</span>
	</div>

<!-- PROJET AVEC OPTION minimum_as_max : Afficher l'objectif minimum -->
<?php else: ?>

	<?php
	$percent = min( 100, $campaign->percent_minimum_completed( false ) );
	$width = 100 * $percent / 100; // taille maxi de la barre est à 100%

	$container_classes = '';
	if ( !empty( $is_progressbar_shortcode ) ) {
		$container_classes .= ' shortcode-context';
	}
	$progress_data_class = '';
	if ( $campaign->has_duplicate_campaigns() ) {
		$progress_data_class .= ' has-duplicate';
	}
	?>

	<div class="progress-bar-container <?php echo $container_classes; ?>">
		<div class="progress-bar project-page-bar">
			<div class="current-bar" style="min-width:<?php echo $width; ?>%"></div>
		</div>
		<div class="progress-data project-page-data <?php echo $progress_data_class; ?>">
			<span class="current-amount">
				<span><?php echo $campaign->get_duplicate_campaigns_total_amount(); ?></span>
				&nbsp;<?php _e( 'progressbar.RAISED', 'yproject' ); ?></span>
				<span class="progress-percent"><span>
					<?php 
						if ( $campaign->has_duplicate_campaigns() ) {
							echo $campaign->current_amount() . ' / ' . $campaign->minimum_goal( false ) . ' € ' ;
							_e( 'progressbar.CURRENTLY_RAISING', 'yproject' );
							echo ' - ';
						}
						echo $campaign->percent_minimum_completed(); 
					?>
				
				</span></span>
		</div>
	</div>

<?php endif;
