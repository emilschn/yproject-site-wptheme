<?php 
/**
 * Template Name: Single Campaign
 *
 */
    global $campaign, $post;
    $page_name = get_post($post)->post_name;
    if ( ! is_object( $campaign ) ) {
	    $campaign = atcf_get_campaign( $post );
    }
    $campaign_id = $_GET['campaign_id'];

    $classes = '';
    $tag_list = wp_get_post_terms($campaign_id, 'download_tag');
    foreach ($tag_list as $tag) {
	    if ($classes != '') { $classes .= ' '; }
	    $classes .= 'theme-' . $tag->slug;
	    $client_context = $tag->slug;
    }
    if ($page_name == ATCF_Campaign::$campaign_status_vote) {
	global $disable_logs;
	$disable_logs = TRUE;
	ypcf_shortcode_vote_results_header();
    }
?>

<?php get_header(); ?>
<div id="content" <?php echo 'class="'.$classes.'"'; ?>>
    
	<?php if ($classes != '') {
	locate_template( array("clients/myphotoreporter/menu.php"), true ); 
	display_photoreporter_menu();
	} ?>
    
	<div class="padder">

		<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="center margin-height">
						<?php
						switch ($page_name) {
							case 'editer-une-actu':
								do_shortcode('[yproject_crowdfunding_edit_news]');
								break;
							default:
								the_content();
								break;
						}
						?>
					</div>
				</div>
			<?php endwhile; endif; ?>

		</div>

	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>