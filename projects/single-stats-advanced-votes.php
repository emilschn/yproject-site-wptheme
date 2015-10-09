<?php
if (isset($_GET['campaign_id'])) {
	locate_template( array("requests/votes.php"), true );
        $campaign = atcf_get_campaign($_GET['campaign_id']); ?>

	<h2>Statistiques des votes</h2>
	<?php
            locate_template( array("projects/stats-votes-public.php"), true );
            $vote_results = WDGCampaignVotes::get_results($_GET['campaign_id']); 
            $status = $campaign->campaign_status();
            
            //N'affiche pas le graphe si les dates des votes n'ont pas été enregistrées ou le vote n'a pas été fait
            $displayGraph = ($status=='vote' || $status=='collecte' || $status=='funded')
                    && $vote_results['count_voters'] != 0
                    && $vote_results['list_date'][0] != 'NULL'
                    && $vote_results['list_date'][0] != null
                    && $vote_results['list_date'][0] != '0000-00-00';
            
            if ($displayGraph){ ?>
            <canvas id="canvas-line-vote" width="420" height="200"></canvas><br/>
            <?php }
            print_vote_results($vote_results);
	?>

	<h3>Conseils</h3>
	<?php if (!empty($vote_results['list_advice'])) { ?>
	<ul class="com-activity-list">
		<?php foreach ( $vote_results['list_advice'] as $advice ) { 
			$user_obj = get_user_by('id', $advice->user_id);
		?>
			<li>
			    <?php echo bp_core_get_userlink($advice->user_id); ?> : <?php echo html_entity_decode($advice->advice, ENT_QUOTES | ENT_HTML401); ?>
			</li>
		<?php } ?>
	</ul>
	<?php }
        
    //GRAPHE VOTE
    if($displayGraph){
        //Fonctions de formattage des dates pour JS
        function date_param($date) {
            return date_format(date_create($date),'"D M d Y H:i:s O"');
        }
        function date_abs($date) {
            return date_format(date_create($date),'"j/m/Y"');
        }

        $list_date = $vote_results['list_date'];
        $liste_cumul_pos = $vote_results['list_cumul_pos'];
        $liste_cumul_neg = $vote_results['list_cumul_neg'];

        //Choix la date de début du graphe
        $beginvotedate=date_create($list_date[0]);

        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                var ctxLineVote = $("#canvas-line-vote").get(0).getContext("2d");
                var dataLineVote = {
                    labels : [<?php echo date_format($beginvotedate,'"j/m/Y"');?>,
                    <?php echo date_format( new DateTime($campaign->end_vote_date()),'"j/m/Y"');?>],
                    xBegin : new Date(<?php echo date_format($beginvotedate,'"D M d Y H:i:s O"'); ?>),
                    xEnd : new Date(<?php echo date_format( new DateTime($campaign->end_vote_date()),'"D M d Y H:i:s O"'); ?>),
                    datasets : [
                        {
                            fillColor : "rgba(0,0,0,0)",
                            strokeColor : "rgba(0,0,0,0)",
                            pointColor : "rgba(0,0,0,0)",
                            pointStrokeColor : "rgba(0,0,0,0)",
                            data : [0,0],
                            xPos : [new Date(<?php echo date_format($beginvotedate,'"D M d Y H:i:s O"'); ?>),new Date(<?php echo date_format( new DateTime($campaign->end_vote_date()),'"D M d Y H:i:s O"'); ?>)],
                            title : "Axe"
                        },{
                            fillColor : "rgba(55,55,55,0.5)",
                            strokeColor : "rgba(55,55,55,1)",
                            pointColor : "rgba(110,110,110,1)",
                            pointStrokeColor : "rgba(55,55,55,1)",
                            data : [<?php foreach ($liste_cumul_neg as $cumul_pos){echo $cumul_pos.',';}?> ],
                            xPos : [<?php foreach ($list_date as $date){echo 'new Date('.date_param($date).'),';}?> ],
                            title : "Non"
                        },{
                            fillColor : "rgba(255,73,76,0.5)",
                            strokeColor : "rgba(255,73,76,1)",
                            pointColor : "rgba(255,73,76,1)",
                            pointStrokeColor : "rgba(199,46,49,1)",
                            data : [<?php foreach ($liste_cumul_pos as $cumul_pos){echo $cumul_pos.',';}?> ],
                            xPos : [<?php foreach ($list_date as $date){echo 'new Date('.date_param($date).'),';}?> ],
                            title : "Oui"
                        }<?php
                        if (new DateTime(null)< new DateTime($campaign->end_vote_date())){
                        ?>,{
                            fillColor : "rgba(0,0,0,0)",
                            strokeColor : "rgba(0,0,0,0)",
                            pointColor : "rgba(116,191,67,1)",
                            pointStrokeColor : "rgba(15,130,22,1)",
                            data : [<?php echo ($vote_results['count_voters']);?>],
                            xPos : [new Date(<?php echo date_param(null); ?>)],
                            title : "Aujourd'hui"
                        }<?php }
                    ?>]
                };

                //Fonction d'affichage 
                displayAnnotVotes = function(cat, date, val){
                    if(cat==="Oui"||cat==="Non"){
                        plur="";
                        if (val>1) { plur="s"; }
                        return val+' vote'+plur+' "'+cat+'" cumulé'+plur+' au total, le '+date.getDate()+'/'+(date.getMonth()+1)+'/'+(date.getFullYear());
                    } else if(cat==="Aujourd'hui") {
                        return 'Aujourd\'hui le '+date.getDate()+'/'+(date.getMonth()+1)+'/'+(date.getFullYear())+' il y a '+val+' votes au total.';
                    } else {
                        return "";
                    }
                };

                var optionsLineVote = {
                    annotateDisplay: true,
                    annotateLabel: "<%=displayAnnotVotes(v1,v2,v3)%>",
                    pointHitDetectionRadius: 7,
                    animation: true,

                    scaleOverride : true,
                    scaleStartValue : 0,
                    scaleSteps : 6,
                    scaleStepWidth : Math.max(<?php echo ATCF_Campaign::$voters_min_required/5 ?>,<?php echo round(($vote_results['count_project_validated']/5),0,PHP_ROUND_HALF_ODD) ?>)
                };
                var canvasLineVote = new Chart(ctxLineVote).Line(dataLineVote, optionsLineVote);
            });
        </script>
    <?php }
}?>