<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

	<div id="content">
		<div class="padder">
				
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    	<?php printCommunityTop("Comment ça marche ?"); ?>
						<div id="post_bottom_bg">

							<div id="post_bottom_content" class="center">
							    <div class="left post_bottom_desc">
									
										<span style="font-size:14px; font-weight:normal;"><?php the_content(); ?></span>
										<ul>
										<?php /* Menu Proposer un projet */ $page_start = get_page_by_path('proposer-un-projet'); ?>
										<li class="page_item"><a href="<?php echo get_permalink($page_start->ID); ?>"><?php echo __('Proposer un projet', 'yproject'); ?></a></li>
										</ul>

										<ul>
										<?php /* Menu Découvrir les projets */ $page_discover = get_page_by_path('projects'); ?>
										<li class="page_item"><a href="<?php echo get_permalink($page_discover->ID); ?>"><?php echo __('Decouvrir les projets', 'yproject'); ?></a>
										</li>
										</ul>
								 	</div> 
							   

							    <div class="left post_bottom_infos">

							    	
							    	<?php showFaq(6); ?>
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