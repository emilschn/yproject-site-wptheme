<?php function print_investments($id_campaign, $is_advanced = FALSE) { 
    $campaign = atcf_get_campaign($id_campaign);
    $voc = $campaign->funding_type_vocabulary();
    ?>

    <div class="ajax-investments-load" data-value="<?php echo $id_campaign?>">
    <h3>G&eacute;n&eacute;ral</h3>
    <div class="ajax-data-inv-loader-img"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>
    <p><strong class="data-inv-count_validate_investments">&hellip;</strong> <?php echo $voc['investor_action']?>s valid&eacute;<?php if ($voc['action_feminin']){echo 'e';}?>s par 
    <strong class="data-inv-count_validate_investors">&hellip;</strong> <?php echo $voc['investor_name']?>s distincts.<br />
    <?php if ($is_advanced) {?>
        <strong class="data-inv-count_not_validate_investments">&hellip;</strong> <?php echo $voc['investor_action']?>s non-validé<?php if ($voc['action_feminin']){echo 'e';}?>s<br/>
    <?php } ?>
    Les <?php echo $voc['investor_name']?>s ont <strong class="data-inv-average_age">&hellip;</strong> ans de moyenne.<br />
    Ce sont <strong class="data-inv-percent_female">&hellip;</strong>% de femmes et <strong class="data-inv-percent_male">&hellip;</strong>% d&apos;hommes.<br />
    <strong class="data-campaign_days_remaining"><?php echo $campaign->time_remaining_fullstr()?></strong><br />
    <?php echo ucfirst($voc['investor_action'])?> moyen<?php if ($voc['action_feminin']){echo 'ne';}?> par personne : <strong class="data-inv-average_invest">&hellip;</strong>&euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> minimal<?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-min_invest">&hellip;</strong>&euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> m&eacute;dian<?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-median_invest">&hellip;</strong>&euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> maximal<?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-max_invest">&hellip;</strong>&euro;<br />
    
    <?php if ($is_advanced === TRUE): ?>
    <br />
    Total des <?php echo $voc['investor_action']?>s par ch&egrave;que : <strong class="data-inv-amount_check">&hellip;</strong><br />
    <?php endif; ?>
    </p>
    <?php if(edd_has_variable_prices($id_campaign)): ?>
    <h3>Choix des contreparties</h3>
    <p><canvas id="canvas-horizontal-rewards" width="420" 
               height="<?php $rewards = atcf_get_rewards($id_campaign);
                    echo ((count($rewards->rewards_list)+1)*40)+70?>">
        </canvas></p>
    <?php endif; ?>

    <h3>Ils ont <?php echo $voc['investor_verb']?></h3>
    <p class="data-inv-investors_string">&hellip;</p>
    </div>
    
    <?php if(edd_has_variable_prices($id_campaign)): ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            var ctxRewardHorizontal = $("#canvas-horizontal-rewards").get(0).getContext("2d");
            var dataRewardHorizontal = {
                labels: ["Pas de\n contrepartie", <?php
                foreach ($rewards->rewards_list as $elem) {
                    //Ceci est une lutte contre le plugin pour forcer un retour à la ligne
                    $text = '"'.intval($elem['amount']).'€ : ';
                    $line_2="";
                    $line_1 = $elem['name'];
                    $line_1 = substr($line_1,0,16);
                    if($line_1 != $elem['name']){
                        $cut_pos = strrpos ($line_1, ' ');
                        $line_1 = substr($line_1, 0, $cut_pos).'\n';
                        $line_2 = substr($elem['name'], $cut_pos+1);
                        if ($line_2!= substr($line_2,0,$cut_pos+20)){
                            $cut_pos = strrpos (substr($line_2,0,$cut_pos+20), ' ');
                            $line_2 = substr($line_2,0,$cut_pos).'...';
                        }
                    }
                    $text .= $line_1.$line_2.'",';
                    echo $text;
                } ?>],
                datasets: [{
                    fillColor: "#ea4f51",
                    strokeColor: "rgba(0,0,0,0)",
                    data: [<?php echo '"'.$rewards->get_reward_number_purchased(-1).'",';
                            foreach ($rewards->rewards_list as $elem) {echo '"'.intval($elem['bought']).'",';} ?>],
                    title : "Achetés"
                },{
                    fillColor: "#CCC",
                    strokeColor: "rgba(0,0,0,0)",
                    data: ["0", <?php
                    foreach ($rewards->rewards_list as $elem) {
                        if ($rewards->is_limited_reward($elem['id'])){
                            echo '"'.((intval($elem['limit']))-intval($elem['bought'])).'",';
                        } else {
                            echo '"0",';
                        }
                    } ?>],
                    title : "Restants"
                }]
            };
            
            displayAnnotReward = function(cat, nom, val){
                if(cat==="Achetés"){
                    return ("Cette contrepartie a été choisie "+val+" fois.");
                } else if(cat ==="Restants") {
                    return ("Cette contrepartie est encore disponible "+val+" fois.");
                } else {
                    return "";
                }
            };
            
            var optionsRewardHorizontal = {
                annotateDisplay: true,
                annotateLabel: "<%=displayAnnotReward(v1,v2,v3)%>",
                inGraphDataShow : true,
                inGraphDataPaddingX : -2,
                inGraphDataFontSize : 18,
                inGraphDataFontFamily : "BebasNeue",
                inGraphDataFontColor : "#000",
                inGraphDataAlign : "right",
                scaleFontSize : 12,
                scaleFontFamily: "Arial",
                scaleFontStyle: "sans-serif",
                legend : true,
                legendBorders : false
            };
            var canvasRewardHorizontal = new Chart(ctxRewardHorizontal).HorizontalStackedBar(dataRewardHorizontal, optionsRewardHorizontal);
        });
    </script>
    <?php endif; ?>
    
<?php }?>
