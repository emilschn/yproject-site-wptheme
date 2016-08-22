<?php 
/**
 * Vérifie si l'utilisateur essaie de passer à l'étape suivante
 */
function check_next_step(){
    global $status,
             $campaign,
             $post_campaign;
     if (isset($_POST['next_step'])&& ($_POST['next_step']==1 || $_POST['next_step']==2) && $campaign->can_go_next_status()){

         //Préparation -> Avant-premiere
         if ($status==ATCF_Campaign::$campaign_status_preparing){
             if($_POST['next_step']==1){
                 $campaign->set_status(ATCF_Campaign::$campaign_status_vote);
                 $campaign->set_validation_next_step(0);
             //Préparation -> Vote
             }
         } //Avant-première -> Vote
         if (($status==ATCF_Campaign::$campaign_status_vote)||(($status==ATCF_Campaign::$campaign_status_preparing)&&($_POST['next_step']==2))) {
             $orga_done=false;
             $api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
             $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);

             //Vérifiation organisation complète
             if (isset($current_organisations) && count($current_organisations) > 0) {
                 $campaign_organisation = $campaign->get_organisation();
                 $organization_obj = new YPOrganisation($campaign_organisation->organisation_wpref);

                 if ($organization_obj->is_registered_lemonway_wallet()) { $orga_done = true; }
             }

             if($orga_done && ypcf_check_user_is_complete($campaign->post_author())&& isset($_POST['innbdayvote'])){
                $vote_time = $_POST['innbdayvote'];
                if(10<=$vote_time && $vote_time<=30){
                    //Fixe date fin de vote
                    $diffVoteDay = new DateInterval('P'.$vote_time.'D');
                    $VoteEndDate = (new DateTime())->add($diffVoteDay);
                    //$VoteEndDate->setTime(23,59);
                    $campaign->set_end_vote_date($VoteEndDate);     

                    $campaign->set_status(ATCF_Campaign::$campaign_status_vote);
                    $campaign->set_validation_next_step(0);
                }
             }

         } //Vote -> Collecte
         else if ($status==ATCF_Campaign::$campaign_status_vote) {
             if(isset($_POST['innbdaycollecte']) && isset($_POST['inendh']) && isset($_POST['inendm'])){
                //Recupere nombre de jours et heure de fin de la collecte
                $collecte_time = $_POST['innbdaycollecte'];
                $collecte_fin_heure = $_POST['inendh'];
                $collecte_fin_minute = $_POST['inendm'];

                $api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
                $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
                
                 if( 1<=$collecte_time && $collecte_time<=60
                        && 0<=$collecte_fin_heure && $collecte_fin_heure<=23
                        && 0<=$collecte_fin_minute && $collecte_fin_minute<=59){
                     //Fixe la date de fin de collecte
                     $diffCollectDay = new DateInterval('P'.$collecte_time.'D');
                     $CollectEndDate = (new DateTime())->add($diffCollectDay);
                     $CollectEndDate->setTime($collecte_fin_heure,$collecte_fin_minute);
                     $campaign->set_end_date($CollectEndDate);
                     $campaign->set_begin_collecte_date(new DateTime());

                     $campaign->set_status(ATCF_Campaign::$campaign_status_collecte);
                     $campaign->set_validation_next_step(0);
                 }
             }
         }
         $status = $campaign->campaign_status();
     }
 }

 /**
  * Lightbox de bienvenue à la première visite, Cache la LB pour les admins
  */
function print_welcome_lightbox(){
    global $campaign;
    if(!$campaign->get_has_been_welcomed() && !current_user_can('manage_options')){
            ob_start();
            locate_template('common/dashboard-welcome-lightbox.php',true);
            $content = ob_get_contents();
            ob_end_clean();
            ?>	
            <div id="lightbox-welcome" class="wdg-lightbox">
                <div class="wdg-lightbox-padder">
                    <?php echo $content; ?>
                </div>
            </div>
    <?php 
        $campaign->set_has_been_welcomed(1);
    }
}

function  block_summary_lightbox(){
    echo do_shortcode('[yproject_gonextstep_lightbox]');
}

function print_block_summary() { 
    global $campaign,
            $campaign_id,
            $status,
            $stylesheet_directory_uri,
            $campaign_id_param,
            $params_partial;
    
    $page_parameters = get_page_by_path('parametres-projet'); ?>
<div id="block-summary" >
    <div class="current-step">
        <img src="<?php echo $stylesheet_directory_uri; ?>/images/frise-preview.png" alt="" /><br>
        <span <?php if($status==ATCF_Campaign::$campaign_status_preparing){echo 'id="current"';} ?>>Pr&eacute;paration </span>
        <span <?php if($status==ATCF_Campaign::$campaign_status_vote){echo 'id="current"';} ?>>Avant-premi&egrave;re </span>
        <span <?php if($status==ATCF_Campaign::$campaign_status_vote){echo 'id="current"';} ?>>Vote </span>
        <span <?php if($status==ATCF_Campaign::$campaign_status_collecte){echo 'id="current"';} ?>>Collecte </span>
        <span <?php if($status==ATCF_Campaign::$campaign_status_funded){echo 'id="current"';} ?>>R&eacute;alisation</span>
    </div>

    <div class="list-button">
        <?php if ($status==ATCF_Campaign::$campaign_status_preparing
            ||$status==ATCF_Campaign::$campaign_status_vote
            ||$status==ATCF_Campaign::$campaign_status_vote){?>
            <?php if (current_user_can('manage_options')) {
                //Visible uniquement par admins : autoriser le PP à passer à l'étape suivante
                if(isset($_GET['validate_next_step'])){
                    $campaign->set_validation_next_step($_GET['validate_next_step']);
                }
                if($campaign->can_go_next_status()){?>
                    <a href="?campaign_id=<?php echo $campaign_id?>&validate_next_step=0" class="button">&cross; Ne plus autoriser &agrave; passer &agrave; l'&eacute;tape suivante</a>
                <?php } else {?>
                    <a href="?campaign_id=<?php echo $campaign_id?>&validate_next_step=1" class="button">&check; Autoriser &agrave; passer &agrave; l'&eacute;tape suivante</a>
                <?php }
            }?>
            <a href="#gonextstep" class="wdg-button-lightbox-open button" data-lightbox="gonextstep">&check; Passer &agrave; l'&eacute;tape suivante</a>
            <?php }?>

        <a href="<?php echo get_permalink($page_parameters->ID) . $campaign_id_param . $params_partial; ?>" class="button"><?php _e('&#128295; Param&egrave;tres', 'yproject'); ?></a>
    </div>
</div>
<?php } 
?>