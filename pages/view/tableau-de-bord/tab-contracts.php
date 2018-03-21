<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Contrats", 'yproject' ); ?></h2>

<div class="db-form v3 full center bg-white">
	
	<?php if ( $page_controler->can_access_admin() ): ?>
	<form action="<?php echo admin_url( 'admin-post.php?action=generate_contract_files'); ?>" method="post" id="contract_files_generate_form" class="field admin-theme">
		/!\ <?php _e( "Si vous choisissez de g&eacute;n&eacute;rer les contrats, cela remplacera les fichiers précédents :", 'yproject' ); ?> /!\
		<br /><br />
		<div class="align-center">
			<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
			<button class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer le contrat vierge", 'yproject' ); ?></button>
		</div>
	</form>

	<h3>Attention : si vous envoyez un document grâce au formulaire ci-dessous, 
		la page se rafraichira et les modifications qui ne sont pas enregistrées seront perdues.</h3>
	<?php endif; ?>

	<form action="<?php echo admin_url( 'admin-post.php?action=upload_contract_files'); ?>" method="post" id="contract_files_form" enctype="multipart/form-data">
		<ul class="errors">

		</ul>

		<?php
		$file_name_contract_orga = $page_controler->get_campaign()->backoffice_contract_orga();
		if (!empty($file_name_contract_orga)) {
			$file_name_exploded = explode('.', $file_name_contract_orga);
			$ext = $file_name_exploded[count($file_name_exploded) - 1];
			$file_name_contract_orga = home_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/contracts/' . $file_name_contract_orga;
		}
		DashboardUtility::create_field(array(
			"id"				=> "new_backoffice_contract_orga",
			"type"				=> "upload",
			"label"				=> "Contrat d'investissement",
			"value"				=> $file_name_contract_orga,
			"editable"			=> $page_controler->can_access_admin(),
			"download_label"	=> $page_controler->get_campaign()->data->post_title . " - Contrat royalties." . $ext
		));
		
		DashboardUtility::create_field(array(
			"id"			=> "new_project_contract_earnings_description",
			"type"			=> "editor",
			"label"			=> __( "Description des revenus", 'yproject' ),
			"value"			=> $page_controler->get_campaign()->contract_earnings_description(),
			'admin_theme'	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_project_contract_simple_info",
			"type"			=> "editor",
			"label"			=> __( "Informations simples", 'yproject' ),
			"value"			=> $page_controler->get_campaign()->contract_simple_info(),
			'admin_theme'	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_project_contract_detailed_info",
			"type"			=> "editor",
			"label"			=> __( "Informations d&eacute;taill&eacute;es", 'yproject' ),
			"value"			=> $page_controler->get_campaign()->contract_detailed_info(),
			'admin_theme'	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_contract_premium",
			"type"			=> "editor",
			"label"			=> "Prime",
			"value"			=> $page_controler->get_campaign()->contract_premium(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_contract_warranty",
			"type"			=> "editor",
			"label"			=> "Garantie",
			"value"			=> $page_controler->get_campaign()->contract_warranty(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_contract_budget_type",
			"type"			=> "select",
			"label"			=> "Budget &eacute;gal au",
			"value"			=> $page_controler->get_campaign()->contract_budget_type(),
			"options_id"	=> array_keys( ATCF_Campaign::$contract_budget_types ),
			"options_names"	=> array_values( ATCF_Campaign::$contract_budget_types ),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));
		?>
		<?php if ( $page_controler->can_access_admin() ): ?>
		<div class="field admin-theme">
			<?php echo _e( "Si le budget est égal au montant collecté, le prévisionnel sera exprimé en pourcentage du budget.", 'yproject' ); ?>
			<br /><br />
		</div>
		<?php endif; ?>

		<?php
		DashboardUtility::create_field(array(
			"id"			=> "new_contract_maximum_type",
			"type"			=> "select",
			"label"			=> "Plafond",
			"value"			=> $page_controler->get_campaign()->contract_maximum_type(),
			"options_id"	=> array_keys( ATCF_Campaign::$contract_maximum_types ),
			"options_names"	=> array_values( ATCF_Campaign::$contract_maximum_types ),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));
		?>
		<?php if ( $page_controler->can_access_admin() ): ?>
		<div class="field admin-theme">
			<?php echo _e( "Si infini, le budget est égal au montant collecté.", 'yproject' ); ?>
			<br /><br />
		</div>
		<?php endif; ?>


		<?php
		DashboardUtility::create_field(array(
			"id"			=> "new_quarter_earnings_estimation_type",
			"type"			=> "select",
			"label"			=> "Estimation de revenus trimestriels",
			"value"			=> $page_controler->get_campaign()->quarter_earnings_estimation_type(),
			"options_id"	=> array_keys( ATCF_Campaign::$quarter_earnings_estimation_types ),
			"options_names"	=> array_values( ATCF_Campaign::$quarter_earnings_estimation_types ),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin()
		));


		if ( $page_controler->can_access_admin() ) {

			DashboardUtility::create_field(array(
				"id"			=> "new_override_contract",
				"type"			=> "editor",
				"label"			=> "Surcharger le contrat standard",
				"infobubble"	=> "Le contrat ne sera pas surcharg&eacute; si ce champ reste vide.",
				"value"			=> $page_controler->get_campaign()->override_contract(),
				"admin_theme"	=> true
			));

			DashboardUtility::create_save_button( 'projectinfo_form', TRUE, "Enregistrer", "Enregistrement", TRUE );
		}
		?>

		<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
	</form>

	<?php if ( $page_controler->can_access_admin() ): ?>
	<form id="contract_modification_form" class="ajax-db-form" data-action="save_project_contract_modification">
		<?php
		DashboardUtility::create_field( array(
			'id'			=> 'new_contract_modification',
			'type'			=> 'editor',
			'label'			=> __( "Modifications sur le contrat entre vote et campagne", 'yproject' ),
			'value'			=> $page_controler->get_campaign()->contract_modifications(),
			'admin_theme'	=> true
		) );
		?>

		<?php DashboardUtility::create_save_button( 'contract_modification_form', TRUE, "Enregistrer", "Enregistrement", TRUE ); ?>
	</form>
	<?php endif; ?>

	<?php
	$pending_preinvestements = $page_controler->get_campaign()->pending_preinvestments();
	$count_pending_preinvestements = count( $pending_preinvestements );
	?>
	<?php if ( $page_controler->can_access_admin() && $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_collecte ): ?>
		<div class="field admin-theme">
			<?php if ( $count_pending_preinvestements > 0 ): ?>
			Il y a des pré-investissements non-validés.<br><br>
			<form action="<?php echo admin_url( 'admin-post.php?action=send_project_contract_modification_notification'); ?>" method="post" class="align-center">
				<button type="submit" class="button red"><?php _e( "Envoyer les notifications de modification de contrat" ); ?></button>
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
			</form>
			<?php else: ?>
			Il n'y a pas de pré-investissement en attente.
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php
	$can_add_contract = $page_controler->can_access_admin() && 
		( 
			$page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_collecte
			|| $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded
			|| $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed
			|| $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_archive
		);
	?>	
	<?php if ( $page_controler->can_access_admin() && $can_add_contract ): ?>
		<br><br>
		<div class="field admin-theme">
			<strong><?php _e( "Contrats investisseurs compl&eacute;mentaires :" ); ?></strong>
			<?php $contract_models = WDGWPREST_Entity_Project::get_contract_models( $page_controler->get_campaign()->get_api_id() ); ?>
			<?php if ( $contract_models ): ?>
				<?php
				$status_to_text = array(
					'draft' => __( "Brouillon", 'yproject' ),
					'sent' => __( "Envoy&eacute;", 'yproject' )
				);
				?>
				<ul>
				<?php foreach ( $contract_models as $contract_model ): ?>
					<li>
						<?php echo $contract_model->model_name; ?> (<?php echo $status_to_text[ $contract_model->status ]; ?>)
						<?php if ( $contract_model->status != 'sent' ): ?>
							<a href="<?php echo admin_url( 'admin-post.php?action=send_contract_model&model=' . $contract_model->id ); ?>" class="button admin-theme alert-confirm" data-alertconfirm="<?php _e( "Ceci enverra le contrat &agrave; chacun des investisseurs", 'yproject' ); ?>"><?php _e( "Faire signer", 'yproject' ); ?></a>
							<button type="button" class="button admin-theme edit-contract-model" data-modelid="<?php echo $contract_model->id; ?>" data-modelname="<?php echo urlencode( $contract_model->model_name ); ?>" data-modelcontent="<?php echo urlencode( $contract_model->model_content ); ?>"><?php _e( "Editer", 'yproject' ); ?></button>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
				</ul>
				<br>
			<?php else: ?>
				<?php _e( "Aucun", 'yproject' ); ?>
			<?php endif; ?>

			<div class="align-center">
				<button id="button-show-form-add-contract-model" type="button" class="button admin-theme"><?php _e( "Ajouter", 'yproject' ); ?></button>
			</div>

			<form id="form-add-contract-model" method="POST" action="<?php echo admin_url( 'admin-post.php?action=add_contract_model' ); ?>" class="db-form v3 full hidden">

				<hr>

				<strong><?php _e( "Nouveau contrat compl&eacute;mentaire :", 'yproject' ); ?></strong><br><br>
				<div class="field">
					<label><?php _e( "Titre (sera repris sur Signsquid)", 'yproject' ); ?></label>
					<div class="field-container">
						<span class="field-value"><input type="text" name="contract_model_name" /></span>
					</div>
				</div>
				<div class="field">
					<label><?php _e( "Contenu", 'yproject' ); ?></label>
					<div class="field-container">
						<?php
						wp_editor( '', 'contract_model_content',
							array(
								'media_buttons' => true,
								'quicktags' => false,
								'tinymce' => array(
									'plugins' => 'wordpress, paste, wplink, textcolor',
									'paste_remove_styles' => true
								)
							)
						);
						?>
					</div>
				</div>

				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>">
				<button type="submit" class="button admin-theme"><?php _e( "Ajouter", 'yproject' ); ?></button>

			</form>

			<form id="form-edit-contract-model" method="POST" action="<?php echo admin_url( 'admin-post.php?action=edit_contract_model' ); ?>" class="db-form v3 full hidden">

				<hr>

				<strong><?php _e( "Edition du contrat compl&eacute;mentaire :", 'yproject' ); ?></strong><br><br>
				<div class="field">
					<label><?php _e( "Titre (sera repris sur Signsquid)", 'yproject' ); ?></label>
					<div class="field-container">
						<span class="field-value"><input type="text" name="contract_edit_model_name" /></span>
					</div>
				</div>
				<div class="field">
					<label><?php _e( "Contenu", 'yproject' ); ?></label>
					<div class="field-container">
						<?php
						wp_editor( '', 'contract_edit_model_content',
							array(
								'media_buttons' => true,
								'quicktags' => false,
								'tinymce' => array(
									'plugins' => 'wordpress, paste, wplink, textcolor',
									'paste_remove_styles' => true
								)
							)
						);
						?>
					</div>
				</div>

				<input type="hidden" name="contract_edit_model_id" value="">
				<button type="submit" class="button admin-theme"><?php _e( "Enregistrer", 'yproject' ); ?></button>

			</form>
		</div>
	<?php endif; ?>



	<br><br><br>
	<?php
	$mandate_conditions = $page_controler->get_campaign()->mandate_conditions();

	$saved_mandates_list = $page_controler->get_campaign_organization()->get_lemonway_mandates();
	$last_mandate_status = '';
	$last_mandate_id = FALSE;
	if ( !empty( $saved_mandates_list ) ) {
		$last_mandate = end( $saved_mandates_list );
		$last_mandate_status = $last_mandate[ "S" ];
		$last_mandate_id = $last_mandate[ "ID" ];
	}
	?>
	<h3><?php _e('Autorisation de pr&eacute;l&egrave;vement', 'yproject'); ?></h3>

	<?php if ( $last_mandate_status != 5 && $last_mandate_status != 6 ): ?>
		<?php if ( $page_controler->can_access_admin() ): ?>
			<form action="" id="forcemandate_form" class="ajax-db-form" data-action="save_project_force_mandate">
				<?php DashboardUtility::create_field( array(
					"id"			=> "new_force_mandate",
					"type"			=> "select",
					"label"			=> __( "Forcer l'entrepreneur &agrave; signer l'autorisation de pr&eacute;l&egrave;vement ?", 'yproject' ),
					"value"			=> $page_controler->get_campaign()->is_forced_mandate(),
					"editable"		=> $page_controler->can_access_admin(),
					"admin_theme"	=> $page_controler->can_access_admin(),
					"visible"		=> $page_controler->can_access_admin(),
					"options_id"	=> array( 0, 1 ),
					"options_names"	=> array( 
						__( "Non", 'yproject' ),
						__( "Oui", 'yproject' )
					)
				) ); ?>

				<?php DashboardUtility::create_field(array(
					"id"			=> "new_mandate_conditions",
					"type"			=> "editor",
					"label"			=> __( "Conditions contractuelles", 'yproject' ),
					"value"			=> $mandate_conditions,
					"editable"		=> $page_controler->can_access_admin(),
					"admin_theme"	=> $page_controler->can_access_admin(),
					"visible"		=> $page_controler->can_access_admin(),
				)); ?>

				<?php DashboardUtility::create_save_button( "forcemandate-form", $page_controler->can_access_admin(), "Enregistrer", "Enregistrement", TRUE ); ?>
			</form>

		<?php elseif ( !empty( $mandate_conditions ) ) : ?>

			<strong><?php _e( "Conditions contractuelles pour la signature du mandat de pr&eacute;l&egrave;vement", 'yproject' ) ?></strong><br />
			<?php echo $mandate_conditions; ?><br /><br />

		<?php endif; ?>
	<?php endif; ?>


	<?php 
	//Si il n'y a pas de RIB enregistré, demander d'éditer l'organisation
	//TODO : permettre l'édition du RIB directement ici
	$keep_going = true;
	?>
	<?php if ( !$page_controler->get_campaign_organization()->has_saved_iban() ): ?>
		<?php $keep_going = false; ?>
		<?php _e( "Afin de signer votre autorisation de pr&eacute;l&egrave;vement, vous devez au pr&eacute;alable renseigner le RIB de l'organisation.", 'yproject' ); ?><br />
		<p class="align-center"><a class="button red switch-tab" href="#organization"><?php _e('Editer', 'yproject'); ?></a></p><br /><br />
		<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>

	<?php endif; ?>

	<?php
	//Si il y a un RIB enregistré
	?>
	<?php if ( $keep_going ): ?>
		<?php
		//Récupérer la liste des mandats liés au wallet de l'organisation
		//Si il n'y en a pas : enregistrer un mandat lié
		?>
		<?php
		$page_controler->get_campaign_organization()->register_lemonway();
		if ( empty( $saved_mandates_list ) ) {
			$keep_going = false;
			if ( !$page_controler->get_campaign_organization()->add_lemonway_mandate() ) {
				echo LemonwayLib::get_last_error_message(); ?>
				<a class="button red switch-tab" href="#organization"><?php _e('Editer', 'yproject'); ?></a><br /><br />
				<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
				<?php
			} else {
				_e( "Cr&eacute;ation de mandat en cours", 'yproject' );
			}
		}
		?>
	<?php endif; ?>

	<?php if ( $keep_going ): ?>
		<?php
		//Récupérer le dernier de la liste, vérifier le statut
		/**
		 * 0 	non validé
		 * 5 	utilisable avec prélèvement effectif dans un délai de 6 jours ouvrés bancaire
		 * 6 	utilisable avec prélèvement effectif dans un délai de 3 jours ouvrés bancaire
		 * 8 	désactivé
		 * 9 	rejeté
		 */
		?>
		<?php if ( $last_mandate_status == 0 ): //Si 0, proposer de signer ?>
			<?php $phone_number = $WDGUser_current->wp_user->get('user_mobile_phone'); ?>

			<?php 
			//Indication pour rappeler qu'ils se sont engagés dans le contrat à autoriser les prélévements automatiques
			?>
			<?php if ( $page_controler->get_campaign()->is_forced_mandate() ): ?>
				<?php _e( "Selon votre contrat, vous vous &ecirc;tes engag&eacute; &agrave; signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />
			<?php endif; ?>


			<?php if ( empty( $phone_number ) ): ?>
				<?php _e( "Afin de signer l'autorisation de pr&eacute;l&eacute;vement automatique, merci de renseigner votre num&eacute;ro de t&eacute;l&eacute;phone mobile dans votre compte utilisateur.", 'yproject' ); ?><br /><br />

			<?php elseif ( !$page_controler->get_campaign_organization()->is_registered_lemonway_wallet() ): ?>
				<?php _e( "L'organisation doit &ecirc;tre authentifi&eacute;e par notre prestataire de paiement afin de pouvoir signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />

			<?php else: ?>
			<form action="<?php echo admin_url( 'admin-post.php?action=organization_sign_mandate'); ?>" method="post" class="align-center">
				<input type="hidden" name="organization_id" value="<?php echo $page_controler->get_campaign_organization()->get_wpref(); ?>" />
				<button type="submit" class="button red"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
			</form>
			<?php endif; ?>

		<?php elseif ( $last_mandate_status == 5 || $last_mandate_status == 6 ): //Si 5 ou 6, afficher que OK ?>
			<?php _e( "Merci d'avoir signé l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?>


			<?php if ( $page_controler->can_access_admin() ): ?>
				<br /><br />
				<form class="ajax-db-form" data-action="pay_with_mandate">
					<div class="field admin-theme">

						<?php
						DashboardUtility::create_field(array(
							'id'			=> 'pay_with_mandate_amount_for_organization',
							'type'			=> 'text',
							'label'			=> "Montant vers&eacute; sur le porte-monnaie de l'organisation",
							'suffix'		=> " &euro;",
							"admin_theme"	=> true
						));
						?>
						<br />

						<?php
						DashboardUtility::create_field(array(
							'id'			=> 'pay_with_mandate_amount_for_commission',
							'type'			=> 'text',
							'label'			=> "Montant vers&eacute; en commission",
							'suffix'		=> " &euro;",
							"admin_theme"	=> true
						));
						?>
						<br />

						<?php
						DashboardUtility::create_field( array(
							'id'			=> 'organization_id',
							'type'			=> 'hidden',
							'value'			=> $page_controler->get_campaign_organization()->get_wpref()
						) );
						?>

						<?php DashboardUtility::create_save_button( "pay_with_mandate" ); ?>

					</div>
				</form>

				<br /><br />
				<form action="<?php echo admin_url( 'admin-post.php?action=organization_remove_mandate'); ?>" method="post">
					<div class="field admin-theme">

						<?php
						DashboardUtility::create_field( array(
							'id'			=> 'organization_id',
							'type'			=> 'hidden',
							'value'			=> $page_controler->get_campaign_organization()->get_wpref()
						) );
						?>

						<?php
						DashboardUtility::create_field( array(
							'id'			=> 'mandate_id',
							'type'			=> 'hidden',
							'value'			=> $last_mandate_id
						) );
						?>

						<?php DashboardUtility::create_save_button( "pay_with_mandate", TRUE, "Annuler le mandat en cours" ); ?>

					</div>
				</form>
			<?php endif; ?>



		<?php elseif ( $last_mandate_status == 8 ): //Si 8, demander de nous contacter ?>
			<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; d&eacute;sactiv&eacute;e. Merci de nous contacter.", 'yproject' ); ?>

		<?php elseif ( $last_mandate_status == 9 ): //Si 9, demander de nous contacter ?>
			<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; rejet&eacute;e. Merci de nous contacter.", 'yproject' ); ?>

		<?php endif; ?>
	<?php endif; ?>		

	
</div>