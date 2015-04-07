<?php 
/**
 * Template Name: Template Communauté
 *
 */
get_header();
date_default_timezone_set("Europe/London");
?>
<div id="content">
	<div class="padder">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php locate_template( array("community/community-header.php"), true ); ?>
		<div id="post_bottom_bg">
			<div id="post_bottom_content" class="center">
				<div class="left community-desc">
					<?php		    	
					$page_name = get_post($post)->post_name;
					switch ($page_name) {
						case 'communaute':
						locate_template( array("community/communaute-body.php"), true );
						break;
						case 'blog':
						locate_template( array("community/blog-body.php"), true );
						break;
						case 'activite':
						locate_template( array("community/activite-body.php"), true );
						break;
						default://Page l'equipe, makesense ou partenaires
						locate_template( array("community/community-general-body.php"), true );
						break;	
					}?>
				</div>

				<?php locate_template( array("community/community-menu.php"), true ); ?>
				<div style="clear: both"></div>
			</div>
		</div>

		<?php endwhile; endif; ?>
	</div><!-- .padder -->
</div><!-- #content -->
<?php get_footer();?>

