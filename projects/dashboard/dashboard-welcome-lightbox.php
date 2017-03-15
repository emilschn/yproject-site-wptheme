<?php global $stylesheet_directory_uri, $campaign ?>
<div>
	<div id="welcome-part-1">
		<h1 style="text-align:center">Bienvenue !</h1>
		<p>Vos informations ont bien été enregistrées.</p>

		<p>Accédez à tout moment à votre projet via le bouton "MON COMPTE" dans le menu principal du site :</p>
		<img style="max-width: 100%;" src="<?php echo $stylesheet_directory_uri; ?>/images/apercu-mon-projet.png" alt="" /><br />

		<p class="align-center">
			<a class="button red">Continuer</a>
		</p>
	</div>

	<div id="welcome-part-2" class="hidden">
		<p>Complétez dès maintenant votre dossier. Notre équipe vous contactera prochainement.</p>

		<p>Toutes les informations communiquées à WE DO GOOD sont gardées confidentielles.</p>

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
</script>