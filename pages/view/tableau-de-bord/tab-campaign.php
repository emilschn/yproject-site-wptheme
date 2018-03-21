<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Campagne", 'yproject' ); ?></h2>

<div class="db-form v3 full center bg-white">
	<form id="projectinfo_form" class="ajax-db-form" data-action="save_project_infos">
		<?php
		DashboardUtility::create_field(array(
			"id"	=> "new_project_name",
			"type"	=> "text",
			"label"	=> "Nom du projet",
			"value"	=> $page_controler->get_campaign()->data->post_title
		));

		if ( $page_controler->can_access_admin() ) {
			DashboardUtility::create_field(array(
				"id"	=> "new_backoffice_summary",
				"type"	=> "editor",
				"label"	=> "Description lors de la création du projet",
				"infobubble"	=> "Ces informations seront traitées de manière confidentielle",
				"value"	=> $page_controler->get_campaign()->backoffice_summary(),
				'admin_theme'	=> true
			));

			DashboardUtility::create_field(array(
				'id'			=> 'new_project_url',
				'type'			=> 'text',
				'label'			=> __( "URL du projet", 'yproject' ),
				'value'			=> $page_controler->get_campaign()->data->post_name,
				'admin_theme'	=> true
			));

			DashboardUtility::create_field(array(
				'id'			=> 'new_is_hidden',
				'type'			=> 'check',
				'label'			=> __( "Masquée du public", 'yproject' ),
				'value'			=> $page_controler->get_campaign()->is_hidden(),
				'admin_theme'	=> true
			));

			DashboardUtility::create_field(array(
				'id'			=> 'new_skip_vote',
				'type'			=> 'check',
				'label'			=> __( "Passer la phase de vote", 'yproject' ),
				'value'			=> $page_controler->get_campaign()->skip_vote(),
				'admin_theme'	=> true,
				"editable"		=> $page_controler->get_campaign()->is_preparing()
			));
		}


		$terms_category = get_terms('download_category', array('slug' => 'categories', 'hide_empty' => false));
		$term_category_id = $terms_category[0]->term_id;
		$terms_activity = get_terms('download_category', array('slug' => 'activities', 'hide_empty' => false));
		$term_activity_id = $terms_activity[0]->term_id;
		$terms_type = get_terms('download_category', array('slug' => 'types', 'hide_empty' => false));
		if ( $terms_type ) {
			$term_type_id = $terms_type[0]->term_id;
		}
		$terms_partners = get_terms('download_category', array('slug' => 'partners', 'hide_empty' => false));
		if ( $terms_partners ) {
			$terms_partners_id = $terms_partners[0]->term_id;
		}
		$terms_tousnosprojets = get_terms('download_category', array('slug' => 'tousnosprojets', 'hide_empty' => false));
		if ( $terms_tousnosprojets ) {
			$terms_tousnosprojets_id = $terms_tousnosprojets[0]->term_id;
		}
		?>

		<div class="field">
			<label for="categories"><?php _e("Cat&eacute;gorie", 'yproject'); ?></label>
			<span class="field field-value" data-type="multicheck" data-id="new_project_categories"><?php
				include_once ABSPATH . 'wp-admin/includes/template.php';
				wp_terms_checklist(
					$page_controler->get_campaign_id(), 
					array(
						'taxonomy' => 'download_category',
						'descendants_and_self' => $term_category_id,
						'checked_ontop' => false
				) );
			?></span>
		</div>

		<div class="field">
			<label for="activities"><?php _e("Secteur d&apos;activit&eacute;", 'yproject'); ?></label>
			<span class="field field-value" data-type="multicheck" data-id="new_project_activities"><?php
				wp_terms_checklist(
					$page_controler->get_campaign_id(),
					array(
						'taxonomy' => 'download_category',
						'descendants_and_self' => $term_activity_id,
						'checked_ontop' => false
				) );
			?></span>
		</div>

		<?php if ( $terms_type ): ?>
		<div class="field">
			<label for="types"><?php _e("Type de projet", 'yproject'); ?></label>
			<span class="field field-value" data-type="multicheck" data-id="new_project_types"><?php
				wp_terms_checklist(
					$page_controler->get_campaign_id(),
					array(
						'taxonomy' => 'download_category',
						'descendants_and_self' => $term_type_id,
						'checked_ontop' => false
				) );
			?></span>
		</div>
		<?php endif; ?>

		<?php if ( $terms_partners ): ?>
		<div class="field">
			<label for="partners"><?php _e("Partenaires", 'yproject'); ?></label>
			<span class="field field-value" data-type="multicheck" data-id="new_project_partners"><?php
				wp_terms_checklist(
					$page_controler->get_campaign_id(),
					array(
						'taxonomy' => 'download_category',
						'descendants_and_self' => $terms_partners_id,
						'checked_ontop' => false
				) );
			?></span>
		</div>
		<?php endif; ?>

		<?php if ( $terms_tousnosprojets ): ?>
		<div class="field">
			<label for="tousnosprojets"><?php _e("Cat&eacute;gorie sur le site tousnosprojets.fr", 'yproject'); ?></label>
			<span class="field field-value" data-type="multicheck" data-id="new_project_tousnosprojets"><?php
				wp_terms_checklist(
					$page_controler->get_campaign_id(),
					array(
						'taxonomy' => 'download_category',
						'descendants_and_self' => $terms_tousnosprojets_id,
						'checked_ontop' => false
				) );
			?></span>
		</div>
		<?php endif; ?>

		<?php
		$locations = atcf_get_locations();
		DashboardUtility::create_field(array(
			"id"			=> "new_project_location",
			"type"			=> "select",
			"label"			=> __( "Localisation", 'yproject' ),
			"value"			=> $page_controler->get_campaign()->location(),
			"options_id"	=> array_keys($locations),
			"options_names"	=> array_values($locations)
		));
		
		DashboardUtility::create_field( array(
			'id'			=> 'new_website',
			'type'			=> 'text',
			'label'			=> "Site web",
			'value'			=> $page_controler->get_campaign()->campaign_external_website(),
			'right_icon'	=> 'link'
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_facebook',
			'type'			=> 'text',
			'label'			=> "Page Facebook",
			'value'			=> $page_controler->get_campaign()->facebook_name(),
			'prefix'		=> 'www.facebook.com/',
			'placeholder'	=> 'PageFacebook',
			'right_icon'	=> 'facebook'
		) );

		DashboardUtility::create_field(array(
			'id'			=> 'new_twitter',
			'type'			=> 'text',
			'label'			=> "Twitter",
			'value'			=> $page_controler->get_campaign()->twitter_name(),
			'prefix'		=> '@',
			'placeholder'	=> 'CompteTwitter',
			'right_icon'	=> 'twitter'
		));
		
		DashboardUtility::create_field(array(
			"id"	=> "new_employees_number",
			"type"	=> "number",
			"label"	=> __( "Nombre d'employ&eacute;s au lancement", 'yproject' ),
			"value"	=> $page_controler->get_campaign()->get_api_data( 'employees_number' )
		));
		
		// Champs personnalisés
		$nb_custom_fields = $page_controler->get_campaign_author()->wp_user->get('wdg-contract-nb-custom-fields');
		if ( $nb_custom_fields > 0 ) {
			for ( $i = 1; $i <= $nb_custom_fields; $i++ ) {
				DashboardUtility::create_field(array(
					"id"	=> 'custom_field_' . $i,
					"type"	=> 'text',
					"label"	=> __( "Champ personnalis&eacute; " , 'yproject') .$i,
					"value"	=> get_post_meta( $page_controler->get_campaign_id(), 'custom_field_' . $i, TRUE)
				));
			}
		}
		?>

		<?php DashboardUtility::create_save_button( 'projectinfo_form' ); ?>
	</form>
			
	<?php if ( $page_controler->can_access_admin() ): ?>
	<div class="field admin-theme">
		<h3>Attention : si vous envoyez un document grâce au formulaire ci-dessous, 
			la page se rafraichira et les modifications qui ne sont pas enregistrées seront perdues.</h3>

		<form action="<?php echo admin_url( 'admin-post.php?action=upload_information_files'); ?>" method="post" id="projectinfo_form" enctype="multipart/form-data">
			<ul class="errors">

			</ul>

			<?php
			$file_name = $page_controler->get_campaign()->backoffice_businessplan();
			if (!empty($file_name)) {
				$file_name_exploded = explode('.', $file_name);
				$ext = $file_name_exploded[count($file_name_exploded) - 1];
				$file_name = home_url() . '/wp-content/plugins/appthemer-crowdfunding/includes/kyc/' . $file_name;
			}
			DashboardUtility::create_field(array(
				"id"				=> "new_backoffice_businessplan",
				"type"				=> "upload",
				"label"				=> "Votre business plan",
				"infobubble"		=> "Ces informations seront traitées de manière confidentielle",
				"value"				=> $file_name,
				"download_label"	=> $page_controler->get_campaign()->data->post_title . " - BP." . $ext
			));

			DashboardUtility::create_save_button( 'projectinfo_form', TRUE, "Enregistrer", "Enregistrement", TRUE );
			?>

			<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
		</form>
	</div>
	<?php endif; ?>
</div>