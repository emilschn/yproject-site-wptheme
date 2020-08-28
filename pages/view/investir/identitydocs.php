<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
$fields_files = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
$fields_files_orga = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
$fields_phone_notification = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_notification );
$fields_phone_number = $page_controler->get_form()->getFields( WDG_Form_User_Identity_Docs::$field_group_phone_number );
$form_errors = $page_controler->get_form_errors();
?>

<?php if ( $page_controler->is_form_success_displayed() ): ?>
	<div class="center upload-docs">
		<?php if ( $page_controler->is_form_file_sent_displayed() ): ?>
			<div class="wdg-message confirm">
				<?php _e( 'invest.identitydocs.DOCUMENTS_RECEIVED', 'yproject' ); ?>
			</div>
		<?php endif; ?>

		<div>
			<?php echo sprintf( __( 'invest.identitydocs.INVESTMENT_SAVED', 'yproject' ), $page_controler->get_current_investment()->get_session_amount() ); ?><br>
			<?php _e( 'invest.identitydocs.WILL_BE_NOTIFIED', 'yproject' ); ?><br>
			<?php _e( 'invest.identitydocs.DOCS_IN_ACCOUNT', 'yproject' ); ?><br><br>
			<?php _e( 'invest.identitydocs.THANKS', 'yproject' ); ?><br><br>
		</div>
		
		<div class="align-center">
			<a href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>" class="button transparent"><?php _e( 'invest.header.BACK_TO_PROJECT', 'yproject' ); ?></a>
			<br><br>
		</div>
	</div>

<?php else: ?>
	<?php if ( !empty( $form_errors ) ): ?>
		<div class="wdg-message error">
			<?php foreach ( $form_errors as $form_error ): ?>
				<?php echo $form_error; ?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full bg-white enlarge identitydocs">
	
		<p class="align-justify resp-item-1">
			<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_1', 'yproject' ); ?>
			<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_2', 'yproject' ); ?><br>
			<?php _e( 'account.identitydocs.AUTHENTICATION_TEXT_3', 'yproject' ); ?><br><br>
		</p>

		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_files as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php if ( !empty( $fields_files_orga ) ): ?>
			<?php foreach ( $fields_files_orga as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		<?php endif; ?>

		<p class="align-left resp-item-5">
			* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
		</p>

		<?php if ( $fields_phone_notification ): foreach ( $fields_phone_notification as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; endif; ?>
		<div class="align-left phone-info">
			<?php _e( 'account.identitydocs.SMS_EXPLAINED', 'yproject' ); ?>
		</div>

		<div class="phone-number-hidden">
			<?php foreach ( $fields_phone_number as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		</div>

		<div class="align-center resp-item-6">
			<button type="submit" class="button save red"><?php _e( 'account.identitydocs.SEND_DOCUMENTS', 'yproject' ); ?></button>
			<br><br>
			<button type="submit" class="button save transparent"><?php _e( 'invest.identitydocs.SEND_LATER', 'yproject' ); ?></button>
			<div style="margin-top: 8px;">
				(<?php _e( 'invest.identitydocs.INVEST_LATER', 'yproject' ); ?>)
			</div>
		</div>

	</form>
<?php endif; ?>