<?php
/**
 * Template Name: Projet Tableau de bord
 *
 */
$campaign_id = filter_input(INPUT_GET, 'campaign_id');
$success_msg = filter_input(INPUT_GET, 'success_msg');
global $feedback_sendautomail;
$feedback_sendautomail = WDGFormProjects::form_validate_send_automail();
WDGFormProjects::form_approve_payment();
WDGFormProjects::form_cancel_payment();
?>

<?php get_header(); ?>

<?php if ( isset($success_msg) && !empty($success_msg) ): ?>
	<div id="lightbox-successmsg" class="wdg-lightbox">
		<div class="wdg-lightbox-click-catcher"></div>
		<div class="wdg-lightbox-padder">
		    <div class="wdg-lightbox-button-close">
			<a href="#" class="button">X</a>
		    </div>
			<?php
			switch ($success_msg) {
				case 'approvepayment':
					_e("Le paiement a &eacute;t&eacute; valid&eacute;.", 'yproject');
					break;
				case 'cancelpayment':
					_e("Le paiement a &eacute;t&eacute; annul&eacute;.", 'yproject');
					break;
			}
			?>
		</div>
	</div>
<?php endif; ?>

<div id="content">
    <div class="padder">
<?php
if ($can_modify){
    global $can_modify, $campaign_id;
    $post_campaign = get_post($campaign_id);
    $author_data = get_userdata($post_campaign->post_author);
    $campaign = atcf_get_campaign($post_campaign);
    $status = $campaign->campaign_status(); ?>

        <div id="ndashboard">
            <nav id="ndashboard-navbar">
                <div class="nav-padding">
                    <ul>
                        <li class="active">Résumé</li>
                        <li>Présentation</li>
                        <li>Informations</li>
                        <li>Gestion financière</li>
                        <li>Campagne</li>
                        <li>Contacts</li>
                        <li>Actualités</li>
                        <li>Accompagnement</li>
                    </ul>
                </div>
            </nav>

            <div id="ndashboard-content">
                <div class="content-padding">
                    <div class="part-title-separator">
                        <span class="part-title"><?php echo $post_campaign->post_title; ?></span>
                    </div>
                    <h3>Projet</h3>
                    <ul>
                        <li>aodnaozn</li>
                        <li>ozenfnezi</li>
                        <li>ncoxnoozen</li>
                    </ul>
                </div>
            </div>
        </div>
    <?php } ?>


        <div class="page" id="blog-single" role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div id="dashboard" class="margin-height">
                <?php
                if ($can_modify){
                        global $can_modify, $campaign_id;
                        $post_campaign = get_post($campaign_id);
                        $author_data = get_userdata($post_campaign->post_author);
                        $campaign = atcf_get_campaign($post_campaign);
                        $status = $campaign->campaign_status();

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
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>