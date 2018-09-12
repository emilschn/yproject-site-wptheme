<?php
global $stylesheet_directory_uri, $campaign_stats;
$goal = $campaign_stats['goal']; // objectif financier de la campagne
$average_median_for_campaign = $campaign_stats['average_median_for_campaign']; // montant de la campagne moyenne

//--- Data évaluations ---//
$vote = $campaign_stats['vote']; // données de la vue évaluations

$vote_list_vote = $vote['list_vote']; // données journalières : montants des intentions d'investissements
$vote_list_preinvestement = $vote['list_preinvestement']; // données journalières : montants des pré-investissements

$vote_nb = $vote['nb']; // données nombre d'évaluateurs
$vote_nb_intent = $vote['nb_intent']; // données nombre d'intentions d'investissement
$vote_amount_intent = $vote['amount_intent']; // données valeur intentions d'investissement
$vote_nb_preinvestment = $vote['nb_preinvestment']; // données nombre de pré-investissements
$vote_amount_preinvestment = $vote['amount_preinvestment']; // données valeurs de pré-investissements
$vote_rates = $vote['rates']; // données impact et cohérence du projet
$vote_rates_project = $vote_rates['project']; // données notation du projet
$vote_risk = $vote['risk']; // données rique
$vote_more_info = $vote['more_info']; // liste des 'autres informations'
?>

