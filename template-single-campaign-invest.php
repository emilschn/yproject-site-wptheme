<?php 
/**
 * Template Name: Single Campaign Invest
 *
 * @package Atlas
 */
    global $campaign, $post;
    $page_name = get_post($post)->post_name;
    if ( ! is_object( $campaign ) ) $campaign = atcf_get_campaign( $post );
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<div class="page" id="blog-single" role="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content">
						<div class="entry">
							<?php require_once('projects/single-header.php'); ?>
							<div class="center">
								<?php 
								if ($page_name == 'paiement') echo ypcf_print_invest_breadcrumb(3);
								
								the_content();
								
								if ($page_name == 'paiement') :
								?>
								<div id="PaylineForm"></div>
								<center><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="Bandeau Mangopay" /></center>
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