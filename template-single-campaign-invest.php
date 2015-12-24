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
							
							<h1><?php _e('Investir sur le projet'); ?> <?php echo $campaign->data->post_title; ?></h1>
							
							<div class="center">
								<?php 
								if ($page_name == 'paiement') echo ypcf_print_invest_breadcrumb(3, $campaign->funding_type());
								
								the_content();
								
								if ($page_name == 'paiement') :
								?>
								<div id="PaylineForm"></div>
								<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="bandeau mangopay" /></div>
								<?php endif; ?>
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
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>