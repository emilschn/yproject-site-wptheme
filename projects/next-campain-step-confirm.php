<?php
$campaign = atcf_get_current_campaign();
?>
<h2 style='text-align:center'>Pr&ecirc;t pour la suite ?</h2>
<div>
Popup avant-première
<br/>☑ J'ai évalué l'intérêt de l'avant-première pour mon projet
<br/>☑ J'ai préparé des animations pour cette phase
<br/>☑ Je suis prêt à passer en vote dans les 2 semaines qui viennent
<br/>
<br/>Popup vote
<br/>☑ J'ai bien compris les effets du vote sur la part de chiffre d'affaires que je devrai verser
<br/>☑ J'ai préparé des messages à envoyer par mail et à publier sur les réseaux sociaux dans l'heure
<br/>☑ J'ai préparé des animations pour la phase de vote
<br/>    ☑Bloquage (et alerte) si informations personnelles ne sont pas remplies
<br/>
<br/>Popup collecte
<br/>☑ Ma carte bancaire est prête pour être le premier investisseur
<br/>☑ J'ai préparé un mail prêt à être envoyé à un nombre significatif de personnes
<br/>☑ Je suis prêt à faire chauffer mon téléphone dans les minutes qui viennent
<br/>    ☑Bloquage (et alerte) si l'organisation n'a pas été déterminée
<br/>
<br/>Pour chaque :
<br/><input type="checkbox" id="cannext" disabled <?php if ($campaign->can_go_next_step()){echo 'checked ';}?>
       <label for="cannext">L'équipe WeDoGood a validé pour passer à l'étape suivante</label>
</div>