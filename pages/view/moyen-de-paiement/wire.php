<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>

<?php _e("Afin de proc&eacute;der au virement, voici les informations bancaires dont vous aurez besoin :", 'yproject'); ?><br>
<ul>
	<li><strong><?php _e( "Titulaire du compte :", 'yproject' ); ?></strong> LEMON WAY</li>
	<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
	<li><strong>BIC :</strong> BNPAFRPPIFE</li>
	<li>
		<strong><?php _e( "Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject' ); ?></strong> wedogood-<?php echo $page_controler->get_investor_lemonway_id(); ?><br>
		<ul>
			<li><?php _e( "Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject' ); ?></li>
		</ul>
	</li>
</ul>
<br><br>

<?php /* if ( !$page_controler->is_investor_lemonway_registered() ): ?>
	<?php if ( $page_controler->get_current_investment()->get_session_user_type() == 'user' ): ?>
		<?php _e("Une validation de votre identit&eacute; par notre prestataire de paiement est n&eacute;cessaire pour un investissement via virement bancaire. L'envoi des documents ci-dessous est n&eacute;cessaire.", 'yproject'); ?><br>
		<form id="userkyc_form" enctype="multipart/form-data" class="db-form v3 full align-justify ajax-form">
			<ul id="userkyc_form_errors" class="errors">

			</ul>

			<div class="align-justify">
				<strong><?php _e("Justificatif d'identit&eacute;", 'yproject'); ?></strong><br>
				<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br>
				<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br>
				<?php
				$current_filelist_id = $page_controler->get_user_document_list_by_type( WDGKYCFile::$type_id );
				$current_file_id = $current_filelist_id[0];
				if ( isset($current_file_id) ):
				?>
				<a target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" id="user_doc_id" name="user_doc_id" /> <br><br>

				<strong><?php _e("Justificatif de domicile", 'yproject'); ?></strong><br>
				<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br>
				<?php
				$current_filelist_home = $page_controler->get_user_document_list_by_type( WDGKYCFile::$type_home );
				$current_file_home = $current_filelist_home[0];
				if ( isset($current_file_home) ):
				?>
				<a target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" id="user_doc_home" name="user_doc_home" /> <br><br>
			</div>

			<p id="userkyc_form_button" class="align-center">
				<button type="submit" class="button blue"><?php _e( "Envoyer", 'yproject' ); ?></button>
			</p>
			<p id="userkyc_form_loading" class="align-center hidden">
				<img id="ajax-loader-img" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" alt="chargement" />
			</p>
			<p id="userkyc_form_success" class="align-center hidden">
				<?php _e( "Documents envoy&eacute;s !", 'yproject' ); ?>
			</p>
			
			<input type="hidden" name="action" value="save_user_docs" />
		</form>
		<br><br>

	<?php else: //Cas d'une organisation ?>
		<?php _e("Une validation de votre organisation par notre prestataire de paiement est n&eacute;cessaire pour un investissement via virement bancaire. L'envoi des documents ci-dessous est n&eacute;cessaire.", 'yproject'); ?><br>
		<form id="userkyc_form" enctype="multipart/form-data" class="db-form full v3 align-justify ajax-form">
			<ul id="userkyc_form_errors" class="errors">

			</ul>

			<div class="align-justify">
				<strong><?php _e("K-BIS ou &eacute;quivalent &agrave; un registre du commerce", 'yproject'); ?></strong><br>
				<?php _e("Datant de moins de 3 mois", 'yproject'); ?><br>
				<?php
				$current_filelist_kbis = $page_controler->get_organization_document_list_by_type( WDGKYCFile::$type_kbis );
				$current_file_kbis = $current_filelist_kbis[0];
				if ( isset($current_file_kbis) ):
				?>
				<a target="_blank" href="<?php echo $current_file_kbis->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_kbis->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" name="org_doc_kbis" /> <br><br>

				<strong><?php _e("Statuts de la soci&eacute;t&eacute;, certifi&eacute;s conformes à l'original par le g&eacute;rant", 'yproject'); ?></strong><br>
				<?php
				$current_filelist_status = $page_controler->get_organization_document_list_by_type( WDGKYCFile::$type_status );
				$current_file_status = $current_filelist_status[0];
				if ( isset($current_file_status) ):
				?>
				<a target="_blank" href="<?php echo $current_file_status->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_status->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" name="org_doc_status" /> <br><br>

				<strong><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br>
				<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br>
				<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br>
				<?php
				$current_filelist_id = $page_controler->get_organization_document_list_by_type( WDGKYCFile::$type_id );
				$current_file_id = $current_filelist_id[0];
				if ( isset($current_file_id) ):
				?>
				<a target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" name="org_doc_id" /> <br><br>

				<strong><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br>
				<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br>
				<?php
				$current_filelist_home = $page_controler->get_organization_document_list_by_type( WDGKYCFile::$type_home );
				$current_file_home = $current_filelist_home[0];
				if ( isset($current_file_home) ):
				?>
				<a target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br>
				<?php endif; ?>
				<input type="file" name="org_doc_home" /> <br><br>
			</div>

			<p id="userkyc_form_button" class="align-center">
				<button type="submit" class="button blue"><?php _e( "Envoyer", 'yproject' ); ?></button>
			</p>
			<p id="userkyc_form_loading" class="align-center hidden">
				<img id="ajax-loader-img" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" alt="chargement" />
			</p>
			<p id="userkyc_form_success" class="align-center hidden">
				<?php _e( "Documents envoy&eacute;s !", 'yproject' ); ?>
			</p>
			
			<input type="hidden" name="action" value="save_user_docs" />
		</form>
	<?php endif; ?>
<?php endif;*/ ?>

<div class="db-form full v3">
	<p class="align-justify">
		<?php _e("Une fois le virement effectu&eacute;, cliquez sur", 'yproject'); ?>
	</p>
	<a class="button red" href="<?php echo $page_controler->get_wire_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>

</div>


<hr />
<?php _e("Exemple de saisie du code destinataire sur diff&eacute;rentes banques :", 'yproject'); ?><br><br>
<div class="align-center"><img src="<?php echo home_url( '/wp-content/plugins/appthemer-crowdfunding/includes/ui/shortcodes/capture-lbp.png' ); ?>" /></div><br><br>