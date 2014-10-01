<?php

/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

date_default_timezone_set("Atlantic/Azores");
do_action( 'bp_before_member_activity_content' ); ?>
<h2 class="underlined">Mon Fil d'activit&eacute;</h2>
<div class="activity center" role="main">

	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>

</div><!-- .activity -->

<?php do_action( 'bp_after_member_activity_content' ); ?>
