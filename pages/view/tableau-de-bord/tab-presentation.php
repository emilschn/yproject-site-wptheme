<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="db-form v3 center">
	<br>
	<p class="align-justify">
		<?php _e( "Vous pouvez visualiser et modifier la pr&eacute;sentation de votre projet directement sur la page qui sera publi&eacute;e.", 'yproject' ); ?>
	</p>
	<br><br>
	
	<a href="<?php echo $page_controler->get_campaign_url(); ?>" class="button red"><?php _e( "Allez &agrave; la pr&eacute;sentation", 'yproject' ); ?></a>
</div>