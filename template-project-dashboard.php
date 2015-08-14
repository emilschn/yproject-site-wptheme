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

            <?php if (have_posts()) : while (have_posts()) : the_post();

                if ($can_modify){
                    require_once('projects/single-admin-bar.php'); ?>

                    <div id="dashboard" class="center margin-height">
                        <?php
                        global $can_modify, $campaign_id;
                        $post_campaign = get_post($campaign_id);
                        $author_data = get_userdata($post_campaign->post_author);
                        $campaign = atcf_get_campaign($post_campaign);
                        $status = $campaign->campaign_status();
                        
                        $page_guide = get_page_by_path('guide');
                        $page_particular_terms = get_page_by_path('conditions-particulieres');

                        $page_parameters = get_page_by_path('parametres-projet');       // Paramètres
                        $page_add_news = get_page_by_path('ajouter-une-actu');          // Ajouter une actualité
                        $page_manage_team = get_page_by_path('projet-gerer-equipe');    // Editer l'équipe
                        $pages_stats_investments = get_page_by_path('statistiques-avancees-investissements');
                        $pages_stats_votes = get_page_by_path('statistiques-avancees-votes');
                        $pages_list_invest = get_page_by_path('liste-investisseurs');

                        $category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_name;
                        $category_obj = get_category_by_slug($category_slug);
                        $category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
                        $news_link = esc_url($category_link);

                        // Page statistiques avancees
                        if (strtotime($post_campaign->post_date) < strtotime('2014-02')) {
                            $pages_stats = get_page_by_path('vote');
                        } else {
                            $pages_stats = get_page_by_path('statistiques-avancees');
                        }
                        
                        /*Import fonctions PHP des blocs*/
                        locate_template( array("projects/dashboard-blocks/summary.php"), true );
                        locate_template( array("projects/dashboard-blocks/stats.php"), true );
                        locate_template( array("projects/dashboard-blocks/community.php"), true );
                        locate_template( array("projects/dashboard-blocks/news.php"), true );
                        locate_template( array("projects/dashboard-blocks/info.php"), true );
                        locate_template( array("projects/dashboard-blocks/team.php"), true );
                        
                        /*Données de statistiques */
                        block_stats_data();
                        /*Données de communauté*/
                        block_community_data();
                        
                        /*Vérifie si l'utilisateur essaie de passer à l'étape suivante **/
                        check_next_step();
                        /*Affiche s'il le faut la LB de bienvenue*/
                        print_welcome_lightbox(); ?>

                        <div class="part-title-separator">
                            <span class="part-title"><?php echo $post_campaign->post_title; ?></span>
                        </div>
                        
                        <div class="blocks-list">
                            <?php 
                                print_block_summary();
                            ?>
                            <br/>

                            <?php
                                print_block_stats();
                            ?>

                            <div id="col-left">
                            <?php
                                print_block_community();
                            ?>

                            <?php
                                print_block_news();
                            ?>

                            <?php
                                print_block_info();
                            ?>

                            </div>

                            <div id="col-right">
                            <?php
                                print_block_team();
                            ?>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <?php if ($campaign->google_doc() != ''){ ?>
                            <div class="google-doc">
                                <?php if (strpos('spreadsheet', $campaign->google_doc()) !== FALSE) : ?>
                                    <iframe src="<?php echo $campaign->google_doc(); ?>/edit?usp=sharing&embed=true" width="100%" height="800"></iframe>
                                <?php else : ?>
                                    <iframe src="<?php echo $campaign->google_doc(); ?>/pub?embedded=true"></iframe>
                                <?php endif; ?>
                            </div>
                        <?php } ?>

                        <div class="clear"></div>
                        
                <?php } else { ?>
                        <?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

                <?php } ?>

                    </div>

                <?php endwhile;
            endif; ?>

        </div>
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>