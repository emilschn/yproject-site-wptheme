<?php get_header(); ?>
<?php require_once("common.php"); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printCommunityTop("MakeSense"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">
			<?php $page_community = get_page_by_path('communaute'); // Menu Communauté ?>
			&lt;&lt; <a href="<?php echo get_permalink($page_community->ID); ?>"><?php echo __('Communaute', 'yproject'); ?></a>
			
			<?php the_content(); ?>
		    </div>

		    <?php printCommunityMenu(); ?>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>