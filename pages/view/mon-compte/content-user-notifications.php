<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUserNotificationsForm = $page_controler->get_user_notifications_form();
$fields_hidden = $WDGUserNotificationsForm->getFields( WDG_Form_User_Notifications::$field_group_hidden );
$fields_newsletters = $WDGUserNotificationsForm->getFields( WDG_Form_User_Notifications::$field_group_newsletters );
$fields_projects = $WDGUserNotificationsForm->getFields( WDG_Form_User_Notifications::$field_group_projects );
$fields_transactions = $WDGUserNotificationsForm->getFields( WDG_Form_User_Notifications::$field_group_transactions );
?>

<h2><?php _e( 'account.notifications.TITLE', 'yproject' ); ?></h2>

<form method="POST" enctype="multipart/form-data" class="db-form v3 full bg-white form-register">

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
		
	<h3><?php _e( 'account.notifications.TITLE_NEWSLETTERS', 'yproject' ); ?></h3>

	<?php foreach ( $fields_newsletters as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
		
	<h3><?php _e( 'account.notifications.TITLE_FOLLOWED_PROJECTS', 'yproject' ); ?></h3>

	<?php foreach ( $fields_projects as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<h3><?php _e( 'account.notifications.TITLE_TRANSACTIONS', 'yproject' ); ?></h3>

	<?php foreach ( $fields_transactions as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<div id="user-notifications-form-buttons">
		<button type="submit" class="button save red"><?php _e( 'common.SAVE_MODIFICATION', 'yproject' ); ?></button>
	</div>
	
</form>