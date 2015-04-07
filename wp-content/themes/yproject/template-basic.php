
<?php
/**
 * Template Name: Template Basique
 *
 */
get_header(); ?>

<?php 
date_default_timezone_set("Europe/Paris");
?>
<div id="content">
	<div class="padder">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php locate_template( array("basic/basic-header.php"), true ); ?>
			<div id="post_bottom_bg">
				<div id="post_bottom_content" class="center">
					<?php 
					$page_name = get_post($post)->post_name;
					the_content(); 
					?>
				</div>
			</div>
		<?php endwhile; endif; ?>
	</div><!-- .padder -->
</div><!-- #content -->
<?php get_footer(); ?>