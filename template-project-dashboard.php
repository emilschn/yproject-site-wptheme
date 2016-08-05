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


    $post_campaign = get_post($campaign_id);
    $campaign = atcf_get_campaign($post_campaign);
    $status = $campaign->campaign_status();

    $WDGAuthor = new WDGUser(get_userdata($post_campaign->post_author));
    $WDGUser_current = WDGUser::current();
    $is_admin = (current_user_can('manage_options'));
    $is_author = $WDGAuthor->wp_user->ID == $WDGUser_current->wp_user->ID;

    //Stats vues
    global $stats_views, $stats_views_today;
    $stats_views = 0;
    $stats_views_today = 0;
    if (function_exists('stats_get_csv')) {
        global $wpdb, $stats_views, $stats_views_today;
        $stats_views = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 365 ) );
        $stats_views_today = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 1 ) );
    }

    //Donnees de votes
    global $vote_results, $nb_jcrois, $nb_votes, $nb_invests;
    $vote_results = WDGCampaignVotes::get_results($campaign_id);

    //Recuperation du nombre de j'y crois
    $nb_jcrois = $campaign->get_jycrois_nb();
    //Recuperation du nombre de votants
    $nb_votes = $campaign->nb_voters();
    //Recuperation du nombre d'investisseurs
    $nb_invests = $campaign->backers_count();

    locate_template( array("projects/dashboard/dashboardutility.php"), true );
    locate_template( array("projects/dashboard/resume.php"), true );
    locate_template( array("projects/dashboard/informations.php"), true );
    locate_template( array("projects/dashboard/campaign-tab.php"), true );
    locate_template( array("projects/dashboard/wallet.php"), true );
    locate_template( array("projects/dashboard/news.php"), true );

    check_change_status();
    page_resume_lightboxes();

    function is_preparing($status){
        return $status==ATCF_Campaign::$campaign_status_preparing;
    }
    function check_enabled_tab($status){
        if(is_preparing($status)) echo 'class="disabled"';
    }?>

        <div id="ndashboard"
        data-campaign-id="<?php echo $campaign_id?>">
            <nav id="ndashboard-navbar">
                <div class="nav-padding">
                    <div class="title"><?php echo $post_campaign->post_title; ?></div>
                    <div class="authorization">
                        <i class="fa fa-user" aria-hidden="true"></i><span>&nbsp;&nbsp;
                        <?php
                            if ($is_admin){
                                echo 'Mode Administrateur';
                            } else if ($is_author) {
                                echo 'Porteur du projet';
                            } else {
                                echo 'Membre du projet';
                            }
                        ?>
                    </span></div>
                    <ul>
                        <li>
                            <a href="#page-resume">
                                <?php _e("R&eacute;sum&eacute;", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a <?php if (!is_preparing($status)) {print('href="'.get_permalink($campaign_id).'" ');}
                                check_enabled_tab($status) ?>>
                                <?php _e("Pr&eacute;sentation", 'yproject');?>&nbsp;&nbsp;
                                <i class="fa fa-external-link" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#page-informations">Informations<div class="badge-notif">0</div></a>
                        </li>
                        <li>
                            <a href="#page-wallet" <?php check_enabled_tab($status) ?>>
                                <?php _e("Gestion financi&egrave;re", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#page-campaign" <?php check_enabled_tab($status) ?>>
                                <?php _e("Campagne", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#page-contacts" <?php check_enabled_tab($status) ?>>
                                <?php _e("Contacts", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#page-news" <?php check_enabled_tab($status) ?>>
                                <?php _e("Actualit&eacute;s", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <!--li>
                            <a href="#page-support">Accompagnement</a>
                        </li-->
                    </ul>
                </div>
            </nav>

            <div id="ndashboard-content">
                <div class="content-padding">
                    <div class="page-dashboard" id="page-resume"><?php print_resume_page(); ?></div>
                    <div class="page-dashboard" id="page-presentation"></div>
                    <div class="page-dashboard" id="page-informations"><?php print_informations_page(); ?></div>
                    <div class="page-dashboard" id="page-wallet"><?php print_wallet_page(); ?></div>
                    <div class="page-dashboard" id="page-campaign"><?php print_campaign_page(); ?></div>
                    <div class="page-dashboard" id="page-contacts">6</div>
                    <div class="page-dashboard" id="page-news"><?php print_news_page(); ?></div>
                    <!--div class="page-dashboard" id="page-support">8</div-->
                    <div class="page-dashboard" id="page-loading">
                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>