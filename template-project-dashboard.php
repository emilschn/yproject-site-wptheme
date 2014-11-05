<?php 
/**
 * Template Name: Projet Tableau de bord
 *
 */
$campaign_id = $_GET['campaign_id'];
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		    
				<?php require_once('projects/single-admin-bar.php'); ?>
		    
				<div id="dashboard" class="center margin-height">
					<?php 
					global $can_modify, $campaign_id; 
					$post_campaign = get_post($campaign_id);
					$campaign = atcf_get_campaign($post_campaign);
					$page_guide = get_page_by_path('guide');
					$page_particular_terms = get_page_by_path('conditions-particulieres');
					?>

					<?php if ($can_modify): ?>

						<div class="part-title-separator">
							<span class="part-title"><?php _e('Bienvenue', 'yproject'); ?></span>
						</div>

						<div class="currentstep">
							<span><span><?php _e('Etape en cours :', 'yproject'); ?></span> <?php _e(ATCF_Campaign::$status_list[$campaign->campaign_status()], 'yproject'); ?></span>
						</div>

						<div class="button-help">
							<a href="<?php echo get_permalink($page_particular_terms->ID); ?>"><?php _e('Conditions particuli&egrave;res', 'yproject'); ?></a>
							<a href="<?php echo get_permalink($page_guide->ID); ?>"><?php _e('Guide de campagne', 'yproject'); ?></a>
							<div class="clear"></div>
						</div>

						<div class="google-doc">
							<iframe src="https://docs.google.com/spreadsheets/d/<?php echo $campaign->google_doc(); ?>/pubhtml?widget=true&amp;headers=false"></iframe>
						</div>
		    
					<?php else: ?>

						<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

					<?php endif; ?>

				</div>
		    
			<?php endwhile; endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>