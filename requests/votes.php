<?php
function wdg_get_project_vote_results($camp_id) {
	if (!isset($camp_id)) return;
	global $wpdb;
	$table_name = $wpdb->prefix . "ypcf_project_votes";
	
	$post_camp = get_post($camp_id);
	$campaign = atcf_get_campaign( $post_camp );
	$campaign_id =  $campaign->ID;

	$buffer = array(
		'count_voters' => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id ),
		'average_impact_economy' => 0,
		'average_impact_environment' => 0,
		'average_impact_social' => 0,
		'list_impact_others_string' => '',
		'count_project_validated' => 0,
		'percent_project_validated' => 0,
		'percent_project_not_validated' => 0,
		'count_invest_ready' => 0,
		'sum_invest_ready' => 0,
		'average_invest_ready' => 0,
		'median_invest_ready' => 0,
		'show_risk' => ($campaign->funding_type() != 'fundingdonation'),
		'average_risk' => 0,
		'risk_list' => array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0),
		'count_more_info_impact' => 0,
		'count_more_info_service' => 0,
		'count_more_info_team' => 0,
		'count_more_info_finance' => 0,
		'count_more_info_other' => 0,
		'string_more_info_other' => '',
                'objective' => $campaign->minimum_goal(),
		'list_advice' => array(),
                'list_date' => array(),
                'list_cumul_pos' => array(),
                'list_cumul_neg' => array(),
                'list_evo_pos' => array(),
                'list_evo_neg' => array(),
                
	);
	
	if ($buffer['count_voters'] > 0) {
		$buffer['total_impact_economy'] = $wpdb->get_var( "SELECT sum(impact_economy) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$buffer['average_impact_economy'] = $buffer['total_impact_economy'] / $buffer['count_voters'];
		$buffer['total_impact_environment'] = $wpdb->get_var( "SELECT sum(impact_environment) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$buffer['average_impact_environment'] = $buffer['total_impact_environment'] / $buffer['count_voters'];
		$buffer['total_impact_social'] = $wpdb->get_var( "SELECT sum(impact_social) FROM ".$table_name." WHERE post_id = ".$campaign_id );
		$buffer['average_impact_social'] = $buffer['total_impact_social'] / $buffer['count_voters'];
		$buffer['list_impact_others'] = $wpdb->get_results( "SELECT impact_other FROM ".$table_name." WHERE post_id = ".$campaign_id." AND impact_other <> ''" );
		foreach ($buffer['list_impact_others'] as $impact_others) { 
			if ($buffer['list_impact_others_string'] != '') $buffer['list_impact_others_string'] .= ', ';
			$buffer['list_impact_others_string'] .= html_entity_decode($impact_others->impact_other, ENT_QUOTES | ENT_HTML401);
		}


		$buffer['count_project_validated'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
		$buffer['percent_project_validated'] = round($buffer['count_project_validated'] / $buffer['count_voters'], 2) * 100;
		$buffer['percent_project_not_validated'] = round(($buffer['count_voters'] - $buffer['count_project_validated']) / $buffer['count_voters'], 2) * 100;

		$buffer['count_invest_ready'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0" );
		if ($buffer['count_invest_ready'] > 0) {
		    $buffer['sum_invest_ready'] = $wpdb->get_var( "SELECT sum(invest_sum) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0" );
		    $buffer['average_invest_ready'] = $buffer['sum_invest_ready'] / $buffer['count_invest_ready'];
		    if ($buffer['count_invest_ready'] == 1) {
			$buffer['median_invest_ready'] = $buffer['average_invest_ready'];
		    } else {
			$median = 0;
			if ($buffer['count_invest_ready'] > 2) $median = round(($buffer['count_invest_ready'] + 1) / 2);
			$buffer['median_invest_ready'] = $wpdb->get_var( "SELECT invest_sum FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_sum > 0 ORDER BY `invest_sum` LIMIT ".$median.", 1" );
		    }
		}

		$buffer['count_risk'] = $wpdb->get_var( "SELECT sum(invest_risk) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
		$buffer['average_risk'] = $buffer['count_risk'] / $buffer['count_project_validated'];

		$buffer['risk_list'] = array(
		    1 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 1" ),
		    2 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 2" ),
		    3 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 3" ),
		    4 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 4" ),
		    5 => $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1 AND invest_risk = 5" ),
		);

		$buffer['count_more_info_impact'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_impact = 1" );
		$buffer['count_more_info_service'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_service = 1" );
		$buffer['count_more_info_team'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_team = 1" );
		$buffer['count_more_info_finance'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_finance = 1" );
		$buffer['count_more_info_other'] = $wpdb->get_var( "SELECT count(user_id) FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_other <> ''" );
		$buffer['more_info_other'] = $wpdb->get_results( "SELECT more_info_other FROM ".$table_name." WHERE post_id = ".$campaign_id." AND more_info_other <> ''" );
		foreach ($buffer['more_info_other'] as $more_info_other_item) { 
			if ($buffer['string_more_info_other'] != '') $buffer['string_more_info_other'] .= ', ';
			$buffer['string_more_info_other'] .= html_entity_decode($more_info_other_item->more_info_other, ENT_QUOTES | ENT_HTML401);
		}

		$buffer['list_advice'] = $wpdb->get_results( "SELECT user_id, advice FROM ".$table_name." WHERE post_id = ".$campaign_id." AND advice <> ''" );
                
                $dates_votes = $wpdb->get_results( "SELECT validate_project, date FROM ".$table_name." WHERE post_id = ".$campaign_id." ORDER BY `date` ASC" );
                
                //Parcours des votes par date :
                foreach ( $dates_votes as $vote ) {
                    if (end($buffer['list_date']) != $vote->date){
                        //Si on est sur un nouveau jour
                        $buffer['list_date'][]= $vote->date;
                        $buffer['list_evo_pos'][]=0;
                        $buffer['list_evo_neg'][]=0;
                        
                        if(end($buffer['list_cumul_pos'])===false){
                            $buffer['list_cumul_pos'][]=0;
                            $buffer['list_cumul_neg'][]=0;

                        } else {
                            $buffer['list_cumul_pos'][]=end($buffer['list_cumul_pos']);
                            $buffer['list_cumul_neg'][]=end($buffer['list_cumul_neg']);
                        }
                    }
                    
                    if ($vote->validate_project==1){
                        $buffer['list_cumul_pos'][count($buffer['list_cumul_pos'])-1]++;
                        $buffer['list_evo_pos'][count($buffer['list_evo_pos'])-1]++;
                    } else {
                        $buffer['list_cumul_neg'][count($buffer['list_cumul_neg'])-1]++;
                        $buffer['list_evo_neg'][count($buffer['list_evo_neg'])-1]++;
                    }
                }
	}
	
	return $buffer;
}
?>