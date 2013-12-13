<?php 
  
    
    global $campaign, $post;
	
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
		 date_default_timezone_set("Europe/Paris");		
	    require_once("common.php");		
			
	    get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">
				<?php 
				    printAdminBar();
				?>
			    
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				    <div class="post-content">
					<div class="entry">
					    <?php printPageTop($post); ?>
					    
					    <?php printPageBottomStart($post, $campaign); ?>
					    
					    <div style="padding-top: 25px;"><?php echo html_entity_decode($campaign->summary()); ?></div>

					    <h2 class="padding-top">En quoi consiste le projet ?</h2>
					    <span><?php the_content(); ?></span>
					    <?php 
						global $wp_embed; 
						echo $wp_embed->run_shortcode( '[embed]' . $campaign->video() . '[/embed]' ); 
					    ?>

					    <h2 class="padding-top">Quelle est l'opportunité économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->added_value()); ?></div>
					    
					    <h2 class="padding-top">Quelle est l'utilité sociétale du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->societal_challenge()); ?></div>

					    <h2 class="padding-top">Quel est le modèle économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->economic_model()); ?></div>

					    <h2 class="padding-top">Qui porte le projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->implementation()); ?></div>
					</div>
					
					    <?php 
						printPageBottomEnd($post, $campaign);
					    ?>
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