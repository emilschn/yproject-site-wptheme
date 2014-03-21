<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
    
    global $campaign, $post;
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">
				<?php require_once('projects/single-admin-bar.php'); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php require_once('projects/single-header.php'); ?>

					<div id="post_bottom_bg">
						<div id="post_bottom_content" class="center">
							<div class="left post_bottom_desc">
								<?php the_content(); ?>
							</div>

							<div class="left post_bottom_infos">
								<?php require_once('projects/single-sidebar.php'); ?>
							</div>

							<div style="clear: both"></div>
						</div>
					</div>
				</div>

			</div>
			

			<?php do_action( 'bp_after_blog_single_post' ); ?>

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