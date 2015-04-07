
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
