<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">
				
			<div class="page" id="blog-single" role="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content">
					<div class="entry">
				 		<?php printPageTop($post); ?>
						<div id="post_bottom_bg">

							<div id="post_bottom_content" class="center">
							    <div class="left post_bottom_desc">
									
										<?php descCommentCaMarche(); ?>
								 	</div> 
							   

							    <div class="left post_bottom_infos">

							    	<div class="post_bottom_buttons">
							    		<div class="dark" style="color:white;">
							    			<?php /* Lien page faq */ $page_manage = get_page_by_path('faq-2'); ?>
	    									<a href="<?php echo get_permalink($page_manage->ID); ?>"><?php echo __('FAQ', 'yproject'); ?></a>
	    							    </div>
							    		<div class="light" >
							    			
							    		</div>
							    	</div>
							    	

							    </div>
							    <div style="clear: both"></div>

							</div>
						 </div> 
							   
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