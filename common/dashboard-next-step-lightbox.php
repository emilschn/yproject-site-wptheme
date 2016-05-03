<?php
$campaign = atcf_get_current_campaign();
?>
<h2 style='text-align:center'>Pr&ecirc;t pour la suite ?</h2>
<form method="POST" action="?campaign_id=<?php echo $campaign->ID ?>">
    <ul>
        <?php if ($campaign->campaign_status() == 'preparing') { ?>
        <p id="desc-preview">L'avant premi&egrave;re permet d'&ecirc;tre visible sur le site wedogood.co avant le lancement de la campagne. 
            Les internautes pourront d&eacute;couvrir une partie de votre projet.</p>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman11">
                J'ai compl&eacute;t&eacute; ma page projet</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman12">
                J'ai &eacute;tabli des actions de communication cibl&eacute;es pour informer du lancement de la campagne : r&eacute;seaux sociaux, mails, &eacute;venements ...</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman13">
                Je suis pr&ecirc;t &agrave; passer en avant-premi&egrave;re</label></li>
        <?php } if (($campaign->campaign_status() == 'preview') || ($campaign->campaign_status() == 'preparing')) { ?>
            <div id="vote-checklist"<?php if ($campaign->campaign_status() == 'preparing') { echo 'hidden=""'; } ?>>
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
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman21">
                J'ai pr&eacute;par&eacute; des messages &agrave; envoyer par mail et &agrave; publier sur les r&eacute;seaux sociaux dans l'heure</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman22">
                J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma campagne</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman23">
                Je suis prêt &agrave; parler de ma campagne &agrave; tout moment et en tout lieu (pr&eacute;sence &agrave; des &eacute;v&egrave;nements, discussions avec mes proches et mes partenaires...)</label></li>

            <li><label><input type="checkbox" class="checkbox-next-step" id="cbinfospersos" disabled
                       <?php if(ypcf_check_user_is_complete($campaign->post_author())){
                        echo "checked";
                       }?>>
                L'auteur du projet, <?php print_r(get_user_by('id', $campaign->post_author())->get('display_name'));?>,
                    a rempli <a href="<?php echo get_permalink(get_page_by_path('modifier-mon-compte')->ID); ?>">ses informations personnelles</a></label></li>
            </div>
        <?php } else if ($campaign->campaign_status() == 'vote') { ?>
            <p>Le moment de la collecte est arriv&eacute; !</p>
            <li><label>Nombre de jours de la collecte : 
                <input type="number" id="innbdaycollecte" name="innbdaycollecte" min="1" max="60" value="30" style="width: 40px;"></label>
                 Fin de la collecte : <span id="previewenddatecollecte"></span>
            </li>
            <li>
                <label>Heure de fin de collecte : 
                <input type="number" id="inendh" name="inendh" min="0" max="23" value="23" style="width: 40px;">h  
                <input type="number" id="inendm" name="inendm" min="0" max="59" value="59" style="width: 40px;"> </label>
            </li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman31">
                Ma carte bancaire est prête pour être le premier investisseur</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman32">
                J'ai pr&eacute;par&eacute; un mail prêt &agrave; être envoy&eacute; &agrave; un nombre significatif de personnes</label></li>
            <li><label><input type="checkbox" class="checkbox-next-step" id="cbman33">
                Je suis prêt &agrave; faire chauffer mon t&eacute;l&eacute;phone dans les minutes qui viennent</label></li>

            <li><label><input type="checkbox" class="checkbox-next-step" id="cbinfosorga" disabled
                <?php
				$campaign_organisation = $campaign->get_organisation();
                if ($campaign_organisation) {
                    echo "checked";
                }?>>
                J'ai d&eacute;termin&eacute; <a href="<?php echo get_permalink(get_page_by_path('parametres-projet')->ID) . '?campaign_id='.$_GET['campaign_id'] . $params_partial; ?>">l'organisation du projet</a></label></li>
			<li><label><input type="checkbox" class="checkbox-next-step" id="cborgaauth" disabled
                <?php
				$organization_obj = new YPOrganisation($organization->organisation_wpref);
				if ($organization_obj->get_lemonway_status() == YPOrganisation::$lemonway_status_registered) { echo "checked"; }
                ?>>
                L'organisation est authentifi&eacute;e.</label></li>
			<li><label><input type="checkbox" class="checkbox-next-step" id="cbvotefin" disabled
                <?php if(($campaign->is_vote_validated() && $campaign->end_vote_remaining()<=0)|| $campaign->can_go_next_step()){echo "checked";}
                ?>>
                Le vote est termin&eacute; et le projet a &eacute;t&eacute; valid&eacute;</label></li>

        <?php } ?>
        <li><label><input type="checkbox" class="checkbox-next-step" id="cbcannext" disabled 
            <?php if ($campaign->can_go_next_step()) {echo 'checked ';} ?>>
            L'&eacute;quipe WE DO GOOD a valid&eacute; pour passer &agrave; l'&eacute;tape suivante</label></li>
    </ul>
    <div class="list-button">
        <input type="hidden" name="next_step" value="1" id="next-step-choice">
        <input type="submit" value="C'est parti !" class="button" id="submit-go-next-step"><br/><br/>
        <?php if ($campaign->campaign_status() == 'preparing') { ?>
            <a class="button" id="no-preview-button">Je ne souhaite pas d'avant-première, passons le projet en vote.</a>
        <?php }  ?>
    </div>
</form>