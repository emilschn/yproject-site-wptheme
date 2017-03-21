<?php global $stylesheet_directory_uri, $campaign ?>
<div>
	<div id="welcome-part-1">
		<h1 style="text-align:center">Bienvenue !</h1>
		<p>Vos informations ont bien été enregistrées.</p>

		<p>Accédez à tout moment à votre campagne via le bouton "MON COMPTE" dans le menu principal du site :</p>
		<img style="max-width: 100%;" src="<?php echo $stylesheet_directory_uri; ?>/images/apercu-mon-projet.png" alt="" /><br />

		<p class="align-center">
			<a class="button red">Continuer</a>
		</p>
	</div>

	<div id="welcome-part-2" class="hidden">
		<p>Si vous avez des questions, contactez-nous via le chat !</p>

		<p class="align-center">
			<img style="max-width: 100%;" src="<?php echo $stylesheet_directory_uri; ?>/images/apercu-chat.png" alt="" />
		</p>

		<p class="align-center">
			<a class="button red">Continuer</a>
		</p>
    </div>

	<div id="welcome-part-3" class="hidden">
		<p>D'ici votre prochain point avec notre équipe, complétez vos informations !</p>

		<p class="align-center">
			<a class="button red" id="wdg-lightbox-welcome-close">J'y vais</a>
		</p>
    </div>
</div>

<script type="text/javascript">
	$("#welcome-part-1 a").click(function() {
		$("#welcome-part-1").hide();
		$("#welcome-part-2").show();
	});
	$("#welcome-part-2 a").click(function() {
		$("#welcome-part-2").hide();
		$("#welcome-part-3").show();
	});
</script>