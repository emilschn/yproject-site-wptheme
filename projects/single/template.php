<?php 
global $campaign, $can_modify;
$can_modify = $campaign->current_user_can_edit();
$client_context = $campaign->get_client_context();
$campaign_status = $campaign->campaign_status();

if ( is_user_logged_in() ) {
	$WDGUser_current = WDGUser::current();
}

// Pour gérer l'affichage de la présentation pour les non-investisseurs après la campagne,
// On peut voir si :
// - on peut éditer (PP / admin)
// - il reste du temps
// - l'option n'est pas activée
// - on est connecté et on a investi sur la campagne
$can_display_presentation = $can_modify
							|| $campaign->is_remaining_time()
							|| !$campaign->is_presentation_visible_only_to_investors()
							|| ( is_user_logged_in() && $WDGUser_current->has_invested_on_campaign( $campaign->ID ) );
?>

<?php if ( is_user_logged_in() && $campaign_status == ATCF_Campaign::$campaign_status_vote ): ?>
	<?php if ( !$WDGUser_current->has_voted_on_campaign( $campaign->ID ) ): ?>
		<?php locate_template( array("projects/single/voteform-lightbox.php"), true ); ?>
	<?php else: ?>
		<?php locate_template( array("projects/single/voteform-lightbox-share.php"), true ); ?>
		<?php locate_template( array("projects/single/preinvestment-warning-lightbox.php"), true ); ?>
	<?php endif; ?>
<?php endif; ?>

<?php echo do_shortcode('[wdg_project_warning_lightbox project_id="' .$campaign->ID. '"][/wdg_project_warning_lightbox]'); ?>

<?php if ($can_modify): ?>
<?php locate_template( array("projects/single/admin.php"), true ); ?>
<?php endif; ?>

<header>
    <div class="header-container">
	<?php locate_template( array("projects/single/banner.php"), true ); ?>
	
	<?php if (!empty($client_context)): ?>
	<?php locate_template( array("projects/" .$client_context. "/header.php"), true ); ?>
	<?php endif; ?>
    </div>
</header>

<?php if ( $can_display_presentation ): ?>
	<?php locate_template( array("projects/single/nav.php"), true ); ?>
<?php endif; ?>

<?php locate_template( array("projects/single/rewards.php"), true ); ?>

<?php if ( $can_display_presentation ): ?>
	<?php locate_template( array("projects/single/description.php"), true ); ?>

	<?php locate_template( array("projects/single/news.php"), true ); ?>

	<?php locate_template( array("projects/single/comments.php"), true ); ?>
	
<?php else: ?>
	<?php locate_template( array("projects/single/description-hidden.php"), true ); ?>

<?php endif; ?>

<?php 
$custom_footer_code = $campaign->custom_footer_code();
if ( !empty( $custom_footer_code ) ) {
	echo $custom_footer_code;
}
