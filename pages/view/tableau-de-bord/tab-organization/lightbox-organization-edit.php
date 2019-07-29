<?php
/* 
 * Lightbox d'édition d'une organisation
 * 
 */
global $campaign_id, $organization_obj;

if ( isset( $organization_obj ) ) {
	$WDGOrganization = $organization_obj;
	$WDGUser_current = WDGUser::current();
	$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
	$fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
	$fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );
?>
	    
<div class="center margin-height">

	<h3><?php _e( "&Eacute;diter l'organisation portant le projet", 'yproject' ); ?></h3>

	<form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="wdg-forms db-form v3 full center" data-action="save_edit_organization" novalidate>
		
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		<input type="hidden" name="action" value="save_edit_organization">

		<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
		<div class="form-error-general align-left">
			<?php _e( "Certaines erreurs ont bloqu&eacute; l'enregistrement de vos donn&eacute;es :", 'yproject' ); ?><br>
			<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
				- <?php echo $error[ 'text' ]; ?><br>
			<?php endforeach; ?>
			<br><br>
		</div>
		<?php endif; ?>
		<?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
		<div class="form-success-general align-left">
			<?php foreach ( $form_feedback[ 'success' ] as $message ): ?>
				<?php echo $message; ?>
			<?php endforeach; ?>
			<br><br>
		</div>
		<?php endif; ?>

		<?php foreach ( $fields_complete as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<h2><?php _e( "Si&egrave;ge social" ); ?></h2>
		<?php foreach ( $fields_address as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>


		<?php if ( $WDGUser_current->is_admin() ): ?>
		<div class="field admin-theme">
			<label for="org_id_quickbooks"><?php _e( "ID Quickbooks", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_id_quickbooks" value="<?php echo $organization_obj->get_id_quickbooks(); ?>">
				</span>
			</div>
		</div>
		<?php endif; ?>


		<?php
		/**
		 * Informations bancaires
		 */
		?>
		<br><br>
		<h3><?php _e( "Informations bancaires", 'yproject' ); ?></h3>
		<div class="field">
			<label for="org_bankownername"><?php _e( "Nom du propri&eacute;taire du compte", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownername" value="<?php echo $organization_obj->get_bank_owner(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneraddress"><?php _e( "Adresse du compte", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneraddress" value="<?php echo $organization_obj->get_bank_address(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneriban"><?php _e( "IBAN", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneriban" value="<?php echo $organization_obj->get_bank_iban(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankownerbic"><?php _e( "BIC", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownerbic" value="<?php echo $organization_obj->get_bank_bic(); ?>">
				</span>
			</div>
		</div>


		<?php
		/**
		 * Documents
		 */
		function temp_get_lw_doc_msg( $wallet_id, $document_type ) {
			$buffer = '';
			$lw_document_id = new LemonwayDocument( $wallet_id, $document_type );
			if ( $lw_document_id->get_status() == LemonwayDocument::$document_status_accepted ) {
				$buffer = __( "Document valid&eacute; par notre prestataire", 'yproject' );
			} else if ( $lw_document_id->get_status() == LemonwayDocument::$document_status_waiting ) {
				$buffer = __( "Document en cours de validation par notre prestataire", 'yproject' );
			} else if ( $lw_document_id->get_status() > 2 ) {
				$buffer = FALSE;
			}
			return $buffer;
		}
		
		$current_filelist_bank = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_bank);
		$current_file_bank = $current_filelist_bank[0];
		$current_file_bank_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_bank );
		$current_filelist_kbis = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_kbis);
		$current_file_kbis = $current_filelist_kbis[0];
		$current_file_kbis_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_kbis );
		$current_filelist_status = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_status);
		$current_file_status = $current_filelist_status[0];
		$current_file_status_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_status );
		$current_filelist_id = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id);
		$current_file_id = $current_filelist_id[0];
		$current_file_id_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_id );
		$current_filelist_home = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home);
		$current_file_home = $current_filelist_home[0];
		$current_file_home_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_home );
		$current_filelist_capital_allocation = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_capital_allocation );
		$current_file_capital_allocation = $current_filelist_capital_allocation[0];
		$current_file_capital_allocation_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_capital_allocation );
		$current_filelist_id_2 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id_2 );
		$current_file_id_2 = $current_filelist_id_2[0];
		$current_file_id_2_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_id2 );
		$current_filelist_home_2 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home_2 );
		$current_file_home_2 = $current_filelist_home_2[0];
		$current_file_home_2_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_home2 );
		$current_filelist_id_3 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id_3 );
		$current_file_id_3 = $current_filelist_id_3[0];
		$current_file_id_3_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_id3 );
		$current_filelist_home_3 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home_3 );
		$current_file_home_3 = $current_filelist_home_3[0];
		$current_file_home_3_msg = temp_get_lw_doc_msg( $organization_obj->get_lemonway_id(), LemonwayDocument::$document_type_home3 );
		?>
		<br><br>
		<h3><?php _e( "Documents", 'yproject' ); ?></h3>
		<p class="align-left">
			<?php _e( "Afin de lutter contre le blanchiment d'argent, les organisations doivent transmettre des documents d'identification.", 'yproject' ); ?><br />
			<?php _e( "Ces fichiers doivent avoir un poids inf&eacute;rieur &agrave; 4Mo et doivent &ecirc;tre dans l'un des formats suivants :", 'yproject' ); ?> 
			<?php echo implode( ', ', WDGKYCFile::$authorized_format_list ); ?>
		</p>

		
		<div class="field">
			<label for="org_doc_bank"><?php _e( "Scan ou copie d'un RIB *", 'yproject' ); ?></label>
			<div class="align-left">
				<span class="field-value">
					<?php if ( isset($current_file_bank) ): ?>
						<a id="org_doc_bank" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_bank->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_bank->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_bank_msg ) ): ?>
						<?php if ( $current_file_bank_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_bank">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_bank_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_kbis"><?php _e( "K-BIS ou &eacute;quivalent &agrave; un registre du commerce *", 'yproject' ); ?></label>
			<div class="align-left">
				<span class="field-value">
					<?php _e( "Datant de moins de 3 mois", 'yproject' ); ?><br />
					<?php if ( isset($current_file_kbis) ): ?>
						<a id="org_doc_kbis" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_kbis->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_kbis->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_kbis_msg ) ): ?>
						<?php if ( $current_file_kbis_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_kbis">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_kbis_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_status"><?php _e( "Statuts de la soci&eacute;t&eacute;, certifi&eacute;s conformes à l'original par le g&eacute;rant *", 'yproject' ); ?></label>
			<div class="align-left">
				<span class="field-value">
					<?php if ( isset($current_file_status) ): ?>
						<a id="org_doc_status" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_status->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_status->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_status_msg ) ): ?>
						<?php if ( $current_file_status_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_status">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_status_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_id"><?php _e( "Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident *", 'yproject' ); ?></label>
			<div class="align-left">
				<span class="field-value">
					<?php _e( "Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject' ); ?><br />
					<?php _e( "Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject' ); ?><br />
					<?php if ( isset($current_file_id) ): ?>
						<a id="org_doc_id" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_id->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_id_msg ) ): ?>
						<?php if ( $current_file_id_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_id">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_id_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_home"><?php _e( "Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident*", 'yproject' ); ?></label>
			<div class="align-left">
				<span class="field-value">
					<?php _e( "Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject' ); ?><br />
					<?php if ( isset($current_file_home) ): ?>
						<a id="org_doc_home" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_home->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_home_msg ) ): ?>
						<?php if ( $current_file_home_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_home">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_home_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>


		<div class="field align-left">
			<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si la r&eacute;partition du capital n'est pas exprim&eacute;e clairement dans les statuts, merci de nous fournir une attestation avec ces pr&eacute;cisions :", 'yproject' ); ?></em></span><br />
			<label for="org_doc_capital_allocation"><?php _e( "Attestation de r&eacute;partition du capital (facultatif)", 'yproject' ); ?></label>
			<div>
				<span class="field-value">
					<?php if ( isset( $current_file_capital_allocation ) ): ?>
						<a id="org_doc_capital_allocation" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_capital_allocation->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_capital_allocation->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_capital_allocation_msg ) ): ?>
						<?php if ( $current_file_capital_allocation_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_capital_allocation">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_capital_allocation_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>


		<p class="align-left" style="color: #EE0000;">
			<em>--&gt; <?php _e( "Si une deuxi&egrave;me personne physique d&eacute;tient au moins 25% du capital, merci de transmettre ces pi&egrave;ces justificatives :", 'yproject' ); ?></em>
		</p>

		<div class="field align-left">
			<label for="org_doc_id_2"><?php _e( "Justificatif d'identit&eacute; de la deuxi&egrave;me personne (facultatif)", 'yproject' ); ?></label>
			<?php _e( "Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject' ); ?><br>
			<?php _e( "Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject' ); ?><br>
			<div>
				<span class="field-value">
					<?php if ( isset( $current_file_id_2 ) ): ?>
					<a id="org_doc_id_2" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id_2->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_id_2->date_uploaded; ?></a>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_id_2_msg ) ): ?>
						<?php if ( $current_file_id_2_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_id_2">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_id_2_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field align-left">
			<label for="org_doc_home_2"><?php _e( "Justificatif de domicile de la deuxi&egrave;me personne (facultatif)", 'yproject' ); ?></label>
			<?php _e( "Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject' ); ?><br>
			<div>
				<span class="field-value">
					<?php if ( isset( $current_file_home_2 ) ): ?>
					<a id="org_doc_home_2" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home_2->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_home_2->date_uploaded; ?></a>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_home_2_msg ) ): ?>
						<?php if ( $current_file_home_2_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_home_2">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_home_2_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>


		<p class="align-left" style="color: #EE0000;">
			<em>--&gt; <?php _e( "Si une troisi&egrave;me personne physique d&eacute;tient au moins 25% du capital, merci de transmettre ces pi&egrave;ces justificatives :", 'yproject' ); ?></em>
		</p>

		<div class="field align-left">
			<label for="org_doc_id_3"><?php _e( "Justificatif d'identit&eacute; de la troisi&egrave;me personne (facultatif)", 'yproject' ); ?></label>
			<?php _e( "Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject' ); ?><br>
			<?php _e( "Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject' ); ?><br>
			<div>
				<span class="field-value">
					<?php if ( isset( $current_file_id_3 ) ): ?>
					<a id="org_doc_id_3" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id_3->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_id_3->date_uploaded; ?></a>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_id_3_msg ) ): ?>
						<?php if ( $current_file_id_3_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_id_3">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_id_3_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>

		<div class="field align-left">
			<label for="org_doc_home_3"><?php _e( "Justificatif de domicile de la troisi&egrave;me personne (facultatif)", 'yproject' ); ?></label>
			<?php _e( "Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject' ); ?><br>
			<div>
				<span class="field-value">
					<?php if ( isset( $current_file_home_3 ) ): ?>
					<a id="org_doc_home_3" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home_3->get_public_filepath(); ?>"><?php _e( "T&eacute;l&eacute;charger le fichier envoy&eacute; le", 'yproject' ); ?> <?php echo $current_file_home_3->date_uploaded; ?></a>
					<?php endif; ?>
						
					<?php if ( empty( $current_file_home_3_msg ) ): ?>
						<?php if ( $current_file_home_3_msg === FALSE ): ?>
							<div class="wdg-message error"><?php echo _e( "Document refus&eacute;", 'yproject' ); ?></div>
						<?php endif; ?>
						<input type="file" name="org_doc_home_3">
						
					<?php else: ?>
						<div class="wdg-message confirm"><?php echo $current_file_home_3_msg; ?></div>
					<?php endif; ?>
				</span>
			</div>
		</div>


		<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si une quatri&egrave;me personne ou une personne morale d&eacute;tient au moins 25% de votre capital, merci de nous le signaler sur support@wedogood.co.", 'yproject' ); ?></em></span><br />
		
		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div id="organization-details-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
		</div>
	</form>


	<?php if ( $WDGUser_current->is_admin() ): ?>
		<br><br>
		<h3><?php _e( "Lemonway", 'yproject' ); ?></h3>

		<?php $organization_lemonway_authentication_status = $organization_obj->get_lemonway_status(); ?>
		<?php if ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_blocked): ?>
			<?php _e( "Afin de s'authentifier chez notre partenaire Lemonway, les informations suivantes sont n&eacute;cessaires : e-mail, description, num&eacute;ro SIRET. Ainsi que les 5 documents suivis d'une &eacute;toile ci-dessus.", 'yproject' ); ?><br />
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_ready): ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_waiting): ?>
			<?php _e( "L'organisation est en cours d'authentification aupr&egrave;s de notre partenaire.", 'yproject' ); ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_incomplete): ?>
			<?php _e( "L'organisation n'est que partiellement authentifi&eacute;e.", 'yproject' ); ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_obj->is_registered_lemonway_wallet()): ?>
			<?php _e( "L'organisation est bien authentifi&eacute;e aupr&egrave;s de notre partenaire.", 'yproject' ); ?>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_rejected): ?>
			<?php _e( "L'organisation a &eacute;t&eacute; refus&eacute;e par notre partenaire.", 'yproject' ); ?>
		<?php endif; ?>

	<?php endif; ?>


	<?php
	/**
	 * Transferts d'argent
	 */
	?>
	<h2 class="underlined"><?php _e( "Transferts d&apos;argent", 'yproject' ); ?></h2>
	<?php
	$args = array(
		'author'	    => $organization_obj->get_wpref(),
		'post_type'	    => 'withdrawal_order',
		'post_status'   => 'any',
		'orderby'	    => 'post_date',
		'order'			=>  'ASC'
	);
	$transfers = get_posts($args);
	if ($transfers) :
	?>
	<ul class="user_history">
		<?php foreach ( $transfers as $post ) :
			$post = get_post($post);
			$date_lemonway = new DateTime( '2016-08-03' );
			$date_transfer = new DateTime( $post->post_date );
			$post_amount = ($date_transfer > $date_lemonway) ? $post->post_title : $post->post_title / 100;
			if ($post->post_status == 'publish') {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
				<?php
			} else if ($post->post_status == 'draft') {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
				<?php
			} else {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
				<?php
			}
		endforeach; ?>
	</ul>
	<?php else: ?>
		Aucun transfert.
	<?php endif; ?>                      

</div>

<?php 
}