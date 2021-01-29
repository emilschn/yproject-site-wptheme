<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( "Vue d'ensemble", 'yproject' ); ?></h2>

<?php DashboardUtility::add_help_item( $page_controler->get_current_user(), 'home', 1 ); ?>

<?php
$status = $page_controler->get_campaign_status();
$collecte_or_after = $status==ATCF_Campaign::$campaign_status_collecte || $status==ATCF_Campaign::$campaign_status_funded || $status==ATCF_Campaign::$campaign_status_archive || $status==ATCF_Campaign::$campaign_status_closed;
$vote_or_after = $collecte_or_after || $status==ATCF_Campaign::$campaign_status_vote;
$preview_or_after = $vote_or_after || $status==ATCF_Campaign::$campaign_status_preview;
$validated_or_after = true; //$preview_or_after || $status==ATCF_Campaign::$campaign_status_validated;
$status_list = ATCF_Campaign::get_campaign_status_list();
$nb_status = count( $status_list ) - 2;
$i=1;

// si on est en évaluation, on ne peut pas passer en collecte tant qu'il y a des chèques non validés
$remaining_check = FALSE;
if( $status==ATCF_Campaign::$campaign_status_vote ) {
	$investment_results = WDGCampaignInvestments::get_list( $page_controler->get_campaign_id() );
	if( $investment_results[ 'count_not_validate_check_investments' ] > 0 ){
		$remaining_check = TRUE;
	}
}
?>
<div id="status-list">
	
	<div class="perso <?php if($status==ATCF_Campaign::$campaign_status_preparing){echo ' preparing ';}?>"></div>
	
	<?php foreach ($status_list as $status_key => $name): ?>
	
		<?php if ( 
			( $status_key != ATCF_Campaign::$campaign_status_preview )
			&& ( 
				( $status == ATCF_Campaign::$campaign_status_archive && $status_key == ATCF_Campaign::$campaign_status_archive )
				|| 
				( $status != ATCF_Campaign::$campaign_status_archive && $status_key == ATCF_Campaign::$campaign_status_closed )
				|| 
				( $status_key != ATCF_Campaign::$campaign_status_closed && $status_key != ATCF_Campaign::$campaign_status_archive )
				) ): ?>
		<div class="status
				<?php   if($i==1){echo "begin ";}
						if($i==$nb_status){echo "end ";}?>"
			   <?php if($status_key==$status){echo 'id="current"';}?>>
			<div class="line
				<?php   if($i==1){echo "begin ";}
						if($i==$nb_status){echo "end ";}
						if($i>$nb_status){echo "none ";}?>">

			</div>
			
			<?php
			switch ($status_key){
				case ATCF_Campaign::$campaign_status_validated:
					?><div class="linetoright"></div><?php
					break;
				case ATCF_Campaign::$campaign_status_preview:
					?><div class="linetoboth"></div><?php
					break;
				case ATCF_Campaign::$campaign_status_vote:
					?><div class="linetoleft"></div><?php
					break;
			}
			?>
			<div class="icon-etape"></div>
			
			<div class="name">
				<?php echo $name.'<br/>';
				switch ($status_key){
					case ATCF_Campaign::$campaign_status_preparing:
						DashboardUtility::get_infobutton("R&eacute;sumez votre projet et transmettez les informations n&eacute;cessaires au comit&eacute; de s&eacute;lection.",true);
						break;
					case ATCF_Campaign::$campaign_status_validated:
						DashboardUtility::get_infobutton("Nous vous conseillons sur votre campagne de communication et la pr&eacute;sentation de votre projet.",true);
						break;
					case ATCF_Campaign::$campaign_status_preview:
						DashboardUtility::get_infobutton("L'avant-première permet de faire d&eacute;couvrir votre projet sur le site. Cette &eacute;tape est facultative",true);
						break;
					case ATCF_Campaign::$campaign_status_vote:
						DashboardUtility::get_infobutton("Testez votre communication et &eacute;valuez votre capacit&eacute; à f&eacute;d&eacute;rer vos cercles d'investisseurs.",true);
						break;
					case ATCF_Campaign::$campaign_status_collecte:
						DashboardUtility::get_infobutton("Mobilisez des investisseurs pour atteindre votre seuil de validation de levée de fonds et le d&eacute;passer !",true);
						break;
					case ATCF_Campaign::$campaign_status_funded:
						DashboardUtility::get_infobutton("Versez les royalties &agrave; vos investisseurs en fonction de votre chiffre d'affaires et tenez-les inform&eacute;s des avanc&eacute;es de votre projet.",true);
						break;
					case ATCF_Campaign::$campaign_status_closed:
					case ATCF_Campaign::$campaign_status_archive:
						DashboardUtility::get_infobutton("Votre contrat est arriv&eacute; &agrave; son terme.",true);
						break;
				}
				?>
			</div>
		</div>
		<?php $i++; ?>
		<?php endif; ?>
	
	<?php endforeach; ?>
