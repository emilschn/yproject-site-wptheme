<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUserBankForm = $page_controler->get_user_bank_form();
$fields_hidden = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_hidden );
$fields_iban = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_iban );
$fields_file = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_file );
?>

<h2 class="underlined"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></h2>

<p class="center">
	<?php if ( !$WDGUser_displayed->can_register_lemonway() ): ?>
		<?php _e( "Pensez &agrave; renseigner vos informations personnelles pour que notre prestataire puisse valider votre RIB.", 'yproject' ); ?><br>
		<a href="#parameters" class="button red go-to-tab" data-tab="parameters"><?php _e( "Mes informations personnelles" ); ?></a><br>
		<br>

	<?php endif; ?>

	<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que votre RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
	<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
	<br>
</p>

<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
	<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="db-form v3 full">
		
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

	<div id="user-bank-form-buttons">
		<button type="submit" class="button save red"><?php _e( "Enregistrer", 'yproject' ); ?></button>
	</div>
	
</form>