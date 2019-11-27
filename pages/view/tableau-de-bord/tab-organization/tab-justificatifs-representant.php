<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $campaign_id, $organization_obj;


if ( isset( $organization_obj ) ) {
	$WDGOrganization = $organization_obj;
	$WDGUser_current = WDGUser::current();
//	$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
    $WDGUserIdentityDocsForm = new WDG_Form_User_Identity_Docs( $WDGUser_current->get_wpref() );
    $fields_hidden = $WDGUserIdentityDocsForm->getFields(WDG_Form_User_Identity_Docs::$field_group_hidden );
    $fields_files = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );

	//$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
   // $fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );



?>

<div id="stat-subtab-justificatifs-representant" class="stat-subtab">
    <form method="POST" enctype="multipart/form-data" class="db-form v3 full">
        
        <p class="align-justify">
            <?php _e( "Afin d'authentifier votre compte, Lemon Way (prestataire de services de paiement agr&eacute;&eacute;) a besoin de deux documents justificatifs d'identit&eacute;.", 'yproject' ); ?>
            <?php _e( "Ces documents sont imm&eacute;diatement transmis, puis v&eacute;rifi&eacute;s sous 48h par Lemon Way. Ils sont d'abord analys&eacute;s par des services automatiques puis par une personne physique en cas d'erreur ou de cas particulier.", 'yproject' ); ?><br>
            <?php _e( "En cas d'erreur manifeste de l'analyse de vos documents, vous pouvez nous contacter &agrave; l'adresse investir@wedogood.co ou sur le chat en ligne.", 'yproject' ); ?><br><br>
        </p>
            
        <?php foreach ( $fields_hidden as $field ): ?>
            <?php global $wdg_current_field; $wdg_current_field = $field; ?>
            <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
        <?php endforeach; ?>

        <?php foreach ( $fields_files as $field ): ?>
            <?php global $wdg_current_field; $wdg_current_field = $field; ?>
            <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
        <?php endforeach; ?>
        
        <p class="align-left">
            <?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
        </p>
        
        <div id="user-identify-docs-form-buttons">
            <button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
        </div>
        
    </form>
</div>
<?php
}