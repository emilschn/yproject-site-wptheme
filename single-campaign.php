<?php date_default_timezone_set("Europe/Paris"); ?>
  
<?php 
global $campaign, $post, $campaign_id, $client_context;
$campaign_id = $post->ID;
if ( ! is_object( $campaign ) ) { $campaign = atcf_get_campaign($post); }
$tag_list = wp_get_post_terms($campaign_id, 'download_tag');
$classes = '';
foreach ($tag_list as $tag) {
	if ($classes != '') { $classes .= ' '; }
	$classes .= 'theme-' . $tag->slug;
	$client_context = $tag->slug;
}
if ($campaign->campaign_status() == "vote") { require_once('projects/header-voteform.php'); }
$suffix = ($campaign->edit_version() > 1) ? '-sf' : '';
?>
			
<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="content" data-campaignid="<?php the_ID(); ?>" <?php echo 'class="'.$classes.'"'; ?>>
    
	<?php if ($classes != '') {
	locate_template( array("clients/myphotoreporter/menu.php"), true ); 
	display_photoreporter_menu();
	} ?>
    
	<div class="padder">

		<?php require_once('projects/single-admin-bar.php'); ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php require_once('projects/single-header'.$suffix.'.php'); ?>

			<div id="post_bottom_bg">

				<div id="post_bottom_content" class="center">

					<?php require_once('projects/single-content'.$suffix.'.php'); ?>

					<div style="clear: both"></div>

				</div>
			</div>

		</div>
		    
	</div><!-- .padder -->
</div><!-- #content -->

<?php if (!is_user_logged_in()): ?>
	<?php echo do_shortcode('[yproject_connexion_lightbox]<p class="align-center">'.__('Afin de soutenir un projet, vous devez &ecirc;tre inscrit et connect&eacute;.', 'yproject').'</p>[/yproject_connexion_lightbox]'); ?>
	<?php echo do_shortcode('[yproject_register_lightbox]'); ?>
<?php endif; ?>


<?php endwhile; else: ?>
<div id="content">
	<div class="padder center">
		Aucun projet ne correspond &agrave; cette page.
	</div><!-- .padder -->
</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer();