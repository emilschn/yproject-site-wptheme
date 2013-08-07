<?php get_header(); ?>

<?php
    wp_reset_query();
    if (is_home() or is_front_page()) {
	require_once("page-home.php");
    } else {
?>

	<div id="content" class="center">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ); ?>

		<div class="page" id="blog-page" role="main">
			<?php 
			    if ($pagename == "gerer" || $pagename == "ajouter-une-actu" || $pagename == "editer-une-actu") {
				require_once("common.php");
				printAdminBar();
			    }
			?>

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry">

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
