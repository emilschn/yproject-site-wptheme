
<?php
/**
 * Template Name: Template Basique
 *
 */
  get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">
		    <?php locate_template( array("basic/basic-header.php"), true ); ?>
		    <div id="post_bottom_bg">
			<div id="post_bottom_content" class="center">
			    <?php the_content(); ?>
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