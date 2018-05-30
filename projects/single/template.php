<?php 
global $campaign, $can_modify;
$can_modify = $campaign->current_user_can_edit();
$client_context = $campaign->get_client_context();
$campaign_status = $campaign->campaign_status();
?>

<?php if ( is_user_logged_in() && $campaign_status == ATCF_Campaign::$campaign_status_vote ): ?>
	<?php $WDGUser_current = WDGUser::current(); ?>
	<?php if ( !$WDGUser_current->has_voted_on_campaign( $campaign->ID ) ): ?>
		<?php locate_template( array("projects/single/voteform-lightbox.php"), true ); ?>
	<?php else: ?>
		<?php locate_template( array("projects/single/voteform-lightbox-share.php"), true ); ?>
		<?php if ( !$WDGUser_current->has_invested_on_campaign( $campaign->ID ) ): ?>
			<?php locate_template( array("projects/single/preinvestment-warning-lightbox.php"), true ); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>

<?php $hideprojectwarning = filter_input( INPUT_COOKIE, 'hideprojectwarning' ); ?>
<?php if ( empty( $hideprojectwarning ) ): ?>
<?php if ( !is_user_logged_in() && ( $campaign_status == ATCF_Campaign::$campaign_status_preview || $campaign_status == ATCF_Campaign::$campaign_status_vote || $campaign_status == ATCF_Campaign::$campaign_status_collecte ) ): ?>
	<?php locate_template( array( 'projects/single/warning-lightbox.php' ), true ); ?>
<?php endif; ?>
<?php endif; ?>

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

<?php locate_template( array("projects/single/nav.php"), true ); ?>

<?php locate_template( array("projects/single/rewards.php"), true ); ?>

<?php locate_template( array("projects/single/description.php"), true ); ?>

<?php locate_template( array("projects/single/news.php"), true ); ?>

<?php locate_template( array("projects/single/comments.php"), true ); ?>

<?php 
$custom_footer_code = $campaign->custom_footer_code();
if ( !empty( $custom_footer_code ) ) {
	echo $custom_footer_code;
}
