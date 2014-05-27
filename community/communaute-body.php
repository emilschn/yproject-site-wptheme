
<?php the_content(); ?>



<h2 class="underlined">Fil d&apos;activit&eacute;</h2>
<ul class="com-activity-list">
<?php // Affichage du fil d'actualité
	if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&max=10' ) ) :
		while ( bp_activities() ) : bp_the_activity();
			locate_template( array( 'activity/entry.php' ), true, false );
		endwhile;
	endif; ?>
</ul>
<?php $page_activities = get_page_by_path('activite'); ?>
		<a href="<?php echo get_permalink($page_activities->ID); ?>">Plus d&apos;activit&eacute;</a>&nbsp;&gt;&gt;
