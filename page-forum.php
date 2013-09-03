<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
    
    global $wpdb, $campaign, $post;
    if ( ! is_object( $campaign ) )
	    $campaign = atcf_get_campaign( $post );
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


					    <div>
					    	<ul>

							<?php
							$post_camp = get_post($_GET['campaign_id']);

							$name = $post_camp->ID.'-2';
							
							if ($name!='') {
								
								$query="SELECT ID FROM wp_posts WHERE post_type='forum' AND post_name= $post_camp->ID";

								$results=$wpdb->get_results($query);


								foreach ($results as $result) {
									 $forum_projet_id = $result->ID;
									
								}
							}
							?>

							</ul>
					    </div>
					    
					    <span>
					    <?php echo do_shortcode('[bbp-single-forum id='.$forum_projet_id.']'); ?>
					    <?php 
						 printPageBottomEnd($post, $campaign);
						?>
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