</div>



<?php if ( $preview_or_after ): ?>
<?php
//Donnees de votes
$vote_results = WDGCampaignVotes::get_results( $page_controler->get_campaign_id() );
//Recuperation du nombre de j'y crois
$nb_jcrois = $page_controler->get_campaign()->get_jycrois_nb();
//Recuperation du nombre de votants
$nb_votes = $page_controler->get_campaign()->nb_voters();
//Recuperation du nombre d'investisseurs
$nb_invests = $page_controler->get_campaign()->backers_count();
?>
<div class="tab-content" id="stats-tab">
	<div id="block-stats" class="large-block">
		<div class="data-blocks">

			<?php if( $status == ATCF_Campaign::$campaign_status_vote ): ?>
				<?php $big_number_class = ( $vote_results['sum_invest_ready'] > 10000 ) ? 'less-big' : ''; ?>
				<div id="stats-vote">
					<div class="quart-card">
						<div class="stat-big-number"><?php echo $nb_votes; ?></div>
						<div class="stat-little-number">sur <?php echo ATCF_Campaign::$voters_min_required?> requis</div>
						<div class="details-card">
							<strong><?php echo $nb_votes; ?></strong> personne<?php if($nb_votes>1){echo 's ont';}else{echo ' a';} echo ' évalué';?>
						</div>
					</div>
					<div class="quart-card">
						<canvas id="canvas-vertical-bar-block" width="160" height="160"></canvas><br/>
						<div class="details-card">
							En moyenne, les évaluateurs notent <strong><?php echo $vote_results[ 'rate_project_average' ]; ?></strong>
						</div>
					</div>
					<div class="quart-card">
						<div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $vote_results['sum_invest_ready'].'&euro;'?></div>
						<div class="stat-little-number">sur <?php echo $page_controler->get_campaign()->vote_invest_ready_min_required() ?> &euro; recommand&eacute;s</div>
						<div class="details-card">
							<strong><?php echo $vote_results['sum_invest_ready']?></strong>&euro; d'intentions d'investissement
						</div>
					</div>
					<div class="quart-card">
						<div class="stat-big-number"><?php echo $page_controler->get_campaign()->time_remaining_str();?><br/></div>
						<div class="stat-little-number">Avant la fin de l'évaluation</div>
						<div class="details-card"><?php echo $page_controler->get_campaign()->time_remaining_fullstr()?></div>
					</div>
				</div>
				<div class="db-form v3 padding-top">
					<a class="button blue half switch-tab" href="#stats"><?php _e( "Voir les statistiques d'&eacute;valuation", 'yproject' ); ?></a>
				</div>

			<?php elseif( $status == ATCF_Campaign::$campaign_status_collecte ): ?>
				<?php $big_number_class = ( $page_controler->get_campaign()->current_amount(false) > 10000 ) ? 'less-big' : ''; ?>
				<div id="stats-invest">
					<div class="half-card">
						<div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $page_controler->get_campaign()->current_amount(); ?></div>
						<div class="stat-little-number">sur <?php echo $page_controler->get_campaign()->minimum_goal(false) / 1; ?> &euro; requis</div>
						<div class="details-card">
							<strong><?php echo $page_controler->get_campaign()->current_amount(); ?></strong> investis par
							<strong><?php echo $nb_invests; ?></strong> personne<?php if( $nb_invests > 1 ){ echo 's'; } ?>
						</div>
					</div><div class="half-card">
						<div class="stat-big-number"><?php echo $page_controler->get_campaign()->time_remaining_str();?><br/></div>
						<div class="stat-little-number">Avant la fin de collecte</div>
						<div class="details-card"><?php echo $page_controler->get_campaign()->time_remaining_fullstr()?></div>
					</div>
				</div>
				<div class="db-form v3 padding-top">
					<a class="button blue half switch-tab" href="#stats"><?php _e( "Voir les statistiques d'investissement", 'yproject' ); ?></a>
				</div>

			<?php elseif ( $status == ATCF_Campaign::$campaign_status_funded || $status == ATCF_Campaign::$campaign_status_archive ): ?>
				<div id="stats-funded">
					<?php $big_number_class = ( $page_controler->get_campaign()->current_amount(false) > 10000 ) ? 'less-big' : ''; ?>
					<div class="stat-big-number <?php echo $big_number_class; ?>"><?php echo $page_controler->get_campaign()->current_amount(); ?></div>
					<div class="stat-little-number">levés sur <?php echo $page_controler->get_campaign()->minimum_goal(false) / 1; ?> &euro;</div>
					<div class="details-card">
						<strong><?php echo $page_controler->get_campaign()->current_amount(); ?></strong> investis par
						<strong><?php echo $nb_invests; ?></strong> personne<?php if($nb_invests>1){echo 's';}?>
					</div>
				</div>
				<div class="db-form v3 padding-top">
					<a class="button blue half switch-tab" href="#stats"><?php _e( "Voir les statistiques d'investissement", 'yproject' ); ?></a>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( $status == ATCF_Campaign::$campaign_status_vote ): ?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				new Chart(
					document.getElementById("canvas-vertical-bar-block"),
					{
						"type":"bar",
						"data":{
							"labels": [ "1", "2", "3", "4", "5" ],
							"datasets":[ {
								"label":"",
								"data":[
									<?php echo $vote_results[ 'rate_project_list' ][ '1' ]; ?>,
									<?php echo $vote_results[ 'rate_project_list' ][ '2' ]; ?>,
									<?php echo $vote_results[ 'rate_project_list' ][ '3' ]; ?>,
									<?php echo $vote_results[ 'rate_project_list' ][ '4' ]; ?>,
									<?php echo $vote_results[ 'rate_project_list' ][ '5' ]; ?>
								],
								"fill":false,
								"backgroundColor":[
									"#93626d",
									"#93626d",
									"#93626d",
									"#93626d",
									"#93626d"
								]
							} ]
						},
						options:{
							legend:{
								display: false
							},
							scales:{
								yAxes:[{
									ticks:{
										beginAtZero: true,
										stepSize: 1
									}
								}]
							}
						}
					}
				);
			});
		</script>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>




