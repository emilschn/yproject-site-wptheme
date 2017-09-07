<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<div id="content">
    
	<div class="padder page">

		<div class="page" id="blog-page" role="main">

			<?php $page_404 = get_page_by_path( 'page-404' ); ?>
			<?php if ( !empty( $page_404 ) ): ?>

				<div id="post0" class="post page-404 error404 not-found">

					<div class="entry center">
					    
						<?php echo apply_filters( 'the_content', $page_404->post_content ); ?>

					</div>

				</div>

			<?php else: ?>

				<div id="post-0" style="margin-top: 80px;">
					
					<h2 class="posttitle"><?php _e( "Vous &ecirc;tes perdu ?", 'yproject' ); ?></h2>

					<p><?php _e( "N&apos;h&eacute;sitez pas &agrave; nous le signaler &agrave; l&apos;adresse", 'yproject' ); ?> bonjour@wedogood.co</p>

					<p>
						<?php $page_discover_projects = get_page_by_path('les-projets'); ?>
						<?php _e( "En attendant, allez donc", 'yproject' ); ?> <a href="<?php echo get_permalink($page_discover_projects->ID); ?>"><?php _e('d&eacute;couvrir les projets', 'yproject'); ?></a>.
					</p>
					
				</div>
			
			
			<?php endif; ?>

		</div><!-- .page -->

	</div><!-- .padder -->
	
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );