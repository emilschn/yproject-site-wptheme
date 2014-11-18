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
					
					$category_slug = $post_campaign->ID . '-blog-' . $post_campaign->post_name;
					$category_obj = get_category_by_slug($category_slug);
					$category_link = (!empty($category_obj)) ? get_category_link($category_obj->cat_ID) : '';
					$news_link = esc_url($category_link);
					?>

					<?php if ($can_modify): ?>

						<div class="part-title-separator">
							<span class="part-title"><?php echo $post_campaign->post_title; ?></span>
						</div>

						<div class="currentstep">
							<span><span><?php _e('Etape en cours :', 'yproject'); ?></span> <?php _e(ATCF_Campaign::$status_list[$campaign->campaign_status()], 'yproject'); ?></span>
						</div>

						<div class="button-help">
							<a href="<?php echo get_permalink($page_particular_terms->ID); ?>" target="_blank"><?php _e('Conditions particuli&egrave;res', 'yproject'); ?></a>
							<a href="<?php echo get_permalink($page_guide->ID); ?>" target="_blank"><?php _e('Guide de campagne', 'yproject'); ?></a>
							
							<?php if ($campaign->google_doc() != ''): ?>
							<a href="https://docs.google.com/spreadsheets/d/<?php echo $campaign->google_doc(); ?>/edit" target="_blank" class="button"><?php _e('Ouvrir le document de gestion de campagne', 'yproject'); ?></a>
							<?php endif; ?>
							<a href="<?php echo $news_link; ?>" class="button"><?php _e('Publier une actualit&eacute;', 'yproject'); ?></a>
							
							<div class="clear"></div>
						</div>

						<?php if ($campaign->google_doc() != ''): ?>
						<div class="google-doc">
							<iframe src="https://docs.google.com/spreadsheets/d/<?php echo $campaign->google_doc(); ?>/pubhtml?widget=true&amp;headers=false"></iframe>
						</div>
						<?php endif; ?>
		    
					<?php else: ?>

						<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

					<?php endif; ?>

				</div>
		    
			<?php endwhile; endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>