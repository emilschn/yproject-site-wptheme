<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

<h2 class="underlined"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></h2>

<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms db-form v3 full center">
	<label for="holdername" class="large-label"><?php _e( "Nom du propri&eacute;taire du compte :", 'yproject' ); ?></label>
		<input type="text" id="holdername" name="holdername" value="<?php echo $WDGUser_displayed->get_bank_holdername(); ?>">
		<br>
	<label for="address" class="large-label"><?php _e( "Adresse du compte :", 'yproject' ); ?></label>
		<input type="text" id="address" name="address" value="<?php echo $WDGUser_displayed->get_bank_address(); ?>">
		<br>
	<label for="address2" class="large-label"><?php _e( "Pays :", 'yproject' ); ?></label>
		<input type="text" id="address2" name="address2" value="<?php echo $WDGUser_displayed->get_bank_address2(); ?>">
		<br>
	<label for="iban" class="large-label"><?php _e( "IBAN :", 'yproject' ); ?></label>
		<input type="text" id="iban" name="iban" value="<?php echo $WDGUser_displayed->get_bank_iban(); ?>">
		<br>
	<label for="bic" class="large-label"><?php _e( "BIC :", 'yproject' ); ?></label>
		<input type="text" id="bic" name="bic" value="<?php echo $WDGUser_displayed->get_bank_bic(); ?>">
		<br>
	<label for="rib" class="large-label"><?php _e( "Fichier de votre RIB :", 'yproject' ); ?></label>
		<input type="file" id="rib" name="rib">
		<br>
	<span class="file-description">
		<?php _e( "Le fichier doit avoir une taille inf&eacute;rieure à 10 Mo.", 'yproject' ); ?>
		<br>
		<?php _e( "Les formats de documents autoris&eacute;s sont : PDF, JPG, JPEG, BMP, GIF, TIF, TIFF et PNG.", 'yproject' ); ?></span>
		<br>
		<br>
		
	<input type="hidden" name="action" value="register_rib" />
	<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />

	<div>
		<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
	</div>
</form>