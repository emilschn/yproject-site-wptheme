<?php
global $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUserBankForm = new WDG_Form_User_Bank( $WDGOrganization->get_wpref(), TRUE );
$fields_hidden = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_hidden );
$fields_iban = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_iban );
$fields_file = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_file );
?>


<h2><?php _e( "Coordonn&eacute;es bancaires de", 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>

<p class="center">
	<?php if ( !$WDGOrganization->can_register_lemonway() ): ?>
		<?php _e( "Pensez &agrave; renseigner les informations de l'organisation pour que notre prestataire puisse valider votre RIB.", 'yproject' ); ?><br><br>
		<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Informations de l'organisation" ); ?></a><br>
		<br>

	<?php endif; ?>

	<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que le RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
	<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
	<br>
</p>

<?php if ( $WDGOrganization->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
	<?php echo $WDGOrganization->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="db-form v3 full" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_bank' ); ?>">
		
	<?php foreach ( $fields_hidden as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_iban as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

	<?php foreach ( $fields_file as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>
	
	<p class="align-left">
		<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
	</p>

	<div id="user-bank-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Enregistrer", 'yproject' ); ?></button>
	</div>
	
</form>