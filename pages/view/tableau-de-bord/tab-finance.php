<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Financement", 'yproject' ); ?></h2>

<div class="db-form v3 full center bg-white">
	<form id="projectfunding_form" class="ajax-db-form" data-action="save_project_funding" novalidate>
		<?php
		DashboardUtility::create_field(array(
			'id'			=> 'new_minimum_goal',
			'type'			=> 'text-money',
			'label'			=> "Objectif",
			'description'	=> "C'est le seuil de validation de votre lev&eacute;e de fonds, incluant la commission de WE DO GOOD. Vous pourrez ensuite viser l'objectif maximum !",
			'value'			=> $page_controler->get_campaign()->minimum_goal(false),
			'min'			=> 500,
			'editable'		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));

		DashboardUtility::create_field(array(
			'id'			=> 'new_maximum_goal',
			'type'			=> 'text-money',
			'label'			=> "Objectif maximum",
			'description'	=> "C'est le montant maximum de votre lev&eacute;e de fonds, incluant la commission de WE DO GOOD.",
			'value'			=> $page_controler->get_campaign()->goal(false),
			'min'			=> 500,
			'editable'		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));
		
		DashboardUtility::create_field(array(
			"id"			=> "new_project_contract_spendings_description",
			"type"			=> "editor",
			"label"			=> __( "Description des d&eacute;penses", 'yproject' ),
			"value"			=> $page_controler->get_campaign()->contract_spendings_description(),
			"editable"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));
		
		DashboardUtility::create_field(array(
			"id"			=> "new_funding_duration",
			"type"			=> "select",
			"label"			=> "Dur&eacute;e du financement",
			"description"	=> "Indiquez 5 ans pour un projet entrepreneurial, sauf cas particulier à valider avec l’équipe WE DO GOOD.",
			"value"			=> $page_controler->get_campaign()->funding_duration(),
			"options_id"	=> array_keys( ATCF_Campaign::$funding_duration_list ),
			"options_names"	=> array_values( ATCF_Campaign::$funding_duration_list ),
			"editable"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));

		if ( $page_controler->can_access_admin() ) {

			DashboardUtility::create_field(array(
				"id"			=> "new_maximum_profit",
				"type"			=> "select",
				"label"			=> "Gain maximum",
				"value"			=> $page_controler->get_campaign()->maximum_profit(),
				"options_id"	=> array_keys( ATCF_Campaign::$maximum_profit_list ),
				"options_names"	=> array_values( ATCF_Campaign::$maximum_profit_list ),
				"prefix"		=> '*',
				"admin_theme"	=> true,
				"editable"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
			));

			DashboardUtility::create_field(array(
				"id"			=> "new_maximum_profit_precision",
				"type"			=> "number",
				"label"			=> "Pr&eacute;cision pour le gain maximum (apr&egrave;s la virgule, nombre entier positif)",
				"value"			=> $page_controler->get_campaign()->maximum_profit_precision(),
				"admin_theme"	=> true,
				"editable"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
			));

			DashboardUtility::create_field(array(
				"id"			=> "new_platform_commission",
				"type"			=> "text-percent",
				"label"			=> "Commission de la plateforme",
				"value"			=> $page_controler->get_campaign()->platform_commission(),
				"unit"			=> "% TTC",
				"min"			=> 0,
				"max"			=> 100,
				"step"			=> 0.000000000000000000000001,
				"editable"		=> $page_controler->can_access_admin(),
				"visible"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));

			DashboardUtility::create_field(array(
				"id"			=> "new_platform_commission_above_100000",
				"type"			=> "text-percent",
				"label"			=> "Commission de la plateforme au-dela de 100 k€",
				"value"			=> $page_controler->get_campaign()->platform_commission_above_100000(),
				"unit"			=> "% TTC",
				"min"			=> 0,
				"max"			=> 100,
				"step"			=> 0.000000000000000000000001,
				"editable"		=> $page_controler->can_access_admin(),
				"visible"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));

		}

		DashboardUtility::create_field(array(
			"id"			=> "new_roi_percent_estimated",
			"type"			=> "text-percent",
			"label"			=> "Royalties",
			"description"	=> "Indiquez le pourcentage de chiffre d’affaires que vous souhaitez reverser à vos investisseurs. Vérifiez dans le prévisionnel ci-dessous que le retour sur investissement est suffisant.",
			"value"			=> $page_controler->get_campaign()->roi_percent_estimated(),
			"unit"			=> "% du CA",
			"min"			=> 0,
			"max"			=> 100,
			"step"			=> 0.000000000000000000000001,
			"editable"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_roi_percent",
			"type"			=> "text-percent",
			"label"			=> "Royalties r&eacute;els (selon montant collect&eacute;)",
			"value"			=> $page_controler->get_campaign()->roi_percent(),
			"unit"			=> "% du CA",
			"min"			=> 0,
			"max"			=> 100,
			"step"			=> 0.000000000000000000000001,
			"visible"		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed,
			"editable"		=> $page_controler->can_access_admin()
		));

		$contract_start_date_editable = ( $page_controler->get_campaign()->is_preparing() || $page_controler->can_access_admin() );
		$contract_start_date_values = array();
		$contract_start_date_list = array();
		if ( $contract_start_date_editable ) {
			// Trouver la prochaine date possible : janvier, avril, juillet, octobre
			$campaign_creation_date = new DateTime( $page_controler->get_campaign()->data->post_date );
			$previous_date = new DateTime();
			$next_date = new DateTime();
			switch ( $campaign_creation_date->format('m') ) {
				case 1:
				case 2:
				case 3:
					$previous_date =  new DateTime( $campaign_creation_date->format( 'Y' ) . '-01-01' );
					$next_date = new DateTime( $campaign_creation_date->format( 'Y' ) . '-04-01' );
					break;
				case 4:
				case 5:
				case 6:
					$previous_date =  new DateTime( $campaign_creation_date->format( 'Y' ) . '-04-01' );
					$next_date = new DateTime( $campaign_creation_date->format( 'Y' ) . '-07-01' );
					break;
				case 7:
				case 8:
				case 9:
					$previous_date =  new DateTime( $campaign_creation_date->format( 'Y' ) . '-07-01' );
					$next_date = new DateTime( $campaign_creation_date->format( 'Y' ) . '-10-01' );
					break;
				case 10:
				case 11:
				case 12:
					$previous_date =  new DateTime( $campaign_creation_date->format( 'Y' ) . '-10-01' );
					$next_date = new DateTime( ( $campaign_creation_date->format( 'Y' ) + 1 ) . '-01-01' );
					break;
			}
			$previous_date->sub( new DateInterval( 'P3M' ) );
			array_push( $contract_start_date_values, $previous_date->format( 'Y-m-d' ) );
			array_push( $contract_start_date_list, $previous_date->format( 'd/m/Y' ) );
			$previous_date->add( new DateInterval( 'P3M' ) );
			array_push( $contract_start_date_values, $previous_date->format( 'Y-m-d' ) );
			array_push( $contract_start_date_list, $previous_date->format( 'd/m/Y' ) );
			// Ensuite on ajoute (arbitrairement) 10 dates
			for ( $i = 0; $i < 10; $i++ ) {
				array_push( $contract_start_date_values, $next_date->format( 'Y-m-d' ) );
				array_push( $contract_start_date_list, $next_date->format( 'd/m/Y' ) );
				$next_date->add( new DateInterval( 'P3M' ) );
			}
		}
		$contract_start_date = new DateTime( $page_controler->get_campaign()->contract_start_date() );
		DashboardUtility::create_field(array(
			"id"			=> "new_contract_start_date",
			"type"			=> "select",
			"label"			=> "Date de d&eacute;marrage du contrat",
			"value"			=> $contract_start_date->format( 'Y-m-d' ),
			"editable"		=> $contract_start_date_editable,
			"options_id"	=> $contract_start_date_values,
			"options_names"	=> $contract_start_date_list
		));

		if ( $page_controler->can_access_admin() ) {
			DashboardUtility::create_field(array(
				"id"			=> "new_turnover_per_declaration",
				"type"			=> "select",
				"label"			=> "Nb d&eacute;claration CA par versement",
				"value"			=> $page_controler->get_campaign()->get_turnover_per_declaration(),
				"options_id"	=> array(1, 3),
				"options_names"	=> array(1, 3),
				"editable"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));
			DashboardUtility::create_field(array(
				"id"			=> "new_declaration_periodicity",
				"type"			=> "select",
				"label"			=> "P&eacute;riodicit&eacute; des d&eacute;clarations",
				"value"			=> $page_controler->get_campaign()->get_declaration_periodicity(),
				"options_id"	=> array_keys( ATCF_Campaign::$declaration_periodicity_list ),
				"options_names"	=> array_values( ATCF_Campaign::$declaration_periodicity_list ),
				"editable"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));
			DashboardUtility::create_field(array(
				"id"			=> "new_minimum_costs_to_organization",
				"type"			=> "text-money",
				"label"			=> "Montant minimum TTC des frais appliqués au PP",
				"value"			=> $page_controler->get_campaign()->get_minimum_costs_to_organization(),
				"editable"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));
			DashboardUtility::create_field(array(
				"id"			=> "new_costs_to_organization",
				"type"			=> "text-percent",
				"label"			=> "Pourcentage de frais appliqués au PP",
				"value"			=> $page_controler->get_campaign()->get_costs_to_organization(),
				"unit"			=> "% TTC",
				"min"			=> 0,
				"max"			=> 100,
				"step"			=> 0.01,
				"editable"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));
			DashboardUtility::create_field(array(
				"id"			=> "new_costs_to_investors",
				"type"			=> "text-percent",
				"label"			=> "Pourcentage de frais appliqués aux investisseurs",
				"value"			=> $page_controler->get_campaign()->get_costs_to_investors(),
				"unit"			=> "% TTC",
				"min"			=> 0,
				"max"			=> 100,
				"step"			=> 0.01,
				"editable"		=> $page_controler->can_access_admin(),
				"admin_theme"	=> true
			));
		}

		DashboardUtility::create_field(array(
			"id"			=> "new_first_payment",
			"type"			=> "date",
			"label"			=> "Première date de versement",
			"value"			=> new DateTime($page_controler->get_campaign()->first_payment_date()),
			"editable"		=> $page_controler->can_access_admin(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"visible"		=> $page_controler->can_access_admin() || ($page_controler->get_campaign()->first_payment_date()!="")
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_estimated_turnover_unit",
			"type"			=> "select",
			"label"			=> "Le pr&eacute;visionnel est exprim&eacute; en",
			"value"			=> $page_controler->get_campaign()->estimated_turnover_unit(),
			"options_id"	=> array( 'euro', 'percent' ),
			"options_names"	=> array( '&euro;', '%' ),
			"editable"		=> $page_controler->can_access_admin(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"visible"		=> $page_controler->can_access_admin()
		));
		?>

		<table>
			
			<thead>
				<tr>
					<td></td>
					<td><?php _e( "CA pr&eacute;visionnel", 'yproject' ); ?></td>
					<td>
						<?php _e( "Montant des Royalties reversées pour", 'yproject' ); ?>
						<span id="total-funding">---</span>&nbsp;&euro;
						<?php _e( "investis", 'yproject' ); ?>
					</td>
				</tr>
			</thead>

			<?php
			$estimated_turnover = $page_controler->get_campaign()->estimated_turnover();
			$is_euro = ( $page_controler->get_campaign()->estimated_turnover_unit() == 'euro' );
			$data_symbol = ( $is_euro ) ? '€' : '%';
			?>
			<tbody id="estimated-turnover" data-symbol="<?php echo $data_symbol; ?>">
				<?php if ( !empty( $estimated_turnover ) ): ?>
					<?php $i = 0; ?>
					<?php foreach ( $estimated_turnover as $year => $turnover ): ?>
						<tr>
							<td>
								Année <span class="year"><?php echo ( $i+1 ); ?></span>
							</td>
							<td class="field field-value" data-type="number" data-id="new_estimated_turnover_<?php echo $i;?>" data-type="number">
								<?php if ( $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing() ): ?>
									<input type="number" value="<?php echo $turnover; ?>" id="new_estimated_turnover_<?php echo $i;?>" class="right-icon format-number" />&nbsp;<?php echo $data_symbol; ?>
								<?php else: ?>
									<?php echo $turnover; ?>
									<span style="padding-right: 70px;"><?php echo $data_symbol; ?></span>
								<?php endif; ?>
							</td>
							<td id="roi-amount-<?php echo $i;?>" class="like-input-center">
								0 €
							</td>
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="2"><?php _e( "TOTAL", 'yproject' ); ?></td>
					<td id="total-roi">0&nbsp;€</td>
				</tr>
				<tr>
					<td colspan="2"><?php _e( "Retour sur investissement", 'yproject' ); ?></td>
					<td id="gain">0&nbsp;€</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php _e( "Pour vos investisseurs :", 'yproject' ); ?><br>
						<?php _e( "Rendement final", 'yproject' ); ?>
					</td>
					<td id="medium-rend">---&nbsp;%</td>
				</tr>
			</tfoot>
			
		</table>
		
		<?php if ( $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing() ): ?>
		<?php DashboardUtility::create_save_button("projectfunding_form"); ?>
		<?php endif; ?>
	</form>
</div>