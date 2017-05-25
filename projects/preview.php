<?php
global $stylesheet_directory_uri, $project_id;
/* 
 * Page pour la section des projets à afficher en page d'accueil
 *
 */
$campaign = atcf_get_campaign( $project_id );
$img = $campaign->get_home_picture_src();
$campaign_status = $campaign->campaign_status();
$campaign_categories_str = $campaign->get_categories_str();

$percent = min(100, $campaign->percent_minimum_completed(false));
$width = 100 * $percent / 100; // taille maxi de la barre est à 100%
?>


<div class="project-container" id="project-<?php echo $project_id ?>" data-step="<?php echo $campaign_status; ?>" data-location="<?php echo $campaign->get_location_number(); ?>" data-categories="<?php echo $campaign_categories_str; ?>">
    <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
        <div class="impacts-container" id="impacts-<?php echo $project_id ?>">
			<?php if (strpos($campaign_categories_str, 'environnemental') !== FALSE): ?>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-env.png" alt="impact environnemental" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact environnemental', 'yproject')?></span>
			<?php endif; ?>
			<?php if (strpos($campaign_categories_str, 'social') !== FALSE): ?>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-social.png" alt="impact social" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact social', 'yproject')?></span>
			<?php endif; ?>
			<?php if (strpos($campaign_categories_str, 'economique') !== FALSE): ?>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/impact-eco.png" alt="impact économique" width="42" height="42" class="impact-logo" /><span class="info-bulle invisible"><?php _e('impact économique', 'yproject')?></span>
			<?php endif; ?>
        </div>
    </a>
        <div class="project-framed">
            <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
                <h2 class="project-title"> <?php echo get_the_title($project_id) ?> </h2>           
                <div class="project-img" style="background-image: url('<?php echo $img; ?>')"></div>
                <div class="project-summary">
                    <?php echo html_entity_decode($campaign->summary()); ?>   
                </div>
            </a>
        <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
        <?php 
            $jycrois = $campaign->get_jycrois_nb();
            if($jycrois > 1){
                $persStatus = __("suivent le projet", "yproject");
            }
            else if ($jycrois == 1){
                $persStatus = __("suit le projet", "yproject");
            }
            else if ($jycrois == 0){ // voir si utile, car si 0 backers, on a tout de même 1 pers qui s'affiche
                $jycrois = false;
                $persStatus = __("Soyez le 1er", "yproject") . '<br />' . __("&agrave; suivre le projet", "yproject");
            }

            //Projets en cours de collecte ou en vote
            if($campaign_status !== ATCF_Campaign::$campaign_status_funded):
				$time_remaining_str = $campaign->time_remaining_str();
				if ($time_remaining_str != '-') {
					$time_remaining_str_split = explode('-', $time_remaining_str);
					$time_remaining_str = ($time_remaining_str_split[1] + 1) . ' ';
					$time_remaining_str_unit = $time_remaining_str_split[0];
					switch ($time_remaining_str_split[0]) {
						case 'J': $time_remaining_str .= __("jours", "yproject"); break;
						case 'H': $time_remaining_str .= __("heures", "yproject"); break;
						case 'M': $time_remaining_str .= __("minutes", "yproject"); break;
					}
				}
				

                if ($campaign_status === ATCF_Campaign::$campaign_status_collecte):
                    $projectAction = __(" pour investir", "yproject");
                    $buttonAction = __("investir sur ce projet", "yproject");
        ?>              
                    <div class="progress-bar">
                        <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;<span><?php echo $campaign->current_amount(); ?></span>&nbsp;</span>
                        <span class="progress-percent"><span><?php echo $campaign->percent_minimum_completed(); ?></span></span>          
                    </div>
        <?php

                elseif ($campaign_status === ATCF_Campaign::$campaign_status_vote):
                    $projectAction = __(" pour voter", "yproject");
                    $buttonAction = __("voter sur ce projet", "yproject");
        ?>
                    <div class="progress-bar">
                        <span class="vote-status" style="min-width:100%">&nbsp;
							<span>
							<?php if ($time_remaining_str != '-'): ?>
							<?php _e("projet en cours de vote", "yproject"); ?>
							<?php else: ?>
							<?php _e("vote termin&eacute;", "yproject"); ?>
							<?php endif; ?>
							</span>
						&nbsp;</span>        
                    </div>
                <?php endif; ?>
        </a>
        <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
                <div class="progress-info">
                    <span class="progress-pers"><?php if($jycrois): ?><span class="info-nb"><?php echo $jycrois; ?>&nbsp;pers.</span><?php endif; ?><span class="info-action"><?php echo $persStatus ?></span></span>
                    <span class="progress-days"><span class="info-nb"><?php echo $time_remaining_str; ?></span><span class="info-action"><?php echo $projectAction ?></span></span>
                </div>
        </a>
		<a class="home-button-project project-button" href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $buttonAction ?></a>
        <?php

            //Projets déja financés
            else :
                $projectStatus = __("projet</br>financé !", "yproject");
                $buttonAction = __("découvrir ce projet", "yproject"); // vers plus d'info sur ce projet
        ?>
            <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
                <div class="progress-bar">
                    <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;
                        <span><?php echo $campaign->current_amount(); ?> : <?php echo $campaign->percent_minimum_completed(); ?></span>&nbsp;
                    </span>        
                </div>
                <div class="progress-info">
                    <span class="progress-pers"><span class="info-nb"><?php echo $campaign->get_jycrois_nb(); ?>&nbsp;<?php _e("pers.", "yproject") ?></span><span class="info-action"><?php echo $persStatus ?></span></span>
                    <span class="progress-status"><span class="info-nb"><?php echo $projectStatus ?></span></span>
                </div>
            </a>          
                <a class="home-button-project project-button see-project" href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $buttonAction ?></a>  
        <?php endif; ?>
                
        </div> <!-- .project-framed -->
</div> <!-- .project-container -->
