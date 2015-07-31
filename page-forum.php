<?php 
global $wpdb, $campaign, $post;
if ( ! is_object( $campaign ) ) $campaign = atcf_get_campaign( $post );

$post_camp = get_post($_GET['campaign_id']);
$post = $post_camp;
$name = $post_camp->ID.'-2';

if ($name!='') {
	$table_name = $wpdb->prefix . "posts";
	$query="SELECT ID FROM $table_name WHERE post_type='forum' AND post_name= $post_camp->ID";

	$results=$wpdb->get_results($query);

	foreach ($results as $result) {
		$forum_projet_id = $result->ID;
	}
}
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">

			<div class="page" id="blog-single" role="main">
				
				<?php require_once('projects/single-admin-bar.php'); ?>
			    
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php require_once('projects/single-header.php'); ?>
				    
					<div id="post_bottom_bg">
						<div id="post_bottom_content" class="center">
							<div class="left post_bottom_desc" style="padding-top: 20px; min-height: 100px;">
								<?php
								if ( is_user_logged_in() ) {
									echo do_shortcode('[bbp-single-forum id='.$forum_projet_id.']'); 
								} else {
									$page_connexion = get_page_by_path('connexion');
								?>
									Vous devez <a href="<?php echo get_permalink($page_connexion->ID); ?>">&ecirc;tre connect&eacute;</a> pour acc&eacute;der au forum !
								<?php
								}
								?>
							</div>

							

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
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>