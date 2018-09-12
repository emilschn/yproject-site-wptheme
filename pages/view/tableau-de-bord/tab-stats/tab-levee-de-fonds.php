<?php
global $stylesheet_directory_uri, $campaign_stats;
$goal = $campaign_stats['goal']; // objectif financier de la campagne
$average_median_for_campaign = $campaign_stats['average_median_for_campaign']; // montant de la campagne moyenne

//--- Data levée de fonds ---//
$funding = $campaign_stats['funding']; // données de la vue levée de fonds

$funding_nb_investment = $funding['nb_investment']; // données nb dinvestissement
$funding_amount_investment = $funding['amount_investment']; // données valeur des investissements
$funding_stats = $funding['stats']; // statistiques supplémentaires
?>

<!-- ONGLET LEVEE DE FONDS -->
<div id="stat-subtab-leveedefonds" class="stat-subtab hidden">

	<!-- Tableau des objectifs -->
	<section>
		<h3>Tableau des objectifs</h3>
		<table class="tablo">
			<tr class="txt-center">
				<th width="30%">&nbsp;</th>
				<th width="20%">En cours</th>
				<th width="20%">Moyenne pour une collecte de <?php echo $average_median_for_campaign; ?> €</th>
				<th width="20%">Médiane pour une collecte de <?php echo $average_median_for_campaign; ?> €</th>
			</tr>
			<tr class="txt-center">
				<td>Nb d’investissements</td>
				<td><?php echo $funding_nb_investment['current']; ?></td>
				<td><?php echo $funding_nb_investment['average']; ?></td>
				<td><?php echo $funding_nb_investment['median']; ?></td>
			</tr>
			<tr class="txt-center">
				<td>Valeur des investissements</td>
			<td><?php echo $funding_amount_investment['current']; ?> €</td>
				<td><?php echo $funding_amount_investment['average']; ?> €</td>
				<td><?php echo $funding_amount_investment['median']; ?> €</td>
			</tr>
		</table>
	</section>

	<!-- Courbe "Valeur des investissements" -->
	<section>
		<h3>Valeurs des investissements</h3>
	  <div class="chart-container">
		<canvas id="investment-chart"></canvas>
	  </div>
	</section>

	<!-- Statistiques supplémentaires -->
	<section>
		<h3>Statistiques supplémentaires</h3>

		<div class="grid-2-small-1 has-gutter">

		<div class="chart-container" id="doooonuts">
			<p class="txt-center"><b><?php echo $funding_nb_investment['current']; ?></b></p>
				<p class="txt-center"><b>investissements validés</b><br> (<?php echo $funding_nb_investment['not_validated']; ?> investissement non-validés)</p>
			<canvas id="sup-stats-chart" width="600"></canvas>
		  <div id="circle-int" class="txt-center">
			<p><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-tableau-de-bord/picto-ensemble-noir-h100.png"></p>
			<p><b><?php echo $funding_nb_investment['current_different']; ?><br>investisseurs<br>distincts</b></p>
		  </div>
		</div>

			<div class="grid-2-small-2" id="stat-sup-color">
		  <div class="txt-center bg-purple">
					<p><?php echo $funding_stats['invest_average']; ?> €</p>
					<p>Investissement moyen / personne</p>
				</div>
				<div class="txt-center bg-purple-medium">
					<p><?php echo $funding_stats['invest_median']; ?> €</p>
					<p>Investissement médian</p>
				</div>
				<div class="txt-center bg-purple-light">
					<p><?php echo $funding_stats['invest_min']; ?> €</p>
					<p>Investissement minimum</p>
				</div>
				<div class="txt-center bg-purple-u-light">
					<p><?php echo $funding_stats['invest_max']; ?> €</p>
					<p>Investissement maximum</p>
				</div>
			</div>

		</div>

	</section>

	<script>
	  var tab = 'levee-de-fonds';
	</script>

</div>