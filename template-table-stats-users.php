<?php 
/**
 * Template Name: Tableau des statistiques utilisateurs
 * 
 */ 
if ( !current_user_can('manage_options') ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
}
global $disable_logs;
$disable_logs = TRUE;
$number = 1000;
$offset = (isset($_GET['offset'])) ? $_GET['offset'] * $number : 0;

get_header();
global $stylesheet_directory_uri, $wpdb;
$table_jcrois = $wpdb->prefix . "jycrois";
$table_vote = $wpdb->prefix . WDGCampaignVotes::$table_name_votes;
$input_poll = filter_input( INPUT_GET, 'poll' );
$input_official_data = filter_input( INPUT_GET, 'official_data' );
$input_declarations_list = filter_input( INPUT_GET, 'declarations_list' );
$input_user_stats = filter_input( INPUT_GET, 'user_stats' );
?>

<div id="content">
    <div class="padder">
		<br><br><br><br><br>
		
		<?php if ( $input_declarations_list == '1' ): ?>
			Liste des déclarations demandées et état des ajustements :<br>
			<?php
			$input_declarations_year = filter_input( INPUT_GET, 'declarations_year' );
			$input_declarations_month = filter_input( INPUT_GET, 'declarations_month' );
			if ( !empty( $input_declarations_month ) && !empty( $input_declarations_year ) ):
				$date_start = new DateTime();
				$date_start->setDate( $input_declarations_year, $input_declarations_month, 1 );
				$date_end = new DateTime();
				$date_end->setDate( $input_declarations_year, $input_declarations_month, 28 );
				$declarations_list = WDGWPREST_Entity_Declaration::get_list_by_date( $date_start->format( 'Y-m-d' ), $date_end->format( 'Y-m-d' ) );
				if ( $declarations_list ):
					foreach ( $declarations_list as $declaration_data ):
						$roi_declaration = new WDGROIDeclaration( $declaration_data->id, $declaration_data );
						$roi_declaration_file_list = $roi_declaration->get_file_list();
						$files_path = $roi_declaration->get_file_path();
						?>
						- <strong><?php echo $declaration_data->name_project; ?> :</strong>
							<?php if ( empty( $roi_declaration_file_list ) ): ?>
							Aucun fichier transmis
							<?php else: ?>
							Fichiers transmis :
							<ul>
							<?php foreach ($declaration_file_list as $declaration_file): ?>
								<li><a href="<?php echo $files_path.$declaration_file->file; ?>" target="_blank"><?php echo html_entity_decode( $declaration_file->text ); ?></a></li>
							<?php endforeach; ?>
							</ul>
							<?php endif; ?>
							<br><br>
						<?php
					endforeach;
				endif;
			endif;
			?>
		
		<?php elseif ( $input_official_data == '1' ): ?>
			<?php
			$count = 0;
			$count_1_50 = 0;
			$count_51_100 = 0;
			$count_101_250 = 0;
			$count_251_1000 = 0;
			$count_1000 = 0;
			$count_invest_by_user_in_france = 0;

			$amount_total = 0;
			$amount_out_of_euro = 0;

			$today = new DateTime();
			$payments = edd_get_payments( array(
				'number'	=> -1,
				'status'	=> 'publish',
				'year'		=> $today->format( 'Y' ) - 1
			) );
			if ( $payments ) {
				foreach ( $payments as $payment ) {
					$count++;
					$amount = edd_get_payment_amount( $payment->ID );
					$amount_total += $amount;
					if ( $amount < 51 ) {
						$count_1_50++;
					} elseif ( $amount < 101 ) {
						$count_51_100++;
					} elseif ( $amount < 251 ) {
						$count_101_250++;
					} elseif ( $amount < 1001 ) {
						$count_251_1000++;
					} else {
						$count_1000++;
					}

					$user_info = edd_get_payment_meta_user_info( $payment->ID );
					$user_id = (isset( $user_info['id'] ) && $user_info['id'] != -1) ? $user_info['id'] : $user_info['email'];
					if ( !WDGOrganization::is_user_organization( $user_id ) ) {
						$WDGUser = new WDGUser( $user_id );
						$country_iso_code = $WDGUser->get_country( 'iso2' );
						if ( $country_iso_code == 'FR' ) {
							$count_invest_by_user_in_france++;
						}
						$euro_list = array( 'DE', 'AT', 'BE', 'BG', 'CY', 'HR', 'DK', 'ES', 'EE', 'FI', 'FR', 'GR', 'HU', 'GR', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'CZ', 'RO', 'GB', 'SK', 'SI', 'SE' );
						if ( !in_array( $country_iso_code, $euro_list ) ) {
							$amount_out_of_euro += $amount;
						}
					}
				}
			}
			?>
			Investissements totaux : <?php echo $count; ?><br>
			Investissements inf 51 € : <?php echo $count_1_50; ?> (<?php echo round( $count_1_50 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 51 et 100 € : <?php echo $count_51_100; ?> (<?php echo round( $count_51_100 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 101 et 250 € : <?php echo $count_101_250; ?> (<?php echo round( $count_101_250 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 251 et 1000 € : <?php echo $count_251_1000; ?> (<?php echo round( $count_251_1000 / $count * 100, 2 ); ?> %)<br>
			Investissements plus de 1000 € : <?php echo $count_1000; ?> (<?php echo round( $count_1000 / $count * 100, 2 ); ?> %)<br>
			Investissements par pers. phys. en France : <?php echo $count_invest_by_user_in_france; ?> (<?php echo round( $count_invest_by_user_in_france / $count * 100, 2 ); ?> %)<br>
			Montants totaux : <?php echo $amount_total; ?> €<br>
			Montants dont provenance hors UE : <?php echo $amount_out_of_euro; ?> € (<?php echo round( $amount_out_of_euro / $amount_total * 100, 2 ); ?> %)<br>
		
		<?php elseif ( $input_poll == 'warranty' ): ?>
		<?php $poll_answers = WDGWPREST_Entity_PollAnswer::get_list( FALSE, FALSE, $input_poll ); ?>
		<h1>Résultats sondage garantie</h1>
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Investirait montant différent</td>
						<td>Investirait montant</td>
						<td>Investirait sur d'autres projets</td>
						<td>Investirait nombre</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Investirait montant différent</td>
						<td>Investirait montant</td>
						<td>Investirait sur d'autres projets</td>
						<td>Investirait nombre</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php foreach ( $poll_answers as $answer ): ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-amount' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-amount-with-warranty' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-number' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-number-per-year-with-warranty' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
		
		
		<?php elseif ( $input_poll == 'source' ): ?>
		<?php $poll_answers = WDGWPREST_Entity_PollAnswer::get_list( FALSE, FALSE, $input_poll ); ?>
		<h1>Résultats sondage source</h1>
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Connait le PP</td>
						<td>Intéret secteur</td>
						<td>Diversifier</td>
						<td>Impact</td>
						<td>Autre</td>
						<td>Autre (txt)</td>
						<td>Connu par</td>
						<td>Autre (txt)</td>
						<td>Venu via</td>
						<td>Autre (txt)</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Connait le PP</td>
						<td>Intéret secteur</td>
						<td>Diversifier</td>
						<td>Impact</td>
						<td>Autre</td>
						<td>Autre (txt)</td>
						<td>Connu par</td>
						<td>Autre (txt)</td>
						<td>Venu via</td>
						<td>Autre (txt)</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php foreach ( $poll_answers as $answer ): ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'know-project-manager' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'interrested-by-domain' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'diversify-savings' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'looking-for-positive-impact' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'other-motivations' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $answers_decoded->{ 'other-motivations-to-invest' }; ?></td>
						<td><?php echo $answers_decoded->{ 'how-the-fundraising-was-known' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-to-know-the-fundraising' }; ?></td>
						<td><?php echo $answers_decoded->{ 'where-user-come-from' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-where-the-user-come-from' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
		




		


		<?php elseif ( $input_user_stats == 'users' ): ?>
			<?php
			$users = get_users();
			$count_users = count( $users );

			$users_female = get_users( array(
				'meta_key'		=> 'user_gender',
				'meta_value'	=> 'female'
			) );
			$count_users_female = count( $users_female );

			$users_male = get_users( array(
				'meta_key'		=> 'user_gender',
				'meta_value'	=> 'male'
			) );
			$count_users_male = count( $users_male );

			$users_orga = get_users( array(
				'meta_query'	=> array( array(
					'key'		=> 'organisation_bopp_id',
					'compare'	=> 'EXISTS'
				) )
			) );
			$count_users_orga = count( $users_orga );

			//todo : répartition par département

			$results_age = $wpdb->get_results( "SELECT AVG(meta_value) as avg_year FROM ".$wpdb->usermeta." WHERE meta_key = 'user_birthday_year'" );
			$avg_year = round( $results_age[ 0 ]->avg_year, 2 );
			$this_year = date( 'Y' );
			?>
			<b>Membres</b><br>
			Nb comptes utilisateurs : <?php echo $count_users; ?><br>
			Nb femme : <?php echo $count_users_female; ?><br>
			% femme : <?php echo round( $count_users_female / $count_users * 100, 2 ); ?><br>
			Nb Homme : <?php echo $count_users_male; ?><br>
			% Homme : <?php echo round( $count_users_male / $count_users * 100, 2 ); ?><br>
			Nb Orga : <?php echo $count_users_orga; ?><br>
			% Orga : <?php echo round( $count_users_orga / $count_users * 100, 2 ); ?><br>
			Date de naissance moyenne : <?php echo $avg_year; ?><br>
			Age moyen approximatif : <?php echo ( $this_year - $avg_year ); ?> ans<br>
		



		<?php elseif ( $input_user_stats == 'investments' ): ?>
			<?php
			$count_total_investments = 0;
			$amount_total_investments = 0;
			$array_amount_investments = array();
			$project_list_funded = ATCF_Campaign::get_list_funded( WDG_Cache_Plugin::$nb_query_campaign_funded, '', true, false );
			foreach ( $project_list_funded as $project_post ) {
				$campaign = atcf_get_campaign( $project_post->ID );
				$payments_data = $campaign->payments_data();
				foreach ( $payments_data as $payment_data ) {
					if ( $payment_data[ 'status' ] == 'publish' ) {
						$count_total_investments++;
						$amount_total_investments += $payment_data[ 'amount' ];
						array_push( $array_amount_investments, $payment_data[ 'amount' ] );
					}
				}
			}
			sort( $array_amount_investments );
			$index_median = round( ( $count_total_investments + 1 ) / 2 ) - 1;
			?>
			<b>Investissements globaux</b><br>
			Nb investissements : <?php echo $count_total_investments; ?><br>
			Montant investissements : <?php echo $amount_total_investments; ?><br>
			Moyenne investissements : <?php echo round( $amount_total_investments / $count_total_investments, 2 ); ?><br>
			Médiane investissements : <?php echo $array_amount_investments[ $index_median ]; ?><br>




		<?php elseif ( $input_user_stats == 'investors' ): ?>
			<?php
			$this_year = date( 'Y' );
			$count_total_investments = 0;
			$amount_total_investments = 0;
			$array_investors = array();

			$global_stats = array(
				'count_female'	=> 0,
				'amount_female'	=> 0,
				'count_male'	=> 0,
				'amount_male'	=> 0,
				'count_orga'	=> 0,
				'amount_orga'	=> 0,
				'count_age_inf25'	=> 0,
				'amount_age_inf25'	=> 0,
				'count_age_2534'	=> 0,
				'amount_age_2534'	=> 0,
				'count_age_3549'	=> 0,
				'amount_age_3549'	=> 0,
				'count_age_5064'	=> 0,
				'amount_age_5064'	=> 0,
				'count_age_sup64'	=> 0,
				'amount_age_sup64'	=> 0,
			);
			$total_real_person_with_birthday_year = 0;
			$total_birthday_year = 0;

			$project_list_funded = ATCF_Campaign::get_list_funded( WDG_Cache_Plugin::$nb_query_campaign_funded, '', true, false );
			foreach ( $project_list_funded as $project_post ) {
				$campaign = atcf_get_campaign( $project_post->ID );
				$payments_data = $campaign->payments_data();
				foreach ( $payments_data as $payment_data ) {
					if ( $payment_data[ 'status' ] == 'publish' ) {

						// init
						$user_id = $payment_data[ 'user' ];
						if ( !isset( $array_investors[ $user_id ] ) ) {
							$array_investors[ $user_id ] = array();
							$array_investors[ $user_id ][ 'amount_count' ] = 0;
							$array_investors[ $user_id ][ 'amount_total' ] = 0;
							$array_investors[ $user_id ][ 'type' ] = '';
							if ( WDGOrganization::is_user_organization( $user_id ) ) {
								$array_investors[ $user_id ][ 'type' ] = 'orga';
							} else {
								$user_gender = get_user_meta( $user_id, 'user_gender', TRUE );
								if ( $user_gender == 'male' ) {
									$array_investors[ $user_id ][ 'type' ] = 'male';

								} else if ( $user_gender == 'female' )  {
									$array_investors[ $user_id ][ 'type' ] = 'female';
								}

								$birthday_year = get_user_meta( $user_id, 'user_birthday_year', TRUE );
								if ( !empty( $birthday_year ) ) {
									$total_real_person_with_birthday_year++;
									$total_birthday_year += $birthday_year;
								}
								// todo : multi
							}
						}

						// adds
						$count_total_investments++;
						$amount_total_investments += $payment_data[ 'amount' ];

						$array_investors[ $user_id ][ 'amount_count' ]++;
						$array_investors[ $user_id ][ 'amount_total' ] += $payment_data[ 'amount' ];
						if ( !empty( $array_investors[ $user_id ][ 'type' ] ) ) {
							$global_stats[ 'count_' . $array_investors[ $user_id ][ 'type' ] ]++;
							$global_stats[ 'amount_' . $array_investors[ $user_id ][ 'type' ] ] += $payment_data[ 'amount' ];

							$birthday_year = get_user_meta( $user_id, 'user_birthday_year', TRUE );
							$age = $this_year - $birthday_year;
							if ( !empty( $age ) ) {
								if ( $age < 25 ) {
									$global_stats[ 'count_age_inf25' ]++;
									$global_stats[ 'amount_age_inf25' ] += $payment_data[ 'amount' ];
								} elseif ( $age >= 25 && $age <= 34 ) {
									$global_stats[ 'count_age_2534' ]++;
									$global_stats[ 'amount_age_2534' ] += $payment_data[ 'amount' ];
								} elseif ( $age >= 35 && $age <= 49 ) {
									$global_stats[ 'count_age_3549' ]++;
									$global_stats[ 'amount_age_3549' ] += $payment_data[ 'amount' ];
								} elseif ( $age >= 50 && $age <= 64 ) {
									$global_stats[ 'count_age_5064' ]++;
									$global_stats[ 'amount_age_5064' ] += $payment_data[ 'amount' ];
								} elseif ( $age > 64 ) {
									$global_stats[ 'count_age_sup64' ]++;
									$global_stats[ 'amount_age_sup64' ] += $payment_data[ 'amount' ];
								}

							}
						}
					}
				}
			}
			$nb_investors = count( $array_investors );
			$avg_birthday_year = round( ( $total_birthday_year / $total_real_person_with_birthday_year ), 2 );
			?>
			<b>Investissements</b><br>
			Nb investisseurs : <?php echo $nb_investors; ?><br><br>

			Nb par une orga : <?php echo $global_stats[ 'count_orga' ]; ?><br>
			% par une orga : <?php echo round( $global_stats[ 'count_orga' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par une orga : <?php echo $global_stats[ 'amount_orga' ] ? round( $global_stats[ 'amount_orga' ] / $global_stats[ 'count_orga' ], 2 ) : 0; ?><br>
			% montant par une orga : <?php echo round( $global_stats[ 'amount_orga' ] / $amount_total_investments * 100, 2 ); ?><br><br>

			Nb par une femme : <?php echo $global_stats[ 'count_female' ]; ?><br>
			% par une femme : <?php echo round( $global_stats[ 'count_female' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par une femme : <?php echo $global_stats[ 'count_female' ] ? round( $global_stats[ 'amount_female' ] / $global_stats[ 'count_female' ], 2 ) : 0; ?><br>
			% montant par une femme : <?php echo round( $global_stats[ 'amount_female' ] / $amount_total_investments * 100, 2 ); ?><br><br>

			Nb par un homme : <?php echo $global_stats[ 'count_male' ]; ?><br>
			% par un homme : <?php echo round( $global_stats[ 'count_male' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par un homme : <?php echo $global_stats[ 'count_male' ] ? round( $global_stats[ 'amount_male' ] / $global_stats[ 'count_male' ], 2 ) : 0; ?><br>
			% montant par un homme : <?php echo round( $global_stats[ 'amount_male' ] / $amount_total_investments * 100, 2 ); ?><br><br>
		
			Année de naissance moyenne : <?php echo $avg_birthday_year; ?><br>
			Age moyen approximatif : <?php echo $this_year - $avg_birthday_year; ?><br><br>

			Nb par moins de 24 ans : <?php echo $global_stats[ 'count_age_inf25' ]; ?><br>
			% par moins de 24 ans : <?php echo round( $global_stats[ 'count_age_inf25' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par moins de 24 ans : <?php echo $global_stats[ 'count_age_inf25' ] ? round( $global_stats[ 'amount_age_inf25' ] / $global_stats[ 'count_age_inf25' ], 2 ) : 0; ?><br>
			Total par moins de 24 ans : <?php echo $global_stats[ 'amount_age_inf25' ]; ?><br><br>

			Nb par 25-34 ans : <?php echo $global_stats[ 'count_age_2534' ]; ?><br>
			% par 25-34 ans : <?php echo round( $global_stats[ 'count_age_2534' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par 25-34 ans : <?php echo $global_stats[ 'count_age_2534' ] ? round( $global_stats[ 'amount_age_2534' ] / $global_stats[ 'count_age_2534' ], 2 ) : 0; ?><br>
			Total par 25-34 ans : <?php echo $global_stats[ 'amount_age_2534' ]; ?><br><br>

			Nb par 35-49 ans : <?php echo $global_stats[ 'count_age_3549' ]; ?><br>
			% par 35-49 ans : <?php echo round( $global_stats[ 'count_age_3549' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par 35-49 ans : <?php echo $global_stats[ 'count_age_3549' ] ? round( $global_stats[ 'amount_age_3549' ] / $global_stats[ 'count_age_3549' ], 2 ) : 0; ?><br>
			Total par 35-49 ans : <?php echo $global_stats[ 'amount_age_3549' ]; ?><br><br>

			Nb par 50-64 ans : <?php echo $global_stats[ 'count_age_5064' ]; ?><br>
			% par 50-64 ans : <?php echo round( $global_stats[ 'count_age_5064' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par 50-64 ans : <?php echo $global_stats[ 'count_age_5064' ] ? round( $global_stats[ 'amount_age_5064' ] / $global_stats[ 'count_age_5064' ], 2 ) : 0; ?><br>
			Total par 50-64 ans : <?php echo $global_stats[ 'amount_age_5064' ]; ?><br><br>

			Nb par plus de 64 ans : <?php echo $global_stats[ 'count_age_sup64' ]; ?><br>
			% par plus de 64 ans : <?php echo round( $global_stats[ 'count_age_sup64' ] / $count_total_investments * 100, 2 ); ?><br>
			Moyenne par plus de 64 ans : <?php echo $global_stats[ 'count_age_sup64' ] ? round( $global_stats[ 'amount_age_sup64' ] / $global_stats[ 'count_age_sup64' ], 2 ) : 0; ?><br>
			Total par plus de 64 ans : <?php echo $global_stats[ 'amount_age_sup64' ]; ?><br><br>













		<?php else: ?>
		<h1>Tableau complet de la liste des utilisateurs</h1>
		
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Prénom Nom</td>
						<td>e-mail</td>
						<td>Entité morale</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>Adresse</td>
						<td>CP</td>
						<td>Ville</td>
						<td>Nb projets suivis</td>
						<td>Nb projets votés</td>
						<td>Nb investissements</td>
						<td>Montants investis</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Prénom Nom</td>
						<td>e-mail</td>
						<td>Entité morale</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>Adresse</td>
						<td>CP</td>
						<td>Ville</td>
						<td>Nb projets suivis</td>
						<td>Nb projets votés</td>
						<td>Nb investissements</td>
						<td>Montants investis</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php $user_list = get_users( array('number' => $number, 'offset' => $offset) ); ?>
					<?php foreach ($user_list as $user): ?>
						<?php
						$sql = "SELECT COUNT(post_meta.meta_value) AS nb_invest, SUM(post_meta.meta_value) AS sum_invest, ";
							$sql .= "(SELECT COUNT(jycrois.campaign_id) FROM ".$table_jcrois." jycrois WHERE jycrois.user_id = " .$user->ID. ") AS nb_follow, ";
							$sql .= "(SELECT COUNT(vote.post_id) FROM ".$table_vote." vote WHERE vote.user_id = " .$user->ID. ") AS nb_votes ";
						$sql .= "FROM ".$wpdb->postmeta." post_meta ";
						$sql .= "LEFT JOIN ".$wpdb->posts." post ON post.ID = post_meta.post_id ";
						$sql .= "LEFT JOIN ".$wpdb->postmeta." post_meta2 ON post_meta.post_id = post_meta2.post_id ";
						$sql .= "WHERE post.post_type='edd_payment' AND post.post_status='publish' AND post_meta.meta_key = '_edd_payment_total' ";
						$sql .= "AND post_meta2.meta_key = '_edd_payment_user_id' AND post_meta2.meta_value = " . $user->ID;
						
						$user_results = $wpdb->get_results( $sql );
						$user_result = $user_results[0];
						if ( WDGOrganization::is_user_organization( $user->ID ) ) {
							$WDGEntity = new WDGOrganization( $user->ID );
							$entity_name = $WDGEntity->get_name();
							$entity_email = $WDGEntity->get_email();
							$entity_gender = 'O';
							$entity_birthdate = '';
							$entity_address = $WDGEntity->get_address();
							$entity_postal_code = $WDGEntity->get_postal_code();
							$entity_city = $WDGEntity->get_city();
							$entity_country = $WDGEntity->get_country();
						} else {
							$WDGEntity = new WDGUser( $user->ID );
							$entity_name = $WDGEntity->get_firstname() . ' ' . $WDGEntity->get_lastname();
							$entity_email = $WDGEntity->get_email();
							$entity_gender = '-';
							if ( $WDGEntity->get_gender() == 'female' ) {
								$entity_gender = 'F';
							}
							if ( $WDGEntity->get_gender() == 'male' ) {
								$entity_gender = 'M';
							}
							$entity_birthdate = $WDGEntity->get_birthday_date();
							$entity_address = $WDGEntity->get_address();
							$entity_postal_code = $WDGEntity->get_postal_code();
							$entity_city = $WDGEntity->get_city();
							$entity_country = $WDGEntity->get_country();
						}
						?>
						<tr>
							<td><?php echo $entity_name; ?></td>
							<td><?php echo $entity_email; ?></td>
							<td><?php echo ( $entity_gender == 'O' ) ? 'OUI' : 'NON'; ?></td>
							<td><?php echo $entity_gender; ?></td>
							<td><?php echo $entity_birthdate; ?></td>
							<td><?php echo $entity_address; ?></td>
							<td><?php echo $entity_postal_code; ?></td>
							<td><?php echo $entity_city; ?></td>
							<td><?php echo $user_result->nb_follow; ?></td>
							<td><?php echo $user_result->nb_votes; ?></td>
							<td><?php echo $user_result->nb_invest; ?></td>
							<td><?php echo $user_result->sum_invest; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
	
		<?php
		$result = count_users();
		$user_count = $result['total_users'];
		$nb_page = ceil( $user_count / 1000 );
		?>
		Pages :
		<a href="<?php echo home_url('/statistiques-utilisateurs/'); ?>">1</a>
		<?php for ($i = 2; $i <= $nb_page; $i++): ?>
		| <a href="<?php echo home_url('/statistiques-utilisateurs/'); ?>?offset=<?php echo ($i-1); ?>"><?php echo $i; ?></a>
		<?php endfor; ?>
		<?php endif; ?>
		
    </div>
</div>

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/jquery.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.colReorder.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/colReorder.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.select.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/select.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.buttons.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/buttons.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/jszip.min.js"></script>
		
<script type="text/javascript">
	jQuery(document).ready( function($) {
		// Ajoute mise en page et interactions du tableau
		// Ajoute un champ de filtre à chaque colonne dans le footer
		$('.wdg-datatable table tfoot td').each( function () {
			$(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
		} );
		
		// Ajoute les actions de filtrage
		$(".wdg-datatable table tfoot input").on( 'keyup change', function () {
			table
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		} );

		//Récupère le tri par défaut 
		sortColumn = 0;
		$('.wdg-datatable table thead td').each(function(index){
			if ($(this).text() === "Nb projets investis") { sortColumn = index; };
		});

		var table = $('.wdg-datatable table').DataTable({
			order: [[ sortColumn, "desc" ]], //Colonne à trier (date)

			dom: 'RC<"clear">lfrtip',
			lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
			iDisplayLength: 25,//nombre d'éléments par défaut

			//Boutons de sélection de colonnes
			colVis: {
				buttonText: "Afficher/cacher colonnes",
				restore: "Restaurer",
				showAll: "Tout afficher",
				showNone: "Tout cacher",
				overlayFade: 100
			},
			language: {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst":      "Premier",
					"sPrevious":   "Pr&eacute;c&eacute;dent",
					"sNext":       "Suivant",
					"sLast":       "Dernier"
				},
				"oAria": {
					"sSortAscending":  ": activer pour trier la colonne par ordre croissant",
					"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				}
			}
		});
	});
</script>

<?php get_footer();