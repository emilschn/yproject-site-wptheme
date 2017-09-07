<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php
$WDGUserDetailsForm = $page_controler->get_show_user_details_confirmation();
$fields_hidden = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_hidden );
$fields_basics = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_basics );
$fields_complete = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_complete );
?>

<?php ob_start(); ?>
<div id="user-details-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full ajax-form">
		
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<span class="form-error-general"></span>
			
		<h3><?php _e( "Confirmez vos informations", 'yproject' ); ?></h3>
		
		<?php foreach ( $fields_basics as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<?php if ( !empty( $fields_complete ) ): ?>
		<?php foreach ( $fields_complete as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		<?php endif; ?>
		
		
		<div id="user-details-form-buttons">
			
			<button class="button save red" data-close="user-details" data-open="user-details-confirmation"><?php _e( "Enregistrer", 'yproject' ); ?></button>
			
			<div class="loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="user-details" title="'.__( "Validation de vos informations", 'yproject' ).'" autoopen="1"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
echo do_shortcode('[yproject_lightbox_cornered id="user-details-confirmation" msgtype="valid"]'.__( "Donn&eacute;es enregistr&eacute;es ! Merci !", 'yproject' ).'[/yproject_lightbox_cornered]');