<?php
class YPOrganisationLib {
	public static function get_list_by_user_id() {
	}
	
	/**
	 * Formulaire de nouvelle organisation
	 */
	public static function submit_new() {
		global $errors_submit_new;
		$errors_submit_new = new WP_Error();
		
		//Vérification que l'on a posté le formulaire
		$action = filter_input(INPUT_POST, 'action');
		if ($action !== 'submit-new-organisation') { 
			return FALSE;
		}
		
		//Vérification que l'utilisateur est connecté
		if (!is_user_logged_in()) {
			$errors_submit_new->add('not-loggedin', __('Vous devez vous connecter.', 'yproject'));
		} else {
			$current_user = wp_get_current_user();
		}
		
		//Vérification de la case à cocher
		if (filter_input(INPUT_POST, 'org_capable', FILTER_VALIDATE_BOOLEAN) !== TRUE) {
			$errors_submit_new->add('not-capable', __('Vous devez cocher la case pour certifier que vous &ecirc;tes en capacit&eacute; de repr&eacute;senter l&apos;organisation.', 'yproject'));
		}
		
		//Vérification de l'adresse e-mail
		$org_email = filter_input(INPUT_POST, 'org_email');
		if (bp_core_validate_email_address($org_email) !== TRUE) {
			$errors_submit_new->add('not-email', __('Cet e-mail n&apos;est pas valide.', 'yproject'));
		}
		
		//On poursuit la procédure
		if (count($errors_submit_new->errors) > 0) {
			return FALSE;
		}
		
		//On commence par créer un utilisateur qui représentera l'organisation
		$org_name = filter_input(INPUT_POST, 'org_name');
		$username = 'org_' . sanitize_title_with_dashes($org_name);
		$password = wp_generate_password();
		$organisation_user_id = wp_create_user($username, $password, $org_email);

		//Si il y a eu une erreur lors de la création de l'utilisateur, on arrête la procédure
		if (isset($organisation_user_id->errors) && count($organisation_user_id->errors) > 0) {
			$errors_submit_new = $organisation_user_id;
			return FALSE;
		}

		//Ajout aux données de l'utilisateur créé
		wp_update_user( array ( 
			'ID' => $organisation_user_id, 
			'first_name' => $org_name,
			'last_name' => $org_name,
			'display_name' => $org_name
		) ) ;
		update_user_meta($organisation_user_id, 'user_type', 'organisation');
		YPOrganisation::set_address_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_address'));
		YPOrganisation::set_nationality_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_nationality'));
		YPOrganisation::set_postal_code_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_postal_code'));
		YPOrganisation::set_city_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_city'));
		YPOrganisation::set_type_by_id($organisation_user_id, 'society');
		YPOrganisation::set_legalform_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_legalform'));
		YPOrganisation::set_capital_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_capital'));
		YPOrganisation::set_idnumber_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_idnumber'));
		YPOrganisation::set_rcs_by_id($organisation_user_id, filter_input(INPUT_POST, 'org_rcs'));

		//Création d'un groupe pour l'organisation
		$new_group_id = groups_create_group( array( 
			'creator_id' => $organisation_user_id,
			'name' => $org_name,
			'description' => $org_name,
			'slug' => groups_check_slug( sanitize_title( esc_attr( $org_name ) ) ), 
			'date_created' => bp_core_current_time(), 
			'enable_forum' => 0,
			'status' => 'private'
		) );
		groups_update_groupmeta( $new_group_id, 'group_type', 'organisation');

		//Ajout de l'utilisateur créé et de l'utilisateur en cours dans le groupe (et on les passe admin)
		groups_accept_invite( $organisation_user_id, $new_group_id);
		$org_group_member = new BP_Groups_Member($organisation_user_id, $new_group_id);
		$org_group_member->promote('admin');
		groups_accept_invite( $current_user->ID, $new_group_id);
		$current_group_member = new BP_Groups_Member($current_user->ID, $new_group_id);
		$current_group_member->promote('admin');
		
		$page_edit_orga = get_page_by_path('editer-une-organisation');
		wp_safe_redirect(get_permalink($page_edit_orga->ID) . '?orga_id=' . $new_group_id);
	}
	
	public static function edit($organisation_obj) {
		global $errors_edit;
		$errors_edit = new WP_Error();
		
		//Vérification que l'on a posté le formulaire
		$action = filter_input(INPUT_POST, 'action');
		if ($action !== 'edit-organisation') { 
			return FALSE;
		}
		
		//Vérification que l'utilisateur est connecté
		if (!is_user_logged_in()) {
			$errors_edit->add('not-loggedin', __('Vous devez vous connecter.', 'yproject'));
		}
		
		//On poursuit la procédure
		if (count($errors_edit->errors) > 0) {
			return FALSE;
		}
		
		$organisation_obj->set_address(filter_input(INPUT_POST, 'org_address'));
		$organisation_obj->set_nationality(filter_input(INPUT_POST, 'org_nationality'));
		$organisation_obj->set_postal_code(filter_input(INPUT_POST, 'org_postal_code'));
		$organisation_obj->set_city(filter_input(INPUT_POST, 'org_city'));
		$organisation_obj->set_legalform(filter_input(INPUT_POST, 'org_legalform'));
		$organisation_obj->set_capital(filter_input(INPUT_POST, 'org_capital'));
		$organisation_obj->set_idnumber(filter_input(INPUT_POST, 'org_idnumber'));
		$organisation_obj->set_rcs(filter_input(INPUT_POST, 'org_rcs'));
	}
}