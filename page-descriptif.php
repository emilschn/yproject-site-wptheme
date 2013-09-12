<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">
				
		    <?php printMiscPagesTop("Comment ca marche ?"); ?>
		    
		    <div id="post_bottom_bg">
			<div id="post_bottom_content" class="center">
			    <div class="left post_bottom_desc">
				<?php the_content(); ?>
			    </div> 

			    <div class="left post_bottom_infos">
				<?php showFaq(6); ?>
			    </div>
			    <div style="clear: both"></div>
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