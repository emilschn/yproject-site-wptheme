<?php 
/**
 * Charge des données : vues et résultats de vote
 * @global int $stats_views
 * @global int $stats_views_today
 * @global type $vote_results
 */
function block_stats_data(){
    global $campaign_id;
    
    /**************Stats Vues *********************/
    $stats_views = 0;
    $stats_views_today = 0;
    if (function_exists('stats_get_csv')) {
            global $wpdb, $stats_views, $stats_views_today;
            $stats_views = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 365 ) );
            $stats_views_today = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 1 ) );
    }
    
    //Donnees de votes
    locate_template( array("requests/votes.php"), true );
    global $vote_results;
    $vote_results = wdg_get_project_vote_results($campaign_id);
}

function block_stats_lightbox(){
    echo do_shortcode('[yproject_statsadvanced_lightbox]');
}

function print_block_stats() { 
    global $campaign,
            $status,
            $stats_views,
            $stats_views_today,
            $nb_votes,
            $nb_invests,
            $vote_results; ?>
<div id="block-stats" class="large-block">
    <div class="head">Statistiques</div>

    <div class="body">

        <?php if($status=='preview'){ ?>
            <div id="stats-prepare">
                <div class="half-card">
                    <div class="stat-little-number-top">Votre projet a &eacute;t&eacute; vu</div>
                    <div class="stat-big-number"><?php echo $stats_views[0]['views']; 
                        if ($stats_views[0]['views']==null){echo "-";}?></div>
                    <div class="stat-little-number">fois au total</div>
                </div>
                <div class="half-card">
                    <div class="stat-little-number-top">Dont</div>
                    <div class="stat-big-number"><?php echo $stats_views_today[0]['views'];
                        if ($stats_views_today[0]['views']==null){echo "-";}?></div>
                    <div class="stat-little-number">Vues aujourd'hui</div>
                </div>
            </div>

        <?php }
        else if($status=='vote'){ ?>
            <div id="stats-vote">
                <div class="quart-card">
                    <div class="stat-big-number"><?php echo $nb_votes?></div>
                    <div class="stat-little-number">sur <?php echo ATCF_Campaign::$voters_min_required?> requis</div>
                    <div class="details-card">
                    <strong><?php echo $nb_votes?></strong> personne<?php if($nb_votes>1){echo 's ont';}else{echo ' a';} echo ' voté';?>
                    </div>
                </div>
                <div class="quart-card">
                    <canvas id="canvas-pie-block" width="160" height="180"></canvas><br/>
                    <div class="details-card">
                        Valid&eacute; par <strong><?php echo $vote_results['percent_project_validated']?>&percnt;</strong> des votants
                    </div>
                </div>
                <div class="quart-card">
                    <div class="stat-big-number"><?php echo $vote_results['sum_invest_ready'].'&euro;'?></div>
                    <div class="stat-little-number">sur <?php echo $campaign->vote_invest_ready_min_required() ?> &euro; recommand&eacute;s</div>
                    <div class="details-card">
                        <strong><?php echo $vote_results['sum_invest_ready']?></strong>&euro; de promesses d'investissement
                    </div>
                </div>
                <div class="quart-card">
                    <div class="stat-big-number"><?php echo $campaign->time_remaining_str();?><br/></div>
                    <div class="stat-little-number">Avant la fin du vote</div>
                    <div class="details-card"><?php echo $campaign->time_remaining_fullstr()?></div>
                </div>
                <div class="clear"></div>
            </div>

        <?php } 
        else if($status=='collecte'){ ?>
            <div id="stats-invest">
                <div class="quart-card">
                    <div class="stat-big-number"><?php echo $campaign->current_amount()?></div>
                    <div class="stat-little-number">sur <?php echo $campaign->minimum_goal(false)/1 ?> &euro; requis</div>
                    <div class="details-card">
                        <strong><?php echo $campaign->current_amount()?></strong> investis par 
                        <strong><?php echo $nb_invests?></strong> personne<?php if($nb_invests>1){echo 's';}?></p>
                    </div>
                </div>
                <div class="half-card">
                    <div class="ajax-investments-load" id="ajax-invests-graph-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
                        <div id="ajax-graph-loader-img" >
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
                            <p style="font-style:italic">Chargement des donn&eacute;es d'investissement,<br/>cela peut prendre un peu de temps</p></div>
                    </div>
                    <canvas id="canvas-line-block" width="420" height="200" style="display:none"></canvas>
                </div>
                <div class="quart-card">
                    <div class="stat-big-number"><?php echo $campaign->time_remaining_str();?><br/></div>
                    <div class="stat-little-number">Avant la fin de collecte</div>
                    <div class="details-card"><?php echo $campaign->time_remaining_fullstr()?></div>
                </div>
            </div>

        <?php } 
        else if($status=='funded'){ ?>
            <div id="stats-funded">
                <div class="half-card">
                    <div class="stat-big-number"><?php echo $campaign->current_amount()?></div>
                    <div class="stat-little-number">récoltés sur <?php echo $campaign->minimum_goal(false)/1 ?> &euro;</div>
                    <div class="details-card">
                        <strong><?php echo $campaign->current_amount()?></strong> investis par 
                        <strong><?php echo $nb_invests?></strong> personne<?php if($nb_invests>1){echo 's';}?></p>
                    </div>
                </div>
                <div class="half-card">
                    <div class="ajax-investments-load" id="ajax-invests-graph-load" style="text-align: center;" data-value="<?php echo $campaign->ID?>">
                        <div id="ajax-graph-loader-img" >
                            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
                            <p style="font-style:italic">Chargement des donn&eacute;es d'investissement,<br/>cela peut prendre un peu de temps</p></div>
                    </div>
                    <canvas id="canvas-line-block" width="420" height="200" style="display:none"></canvas>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>

        <div class="list-button">
            <a href="#statsadvanced" class="wdg-button-lightbox-open button" data-lightbox="statsadvanced">&#x1f50e;  Statistiques d&eacute;taill&eacute;s</a>
        </div>
        <div class="clear"></div>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready( function($) {

        <?php if($status=='vote'){ ?>
            var ctxPie = $("#canvas-pie-block").get(0).getContext("2d");
            var dataPie = [
                {value: <?php echo $vote_results['count_project_validated']; ?>, color: "#FE494C", title: "Oui"}, 
                {value: <?php echo ($vote_results['count_voters'] - $vote_results['count_project_validated']); ?>, color: "#333333", title: "Non"}
            ];
            var optionsPie = {
                legend: true,
                legendBorders: false,
                inGraphDataShow : true,
                inGraphDataTmpl : "<%=v6%>%",
                inGraphDataFontFamily : "BebasNeue",
                inGraphDataFontSize : 25,
                inGraphDataFontColor : "#FFF",
                inGraphDataAnglePosition : 2,
                inGraphDataRadiusPosition : 2,
                inGraphDataMinimumAngle : 30,
                inGraphDataAlign : "center",
                inGraphDataVAlign : "middle"
            };
            var canvasPie = new Chart(ctxPie).Pie(dataPie, optionsPie);

        <?php } ?>
    });
    </script>
</div>


<?php }?>