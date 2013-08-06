<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
    
    global $campaign, $post;
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ); ?>

			<div class="page" id="blog-single" role="main">
				<?php 
				    // La barre d'admin n'apparait que pour l'admin du site et pour l'admin de la page
				    $current_user = wp_get_current_user();
				    $current_user_id = $current_user->ID;
				    $author_id = get_the_author_meta('ID');
				    if ($current_user_id == $author_id || current_user_can('manage_options')) {
				?>
				<div id="yp_admin_bar" class="center">
				    <?php /* Lien gerer un projet */ $page_manage = get_page_by_path('gerer'); ?>
				    <a href="<?php echo get_permalink($page_manage->ID); ?>?campaign_id=<?php the_ID(); ?>"><?php echo __('G&eacute;rer vos informations', 'yproject'); ?></a>
				    .:|:.
				    <?php /* Lien ajouter une actu */ $page_add_news = get_page_by_path('ajouter-une-actu'); ?>
				    <a href="<?php echo get_permalink($page_add_news->ID); ?>?campaign_id=<?php the_ID(); ?>"><?php echo __('Ajouter une actualit&eacute;', 'yproject'); ?></a>
				</div>
				<?php } ?>
			    
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
					    <div><?php echo html_entity_decode($campaign->measuring_impact()); ?></div>
					    <div><?php echo html_entity_decode($campaign->implementation()); ?></div>

					    <h2>Quel est le modèle économique du projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->economic_model()); ?></div>
					    <div><?php echo html_entity_decode($campaign->development_strategy()); ?></div>

					    <h2>Qui porte le projet ?</h2>
					    <div><?php echo html_entity_decode($campaign->impact_area()); ?></div>
					    
					    <?php 
						
						$vota = html_entity_decode($campaign->vote());
			
						if($vota == 'vote') {
							printPageVoteForm($post, $campaign);
						} else
						{
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