<?php
$WDGUser_current = WDGUser::current();
ob_start();
?>

<?php _e("Afin de lutter contre le blanchiment d'argent, les investissements sup&eacute;rieurs &agrave; 250 € n&eacute;cessitent de fournir les documents d'identification suivants. Ceux-ci seront transmis &agrave; Lemon Way, notre partenaire de paiement.", 'yproject'); ?><br />

<form id="lightbox_userkyc_form" enctype="multipart/form-data">
	<ul id="lightbox_userkyc_form_errors" class="errors">
		
	</ul>
	
	<strong><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
	<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br />
	<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br />
	<?php
	$current_filelist_id = WDGKYCFile::get_list_by_owner_id($WDGUser_current->wp_user->ID, WDGKYCFile::$owner_user, WDGKYCFile::$type_id);
	$current_file_id = $current_filelist_id[0];
	if ( isset($current_file_id) ):
	?>
	<a target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a><br />
	<?php endif; ?>
	<input type="file" id="user_doc_id" name="user_doc_id" /> <br /><br />
	
	<strong><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
	<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
	<?php
	$current_filelist_home = WDGKYCFile::get_list_by_owner_id($WDGUser_current->wp_user->ID, WDGKYCFile::$owner_user, WDGKYCFile::$type_home);
	$current_file_home = $current_filelist_home[0];
	if ( isset($current_file_home) ):
	?>
	<a target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br />
	<?php endif; ?>
	<input type="file" id="user_doc_home" name="user_doc_home" /> <br /><br />
	
	<strong><?php _e("Scan ou copie d'un RIB", 'yproject'); ?></strong><br />
	<?php
	$current_filelist_bank = WDGKYCFile::get_list_by_owner_id($WDGUser_current->wp_user->ID, WDGKYCFile::$owner_user, WDGKYCFile::$type_bank);
	$current_file_bank = $current_filelist_bank[0];
	if ( isset($current_file_bank) ):
	?>
	<a target="_blank" href="<?php echo $current_file_bank->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br />
	<?php endif; ?>
	<input type="file" id="user_doc_bank" name="user_doc_bank" /> <br /><br />
	
	<p id="lightbox_userkyc_form_button" class="align-center">
		<input type="submit" value="<?php _e( "Envoyer", 'yproject' ); ?>" class="button" />
	</p>
	<p id="lightbox_userkyc_form_loading" class="align-center hidden">
		<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
	</p>
</form>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox id="userkyc"]' .$lightbox_content. '[/yproject_lightbox]');