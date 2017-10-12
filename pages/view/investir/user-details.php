<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_hidden );
$fields_user_info = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_user_info );
$fields_orga_select = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_select );
$fields_orga_info = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_info );
$fields_orga_info_new = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_info_new );
$fields_info_confirm = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_confirm );
?>
	
<form method="post" class="db-form v3 full bg-white">
	
	<div class="align-left">
		<?php $form_errors = $page_controler->get_form_errors(); ?>
		<?php if ( $form_errors ): ?>
			<?php foreach ( $form_errors as $form_error ): ?>
				<span class="invest_error"><?php echo $form_error[ 'text' ]; ?></span>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<?php foreach ( $fields_user_info as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div id="fieldgroup-user-type-orga" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_orga_select as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-orga-info" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_orga_info as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-orga-info-new" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_orga_info_new as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-to-display" class="hidden">
		<?php foreach ( $fields_info_confirm as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
		<?php endforeach; ?>

		<button type="submit" class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
	</div>
	
	<div class="clear"></div>
	
</form>