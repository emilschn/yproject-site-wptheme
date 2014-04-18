
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
				if ($page_name=="mentions-legales") {?>
				<div class="left post_bottom_desc_small">
					<?php } ?>
					<?php the_content(); ?>
					<?php if ($page_name=="mentions-legales") {?>
					<br />
					<!-- GeoTrust QuickSSL [tm] Smart Icon tag. Do not edit. -->
					<center><SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="https://smarticon.geotrust.com/si.js"></SCRIPT></center>
					<!-- end GeoTrust Smart Icon tag -->
					<br />
				</div>
				<?php } ?>
				<div style="clear: both"></div>
			</div>
		</div>
	<?php endwhile; endif; ?>
</div><!-- .padder -->
</div><!-- #content -->
<?php get_footer(); ?>