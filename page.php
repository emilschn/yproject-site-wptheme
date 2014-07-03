<?php get_header(); ?>

<?php
    wp_reset_query();
    if (is_home() or is_front_page()) {
	require_once("page-home.php");
    } else {
?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php 
					ob_start(); 
					$is_single_forum = false;
					if ( !bbp_is_forum_category() && bbp_has_topics() ) : 
					    $temp = ob_get_clean(); 
					    $is_single_forum = true;
					else: 
					    $temp = ob_get_clean(); 
					endif; 
					?>

					<?php if ($is_single_forum === true):
						global $campaign_id;
						$temp_post = get_post(get_the_ID());
						$campaign_id = $temp_post->post_title;
						require_once('projects/single-admin-bar.php');
						require_once('projects/single-header.php');
					?>
					<div class="entry">
					<?php else: ?>	
					<div class="entry center">
					<?php endif; ?>
					    
						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'buddypress' ) ); ?>

					</div>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->
<?php
    }
?>

<?php get_footer(); ?>
