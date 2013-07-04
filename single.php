<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="post-content">
					<div class="entry">
					    <?php printPageProject(); ?>
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