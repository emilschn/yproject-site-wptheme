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

                        $category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_name;
                        $category_obj = get_category_by_slug($category_slug);
                        $category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
                        $news_link = esc_url($category_link);

                        // Statistiques avancées
                        if (strtotime($post_campaign->post_date) < strtotime('2014-02')) {
                            $pages_stats = get_page_by_path('vote');
                        } else {
                            $pages_stats = get_page_by_path('statistiques-avancees');
                        }

                        $pages_stats_investments = get_page_by_path('statistiques-avancees-investissements');
                        $pages_stats_votes = get_page_by_path('statistiques-avancees-votes');
                        $pages_list_invest = get_page_by_path('liste-investisseurs');
                        
                        /**************Données communauté**************/
                        //Récupération du nombre de j'y crois
                            $nb_jcrois = $campaign->get_jycrois_nb();
                        //Récupération du nombre de votants
                            $nb_votes = $campaign->nb_voters();
                        //Récupération du nombre d'investisseurs
                            $nb_invests = $campaign->backers_count();
                        ?>

                        <?php if ($can_modify): ?>
                            <div class="part-title-separator">
                                <span class="part-title"><?php echo $post_campaign->post_title; ?></span>
                            </div>
                            
                            <div class="blocks-list">
                                
                                <div id="block-investors" class="block">
                                    <div class="head">Investisseurs</div>
                                    <div class="body">
                                    <p>
                                        <img src="<?php echo $stylesheet_directory_uri; ?>/images/personnes.png" alt="logo personnes" />
                                        <?php echo $nb_invests?> investissement<?php if($nb_invests>1){echo 's';}?></p>
                                    <p><?php echo $campaign->current_amount() . ' financés sur ' . $campaign->minimum_goal(true) ; ?></p>
                                    </div>
                                    <div class="foot">
                                        <a href="<?php echo get_permalink($pages_list_invest->ID) . $campaign_id_param . $params_partial; ?>">&#x1f50e; Liste des investisseurs</a>
                                    </div>
                                </div>
                                
                                <div id ="block-community" class="block">
                                    <div class="head">Communauté</div>
                                    <div class="body">
                                    <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/>
                                        <strong><?php echo $nb_jcrois?></strong> y croi<?php if($nb_jcrois>1){echo 'en';}?>t</p>
                                    <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/>
                                        <strong><?php echo $nb_votes?></strong> <?php if($nb_votes>1){echo 'ont';} else {echo 'a';}?> voté</p>
                                    <p><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/>
                                        <strong><?php echo $nb_invests?></strong> <?php if($nb_invests>1){echo 'ont';} else {echo 'a';}?> investi</p>
                                    </div>
                                    <div class="foot">&#9993 Envoyer un message</div>
                                </div>
                                
                                <div class="clear"></div>
                            </div>

                            <div class="currentstep">
                                <span><span><?php _e('Etape en cours :', 'yproject'); ?></span> <?php _e(ATCF_Campaign::$status_list[$campaign->campaign_status()], 'yproject'); ?></span>
                            </div>

                            <div class="button-help">
                                <a href="<?php echo get_permalink($page_particular_terms->ID); ?>" target="_blank"><?php _e('Conditions particuli&egrave;res', 'yproject'); ?></a>

                                <a href="<?php echo get_permalink($page_guide->ID); ?>" target="_blank"><?php _e('Guide de campagne', 'yproject'); ?></a>

                                <?php if ($campaign->google_doc() != ''): ?>
                                    <a href="<?php echo $campaign->google_doc(); ?>/edit" target="_blank" class="button"><?php _e('Ouvrir le document de gestion de campagne', 'yproject'); ?></a>
                                <?php endif; ?>

                                <a href="<?php echo $news_link; ?>" class="button"><?php _e('Publier une actualit&eacute;', 'yproject'); ?></a>
                                <div class="clear"></div>
                            </div>
                        
                            <div class="part-title-separator">
                                <span class="part-title"><?php _e('Statistiques','yproject'); ?></span>
                            </div>
                            
                            <div class="button-help">
                                <a href="<?php echo get_permalink($pages_stats->ID) . $campaign_id_param . $params_partial; ?>"><?php _e('Statistiques générales', 'yproject'); ?></a>

                                <a href="<?php echo get_permalink($pages_stats_votes->ID) . $campaign_id_param . $params_partial; ?>"><?php _e('Votes', 'yproject'); ?></a>

                                <a href="<?php echo get_permalink($pages_stats_investments->ID) . $campaign_id_param . $params_partial; ?>"><?php _e('Investissements', 'yproject'); ?></a>
                                
                                <a href="<?php echo get_permalink($pages_list_invest->ID) . $campaign_id_param . $params_partial; ?>"><?php _e('Liste des investisseurs', 'yproject'); ?></a>

                                <div class="clear"></div>
                            </div>

                            <?php if ($campaign->google_doc() != ''): ?>
                                <div class="google-doc">
                                    <?php if (strpos('spreadsheet', $campaign->google_doc()) !== FALSE) : ?>
                                        <iframe src="<?php echo $campaign->google_doc(); ?>/edit?usp=sharing&embed=true" width="100%" height="800"></iframe>
                                    <?php else : ?>
                                        <iframe src="<?php echo $campaign->google_doc(); ?>/pub?embedded=true"></iframe>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

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