<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">
				
		    <?php printMiscPagesTop("Foire aux questions"); ?>
		    
		    <div id="post_bottom_bg">
			<div id="post_bottom_content" class="center">
			    <div class="left post_bottom_desc">
				<?php $page_descriptif = get_page_by_path('descriptif'); // Menu Comment ça marche ?>
				&lt;&lt; <a href="<?php echo get_permalink($page_descriptif->ID); ?>"><?php echo __('Comment ça marche ?', 'yproject'); ?></a><br /><br />
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