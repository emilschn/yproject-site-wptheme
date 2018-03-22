<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<h2><?php _e( "Guide et outils", 'yproject' ); ?></h2>
<div class="db-form v3 center">
	<br>
	<p class="align-justify">
		<?php _e( "Le guide de campagne vous met &agrave; disposition des conseils pratiques sur tous les aspects de votre communication pour votre campagne.", 'yproject' ); ?><br>
		<?php _e( "Nous sommes aussi disponibles via le chat en ligne ou &agrave; l'adresse suivante : support@wedogood.co.", 'yproject' ); ?><br>
	</p>
	<br><br>
	
	<a href="<?php echo home_url( '/guide' ); ?>" class="button red" target="_blank"><?php _e( "Consulter le guide", 'yproject' ); ?></a>
</div>