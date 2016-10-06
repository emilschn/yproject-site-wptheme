<?php

/* 
 * Page pour la section des projets à afficher en page d'accueil
 *
 */
$campaign = atcf_get_campaign($one_project);
$campaign_status = $campaign->campaign_status();

$percent = min(100, $campaign->percent_minimum_completed(false));
$width = 100 * $percent / 100; /* taille maxi de la barre est à 100% */
?>



<!-- Encadré du projet-->    
<div class="project-framed">
    <h2 class="project-title"> <?= $one_project->post_title ?> </h2>
    <div class="project-img" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>')"<?php } ?>></div>
    <div class="project-summary">
<?php        
        echo html_entity_decode($campaign->summary()); 
?>   
    </div>
    
<?php 
    if ($campaign->get_jycrois_nb() == 1){
        $persStatus = "suit le projet";
        }
    else if($campaign->get_jycrois_nb() > 1){
        $persStatus = "suivent le projet";
    }
    //Projets en cours de collecte ou en vote
    if($campaign_status !== ATCF_Campaign::$campaign_status_funded){
        
        substr($campaign->time_remaining_str(),2) == "0" || substr($campaign->time_remaining_str(),2) == "1" ? $days = " jour" : $days = " jours";

        if ($campaign_status === ATCF_Campaign::$campaign_status_collecte){  
            $timeRemaining = substr($campaign->time_remaining_str(),2);
            $projectAction = " pour investir";
            $buttonAction = "investir sur ce projet";
?>
            <div class="progress-bar">
                <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;<p><?php echo $campaign->current_amount(); ?></p>&nbsp;</span>
                <span class="progress-percent"><p><?php echo $campaign->percent_minimum_completed(); ?></p></span>          
            </div>
<?php
        }   
        else if ($campaign_status === ATCF_Campaign::$campaign_status_vote){
            $timeRemaining = $campaign->end_vote_remaining();
            $projectAction = " pour voter";
            $buttonAction = "voter sur ce projet";
?>
            <div class="progress-bar">
                <span class="vote-status" style="min-width:100%">&nbsp;<p>projet en cours de vote</p>&nbsp;</span>        
            </div>
    <?php
        }
    ?>
        
        <div class="progress-info">
            <span class="progress-pers"><p class="info-nb"><?php echo $campaign->get_jycrois_nb(); ?>&nbsp;pers.</p><p class="info-action"><?= $persStatus ?></p></span>
            <span class="progress-days"><p class="info-nb"><?php echo $timeRemaining; echo $days ?></p><p class="info-action"><?= $projectAction ?></p></span>
        </div>
        <button class="button big project-button"><?= $buttonAction ?><a href=""></a></button>
<?php
    }  
    //Projets déja financés
    else if($campaign_status === ATCF_Campaign::$campaign_status_funded ){
        $projectStatus = "projet</br>financé !";
        $buttonAction = "découvrir ce projet"; // plus d'info sur ce projet
?>
        <div class="progress-bar">
            <span class="current-amount" style="min-width:<?php echo $width; ?>%">&nbsp;
                <p><?php echo $campaign->current_amount(); ?> : <?php echo $campaign->percent_completed(); ?></p>&nbsp;
            </span>        
        </div>
        <div class="progress-info">
            <span class="progress-pers"><p class="info-nb"><?php echo $campaign->get_jycrois_nb(); ?>&nbsp;pers.</p><p class="info-action"><?= $persStatus ?></p></span>
            <span class="progress-status"><p class="info-nb"><?= $projectStatus ?></p></span>
        </div>
        <button class="button big project-button see-project"><?= $buttonAction ?><a href=""></a></button>    
<?php
    }
?>
           
</div> <!-- .project-framed -->