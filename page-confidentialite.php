<?php 
    get_header();
    require_once("common.php");
?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Confidentialit&eacute;"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
			<?php the_content(); ?>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>