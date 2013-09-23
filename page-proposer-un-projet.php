<?php get_header(); ?>
<?php require_once("common.php"); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Proposer un projet"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc_small">
			<?php 
			if (is_user_logged_in()) {
			    the_content();
			} else {
			    $page_connexion = get_page_by_path('connexion');
			?>
			    <a href="<?php echo get_permalink($page_connexion->ID); ?>">Vous devez &ecirc;tre connect&eacute; pour proposer un projet</a>
			<?php } ?>
		    </div>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>