<?php function print_vote_results($vote_results) { ?>

<?php if ($vote_results['count_voters'] > 0): ?>
<strong><?php echo $vote_results['count_voters']; ?></strong> <?php _e('personnes ont vot&eacute; sur ce projet.', 'yproject'); ?><br />

<h3><?php _e('Impact et coh&eacute;rence du projet', 'yproject'); ?></h3>
<?php //TODO : grenades ?>
<ul class="vote-results-impacts">
	<li><span><?php _e('Economie :', 'yproject'); ?></span> <?php echo round($vote_results['average_impact_economy'], 1); ?></li>
	<li><span><?php _e('Environnement :', 'yproject'); ?></span> <?php echo round($vote_results['average_impact_environment'], 1); ?></li>
	<li><span><?php _e('Social :', 'yproject'); ?></span> <?php echo round($vote_results['average_impact_social'], 1); ?></li>
	<li><span><?php _e('Autres :', 'yproject'); ?></span> <?php echo $vote_results['list_impact_others_string']; ?>
</ul>

<em><?php _e('Notes attribu&eacute;es au projet', 'yproject'); ?></em><br />
<center><canvas id="canvas-vertical-bar" width="400" height="200"></canvas></center>

<strong><?php echo $vote_results['count_project_validated']; ?></strong> <?php _e('personnes ont donn&eacute; une note sup&eacute;rieure &agrave; 2', 'yproject'); ?><br />
<ul>
	<li>
			<?php
			$total = $vote_results['count_invest_ready'] * $vote_results['average_invest_ready'];
			$pourcentage = ($total*100)/$vote_results['objective'];
            ?>
      
            <?php _e('Sur ces', 'yproject'); ?> <strong><?php echo $vote_results['count_project_validated']; ?></strong> <?php _e('votants', 'yproject'); ?>, <strong><?php echo $vote_results['count_invest_ready']; ?></strong> <?php _e("personnes ont d&eacute;clar&eacute; qu'ils investiraient en moyenne", 'yproject'); ?> <strong><?php echo round($vote_results["average_invest_ready"],2); ?> &euro;</strong>. 
            <?php _e('Pour un total de', 'yproject'); ?> <strong><?php echo round($total ,2); ?></strong> <?php _e("euros d'intentions d'investissement, soit", 'yproject'); ?> <strong><?php echo round($pourcentage ,2).' %'; ?></strong> <?php _e("de l’objectif", 'yproject'); ?>.
        </li>
	<?php if ($vote_results['show_risk']): ?>
	<li>
	    <?php _e("ont &eacute;valu&eacute; le risque, en moyenne, &agrave; :", 'yproject'); ?> <strong><?php echo round($vote_results['average_risk'], 2); ?></strong> / 5<br />
	    <center><canvas id="canvas-vertical" width="400" height="200"></canvas></center>
	</li>
	<?php endif; ?>
</ul>

<h3><?php _e("Remarques", 'yproject'); ?></h3>
<?php _e("Les internautes aimeraient avoir plus d’informations sur :", 'yproject'); ?><br />
<center><canvas id="canvas-horizontal" width="600" height="300"></canvas></center><br />
<?php _e("Autres informations :", 'yproject'); ?> <br /><strong><?php echo $vote_results['string_more_info_other']; ?></strong>


<script type="text/javascript">
jQuery(document).ready( function($) {
    var ctxBar = $("#canvas-vertical-bar").get(0).getContext("2d");
		var nStepsBar = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $vote_results['rate_project_list'][1]; ?>), <?php echo $vote_results['rate_project_list'][2]; ?>), <?php echo $vote_results['rate_project_list'][3]; ?>), <?php echo $vote_results['rate_project_list'][4]; ?>), <?php echo $vote_results['rate_project_list'][5]; ?>);
		var barData = {
			labels: [ "1", "2", "3", "4", "5" ],
			datasets: [{
				fillColor: "#FE494C",
				strokeColor: "#FE494C",
				data: [
					<?php echo $vote_results[ 'rate_project_list' ][ '1' ]; ?>,
					<?php echo $vote_results[ 'rate_project_list' ][ '2' ]; ?>,
					<?php echo $vote_results[ 'rate_project_list' ][ '3' ]; ?>,
					<?php echo $vote_results[ 'rate_project_list' ][ '4' ]; ?>,
					<?php echo $vote_results[ 'rate_project_list' ][ '5' ]; ?>
				]
			}]
		};
		var barOptions = {
			scaleOverride: true,
			scaleSteps: nStepsBar,
			scaleStepWidth: 1,
			scaleStartValue: 0,
			pointDot: false
		};
		var canvasBar = new Chart( ctxBar ).Bar( barData, barOptions );
    
    <?php if ($vote_results['show_risk']): ?>
    var ctxVertical = $("#canvas-vertical").get(0).getContext("2d");
    var dataVertical = {
		labels: ["1", "2", "3", "4", "5"],
		datasets: [{
			fillColor: "#FE494C",
			strokeColor: "#FE494C",
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
    };
    var canvasVertical = new Chart(ctxVertical).Bar(dataVertical, optionsVertical);
    <?php endif; ?>
    
    var ctxHorizontal = $("#canvas-horizontal").get(0).getContext("2d");
    var dataHorizontal = {
	labels: ["<?php _e("autres", 'yproject'); ?>", "<?php _e("previsionnel financier", 'yproject'); ?>", "<?php _e("structuration de l'equipe", 'yproject'); ?>", "<?php _e("produit / service", 'yproject'); ?>", "<?php _e("impact societal", 'yproject'); ?>"],
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

<?php else: ?>
<?php _e("Il n'y a pas encore eu de vote sur ce projet.", 'yproject'); ?>
<?php endif; ?>
<?php } ?>