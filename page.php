<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<?php if ( defined( 'SKIP_BASIC_HTML' ) ): ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php the_content(); ?>
		
	<?php endwhile; endif; ?>

<?php else: ?>
<div id="content">
    
	<div class="padder page">

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

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );
