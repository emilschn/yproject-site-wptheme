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

<?php
$WDGUser_lw_bank_info = $page_controler->get_current_user_iban();
$WDGUser_lw_bank_status = $page_controler->get_current_user_iban_status();
$WDGUser_lw_bank_document_status = $page_controler->get_current_user_iban_document_status();
?>

<?php
// Si l'IBAN et le document de RIB sont validés, on affiche le résumé et le bouton qui permet de le modifier
if ( $page_controler->is_iban_validated() ): ?>
	<div class="center">
		<div class="wdg-message confirm">
			<?php _e( "Coordonn&eacute;es bancaires valid&eacute;es", 'yproject' ); ?>
		</div>
	</div>

	<?php print_r( $WDGUser_lw_bank_info ); ?>

	<?php
	$current_filelist_bank = WDGKYCFile::get_list_by_owner_id( $page_controler->get_current_user()->get_wpref(), WDGKYCFile::$owner_user, WDGKYCFile::$type_bank );
	$current_file_bank = $current_filelist_bank[0];
	$bank_file_path = ( empty( $current_file_bank ) ) ? '' : $current_file_bank->get_public_filepath();
	?>
	<div class="align-center">
		<a href="<?php echo $bank_file_path; ?>" target="_blank"><?php _e( "Aper&ccedil;u", 'yproject' ); ?></a>
	</div>

	<br><br>
	<div class="align-center">
		<button id="modify-iban" class="button blue"><?php _e( "Modifier mon RIB", 'yproject' ); ?></button>
	</div>
	<br><br>

	<form method="POST" enctype="multipart/form-data" id="form-modify-iban" class="db-form v3 full hidden">

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

<?php
// Si l'IBAN et le document de RIB sont en attente, on affiche juste un résumé
elseif( $page_controler->is_iban_waiting() ): ?>
	<div class="center">
		<div class="wdg-message error">
			<?php _e( "Coordonn&eacute;es bancaires en attente de validation", 'yproject' ); ?>
		</div>
	</div>

	<?php print_r( $WDGUser_lw_bank_info ); ?>

	<?php
	$current_filelist_bank = WDGKYCFile::get_list_by_owner_id( $page_controler->get_current_user()->get_wpref(), WDGKYCFile::$owner_user, WDGKYCFile::$type_bank );
	$current_file_bank = $current_filelist_bank[0];
	$bank_file_path = ( empty( $current_file_bank ) ) ? '' : $current_file_bank->get_public_filepath();
	?>
	<div class="align-center">
		<a href="<?php echo $bank_file_path; ?>" target="_blank"><?php _e( "Aper&ccedil;u", 'yproject' ); ?></a>
	</div>
	

<?php else: ?>
	<?php if ( $WDGUser_lw_bank_status == WDGUser::$iban_status_disabled ): ?>
		<div class="center">
			<div class="wdg-message error">
				<?php _e( "Coordonn&eacute;es bancaires d&eacute;sactiv&eacute;es", 'yproject' ); ?>
			</div>
		</div>
	<?php elseif ( $WDGUser_lw_bank_status == WDGUser::$iban_status_rejected ): ?>
		<div class="center">
			<div class="wdg-message error">
				<?php _e( "Coordonn&eacute;es bancaires refus&eacute;es", 'yproject' ); ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
		<div class="center">
			<div class="wdg-message error">
				<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?>
			</div>
		</div>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full">

		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php if ( empty( $WDGUser_lw_bank_status ) || $WDGUser_lw_bank_status == WDGUser::$iban_status_disabled || $WDGUser_lw_bank_status == WDGUser::$iban_status_rejected ): ?>
			<?php foreach ( $fields_iban as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		<?php elseif ( $WDGUser_lw_bank_status == WDGUser::$iban_status_waiting ): ?>
			<div class="center">
				<div class="wdg-message error">
					<?php _e( "Coordonn&eacute;es bancaires en attente de validation", 'yproject' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $WDGUser_lw_bank_document_status == LemonwayDocument::$document_status_waiting ): ?>
			<div class="center">
				<div class="wdg-message error">
					<?php _e( "RIB en attente de validation", 'yproject' ); ?>
				</div>
			</div>
		<?php else: ?>
			<?php foreach ( $fields_file as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>
	
		<div id="user-bank-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer", 'yproject' ); ?></button>
		</div>

	</form>
<?php endif; ?>