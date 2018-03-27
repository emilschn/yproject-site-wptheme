<?php
global $declaration;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>

	<?php if ( $declaration->get_adjustment_needed() ): ?>
	<form method="POST" enctype="multipart/form-data" class="form-adjustment-file">
		<h4><?php _e( "Ajustement", 'yproject' ); ?></h4>
		Tous les ans (ou plus régulièrement), transmettez-nous votre bilan comptable certifié conforme,
		vos déclarations fiscales de chiffre d'affaires ou l’attestation de votre expert-comptable
		afin de valider vos déclarations et procéder à d'éventuels ajustements !

		<?php $declaration_file_list = $declaration->get_file_list(); ?>
		<?php if ( empty( $declaration_file_list ) ): ?>
			<br><br>
			<?php _e( "Aucun fichier pour l'instant", 'yproject' ); ?><br>

		<?php else: ?>
			<ul>
				<?php $files_path = $declaration->get_file_path(); ?>
				<?php $i = 0; foreach ($declaration_file_list as $declaration_file): $i++; ?>
					<li>
						<?php echo html_entity_decode( $declaration_file->text ); ?><br /><br />
						<a href="<?php echo $files_path.$declaration_file->file; ?>" target="_blank" class="button blue"><?php _e( "T&eacute;l&eacute;charger le fichier", 'yproject' ); ?></a>
						<br /><br />
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<strong>Ajouter un nouveau fichier</strong><br />
		<input type="file" name="accounts_file_<?php echo $declaration->id; ?>" /><br />
		<textarea name="info_file_<?php echo $declaration->id; ?>"></textarea>

		<strong>Attention : si vous envoyez un document grâce au formulaire ci-dessus, 
			la page se rafraichira et les modifications qui ne sont pas enregistrées seront perdues.</strong><br /><br />

		<input type="hidden" name="declaration-id" value="<?php echo $declaration->id; ?>" />
		<button type="submit" class="button red">Envoyer le document</button><br /><br />
	</form>
	<?php endif; ?>

	<?php if ( $page_controler->can_access_admin() ): ?>
	<form id="form-declaration-adjustment" class="ajax-db-form" data-action="save_declaration_adjustment"
			data-roi-percent="<?php echo $page_controler->get_campaign()->roi_percent(); ?>"
			data-costs-orga="<?php echo $page_controler->get_campaign()->get_costs_to_organization(); ?>">
		<div class="field admin-theme">

			<?php
			DashboardUtility::create_field(array(
				'id'			=> 'new_declaration_adjustment_needed',
				'type'			=> 'select',
				'label'			=> "Ajustement obligatoire",
				'value'			=> $declaration->get_adjustment_needed(),
				'options_id'	=> array( 0, 1 ),
				'options_names'	=> array( __( "Non", 'yproject' ), __( "Oui", 'yproject' ) )
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'			=> 'new_declaration_adjustment_validated',
				'type'			=> 'select',
				'label'			=> "Statut ajustement",
				'value'			=> $declaration->get_adjustment_validated(),
				'options_id'	=> array( 0, 1 ),
				'options_names'	=> array( __( "En attente", 'yproject' ), __( "Effectu&eacute;", 'yproject' ) )
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'		=> 'new_declaration_adjustment_turnover_difference',
				'type'		=> 'text',
				'label'		=> "Diff&eacute;rentiel de CA",
				'value'		=> $declaration->get_adjustment_turnover_difference(),
				'suffix'	=> " &euro;"
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'		=> 'new_declaration_adjustment_value',
				'type'		=> 'text',
				'label'		=> "Somme &agrave; verser",
				'value'		=> $declaration->get_adjustment_value(),
				'suffix'	=> " &euro;"
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'		=> 'new_declaration_adjustment_message_author',
				'type'		=> 'textarea',
				'label'		=> "Message pour l'entrepreneur :",
				'value'		=> html_entity_decode( $declaration->get_adjustment_message( 'author' ) )
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'		=> 'new_declaration_adjustment_message_investors',
				'type'		=> 'textarea',
				'label'		=> "Message pour les investisseurs :",
				'value'		=> html_entity_decode( $declaration->get_adjustment_message( 'investors' ) )
			));
			?>
			<br />

			<?php
			DashboardUtility::create_field(array(
				'id'		=> 'declaration_id',
				'type'		=> 'hidden',
				'value'		=> $declaration->id
			));
			?>
			<?php DashboardUtility::create_save_button( "save_declaration_adjustment" ); ?>

		</div>
	</form>
	<?php endif; ?>

<hr />

<?php endif; ?>