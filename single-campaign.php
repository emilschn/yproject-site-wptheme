<?php date_default_timezone_set("Europe/Paris"); ?>
  
<?php 
	global $campaign, $post, $campaign_id;
	$campaign_id=$post->ID;
	if ( ! is_object( $campaign ) ) $campaign = atcf_get_campaign( $post );
?>
			
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="content">
	<div class="padder">
		<div class="page" id="blog-single" role="main">
			<?php require_once('projects/single-admin-bar.php'); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
				<?php  
						require_once('projects/single-header.php'); 
				?>

				<div id="post_bottom_bg">
					<div id="post_bottom_content" class="center">
						
							<?php  $cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content');
									if(false===$cache_result){
									ob_start();
							 		require_once('projects/single-content.php'); 
							 		$cache_result=ob_get_contents();
									$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content',$cache_result);
						 			ob_end_clean();
									}
								echo $cache_result;
						?>
						<div style="clear: both"></div>
					</div>
				</div>
			</div>
		</div>
	</div><!-- .padder -->
</div><!-- #content -->


<?php endwhile; else: ?>
<div id="content">
    <div class="padder center">
	Aucun projet ne correspond &agrave; cette page.
    </div><!-- .padder -->
</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>