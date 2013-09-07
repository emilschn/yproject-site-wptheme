<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

	<div id="content">
		<div class="padder">
				
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    	<?php printCommunityTop("FAQ"); ?>
						<div id="post_bottom_bg">

							<div id="post_bottom_content" class="center">
							    <div class="left post_bottom_desc">
							    		<?php $page_descriptif = get_page_by_path('descriptif'); // Menu Comment ça marche ?>
										&lt;&lt; <a href="<?php echo get_permalink($page_descriptif->ID); ?>"><?php echo __('Comment ça marche ?', 'yproject'); ?></a>
				 		
										<span><?php the_content(); ?></span>
										
								 	</div> 
							   

							    <div class="left post_bottom_infos">
							    	<?php printCommentcamarcheright(); ?>
							    	

							    </div>
							    <div style="clear: both"></div>

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