<?php function print_vote_results($vote_results) { ?>

<strong><?php echo $vote_results['count_voters']; ?></strong> personnes ont vot&eacute; sur votre projet.<br />

<h3>Impact et cohérence du projet</h3>
<?php //TODO : grenades ?>
<ul class="vote-results-impacts">
	<li><span>Economie :</span> <?php echo round($vote_results['average_impact_economy'], 1); ?></li>
	<li><span>Environnement :</span> <?php echo round($vote_results['average_impact_environment'], 1); ?></li>
	<li><span>Social :</span> <?php echo round($vote_results['average_impact_social'], 1); ?></li>
	<li><span>Autres :</span> <?php echo $vote_results['list_impact_others_string']; ?>
</ul>

<em>Vos impacts sont-ils suffisants pour que votre projet soit en financement sur WEDOGOOD.co ?</em><br />
<center><canvas id="canvas-pie" width="400" height="200"></canvas></center>

<strong><?php echo $vote_results['count_project_validated']; ?></strong> personnes ont vot&eacute; oui...<br />
<ul>
	<li>
	    <?php
                $total = $vote_results['count_invest_ready'] * $vote_results['average_invest_ready'];
                $pourcentage = ($total*100)/$vote_results['objective'];
            ?>
      
            Sur ces <strong><?php echo $vote_results['count_project_validated']; ?></strong> votants, <strong><?php echo $vote_results['count_invest_ready']; ?></strong> personnes ont déclaré qu'ils investiraient en moyenne <strong><?php echo round($vote_results['average_invest_ready'],2); ?> &euro;</strong>. 
            Pour un total de <strong><?php echo round($total ,2); ?></strong> euros d'intention d'investissement, soit <strong><?php echo round($pourcentage ,2).' %'; ?></strong> de l’objectif.
        </li>
	<?php if ($vote_results['show_risk']): ?>
	<li>
	    ont &eacute;valu&eacute; le risque, en moyenne, &agrave; : <strong><?php echo round($vote_results['average_risk'], 2); ?></strong> / 5<br />
	    <center><canvas id="canvas-vertical" width="300" height="200"></canvas></center>
	</li>
	<?php endif; ?>
</ul>

<h3>Remarques</h3>
Les internautes aimeraient avoir plus d’informations sur :<br />
<center><canvas id="canvas-horizontal" width="400" height="200"></canvas></center><br />
Autres informations : <br /><strong><?php echo $vote_results['string_more_info_other']; ?></strong>


<script type="text/javascript">
jQuery(document).ready( function($) {
    var ctxPie = $("#canvas-pie").get(0).getContext("2d");
    var dataPie = [
	{value: <?php echo $vote_results['count_project_validated']; ?>, color: "#FE494C", title: "Oui"}, 
	{value: <?php echo ($vote_results['count_voters'] - $vote_results['count_project_validated']); ?>, color: "#333333", title: "Non"}
    ];
    var optionsPie = {
	legend: true,
	legendBorders: false,
	inGraphDataShow : true
    };
    var canvasPie = new Chart(ctxPie).Pie(dataPie, optionsPie);
    
    <?php if ($vote_results['show_risk']): ?>
    var ctxVertical = $("#canvas-vertical").get(0).getContext("2d");
    var dataVertical = {
	labels: ["1", "2", "3", "4", "5"],
	datasets: [{
	    fillColor: "#CCC",
	    strokeColor: "#CCC",
	    data: [<?php echo $vote_results['risk_list'][1] . ',' . $vote_results['risk_list'][2] . ',' . $vote_results['risk_list'][3] . ',' . $vote_results['risk_list'][4] . ',' . $vote_results['risk_list'][5]; ?>]
	}]
    };
    var nSteps = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $vote_results['risk_list'][1]; ?>), <?php echo $vote_results['risk_list'][2]; ?>), <?php echo $vote_results['risk_list'][3]; ?>), <?php echo $vote_results['risk_list'][4]; ?>), <?php echo $vote_results['risk_list'][5]; ?>);
    var optionsVertical = {
	scaleOverride: true,
	scaleSteps: nSteps,
	scaleStepWidth: 1,
	scaleStartValue: 0,
	pointDot: false
    }
    var canvasVertical = new Chart(ctxVertical).Bar(dataVertical, optionsVertical);
    <?php endif; ?>
    
    var ctxHorizontal = $("#canvas-horizontal").get(0).getContext("2d");
    var dataHorizontal = {
	labels: ["autres", "prévisionnel financier", "structuration de l'équipe", "produit / service", "impact sociétal"],
	datasets: [{
	    fillColor: "#CCC",
	    strokeColor: "#CCC",
	    data: [<?php echo $vote_results['count_more_info_other'] .','. $vote_results['count_more_info_finance'] .','. $vote_results['count_more_info_team'] .','. $vote_results['count_more_info_service'] .','. $vote_results['count_more_info_impact']; ?>]
	}]
    };
    var nSteps = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $vote_results['count_more_info_impact']; ?>), <?php echo $vote_results['count_more_info_service']; ?>), <?php echo $vote_results['count_more_info_team']; ?>), <?php echo $vote_results['count_more_info_finance']; ?>), <?php echo $vote_results['count_more_info_other']; ?>);
    var optionsHorizontal = {
	scaleOverride: true,
	scaleSteps: nSteps,
	scaleStepWidth: 1,
	scaleStartValue: 0
    }
    var canvasHorizontal = new Chart(ctxHorizontal).HorizontalBar(dataHorizontal, optionsHorizontal);
});
</script>

<?php } ?>