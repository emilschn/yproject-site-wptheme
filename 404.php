<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<style type="text/css" data-type="vc_shortcodes-custom-css">.vc_custom_1504881715358{background-image: url(https://www.wedogood.co/wp-content/uploads/2017/09/guerilla-gardening.jpg?id=20411) !important;background-position: center !important;background-repeat: no-repeat !important;background-size: cover !important;}.vc_custom_1487064306779{margin-right: 10px !important;margin-left: 10px !important;}.vc_custom_1504876510677{margin-top: 0px !important;border-top-width: 0px !important;padding-top: 0px !important;}.vc_custom_1504873482986{margin-top: 0px !important;border-top-width: 0px !important;padding-top: 0px !important;background-image: url(https://www.wedogood.co/wp-content/uploads/2014/12/trame.png?id=14743) !important;background-position: 0 0 !important;background-repeat: no-repeat !important;}.vc_custom_1504876349330{margin-top: 90px !important;border-top-width: 0px !important;padding-top: 0px !important;}.vc_custom_1504873653320{padding-top: 10px !important;padding-bottom: 25px !important;}</style>

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