<?php
/**
 * Template Name: Projet Tableau de bord V2
 *
 */
?>

<?php
global $return_roi_payment;
$return_roi_payment = WDGFormProjects::form_submit_roi_payment();
WDGFormProjects::form_approve_payment();
WDGFormProjects::form_cancel_payment();
?>

<?php get_header(); ?>

<?php
$campaign_id = filter_input(INPUT_GET, 'campaign_id');

//TODO: Unification des feedbacks
$success_msg = filter_input(INPUT_GET, 'success_msg');
if ( isset($success_msg) && !empty($success_msg) ): ?>
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
<?php endif;


if ($can_modify){
    //Données générales
    $post_campaign = get_post($campaign_id);
    $campaign = atcf_get_campaign($post_campaign);

    $WDGAuthor = new WDGUser(get_userdata($post_campaign->post_author));
    $WDGUser_current = WDGUser::current();
    $is_admin = $WDGUser_current->is_admin();
    $is_author = $WDGAuthor->wp_user->ID == $WDGUser_current->wp_user->ID;

	$campaign_organization = $campaign->get_organization();
	$organization_obj = new WDGOrganization( $campaign_organization->wpref, $campaign_organization );

    $status = $campaign->campaign_status();
    $collecte_or_after = $status==ATCF_Campaign::$campaign_status_collecte || $status==ATCF_Campaign::$campaign_status_funded || $status==ATCF_Campaign::$campaign_status_archive || $status==ATCF_Campaign::$campaign_status_closed;
    $vote_or_after = $collecte_or_after || $status==ATCF_Campaign::$campaign_status_vote;
    $preview_or_after = $vote_or_after || $status==ATCF_Campaign::$campaign_status_preview;
    $validated_or_after = true; //$preview_or_after || $status==ATCF_Campaign::$campaign_status_validated;

    //Stats vues
    $stats_views = 0;
    $stats_views_today = 0;
    if (function_exists('stats_get_csv')) {
        $stats_views = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 365 ) );
        $stats_views_today = stats_get_csv( 'postviews', array( 'post_id' => $campaign_id, 'days' => 1 ) );
    }

    //Donnees de votes
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
    locate_template( array("projects/dashboard/campaign-dbpage.php"), true );
    locate_template( array("projects/dashboard/contacts.php"), true );
    locate_template( array("projects/dashboard/news.php"), true );

    page_resume_lightboxes();

	$hidenewprojectlightbox = filter_input( INPUT_COOKIE, 'hidenewprojectlightbox' );
    if ( /*empty($hidenewprojectlightbox) &&*/ (filter_input(INPUT_GET, 'lightbox') == 'newproject') ) {
        ob_start();
        locate_template('projects/dashboard/dashboard-welcome-lightbox.php', true);
        $content = ob_get_contents();
        ob_end_clean();
        ?>
        <div id="lightbox-welcome" class="wdg-lightbox">
            <div class="wdg-lightbox-padder">
                <?php echo $content; ?>
            </div>
        </div>
		<script type="text/javascript">
			var date = new Date();
			var days = 100;
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
			document.cookie = "hidenewprojectlightbox=1"+expires+"; path=/";
		</script>
        <?php
    }
?>

<div id="content">
    <div class="">
        <div id="ndashboard" data-campaign-id="<?php echo $campaign_id?>">
            <nav id="ndashboard-navbar">
                <div class="nav-padding">
                    <div class="title"><?php echo $post_campaign->post_title; ?></div>
                    <div class="authorization">
                        <i class="fa fa-user" aria-hidden="true"></i>
						<span>&nbsp;&nbsp
                        <?php
                        if ($is_admin){
                                echo 'Mode Administrateur';
                            } else if ($is_author) {
                                echo 'Porteur du projet';
                            } else {
                                echo 'Membre du projet';
                            }
                        ?>
						</span>
					</div>
					
                    <ul>
                        <li>
                            <a href="#resume" data-target="page-resume">
                                <?php _e("Vue d'ensemble", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a <?php if ($validated_or_after || $is_admin) {echo ('href="'.get_permalink($campaign_id).'" ');} ?>
                                <?php DashboardUtility::check_enabled_page(); ?>>
                                <?php _e("Pr&eacute;sentation", 'yproject');?>&nbsp;&nbsp;
                                <i class="fa fa-external-link" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/guide'); ?>">
                                <?php _e("Guide", 'yproject');?>&nbsp;&nbsp;
                                <i class="fa fa-external-link" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#informations" data-target="page-informations">
                                <?php _e("Informations", 'yproject');?>
                                <div class="badge-notif"><?php
                                    if(filter_input(INPUT_GET,'lightbox')=='newproject'){echo '<i class="fa fa-exclamation" aria-hidden="true"></i>';}?></div>
                            </a>
                        </li>
                        <li>
                            <a href="#wallet" data-target="page-wallet"
                                <?php DashboardUtility::check_enabled_page(); ?>>
                                <?php _e("Gestion financi&egrave;re", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#campaign" data-target="page-campaign"
                                <?php DashboardUtility::check_enabled_page(); ?>>
                                <?php _e("Campagne", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#contacts" data-target="page-contacts"
                                <?php DashboardUtility::check_enabled_page(); ?>>
                                <?php _e("Contacts", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <a href="#news" data-target="page-news"
                                <?php DashboardUtility::check_enabled_page(); ?>>
                                <?php _e("Actualit&eacute;s", 'yproject');?>&nbsp;&nbsp;&nbsp;&nbsp;
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div id="ndashboard-content" class="db-form">
                <div class="content-padding">
                    <div class="page-dashboard" id="page-resume"><?php print_resume_page(); ?></div>
                    <div class="page-dashboard" id="page-presentation"></div>
                    <div class="page-dashboard" id="page-informations"><?php print_informations_page(); ?></div>
                    <?php if ($validated_or_after || $is_admin){?>
                    <div class="page-dashboard" id="page-wallet"  ><?php locate_template( array("projects/dashboard/wallet.php"), true ); ?></div>
                    <div class="page-dashboard" id="page-campaign"><?php print_campaign_page(); ?></div>
                    <div class="page-dashboard" id="page-contacts"><?php print_contacts_page()?></div>
                    <div class="page-dashboard" id="page-news"><?php print_news_page(); ?></div>
                    <!--div class="page-dashboard" id="page-support">8</div-->
                    <?php }?>
                    <div class="page-dashboard" id="page-loading" style="display:block">
                        <div class="tab-content">
                            <h2><i class="fa fa-spinner fa-spin fa-fw"></i>&nbsp;Chargement...</h2>
                        </div>
                    </div>
                    <div class="page-dashboard" id="page-redirect">
                        <div class="tab-content">
                            <h2><i class="fa fa-spinner fa-spin fa-fw"></i>&nbsp;Redirection vers la page...</h2>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php } else {
        echo '<div class="center margin-height">';
        _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject');
        echo '</div>';
    }?>
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );