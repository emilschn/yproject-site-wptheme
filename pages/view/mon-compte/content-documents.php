<?php
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGUser_displayed = $page_controler->get_current_user();
	$list_current_organizations = $page_controler->get_current_user_organizations();
	$WDGUserTaxExemptionForm = $page_controler->get_user_tax_exemption_form();
	$fields_hidden = $WDGUserTaxExemptionForm->getFields( WDG_Form_User_Tax_Exemption::$field_group_hidden );
	$fields_upload = $WDGUserTaxExemptionForm->getFields( WDG_Form_User_Tax_Exemption::$field_group_upload );
	$fields_create = $WDGUserTaxExemptionForm->getFields( WDG_Form_User_Tax_Exemption::$field_group_create );
?>

<h2><?php _e( 'account.documents.DOCUMENTS_OF', 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( 'account.common.INFORMATION_BELOW_PERSONAL_ACCOUNT', 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
		<?php _e( 'account.documents.IF_INVESTMENT_ORGA', 'yproject' ); ?>
	<?php endif; ?>
</p>


<h3><?php _e( 'account.documents.MY_YEARLY_TRANSACTIONS_CERTIFICATES', 'yproject' ); ?></h3>
<?php
	$has_declaration = false;
	$date_now = new DateTime();
?>
<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
	<?php if ( $WDGUser_displayed->has_royalties_for_year( $year ) ): ?>
		<?php
			$has_declaration = true;
			$declaration_url = $WDGUser_displayed->get_royalties_certificate_per_year( $year );
		?>
		<a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate"><?php _e( "account.documents.DOWNLOAD_CERTIFICATE", 'yproject' ); ?> <?php echo $year; ?></a>
		<br><br>
	<?php endif; ?>
<?php endfor; ?>
<?php if ( !$has_declaration ): ?>
	<?php _e( 'common.NONE.F', 'yproject' ); ?>
	<br>
	<br>
<?php endif; ?>


<?php if ( $page_controler->has_tax_documents() ): ?>
	<h3><?php _e( 'account.documents.MY_CERTIFICATES_IFU', 'yproject' ); ?></h3>
	<?php $tax_documents = $page_controler->get_tax_documents(); ?>
	<?php foreach( $tax_documents as $year => $document_path ): ?>
		<a href="<?php echo $document_path; ?>" download="ifu-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate"><?php _e( "account.documents.DOWNLOAD_CERTIFICATE", 'yproject' ); ?> <?php echo $year; ?></a>
		<br><br>
	<?php endforeach; ?>
<?php endif; ?>



<?php if ( $page_controler->get_can_ask_tax_exemption() ): ?>

	<h3><?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION', 'yproject' ); ?></h3>

	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_1', 'yproject' ); ?>
	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_2', 'yproject' ); ?>
	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_3', 'yproject' ); ?>
	<br><br>

	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_4', 'yproject' ); ?><br>
	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_5', 'yproject' ); ?><br>
	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_6', 'yproject' ); ?><br><br>
		
	<?php _e( 'account.documents.MY_ANNUAL_REQUEST_OF_LEVY_EXEMPTION_TEXT_7', 'yproject' ); ?><br>
	<br>

	<?php
		$date_today = new DateTime();
		$date_start_for_wdg = 2018;
	?>
	<?php for ( $year = $date_start_for_wdg; $year <= $date_today->format( 'Y' ); $year++ ): ?>
		<?php 
			$tax_exemption_filename = $WDGUser_displayed->has_tax_exemption_for_year( $year ); 
			$file_name_exploded = explode('.', $tax_exemption_filename);
			$ext = $file_name_exploded[count($file_name_exploded) - 1];
		?>
		<?php if ( !empty( $tax_exemption_filename ) ): ?>

			<a href="<?php echo $tax_exemption_filename; ?>" download="dispense-prelevement-<?php echo $year; ?>.<?php echo $ext; ?>" class="button blue-pale download-certificate"><?php _e( 'account.documents.DOWNLOAD_EXEMPTION_FILE', 'yproject' ); ?> <?php echo $year; ?></a>
			<br><br>
		<?php endif; ?>
	<?php endfor; ?>

	<?php if ( $page_controler->get_show_user_tax_exemption_form() ): ?>
		<br><br>
		<div class="db-form v3 full">
			<button id="display-tax-exemption-form" class="button blue"><?php _e( 'account.documents.ASK_ANNUAL_EXEMPTION', 'yproject' ); ?></button>
			<br><br><?php _e( 'common.OR', 'yproject' ); ?><br><br>
			<button id="display-upload-tax-exemption-form" class="button blue"><?php _e( 'account.documents.SEND_ANNUAL_EXEMPTION', 'yproject' ); ?></button>
			<br><br>
		</div>

		<form method="post" id="tax-exemption-form" class="db-form v3 full enlarge hidden">
			
			<div id="tax-exemption-preview">
				<?php echo $page_controler->get_tax_exemption_preview(); ?>
			</div>

			<?php foreach ( $fields_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<?php foreach ( $fields_create as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<button type="button" class="button transparent half left"><?php _e( 'common.CANCEL', 'yproject' ); ?></button>
			<button type="submit" class="button red half right"><?php _e( 'account.documents.SAVE_ANNUAL_EXEMPTION', 'yproject' ); ?></button>
			<div class="clear"></div>
		</form>

		<form method="post" id="upload-tax-exemption-form" class="db-form v3 full enlarge hidden" enctype="multipart/form-data">

			<?php foreach ( $fields_hidden as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<?php foreach ( $fields_upload as $field ): ?>
				<?php global $wdg_current_field; $wdg_current_field = $field; ?>
				<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
			<?php endforeach; ?>

			<button type="button" class="button transparent half left"><?php _e( 'common.CANCEL', 'yproject' ); ?></button>
			<button type="submit" class="button red half right"><?php _e( 'common.SAVE', 'yproject' ); ?></button>
			<div class="clear"></div>
		</form>
	<?php endif; ?>

	<br>

<?php endif; ?>