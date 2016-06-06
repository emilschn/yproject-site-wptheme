<?php
global $campaign;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}
ypcf_session_start();

if (isset($campaign)):
    $min_value = ypcf_get_min_value_to_invest();
    $max_value = ypcf_get_max_value_to_invest();
    $part_value = ypcf_get_part_value();
    $max_part_value = ypcf_get_max_part_value();

    if ($max_part_value > 0):
        //Si la valeur peut être ponctionnée sur l'objectif, et si c'est bien du numérique supérieur à 0
        $amount_part = FALSE;
        if (isset($_POST['amount_part'])) {
			$amount_part = $_POST['amount_part'];
			$_SESSION['redirect_current_amount_part'] = $amount_part;
		}
		if (isset($_SESSION['redirect_current_amount_part'])) {
			$amount_part = $_SESSION['redirect_current_amount_part'];
		}
		ypcf_debug_log('shortcode::invest/confirm.php > $amount_part : ' . $amount_part);
        
        if (isset($_POST['selected_reward'])) {
			$_SESSION['redirect_current_selected_reward'] = $_POST['selected_reward'];
		}
                    
        $amount = ($amount_part === FALSE) ? 0 : $amount_part * $part_value;
        $remaining_amount = $max_value - $amount;
        if (is_numeric($amount_part) && intval($amount_part) == $amount_part && $amount_part >= 1 && $amount >= $min_value && $amount_part <= $max_part_value && ($remaining_amount == 0 || $remaining_amount >= $part_value)):

            $current_user = wp_get_current_user();
			if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay) {
				ypcf_init_mangopay_user($current_user);
			}
            $current_user_organisation = false;
            $organisation = false;
			
            $invest_type = '';
			if (isset($_SESSION['new_orga_just_created']) && !empty($_SESSION['new_orga_just_created'])) {
				$invest_type = $_SESSION['new_orga_just_created'];
				$_SESSION['redirect_current_invest_type'] = $invest_type;
			} else {
				if (isset($_POST['invest_type'])) {
					$invest_type = $_POST['invest_type'];
					$_SESSION['redirect_current_invest_type'] = $invest_type;
				}
			}
			if (isset($_SESSION['redirect_current_invest_type'])) {
				$invest_type = $_SESSION['redirect_current_invest_type'];
			}
			
            if ($invest_type != 'user') {
                $organisation = new YPOrganisation($invest_type);
                $current_user_organisation = $organisation->get_creator();
				if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay) {
					ypcf_init_mangopay_user($current_user_organisation, true);
				}
            }

			if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay) {
				if (isset($_POST['document_submited'])) {
					$url_request = ypcf_init_mangopay_user_strongauthentification($current_user);
					$curl_result = ypcf_mangopay_send_strong_authentication($url_request, 'StrongValidationDtoPicture');
					if ($curl_result) {
						ypcf_mangopay_set_user_strong_authentication_doc_transmitted($current_user->ID);
					} else {
						_e("Il y a eu une erreur pendant l&apos;envoi", 'yproject'); ?><br /><?php
					}
				}
			}

            //Si le montant transmis est supérieur à ce que mangopay accepte sans identification
            $test_user = $current_user;
            if ($invest_type != 'user') $test_user = $current_user_organisation;
            $annual_amount = $amount + ypcf_get_annual_amount_invested($test_user->ID);
			
			if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay && $annual_amount > YP_STRONGAUTH_AMOUNT_LIMIT && !ypcf_mangopay_is_user_strong_authenticated($test_user->ID)):
				if (ypcf_mangopay_is_user_strong_authentication_sent($test_user->ID)): ?>
					<?php _e("Votre pi&egrave;ce d&apos;identit&eacute; est en cours de validation.", "yproject"); ?><br />
					<?php _e("Un d&eacute;lai maximum de 24h est n&eacute;cessaire &agrave; cette validation.", "yproject"); ?><br />
					<?php _e("Merci de votre compr&eacute;hension.", "yproject"); ?>
                    
				<?php else: ?>
					<?php if ($invest_type != 'user'): ?>
						<?php $page_update = get_page_by_path('modifier-mon-compte'); ?>
						<br />
						<?php _e("Pour investir une somme sup&eacute;rieure &agrave;", 'yproject'); ?>
						<?php echo YP_STRONGAUTH_AMOUNT_LIMIT.'&euro;'; ?>
						<?php _e("sur une ann&eacute;e, merci de vous rendre dans", 'yproject'); ?>
						<a href="<?php echo get_permalink($page_update->ID); ?>"><?php _e("l&apos;administration de votre entreprise", 'yproject'); ?></a>.<br />

					<?php else: ?>
						<br />
						<?php _e("Pour investir une somme sup&eacute;rieure &agrave;", 'yproject'); ?>
						<?php echo YP_STRONGAUTH_AMOUNT_LIMIT.'&euro;'; ?>
						<?php _e("sur une ann&eacute;e, vous devez fournir une pi&egrave;ce d&apos;identit&eacute;.", "yproject"); ?><br />
						<?php _e("Si il s&apos;agit d&apos;une carte nationale d&apos;identit&eacute;, veillez &agrave; ce que le fichier contienne le recto et le verso de votre carte.", "yproject"); ?>
						<?php _e("Le fichier doit &ecirc;tre de type jpeg, gif, png ou pdf et son poids inf&eacute;rieur &agrave; 2 Mo.", "yproject"); ?><br />
						<form id="mangopay_strongauth_form" action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="document_submited" value="1" />
							<input type="hidden" name="amount_part" value="<?php echo $amount_part; ?>" />
							<input type="file" name="StrongValidationDtoPicture" />
							<input type="submit" value="<?php _e("Envoyer", 'yproject'); ?>" />
						</form>
						<br /><br />
					<?php endif; ?>
				<?php endif; ?>

            <?php else: ?>
				<?php
				$continue = TRUE;
				if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay) {
					$mangopay_project_id = ypcf_init_mangopay_project();
					if ($mangopay_project_id === FALSE)  {
						$continue = FALSE;
						_e("Probl&egrave;me de cr&eacute;ation de projet", 'yproject'); ?><br /><br /><?php
					}
				} else if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway) {
					$campaign_organization = $campaign->get_organisation();
					$organization_obj = new YPOrganisation($campaign_organization->organisation_wpref);
					$organization_lemonway_status = $organization_obj->get_lemonway_status();
					if ($organization_lemonway_status != YPOrganisation::$lemonway_status_registered) {
						$continue = FALSE;
						_e("Probl&egrave;me de porte-monnaie projet (ERRLWPW01)", 'yproject'); ?><br /><br /><?php
					}
				}
				
				
				if ($continue):
                //Procédure modifiée d'ajout au panier (on ajoute x items de 1 euros => le montant se retrouve en tant que quantité)
                edd_empty_cart();
                
                $options_cart = array();
                if ($campaign->funding_type() == 'fundingdonation') {
					//Gestion contreparties : ajoute la contrepartie
					$rewards = atcf_get_rewards($campaign->ID);
					if (isset($rewards)) {
						$data_reward = $rewards->get_reward_from_ID($_SESSION['redirect_current_selected_reward']);
						$save_reward = array(
							'id'    => intval($data_reward['id']),
							'amount'=> intval($data_reward['amount']),
							'name'  => $data_reward['name'],
						);
						$options_cart['reward'] = $save_reward;
					} else {
						$options_cart['reward'] = 'none';
					}
				}
                
                $to_add = array();
                $to_add[] = apply_filters( 'edd_add_to_cart_item', array( 'id' => $campaign->ID, 'options' => $options_cart, 'quantity' => $amount ) );
                EDD()->session->set( 'edd_cart', $to_add );

                // Rappel des informations remplies
				locate_template( 'country_list.php', true );
                global $country_list;
                $_SESSION['redirect_current_campaign_id'] = $campaign->ID;
				?>
						
				<?php
				global $current_breadcrumb_step; $current_breadcrumb_step = 2;
				locate_template( 'invest/breadcrumb.php', true );
				?>
				
				<?php if (isset($_POST['confirmed']) && !isset($_POST['information_confirmed'])): ?>
					<span class="errors"><?php _e("Merci de valider vos informations.", 'yproject'); ?></span><br />
				<?php endif; ?>
                
				<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
					<?php if (isset($_POST['confirmed']) && (!isset($_POST['confirm_power']) || (isset($_POST['confirm_power']) && (strtolower($_POST['confirm_power'])) != 'bon pour souscription'))): ?>
						<span class="errors"><?php _e("Merci de saisir 'Bon pour souscription' (sans guillemets) dans le champ pr&eacute;vu &agrave; cet effet.", 'yproject'); ?></span><br />
					<?php endif; ?>
                    <?php if (isset($_POST['confirmed']) && ($amount <= 1500) && (!isset($_POST['confirm_signing']) || !$_POST['confirm_signing'])): ?>
						<span class="errors"><?php _e("Merci de cocher la case de validation de contrat.", 'yproject'); ?></span><br />
					<?php endif; ?>
				<?php endif; ?>
                
				<?php
                $page_invest = get_page_by_path('investir');
                $page_invest_link = get_permalink($page_invest->ID);
                $page_invest_link .= '?campaign_id=' . $campaign->ID;
                $plurial = '';
                if ($amount_part > 1) $plurial = 's';
                switch ($campaign->funding_type()) {
                    case 'fundingdevelopment':
					case 'fundingproject': ?>
                        <br />
						<?php _e("Vous vous appr&ecirc;tez &agrave; investir", 'yproject'); ?>
						<strong><?php echo $amount; ?>&euro;</strong>
						<?php _e("sur le projet", 'yproject'); ?>
						<strong><?php echo $campaign->data->post_title; ?></strong>.
						<a href="<?php echo $page_invest_link; ?>&invest_start=1"><?php _e("Modifier mon investissement", 'yproject'); ?></a><br /><br />
                        <?php
						break;
					case 'fundingdonation': ?>
						<br />
                        <?php _e("Vous vous appr&ecirc;tez &agrave; donner", 'yproject'); ?>
						<strong><?php echo $amount; ?>&euro;</strong>
						<?php _e("sur le projet", 'yproject'); ?>
						<strong><?php echo $campaign->data->post_title; ?></strong>.<br/>
                        <?php _e("En &eacute;change de ce don, vous avez choisi la contrepartie suivante :", 'yproject'); ?> <strong><?php echo $data_reward['name']; ?></strong>.<br/>
                        <a href="<?php echo $page_invest_link; ?>&invest_start=1"><?php _e("Modifier mon don", 'yproject'); ?></a><br /><br />
                        <?php
                        break;
                }
				?>

                <form action="<?php echo $page_invest_link; ?>" method="post" enctype="multipart/form-data">
					<div class="invest_part">
						<?php _e("Veuillez v&eacute;rifier ces informations avant de passer &agrave; l&apos;&eacute;tape suivante.", 'yproject'); ?><br />
						<?php if ($campaign->funding_type() == 'fundingdonation'): ?>
							<em><?php _e('Si vous choisissez une contrepartie, pensez &agrave; indiquer votre adresse.', 'yproject'); ?></em><br />
						<?php endif; ?>
						<br />

						<strong><?php _e("Informations personnelles", 'yproject'); ?></strong><br />
						<?php
						$user_title = ($current_user->get('user_gender') == "male") ? __("Monsieur", 'yproject') : __("Madame", 'yproject');
						$user_name = $user_title . ' ' . $current_user->first_name . ' ' . $current_user->last_name;
						?>
						<span class="label"><?php _e("Identit&eacute; :", 'yproject'); ?></span><?php echo $user_name; ?><br />
						<span class="label"><?php _e("E-mail :", 'yproject'); ?></span><?php echo $current_user->user_email; ?><br /><br />
						<span class="label"><?php _e("Date de naissance :", 'yproject'); ?></span><?php echo $current_user->get('user_birthday_day') . '/' . $current_user->get('user_birthday_month') . '/' . $current_user->get('user_birthday_year'); ?><br />
						<span class="label"><?php _e("Nationalit&eacute; :", 'yproject'); ?></span><?php echo $country_list[$current_user->get('user_nationality')]; ?><br /><br />
						<?php if ($campaign->funding_type() != 'fundingdonation' || 
								($current_user->get('user_address') != '' && $current_user->get('user_postal_code') != '' && $current_user->get('user_city') != '' && $current_user->get('user_country') != '')): ?>
							<div class="label left"><?php _e("Adresse :", 'yproject'); ?></div>
							<div class="left">
								<?php echo $current_user->get('user_address'); ?><br />
								<?php echo $current_user->get('user_postal_code') . ' ' . $current_user->get('user_city'); ?><br />
								<?php echo $current_user->get('user_country'); ?>
							</div>
							<div style="clear: both;"></div>
							<br />
						<?php endif; ?>
							
						<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
							<span class="label"><?php _e("Num&eacute;ro de t&eacute;l&eacute;phone :", 'yproject'); ?></span><?php echo $current_user->get('user_mobile_phone'); ?>
							<?php if (!ypcf_check_user_phone_format($current_user->get('user_mobile_phone'))): ?>
								<span class="errors"><?php _e("Le num&eacute;ro de t&eacute;l&eacute;phone ne correspond pas &agrave; un num&eacute;ro fran&ccedil;ais.", 'yproject'); ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<br /><br /><br />

						<?php if ($invest_type != 'user'): ?>
							<hr />
							<strong><?php _e("Informations de l&apos;organisation", 'yproject'); ?> <em><?php echo $organisation->get_name(); ?></em></strong><br />
							<span class="label"><?php _e("Num&eacute;ro d&apos;immatriculation :", 'yproject'); ?></span> <?php echo $organisation->get_idnumber(); ?><br />
							<span class="label"><?php _e("RCS :", 'yproject'); ?></span> <?php echo $organisation->get_rcs(); ?><br />
							<span class="label"><?php _e("Forme juridique :", 'yproject'); ?></span> <?php echo $organisation->get_legalform(); ?><br />
							<span class="label"><?php _e("Capital social :", 'yproject'); ?></span> <?php echo $organisation->get_capital(); ?><br /><br />

							<div class="label left"><?php _e("Adresse :", 'yproject'); ?></div>
							<div class="left">
								<?php echo $organisation->get_address(); ?><br />
								<?php echo $organisation->get_postal_code() . ' ' . $organisation->get_city(); ?><br />
								<?php echo $country_list[$organisation->get_nationality()]; ?>
							</div>
							<div style="clear: both;"></div>
							<br /><br />
						<?php endif; ?>

						<?php $page_update = get_page_by_path('modifier-mon-compte'); ?>
						<a href="<?php echo get_permalink($page_update->ID); ?>"><?php _e("Modifier ces informations", 'yproject'); ?></a><br /><br />

						<?php $information_confirmed = (isset($_POST["information_confirmed"]) && $_POST["information_confirmed"] == "1") ? 'checked="checked" ' : ''; ?>
						<label><input type="checkbox" name="information_confirmed" value="1" <?php echo $information_confirmed; ?> /> <?php _e("Je d&eacute;clare que ces informations sont exactes.", 'yproject'); ?></label><br />
					</div>

					<input type="hidden" name="amount_part" value="<?php echo $amount_part; ?>">
					<input type="hidden" name="confirmed" value="1">

					<?php if (($campaign->funding_type() != 'fundingdonation')): ?>
						<?php if ($amount <= 1500): ?>
								<h3><?php _e("Merci de prendre connaissance du contrat que vous allez accepter :", 'yproject'); ?></h3>
						<?php else: ?>
								<h3><?php _e("Voici le pouvoir que vous allez signer pour valider l&apos;investissement :", 'yproject'); ?></h3>
						<?php endif; ?>
								
						<?php $invest_data = array(
							"amount_part" => $amount_part,
							"amount" => $amount,
							"total_parts_company" => $campaign->total_parts(),
							"total_minimum_parts_company" => $campaign->total_minimum_parts(),
							"ip" => $_SERVER['REMOTE_ADDR']);
						?>
						<div style="padding: 10px; border: 1px solid grey; height: 400px; overflow: scroll;">
							<?php echo fillPDFHTMLDefaultContent($current_user, $campaign, $invest_data, $organisation); ?>
						</div><br />

						<?php _e("Je donne pouvoir à la société WE DO GOOD :", 'yproject'); ?><br />
						<?php _e("Ecrire", 'yproject'); ?> "<strong>Bon pour souscription</strong>" <?php _e("dans la zone de texte ci-contre :", 'yproject'); ?>
						<?php $confirm_power = (isset($_POST["confirm_power"])) ? $_POST["confirm_power"] : ''; ?>
						&nbsp;<input type="text" name="confirm_power" value="<?php echo $confirm_power; ?>" placeholder="Bon pour souscription" /><br /><br />

						<?php //Si investissement <= 1500, pas besoin de signature, donc on fait cocher une case
						if ($amount <= 1500): ?>
							<br />
							<label for="confirm_signing">
								<input type="checkbox" id="confirm_signing" name="confirm_signing" /> <?php _e("J&apos;ai bien compris les termes du contrat, que je valide.", 'yproject'); ?>
							</label><br /><br />
						<?php endif; ?>
					<?php endif; ?>
							
					<?php switch ($campaign->funding_type()) {
						case 'fundingdonation':
							$button_title = __("Confirmer le don", 'yproject');
							break;
						default:
							$button_title = __("Investir", 'yproject');
							break;
					}  ?>
					<center><input type="submit" value="<?php echo $button_title; ?>" class="button"></center>
				</form>
				<br /><br />
				<?php if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay): ?>
				<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" /></div>
				<?php endif; ?>
				<?php
				endif;
            endif;

        else:
            $error = 'general';
            if (intval($amount_part) != $amount_part) $error = 'integer';
            if ($amount_part < 1 || $amount < $min_value) $error = 'min';
            if ($amount > $max_value) $error = 'max';
            if ($remaining_amount > 0 && $remaining_amount < $part_value) $error = 'interval';
            unset($amount_part);
            locate_template( 'invest/input.php', true );
        endif; ?>


	<?php else: ?>
		<?php _e("Il n&apos;est plus possible d&apos;investir sur ce", 'yproject'); ?> <a href="<?php echo get_permalink($campaign->ID); ?>"><?php _e("projet", 'yproject'); ?></a> !
	<?php endif; ?>
<?php endif;