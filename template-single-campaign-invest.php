<?php 
/**
 * Template Name: Single Campaign Invest
 *
 * @package Atlas
 */
    global $campaign, $post;
    $page_name = get_post($post)->post_name;
    $campaign = atcf_get_current_campaign();
	$wdginvestment = WDGInvestment::current();
	$context = ATCF_CrowdFunding::get_platform_context();
	if ( $wdginvestment->has_token() ) {
		$context = 'invest-token';
	}
?>

<?php get_header( $context ); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<div id="template-single-campaign-invest" class="page" role="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content">
						<div class="entry">
							
							<?php if ( $wdginvestment->has_token() ): ?>
							<h1><?php _e('Investir en royalties', 'yproject'); ?></h1>
							<?php else: ?>
							<h1><?php _e('Investir sur le projet', 'yproject'); ?> <?php echo $campaign->data->post_title; ?></h1>
							<?php endif; ?>
							
							<div class="center">
								<?php if ( !$wdginvestment->has_token() ): ?>
								<div class="align-center">(<a href="<?php echo get_permalink($campaign->ID); ?>"><?php _e("retour au projet", 'yproject'); ?></a>)</div>
								<?php endif; ?>
								
								<?php 
								if ($page_name == 'paiement') {
									global $current_breadcrumb_step; $current_breadcrumb_step = 3;
									locate_template( 'invest/breadcrumb.php', true );
								}
								
								the_content();
								?>
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

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );