
<!doctype html>
<html lang="fr">
<head>
        <meta charset="UTF-8" />
        <title>Mon premier formulaire HTML</title>
</head>
<body>

	<div id="content">
		<div class="padder">

			<div class="page" id="contact-page">

				<form method=post action="contact.php">
					<h2>Envoyez nous un mail</h2>
					
	    			<label for="nom">Nom : </label>
	              	<input type="text" id="nom"/>
	              	<label for="prenom">Prénom : </label>
	              	<input type="text" id="prenom"/>
	    			<label for="email">e-mail : </label>
	    			<input type="text" id="email"/>
	    			<textarea rows="3" name="message">
						Tapez ici votre message
					</textarea>

					<INPUT type="submit" value="Envoyer">
				</from>
				
			</div>

		</div>

	</div>
</body>