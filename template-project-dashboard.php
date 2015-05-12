<?php
/**
 * Template Name: Projet Tableau de bord
 *
 */
$campaign_id = $_GET['campaign_id'];
?>

<?php get_header(); ?>
<div id="content">
    <div class="padder">
        <div class="page" id="blog-single" role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                    <?php require_once('projects/single-admin-bar.php'); ?>

                    <div id="dashboard" class="center margin-height">
                        <?php
                        global $can_modify, $campaign_id;
                        $post_campaign = get_post($campaign_id);
                        $campaign = atcf_get_campaign($post_campaign);
                        $page_guide = get_page_by_path('guide');
                        $page_particular_terms = get_page_by_path('conditions-particulieres');
                        $status = $campaign->campaign_status();

                        $category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_name;
                        $category_obj = get_category_by_slug($category_slug);
                        $category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
                        $news_link = esc_url($category_link);

                        // Statistiques avancees
                        if (strtotime($post_campaign->post_date) < strtotime('2014-02')) {
                            $pages_stats = get_page_by_path('vote');
                        } else {
                            $pages_stats = get_page_by_path('statistiques-avancees');
                        }
                        
                        $page_parameters = get_page_by_path('parametres-projet');
                        
                        /**************Donnees communaute**************/
                        //Recuperation du nombre de j'y crois
                            $nb_jcrois = $campaign->get_jycrois_nb();
                        //Recuperation du nombre de votants
                            $nb_votes = $campaign->nb_voters();
                        //Recuperation du nombre d'investisseurs
                            $nb_invests = $campaign->backers_count();
                        
                        //Recuperation donnees de votes
                        locate_template( array("requests/votes.php"), true );
                        $vote_results = wdg_get_project_vote_results($campaign_id);
                        
                        //Recuperation donnees d'investissement
                        locate_template( array("requests/investments.php"), true );
                        $investments_list = $campaign->payments_data();
                        
                        /****Liste des montants cumulés triés par leur date****/
                        $datesinvest = array();
                        $amountinvest = array();
                        
                        foreach ( $investments_list as $item ) {
                            $datesinvest[]=$item['date'];
                            $amountinvest[]=$item['amount'];
                        }
                        $cumulamount = array_combine($datesinvest, $amountinvest);
                        sort($datesinvest);
                        
                        for($i=1; $i<count($datesinvest); $i++){
                            $cumulamount[$datesinvest[$i]]=$cumulamount[$datesinvest[$i-1]]+$cumulamount[$datesinvest[$i]];
                        }
                        ksort($cumulamount);
                        /******************************************************/
                        
                        /*Vérifie si l'utilisateur essaie de passer à l'étape suivante **/
                        if ($can_modify){
                            if (isset($_POST['next_step'])&& $_POST['next_step']==1 && $campaign->can_go_next_step()){
                                if ($status=='preparing'){
                                    $campaign->set_status('preview');
                                    $campaign->set_validation_next_step(0);
                                } else if ($status=='preview') {
                                    if(ypcf_check_user_is_complete($campaign->post_author())){
                                        $campaign->set_status('vote');
                                        $campaign->set_validation_next_step(0);
                                    }
                                } else if ($status=='vote') {
                                    if($campaign->company_name()!=null&&$campaign->company_status()!=null
                                            &&$campaign->is_validated_by_vote() && $campaign->end_vote_remaining()<=0){
                                        $campaign->set_status('collecte');
                                        $campaign->set_validation_next_step(0);
                                    }
                                }
                                $status = $campaign->campaign_status();
                            }
                        }
                        
                        /******************************************************/
                        ?>

                        <?php if ($can_modify): ?>
                            <div class="part-title-separator">
                                <span class="part-title"><?php echo $post_campaign->post_title; ?></span>
                            </div>
                            <div class="blocks-list">
                                <div id="block-summary" >
                                    <div class="current-step">
                                        <span <?php if($status=='preparing'){echo 'id="current"';} ?>>Pr&eacute;paration </span>
                                        <span <?php if($status=='preview'){echo 'id="current"';} ?>>Avant-premi&egrave;re </span>
                                        <span <?php if($status=='vote'){echo 'id="current"';} ?>>Vote </span>
                                        <span <?php if($status=='collecte'){echo 'id="current"';} ?>>Collecte </span>
                                        <span <?php if($status=='funded'){echo 'id="current"';} ?>>R&eacute;alisation</span>
                                    </div>
                                    <?php if ($status=='preparing'||$status=='preview'||$status=='vote'){?>
                                        <div class="list-button">   
                                            <?php if (current_user_can('manage_options')) {
                                                //Visible uniquement par admins : autoriser le PP à passer à l'étape suivante
                                                if(isset($_GET['validate_next_step'])){
                                                    $campaign->set_validation_next_step($_GET['validate_next_step']);
                                                }
                                                if($campaign->can_go_next_step()){?>
                                                    <a href="?campaign_id=<?php echo $campaign_id?>&validate_next_step=0" class="button">&cross; Ne plus autoriser &agrave; passer &agrave; l'&eacute;tape suivante</a>
                                                <?php } else {?>
                                                    <a href="?campaign_id=<?php echo $campaign_id?>&validate_next_step=1" class="button">&check; Autoriser &agrave; passer &agrave; l'&eacute;tape suivante</a>
                                                <?php }
                                            }?>
                                            <a href="#gonextstep" class="wdg-button-lightbox-open button" data-lightbox="gonextstep">&check; Passer &agrave; l'&eacute;tape suivante</a>
                                            <?php //Lightbox passage à l'étape suivante
                                            echo do_shortcode('[yproject_gonextstep_lightbox]'); ?>
                                        </div>
                                    <?php }?>
                                </div>
                                <br/>
                                
                                <div id="block-stats" class="large-block">
                                    <div class="head">Statistiques</div>
                                    <div class="body">
                                        <div id="stats-prepare"
                                             <?php if($status!='vote'){echo 'hidden="hidden"';} ?>>
                                        </div>
                                        
                                        <div id="stats-vote" 
                                            <?php if($status!='vote'){echo 'hidden="hidden"';} ?>>
                                            <div class="quart-card">
                                                <div class="stat-big-number"><?php echo $nb_votes?></div>
                                                <div class="stat-little-number">sur 50 requis</div>
                                                <div class="details-card">
                                                <strong><?php echo $nb_votes?></strong> personne<?php if($nb_votes>1){echo 's ont';}else{echo ' a';} echo ' voté';?>
                                                </div>
                                            </div>
                                            <div class="quart-card">
                                                <canvas id="canvas-pie-block" width="180" height="200"></canvas><br/>
                                                <div class="details-card">
                                                    Valid&eacute; par <strong><?php echo $vote_results['percent_project_validated']?>&percnt;</strong> des votants
                                                </div>
                                            </div>
                                            <div class="quart-card">
                                                <div class="stat-big-number"><?php echo $vote_results['sum_invest_ready'].'&euro;'?></div>
                                                <div class="stat-little-number">sur <?php echo $campaign->goal(false)/2 ?> &euro; requis</div>
                                                <div class="details-card">
                                                    <strong><?php echo $vote_results['sum_invest_ready']?></strong>&euro; de promesses d'investissement
                                                </div>
                                            </div>
                                            <div class="quart-card">
                                                <div class="stat-big-number"><?php echo $campaign->end_vote_remaining();?><br/></div>
                                                <div class="stat-little-number">jour<?php if($campaign->end_vote_remaining()>1){echo 's';}?></div>
                                                <div class="details-card">
                                                    <strong><?php echo $campaign->end_vote_remaining(); ?></strong> jour<?php if($campaign->end_vote_remaining()>1){echo 's';}?> de vote restant<?php if($campaign->end_vote_remaining()>1){echo 's';}?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="stats-invest"
                                             <?php if($status!='collecte'){echo 'hidden="hidden"';} ?>>
                                            <div class="quart-card">
                                                <div class="stat-big-number"><?php echo $campaign->current_amount()?></div>
                                                <div class="stat-little-number">sur <?php echo $campaign->goal(false)/1 ?> &euro; requis</div>
                                                <div class="details-card">
                                                    <strong><?php echo $campaign->current_amount()?></strong> investis par 
                                                    <strong><?php echo $nb_invests?></strong> personne<?php if($nb_invests>1){echo 's';}?></p>
                                                </div>
                                            </div>
                                            <div class="half-card">
                                                <canvas id="canvas-line-block" width="420" height="200"></canvas>
                                            </div>
                                            <div class="quart-card">
                                                <div class="stat-big-number"><?php echo $campaign->days_remaining();?><br/></div>
                                                <div class="stat-little-number">jour<?php if($campaign->days_remaining()>1){echo 's';}?></div>
                                                <div class="details-card">
                                                    <strong><?php echo $campaign->days_remaining(); ?></strong> jour<?php if($campaign->days_remaining()>1){echo 's';}?> de collecte restant<?php if($campaign->days_remaining()>1){echo 's';}?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        
                                        <div class="list-button">
                                            <a href="#statsadvanced" class="wdg-button-lightbox-open button" data-lightbox="statsadvanced">&#x1f50e;  Statistiques d&eacute;taill&eacute;s</a>
                                            <?php echo do_shortcode('[yproject_statsadvanced_lightbox]'); ?>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                
                                <div id="block-investors" class="block">
                                    <div class="head">Investisseurs</div>
                                    <div class="body" style="text-align:center">
                                    <p>
                                        <img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
                                        <?php echo $nb_invests?> investissement<?php if($nb_invests>1){echo 's';}?></p>
                                    <p><?php echo $campaign->current_amount() . ' financ&eacute;s sur ' . $campaign->minimum_goal(true) ; ?></p>
                                    <div class="list-button">
                                        <a href="#listinvestors" class="wdg-button-lightbox-open button" data-lightbox="listinvestors">&#x1f50e; Liste des investisseurs</a>
		                    <?php echo do_shortcode('[yproject_listinvestors_lightbox]'); ?>
                                    </div>
                                    </div>
                                </div>
                                
                                <div id ="block-community" class="block">
                                    <div class="head">Communaut&eacute;</div>
                                    <div class="body" style="text-align:center">
                                        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/><br/>
                                            <strong><?php echo $nb_jcrois?></strong> y croi<?php if($nb_jcrois>1){echo 'en';}?>t</div>
                                        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/><br/>
                                            <strong><?php echo $nb_votes?></strong> <?php if($nb_votes>1){echo 'ont';} else {echo 'a';}?> vot&eacute;</div>
                                        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/><br/>
                                            <strong><?php echo $nb_invests?></strong> <?php if($nb_invests>1){echo 'ont';} else {echo 'a';}?> investi</div>
                                        <!--div class="list-button">
                                            <div class="button">&#9993 Envoyer un message</div>
                                        </div><div class="clear"></div-->
                                        <!--div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.jpg"/><br/>
                                            <strong><?php echo 'XXXX'?></strong> partage<?php if(2>1){echo 's';}?> Facebook</div>
                                        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.jpg"/><br/>
                                            <strong><?php echo 'XXXX'?></strong> partage<?php if(2>1){echo 's';}?> Twitter</div>
                                        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/google+.jpg"/><br/>
                                            <strong><?php echo 'XXXX'?></strong> partage<?php if(2>1){echo 's';}?> Google+</div-->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="clear"></div>
                           
                                <div class="block">
                                    <div class="head"><?php _e('Informations','yproject'); ?></div>
                                    <div class="body">
                                    <ul>
                                    <a href="<?php echo get_permalink($page_particular_terms->ID); ?>" target="_blank"><li><?php _e('Conditions particuli&egrave;res', 'yproject'); ?></li></a>

                                    <a href="<?php echo get_permalink($page_guide->ID); ?>" target="_blank"><li><?php _e('Guide de campagne', 'yproject'); ?></li></a>
                                    </ul>
                                    </div>
                                </div>
                        
                                <div class="button-help block">
                                    <br/><br/>
                                <?php if ($campaign->google_doc() != ''): ?>
                                    <a href="<?php echo $campaign->google_doc(); ?>/edit" target="_blank" class="button"><?php _e('Ouvrir le document de gestion de campagne', 'yproject'); ?></a>
                                    <?php endif; ?>
                                    <a href="<?php echo $news_link; ?>" class="button"><?php _e('Publier une actualit&eacute;', 'yproject'); ?></a>
                                <div class="clear"></div>
                                </div>
                                
                                <div class="clear"></div>

                            <?php if ($campaign->google_doc() != ''): ?>
                                <div class="google-doc">
                                    <?php if (strpos('spreadsheet', $campaign->google_doc()) !== FALSE) : ?>
                                        <iframe src="<?php echo $campaign->google_doc(); ?>/edit?usp=sharing&embed=true" width="100%" height="800"></iframe>
                                    <?php else : ?>
                                        <iframe src="<?php echo $campaign->google_doc(); ?>/pub?embedded=true"></iframe>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                                <script type="text/javascript">
                                jQuery(document).ready( function($) {
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

                                        <?php 
                                        function date_param($date) {
                                                return date_format(new DateTime($date),'Y,n,j,G,i,s').',0';
                                        }
                                        ?>

                                        var ctxLine = $("#canvas-line-block").get(0).getContext("2d");
                                        var dataLine = {
                                            labels : [new Date(<?php echo date_param($datesinvest[0]); ?>),new Date(<?php echo date_param($campaign->end_date()); ?>)],
                                            xBegin : new Date(<?php echo date_param($datesinvest[0]); ?>),
                                            xEnd : new Date(<?php echo date_param($campaign->end_date()); ?>),
                                            datasets : [
                                                {
                                                    fillColor : "rgba(255,73,76,0.5)",
                                                    strokeColor : "rgba(255,73,76,1)",
                                                    pointColor : "rgba(255,73,76,1)",
                                                    pointStrokeColor : "rgba(199,46,49,1)",
                                                    data : [<?php foreach ($cumulamount as $date => $amount){echo $amount.',';}?> ],
                                                    xPos : [<?php foreach ($cumulamount as $date => $amount){echo 'new Date('.date_param($date).'),';}?>],
                                                    title : "investissements"
                                                },{
                                                    fillColor : "rgba(204,204,204,0.25)",
                                                    strokeColor : "rgba(180,180,180,0.5)",
                                                    pointColor : "rgba(0,0,0,0)",
                                                    pointStrokeColor : "rgba(0,0,0,0)",
                                                    data : [0,<?php echo $campaign->minimum_goal(false);?>],
                                                    xPos : [new Date(<?php echo date_param($datesinvest[0]); ?>),new Date(<?php echo date_param($campaign->end_date()); ?>)],
                                                    title : "But"
                                                }
                                            ]
                                        };

                                        var optionsLine = {
                                            xAxisBottom : false,
                                            scaleOverride : true,
                                            scaleStartValue : 0,
                                            scaleSteps : 5,
                                            scaleStepWidth :  <?php echo $campaign->goal(false)/5; ?>,
                                        };
                                        var canvasLine = new Chart(ctxLine).Line(dataLine, optionsLine);
                                });
                                </script>
                        <?php else: ?>

                            <?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

                        <?php endif; ?>

                    </div>

                <?php endwhile;
            endif; ?>

        </div>
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>