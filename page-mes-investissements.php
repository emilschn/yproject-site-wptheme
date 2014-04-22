<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) :
    wp_redirect(site_url());
    exit();
else:

?>
<?php get_header(); ?>

    <div id="content">
	<div class="padder">
	    <?php locate_template( array( 'members/single/admin-bar.php' ), true ); ?>
	    <div class="center">
		<?php 
		if (is_user_logged_in()) :
		    //Si on a demandé de renvoyer le code
		    if (isset($_GET['invest_id_resend']) && $_GET['invest_id_resend'] != '') {
			$contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
//			$signsquid_infos = signsquid_get_contract_infos($contractid);
			$signsquid_signatory = signsquid_get_contract_signatory($contractid);
			$current_user = wp_get_current_user();
			if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
			    if (ypcf_send_mail_purchase($_GET['invest_id_resend'], "send_code", $signsquid_signatory->{'code'}, $current_user->user_email)) {
				?>
				Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br />
				<?php
			    } else {
				?>
				<span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br />
				<?php
			    }
			} else {
			    ?>
			    <span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br />
			    <?php
			}
		    }
		    
		    $args = array(
			'author'    => get_current_user_id(),
			'post_type' => 'withdrawal_order',
			'post_status'   => 'pending'
		    );
		    $pending_transfers = get_posts($args);

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
				    <input type="submit" value="Envoyer"/>
				</form><br /><br />
				<?php
			    }
			} else {
			    //Créer le beneficiary
			    $errors = '';
			    if (isset($_POST['bankownername'])) {
				$beneficiary_id = "empty";
				if (($_POST["bankowneraddress"] == "") || ($_POST["bankowneriban"] == "") || ($_POST["bankownerbic"] == "")) $errors = 'Certaines informations n&apos;ont pas &eacute;t&eacute; correctement remplies.';
				else $beneficiary_id = ypcf_init_mangopay_beneficiary(get_current_user_id(), $_POST["bankownername"], $_POST["bankowneraddress"], $_POST["bankowneriban"], $_POST["bankownerbic"]);
				if ($beneficiary_id == "") {
				    global $mp_errors;
				    $errors = 'Erreur lors du transfert : ' . $mp_errors;
				}
			    }
			    
			    //Tester si il existe un beneficiary correspondant à l'utilisateur
			    $bankaccountownername = (isset($_POST["bankownername"])) ? $_POST["bankownername"] : '';
			    $bankaccountowneraddress = (isset($_POST["bankowneraddress"])) ? $_POST["bankowneraddress"] : '';
			    $bankaccountiban = (isset($_POST["bankowneriban"])) ? $_POST["bankowneriban"] : '';
			    $bankaccountbic = (isset($_POST["bankownerbic"])) ? $_POST["bankownerbic"] : '';
			    $beneficiary_id = ypcf_mangopay_get_mp_user_beneficiary_id(get_current_user_id());
			    if ($beneficiary_id == "" || isset($_POST["edit"])) {
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
					<input type="text" name="bankownerbic" value="<?php echo $bankaccountbic; ?>" /> <br />
				    <input type="hidden" name="mangopaytoaccount" value="1" />
				    <input type="submit" value="Valider" />
				</form>
				<?php
				
			    } else {
				if (isset($_POST['valid'])) {
				    //Faire un withdrawal avec le userid, le beneficiaryid et $mp_amount
				    $withdrawal_obj = ypcf_mangopay_make_withdrawal(get_current_user_id(), $beneficiary_id, $mp_amount);
				    
				    //Enregistrer le withdrawal pour garder une trace
				    if (is_string($withdrawal_obj)) {
					echo '<span class="error">Erreur durant la transaction : ' . $withdrawal_obj . '</span>';
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
					
					//Affichage message état
					?>
					La transaction est en cours. Vous pourrez suivre son &eacute;volution sur la page <a href="">Mes investissements</a>.
					<?php
				    }
				    
				} else {
				    $beneficiary_obj = ypcf_mangopay_get_beneficiary_by_id($beneficiary_id);
				    //Rappeler les infos de compte
				    ?>
				    Veuillez v&eacute;rifier l&apos;exactitude de ces informations :<br />
					Nom du propri&eacute;taire du compte : <?php echo $beneficiary_obj->BankAccountOwnerName ?> <br />
					Adresse du compte : <?php echo $beneficiary_obj->BankAccountOwnerAddress ?> <br />
					IBAN : <?php echo $beneficiary_obj->BankAccountIBAN ?> <br />
					BIC : <?php echo $beneficiary_obj->BankAccountBIC ?> <br />
				    <form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="mangopaytoaccount" value="1" />
					<input type="hidden" name="valid" value="1" />
					<input type="submit" value="Valider" />
				    </form>
				    <form action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="mangopaytoaccount" value="1" />
					<input type="hidden" name="edit" value="1" />
					<input type="submit" value="Editer" />
				    </form>

				    <?php
				}
				
			    }
			}
		    } else {
		?>

		    <h2 class="underlined">Mon porte-monnaie électronique</h2>
		    <?php
			$real_amount_invest = ypcf_mangopay_get_user_personalamount_by_wpid(get_current_user_id()) / 100;
		    ?>
			Vous disposez de <?php echo $real_amount_invest; ?>&euro; dans votre porte-monnaie.<br />
		
		    <?php 
			$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status(get_current_user_id());
			if ($strongauth_status['status'] != ''):
		    ?>
			<span class="error"><?php echo $strongauth_status; ?></span><br />
			<?php if ($strongauth_status['status'] != 'waiting'): ?>
			    La pi&egrave;ce d&apos;identit&eacute; doit &ecirc;tre pr&eacute;sent&eacute;e recto-verso.<br />
			    Le fichier doit &ecirc;tre de type jpeg, gif, png ou pdf.<br />
			    Son poids doit &ecirc;tre inf&eacute;rieur &agrave; 2 Mo.<br />
			    <form id="mangopay_strongauth_form" action="" method="post" enctype="multipart/form-data">
				<input type="hidden" name="mangopaytoaccount" value="1" />
				<input type="hidden" name="document_submited" value="1" />
				<input type="file" name="StrongValidationDtoPicture" />
				<input type="submit" value="Envoyer"/>
			    </form><br /><br />
			<?php endif; ?>
		    <?php 
			endif; 
		    ?>
		
		    <?php
			if ($pending_transfers) :
		    ?>
			Vous avez un transfert en cours.
		    <?php
			else :
			    if ($real_amount_invest > 0) {
		    ?>
			<form action="" method="post" enctype="multipart/form-data">
			    <input type="hidden" name="mangopaytoaccount" value="1" />
			    <input type="submit" value="Reverser sur mon compte bancaire" />
			</form>
		    <?php
			    }
			endif;
		    ?>

		    <h2 class="underlined"><?php _e( 'Mes investissements', 'yproject' ); ?></h2>
		    <?php
		    $purchases = edd_get_users_purchases(bp_current_user_id(), -1, false, array('completed', 'pending', 'publish', 'failed', 'refunded'));
		    if ( $purchases ) : ?>
		    <ul class="user_history com-activity-list">
			<?php 
			    foreach ( $purchases as $post ) : setup_postdata( $post );
				$downloads = edd_get_payment_meta_downloads($post->ID); 
				$download_id = '';
				if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
				else $download_id = $downloads[0];
				$post_camp = get_post($download_id);
				printUserInvest($post, $post_camp);
			    endforeach;
			?>
		    </ul>
		    <?php endif; ?>
		    
		    <h2 class="underlined"><?php _e( 'Mes transferts d&apos;argent', 'yproject' ); ?></h2>
		    <?php
		    $args = array(
			'author'    => get_current_user_id(),
			'post_type' => 'withdrawal_order',
			'post_status' => 'any',
			'orderby'   => 'post_date',
			'order'     =>  'ASC'
		    );
		    $transfers = get_posts($args);
		    if ($transfers) :
		    ?>
		    <ul class="user_history">
			<?php 
			    foreach ( $transfers as $post ) :
				$widthdrawal_obj = ypcf_mangopay_get_withdrawal_by_id($post->post_content);
				if ($widthdrawal_obj->Error != "" && $widthdrawal_obj->Error != NULL) {
				    $args = array(
					'ID'	=>  $post->ID,
					'post_status'	=> 'draft'
				    );
				    wp_update_post($args);
				    
				} else if ($widthdrawal_obj->IsSucceeded && $widthdrawal_obj->IsCompleted && $post->post_status != 'publish') {
				    $args = array(
					'ID'	=>  $post->ID,
					'post_status'	=> 'publish'
				    );
				    wp_update_post($args);
				}
				$post = get_post($post);
				$post_amount = $post->post_title / 100;
				if ($post->post_status == 'publish') {
				    ?>
				    <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
				    <?php
				} else if ($post->post_status == 'draft') {
				    ?>
				    <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
				    <?php
				} else {
				    ?>
				    <li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
				    <?php
				}
			    endforeach;
			?>
		    </ul>
		    <?php endif; ?>
		    
		    
		<?php
		    }
		endif;
		?>
		<center><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" /></center>
	    </div>
	</div>
    </div>

