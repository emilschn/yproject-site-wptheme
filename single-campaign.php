<?php
global $campaign, $post, $campaign_id, $client_context;
$campaign = atcf_get_current_campaign();
if (!empty($campaign)) {
	if ( ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_preparing || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_validated) && !$campaign->current_user_can_edit()) {
		wp_redirect(home_url());
	}
	if ($campaign->current_user_can_edit()) {
		WDGFormProjects::form_validate_lang_add();
	}

	$tag_list = $campaign->get_keywords();
	$client_context = $campaign->get_client_context();
	$classes = ($client_context != '') ? 'theme-' . $client_context . ' ' : '';

	$classes .= 'version-3';
}

// Pour les projets masqués, on enregistre en session pour que l'utilisateur puisse le retrouver
// (uniquement pour les utilisateurs qui ne modifient pas la page)
if ( $campaign->is_hidden() && !$campaign->current_user_can_edit() ) {
	setcookie( 'hidden_project_visited', $campaign->ID, time() + 10 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	setcookie( 'hidden_project_visited_title', $campaign->get_name(), time() + 10 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
	setcookie( 'hidden_project_visited_url', $campaign->get_public_url(), time() + 10 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
}
?>
			
<?php get_header(); ?>

<?php if ( !empty( $campaign ) ) : ?>
<div id="content" data-campaignid="<?php echo $campaign->ID; ?>" data-campaignstatus="<?php echo $campaign->campaign_status(); ?>" class="<?php echo $classes; ?>">

	<?php locate_template( array("projects/single/template.php"), true ); ?>

</div><!-- #content -->


<?php else: ?>
<div id="content">
	<div class="padder">
		Aucun projet ne correspond &agrave; cette page.
	</div><!-- .padder -->
</div><!-- #content -->

<?php endif; ?>
	
<?php get_footer();
