<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$WDGUserBankForm = $page_controler->get_user_bank_form();
$fields_hidden = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_hidden );
$fields_iban = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_iban );
$fields_file = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_file );
$form_feedback = $page_controler->get_user_form_feedback();
?>

<h2><?php _e( 'account.menu.MY_BANK_INFO', 'yproject' ); ?></h2>

<div class="db-form v3 bg-white">
	<?php if ( $WDGUser_displayed->has_saved_card_expiration_date() ): ?>
	<h3><?php _e( 'account.bank.MY_BANK_CARD', 'yproject' ); ?></h3>

	<p class="align-justify">
		<?php _e( 'account.bank.YOUR_BANK_CARD_INFO_ON_LEMON_WAY', 'yproject' ); ?>
		<?php _e( 'account.bank.WEDOGOOD_KEEPS_EXPIRATION_DATE', 'yproject' ); ?><br><br>
	</p>

	<?php $lemonway_registered_cards = $WDGUser_displayed->get_lemonway_registered_cards(); ?>
	<?php if ( !empty( $lemonway_registered_cards ) ): ?>

		<div class="align-justify">
			<strong><?php _e( 'account.bank.MY_REGISTERED_BANK_CARDS', 'yproject' ); ?></strong><br>

			<?php foreach ( $lemonway_registered_cards as $registered_card ): ?>
				<div class="user-registered-card">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( 'account.bank.BANK_CARD', 'yproject' ); ?>" width="120">
					<span>
						<?php echo $registered_card[ 'number' ]; ?><br>
						Exp <?php echo $registered_card[ 'expiration' ]; ?>
					</span>
					<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=remove_user_registered_card' ); ?>" class="db-form v3">
						<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">
						<input type="hidden" name="card_id" value="<?php echo $registered_card[ 'id' ]; ?>">
						<button type="submit" class="button blue"><?php _e( 'account.bank.REMOVE_CARD', 'yproject' ); ?></button>
					</form>
				</div>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<br><br>
	<?php endif; ?>

	<h3><?php _e( 'account.bank.MY_BANK_DETAILS', 'yproject' ); ?></h3>
	<p class="align-justify">
		<?php if ( !$WDGUser_displayed->can_register_lemonway() ): ?>
			<?php _e( 'account.bank.PROVIDE_PERSONAL_INFORMATION', 'yproject' ); ?><br>
			<a href="#parameters" class="button red go-to-tab" data-tab="parameters"><?php _e( 'account.menu.MY_INFO', 'yproject' ); ?></a><br>
			<br>

		<?php endif; ?>

		<?php _e( 'account.bank.BANK_DETAILS_TO_BE_CHECKED', 'yproject' ); ?><br>
		<?php _e( 'account.bank.BANK_DETAILS_WITH_YOUR_NAME', 'yproject' ); ?><br>
		
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
			<?php _e( 'account.bank.VALIDATED_BANK_DETAILS', 'yproject' ); ?>
		</div>

		<?php _e( 'account.bank.VALIDATED_BANK_DETAILS_ARE', 'yproject' ); ?><br>
		<strong><?php _e( 'account.bank.BANK_ACCOUNT_OWNER', 'yproject' );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
		<strong><?php _e( 'account.bank.IBAN', 'yproject' );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
		<strong><?php _e( 'account.bank.BIC', 'yproject' );?></strong><br>
		<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>

		<?php
		$current_filelist_bank = WDGKYCFile::get_list_by_owner_id( $page_controler->get_current_user()->get_wpref(), WDGKYCFile::$owner_user, WDGKYCFile::$type_bank );
		$current_file_bank = $current_filelist_bank[0];
		$bank_file_path = ( empty( $current_file_bank ) ) ? '' : $current_file_bank->get_public_filepath();
		?>
		<?php if ( !empty( $bank_file_path ) ): ?>
		<div class="align-center">
			<a href="<?php echo $bank_file_path; ?>" target="_blank"><?php _e( 'common.PREVIEW', 'yproject' ); ?></a>
		</div>
		<?php endif; ?>

		<br><br>
		<div class="align-center">
			<button id="modify-iban" type="button" class="button blue"><?php _e( 'account.bank.MODIFY_BANK_DETAILS', 'yproject' ); ?></button>
		</div>
		<br><br>

		<div id="form-modify-iban" class="hidden">
			<?php foreach ( $fields_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

			<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
				<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
					<div class="wdg-message error">
						<?php echo $error[ 'text' ]; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php foreach ( $fields_iban as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

			<?php foreach ( $fields_file as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

			<p class="align-left">
				* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
			</p>

			<div id="user-bank-form-buttons">
				<button type="submit" class="button save red"><?php _e( 'common.SAVE', 'yproject' ); ?></button>
			</div>
		</div>

	</form>

<?php
// Si l'IBAN et le document de RIB sont en attente, on affiche juste un résumé
elseif( $page_controler->is_iban_waiting() ): ?>
	<?php
	$current_filelist_bank = WDGKYCFile::get_list_by_owner_id( $page_controler->get_current_user()->get_wpref(), WDGKYCFile::$owner_user, WDGKYCFile::$type_bank );
	$current_file_bank = $current_filelist_bank[0];
	$bank_file_path = ( empty( $current_file_bank ) ) ? '' : $current_file_bank->get_public_filepath();
	?>
	
	<?php if ( !empty( $bank_file_path ) ): ?>
	<div class="wdg-message error">
		<?php _e( 'account.bank.BANK_DETAILS_AWAITING_VALIDATION', 'yproject' ); ?>
	</div>
	<?php else: ?>
	<div class="wdg-message error">
		<?php _e( 'account.bank.BANK_DETAILS_AWAITING_FILE', 'yproject' ); ?>
	</div>
	<?php endif; ?>

	<strong><?php _e( 'account.bank.BANK_ACCOUNT_OWNER', 'yproject' );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
	<strong><?php _e( 'account.bank.IBAN', 'yproject' );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
	<strong><?php _e( 'account.bank.BIC', 'yproject' );?></strong><br>
	<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>

	<?php if ( !empty( $bank_file_path ) ): ?>
		<div class="align-center">
			<a href="<?php echo $bank_file_path; ?>" target="_blank"><?php _e( 'common.PREVIEW', 'yproject' ); ?></a>
		</div>
	<?php else: ?>
		<form method="POST" enctype="multipart/form-data" class="db-form v3 full">
			<?php foreach ( $fields_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

			<?php foreach ( $fields_file as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

			<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
				<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
					<div class="wdg-message error">
						<?php echo $error[ 'text' ]; ?>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<p class="align-left">
				* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
			</p>

			<div id="user-bank-form-buttons">
				<button type="submit" class="button save red"><?php _e( 'common.SAVE', 'yproject' ); ?></button>
			</div>
		</form>
	<?php endif; ?>
	

<?php else: ?>
	<?php if ( $WDGUser_lw_bank_status == WDGUser::$iban_status_disabled ): ?>
		<div class="wdg-message error">
			<?php _e( 'account.bank.BANK_DETAILS_DISABLED', 'yproject' ); ?>
		</div>

	<?php elseif ( $WDGUser_lw_bank_status == WDGUser::$iban_status_rejected ): ?>
		<div class="wdg-message error">
			<?php _e( 'account.bank.BANK_DETAILS_REJECTED', 'yproject' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
		<div class="wdg-message error">
			<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?>
		</div>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full account-form">

		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
			<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
				<div class="wdg-message error">
					<?php echo $error[ 'text' ]; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( empty( $WDGUser_lw_bank_status ) || $WDGUser_lw_bank_status == WDGUser::$iban_status_disabled || $WDGUser_lw_bank_status == WDGUser::$iban_status_rejected ): ?>
			<?php foreach ( $fields_iban as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>

		<?php elseif ( $WDGUser_lw_bank_status == WDGUser::$iban_status_waiting ): ?>
			<?php
			$current_filelist_bank = WDGKYCFile::get_list_by_owner_id( $page_controler->get_current_user()->get_wpref(), WDGKYCFile::$owner_user, WDGKYCFile::$type_bank );
			$current_file_bank = $current_filelist_bank[0];
			$bank_file_path = ( empty( $current_file_bank ) ) ? '' : $current_file_bank->get_public_filepath();
			?>
			<div>
				<?php if ( !empty( $bank_file_path ) ): ?>
				<div class="wdg-message error">
					<?php _e( 'account.bank.BANK_DETAILS_AWAITING_VALIDATION', 'yproject' ); ?>
				</div>
				<?php else: ?>
				<div class="wdg-message error">
					<?php _e( 'account.bank.BANK_DETAILS_AWAITING_FILE', 'yproject' ); ?>
				</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $WDGUser_lw_bank_document_status == LemonwayDocument::$document_status_waiting ): ?>
			<div>
				<div class="wdg-message error">
					<?php _e( 'account.bank.BANK_DETAILS_RIB_AWAITING_VALIDATION', 'yproject' ); ?>
				</div>
			</div>
		<?php else: ?>
			<?php foreach ( $fields_file as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<p class="align-left">
			* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
		</p>
	
		<div id="user-notifications-form-buttons">
		<button type="submit" class="button save red">
			<span class="button-text">
				<?php _e( 'common.SAVE', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.REGISTERING', 'yproject' ); ?>			
			</span>
		</button>
	</div>
	</form>
<?php endif; ?>

</div>