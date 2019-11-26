<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="stat-subtab-justificatifs" class="stat-subtab">
<?php
global $campaign_id, $organization_obj;

if ( isset( $organization_obj ) ) {
	$WDGOrganization = $organization_obj;
	$WDGUser_current = WDGUser::current();
	$WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
	$fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
	$fields_dashboard = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_dashboard );
	$fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );

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
        
        <form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="db-form v3 full center bg-white" data-action="save_edit_organization" novalidate>

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
</div>

<?php
}