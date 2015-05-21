<?php function print_investments($investments_list, $is_advanced = FALSE) { ?>

<h3>G&eacute;n&eacute;ral</h3>
<strong><?php echo $investments_list['count_validate_investments']; ?></strong> investissements valid&eacute;s.<br />
Les investisseurs ont <strong><?php echo $investments_list['average_age']; ?></strong> ans de moyenne.<br />
Ce sont <strong><?php echo $investments_list['percent_female']; ?>%</strong> de femmes et <strong><?php echo $investments_list['percent_male']; ?>%</strong> d&apos;hommes.<br />
<strong><?php echo $investments_list['campaign']->days_remaining(); ?></strong> jours restants.<br />
Investissement moyen par personne : <strong><?php echo $investments_list['average_invest']; ?></strong>&euro;<br />
Investissement m&eacute;dian : <strong><?php echo $investments_list['median_invest']; ?></strong>&euro;

<?php if ($is_advanced === TRUE): ?>
<br /><br />
Total des investissements par ch&egrave;que : <strong><?php echo $investments_list['amount_check']; ?></strong><br />
<?php endif; ?>

<h3>Ils ont investi</h3>
<?php echo $investments_list['investors_string']; ?><br />

<?php } ?>