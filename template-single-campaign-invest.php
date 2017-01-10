<?php 
/**
 * Template Name: Single Campaign Invest
 *
 * @package Atlas
 */
    global $campaign, $post;
    $page_name = get_post($post)->post_name;
    $campaign = atcf_get_current_campaign();
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<div id="template-single-campaign-invest" class="page" role="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content">
						<div class="entry">
							
							<h1><?php _e('Investir sur le projet', 'yproject'); ?> <?php echo $campaign->data->post_title; ?></h1>
							
							<div class="center">
								<div class="align-center">(<a href="<?php echo get_permalink($campaign->ID); ?>"><?php _e("retour au projet", 'yproject'); ?></a>)</div>
								
								<?php 
								if ($page_name == 'paiement') {
									global $current_breadcrumb_step; $current_breadcrumb_step = 3;
									locate_template( 'invest/breadcrumb.php', true );
								}
								
								the_content(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div><!-- .padder -->
	</div><!-- #content -->


<?php endwhile; else: ?>
	<div id="content">
	    <div class="padder center">
		<p><?php _e( 'D&eacute;sol&eacute;, aucun article correspondant.', 'yproject' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>