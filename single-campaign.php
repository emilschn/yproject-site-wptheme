<?php 
  
    
    global $campaign, $post;
	
	//getNewPdfToSign($post->ID); //DEBUG
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
					    
					    <div><?php echo html_entity_decode($campaign->summary()); ?></div>

					    <h2>En quoi consiste le projet ?</h2>
					    <span><?php the_content(); ?></span>
					    <?php 
						global $wp_embed; 
						echo $wp_embed->run_shortcode( '[embed]' . $campaign->video() . '[/embed]' ); 
					    ?>

					    <h2>Quelle est l'opportunité économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->added_value()); ?></div>
					    
					    <h2>Quelle est l'utilité sociétale du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->societal_challenge()); ?></div>

					    <h2>Quel est le modèle économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->economic_model()); ?></div>

					    <h2>Qui porte le projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->implementation()); ?></div>
					    </div>
					    <?php 
						
						$vota = html_entity_decode($campaign->vote());

						// Nombre de jours restants					
						$dateJour = strtotime(date("d-m-Y"));
						$fin   = strtotime($post->campaign_end_vote);
						$jours_restants = (round(abs($fin - $dateJour)/60/60/24));
					
						if ($vota == 'vote' && $jours_restants > 0) {
						    do_shortcode('[yproject_crowdfunding_printPageVoteForm remaining_days='.$jours_restants.']');
						} elseif ($vota == 'vote' && $jours_restants <= 0) {
						    do_shortcode('[yproject_crowdfunding_printPageVoteDeadLine]');
						} elseif ($vota !='vote') {
						    printPageBottomEnd($post, $campaign);
						}
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