<?php get_header(); ?>

<div id="content" style="margin-top: 90px;">
	<div class="padder one-column">
		<div class="center">

			<div id="post-0" class="post page-404 error404 not-found" role="main">
				<h2 class="posttitle"><?php _e( "Vous &ecirc;tes perdu ?", 'yproject' ); ?></h2>

				<p><?php _e( "N&apos;h&eacute;sitez pas &agrave; nous le signaler &agrave; l&apos;adresse", 'yproject' ); ?> bonjour@wedogood.co</p>
				
				<p>
					<?php $page_discover_projects = get_page_by_path('les-projets'); ?>
					<?php _e( "En attendant, allez donc", 'yproject' ); ?> <a href="<?php echo get_permalink($page_discover_projects->ID); ?>"><?php _e('d&eacute;couvrir les projets', 'yproject'); ?></a>.
				</p>
			</div>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer(); ?>