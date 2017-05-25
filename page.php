<?php get_header(); ?>

<?php
wp_reset_query();
if (is_home() or is_front_page()) {
	locate_template( array('pages/controler/controler.php'), true );
	locate_template( array('pages/controler/controler-home.php'), true );
	locate_template( array('pages/view/view-home.php'), true );
} else {
?>

<div id="content">
    
	<div class="padder">

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php 
					ob_start();
					$temp = ob_get_clean(); 
					?>
					<div class="entry center">
					    
						<?php the_content( __( '<p class="serif">Lire le reste de la page &rarr;</p>', 'yproject' ) ); ?>

					</div>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

	</div><!-- .padder -->
	
</div><!-- #content -->
<?php
}
?>

<?php get_footer(); ?>
