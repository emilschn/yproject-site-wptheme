<?php



function check_change_status(){
    global $can_modify,
           $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;
    $status = $campaign->campaign_status();

    if ($can_modify
        && isset($_POST['next_status'])
        && ($_POST['next_status']==1 || $_POST['next_status']==2)){

        if ($status==ATCF_Campaign::$campaign_status_preparing && $is_admin){
            //Préparation -> Validé (pour les admin seulement)
            $campaign->set_status(ATCF_Campaign::$campaign_status_validated);
            $campaign->set_validation_next_status(0);

        } else if ($campaign->can_go_next_status()){
            if ($status==ATCF_Campaign::$campaign_status_validated && ($_POST['next_status']==1)){
                //Validé -> Avant-première
                $campaign->set_status(ATCF_Campaign::$campaign_status_preview);
                $campaign->set_validation_next_status(0);

            } else if ($status==ATCF_Campaign::$campaign_status_preview
                || ($status==ATCF_Campaign::$campaign_status_validated &&($_POST['next_status']==2))){
                //Validé/Avant-première -> Vote

                //Vérifiation organisation complète
                $orga_done=false;
                $api_project_id = BoppLibHelpers::get_api_project_id($campaign_id);
                $current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);

                if (isset($current_organisations) && count($current_organisations) > 0) {
                    $campaign_organisation = $campaign->get_organisation();

                    //Vérification validation lemonway
                    $organization_obj = new YPOrganisation($campaign_organisation->organisation_wpref);
                    if ($organization_obj->is_registered_lemonway_wallet()) { $orga_done = true; }
                }

                //Validation données
                if($orga_done && ypcf_check_user_is_complete($campaign->post_author())&& isset($_POST['innbdayvote'])){
                    $vote_time = $_POST['innbdayvote'];
                    if(10<=$vote_time && $vote_time<=30){
                        //Fixe date fin de vote
                        $diffVoteDay = new DateInterval('P'.$vote_time.'D');
                        $VoteEndDate = (new DateTime())->add($diffVoteDay);
                        //$VoteEndDate->setTime(23,59);
                        $campaign->set_end_vote_date($VoteEndDate);

                        $campaign->set_status(ATCF_Campaign::$campaign_status_vote);
                        $campaign->set_validation_next_status(0);
                    }
                }


            } else if ($status==ATCF_Campaign::$campaign_status_vote){
                //Vote -> Collecte
                if(isset($_POST['innbdaycollecte'])
                    && isset($_POST['inendh'])
                    && isset($_POST['inendm'])){
                    //Recupere nombre de jours et heure de fin de la collecte
                    $collecte_time = $_POST['innbdaycollecte'];
                    $collecte_fin_heure = $_POST['inendh'];
                    $collecte_fin_minute = $_POST['inendm'];

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
                        $campaign->set_validation_next_status(0);
                    }
                }
            }
        }
    }
}

