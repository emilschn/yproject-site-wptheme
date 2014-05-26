<?php
global $wpdb, $campaign, $post;
$table_name = $wpdb->prefix . "ypcf_project_votes";
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
$save_post = $post;
if (isset($_GET['campaign_id'])) $post = get_post($_GET['campaign_id']);
$author_id = $post->post_author;
if (($current_user_id == $author_id || current_user_can('manage_options')) && isset($_GET['campaign_id'])) {
	$campaign = atcf_get_campaign( $post );
	$campaign_id =  $campaign->ID;
	
	$count_voters = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id );
	$average_impact_economy = 0;
	$average_impact_environment = 0;
	$average_impact_social = 0;
	$list_impact_others_string = '';
	$count_project_validated = 0;
	$percent_project_validated = 0;
	$percent_project_not_validated = 0;
	$count_invest_ready = 0;
	$sum_invest_ready = 0;
	$average_invest_ready = 0;
	$median_invest_ready = 0;
	$average_risk = 0;
	$risk_list = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
	$count_more_info_impact = 0;
	$count_more_info_service = 0;
	$count_more_info_team = 0;
	$count_more_info_finance = 0;
	$count_more_info_other = 0;
	$string_more_info_other = '';
	$list_advice = array();
	if ($count_voters > 0) {
		$total_impact_economy = $wpdb->get_var( "SELECT sum(impact_economy) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$average_impact_economy = $total_impact_economy / $count_voters;
		$total_impact_environment = $wpdb->get_var( "SELECT sum(impact_environment) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$average_impact_environment = $total_impact_environment / $count_voters;
		$total_impact_social = $wpdb->get_var( "SELECT sum(impact_social) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$average_impact_social = $total_impact_social / $count_voters;
		$list_impact_others = $wpdb->get_results( "SELECT impact_other FROM ".$table_name." WHERE post_id = ".$campaign_id." AND impact_other <> ''" );
		foreach ($list_impact_others as $impact_others) { 
			if ($list_impact_others_string != '') $list_impact_others_string .= ', ';
			$list_impact_others_string .= html_entity_decode($impact_others->impact_other, ENT_QUOTES | ENT_HTML401);
		}
		
		
		$count_project_validated = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
		$percent_project_validated = round($count_project_validated / $count_voters, 2) * 100;
		$percent_project_not_validated = round(($count_voters - $count_project_validated) / $count_voters, 2) * 100;
		
		$count_invest_ready = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0" );
		if ($count_invest_ready > 0) {
		    $sum_invest_ready = $wpdb->get_var( "SELECT sum(invest_sum) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0" );
		    $average_invest_ready = $sum_invest_ready / $count_invest_ready;
		    if ($count_invest_ready == 1) {
			$median_invest_ready = $average_invest_ready;
		    } else {
			$median = 0;
			if ($count_invest_ready > 2) $median = round(($count_invest_ready + 1) / 2);
			$median_invest_ready = $wpdb->get_var( "SELECT invest_sum FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0 ORDER BY `invest_sum` LIMIT ".$median.", 1" );
		    }
		}
		
		$count_risk = $wpdb->get_var( "SELECT sum(invest_risk) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
		$average_risk = $count_risk / $count_project_validated;
		
		$risk_list = array(
		    1 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 1" ),
		    2 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 2" ),
		    3 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 3" ),
		    4 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 4" ),
		    5 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 5" ),
		);
		
		$count_more_info_impact = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_impact = 1" );
		$count_more_info_service = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_service = 1" );
		$count_more_info_team = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_team = 1" );
		$count_more_info_finance = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_finance = 1" );
		$count_more_info_other = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_other <> ''" );
		$more_info_other = $wpdb->get_results( "SELECT more_info_other FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_other <> ''" );
		foreach ($more_info_other as $more_info_other_item) { 
			if ($string_more_info_other != '') $string_more_info_other .= ', ';
			$string_more_info_other .= html_entity_decode($more_info_other_item->more_info_other, ENT_QUOTES | ENT_HTML401);
		}
		
		$list_advice = $wpdb->get_results( "SELECT user_id, advice FROM ".$table_name." WHERE post_id = ".$campaign_id." AND advice <> ''" );
	}
	
?>

<strong><?php echo $count_voters; ?></strong> personnes ont vot&eacute; sur votre projet.<br />

<h2>Impact et cohérence du projet</h2>
<?php //TODO : grenades ?>
<ul class="vote-results-impacts">
	<li><span>Economie :</span> <?php echo round($average_impact_economy, 1); ?></li>
	<li><span>Environnement :</span> <?php echo round($average_impact_environment, 1); ?></li>
	<li><span>Social :</span> <?php echo round($average_impact_social, 1); ?></li>
	<li><span>Autres :</span> <?php echo $list_impact_others_string; ?>
</ul>

<em>Vos impacts sont-ils suffisants pour que votre projet soit en financement sur WEDOGOOD.co ?</em><br />
<center><canvas id="canvas-pie" width="400" height="200"></canvas></center>

Les <strong><?php echo $count_project_validated; ?></strong> personnes qui ont vot&eacute; oui...<br />
<ul>
	<li>
	    investiraient en moyenne <strong><?php echo $average_invest_ready; ?> &euro;</strong>
	    (<strong><?php echo $count_invest_ready; ?></strong> personnes).
	    La moiti&eacute; d&apos;entre elles investiraient plus de <strong><?php echo $median_invest_ready; ?> &euro;</strong>.
	</li>
	<li>
	    ont &eacute;valu&eacute; le risque, en moyenne, &agrave; : <strong><?php echo round($average_risk, 2); ?></strong> / 5<br />
	    <center><canvas id="canvas-vertical" width="400" height="250"></canvas></center>
	</li>
</ul>

<h2>Remarques</h2>
Les internautes aimeraient avoir plus d’informations sur :<br />
<center><canvas id="canvas-horizontal" width="590" height="400"></canvas></center><br />
Autres informations : <strong><?php echo $string_more_info_other; ?></strong>

<h2>Conseils</h2>
<?php if (!empty($list_advice)) { ?>
<ul class="com-activity-list">
	<?php foreach ( $list_advice as $advice ) { 
		$user_obj = get_user_by('id', $advice->user_id);
	?>
		<li>
		    <a href="<?php echo bp_core_get_userlink($advice->user_id, false, true); ?>"><?php echo $user_obj->display_name; ?></a> : <?php echo html_entity_decode($advice->advice, ENT_QUOTES | ENT_HTML401); ?>
		</li>
	<?php } ?>
</ul>
<?php } ?>


<script type="text/javascript">
jQuery(document).ready( function($) {
    var ctxPie = $("#canvas-pie").get(0).getContext("2d");
    var dataPie = [
	{value: <?php echo $count_project_validated; ?>, color: "#FE494C", title: "Oui"}, 
	{value: <?php echo ($count_voters - $count_project_validated); ?>, color: "#333333", title: "Non"}
    ];
    var optionsPie = {
	legend: true,
	legendBorders: false,
	inGraphDataShow : true
    };
    var canvasPie = new Chart(ctxPie).Pie(dataPie, optionsPie);
    
    
    var ctxVertical = $("#canvas-vertical").get(0).getContext("2d");
    var dataVertical = {
	labels: ["1", "2", "3", "4", "5"],
	datasets: [{
	    fillColor: "#F2F2F2",
	    strokeColor: "#F2F2F2",
	    data: [<?php echo $risk_list[1] . ',' . $risk_list[2] . ',' . $risk_list[3] . ',' . $risk_list[4] . ',' . $risk_list[5]; ?>]
	}]
    };
    var nSteps = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $risk_list[1]; ?>), <?php echo $risk_list[2]; ?>), <?php echo $risk_list[3]; ?>), <?php echo $risk_list[4]; ?>), <?php echo $risk_list[5]; ?>);
    var optionsVertical = {
	scaleOverride: true,
	scaleSteps: nSteps,
	scaleStepWidth: 1,
	scaleStartValue: 0,
	pointDot: false
    }
    var canvasVertical = new Chart(ctxVertical).Bar(dataVertical, optionsVertical);
    
    
    var ctxHorizontal = $("#canvas-horizontal").get(0).getContext("2d");
    var dataHorizontal = {
	labels: ["autres", "prévisionnel financier", "structuration de l'équipe", "produit / service", "impact sociétal"],
	datasets: [{
	    fillColor: "#F2F2F2",
	    strokeColor: "#F2F2F2",
	    data: [<?php echo $count_more_info_other .','. $count_more_info_finance .','. $count_more_info_team .','. $count_more_info_service .','. $count_more_info_impact; ?>]
	}]
    };
    var nSteps = Math.max(Math.max(Math.max(Math.max(Math.max(0, <?php echo $count_more_info_impact; ?>), <?php echo $count_more_info_service; ?>), <?php echo $count_more_info_team; ?>), <?php echo $count_more_info_finance; ?>), <?php echo $count_more_info_other; ?>);
    var optionsHorizontal = {
	scaleOverride: true,
	scaleSteps: nSteps,
	scaleStepWidth: 1,
	scaleStartValue: 0
    }
    var canvasHorizontal = new Chart(ctxHorizontal).HorizontalBar(dataHorizontal, optionsHorizontal);
});
</script>

<?php
}
$post = $save_post;
?>