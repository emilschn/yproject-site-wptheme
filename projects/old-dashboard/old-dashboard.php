<div class="page" style="display:none" id="blog-single" role="main">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div id="dashboard" class="">
            <?php
            if ($can_modify){
                global $can_modify, $campaign_id;
                $post_campaign = get_post($campaign_id);
                $author_data = get_userdata($post_campaign->post_author);
                $campaign = atcf_get_campaign($post_campaign);
                $status = $campaign->campaign_status();

                /*Import fonctions PHP des blocs*/
                locate_template( array("projects/old-dashboard/summary.php"), true );
                locate_template( array("projects/old-dashboard/stats.php"), true );
                locate_template( array("projects/old-dashboard/community.php"), true );
                locate_template( array("projects/old-dashboard/news.php"), true );
                locate_template( array("projects/old-dashboard/info.php"), true );
                locate_template( array("projects/old-dashboard/team.php"), true );

                /*Données de statistiques */
                block_stats_data();
                /*Données de communauté*/
                block_community_data();

                /*Vérifie si l'utilisateur essaie de passer à l'étape suivante **/
                check_next_step();
                /*Vérifie si l'utilisateur essaie d'envoyer un mail **/
                $feedback_sendmail = WDGFormProjects::form_validate_send_mail();
                /*Affiche s'il le faut la LB de bienvenue*/
                print_welcome_lightbox();

                /*Charge les lightbox en début de page pour éviter les problèmes de CSS*/
                block_summary_lightbox();
                block_stats_lightbox();
                block_community_lightbox();
                ?>

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