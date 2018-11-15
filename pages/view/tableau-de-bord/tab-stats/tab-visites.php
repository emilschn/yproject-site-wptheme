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

<!-- ONGLET VISITES -->
<div id="stat-subtab-visites" class="stat-subtab hidden">

	<!-- Courbe "Visiteurs + Votes + Investissements + Pré-investissements" -->
	<section>
		<h3>Visites sur l'ensemble de la levée de fonds</h3>
	  <div class="chart-container">
		<canvas id="visit-chart"></canvas>
	  </div>
	</section>

	<section>
	  <div class="grid-2-small-1 has-gutter">

		<!-- Tableau des provenances -->
		<div>
		  <h3>Provenances</h3>
			<table class="tablo" id="cities-tab">
				<tr class="txt-center">
					<th width="50%">Villes</th>
					<th width="20%">Visites</th>
				<th width="20%">% Visites</th>
				</tr>
			</table>
			<a id="more-cities" onclick="$('#cities-tab>tbody>tr').css('display','');$('#more-cities').css('display','none');">Afficher plus de villes</a>
		</div>

		<!-- Graphique "Principaux canaux" -->
		<div>
		  <h3>Principaux canaux</h3>
		  <div class="chart-container">
			<canvas id="chanel-chart" width="600"></canvas>
		  </div>
		</div>

	  </div>
	</section>

	<script>
	  var tab = 'visites';
	</script>

</div>