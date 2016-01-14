<?php
global $campaign, $post, $campaign_id, $client_context;
$campaign = atcf_get_current_campaign();
if ($campaign->current_user_can_edit()) {
WDGFormProjects::form_validate_lang_add();
}

$tag_list = $campaign->get_keywords();
$client_context = $campaign->get_client_context();
$classes = ($client_context != '') ? 'theme-' . $client_context . ' ' : '';

if ($campaign->campaign_status() == "vote") { require_once('projects/header-voteform.php'); }
$edit_version = $campaign->edit_version();
$classes .= 'version-' . $edit_version;
?>
			
<?php get_header(); ?>

<?php if (isset($campaign)) : ?>
<div id="content" data-campaignid="<?php echo $campaign->ID; ?>" class="<?php echo $classes; ?>">

	<?php if ($edit_version < 3): ?>
		
		<?php if ($client_context != '') {
		locate_template( array("clients/myphotoreporter/menu.php"), true ); 
		display_photoreporter_menu();
		} ?>

		<div class="padder">

			<?php require_once('projects/single-admin-bar'.$suffix.'.php'); ?>

			<div id="post-<?php echo $campaign->ID; ?>" <?php post_class(); ?>>

				<?php require_once('projects/single-header'.$suffix.'.php'); ?>

				<div id="post_bottom_bg">

					<div id="post_bottom_content" class="center">

						<?php require_once('projects/single-content'.$suffix.'.php'); ?>

						<div style="clear: both"></div>

					</div>
				</div>

			</div>

		</div><!-- .padder -->

	<?php else: ?>
		<?php locate_template( array("projects/single/template.php"), true ); ?>
		
	<?php endif; ?>

</div><!-- #content -->


<?php else: ?>
<div id="content">
	<div class="padder center">
		Aucun projet ne correspond &agrave; cette page.
	</div><!-- .padder -->
</div><!-- #content -->

<?php endif; ?>
	
<?php get_footer();