<?php
class YPOrganisationLib {
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
		/*$org_email = filter_input(INPUT_POST, 'org_email');
		if (bp_core_validate_email_address($org_email) !== TRUE) {
			$errors_submit_new->add('not-email', __('Cet e-mail n&apos;est pas valide.', 'yproject'));
		}*/
		
		//Vérification du code postal
		$org_postal_code = filter_input(INPUT_POST, 'org_postal_code');
		if (substr($org_postal_code, 0, 1) === '0') { $org_postal_code = substr($org_postal_code, 1); }
		$org_postal_code = filter_var($org_postal_code, FILTER_VALIDATE_INT);
		if ($org_postal_code === FALSE) {
			$errors_submit_new->add('postalcode-not-integer', __('Le code postal doit &ecirc;tre un nombre entier.', 'yproject'));
		} else {
			if (strlen($org_postal_code) === 4) { $org_postal_code = '0' . $org_postal_code; }
		}
		
		//Vérification du capital
		$org_capital = filter_input(INPUT_POST, 'org_capital', FILTER_VALIDATE_INT);
		if ($org_capital === FALSE) {
			$errors_submit_new->add('capital-not-integer', __('Le capital doit &ecirc;tre un nombre entier.', 'yproject'));
		}
		
		//Vérification des données obligatoires
		$necessary_fields = array('org_name', 'org_address', 'org_city', 'org_nationality', 'org_legalform', 'org_idnumber', 'org_ape');
		$necessary_fields_full = TRUE;
		foreach ($necessary_fields as $field) {
			$value = filter_input(INPUT_POST, $field);
			if (empty($value)) {
				$necessary_fields_full = FALSE;
			}
		}
		if (!$necessary_fields_full) {
			$errors_submit_new->add('empty-fields', __('Certains champs obligatoires sont vides. Veuillez les renseigner.', 'yproject'));
		}
		
		//On poursuit la procédure
		if (count($errors_submit_new->errors) > 0) {
			return FALSE;
		}
		
		//Création de l'objet organisation
		global $current_user;
		$org_object = new YPOrganisation();
		$org_object->set_strong_authentication(FALSE);
		$org_object->set_name(filter_input(INPUT_POST, 'org_name'));
		$org_object->set_address(filter_input(INPUT_POST, 'org_address'));
		$org_object->set_postal_code($org_postal_code);
		$org_object->set_city(filter_input(INPUT_POST, 'org_city'));
		$org_object->set_nationality(filter_input(INPUT_POST, 'org_nationality'));
		$org_object->set_type('society');
		$org_object->set_legalform(filter_input(INPUT_POST, 'org_legalform'));
		$org_object->set_capital($org_capital);
		$org_object->set_idnumber(filter_input(INPUT_POST, 'org_idnumber'));
		$org_object->set_rcs('---');
		$org_object->set_ape(filter_input(INPUT_POST, 'org_ape'));
		$org_object->set_bank_owner(filter_input(INPUT_POST, 'org_bankownername'));
		$org_object->set_bank_address(filter_input(INPUT_POST, 'org_bankowneraddress'));
		$org_object->set_bank_iban(filter_input(INPUT_POST, 'org_bankowneriban'));
		$org_object->set_bank_bic(filter_input(INPUT_POST, 'org_bankownerbic'));
		$wp_orga_user_id = $org_object->create();
		
		if ($wp_orga_user_id !== FALSE) {
			$org_object->set_creator($current_user->ID);
			if (session_id() == '') session_start();
			if (isset($_SESSION['redirect_current_invest_type']) && $_SESSION['redirect_current_invest_type'] == 'new_organisation') {
				$_SESSION['redirect_current_invest_type'] = $wp_orga_user_id;
				wp_redirect(ypcf_login_gobackinvest_url());
				exit();
				
			} else {
				wp_safe_redirect(bp_loggedin_user_domain() . '#community');
				exit();
			}
		}
	}
	
	public static function edit($org_object) {
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
		
		$org_object->set_address(filter_input(INPUT_POST, 'org_address'));
		$org_object->set_nationality(filter_input(INPUT_POST, 'org_nationality'));
		$org_object->set_postal_code(filter_input(INPUT_POST, 'org_postal_code'));
		$org_object->set_city(filter_input(INPUT_POST, 'org_city'));
		$org_object->set_legalform(filter_input(INPUT_POST, 'org_legalform'));
		$org_object->set_capital(filter_input(INPUT_POST, 'org_capital'));
		$org_object->set_idnumber(filter_input(INPUT_POST, 'org_idnumber'));
//		$org_object->set_rcs(filter_input(INPUT_POST, 'org_rcs'));
		$org_object->set_ape(filter_input(INPUT_POST, 'org_ape'));
		$org_object->set_bank_owner(filter_input(INPUT_POST, 'org_bankownername'));
		$org_object->set_bank_address(filter_input(INPUT_POST, 'org_bankowneraddress'));
		$org_object->set_bank_iban(filter_input(INPUT_POST, 'org_bankowneriban'));
		$org_object->set_bank_bic(filter_input(INPUT_POST, 'org_bankownerbic'));
		
		$wp_organisation_user = get_user_by('id', $org_object->get_wpref());
		$url_request = ypcf_init_mangopay_user_strongauthentification($wp_organisation_user);
		$curl_result_cni = (isset($_FILES['org_file_cni']['tmp_name'])) ? ypcf_mangopay_send_strong_authentication($url_request, 'org_file_cni') : false;
		$curl_result_status = (isset($_FILES['org_file_status']['tmp_name'])) ? ypcf_mangopay_send_strong_authentication($url_request, 'org_file_status') : false;
		$curl_result_extract = (isset($_FILES['org_file_extract']['tmp_name'])) ? ypcf_mangopay_send_strong_authentication($url_request, 'org_file_extract') : false;
		if (isset($_FILES['org_file_declaration']['tmp_name'])) ypcf_mangopay_send_strong_authentication($url_request, 'org_file_declaration');
		if ($curl_result_cni && $curl_result_status && $curl_result_extract) ypcf_mangopay_set_user_strong_authentication_doc_transmitted($wp_organisation_user->ID);
		else {
			if ($curl_result_cni || $curl_result_status || $curl_result_extract) {
				$errors_edit->add('strongauthentication-incomplete', __('Les 3 fichiers d&apos;identification obligatoires doivent &ecirc;tre envoy&eacute;s en m&ecirc;me temps.', 'yproject'));
			}
		}
	}
}