<?php
global $stylesheet_directory_uri, $project_id;
/*
 * Page pour la section des projets à afficher en page d'accueil
 *
 */
$campaign = atcf_get_campaign( $project_id );
$title = get_the_title( $campaign->ID );
$description = html_entity_decode( $campaign->summary() );
$img = $campaign->get_home_picture_src( TRUE, 'large' );
$link = get_permalink( $campaign->ID );

$campaign_status = $campaign->campaign_status();
$campaign_categories_str = $campaign->get_categories_str();
// TODO : chercher en anglais aussi
$class_category = ( strpos( $campaign_categories_str, 'actifs' ) !== FALSE ) ? 'cat-actifs' : 'cat-entreprises';
// TODO : chercher en anglais aussi
if ( $campaign->is_positive_savings() ) {
	$class_category .= ' cat-epargne-positive';

	WDG_Languages_Helpers::switch_to_french_temp();
	$term_positive_savings_by_slug = get_term_by( 'slug', 'epargne-positive', 'download_category' );
	$id_cat_positive_savings = $term_positive_savings_by_slug->term_id;
	$categories = get_the_terms( $campaign->ID, 'download_category' );
	foreach ( $categories as $category ) {
		if ( $category->parent == $id_cat_positive_savings ) {
			$title = $category->name;
			$description = $category->description;
			$array_description_exploded = preg_split( "/\n/", $description );
			if ( count( $array_description_exploded ) > 1 ) {
				$img = array_pop( $array_description_exploded );
				$description = implode( '<br>', $array_description_exploded );
			}
			$link = $campaign->get_public_url();
			break;
		}
	}
	WDG_Languages_Helpers::switch_back_to_display_language();
}

$jycrois = $campaign->get_jycrois_nb(); // Personnes qui suivent le projet
if ($jycrois > 1) {
	$persStatus = __( 'project.FOLLOW.P', 'yproject' );
} else {
	if ($jycrois == 1) {
		$persStatus = __( 'project.FOLLOW.ONE', 'yproject' );
	} else {
		if ($jycrois == 0) { // voir si utile, car si 0 backers, on a tout de même 1 pers qui s'affiche
			$jycrois = false;
			$persStatus = __( 'project.FOLLOW.BE_FIRST_1', 'yproject' ) . '<br />' . __( 'project.FOLLOW.BE_FIRST_2', 'yproject' );
		}
	}
}

$percent = min(100, $campaign->percent_minimum_completed(false));
$width = 100 * $percent / 100; // taille maxi de la barre est à 100%
?>