function print_resume_page()
{
    global $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current,
           $is_admin, $is_author;

    $status = $campaign->campaign_status();

    ?>
    <div class="head"><?php _e('R&eacute;sum&eacute; du projet', 'yproject'); ?></div>
    <div id="status-list">
        <?php
        $status_list = ATCF_Campaign::get_campaign_status_list();
        $nb_status = count($status_list)-1;
        $i=1; ?>
        <div class="perso <?php if($status==ATCF_Campaign::$campaign_status_preparing){echo ' preparing ';}?>"></div>
        <?php foreach ($status_list as $status_key => $name) {
            ?><div class="status
                    <?php   if($i==1){echo "begin ";}
                            if($i==$nb_status){echo "end ";}?>"
                   <?php if($status_key==$status){echo 'id="current"';}?>>
                <div class="line
                    <?php   if($i==1){echo "begin ";}
                            if($i==$nb_status){echo "end ";}
                            if($i>$nb_status){echo "none ";}?>">

                </div>
                <?php
                switch ($status_key){
                    case ATCF_Campaign::$campaign_status_validated:
                        echo '<div class="linetoright"></div>';
                        break;
                    case ATCF_Campaign::$campaign_status_preview:
                        echo '<div class="linetoboth"></div>';
                        break;
                    case ATCF_Campaign::$campaign_status_vote:
                        echo '<div class="linetoleft"></div>';
                        break;
                }
                ?>
                <div class="icon-etape">

                </div>
                <div class="name">
                    <?php echo $name.'<br/>';
                    switch ($status_key){
                        case ATCF_Campaign::$campaign_status_preparing:
                            DashboardUtility::get_infobutton("Pendant la pr&eacute;paration, pr&eacute;sentez votre projet à WDG.",true,false);
                            break;
                        case ATCF_Campaign::$campaign_status_validated:
                            DashboardUtility::get_infobutton("Une fois validé, créez la page du projet qui sera visible sur le site.",true,false);
                            break;
                        case ATCF_Campaign::$campaign_status_preview:
                            DashboardUtility::get_infobutton("L'avant-première fait découvrir votre projet sur le site. Cette étape est facultative",true,false);
                            break;
                        case ATCF_Campaign::$campaign_status_vote:
                            DashboardUtility::get_infobutton("Le vote sert à confirmer votre capacité à fédérer vos cercles d'investisseurs",true,false);
                            break;
                        case ATCF_Campaign::$campaign_status_collecte:
                            DashboardUtility::get_infobutton("Ici, on récolte les sous",true,false);
                            break;
                        case ATCF_Campaign::$campaign_status_funded:
                            DashboardUtility::get_infobutton("TOUS LES SOUS!!!!",true,false);
                            break;
                    }
                    ?>
                </div>
            </div><?php $i++; } ?>
    </div>

    <div class="tab-content" id="next-status-tab">
        <?php if($status == ATCF_Campaign::$campaign_status_preparing
            || $status == ATCF_Campaign::$campaign_status_validated
            || $status == ATCF_Campaign::$campaign_status_preview
            || ($status == ATCF_Campaign::$campaign_status_vote && $campaign->end_vote_remaining()<=0)){ ?>
        <h2 style='text-align:center'>Pr&ecirc;t pour la suite ?</h2>

        <form method="POST" action="">
            <ul>
                <?php if ($status == ATCF_Campaign::$campaign_status_preparing) { ?>
                    <p id="desc-preview">(texte à peut-être changer) Pour savoir si votre projet il est bien ou bien
                        il est pas bien il va &ecirc;tre examin&eacute; par le conseil de s&eacute;lection,
                        il va falloir remplir des informations avant!!!!</p>

                    <li><label><input type="checkbox" class="checkbox-next-status" disabled
                                <?php if(ypcf_check_user_is_complete($campaign->post_author())){
                                    echo "checked";
                                }?>>
                            L'auteur du projet, <?php print_r(get_user_by('id', $campaign->post_author())->get('display_name'));?>,
                            a rempli ses informations personnelles</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status" disabled
                                <?php
                                $campaign_organisation = $campaign->get_organisation();
                                if ($campaign_organisation) {
                                    echo "checked";
                                }?>>
                            J'ai d&eacute;termin&eacute; l'organisation du projet</label>
                    </li>

                <?php }
                if ($status == ATCF_Campaign::$campaign_status_validated) { ?>
                    <p id="desc-preview">L'avant premi&egrave;re permet d'&ecirc;tre visible sur le site wedogood.co avant le lancement de la campagne.
                        Les internautes pourront d&eacute;couvrir une partie de votre projet.</p>
                    <li><label><input type="checkbox" class="checkbox-next-status" disabled
                                <?php if(ypcf_check_user_is_complete($campaign->post_author())){
                                    echo "checked";
                                }?>>
                            L'auteur du projet, <?php print_r(get_user_by('id', $campaign->post_author())->get('display_name'));?>,
                            a rempli ses informations personnelles</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status" disabled
                                <?php
                                $campaign_organisation = $campaign->get_organisation();
                                if ($campaign_organisation) {
                                    echo "checked";
                                }?>>
                            J'ai d&eacute;termin&eacute; l'organisation du projet</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status">
                            J'ai compl&eacute;t&eacute; ma page projet</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status">
                            J'ai &eacute;tabli des actions de communication cibl&eacute;es pour informer du lancement de la campagne : r&eacute;seaux sociaux, mails, &eacute;venements ...</label>
                    </li>
                    <li id="cb-go-preview"><label><input type="checkbox" class="checkbox-next-status">
                            Je suis pr&ecirc;t &agrave; passer en avant-premi&egrave;re</label>
                    </li>


                <?php }
                if (($status == ATCF_Campaign::$campaign_status_preview)
                    || ($status == ATCF_Campaign::$campaign_status_validated)) { ?>
                    <div id="vote-checklist"<?php if ($status == ATCF_Campaign::$campaign_status_validated) { echo 'hidden=""'; } ?>>
                        <p>Pour r&eacute;ussir la phase de vote, je dois :</p>
                        <ul id="vote-goals">
                            <li>R&eacute;unir au moins <strong><?php echo ATCF_Campaign::$voters_min_required?></strong> votants</li>
                            <li>Avoir au moins <strong><?php echo ATCF_Campaign::$vote_score_min_required;?>%</strong> de vote positif</li>
                            <li>Avoir au moins <strong>50%</strong> d'intentions d'investissement de l'objectif de collecte (recommand&eacute;)</li>
                        </ul>
                        <li><label>Nombre de jours du vote :
                                <input type="number" id="innbdayvote" name="innbdayvote" min="10" max="30" value="30" style="width: 40px;"></label>
                            Fin du vote : <span id="previewenddatevote"></span>
                        </li>
                        <li><label><input type="checkbox" class="checkbox-next-status">
                                J'ai pr&eacute;par&eacute; des messages &agrave; envoyer par mail et &agrave; publier sur les r&eacute;seaux sociaux dans l'heure</label>
                        </li>
                        <li><label><input type="checkbox" class="checkbox-next-status">
                                J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma campagne</label>
                        </li>
                        <li><label><input type="checkbox" class="checkbox-next-status">
                                Je suis prêt &agrave; parler de ma campagne &agrave; tout moment et en tout lieu (pr&eacute;sence &agrave; des &eacute;v&egrave;nements, discussions avec mes proches et mes partenaires...)</label>
                        </li>

                        <li><label><input type="checkbox" class="checkbox-next-status" disabled
                                    <?php
                                    $organization_obj = new YPOrganisation($campaign_organisation->organisation_wpref);
                                    if ($organization_obj->is_registered_lemonway_wallet()) { echo "checked"; }
                                    ?>>
                                L'organisation est authentifi&eacute;e par le prestataire de paiement.
                                <?php DashboardUtility::get_infobutton("Une fois les documents transmis dans la partie &quot;entreprise&quot;, le prestataire de paiement sécurisé doit valider votre compte. Cela peut prendre quelques jours.",true)?></label>
                        </li>
                    </div>


                <?php }
                else if ($status == ATCF_Campaign::$campaign_status_vote) { ?>
                    <p>Le moment de la collecte est arriv&eacute; !</p>
                    <li><label>Nombre de jours de la collecte :
                            <input type="number" id="innbdaycollecte" name="innbdaycollecte" min="1" max="60" value="30" style="width: 40px;"></label>
                        Fin de la collecte : <span id="previewenddatecollecte"></span>
                    </li>
                    <li>
                        <label>Heure de fin de collecte :
                            <input type="number" id="inendh" name="inendh" min="0" max="23" value="12" style="width: 40px;">h
                            <input type="number" id="inendm" name="inendm" min="0" max="59" value="00" style="width: 40px;"> </label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status">
                            Ma carte bancaire est prête pour être le premier investisseur</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status">
                            J'ai pr&eacute;par&eacute; un mail prêt &agrave; être envoy&eacute; &agrave; un nombre significatif de personnes</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status">
                            Je suis prêt &agrave; faire chauffer mon t&eacute;l&eacute;phone dans les minutes qui viennent</label>
                    </li>
                    <li><label><input type="checkbox" class="checkbox-next-status" id="cbvotefin" disabled
                                <?php if(($campaign->is_vote_validated() && $campaign->end_vote_remaining()<=0)|| $campaign->can_go_next_status()){echo "checked";}
                                ?>>
                            Le vote est termin&eacute; et le projet a &eacute;t&eacute; valid&eacute;</label>
                    </li>

                <?php } if ($status != ATCF_Campaign::$campaign_status_preparing) { ?>
                <li><label><input type="checkbox" class="checkbox-next-status" id="cbcannext" disabled
                            <?php if ($campaign->can_go_next_status()) {echo 'checked ';} ?>>
                        L'&eacute;quipe WE DO GOOD a valid&eacute; pour passer &agrave; l'&eacute;tape suivante</label>
                </li>
                <?php } ?>
            </ul>

            <div class="list-button">
                <?php if ($status == ATCF_Campaign::$campaign_status_preparing && $is_admin){
                    echo DashboardUtility::get_admin_infobutton()?>
                    <input type="submit" value="Valider le projet" class="button admin" id="submit-go-next-status-admin">
                <?php } else if ($status != ATCF_Campaign::$campaign_status_preparing) { ?>
                    <input type="submit" value="C'est parti !" class="button" id="submit-go-next-status">
                <?php }
                if ($status == ATCF_Campaign::$campaign_status_validated) { ?>
                    <br/><br/><a class="button" id="no-preview-button">Je ne souhaite pas d'avant-première, passons le projet en vote.</a>
                <?php }  ?>
            </div>

            <input type="hidden" name="next_status" value="1" id="next-status-choice">
        </form>
        <?php } ?>


        <form action="" id="statusmanage_form" class="db-form">
            <?php
            DashboardUtility::create_field(array(
                "id"=>"campaign_status",
                "type"=>"select",
                "label"=>"Changer l'&eacute;tape actuelle de la campagne",
                "value"=>$status,
                "editable"=> $is_admin,
                "admin_theme"=>$is_admin,
                "visible"=>$is_admin,
                "options_id"=>array_keys($status_list),
                "options_names"=>array_values($status_list),
                "warning"=>true
            ));

            DashboardUtility::create_field(array(
                "id"=>"can_go_next_status",
                "type"=>"check",
                "label"=>"Autoriser &agrave; passer &agrave; l'&eacute;tape suivante",
                "value"=> $campaign->can_go_next_status(),
                "editable"=> $is_admin,
                "admin_theme"=>$is_admin,
                "visible"=>$is_admin && ($status==ATCF_Campaign::$campaign_status_validated ||
                        $status==ATCF_Campaign::$campaign_status_preview ||
                        $status==ATCF_Campaign::$campaign_status_vote ),
                "placeholder"=>"http://....."
            ));

            DashboardUtility::create_save_button("statusmanage-form",$is_admin);
            ?>
        </form>
    </div>
    <?php

}