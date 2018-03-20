<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<h2><?php _e( "Pr&eacute;sentation", 'yproject' ); ?></h2>
<div class="db-form v3 center">
	<br>
	<p class="align-justify">
		<?php _e( "La visualisation et l'&eacute;dition de votre pr&eacute;sentation se fait directement sur la page qui sera publi&eacute;e.", 'yproject' ); ?>
	</p>
	<br><br>
	
	<a href="<?php echo $page_controler->get_campaign_url(); ?>" class="button red"><?php _e( "Aller &agrave; la pr&eacute;sentation", 'yproject' ); ?></a>
</div>