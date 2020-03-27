<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUserBankForm = $page_controler->get_user_bank_form();
$fields_hidden = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_hidden );
$fields_iban = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_iban );
$fields_file = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_file );
?>

<h2><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></h2>

<div class="db-form v3">
	<?php if ( $WDGUser_displayed->has_saved_card_expiration_date() ): ?>
	<h3><?php _e( "Ma carte bancaire", 'yproject' ); ?></h3>

	<p class="align-justify">
		<?php _e( "Les informations de vos cartes bancaires sont stock&eacute;es par Lemon Way, prestataire de service de paiement agr&eacute;&eacute;.", 'yproject' ); ?>
		<?php _e( "WE DO GOOD ne stocke que la date d'expiration afin de vous pr&eacute;venir quand la date approche.", 'yproject' ); ?><br><br>
	</p>

	<?php $lemonway_registered_cards = $WDGUser_displayed->get_lemonway_registered_cards(); ?>
	<?php if ( !empty( $lemonway_registered_cards ) ): ?>

		<div class="align-justify">
			<strong><?php _e( "Mes cartes bancaires enregistr&eacute;es", 'yproject' ); ?></strong><br>

			<?php foreach ( $lemonway_registered_cards as $registered_card ): ?>
				<div class="user-registered-card">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
					<span>
						<?php echo $registered_card[ 'number' ]; ?><br>
						Exp <?php echo $registered_card[ 'expiration' ]; ?>
					</span>
					<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=remove_user_registered_card' ); ?>" class="db-form v3">
						<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">
						<input type="hidden" name="card_id" value="<?php echo $registered_card[ 'id' ]; ?>">
						<button type="submit" class="button blue"><?php _e( "Supprimer", 'yproject' ); ?></button>
					</form>
				</div>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<br><br>
	<?php endif; ?>

	<h3><?php _e( "Mon relev&eacute; d'identit&eacute; bancaire", 'yproject' ); ?></h3>
	<p class="align-justify">
		<?php if ( !$WDGUser_displayed->can_register_lemonway() ): ?>
			<?php _e( "Pensez &agrave; renseigner vos informations personnelles pour que notre prestataire puisse valider votre RIB.", 'yproject' ); ?><br>
			<a href="#parameters" class="button red go-to-tab" data-tab="parameters"><?php _e( "Mes informations personnelles" ); ?></a><br>
			<br>

		<?php endif; ?>

		<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que votre RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
		<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
		<?php _e( "Si votre compte bancaire est un compte en ligne (Ex : Compte Nickel), notre prestataire vous demandera une deuxi&egrave;me pi&egrave;ce d'identit&eacute; pour le valider. Il sera &agrave; transmettre dans l'onglet Mes justificatifs d'identit&eacute;.", 'yproject' ); ?><br>
		
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
	<form method="POST" enctype="multipart/form-data" class="db-form v3 full">
		<div class="wdg-message confirm">
			<?php _e( "Coordonn&eacute;es bancaires valid&eacute;es", 'yproject' ); ?>
		</div>

		<?php _e( "Le RIB valid&eacute; est le suivant :", 'yproject' ); ?><br>
		<strong><?php _e( "Propri&eacute;taire du compte :" );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
		<strong><?php _e( "IBAN :" );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
		<strong><?php _e( "BIC :" );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>

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
			<button id="modify-iban" type="button" class="button blue"><?php _e( "Modifier mon RIB", 'yproject' ); ?></button>
		</div>
		<br><br>

		<div id="form-modify-iban" class="hidden">
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
		</div>

	</form>

<?php
// Si l'IBAN et le document de RIB sont en attente, on affiche juste un résumé
elseif( $page_controler->is_iban_waiting() ): ?>
	<div class="wdg-message error">
		<?php _e( "Coordonn&eacute;es bancaires en attente de validation", 'yproject' ); ?>
	</div>

	<strong><?php _e( "Propri&eacute;taire du compte :" );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
	<strong><?php _e( "IBAN :" );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
	<strong><?php _e( "BIC :" );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>

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
		<div class="wdg-message error">
			<?php _e( "Coordonn&eacute;es bancaires d&eacute;sactiv&eacute;es", 'yproject' ); ?>
		</div>

	<?php elseif ( $WDGUser_lw_bank_status == WDGUser::$iban_status_rejected ): ?>
		<div class="wdg-message error">
			<?php _e( "Coordonn&eacute;es bancaires refus&eacute;es", 'yproject' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
		<div class="wdg-message error">
			<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?>
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
			<div>
				<div class="wdg-message error">
					<?php _e( "Coordonn&eacute;es bancaires en attente de validation", 'yproject' ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $WDGUser_lw_bank_document_status == LemonwayDocument::$document_status_waiting ): ?>
			<div>
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

</div>