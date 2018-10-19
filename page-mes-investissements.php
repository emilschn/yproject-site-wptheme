<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) {
	wp_redirect(site_url());
	exit();
}
?>

<?php get_header(); ?>

<div id="content">
    
	<div class="padder">
		
		<?php locate_template( array("common/basic-header.php"), true ); ?>
	    
		<div class="center margin-height">
			
			&lt;&lt; <a href="<?php echo home_url('/mon-compte/'); ?>">Mon compte</a><br /><br />
			
			<?php
			//Demande de renvoi de code
			if (isset($_GET['invest_id_resend']) && !empty($_GET['invest_id_resend'])) {
				$contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
				$signsquid_signatory = signsquid_get_contract_signatory($contractid);
				$current_user = wp_get_current_user();
				if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
					if (NotificationsEmails::send_code_user($_GET['invest_id_resend'], $current_user, $signsquid_signatory->{'code'})) {
						?>Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br /><?php
					    
					} else {
						?><span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br /><?php
					}
					
				} else {
					?><span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br /><?php
				}
			}
			?>
					
					
					
			<?php
			//Recherche des retraits en cours
			$args = array(
				'author'    => get_current_user_id(),
				'post_type' => 'withdrawal_order',
				'post_status'   => 'pending'
			);
			$pending_transfers = get_posts($args);
			
			
			//Si il n'y a pas de retraits en cours et qu'on demande le transfert
			if (!$pending_transfers && isset($_POST['mangopaytoaccount'])) {
			    
			    
				//Test d'abord de la somme qu'on tente de retirer : si plus de 1000€, on doit mettre en place une strong auth sur cet utilisateur
				$mp_amount = ypcf_mangopay_get_user_personalamount_by_wpid(get_current_user_id());
				$real_amount_invest = $mp_amount / 100;
				if ($real_amount_invest > YP_STRONGAUTH_REFUND_LIMIT && !ypcf_mangopay_is_user_strong_authenticated(get_current_user_id())) {
					if (isset($_POST['document_submited'])) {
						$url_request = ypcf_init_mangopay_user_strongauthentification(wp_get_current_user());
						$curl_result = ypcf_mangopay_send_strong_authentication($url_request, 'StrongValidationDtoPicture');
						if ($curl_result) ypcf_mangopay_set_user_strong_authentication_doc_transmitted(get_current_user_id());
						else echo 'Il y a eu une erreur pendant l&apos;envoi.';
					}

					$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status(get_current_user_id());
					if ($strongauth_status['status'] != 'waiting') {
						echo $strongauth_status['message'];

					} else {
						if ($strongauth_status['message'] != '') echo $strongauth_status['message'] . '<br />';
						?>
						Pour retirer une somme sup&eacute;rieure &agrave; <?php echo YP_STRONGAUTH_REFUND_LIMIT; ?>&euro; sur une ann&eacute;e, vous devez fournir une pi&egrave;ce d&apos;identit&eacute;.<br />
						La pi&egrave;ce d&apos;identit&eacute; doit &ecirc;tre pr&eacute;sent&eacute;e recto-verso.<br />
						Le fichier doit &ecirc;tre de type jpeg, gif, png ou pdf.<br />
						Son poids doit &ecirc;tre inf&eacute;rieur &agrave; 2 Mo.<br />
						<form id="mangopay_strongauth_form" action="" method="post" enctype="multipart/form-data">
						    <input type="hidden" name="mangopaytoaccount" value="1" />
						    <input type="hidden" name="document_submited" value="1" />
						    <input type="file" name="StrongValidationDtoPicture" />
						    <input type="submit" value="Envoyer" class="button" />
						</form><br /><br />
						<?php
					}
					
					
				//Sinon, on peut créer le transfert
				} else {
					$errors = '';
					
					//Si les informations bancaires sont remplies
					if (isset($_POST['bankownername'])) {
						$beneficiary_id = "empty";
						if (($_POST["bankowneraddress"] == "") || ($_POST["bankowneriban"] == "") || ($_POST["bankownerbic"] == "")) {
							$errors = 'Certaines informations n&apos;ont pas &eacute;t&eacute; correctement remplies.';
						} else {
							$beneficiary_id = ypcf_init_mangopay_beneficiary(get_current_user_id(), $_POST["bankownername"], $_POST["bankowneraddress"], $_POST["bankowneriban"], $_POST["bankownerbic"]);
						}
						
						if ($beneficiary_id == "") {
							global $mp_errors;
							$errors = 'Erreur lors du transfert : ' . $mp_errors;
						}
					}

					//Récupération des informations déjà rentrées enventuellement
					$bankaccountownername = (isset($_POST["bankownername"])) ? $_POST["bankownername"] : '';
					$bankaccountowneraddress = (isset($_POST["bankowneraddress"])) ? $_POST["bankowneraddress"] : '';
					$bankaccountiban = (isset($_POST["bankowneriban"])) ? $_POST["bankowneriban"] : '';
					$bankaccountbic = (isset($_POST["bankownerbic"])) ? $_POST["bankownerbic"] : '';
					
					//Tester si il existe un beneficiary correspondant à l'utilisateur
					$beneficiary_id = ypcf_mangopay_get_mp_user_beneficiary_id(get_current_user_id());
					if ($beneficiary_id == "" || isset($_POST["edit"])) {
					    
						//Si on est en mode édition du RIB
						if (isset($_POST["edit"])) {
							$beneficiary_obj = ypcf_mangopay_get_beneficiary_by_id($beneficiary_id);
							$bankaccountownername = $beneficiary_obj->BankAccountOwnerName;
							$bankaccountowneraddress = $beneficiary_obj->BankAccountOwnerAddress;
							$bankaccountiban = $beneficiary_obj->BankAccountIBAN;
							$bankaccountbic = $beneficiary_obj->BankAccountBIC;
						}
					    
						if ($errors != '') echo '<span class="error">' . $errors . '</span><br />';
						//Entrer les données d'un beneficiary
						?>
						<strong>Veuillez entrer vos informations bancaires</strong><br />
						<form action="" method="post" enctype="multipart/form-data">
							<label for="bankownername" class="large-label">Nom du propri&eacute;taire du compte : </label>
								<input type="text" name="bankownername" value="<?php echo $bankaccountownername; ?>" /> <br />
							<label for="bankowneraddress" class="large-label">Adresse du compte : </label>
								<input type="text" name="bankowneraddress" value="<?php echo $bankaccountowneraddress; ?>" /> <br />
							<label for="bankowneriban" class="large-label">IBAN : </label>
								<input type="text" name="bankowneriban" value="<?php echo $bankaccountiban; ?>" /> <br />
							<label for="bankownerbic" class="large-label">BIC : </label>
								<input type="text" name="bankownerbic" value="<?php echo $bankaccountbic; ?>" /> <br /><br />
							<input type="hidden" name="mangopaytoaccount" value="1" />
							<input type="submit" value="Valider" class="button" />
						</form>

						<?php
					} else {
						
						//Si l'utilisateur a validé les informations de RIB
						if (isset($_POST['valid'])) {
							//Faire un withdrawal avec le userid, le beneficiaryid et $mp_amount
							$withdrawal_obj = ypcf_mangopay_make_withdrawal(get_current_user_id(), $beneficiary_id, $mp_amount);

							//Si il y a une erreur lors du retrait
							if (is_string($withdrawal_obj)) {
								echo '<span class="error">Erreur durant la transaction : ' . $withdrawal_obj . '</span>';
								
							//Enregistrer le withdrawal pour garder une trace
							} else {
								//Enregistrement de l'id du withdrawal (en tant que post wp)
								$withdrawal_post = array(
								    'post_author'   => get_current_user_id(),
								    'post_title'    => $mp_amount,
								    'post_content'  => $withdrawal_obj->ID,
								    'post_status'   => 'pending',
								    'post_type'	    => 'withdrawal_order'
								);
								wp_insert_post( $withdrawal_post );

								?>
								La transaction est en cours. Vous pourrez suivre son &eacute;volution sur <a href="<?php echo home_url('/mon-compte/'); ?>">votre compte</a>.
								<?php
							}

							
						//Sinon on lui affiche ses informations de RIB pour validation
						} else {
							$beneficiary_obj = ypcf_mangopay_get_beneficiary_by_id($beneficiary_id);
							?>
							Veuillez v&eacute;rifier l&apos;exactitude de ces informations :<br />
							    Nom du propri&eacute;taire du compte : <?php echo $beneficiary_obj->BankAccountOwnerName ?> <br />
							    Adresse du compte : <?php echo $beneficiary_obj->BankAccountOwnerAddress ?> <br />
							    IBAN : <?php echo $beneficiary_obj->BankAccountIBAN ?> <br />
							    BIC : <?php echo $beneficiary_obj->BankAccountBIC ?> <br />
							<form action="" method="post" enctype="multipart/form-data">
							    <input type="hidden" name="mangopaytoaccount" value="1" />
							    <input type="hidden" name="valid" value="1" />
							    <input type="submit" value="Valider" class="button" />
							</form>
							<form action="" method="post" enctype="multipart/form-data">
							    <input type="hidden" name="mangopaytoaccount" value="1" />
							    <input type="hidden" name="edit" value="1" />
							    <input type="submit" value="Editer" class="button" />
							</form>

							<?php
						}

					}
				}
			
			//
			} else {
				//Si il y a un statut en cours de strong authentication
				$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status(get_current_user_id());
				if ($strongauth_status['status'] != ''): ?>
					<span class="error"><?php echo $strongauth_status; ?></span><br />
					<?php if ($strongauth_status['status'] != 'waiting'): ?>
					    La pi&egrave;ce d&apos;identit&eacute; doit &ecirc;tre pr&eacute;sent&eacute;e recto-verso.<br />
					    Le fichier doit &ecirc;tre de type jpeg, gif, png ou pdf.<br />
					    Son poids doit &ecirc;tre inf&eacute;rieur &agrave; 2 Mo.<br />
					    <form id="mangopay_strongauth_form" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="mangopaytoaccount" value="1" />
						<input type="hidden" name="document_submited" value="1" />
						<input type="file" name="StrongValidationDtoPicture" />
						<input type="submit" value="Envoyer" class="button" />
					    </form><br /><br />
					<?php endif; ?>
				<?php endif; ?>

				<?php //On ne peut pas reverser sur le compte car il y a déjà un transfert en cours ?>
				<?php if ($pending_transfers) : ?>
				    Vous avez un transfert en cours.
				
				<?php else : ?>
					<?php //Si il y a une somme à retirer ?>
					<?php if ($real_amount_invest > 0) : ?>
						<form action="" method="post" enctype="multipart/form-data">
						    <input type="hidden" name="mangopaytoaccount" value="1" />
						    <input type="submit" value="Reverser sur mon compte bancaire" class="button" />
						</form>
					<?php endif; ?>
				<?php endif; ?>

			<?php } ?>
				
			<br /><br />
			
			<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="logo mangopay"></div>
		</div>
	    
	</div>
    
</div>

<?php get_footer(); ?>