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
    global $can_modify,
           $campaign_id, $campaign, $post_campaign,
           $WDGAuthor, $WDGUser_current;

    $post_campaign = get_post($campaign_id);
    $WDGAuthor = new WDGUser(get_userdata($post_campaign->post_author));
    $WDGUser_current = WDGUser::current();
    $campaign = atcf_get_campaign($post_campaign);
    $status = $campaign->campaign_status();

    locate_template( array("projects/dashboard-pages/informations.php"), true );

    function is_preparing($status){
        return $status==ATCF_Campaign::$campaign_status_preparing;
    }
    function check_enabled_tab($status){
        if(is_preparing($status)) echo 'class="disabled"';
    }?>

        <div id="ndashboard">
            <nav id="ndashboard-navbar">
                <div class="nav-padding">
                    <div  class="title"><?php echo $post_campaign->post_title; ?></div>
                    <ul>
                        <li>
                            <a href="#page-informations">Résumé</a>
                        </li>
                        <li>
                            <a <?php if (!is_preparing($status)) {print('href="'.get_permalink($campaign_id).'" ');}
                                check_enabled_tab($status) ?>>
                                Présentation</a>
                        </li>
                        <li>
                            <a href="#page-informations">Informations<div class="badge-notif">0</div></a>
                        </li>
                        <li>
                            <a href="#page-wallet" <?php check_enabled_tab($status) ?>>Gestion financière</a>
                        </li>
                        <li>
                            <a href="#page-campaign" <?php check_enabled_tab($status) ?>>Campagne</a>
                        </li>
                        <li>
                            <a href="#page-contacts" <?php check_enabled_tab($status) ?>>Contacts</a>
                        </li>
                        <li>
                            <a href="#page-news" <?php check_enabled_tab($status) ?>>Actualités</a>
                        </li>
                        <li>
                            <a href="#page-support">Accompagnement</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div id="ndashboard-content">
                <div class="content-padding">
                    <div class="page-dashboard" id="page-resume">1</div>
                    <div class="page-dashboard" id="page-presentation">2</div>
                    <div class="page-dashboard" id="page-informations"><?php print_informations_page() ?></div>
                    <div class="page-dashboard" id="page-wallet">4</div>
                    <div class="page-dashboard" id="page-campaign">5</div>
                    <div class="page-dashboard" id="page-contacts">6</div>
                    <div class="page-dashboard" id="page-news">7</div>
                    <div class="page-dashboard" id="page-support">8</div>

                </div>
            </div>
        </div>
    <?php } ?>
    </div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>