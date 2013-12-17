<?php get_header(); ?>
<?php require_once("common.php"); ?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Fil d&apos;activit&eacute;"); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">
			<?php $page_community = get_page_by_path('communaute'); // Menu Communauté ?>
			&lt;&lt; <a href="<?php echo get_permalink($page_community->ID); ?>"><?php _e('Communaut&eacute;', 'yproject'); ?></a></br>
			
			<ul class="com-activity-list">
			<?php 
			if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) :
			    while ( bp_activities() ) : bp_the_activity();
				locate_template( array( 'activity/entry.php' ), true, false );
			    endwhile;
			endif; 
			?>
			</ul>
		    </div>

		    <?php printCommunityMenu(); ?>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>