<?php
function wdg_get_project_investments($camp_id, $include_pending = FALSE) {
	$post_campaign = get_post($camp_id);
	$campaign = atcf_get_campaign( $post_campaign );
	$payments_data = $campaign->payments_data();
	$csv_buffer = "\xEF\xBB\xBF";
	$csv_buffer .= 'Prénom;Nom;e-mail;Investissement;Genre;Date de naissance;Ville de naissance;Nationalité;Adresse;Code postal;Ville;Pays;Téléphone;Compte Facebook';
	$csv_buffer .= PHP_EOL;
	
	$buffer = array(
		'campaign' => $campaign,
		'payments_data' => $payments_data,
		'count_validate_investments' => 0,
		'count_validate_investors' => 0,
		'count_age' => 0,
		'count_average_age' => 0,
		'count_female' => 0,
		'count_invest' => 0,
		'amounts_array' => array(),
		'average_age' => 0,
		'percent_female' => 0,
		'percent_male' => 0,
		'average_invest' => 0,
		'median_invest' => 0,
		'investors_list' => array(),
		'investors_string' => ''
	);
	foreach ( $payments_data as $item ) {
		if (($item['status'] == 'publish' || ($include_pending && $item['status'] == 'pending')) && (isset($item['mangopay_contribution']->IsSucceeded) && $item['mangopay_contribution']->IsSucceeded) && $item['signsquid_status'] == 'Agreed') {
			$invest_user = get_user_by('id', $item['user']);
			$buffer['count_validate_investments']++;
			if (!isset($buffer['investors_list'][$item['user']])) {
				$buffer['count_validate_investors']++;
				$buffer['investors_list'][$item['user']] = $item['user'];
				if ($invest_user->get('user_gender') != "") {
					$buffer['count_age'] += ypcf_get_age($invest_user->get('user_birthday_day'), $invest_user->get('user_birthday_month'), $invest_user->get('user_birthday_year'));
					$buffer['count_average_age'] ++;
				}
				if ($invest_user->get('user_gender') == "female") $buffer['count_female']++;
				if ($buffer['investors_string'] != '') $buffer['investors_string'] .= ', ';
				$buffer['investors_string'] .= bp_core_get_userlink($item['user']);
			}
			$buffer['count_invest'] += $item['amount'];
			$buffer['amounts_array'][] = $item['amount'];
		}
		if ($item['status'] == 'publish') $csv_buffer .= ypcf_csv_investors_add_line($item['user'], $item['amount']);
	}
	
	$filename = $camp_id . '_investors_' . time() . '.csv';
        $filepath = dirname ( __FILE__ ) . '/pdf_files/' . $filename;
	file_put_contents ($filepath, $csv_buffer);
	
	sort($buffer['amounts_array']);
	
	if ($buffer['count_validate_investments'] > 0) {
		$buffer['average_age'] = round($buffer['count_age'] / $buffer['count_average_age'], 1);
		$buffer['percent_female'] = round($buffer['count_female'] / $buffer['count_validate_investors'] * 100);
		$buffer['percent_male'] = 100 - $buffer['percent_female'];
		$buffer['average_invest'] = round($buffer['count_invest'] / $buffer['count_validate_investments'], 2);
		$buffer['median_invest'] = $buffer['amounts_array'][0];
		if ($buffer['count_validate_investments'] > 2) {
			$index = round(($buffer['count_validate_investments'] + 1) / 2) - 1;
			$buffer['median_invest'] = $buffer['amounts_array'][$index];
		}
	}
	
	return $buffer;
}
?>