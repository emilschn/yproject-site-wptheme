<?php 
/**
 * Template Name: Projet Gestion financière
 *
 */
global $campaign_id;
$campaign_id = $_GET['campaign_id'];
$post_campaign = get_post($campaign_id);
$campaign = atcf_get_campaign($post_campaign);
WDGFormProjects::form_submit_account_files();
WDGFormProjects::form_submit_roi_payment();
//$result_proceed_roi_list = WDGFormProjects::form_proceed_roi_list($campaign);
//WDGFormProjects::form_proceed_roi_return();
WDGFormProjects::form_proceed_roi_transfers();
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<?php require_once('projects/single-admin-bar.php'); ?>

		<div id="project-wallet" class="center margin-height">
		    
			<?php global $can_modify; ?>

			<?php if ($can_modify): ?>
		    
				<?php
				if (have_posts()) {
				    the_post();
				    the_content();
				}
				
				if ($result_proceed_roi_list != FALSE) { ?>
					Le virement en attente de réception par notre partenaire Mangopay. Rappel du virement à effectuer :<br />
					- Propriétaire du compte : <?php echo $result_proceed_roi_list->BankAccountOwner; ?><br />
					- IBAN : <?php echo $result_proceed_roi_list->BankAccountIBAN; ?><br />
					- BIC : <?php echo $result_proceed_roi_list->BankAccountBIC; ?><br />
					- Code de référence : <?php echo $result_proceed_roi_list->GeneratedReference; ?><br />
					- Montant : <?php echo ($result_proceed_roi_list->Amount / 100); ?> &euro;<br />
				<?php }
				
				//Init variables utiles
				$keep_going = TRUE;
				$display_rib = FALSE;
				$current_index = 1;
				global $campaign_id, $current_user; 
				$post_campaign = get_post($campaign_id);
				$campaign = atcf_get_campaign($post_campaign);
				?>
		    
				<h2><?php _e('Porte-monnaie de ', 'yproject'); echo $post_campaign->post_title; ?></h2>
				<h3><?php echo $current_index; $current_index++; ?> - <?php _e('Associer une organisation &agrave; votre projet', 'yproject'); ?></h3>
				<?php if ($keep_going) {
					$current_organisation = $campaign->get_organisation();
					if (isset($current_organisation)) {
						$page_edit_orga = get_page_by_path('editer-une-organisation');
						echo __('Organisation d&eacute;finie :', 'yproject') . ' ' . $current_organisation->organisation_name . ' <a class="button" href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref.'">' . __('Editer', 'yproject') . '</a>';
						$organisation_obj = new YPOrganisation($current_organisation->organisation_wpref);
					} else {
						$keep_going = FALSE;
						_e('Pas encore d&eacute;fini', 'yproject');
						$page_parameters = get_page_by_path('parametres-projet');
						echo ' - <a href="' .get_permalink($page_parameters->ID) . $campaign_id_param . $params_partial . '">' . __('Param&egrave;tres', 'yproject') . '</a>';
					}
				} ?>
				
				<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
				<h3 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php echo $current_index; $current_index++; ?> - <?php _e('Documents d&apos;authentification', 'yproject'); ?></h3>
					<?php if ($keep_going): ?>
						<?php if ($campaign->get_payment_provider() == "mangopay"): ?>
							<?php
							$organisation_obj->check_strong_authentication();
							switch ($organisation_obj->get_strong_authentication()) {
								case 0:
									$keep_going = FALSE;
									?>
									Afin de lutter contre le blanchiment d&apos;argent, vous devez vous authentifier auprès de notre partenaire de paiement.<br />
									Rendez-vous sur la <a href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref; ?>">page de votre organisation</a>.
									<?php
									break;
								case 1:
									$display_rib = TRUE;
									_e('Cette organisation est identifi&eacute;e et valid&eacute;e par notre partenaire Mangopay.', 'yproject');
									?><br /><br /><?php
									break;
								case 5:
									$keep_going = FALSE;
									if (WP_IS_DEV_SITE) $keep_going = TRUE;
									$display_rib = TRUE;
									$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status($organisation_obj->get_wpref());
									if ($strongauth_status['message'] != '') { echo $strongauth_status['message'] . '<br />'; }
									break;
							} ?>

						<?php elseif ($campaign->get_payment_provider() == "lemonway"): ?>
							<?php if ($organisation_obj->get_lemonway_status() == YPOrganisation::$lemonway_status_registered): $display_rib = TRUE; ?>
								<?php _e('Cette organisation est identifi&eacute;e et valid&eacute;e par notre partenaire Lemonway.', 'yproject'); ?>
							<?php else: if (!WP_IS_DEV_SITE) { $keep_going = FALSE; } ?>
								Votre organisation n'est pas encore identifi&eacute;e par notre partenaire Lemonway.<br />
								Rendez-vous sur la <a href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref; ?>">page de votre organisation</a>.
							<?php endif; ?>
									
						<?php endif; ?>
					<?php endif; ?>
				<?php else: $display_rib = TRUE; ?>
				<?php endif; ?>
						
				<h3 <?php if (!$display_rib) { ?>class="grey"<?php } ?>><?php echo $current_index; $current_index++; ?> - <?php _e('RIB', 'yproject'); ?></h3>
				<?php if ($display_rib) { ?>
					<?php $organisation_obj->submit_bank_info(); ?>
					<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">
						<label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankownername" value="<?php echo $organisation_obj->get_bank_owner(); ?>" /> <br />

						<label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankowneraddress" value="<?php echo $organisation_obj->get_bank_address(); ?>" /> <br />

						<label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
						<input type="text" name="org_bankowneriban" value="<?php echo $organisation_obj->get_bank_iban(); ?>" /> <br />

						<label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
						<input type="text" name="org_bankownerbic" value="<?php echo $organisation_obj->get_bank_bic(); ?>" /> <br />
							
						<input type="hidden" name="action" value="save_iban_infos" />
						<input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" class="button" />
					</form>
				<?php }
				if (!isset($current_organisation) || ($organisation_obj->get_bank_owner() == '') || ($organisation_obj->get_bank_address() == '') || ($organisation_obj->get_bank_iban() == '') || ($organisation_obj->get_bank_bic() == '')) {
					$keep_going = FALSE;
				}
				?>
						
				
				
				<h3 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php echo $current_index; $current_index++; ?> - <?php _e('Dans votre porte-monnaie', 'yproject'); ?></h3>
				<?php
				if ($keep_going) { $organisation_obj->submit_transfer_wallet(); }
				if (isset($organisation_obj)) { 
				    $current_wallet_amount = $organisation_obj->get_wallet_amount();
				} else {
				    $current_wallet_amount = 0;
				}
				?>
				<span <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php echo $current_wallet_amount; ?> &euro;</span><br /><br />
				<?php if (!$keep_going || $current_wallet_amount == 0) { ?>
				<span class="button disabled"><?php _e('Proc&eacute;der au virement', 'yproject'); ?></span>
				<?php } else { ?>
					<form action="" method="POST">
						<input type="hidden" name="mangopaytoaccount" value="1" />
						<input type="hidden" name="action" value="transfer_to_account" />
						<input type="submit" class="button" value="<?php _e('Proc&eacute;der au virement', 'yproject'); ?>" />
					</form>
				<?php } ?>
					
				
				
				<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
				<h2 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php _e('Reverser aux investisseurs', 'yproject'); ?></h2>
				<?php if ($keep_going) { ?>
				<h3>Dates de vos versements :</h3>
				
				<?php $declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign->ID ); ?>
				<?php if ($declaration_list): ?>
					<ul class="payment-list">
					<?php foreach ( $declaration_list as $declaration ): ?>
						<li>
						    <h4><?php echo $declaration->get_formatted_date(); ?></h4>
							<div>
								<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
									<span class="errors">Le montant n'est pas encore défini</span>

								<?php else: ?>
									<?php if (  $declaration->get_status() == WDGROIDeclaration::$status_payment ): ?>
										<b>Montant à verser : </b><?php echo $declaration->get_amount_to_pay(); ?> &euro;<br />
										
										<form action="" method="POST" enctype="">
										<input type="hidden" name="action" value="proceed_roi" />
										<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
										<input type="submit" name="payment_card" class="button" value="<?php _e('Payer par carte', 'yproject'); ?>" />
										<input type="submit" name="payment_wire" class="button" value="<?php _e('Payer par virement', 'yproject'); ?>" />
										</form>

									<?php else: ?>
										<?php /*if ($post_payment_status->post_status == "pending"): ?>
											<?php $withdrawal_obj = ypcf_mangopay_get_withdrawalcontribution_by_id($post_payment_status->post_content); ?>
											<?php if ($withdrawal_obj->Status == "ACCEPTED"): ?>
												<?php echo $payment_date; ?> - <?php echo $payment_list[$i]; ?> &euro; - Virement effectué sur le porte-monnaie.

												<?php if (current_user_can('manage_options')): ?>
													<br /><br />
													<a href="#transfer-roi" class="button wdg-button-lightbox-open transfert-roi-open" data-lightbox="transfer-roi" data-campaignid="<?php echo $campaign->ID; ?>" data-paymentitem="<?php echo $i; ?>">Transférer le retour sur investissement</a> [Visible uniquement des administrateurs]
												<?php endif; ?>

											<?php elseif ($withdrawal_obj->Status == "CREATED"): ?>
												Virement en attente de réception par notre partenaire Mangopay. Rappel du virement à effectuer :<br />
												- Propriétaire du compte : <?php echo $withdrawal_obj->BankAccountOwner; ?><br />
												- IBAN : <?php echo $withdrawal_obj->BankAccountIBAN; ?><br />
												- BIC : <?php echo $withdrawal_obj->BankAccountBIC; ?><br />
												- Code de référence : <?php echo $withdrawal_obj->GeneratedReference; ?><br />
												- Montant : <?php echo $payment_list[$i]; ?> &euro;<br />

											<?php else: ?>
												Problème de virement
											<?php endif; ?>

										<?php elseif ($post_payment_status->post_status == "published"): ?>
											<?php echo $payment_date; ?> - <?php echo $payment_list[$i]; ?> &euro; - Versement effectué auprès des investisseurs.

										<?php endif; */ ?>
									<?php endif; ?>
								<?php endif; ?>
									
									
								<div>
									<b>Comptes annuels :</b><br />
									<?php $declaration_file_list = $declaration->get_file_list(); ?>
									<?php if ( empty( $declaration_file_list ) ): ?>
										Aucun fichier pour l'instant<br />
									<?php else: ?>
										<ul>
											<?php $i = 0; foreach ($declaration_file_list as $declaration_file): $i++; ?>
											<li><a href="<?php echo $declaration_file; ?>" target="_blank">Fichier <?php echo $i; ?></a></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
									
									<form action="" method="POST" enctype="multipart/form-data">
										<input type="file" name="accounts_file_<?php echo $declaration->id; ?>" />
										<input type="submit" class="button" value="<?php _e('Envoyer', 'yproject'); ?>" />
									</form>
								</div>
							</div>
						</li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				
				
				<?php /*
				    <?php
				    $fp_date = $campaign->first_payment_date();
				    $fp_dd = mysql2date( 'd', $fp_date, false );
				    $fp_mm = mysql2date( 'm', $fp_date, false );
				    $fp_yy = mysql2date( 'Y', $fp_date, false );
				    $payment_list = $campaign->payment_list(); 
				    if ($campaign->funding_duration() > 0 && !empty($fp_date)): ?>
					<ul class="payment-list">
					    <?php for ($i = $fp_yy; $i < $campaign->funding_duration() + $fp_yy; $i++): ?>
						<?php
						WDGFormProjects::form_submit_yearly_account($i);
						$payment_status = $campaign->payment_status_for_year($i);
						if (isset($payment_status)) {
						    $post_payment_status = get_post($payment_status);
						    $payment_date = $post_payment_status->post_date;
						}
						?>
						<li>
						    <h4><?php echo $fp_dd . ' / ' . $fp_mm . ' / ' . $i; ?></h4>
						    <div>
							<?php if ($payment_list[$i] > 0): ?>
							    <?php if (isset($payment_status)): ?>
								    <?php if ($post_payment_status->post_status == "pending"): ?>
									    <?php $withdrawal_obj = ypcf_mangopay_get_withdrawalcontribution_by_id($post_payment_status->post_content); ?>
									    <?php if ($withdrawal_obj->Status == "ACCEPTED"): ?>
										    <?php echo $payment_date; ?> - <?php echo $payment_list[$i]; ?> &euro; - Virement effectué sur le porte-monnaie.

										    <?php if (current_user_can('manage_options')): ?>
											    <br /><br />
											    <a href="#transfer-roi" class="button wdg-button-lightbox-open transfert-roi-open" data-lightbox="transfer-roi" data-campaignid="<?php echo $campaign->ID; ?>" data-paymentitem="<?php echo $i; ?>">Transférer le retour sur investissement</a> [Visible uniquement des administrateurs]
										    <?php endif; ?>
											    
									    <?php elseif ($withdrawal_obj->Status == "CREATED"): ?>
										    Virement en attente de réception par notre partenaire Mangopay. Rappel du virement à effectuer :<br />
										    - Propriétaire du compte : <?php echo $withdrawal_obj->BankAccountOwner; ?><br />
										    - IBAN : <?php echo $withdrawal_obj->BankAccountIBAN; ?><br />
										    - BIC : <?php echo $withdrawal_obj->BankAccountBIC; ?><br />
										    - Code de référence : <?php echo $withdrawal_obj->GeneratedReference; ?><br />
										    - Montant : <?php echo $payment_list[$i]; ?> &euro;<br />
											    
									    <?php else: ?>
										    Problème de virement
									    <?php endif; ?>
											    
								    <?php elseif ($post_payment_status->post_status == "published"): ?>
									    <?php echo $payment_date; ?> - <?php echo $payment_list[$i]; ?> &euro; - Versement effectué auprès des investisseurs.
    
								    <?php endif; ?>
								
							    <?php else: ?>
								    <b>Montant à verser : </b><?php echo $payment_list[$i]; ?> &euro;<br />
								    <form action="" method="POST" enctype="">
									<input type="hidden" name="action" value="proceed_roi" />
									<input type="hidden" name="proceed_roi_<?php echo $i; ?>" value="<?php echo $fp_dd.'_'.$fp_mm.'_'.$i; ?>" />
									<input type="submit" class="button" value="<?php _e('Reverser', 'yproject'); ?>" />
								    </form>
							    <?php endif; ?>
								    
							<?php else: ?>
							    <span class="errors">Le montant n'est pas encore défini</span>
							<?php endif; ?>
						    </div>
						    <div>
							<b>Comptes annuels :</b><br />
							<?php 
							$yearly_accounts = array();
							$yearly_accounts = $campaign->yearly_accounts_file($i);
							if (!empty($yearly_accounts)): ?>
							<ul>
							    <?php foreach ($yearly_accounts as $account_id => $yearly_account_file): ?>
								<li><a href="<?php echo $yearly_account_file["url"]; ?>" target="_blank"><?php echo $yearly_account_file["filename"][0]; ?></a></li>
							    <?php endforeach; ?>
							</ul>
							<?php endif; ?>
							<form action="" method="POST" enctype="multipart/form-data">
							    <input type="hidden" name="action" value="add_account_file" />
							    <input type="file" name="accounts_year_<?php echo $i; ?>" />
							    <input type="submit" class="button" value="<?php _e('Envoyer', 'yproject'); ?>" />
							</form>
						    </div>
						</li>
					    <?php endfor; ?>
					</ul>
					<?php 
					$lightbox_content = '<h3>' . __('Reverser aux utilisateurs', 'yproject') . '</h3>';
					$lightbox_content .= '<div id="lightbox-content">';
					$lightbox_content .= '<div class="loading-image align-center"><img id="ajax-email-loader-img" src="'.get_stylesheet_directory_uri().'/images/loading.gif" alt="chargement" /></div>';
					$lightbox_content .= '<div class="loading-content"></div>';
					$lightbox_content .= '<div class="loading-form align-center hidden"><form action="" method="POST">';
					$lightbox_content .= '<input type="hidden" name="action" value="proceed_roi_transfers" />';
					$lightbox_content .= '<input type="hidden" id="hidden-roi-id" name="roi_id" value="" />';
					$lightbox_content .= '<input type="submit" class="button" value="Transférer" />';
					$lightbox_content .= '</form></div>';
					$lightbox_content .= '</div>';
					echo do_shortcode('[yproject_lightbox id="transfer-roi"]' . $lightbox_content . '[/yproject_lightbox]');
					?>
				    <?php else: ?>
					<span class="disabled">Il manque certains paramètres. Contactez-nous.</span>
				    <?php endif; ?>
				 * 
				 */ ?>
				<?php } ?>
				
				
				<h2 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php _e('Liste des op&eacute;rations bancaires', 'yproject'); ?></h2>
				<?php if ($keep_going): ?>
					<?php $transfers = $organisation_obj->get_transfers();
					if ($transfers) : ?>
				
					<h3>Transferts vers votre compte :</h3>
					<ul>
					    <?php 
						foreach ( $transfers as $transfer_post ) :
							$post_status = ypcf_get_updated_transfer_status($transfer_post);
							$transfer_post = get_post($transfer_post);
							$post_amount = $transfer_post->post_title / 100;
							$status_str = 'En cours';
							if ($post_status == 'publish') {
								$status_str = 'Termin&eacute;';
							} else if ($post_status == 'draft') {
								$status_str = 'Annul&eacute;';
							}
							?>
							<li id="<?php echo $transfer_post->post_content; ?>"><?php echo $transfer_post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
							<?php
						endforeach;
					    ?>
					</ul>
				
					<?php else: ?>
						Aucun transfert d&apos;argent.
					<?php endif; ?>
				<?php endif; ?>
				<?php endif; ?>

			<?php else: ?>

				<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

			<?php endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>