<div class="project-container <?php echo $class_category; ?>" id="project-<?php echo $project_id ?>" data-step="<?php echo $campaign_status; ?>" data-location="<?php echo $campaign->get_location_number(); ?>" data-categories="<?php echo $campaign_categories_str; ?>">
	<div class="project-framed">
		<div class="project-img" <?php if ( !empty( $img ) ) { ?> style="background-image: url('<?php echo $img; ?>')"<?php } ?>></div>
		<a class="hidden-link" href="<?php echo $link; ?>">
			<div class="impacts-container" id="impacts-<?php echo $project_id ?>">
				<?php if (strpos($campaign_categories_str, 'environnemental') !== FALSE || strpos($campaign_categories_str, 'environmental')  !== FALSE): ?>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-env.png" alt="<?php _e( 'project.impact.ENVIRONMENT', 'yproject' ); ?>" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e( 'project.impact.ENVIRONMENT', 'yproject' ); ?></span>
				<?php endif; ?>
				<?php if (strpos($campaign_categories_str, 'social') !== FALSE): ?>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-social.png" alt="<?php _e( 'project.impact.SOCIAL', 'yproject' ); ?>" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e( 'project.impact.SOCIAL', 'yproject' ); ?></span>
				<?php endif; ?>
				<?php if (strpos($campaign_categories_str, 'economique') !== FALSE || strpos($campaign_categories_str, 'economic')  !== FALSE): ?>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-eco.png" alt="<?php _e( 'project.impact.ECO', 'yproject' ); ?>" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e( 'project.impact.ECO', 'yproject' ); ?></span>
				<?php endif; ?>
				<?php if (strpos($campaign_categories_str, 'entreprise-engagee') !== FALSE || strpos($campaign_categories_str, 'committed-company')  !== FALSE): ?>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-engagee.png" alt="<?php _e( 'project.impact.ENGAGEMENT', 'yproject' ); ?>" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e( 'project.impact.ENGAGEMENT', 'yproject' ); ?></span>
				<?php endif; ?>
			</div>
		</a>
		<a class="hidden-link" href="<?php echo $link; ?>">
			<div class="title-container"><h2 class="project-title"> <?php echo $title; ?> </h2></div>
			<div class="project-summary"><?php echo $description; ?></div>
		</a>


		<?php
		//Projets en cours de collecte ou en vote
		if ( ( $campaign_status == ATCF_Campaign::$campaign_status_vote || $campaign_status == ATCF_Campaign::$campaign_status_collecte ) && !$campaign->is_positive_savings() ):
		?>
			<a class="hidden-link" href="<?php echo $link; ?>">
				<?php
					$time_remaining_str = $campaign->time_remaining_str();

					if ( $time_remaining_str == '-' && $campaign_status === ATCF_Campaign::$campaign_status_collecte && $campaign->can_invest_until_contract_start_date() ) {
						$time_remaining_str = $campaign->time_remaining_str_until_contract_start_date();
					}

					if ($time_remaining_str != '-') {
						$time_remaining_str_split = explode('-', $time_remaining_str);
						$time_remaining_str = ($time_remaining_str_split[1] + 1) . ' ';
						$time_remaining_str_unit = $time_remaining_str_split[0];
						switch ($time_remaining_str_split[0]) {
							case 'J': $time_remaining_str .= __( 'project.DAYS', 'yproject' ); break;
							case 'H': $time_remaining_str .= __( 'project.HOURS', 'yproject' ); break;
							case 'M': $time_remaining_str .= __( 'project.MINUTES', 'yproject' ); break;
						}
					}
				?>

				<?php if ( $campaign_status === ATCF_Campaign::$campaign_status_collecte && !$campaign->is_positive_savings() ): ?>
					<?php
						$projectAction = __( 'project.TO_INVEST', 'yproject' );
						$buttonAction = __( 'project.INVEST_ON_PROJECT', 'yproject' );
					?>
						<div class="progress-bar">
							<div class="current-bar" style="min-width:<?php echo $width; ?>%"></div>
						</div>
						<div class="progress-data">
							<span class="current-amount"><span><?php echo $campaign->current_amount(); ?></span>&nbsp;</span>
							<span class="progress-percent"><span><?php echo $campaign->percent_minimum_completed(); ?></span></span>
						</div>

				<?php elseif ( $campaign_status === ATCF_Campaign::$campaign_status_vote && !$campaign->is_positive_savings() ): ?>
					<?php
						if ( $time_remaining_str != '-' ) {
							$projectAction = __( 'project.TO_EVALUATE', 'yproject' );
							$buttonAction = __( 'project.EVALUATE_PROJECT', 'yproject' );
							$project_status = __( 'project.PROJECT_CURRENTLY_EVALUATED', 'yproject' );
						} else {
							$projectAction = '';
							$buttonAction = __( 'project.DISCOVER_THIS_PROJECT', 'yproject' );
							$project_status = __( 'project.EVALUATION_FINISHED', 'yproject' );
						}
					?>
					<div class="evaluation-bar"></div>
					<span class="vote-status" style="min-width:100%"><span><?php echo $project_status; ?></span>&nbsp;</span>
				<?php endif; ?>
			</a>
			<?php if ($campaign->is_funded()) : ?>
				<div class="financed-banner campaign-collecte"> 
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png">
					<p><?php _e( 'project.FUNDED_BANNER', 'yproject' ); ?></p>
				</div>
			<?php endif; ?>
			<a class="hidden-link" href="<?php echo $link; ?>">
				<div class="progress-info">
					<span class="progress-pers">
						<?php if ($jycrois): ?>
							<span class="info-nb">
								<?php echo $jycrois; ?>
							pers.</span>
							<br>
						<?php endif; ?>
						<span class="info-action">
							<?php echo $persStatus ?>
						</span>
					</span>
					<hr>
					<span class="progress-days"><span class="info-nb"><?php echo $time_remaining_str; ?></span><br><span class="info-action"> <?php echo $projectAction ?></span></span>
				</div>
			</a>
			<a class="home-button-project project-button" href="<?php echo $link; ?>"><?php echo $buttonAction ?></a>

		<?php
		//Projets en épargne positive
		elseif ($campaign->is_positive_savings()) :
		?>
			<div class="financed-banner"> 
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png">
				<p><?php _e( 'project.POSITIVE_SAVINGS_BANNER', 'yproject' ); ?></p>
			</div>
			<a class="home-button-project project-button" href="<?php echo $link; ?>"><?php _e( 'project.INVEST_ON_PROJECT', 'yproject' ) ?></a>

		<?php
		//Projets déja financés
		else :
		?>
			<?php
				$projectStatus = __( 'project.CAMPAIGN_FUNDED', 'yproject' );
				$buttonAction = __( 'project.DISCOVER_THIS_PROJECT', 'yproject' ); // vers plus d'info sur ce projet
			?>
			<div class="financed-banner"> 
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/favicon.png">
				<p><?php _e( 'project.FUNDED_BANNER', 'yproject' ); ?></p>
			</div>

			<a class="hidden-link" href="<?php echo $link; ?>">
				<span class="info-nb financed-nb"><?php echo $campaign->get_jycrois_nb(); ?>&nbsp;<?php _e('project.PEOPLE_RAISE', 'yproject', "yproject") ?></span>
				<div class="financed-data">
						<span><?php echo $campaign->current_amount(); ?></span><hr><span><?php echo $campaign->percent_minimum_completed(); ?></span>
					</span>
				</div>
			</a>
			<a class="financed-link" href="<?php echo $link; ?>"><?php echo $buttonAction ?></a>  
		<?php endif; ?>
		
	</div>
</div>
