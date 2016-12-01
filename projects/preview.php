<?php

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
                    <?php if (strpos($campaign_categories_str, 'environnemental') != FALSE): ?>
            <span class="impact-logo impact-ecologic" id="impact-ecologic-<?php echo $project_id ?>"><p>ecl</p></span>
                    <?php endif; ?>
                    <?php if (strpos($campaign_categories_str, 'social') != FALSE): ?>
            <span class="impact-logo impact-social" id="impact-social-<?php echo $project_id ?>"><p>soc</p></span>
                    <?php endif; ?>
                    <?php if (strpos($campaign_categories_str, 'economique') != FALSE): ?>
            <span class="impact-logo impact-economic" id="impact-economic-<?php echo $project_id ?>"><p>ecn</p></span>
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
                $daysRemaining = $campaign->days_remaining();
                $daysRemaining == "0" || $daysRemaining == "1" ? $days = __(" jour", "yproject") : $days = __(" jours", "yproject");

                if ($campaign_status === ATCF_Campaign::$campaign_status_collecte):  
                    $timeRemaining = $daysRemaining;
                    $projectAction = __(" pour investir", "yproject");
                    $buttonAction = __("investir sur ce projet", "yproject");
        ?>              
                    <div class="progress-bar">
                        <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;<p><?php echo $campaign->current_amount(); ?></p>&nbsp;</span>
                        <span class="progress-percent"><p><?php echo $campaign->percent_minimum_completed(); ?></p></span>          
                    </div>
        <?php

                elseif ($campaign_status === ATCF_Campaign::$campaign_status_vote):
                    $timeRemaining = $campaign->end_vote_remaining();
                    $projectAction = __(" pour voter", "yproject");
                    $buttonAction = __("voter sur ce projet", "yproject");
        ?>
                    <div class="progress-bar">
                        <span class="vote-status" style="min-width:100%">&nbsp;<p><?php _e("projet en cours de vote", "yproject"); ?></p>&nbsp;</span>        
                    </div>
                <?php endif; ?>
        </a>
        <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
                <div class="progress-info">
                    <span class="progress-pers"><?php if($jycrois): ?><p class="info-nb"><?php echo $jycrois; ?>&nbsp;pers.</p><?php endif; ?><p class="info-action"><?php echo $persStatus ?></p></span>
                    <span class="progress-days"><p class="info-nb"><?php echo $timeRemaining; echo $days ?></p><p class="info-action"><?php echo $projectAction ?></p></span>
                </div>
        </a>
                            <a class="home-button-project project-button" href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $buttonAction ?></a>
        <?php

            //Projets déja financés
            elseif($campaign_status === ATCF_Campaign::$campaign_status_funded ):
                $projectStatus = __("projet</br>financé !", "yproject");
                $buttonAction = __("découvrir ce projet", "yproject"); // vers plus d'info sur ce projet
        ?>
            <a class="hidden-link" href="<?php echo get_permalink($campaign->ID); ?>">
                <div class="progress-bar">
                    <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;
                        <p><?php echo $campaign->current_amount(); ?> : <?php echo $campaign->percent_completed(); ?></p>&nbsp;
                    </span>        
                </div>
                <div class="progress-info">
                    <span class="progress-pers"><p class="info-nb"><?php echo $campaign->get_jycrois_nb(); ?>&nbsp;<?php _e("pers.", "yproject") ?></p><p class="info-action"><?php echo $persStatus ?></p></span>
                    <span class="progress-status"><p class="info-nb"><?php echo $projectStatus ?></p></span>
                </div>
            </a>          
                <a class="home-button-project project-button see-project" href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $buttonAction ?></a>  
        <?php endif; ?>
                
        </div> <!-- .project-framed -->
</div> <!-- .project-container -->
