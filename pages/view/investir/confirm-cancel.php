<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 full">
	<p class="align-justify">
		<?php _e( "Votre investissement a bien &eacute;t&eacute; annul&eacute;.", 'yproject' ); ?><br />
		<?php _e( "Si vous aviez pay&eacute; par carte bancaire, l'argent a &eacute;t&eacute; revers&eacute; sur votre compte bancaire.", 'yproject' ); ?><br />
		<?php _e( "Si vous aviez pay&eacute; par virement ou porte-monnaie électronique, l'argent est vers&eacute; sur votre porte-monnaie &eacute;lectronique. Rendez-vous sur votre compte personnel.", 'yproject' ); ?><br />
		<?php _e( "Si vous aviez pay&eacute; par ch&egrave;que, l'argent ne sera pas retir&eacute;, le ch&egrave;que sera annul&eacute;.", 'yproject' ); ?><br />
	</p>
	<br>
	<a href="<?php echo home_url( '/les-projets/' ); ?>" class="button transparent"><?php _e( "Voir les projets en cours", 'yproject' ); ?></a>
	<br><br>
	<a href="<?php echo home_url( '/investir/' ) . '?campaign_id=' .$page_controler->get_current_campaign()->ID. '&invest_start=1'; ?>" class="button red"><?php _e( "Investir un autre montant", 'yproject' ); ?></a>
</div>
