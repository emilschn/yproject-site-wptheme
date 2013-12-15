<?php 
    get_header();
    require_once("common.php");
?>

<div id="content">
    <div class="padder">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    <?php printMiscPagesTop("Communaute", true); ?>
	    <div id="post_bottom_bg">
		<div id="post_bottom_content" class="center">
		    <div class="left post_bottom_desc">
			<?php the_content(); ?>
			

			<h2 class="underlined"><?php _e("Derniers inscrits", "yproject"); ?></h2>
			<?php 
			    $args = array (
				"role" => "Subscriber",
				"orderby" => "registered", 
				"order" => "DESC", 
				"number" => 5,
				"meta_key" => "last_activity",
				"meta_value" => "",
				"meta_compare" => "<>"
			    );
			    $user_query = new WP_User_Query( $args ); 
			    if ( ! empty( $user_query->results ) ) {
				echo '<ul class="last_subscribers">';
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
				}
				echo '</ul>';
			    }
			?>
			<br/>
			<h2 class="underlined"><?php _e("Fil d'actualité", "yproject"); ?></h2>

				<?php // Affichage du fil d'actualité ?>
					<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&max=10' ) )  : ?>
    				<?php while ( bp_activities() ) : bp_the_activity(); ?>
 
        			<?php locate_template( array( 'activity/entry.php' ), true, false ); ?>
 
    		<?php endwhile; ?>
			<?php endif; ?>

			<?php $page_activities = get_page_by_path('activities'); ?>
				<a href="<?php echo get_permalink($page_activities->ID); ?>"><?php echo __('Plus d\'actualités', 'yproject'); ?></a>
				&gt;&gt;<br />
		    </div>

		    <?php printCommunityMenu(); ?>
		    <div style="clear: both"></div>
		</div>
	    </div>

	<?php endwhile; endif; ?>

    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>