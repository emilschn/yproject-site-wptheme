<?php date_default_timezone_set("Europe/Paris"); ?>
  
<?php 
	global $campaign, $post, $campaign_id;
	$campaign_id=$post->ID;
	if ( ! is_object( $campaign ) ) $campaign = atcf_get_campaign( $post );
	$vote_status = $campaign->campaign_status(); 
?>
			
<?php get_header(); ?>
<?php if($vote_status="vote") require_once('projects/header-voteform.php');

if (have_posts()) : while (have_posts()) : the_post(); ?>
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
						
							<?php  
							 		require_once('projects/single-content.php'); 
							 		
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