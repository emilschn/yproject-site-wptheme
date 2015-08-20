<?php 

/**
 * Vérifie si l'utilisateur essaie d'envoyer des mails via la lightbox dashboard-mail
 */
function check_send_mail(){
    if (isset($_POST['send_mail'])&& ($_POST['send_mail']==1)){
        global $campaign_id, $feedback;
        $feedback = "";
        if ((isset($_POST['mail_title']) && isset($_POST['mail_content']))
                &&($_POST['mail_title']!='' && $_POST['mail_content']!='')){
            $jycrois = isset($_POST['jycrois']) && ($_POST['jycrois']=='on');
            $voted = isset($_POST['voted']) && ($_POST['voted']=='on');
            $invested = isset($_POST['invested']) && ($_POST['invested']=='on');
            $id_investors_list = explode(",", $_POST['investors_id']);
            //Au moins une catégorie sélectionnée
            if ($jycrois || $voted || $invested){
                $feedback_email = NotificationsEmails::project_mail($campaign_id, 
                        $_POST['mail_title'], 
                        $_POST['mail_content'], 
                        $jycrois, 
                        $voted, 
                        $invested,
                        $id_investors_list);
                
                $nb_try = count($feedback_email);
                $nb_errors = $nb_try - count(array_filter($feedback_email));
                if ($nb_errors <= 0){
                    $feedback .= "Les messages ont &eacute;t&eacute; correctement envoy&eacute;s !";
                } else {
                    $feedback .= "Les messages ont &eacute;t&eacute; envoy&eacute;s mais des erreurs ont eu lieu.";
                }
                
            } else {
                $feedback .= "Vous n'avez pas s&eacute;lectionn&eacute; de groupe &agrave; qui envoyer le message. ";
            }
        } else {
            $feedback .= "Il faut donner un objet et un contenu &agrave; votre mail. ";
        }
    }
    return $feedback;
}

/**
 * Charge des données : nombre de j'y crois, de votes et d'investissements
 * @global type $nb_jcrois
 * @global type $nb_votes
 * @global type $nb_invests
 */
function block_community_data(){
    global $campaign, 
            $nb_jcrois, $nb_votes, $nb_invests;
    //Recuperation du nombre de j'y crois
        $nb_jcrois = $campaign->get_jycrois_nb();
    //Recuperation du nombre de votants
        $nb_votes = $campaign->nb_voters();
    //Recuperation du nombre d'investisseurs
        $nb_invests = $campaign->backers_count();
}

function block_community_lightbox(){
    echo do_shortcode('[yproject_votecontact_lightbox]');
    echo do_shortcode('[yproject_listinvestors_lightbox]');
    echo do_shortcode('[yproject_dashboardmail_lightbox]');
}

function print_block_community() { 
    global $campaign,
            $nb_jcrois,
            $nb_votes,
            $nb_invests,
            $stylesheet_directory_uri; ?>

<div id ="block-community" class="block">
    <div class="head">Communaut&eacute;</div>
    <div class="body" style="text-align:center">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/><br/>
            <strong><?php echo $nb_jcrois?></strong> y croi<?php if($nb_jcrois>1){echo 'en';}?>t</div>
        <a href="#votecontact" class="wdg-button-lightbox-open" data-lightbox="votecontact">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/><br/>
            <strong><?php echo $nb_votes?></strong> <?php if($nb_votes>1){echo 'ont';} else {echo 'a';}?> vot&eacute;
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></div>
            </a>
        <a href="#listinvestors" class="wdg-button-lightbox-open" data-lightbox="listinvestors">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/><br/>
            <strong><?php echo $nb_invests?></strong> <?php if($nb_invests>1){echo 'ont';} else {echo 'a';}?> <?php echo $campaign->funding_type_vocabulary()['investor_verb'];?>
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></div>
            </a>
    <div class="clear"></div>
        <div class="list-button">
           <a href="#dashboardmail" class="wdg-button-lightbox-open button" data-lightbox="dashboardmail">&#9993; Envoyer un mail</a>
        </div>
    </div>
</div>

<?php } ?>