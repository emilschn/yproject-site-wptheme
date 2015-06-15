<?php
global $post;
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;
$author_id = $post->post_author;
if (($current_user_id == $author_id || current_user_can('manage_options')) && isset($_GET['campaign_id'])) {
	locate_template( array("requests/votes.php"), true );?>
<h2>Statistiques des votes</h2>
<?php
	locate_template( array("projects/stats-votes-public.php"), true );
	$vote_results = wdg_get_project_vote_results($_GET['campaign_id']);
        $campaign = atcf_get_campaign($_GET['campaign_id']);
        ?>

        <canvas id="canvas-line-vote" width="420" height="200"></canvas><br/>
<?php
        
	print_vote_results($vote_results);
?>

<h3>Conseils</h3>
<?php if (!empty($vote_results['list_advice'])) { ?>
<ul class="com-activity-list">
	<?php foreach ( $vote_results['list_advice'] as $advice ) { 
		$user_obj = get_user_by('id', $advice->user_id);
	?>
		<li>
		    <a href="<?php echo bp_core_get_userlink($advice->user_id, false, true); ?>"><?php echo $user_obj->display_name; ?></a> : <?php echo html_entity_decode($advice->advice, ENT_QUOTES | ENT_HTML401); ?>
		</li>
	<?php } ?>
</ul>
<?php } 

    //Fonctions de formattage des dates pour JS
    function date_param($date) {
        return date_format(date_create($date),'"D M d Y H:i:s O"');
    }

    function date_abs($date) {
        return date_format(date_create($date),'"j/m/Y"');
    }
    $list_date = $vote_results['list_date'];
    $list_cumul_pos = $vote_results['list_cumul_pos'];
    $list_cumul_neg = $vote_results['list_cumul_neg'];
    $list_evo_pos = $vote_results['list_evo_pos'];
    $list_evo_neg = $vote_results['list_evo_neg'];
    $beginvotedate = date_sub(date_create($campaign->end_vote_date()),new DateInterval('P'.ATCF_Campaign::$vote_duration.'D'));
var_dump($beginvotedate);
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
                                fillColor : "rgba(55,55,55,0.5)",
                                strokeColor : "rgba(55,55,55,1)",
                                pointColor : "rgba(110,110,110,1)",
                                pointStrokeColor : "rgba(55,55,55,1)",
                                data : [<?php foreach ($list_cumul_neg as $cumul_pos){echo $cumul_pos.',';}?> ],
                                xPos : [<?php foreach ($list_date as $date){echo 'new Date('.date_param($date).'),';}?> ],
                                title : "Non"
                            },{
                                fillColor : "rgba(255,73,76,0.5)",
                                strokeColor : "rgba(255,73,76,1)",
                                pointColor : "rgba(255,73,76,1)",
                                pointStrokeColor : "rgba(199,46,49,1)",
                                data : [<?php foreach ($list_cumul_pos as $cumul_pos){echo $cumul_pos.',';}?> ],
                                xPos : [<?php foreach ($list_date as $date){echo 'new Date('.date_param($date).'),';}?> ],
                                title : "Oui"
                            }]
                    };

                    
                    displayAnnotVotes = function(cat, date, val){
                        return val+' '+cat+', le '+date.getDate()+'/'+(date.getMonth()+1)+'/'+(date.getFullYear());
                    };

                    var optionsLineVote = {
                        //annotateDisplay: true,
                        //annotateLabel: "<%=displayAnnotVotes(v1,v2,v3)%>",
                        //pointHitDetectionRadius: 7,
                        animation: true,
                        legend: true,
                        legendBorders : false
                    };
                    var canvasLineVote = new Chart(ctxLineVote).Line(dataLineVote, optionsLineVote);
            });
    </script>
    <?php
    
var_dump($list_date);
var_dump($list_cumul_pos);
var_dump($list_cumul_neg);
var_dump($list_evo_pos);
var_dump($list_evo_neg);

}
?>