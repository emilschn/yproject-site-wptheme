<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_hidden );
$fields_user_type = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_user_type );
$fields_user_info = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_user_info );
$fields_orga_select = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_select );
?>
	
<form method="post" class="db-form v3 full bg-white">

	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<?php foreach ( $fields_user_type as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div id="fieldgroup-user-type-user" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_user_info as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-user-type-orga" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_orga_select as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<button type="submit" class="button half right transparent hidden"><?php _e( "Suivant", 'yproject' ); ?></button>
	
	<div class="clear"></div>
	
</form>