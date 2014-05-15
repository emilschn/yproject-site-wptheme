<?php 
/**
 * Template Name: Single Campaign
 *
 */
    global $campaign, $post;
    $page_name = get_post($post)->post_name;
    if ( ! is_object( $campaign ) ) $campaign = atcf_get_campaign( $post );
    if ($page_name == 'vote') {
	global $disable_logs;
	$disable_logs = TRUE;
	ypcf_shortcode_vote_results_header();
    }
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">

		<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php require_once('projects/single-admin-bar.php'); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php require_once('projects/single-header.php'); ?>

					<div id="post_bottom_bg">
						<div id="post_bottom_content" class="center">
							<div class="left post_bottom_desc">
								<?php
								switch ($page_name) {
								    case 'ajouter-une-actu':
									do_shortcode('[yproject_crowdfunding_add_news]');
									break;
								    case 'editer-une-actu':
									do_shortcode('[yproject_crowdfunding_edit_news]');
									break;
								    case 'statistiques-avancees':
									locate_template( array("projects/single-stats-advanced-main.php"), true );
									break;
								    case 'statistiques-avancees-votes':
									locate_template( array("projects/single-stats-advanced-votes.php"), true );
									break;
								    case 'statistiques-avancees-investissements':
									locate_template( array("projects/single-stats-advanced-investments.php"), true );
									break;
								    case 'statistiques':
									the_content();
									if (isset($_GET["campaign_id"])) {
									    $post_campaign = get_post($_GET["campaign_id"]);
									    $upload_dir = wp_upload_dir();
									    if (file_exists($upload_dir['basedir'] . '/projets/' . $post_campaign->post_name . '-stats.jpg')) { 
										echo '<img src="'.$upload_dir['baseurl'] . '/projets/' . $post_campaign->post_name . '-stats.jpg" alt="Statistiques du projet" />';
									    }
									}
									break;
								    default:
									the_content();
									break;
								}
								?>
							</div>

							<div class="left post_bottom_infos">
								<?php require_once('projects/single-sidebar.php'); ?>
							</div>

							<div style="clear: both"></div>
						</div>
					</div>
				</div>
			<?php endwhile; endif; ?>

		</div>

	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>