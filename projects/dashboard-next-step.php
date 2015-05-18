<?php
$campaign = atcf_get_current_campaign();
?>
<h2 style='text-align:center'>Pr&ecirc;t pour la suite ?</h2>
<form method="POST" action="?campaign_id=<?php echo $campaign->ID ?>">
    <ul>
        <?php if ($campaign->campaign_status() == 'preparing') { ?>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman1">
                <label for="cbman1">J'ai évalué l'intérêt de l'avant-première pour mon projet</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman2">
                <label for="cbman2">J'ai préparé des animations pour cette phase</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman3">
                <label for="cbman3">Je suis prêt à passer en vote dans les 2 semaines qui viennent</label></li>

        <?php } else if ($campaign->campaign_status() == 'preview') { ?>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman4">
                <label for="cbman4">J'ai bien compris les effets du vote sur la part de chiffre d'affaires que je devrai verser</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman5">
                <label for="cbman5">J'ai préparé des messages à envoyer par mail et à publier sur les réseaux sociaux dans l'heure</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman6">
                <label for="cbman6">J'ai préparé des animations pour la phase de vote</label></li>

            <li><input type="checkbox" class="checkbox-next-step" id="cbinfospersos" disabled
                       <?php if(ypcf_check_user_is_complete($campaign->post_author())){
                        echo "checked";
                       }?>>
                <label for="cbinfospersos">L'auteur du projet,
                    <?php print_r(get_user_by('id', $campaign->post_author())->get('display_name'));?>,
                    a rempli ses informations personnelles</label></li>

        <?php } else if ($campaign->campaign_status() == 'vote') { ?>
            <li><label for="innbday">Nombre de jours de la collecte </label>
                <input type="number" id="innbday" name="innbday" min="1" max="60" value="30" style="width: 40px;">
                Fin de la collecte : <span id="previewenddatecollecte"></span>
            </li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman7">
                <label for="cbman7">Ma carte bancaire est prête pour être le premier investisseur</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman8">
                <label for="cbman8">J'ai préparé un mail prêt à être envoyé à un nombre significatif de personnes</label></li>
            <li><input type="checkbox" class="checkbox-next-step" id="cbman9">
                <label for="cbman9">Je suis prêt à faire chauffer mon téléphone dans les minutes qui viennent</label></li>

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