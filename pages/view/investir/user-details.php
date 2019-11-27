<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_hidden );
$fields_user_type = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_user_type );
$fields_user_info = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_user_info );
$fields_orga_select = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_select );
$fields_orga_info = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_info );
$fields_orga_info_new = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_orga_info_new );
$fields_info_confirm = $page_controler->get_form()->getFields( WDG_Form_Invest_User_Details::$field_group_confirm );
?>
	
<form action="<?php echo $page_controler->get_form_action(); ?>#infosfilled" method="post" class="db-form v3 full bg-white">
	
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
	
	<?php foreach ( $fields_user_type as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	
	<div id="fieldgroup-user-type-orga" class="fieldgroup-user-type hidden">
	<?php foreach ( $fields_orga_select as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-user-info" class="hidden">
	<h2><?php _e( "Informations personnelles", 'yproject' ); ?></h2>
	<?php foreach ( $fields_user_info as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
	<?php endforeach; ?>
	</div>
	
	<div id="fieldgroup-orga-info" class="fieldgroup-user-type hidden">
	<h2><?php _e( "Informations de votre organisation", 'yproject' ); ?></h2>
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

		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<button type="submit" class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
	
		<button type="submit" name="nav" value="previous" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
	</div>
	
	<div class="clear"></div>
	
</form>