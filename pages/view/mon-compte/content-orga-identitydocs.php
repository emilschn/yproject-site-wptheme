<?php
global $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGOrganizationIdentityDocsForm = new WDG_Form_User_Identity_Docs( $WDGOrganization->get_wpref(), TRUE );
$fields_hidden = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
$fields_files = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
$fields_files_orga = $WDGOrganizationIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files_orga );
?>

<h2 class="underlined"><?php _e( "Justificatifs d'identitification de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<form method="POST" enctype="multipart/form-data" class="db-form v3 full" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_identitydocs' ); ?>">
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_files as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_files_orga as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	
	<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si une quatri&egrave;me personne ou une personne morale d&eacute;tient au moins 25% de votre capital, merci de nous le signaler sur support@wedogood.co.", 'yproject' ); ?></em></span>
	<br><br>
	
	<p class="align-left">
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="user-identify-docs-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
	</div>
	
</form>