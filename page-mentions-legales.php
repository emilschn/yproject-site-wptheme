<?php get_header(); ?>
<?php require_once("common.php"); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Mentions l&eacute;gales"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc_small">
			<?php 
			    the_content();
			?>
			<br />

			<!-- GeoTrust QuickSSL [tm] Smart Icon tag. Do not edit. -->
			<center><SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="https://smarticon.geotrust.com/si.js"></SCRIPT></center>
			<!-- end GeoTrust Smart Icon tag -->
			<br />
		    </div>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>