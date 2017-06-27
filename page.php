<?php get_header(); ?>

<?php if ( defined( 'SKIP_BASIC_HTML' ) ): ?>
	<?php the_content(); ?>

<?php else: ?>
<div id="content">
    
	<div class="padder">

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry center">
					    
						<?php the_content( __( '<p class="serif">Lire le reste de la page &rarr;</p>', 'yproject' ) ); ?>

					</div>

				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

	</div><!-- .padder -->
	
</div><!-- #content -->

<?php endif; ?>

<?php get_footer();
