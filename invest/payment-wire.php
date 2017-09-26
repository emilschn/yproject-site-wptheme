<?php
global $campaign;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign)): ?>

	<?php if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == 'wire'): ?>
		<?php
		ypcf_debug_log( 'payment-wire.php' );
		$WDGUser_current = WDGUser::current();
		$WDGUser_current->register_lemonway();
		$invest_type = $_SESSION['redirect_current_invest_type'];
		$lemonway_id = $WDGUser_current->get_lemonway_id();
		if ( $invest_type != 'user' ) {
			$organization = new WDGOrganization($invest_type);
			$lemonway_id = $organization->get_lemonway_id();
		}
		$page_payment_done = get_page_by_path('paiement-effectue');
		global $current_breadcrumb_step; $current_breadcrumb_step = 3;
		locate_template( 'invest/breadcrumb.php', true );
		?>

		<?php _e("Afin de proc&eacute;der au virement, voici les informations bancaires dont vous aurez besoin :", 'yproject'); ?><br />
		<ul>
			<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> LEMON WAY</li>
			<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
			<li><strong>BIC :</strong> BNPAFRPPIFE</li>
			<li>
				<strong><?php _e("Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject'); ?></strong> wedogood-<?php echo $lemonway_id; ?><br />
				<ul>
					<li><?php _e("Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject'); ?></li>
				</ul>
			</li>
		</ul>
		<br /><br />
	
		<?php if ( $invest_type == 'user' ): ?>
			<?php if ( !$WDGUser_current->is_lemonway_registered() ): ?>
			<?php _e("Une validation de votre identit&eacute; par notre prestataire de paiement est n&eacute;cessaire pour un investissement via virement bancaire. L'envoi des documents ci-dessous est n&eacute;cessaire.", 'yproject'); ?><br />
			<form id="userkyc_form" enctype="multipart/form-data">
				<ul id="userkyc_form_errors" class="errors">

				</ul>

				<strong><?php _e("Justificatif d'identit&eacute;", 'yproject'); ?></strong><br />
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

				<strong><?php _e("Justificatif de domicile", 'yproject'); ?></strong><br />
				<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
				<?php
				$current_filelist_home = WDGKYCFile::get_list_by_owner_id($WDGUser_current->wp_user->ID, WDGKYCFile::$owner_user, WDGKYCFile::$type_home);
				$current_file_home = $current_filelist_home[0];
				if ( isset($current_file_home) ):
				?>
				<a target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br />
				<?php endif; ?>
				<input type="file" id="user_doc_home" name="user_doc_home" /> <br /><br />

				<p id="userkyc_form_button" class="align-center">
					<input type="submit" value="<?php _e( "Envoyer", 'yproject' ); ?>" class="button" />
				</p>
				<p id="userkyc_form_loading" class="align-center hidden">
					<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
				</p>
				<p id="userkyc_form_success" class="align-center hidden">
					<?php _e( "Documents envoy&eacute;s !", 'yproject' ); ?>
				</p>
			</form>
			<br /><br />
			<?php endif; ?>
		
		<?php else: //Cas d'une organisation ?>
			
			<?php if ( !$organization->is_registered_lemonway_wallet() ): ?>
				<?php _e("Une validation de votre organisation par notre prestataire de paiement est n&eacute;cessaire pour un investissement via virement bancaire. L'envoi des documents ci-dessous est n&eacute;cessaire.", 'yproject'); ?><br />
				<form id="userkyc_form" enctype="multipart/form-data">
					<ul id="userkyc_form_errors" class="errors">

					</ul>

					<strong><?php _e("K-BIS ou &eacute;quivalent &agrave; un registre du commerce", 'yproject'); ?></strong><br />
					<?php _e("Datant de moins de 3 mois", 'yproject'); ?><br />
					<?php
					$current_filelist_kbis = WDGKYCFile::get_list_by_owner_id($organization->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_kbis);
					$current_file_kbis = $current_filelist_kbis[0];
					if ( isset($current_file_kbis) ):
					?>
					<a target="_blank" href="<?php echo $current_file_kbis->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_kbis->date_uploaded; ?></a><br />
					<?php endif; ?>
					<input type="file" name="org_doc_kbis" /> <br /><br />

					<strong><?php _e("Statuts de la soci&eacute;t&eacute;, certifi&eacute;s conformes à l'original par le g&eacute;rant", 'yproject'); ?></strong><br />
					<?php
					$current_filelist_status = WDGKYCFile::get_list_by_owner_id($organization->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_status);
					$current_file_status = $current_filelist_status[0];
					if ( isset($current_file_status) ):
					?>
					<a target="_blank" href="<?php echo $current_file_status->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_status->date_uploaded; ?></a><br />
					<?php endif; ?>
					<input type="file" name="org_doc_status" /> <br /><br />

					<strong><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
					<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br />
					<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br />
					<?php
					$current_filelist_id = WDGKYCFile::get_list_by_owner_id($organization->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id);
					$current_file_id = $current_filelist_id[0];
					if ( isset($current_file_id) ):
					?>
					<a target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a><br />
					<?php endif; ?>
					<input type="file" name="org_doc_id" /> <br /><br />

					<strong><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident", 'yproject'); ?></strong><br />
					<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
					<?php
					$current_filelist_home = WDGKYCFile::get_list_by_owner_id($organization->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home);
					$current_file_home = $current_filelist_home[0];
					if ( isset($current_file_home) ):
					?>
					<a target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a><br />
					<?php endif; ?>
					<input type="file" name="org_doc_home" /> <br /><br />

					<p id="userkyc_form_button" class="align-center">
						<input type="submit" value="<?php _e( "Envoyer", 'yproject' ); ?>" class="button" />
					</p>
					<p id="userkyc_form_loading" class="align-center hidden">
						<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
					</p>
					<p id="userkyc_form_success" class="align-center hidden">
						<?php _e( "Documents envoy&eacute;s !", 'yproject' ); ?>
					</p>
				</form>
		
			<?php endif; ?>
		<?php endif; ?>
		
		
		<?php _e("Une fois le virement effectu&eacute;, cliquez sur", 'yproject'); ?>
		<a href="<?php echo get_permalink($page_payment_done->ID) . '?campaign_id=' . $campaign->ID . '&meanofpayment=wire'; ?>" class="button"><?php _e("SUIVANT", 'yproject'); ?></a><br /><br />

		
		
		<hr />
		<?php _e("Exemple de saisie du code destinataire sur diff&eacute;rentes banques :", 'yproject'); ?><br /><br />
		<div class="align-center"><img src="<?php echo home_url(); ?>/wp-content/plugins/appthemer-crowdfunding/includes/ui/shortcodes/capture-lbp.png" /></div><br /><br />

	<?php else: ?>
		Error YPSIPW001 : <?php _e("Probl&egrave;me de page.", 'yproject'); ?>
		
	<?php endif; ?>
	
<?php endif;