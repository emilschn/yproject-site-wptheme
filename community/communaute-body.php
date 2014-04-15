
<?php the_content(); ?>


<h2 class="underlined"><?php _e("Derniers inscrits", "yproject"); ?></h2>
<?php 
$args = array (
	"role" => "Subscriber",
	"orderby" => "registered", 
	"order" => "DESC", 
	"number" => 5
	);
$user_query = new WP_User_Query( $args ); 
if ( ! empty( $user_query->results ) ) { ?>
<ul class="com-activity-list">
	<?php
	foreach ( $user_query->results as $user ) {
		$now = new DateTime(date("Y-m-d H:i:s"));
		$registration = new DateTime($user->user_registered);
		$diff = $now->diff($registration);
		if ($diff->y > 0) $time = $diff->format("%y années");
		else if ($diff->m > 0) $time = $diff->format("%m mois");
		else if ($diff->days > 0) $time = $diff->format("%d jours");
		else if ($diff->h > 0) $time = $diff->format("%h heures");
		else if ($diff->i > 0) $time = $diff->format("%i minutes");
		else if ($diff->s > 0) $time = $diff->format("%s secondes");
		echo '<li>' . $user->display_name . __(" a rejoint WEDOGOOD - Il y a ", "yproject") . $time . '</li>';
	} ?>
</ul>
<?php
}
?>
<br/>


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
