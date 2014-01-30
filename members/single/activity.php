<?php

/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity center" role="main">

	<?php locate_template( array( 'activity/activity-loop.php' ), true ); ?>

</div><!-- .activity -->

<?php do_action( 'bp_after_member_activity_content' ); ?>
