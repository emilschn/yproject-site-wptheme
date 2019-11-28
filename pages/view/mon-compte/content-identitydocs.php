<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGUserIdentityDocsForm = $page_controler->get_user_identitydocs_form();
	$fields_hidden = $WDGUserIdentityDocsForm->getFields(WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
?>

<h2><?php _e( "Mes justificatifs d'identit&eacute;", 'yproject' ); ?></h2>

<?php 
	locate_template( array( 'pages/view/common/form-identitydocs.php'  ), true );
?>