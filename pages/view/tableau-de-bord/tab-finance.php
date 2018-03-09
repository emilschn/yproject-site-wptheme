<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Financement", 'yproject' ); ?></h2>

<div class="db-form v3 full center bg-white">
	<form id="projectfunding_form" class="ajax-db-form" data-action="save_project_funding">
		<?php
		DashboardUtility::create_field(array(
			'id'			=> 'new_minimum_goal',
			'type'			=> 'text-money',
			'label'			=> "Objectif",
			'description'	=> "C'est le seuil de validation de votre lev&eacute;e de fonds, vous pourrez ensuite viser le montant maximum !",
			'value'			=> $page_controler->get_campaign()->minimum_goal(false),
			'min'			=> 500,
			'editable'		=> $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing()
		));

		DashboardUtility::create_field(array(
			'id'			=> 'new_maximum_goal',
			'type'			=> 'text-money',
			'label'			=> "Montant maximum",
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
			array_push( $contract_start_date_values, $previous_date->format( 'Y-m-d H:i:s' ) );
			array_push( $contract_start_date_list, $previous_date->format( 'd/m/Y' ) );
			// Ensuite on ajoute (arbitrairement) 10 dates
			for ( $i = 0; $i < 10; $i++ ) {
				array_push( $contract_start_date_values, $next_date->format( 'Y-m-d H:i:s' ) );
				array_push( $contract_start_date_list, $next_date->format( 'd/m/Y' ) );
				$next_date->add( new DateInterval( 'P3M' ) );
			}
		}
		DashboardUtility::create_field(array(
			"id"			=> "new_contract_start_date",
			"type"			=> "select",
			"label"			=> "Date de d&eacute;marrage du contrat",
			"value"			=> $page_controler->get_campaign()->contract_start_date(),
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
				"id"			=> "new_costs_to_organization",
				"type"			=> "text-percent",
				"label"			=> "Pourcentage de frais appliqués au PP",
				"value"			=> $page_controler->get_campaign()->get_costs_to_organization(),
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

		<div class="field">
			<label class="column-title" style="margin-left: 270px">Chiffre d'affaires pr&eacute;visionnel</label>
			<label class="column-title" style="margin-left: 10px; width: 260px">
				<?php echo __('Montant des Royalties reversées', 'yproject')."&nbsp;".__("pour","yproject")?>
				<span id="total-funding">---</span>&nbsp;&euro;
				<?php echo "&nbsp;".__("investis"); ?>
			</label>
		</div>
		<?php $is_euro = ( $page_controler->get_campaign()->estimated_turnover_unit() == 'euro' ); ?>
		<ul id="estimated-turnover" data-symbol="<?php if ( $is_euro ): ?>€<?php else: ?>%<?php endif; ?>">
			<?php
			$estimated_turnover = $page_controler->get_campaign()->estimated_turnover();
			if(!empty($estimated_turnover)){
				$i=0;
				foreach (($page_controler->get_campaign()->estimated_turnover()) as $year => $turnover) :?>
					<li class="field">
						<label>Année <span class="year"><?php echo ($i+1); ?></span></label>                           
						<span class="field-container" <?php if ( !$page_controler->can_access_admin() && !$page_controler->get_campaign()->is_preparing() ): ?> style="padding-left: 80px;" <?php endif; ?>>
							<span class="field-value" data-type="number" data-id="new_estimated_turnover_<?php echo $i;?>">
								<?php if ( $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing() ): ?>
								<i class="right fa <?php if ($is_euro): ?>fa-eur<?php endif; ?>" aria-hidden="true"></i>
								<input type="number" value="<?php echo $turnover?>" id="new_estimated_turnover_<?php echo $i;?>" class="right-icon" />
									<?php if ( !$is_euro ): ?>%<?php endif; ?>
								<?php else: ?>
								<?php echo $turnover; ?>
								<?php endif; ?>
							</span>
							<?php if ( !$page_controler->can_access_admin() && !$page_controler->get_campaign()->is_preparing() ): ?>
								<span style="padding-right: 70px;">&euro;</span>
							<?php endif; ?>
							<!--montant des royalties reversées par année-->
							<span class="like-input-center">
								<p id="roi-amount-<?php echo $i;?>">0 €</p>
								<!--<input class="input-center" type="text" id="new-estimated-roi-<?php echo $i;?>" disabled="disabled"/>-->
							</span>                                     
						</span>
					</li>
				<?php
					$i++;
				endforeach;
			}
			 ?>
		</ul>
		<!-- Total de royalties reversées -->
		<div id="total-roi-container" class="field">
			<label><?php _e("TOTAL", "yproject")?></label><span class="like-input-center"><p id="total-roi">0&nbsp;€</p></span>
			<label><?php _e("Retour sur investissement", "yproject")?></label><span class="like-input-center"><p id="gain"></p></span>
		</div>
		<!-- Rendement annuel moyen pour les investisseurs -->
		<div id="annual-gain-container" class="field">
			<label><?php _e("Pour vos investisseurs", "yproject")?>&nbsp;:</label>
			<label><?php _e("Rendement final", "yproject") ?></label><span class="like-input-center"><p id="medium-rend">---&nbsp;%</p></span>
		</div>
		<?php if ( $page_controler->can_access_admin() || $page_controler->get_campaign()->is_preparing() ): ?>
		<?php DashboardUtility::create_save_button("projectfunding_form"); ?>
		<?php endif; ?>
	</form>
</div>