<?php 
get_footer(); 
endif;
?>
<?php
function printUserInvest($post_invest, $post_campaign) {
    global $post;
    $post = $post_campaign;
    $campaign = atcf_get_campaign( $post_campaign );
    $payment_status = ypcf_get_updated_payment_status($post_invest->ID);
    $contractid = ypcf_get_signsquidcontractid_from_invest($post_invest->ID);
    $signsquid_infos = signsquid_get_contract_infos_complete($contractid);
    $signsquid_status = ypcf_get_signsquidstatus_from_infos($signsquid_infos);
    ?>
    <li id="invest-<?php echo $post_invest->ID. '-' .$contractid; ?>">
	<a href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $post_campaign->post_title; ?></a><br />
	<div class="user_history_title left">
	    <div class="project_preview_item_progress">
	    <?php
		$percent = min(100, $campaign->percent_minimum_completed(false));
		$width = 150 * $percent / 100;
		$width_min = 0;
		if ($percent >= 100 && $campaign->is_flexible()) {
		    $percent_min = $campaign->percent_minimum_to_total();
		    $width_min = 150 * $percent_min / 100;
		}
		?>
		<div class="project_preview_item_progressbg">
		    <div class="project_preview_item_progressbar" style="width:<?php echo $width; ?>px">
			<?php if ($width_min > 0): ?>
			<div style="width: <?php echo $width_min; ?>px; height: 100%; border: 0px; border-right: 1px solid white;">&nbsp;</div>
			<?php else: ?>
			&nbsp;
			<?php endif; ?>
		    </div>
		</div>
		<span class="project_preview_item_progressprint"><?php echo $campaign->percent_minimum_completed(); ?></span>
	    </div>
	</div>
	<div class="user_history_pictos left">
	    <div class="project_preview_item_pictos">
		<div class="project_preview_item_infos">
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/horloge.png" />
			<?php echo $campaign->days_remaining(); ?>
		    </div>
		    <div class="project_preview_item_picto">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cible.png" />
			<?php echo $campaign->minimum_goal(true); ?>
		    </div>
		</div>
		<div class="project_preview_item_infos" style="width: 120px;">
		    <?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post_invest->ID ) ) ); ?><br />
		    <?php echo edd_get_payment_amount( $post_invest->ID ) ?>&euro;
		</div>
		<div class="project_preview_item_infos" style="width: 120px;">
		    <?php echo __("Paiement", "yproject") . ' ' . edd_get_payment_status( $post_invest, true ); ?><br />
		</div>
		<div class="project_preview_item_infos" style="width: 120px;">
		    <?php echo $signsquid_status; ?>
		</div>
		
		<?php
		    //Boutons pour Annuler l'investissement | Recevoir le code à nouveau
		    //Visibles si la collecte est toujours en cours, si le paiement a bien été validé, si le contrat n'est pas encore signé
		    if ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->vote() == "collecte" && $payment_status == "publish" && is_object($signsquid_infos) && $signsquid_infos->{'status'} != 'Agreed') :
		?>
		<div class="project_preview_item_cancel">
		<?php
			if ($signsquid_infos != '' && is_object($signsquid_infos)):
			    $page_my_investments = get_page_by_path('mes-investissements');
		?>
		    <a href="<?php echo get_permalink($page_my_investments->ID); ?>?invest_id_resend=<?php echo $post_invest->ID; ?>"><?php _e("Renvoyer le code de confirmation", "yproject"); ?></a><br />
		<?php
			endif;
			$page_cancel_invest = get_page_by_path('annuler-un-investissement');
		?>
		    <a href="<?php echo get_permalink($page_cancel_invest->ID); ?>?invest_id=<?php echo $post_invest->ID; ?>"><?php _e("Annuler mon investissement", "yproject"); ?></a>
		</div>
		<?php
		    endif;
		?>
		
		<?php
		    //Lien vers le groupe d'investisseurs du projet
		    //Visible si le groupe existe et que l'utilisateur est bien dans ce groupe
		    $investors_group_id = get_post_meta($campaign->ID, 'campaign_investors_group', true);
		    $group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
		    $is_user_group_member = groups_is_user_member(bp_loggedin_user_id(), $investors_group_id);
		    if ($group_exists && $is_user_group_member):
			$group_obj = groups_get_group(array('group_id' => $investors_group_id));
			$group_link = bp_get_group_permalink($group_obj);
		?>
		<div class="project_preview_item_infos" style="width: 120px;">
		    <a href="<?php echo $group_link; ?>">Acc&eacute;der au groupe priv&eacute;</a>
		</div>
		<?php
		    endif;
		?>
		
		<div style="clear: both"></div>
	    </div>
	</div>
	<div style="clear: both"></div>
    </li>
    <?php
}?>