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
									locate_template( array("projects/single-stats-public.php"), true );
									break;
								    default:
									the_content();
									break;
								}
								?>
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