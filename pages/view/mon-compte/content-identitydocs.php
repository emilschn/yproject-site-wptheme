<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();

$current_filelist_id = WDGKYCFile::get_list_by_owner_id($WDGUser_displayed->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id);
$current_file_id = $current_filelist_id[0];
$current_filelist_home = WDGKYCFile::get_list_by_owner_id($WDGUser_displayed->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home);
$current_file_home = $current_filelist_home[0];
?>

<h2 class="underlined"><?php _e( "Mes justificatifs d'identit&eacute;", 'yproject' ); ?></h2>

<form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="wdg-forms db-form v3 full center">
	<div class="field">
		<label for="org_doc_id"><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident*", 'yproject'); ?></label>
		<div class="field-container align-left">
			<span class="field-value">
				<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br />
				<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br />
				<?php if ( isset($current_file_id) ): ?>
					<a id="org_doc_id" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a>
					<br>
				<?php endif; ?>
				<input type="file" name="org_doc_id">
			</span>
		</div>
	</div>

	<div class="field">
		<label for="org_doc_home"><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident*", 'yproject'); ?></label>
		<div class="field-container align-left">
			<span class="field-value">
				<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
				<?php if ( isset($current_file_home) ): ?>
					<a id="org_doc_home" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a>
					<br>
				<?php endif; ?>
				<input type="file" name="org_doc_home">
			</span>
		</div>
	</div>

	<div>
		<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
	</div>
</form>