<!-- ONGLET EVALUATIONS -->
<div id="stat-subtab-evaluations" class="stat-subtab">

	<!-- Résumé -->
	<section id="eval-resume">
	  <div class="grid-7-small-1 has-gutter">
		<div class="col-3-small-1">
				<div class="grid-3 has-gutter">
					<div id="resume-part" class="txt-center">
						<span class="txt-big remplissage" id="nb-evals"><?php echo $vote_nb['current']; ?></span>
						<span>évaluateurs</span>
					<div id="masque">
						<div>
						<div class="pastille-bleu" id="nb-intent-invest"><?php echo $vote_nb_intent['current']; ?></div>
						<div class="line-bleu"></div>
					</div>
				  </div>
				</div>
				<div class="col-2-small-1">
						<p class="nb-intention">personnes ont déclaré<br> qu'elles investiraient<br> en moyenne <b><?php echo $vote['average_intent'] . ' €'?></b></p>
				</div>
			</div>
		  </div>
		<div class="txt-center txt-big">=</div>
		<div class="col-3-small-1">
		  <div class="grid-3 has-gutter">
			<div>
			  <p><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-tableau-de-bord/icon-money.png"></p>
			</div>
			<div class="col-2-small-1">
			  <p><b><?php echo $vote_amount_intent['current'] . ' €';?></b> d’intentions d’investissement soit <b><?php echo $vote['percent_intent'] . ' %';?> </b> de l’objectif (<?php echo $goal . ' €'?>).</p>
			</div>
			 </div>
		 </div>
	   </div>
	</section>

	<!-- Tableau des objectifs -->
	<section>
	  <h3>Tableau des objectifs</h3>
	  <table class="tablo">
		<tr class="txt-center">
			<th width="30%">&nbsp;</th>
			<th width="10%">En cours</th>
			<th width="20%">Minimum pour passer en levée de fonds</th>
			<th width="20%">Moyenne pour une collecte de <?php echo $average_median_for_campaign; ?> €</th>
			<th width="20%">Médiane pour une collecte de <?php echo $average_median_for_campaign; ?> €</th>
		</tr>
		<tr class="txt-center">
			<td>Nb d’évaluateur</td>
			<td <?php if ($vote_nb['current'] >= $vote_nb['min']) { ?> class="min-ok" <?php } ?>><?php echo $vote_nb['current']; ?></td>
			<td><?php echo $vote_nb['min']; ?></td>
			<td><?php echo $vote_nb['average']; ?></td>
			<td><?php echo $vote_nb['median']; ?></td>
		</tr>
		<tr class="txt-center">
			<td>Nb intentions d’investissement</td>
			<td <?php if ($vote_nb_intent['current'] >= $vote_nb_intent['min']) { ?> class="min-ok" <?php } ?>><?php echo $vote_nb_intent['current']; ?></td>
			<td><?php echo $vote_nb_intent['min']; ?></td>
			<td><?php echo $vote_nb_intent['average']; ?></td>
			<td><?php echo $vote_nb_intent['median']; ?></td>
		</tr>
		<tr class="txt-center">
			<td>Valeur intentions d’investissement</td>
			<td <?php if ($vote_amount_intent['current'] >= $vote_amount_intent['min']) { ?> class="min-ok" <?php } ?>><?php echo $vote_amount_intent['current'] . ' €'; ?></td>
			<td><?php echo $vote_amount_intent['min'] . ' €'; ?></td>
			<td><?php echo $vote_amount_intent['average'] . ' €'; ?></td>
			<td><?php echo $vote_amount_intent['median'] . ' €'; ?></td>
		</tr>
		<tr class="txt-center">
			<td>Nb pré-investissement</td>
			<td <?php if ($vote_nb_preinvestment['current'] >= $vote_nb_preinvestment['min']) { ?> class="min-ok" <?php } ?>><?php echo $vote_nb_preinvestment['current']; ?><?php if ($vote_nb_preinvestment['current'] < $vote_nb_preinvestment['min']) { ?><br><span class="reste">(plus que <?php echo $vote_nb_preinvestment['min'] - $vote_nb_preinvestment['current']; ?>)</span><?php } ?></td>
			<td><?php echo $vote_nb_preinvestment['min']; ?></td>
			<td><?php echo $vote_nb_preinvestment['average']; ?></td>
			<td><?php echo $vote_nb_preinvestment['median']; ?></td>
		</tr>
		<tr class="txt-center">
			<td>Valeur pré-investissement</td>
			<td <?php if ($vote_amount_preinvestment['current'] >= $vote_amount_preinvestment['min']) { ?> class="min-ok" <?php } ?>><?php echo $vote_amount_preinvestment['current'] . ' €'; ?></td>
			<td><?php echo $vote_amount_preinvestment['min'] . ' €'; ?></td>
			<td><?php echo $vote_amount_preinvestment['average'] . ' €'; ?></td>
			<td><?php echo $vote_amount_preinvestment['median'] . ' €'; ?></td>
		</tr>
	  </table>
	</section>

	<!-- Courbe "Valeurs des pré-investissements et intentions d'investissements" -->
	<section>
		<h3>Valeurs des pré-investissements et intentions d'investissements</h3>
	  <div class="chart-container">
		<canvas id="preinvestment-intent-values-chart"></canvas>
	  </div>
	</section>

	<!-- Courbe des objectifs -->
	<section>
	  <h3>Courbes des objectifs</h3>
	  <div class="chart-container">
		<canvas id="goal-chart"></canvas>
	  </div>
	</section>

	<!-- Impacts et Risque -->
	<section>
	  <div class="grid-2-small-1 has-gutter">
		<!-- Impact et cohérence du projet -->
		<div id="impact-global">
		  <h3>Impact et cohérence du projet</h3>
		  <div class="grid-12-small-1 jauge-list">

					<!-- jauge économie-->
					<p class="col-4"><span>Économie</span></p>
					<div class="impact col-7">
						<div class="grid-6-small-6 grad-value">
							<div><span>0</span></div>
							<div><span>1</span></div>
							<div><span>2</span></div>
							<div><span>3</span></div>
							<div><span>4</span></div>
							<div><span>5</span></div>
						</div>
			  <!-- progress bar économie -->
						<div class="impact-jauge grid-6-small-6">
							<span class="grad-default-blue"></span>
							<div class="col-5">
								<span class="grad-bar" style="width:<?php echo $vote_rates['economy'] * 20; ?>%;"></span>
							</div>
						</div>
						<div class="grid-6-small-6 graduate">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
						<img class="impact-icon"src="<?php echo $stylesheet_directory_uri; ?>/images/template-tableau-de-bord/impact-eco.png">
					</div>
					<p class="col-1"><span><?php echo $vote_rates['economy']; ?></span></p>
				</div>

				<!-- jauge environnement -->
				<div class="grid-12-small-1 jauge-list">
					<p class="col-4"><span>Environnement</span></p>
					<div class="impact col-7">
						<div class="grid-6-small-6 graduate">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
			  <!-- progress bar environnement -->
						<div class="impact-jauge grid-6-small-6">
							<span class="grad-default-blue"></span>
							<div class="col-5">
								<span class="grad-bar" style="width:<?php echo $vote_rates['environment'] * 20; ?>%;"></span>
							</div>
						</div>
						<div class="grid-6-small-6 graduate">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
						<img class="impact-icon"src="<?php echo $stylesheet_directory_uri; ?>/images/template-tableau-de-bord/impact-env.png">
					</div>
					<p class="col-1"><span><?php echo $vote_rates['environment']; ?></span></p>
				</div>

				<!-- jauge social -->
				<div class="grid-12-small-1 jauge-list">
					<p class="col-4"><span>Social</span></p>
					<div class="impact col-7">
						<div class="grid-6-small-6 graduate">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>

			  <!-- progress bar social -->
						<div class="impact-jauge grid-6-small-6">
							<span class="grad-default-blue"></span>
							<div class="col-5">
								<span class="grad-bar" style="width:<?php echo $vote_rates['social'] * 20; ?>%;"></span>
							</div>
						</div>
						<div class="grid-6-small-6 graduate">
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
							<span></span>
						</div>
						<img class="impact-icon"src="<?php echo $stylesheet_directory_uri; ?>/images/template-tableau-de-bord/impact-social.png">
					</div>
					<p class="col-1"><span><?php echo $vote_rates['social']; ?></span></p>
				</div>

				<!-- Note attribué au projet -->
				<div id="notation">
					<div class="grid-12-small-1">
						<div class="col-4">
							<p><span>Note attribué<br> au projet</span></p>
						</div>
						<div class="col-7">
							<div class="grid-5-small-1 note-dot">
								<div><span></span></div>
								<div><span></span></div>
								<div><span></span></div>
								<div><span></span></div>
								<div><span class="note-half"></span></div>
							</div>
						</div>
						<div class="col-1"><p><span><?php echo $vote_rates_project['average']; ?></span></p></div>
					</div>
					<div class="grid-12-small-1">
						<div class="col-4">
							<p>&nbsp;</p>
						</div>
						<div class="col-8"><p><?php echo $vote_rates_project['positive_percent']; ?> % des évaluateurs ont donné un avis positif (note supérieure à 2)</p></div>
					</div>
				</div>
			</div>

		<!-- Risque -->
			<div>
		  <h3>Risque</h3>
				<p>Les <b><?php echo $vote_nb['current']; ?> évaluateurs</b> ont évalué le risque, en moyenne, à <b><?php echo $vote_risk['average']; ?>/5</b></p>
		  <div class="chart-container">
			<canvas id="risk-chart"></canvas>
		  </div>
			</div>

		</div>
	</section>

	<!-- Remarques -->
	<section>
		<h3>Remarques</h3>
		<div class="grid-2 has-gutter">

		<!-- Cerles "plus d'infos" -->
			<div>
				<p><b>Les internautes aimeraient avoir plus d’informations sur :</b></p>
				<div id="chart-infos" class="chart"></div>
			</div>

		<!-- Liste "autres informations" -->
			<div>
				<p><b>Autres informations :</b></p>
		  <?php foreach ($vote_more_info['others'] as $user => $info){ ?>
			<div class="remarque-sup">
					<p><?php echo $info; ?></p>
					<p><?php echo $user; ?></p>
				</div>
		  <?php } ?>
			</div>

		</div>
	</section>

	<p class="txt-center"><a class="btn-new-blue" href="#contacts">Afficher les conseils de vos contacts</a></p>

	<script>
	  // Gestion du remplissage de la jauge du nombre d'évaluateurs
	  var nbEvals = $('#nb-evals').text(); //on récupère la val du nombre d'évaluateurs
	  var nbIntentInvest = $('#nb-intent-invest').text(); // on récupère le nombre d'intentions d'investissement
	  var percentIntentInvest = 100 * nbIntentInvest / nbEvals + '%'; // calcul du pourcentage d'intentions d'investissement / évaluateurs
	  var invertPercentIntentInvest = 100 - 100 * nbIntentInvest / nbEvals + '%'; // pourcentage restant pour arriver à 100%
	  $('.pastille-bleu').css('bottom',percentIntentInvest);
	  $('.line-bleu').css('bottom',percentIntentInvest);
	  $('.remplissage').css('background','linear-gradient(to bottom, #000333 ' + invertPercentIntentInvest + ', #93626d ' + invertPercentIntentInvest + ')');
	  $('.remplissage').css('-webkit-background-clip','text');
	  $('.remplissage').css('-webkit-text-fill-color','transparent');
	</script>

	<script>
	  var tab = 'evaluations';
	</script>

</div>