<div class="tab-content" id="next-status-tab">
	<?php if (	$status == ATCF_Campaign::$campaign_status_preparing
				|| $status == ATCF_Campaign::$campaign_status_validated
				|| $status == ATCF_Campaign::$campaign_status_preview
				|| ($status == ATCF_Campaign::$campaign_status_vote && $page_controler->get_campaign()->can_go_next_status())): ?>
	<h2 style='text-align:center'><?php _e("Pr&ecirc;t(e) pour la suite ?", 'yproject'); ?></h2>

	<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=change_project_status'); ?>" id="form-changing-from-<?php echo $status; ?>">
		<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id();?>">
		<ul>
			<?php if ($status == ATCF_Campaign::$campaign_status_preparing): ?>
				<p id="desc-preview">
					<?php _e("Votre projet doit maintenant &ecirc;tre valid&eacute; par le Comit&eacute; de s&eacute;lection.", 'yproject'); ?><br />
					<?php _e("&Ecirc;tes-vous pr&ecirc;t(e) &agrave; le pr&eacute;senter ?", 'yproject'); ?>
				</p>

				<li>
					<label>
						<?php
						$preparing_has_filled_desc = ( $page_controler->get_campaign()->get_validation_step_status( 'has_filled_desc' ) == "on" );
						$is_waiting_for_comitee = $preparing_has_filled_desc;
						?>
						<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-desc" <?php if (!$page_controler->can_access_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_has_filled_desc ); ?>>
						J'ai compl&eacute;t&eacute; la description de mon projet et de ses impacts
					</label>
				</li>
				<li>
					<label>
						<?php
						$preparing_user_is_complete = ypcf_check_user_is_complete( $page_controler->get_campaign()->post_author() );
						$is_waiting_for_comitee &= $preparing_user_is_complete;
						?>
						<input type="checkbox" class="checkbox-next-status" disabled <?php checked( $preparing_user_is_complete ); ?>>
						L'auteur du projet, <?php echo $page_controler->get_campaign_author()->wp_user->get('display_name'); ?>, a rempli ses informations personnelles
					</label>
				</li>
				<li>
					<label>
						<?php
						$preparing_org_is_complete = $page_controler->get_campaign_organization()->has_filled_invest_infos();
						$is_waiting_for_comitee &= $preparing_org_is_complete;
						?>
						<input type="checkbox" class="checkbox-next-status" disabled <?php checked( $preparing_org_is_complete ); ?>>
						J'ai compl&eacute;t&eacute; les informations sur l'organisation qui porte le projet
					</label>
				</li>
				<li>
					<label>
						<?php
						$preparing_finance_ready = ( $page_controler->get_campaign()->get_validation_step_status( 'has_filled_finance' ) == "on" );
						$is_waiting_for_comitee &= $preparing_finance_ready;
						?>
						<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-finance" <?php if (!$page_controler->can_access_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_finance_ready ); ?>>
						J'ai pr&eacute;cis&eacute; mon besoin de financement
					</label>
				</li>
				<li>
					<label>
						<?php
						$preparing_parameters_validated = ( $page_controler->get_campaign()->get_validation_step_status( 'has_filled_parameters' ) == "on" );
						$is_waiting_for_comitee &= $preparing_parameters_validated;
						?>
						<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-parameters" <?php if (!$page_controler->can_access_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_parameters_validated ); ?>>
						J'ai valid&eacute; les param&egrave;tres de ma lev&eacute;e de fonds avec un membre de l'équipe WE DO GOOD
					</label>
				</li>
				<li>
					<label>
						<?php
						$preparing_signed_order = ( $page_controler->get_campaign()->get_validation_step_status( 'has_signed_order' ) == "on" );
						$is_waiting_for_comitee &= $preparing_signed_order;
						?>
						<input type="checkbox" class="checkbox-next-status" name="validation-step-has-signed-order" <?php if (!$page_controler->can_access_admin()) { ?>disabled<?php } ?> <?php checked( $preparing_signed_order ); ?>>
						J'ai exprim&eacute; mon engagement en renvoyant le bon de commande sign&eacute;
					</label>
				</li>


			<?php elseif ($status == ATCF_Campaign::$campaign_status_validated && !$page_controler->get_campaign()->skip_vote()): ?>
				<p id="desc-preview">
					<?php _e("Il est temps maintenant de pr&eacute;parer la publication de votre projet.", 'yproject'); ?><br />
					<?php _e("Vous devrez r&eacute;unir au moins :", 'yproject'); ?><br />
					<?php _e("- 50 évaluateurs avec des intentions d'investissement", 'yproject'); ?><br />
					<?php _e("- 50% de évaluations positives", 'yproject'); ?><br />
					<?php _e("- 50% d'intentions d'investissement par rapport &agrave; votre objectif", 'yproject'); ?><br />
					<br />
					<?php _e("&Ecirc;tes-vous pr&ecirc;t(e) &agrave; le publier ?", 'yproject'); ?><br />
				</p>

				<li>
					<label>
						<?php
						$validated_documents_sent = $page_controler->get_campaign_organization()->has_sent_all_documents();
						?>
						<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_documents_sent); ?>>
						J'ai transmis les documents d'authentification de mon organisation
					</label>
				</li>
				<li>
					<label>
						<?php
						$validated_org_authentified = $page_controler->get_campaign_organization()->is_registered_lemonway_wallet();
						?>
						<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_org_authentified); ?>>
						L'organisation est authentifiée par le prestataire de paiement
						<?php DashboardUtility::get_infobutton("Le prestataire de paiement s&eacute;curis&eacute; doit valider votre compte. Cela peut prendre quelques jours.", true); ?>
					</label>
				</li>
				<li>
					<label>
						<?php
						$validated_presentation_complete = ( $page_controler->get_campaign()->get_validation_step_status( 'has_filled_presentation' ) == "on" );
						?>
						<input type="checkbox" class="checkbox-next-status" name="validation-step-has-filled-presentation" <?php if (!$page_controler->can_access_admin()) { ?>disabled<?php } ?> <?php checked($validated_presentation_complete); ?>>
						J'ai compl&eacute;t&eacute; la pr&eacute;sentation de mon projet
					</label>
				</li>
				<li>
					<label>
						<?php
						$validated_vote_authorized = $page_controler->get_campaign()->can_go_next_status();
						?>
						<input type="checkbox" class="checkbox-next-status" disabled <?php checked($validated_vote_authorized); ?>>
						WE DO GOOD a autoris&eacute; la publication de mon projet en évaluation
					</label>
				</li>

				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						J'ai list&eacute; tous les contacts mobilisables pour ma lev&eacute;e de fonds avec toute mon &eacute;quipe
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						J'ai pr&eacute;par&eacute; des mails &agrave; envoyer à l'ensemble de mes contacts et des publications pour les r&eacute;seaux sociaux
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma levée de fonds &agrave; mes proches et &agrave; mon r&eacute;seau
					</label>
				</li>

				<li>
					<label>
						Nombre de jours d'évaluation :
						<input type="number" id="innbdayvote" name="innbdayvote" min="10" max="45" value="45" style="width: 40px;">
					</label>
					Fin de l'évaluation : <span id="previewenddatevote"></span>
					<?php //TODO : choisir l'heure de fin de vote ?>
				</li>


			<?php elseif ( $status == ATCF_Campaign::$campaign_status_vote || ( $status == ATCF_Campaign::$campaign_status_validated && $page_controler->get_campaign()->skip_vote() ) ): ?>
				<p id="desc-preview">
					<?php _e("Il est temps maintenant de passer aux choses s&eacute;rieuses.", 'yproject'); ?>
					<?php _e("&Ecirc;tes-vous pr&ecirc;t(e) &agrave; lancer votre lev&eacute;e de fonds ?", 'yproject'); ?>
				</p>
				<li>
					<label>
						<?php
						$vote_can_go_next = $page_controler->get_campaign()->can_go_next_status();
						?>
						<input type="checkbox" class="checkbox-next-status" id="cbcannext" disabled <?php checked( $vote_can_go_next ); ?>>
						WE DO GOOD a autoris&eacute; mon passage en lev&eacute;e de fonds
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						Je suis pr&ecirc;t(e) &agrave; devenir le premier investisseur de mon projet 
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						J'ai pr&eacute;par&eacute; des mails &agrave; envoyer &agrave; l'ensemble de mes contacts et des publications pour les r&eacute;seaux sociaux
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" class="checkbox-next-status">
						J'ai planifi&eacute; des rencontres et des prises de contact pour parler de mon projet et de ma levée de fonds &agrave; mes proches et &agrave; mon r&eacute;seau
					</label>
				</li>
				<li>
					<label>
						Nombre de jours de la collecte :
						<input type="number" id="innbdaycollecte" name="innbdaycollecte" min="1" max="60" value="30" style="width: 40px;">
					</label>
					Fin de la collecte : <span id="previewenddatecollecte"></span>
				</li>
				<li>
					<label>
						La lev&eacute;e de fonds se terminera &agrave; :
						<input type="number" id="inendh" name="inendh" min="0" max="23" value="12" style="width: 40px;">h
						<input type="number" id="inendm" name="inendm" min="0" max="59" value="00" style="width: 40px;">
					</label>
					<?php DashboardUtility::get_infobutton("Veillez &agrave; d&eacute;finir l'heure de fin &agrave; un moment o&ugrave; vous pourrez toucher des investisseurs et encore mener des action de communication. Nous vous conseillons 21h.",true); ?>
				</li>
			<?php endif; ?>
		</ul>

		<div class="list-button align-center">
			<?php if ($status == ATCF_Campaign::$campaign_status_preparing): ?>
				<?php if ( $page_controler->can_access_admin() ): ?>
				<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
				<button type="submit" name="validation-next-save" value="1" id="submit-go-next-status-admin" class="button admin-theme">Enregistrer le statut</button><br /><br />
				<?php endif; ?>

				<?php if ( $is_waiting_for_comitee ): ?>
					Dossier en attente de validation par le Comit&eacute; de s&eacute;lection.<br />
					<?php if ( $page_controler->can_access_admin() ): ?>
						<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
						<button type="submit" name="validation-next-validate" value="1" id="submit-go-next-status-admin" class="button admin-theme">Valider le projet</button>
					<?php endif; ?>
				<?php endif; ?>

			<?php elseif ( $status == ATCF_Campaign::$campaign_status_validated && !$page_controler->get_campaign()->skip_vote() ): ?>
				<?php if ( $page_controler->can_access_admin() ): ?>
				<?php DashboardUtility::get_admin_infobutton( TRUE ); ?>
				<button type="submit" name="validation-next-save" value="1" id="submit-go-next-status-admin" class="button admin-theme">Enregistrer le statut</button><br /><br />
				<?php endif; ?>

				<input type="submit" value="Publier mon projet en évaluation !" class="button red" id="submit-go-next-status">

			<?php elseif ( $status == ATCF_Campaign::$campaign_status_vote || ( $status == ATCF_Campaign::$campaign_status_validated && $page_controler->get_campaign()->skip_vote() ) ): ?>
				<input type="submit" value="Lancer ma lev&eacute;e de fonds !" class="button red" id="submit-go-next-status">
			<?php endif; ?>
		</div>

		<input type="hidden" name="next_status" value="2" id="next-status-choice">
	</form>
	<hr class="form-separator">
	<?php endif; ?>

	<?php if ( $page_controler->can_access_admin() ): ?>
	<form action="" id="statusmanage_form" class="ajax-db-form db-form v3 full center" data-action="save_project_status">
		<?php
		DashboardUtility::create_field(array(
			'id'			=> 'new_campaign_status',
			'type'			=> 'select',
			'label'			=> "Changer l'&eacute;tape actuelle de la levée de fonds",
			'value'			=> $status,
			'editable'		=> $page_controler->can_access_admin(),
			'admin_theme'	=> $page_controler->can_access_admin(),
			'visible'		=> $page_controler->can_access_admin(),
			'options_id'	=> array_keys( $status_list ),
			'options_names'	=> array_values( $status_list ),
			'warning'		=> true
		));

		?>
		<?php if ( $remaining_check ): ?>
			<div class="field admin-theme">
			Attention : Il y a des chèques en attente de validation avant de pouvoir autoriser à passer à l'étape d'investissement !
			</div>
		<?php endif; ?>
		<?php
		DashboardUtility::create_field(array(
			"id"			=> 'new_can_go_next_status',
			"type"			=> 'check',
			"label"			=> "Autoriser &agrave; passer &agrave; l'&eacute;tape suivante",
			"value"			=> $page_controler->get_campaign()->can_go_next_status(),
			"editable"		=> $page_controler->can_access_admin(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"visible"		=> $page_controler->can_access_admin() && $validated_or_after && !$remaining_check 
		));

		DashboardUtility::create_save_button( 'statusmanage-form', $page_controler->can_access_admin(), 'Enregistrer', 'Enregistrement', TRUE );
		?>
	</form>
	<?php endif; ?>
	
	<h2><?php _e( "Planning de lev&eacute;e de fonds", 'yproject' ); ?></h2>
	<form action="" id="campaign_form" class="ajax-db-form db-form v3 full center" data-action="save_project_campaigntab">
		<ul class="errors">

		</ul>
		<?php
		DashboardUtility::create_field(array(
			"id"			=> "new_end_vote_date",
			"type"			=> "datetime",
			"label"			=> "Date de fin d'évaluation",
			"value"			=> new DateTime($page_controler->get_campaign()->end_vote_date()),
			"editable"		=> $page_controler->can_access_admin(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"warning"		=> true,
			"visible"		=> $page_controler->can_access_admin() || $vote_or_after
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_begin_collecte_date",
			"type"			=> "datetime",
			"label"			=> "Date de d&eacute;but de collecte",
			"value"			=> new DateTime($page_controler->get_campaign()->begin_collecte_date()),
			"editable"		=> false,
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin(),
			"warning"		=> true,
			"visible"		=> $page_controler->can_access_admin() || $collecte_or_after
		));

		DashboardUtility::create_field(array(
			"id"			=> "new_end_collecte_date",
			"type"			=> "datetime",
			"label"			=> "Date de fin de collecte",
			"value"			=> new DateTime($page_controler->get_campaign()->end_date()),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"editable"		=> $page_controler->can_access_admin(),
			"warning"		=> true,
			"visible"		=> $page_controler->can_access_admin() || $collecte_or_after
		));

		DashboardUtility::create_save_button( 'campaign_form', $page_controler->can_access_admin(), 'Enregistrer', 'Enregistrement', TRUE );
		?>
	</form>


	<?php if ( $page_controler->can_access_admin() && $status == ATCF_Campaign::$campaign_status_archive ): ?>

		<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=refund_investors'); ?>" class="align-center admin-theme-block">

			<br />
			<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>" />
			<button type="submit" class="button"><?php _e( "Rembourser les investisseurs", 'yproject' ); ?></button>
			<br /><br />

		</form>
		<br /><br />

	<?php endif; ?>
		
	
	<?php 
	$finished_declarations = $page_controler->get_campaign()->get_roi_declarations_by_status( WDGROIDeclaration::$status_finished );
	$nb_finished_declarations = count( $finished_declarations );
	$roi_percent = $page_controler->get_campaign()->roi_percent();
	$campaign_id = $page_controler->get_campaign()->ID;
	$investment_results = WDGCampaignInvestments::get_list( $campaign_id );
	?>
	<h2><?php _e('Situation', 'yproject'); ?></h2>
	<ul>
		<li><strong><?php echo UIHelpers::format_number( $page_controler->get_campaign_organization()->get_lemonway_balance( 'campaign' ) ); ?> €</strong> <?php _e( "dans votre porte-monnaie de projet", 'yproject' ); ?></li>
		<li><strong><?php echo UIHelpers::format_number( $page_controler->get_campaign()->current_amount( false ) ); ?> €</strong> <?php _e( "lev&eacute;s", 'yproject' ); ?></li>
		<li><strong><?php echo UIHelpers::format_number( $investment_results[ 'amount_not_validate_investments' ] ); ?> €</strong> <?php _e( "en attente de validation", 'yproject' ); ?></li>

		<?php if ( $roi_percent > 0 ): ?>
			<li><strong><?php echo $page_controler->get_campaign()->roi_percent(); ?> %</strong> <?php _e( "du CA &agrave; verser pendant", 'yproject' ); ?> <strong><?php echo $page_controler->get_campaign()->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?></strong></li>
			<?php if ( $page_controler->get_campaign()->roi_percent_remaining() != $roi_percent ): ?>
				<li><?php _e( "Suite &agrave; des modifications sur vos contrats, il restera" ); ?> <strong><?php echo $page_controler->get_campaign()->roi_percent_remaining(); ?> %</strong> <?php _e( "du CA &agrave; verser.", 'yproject' ); ?></li>
			<?php endif; ?>
		<?php else: ?>
			<li><strong><?php echo $page_controler->get_campaign()->roi_percent_estimated(); ?> %</strong> <?php _e( "maximum du CA &agrave; verser pendant", 'yproject' ); ?> <strong><?php echo $page_controler->get_campaign()->funding_duration(); ?> <?php _e( "ans", 'yproject' ); ?></strong></li>
		<?php endif; ?>

		<li><strong><?php echo count( $finished_declarations ); ?> / <?php echo $page_controler->get_campaign()->get_roi_declarations_number(); ?></strong> <?php _e( "&eacute;ch&eacute;ances", 'yproject' ); ?></li>
		<li>
			<strong><?php echo $page_controler->get_campaign()->get_roi_declarations_total_turnover_amount(); ?> €</strong> <?php _e( "de CA d&eacute;clar&eacute;", 'yproject' ); ?>
		</li>
		<li>
			<strong><?php echo $page_controler->get_campaign()->get_roi_declarations_total_roi_amount(); ?> €</strong> <?php _e( "de royalties vers&eacute;es", 'yproject' ); ?>
		</li>
	</ul>
	<br>


	<h2><?php _e('Historique', 'yproject'); ?></h2>
	<?php if ( $nb_finished_declarations > 0 ): ?>

		<ul>
		<?php foreach( $finished_declarations as $declaration_item ): ?>
			<li>Déclaration du <?php echo $declaration_item->date_due; ?> : <?php echo $declaration_item->get_amount_with_adjustment(); ?> € de royalties
				<?php if ( $declaration_item->get_amount_with_adjustment() > 0 ): ?>
				payées le <?php echo $declaration_item->get_formatted_date( 'paid' ); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>

	<?php endif; ?>

	<?php $transfers = $page_controler->get_campaign_organization()->get_transfers();
	if ($transfers) : ?>

		<h3>Transferts vers votre compte :</h3>
		<ul>
			<?php
			foreach ( $transfers as $transfer_post ) :
				$post_status = ypcf_get_updated_transfer_status($transfer_post);
				$transfer_post = get_post($transfer_post);
				$post_amount = $transfer_post->post_title;
				$post_date = new DateTime($transfer_post->post_date);
				// Les versements faits via Mangopay doivent être recalculés
				if ( $post_date < new DateTime('2016-07-01') ) {
					$post_amount /= 100;
				}
				$status_str = 'En cours';
				if ($post_status == 'publish') {
					$status_str = 'Termin&eacute;';
				} else if ($post_status == 'draft') {
					$status_str = 'Annul&eacute;';
				}
				?>
				<li id="<?php echo $transfer_post->post_content; ?>"><?php echo $transfer_post->post_date; ?> : <?php echo UIHelpers::format_number( $post_amount ); ?>&euro; -- Termin&eacute;</li>
				<?php
			endforeach;
			?>
		</ul>

	<?php else: ?>
		<?php _e( "Aucun transfert d&apos;argent.", 'yproject' ); ?>
	<?php endif; ?>
	<br><br>


	<h2><?php _e( 'Porte-monnaie', 'yproject' ); ?></h2>
	<?php // Porte-monnaie LW ?>
	<?php $lemonway_balance = $page_controler->get_campaign_organization()->get_lemonway_balance( 'campaign' ); ?>
	Vous disposez de <?php echo $lemonway_balance; ?>&euro; dans votre porte-monnaie.<br /><br />

	<?php if ( $page_controler->can_access_admin() && $lemonway_balance > 0 ): ?>

		<?php if ( $page_controler->is_iban_validated() ): ?>
			<div class="db-form">
				Ce formulaire n'est accessible qu'en administration :<br />
				<form action="" method="POST" class="field admin-theme">
					<input type="hidden" name="submit_transfer_wallet_lemonway" value="1" />
					Somme à verser au porteur de projet : <input type="text" name="transfer_amount" value="0" /><br />
					Somme à prendre en commission : <input type="text" name="transfer_commission" value="0" /><br />
					<input type="submit" value="Verser" />
				</form>
			</div>

		<?php else: ?>
			Le RIB de l'organisation n'est pas prêt

		<?php endif; ?>

	<?php endif; ?>
</div>
