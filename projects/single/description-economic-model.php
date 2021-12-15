<?php global $campaign; ?>
<p>
	Modèle économique...!<br>
	<br>
</p>
<p>
	CA de l'année précédente : <?php echo UIHelpers::format_number( $campaign->turnover_previous_year() ); ?> €<br>
	Montant déjà réuni : <?php echo UIHelpers::format_number( $campaign->total_previous_funding() ); ?> €<br>
	<?php echo html_entity_decode( $campaign->total_previous_funding_description() ); ?><br>
</p>