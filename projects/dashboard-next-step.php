<?php
$campaign = atcf_get_current_campaign();
?>
<h2 style='text-align:center'>Pr&ecirc;t pour la suite ?</h2>
<form method="POST" action="?campaign_id=<?php echo $campaign->ID ?>">
    <ul>
        <?php if ($campaign->campaign_status() == 'preparing') { ?>
        <p>L'avant premi&egrave;re permet d'&ecirc;tre visible sur le site wedogood.co avant le lancement de la campagne. 
            Les internautes pourront d&eacute;couvrir une partie de votre projet.</p>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman11">
                <label for="cbman11">J'ai compl&eacute;t&eacute; ma page projet</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman12">
                <label for="cbman12">J'ai &eacute;tabli des actions de communication cibl&eacute;es pour informer du lancement de la campagne : r&eacute;seaux sociaux, mails, &eacute;venements ...</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman13">
                <label for="cbman13">Je suis pr&ecirc;t &agrave; passer en avant-premi&egrave;re</label></li>

        <?php } else if ($campaign->campaign_status() == 'preview') { ?>
            <p>Pour réussir la phase de vote, je dois :</p>
            <ul id="vote-goals">
                <li>R&eacute;unir au moins <strong><?php echo ATCF_Campaign::$voters_min_required?></strong> votants</li>
                <li>Avoir au moins <strong><?php echo ATCF_Campaign::$vote_score_min_required;?>%</strong> de vote positif</li>
                <li>Avoir au moins <strong>50%</strong> de promesse d'investissement</li>
            </ul>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman21">
                <label for="cbman21">J'ai préparé des messages à envoyer par mail et à publier sur les réseaux sociaux dans l'heure</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman22">
                <label for="cbman22">J'ai planifié des rencontres et des prises de contact pour parler de mon projet et de ma campagne</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman23">
                <label for="cbman23">Je suis prêt à parler de ma campagne à tout moment et en tout lieu (présence à des évènements, discussions avec mes proches et mes partenaires...)</label></li>

            <li><input type="checkbox" class="checkbox-next-step" id="cbinfospersos" disabled
                       <?php if(ypcf_check_user_is_complete($campaign->post_author())){
                        echo "checked";
                       }?>>
                <label for="cbinfospersos">L'auteur du projet,
                    <?php print_r(get_user_by('id', $campaign->post_author())->get('display_name'));?>,
                    a rempli ses informations personnelles</label></li>

        <?php } else if ($campaign->campaign_status() == 'vote') { ?>
            <p>Le moment de la collecte est arrivé !</p>
            <li><label for="innbday">Nombre de jours de la collecte </label>
                <input type="number" id="innbday" name="innbday" min="1" max="60" value="30" style="width: 40px;">
                 Fin de la collecte : <span id="previewenddatecollecte"></span>
            </li>
            <li>
                <label for="innbday">Heure de fin de collecte </label>
                <input type="number" id="inendh" name="inendh" min="0" max="23" value="23" style="width: 40px;">h  
                <input type="number" id="inendm" name="inendm" min="0" max="59" value="59" style="width: 40px;">
            </li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman31">
                <label for="cbman31">Ma carte bancaire est prête pour être le premier investisseur</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman32">
                <label for="cbman32">J'ai préparé un mail prêt à être envoyé à un nombre significatif de personnes</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman33">
                <label for="cbman33">Je suis prêt à faire chauffer mon téléphone dans les minutes qui viennent</label></li>

            <li><input type="checkbox" class="checkbox-next-step" id="cbinfosorga" disabled
                <?php if ($campaign->company_name()!=null&&$campaign->company_status()!=null){
                    echo "checked";
                }?>>
                <label for="cbinfosorga" >J'ai déterminé l'organisation du projet</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbvotefin" disabled
                <?php if($campaign->is_validated_by_vote() && $campaign->end_vote_remaining()<=0){echo "checked";}
                ?>>
                <label for="cbvotefin">Le vote est terminé et le projet a été validé</label></li>

        <?php } ?>
        <li><input type="checkbox" class="checkbox-next-step" id="cbcannext" disabled 
            <?php if ($campaign->can_go_next_step()) {
            echo 'checked ';
        } ?>>
            <label for="cbcannext">L'équipe WE DO GOOD a validé pour passer à l'étape suivante</label></li>
    </ul>
    <div class="list-button">
        <input type="hidden" name="next_step" value="1">
        <input type="submit" value="C'est parti !" class="button" id="submit-go-next-step">
    </div>


</form>