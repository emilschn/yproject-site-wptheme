<?php global $campaign, $stylesheet_directory_uri; ?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<p class="align-center">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-stat-loupe.png" width="150">
	</p>
	<br>
	
	<div class="align-justify">
		<?php _e( "Bonjour,", 'yproject' ); ?><br>
		<?php echo sprintf( __( "Vous &ecirc;tes sur le point d'acc&eacute;der &agrave; la pr&eacute;sentation du projet %s sur WE DO GOOD, plateforme d'investissement en &eacute;change de royalties.", 'yproject' ), $campaign->data->post_title ); ?><br>
		<br>
		
		<?php _e( "Vous pouvez acc&eacute;der &agrave; la pr&eacute;sentation du projet sans investir, mais la r&eacute;glementation et notre &eacute;thique nous imposent de vous informer que l'investissement dans des soci&eacute;t&eacute;s non cot&eacute;es comporte des risques sp&eacute;cifiques :", 'yproject' ); ?><br>
		<?php _e( "&gt; Le retour sur investissement d&eacute;pend de la r&eacute;ussite du projet financ&eacute;.", 'yproject' ); ?><br>
		<?php _e( "&gt; Risque de perte totale ou partielle du capital investi.", 'yproject' ); ?><br>
		<br>

		<?php _e( "Ceci est normal : l'aventure entrepreneuriale est risqu&eacute;e et peut &eacute;chouer, mais cela vaut le coup d'essayer pour faire &eacute;merger des id&eacute;es qui am&eacute;liorent le monde dans lequel nous vivons.", 'yproject' ); ?><br>
		<br>

		<?php _e( "Donc si vous souhaitez y participer et d&eacute;cidez d'investir, n'investissez que de l'argent dont vous n'avez pas besoin.", 'yproject' ); ?>
		<?php _e( "Sinon, vous pouvez simplement donner votre avis sur les projets.", 'yproject' ); ?><br>
		<br>

		<?php _e( "WE DO GOOD est agr&eacute;&eacute;e par l'ORIAS et membre de l'association professionnelle Financement Participatif France.", 'yproject' ); ?>
		<?php _e( "Pour les paiements, nous utilisons les services de Lemon Way, &eacute;tablissement de paiement agr&eacute;&eacute; par l'ACPR.", 'yproject' ); ?><br>
		<br>
		
		<strong><?php _e( "Avez‐vous conscience que, dans le cas o&ugrave; vous investissez, vous pouvez perdre &eacute;ventuellement la totalit&eacute; de votre investissement ?", 'yproject' ); ?></strong><br>
		<br>

		<form class="db-form v3">
			<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investissement' ); ?>" class="button half left transparent"><?php _e( "Non / En savoir plus", 'yproject' ); ?></a>
			<button type="button" class="button half right close red" data-close="project-warning"><?php _e( "Oui / Continuer", 'yproject' ); ?></button>
		</form>
		<div class="clear">
			<br><br>
		</div>
	</div>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="project-warning" title="'.__( "Avertissement", 'yproject' ).'" autoopen="0" catchclick="0"]' . $lightbox_content . '[/yproject_lightbox_cornered]');