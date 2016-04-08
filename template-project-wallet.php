<?php 
/**
 * Template Name: Projet Gestion financière
 *
 */
global $campaign_id;
$campaign_id = $_GET['campaign_id'];
$post_campaign = get_post($campaign_id);
$campaign = atcf_get_campaign($post_campaign);
$wdg_user = WDGUser::current();
WDGFormProjects::form_submit_turnover();
WDGFormProjects::form_submit_account_files();
$return_roi_payment = WDGFormProjects::form_submit_roi_payment();
$return_lemonway_card = WDGFormProjects::return_lemonway_card();
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
				?>
			
				<?php if ( $return_roi_payment == 'error_lw_payment' ): ?>
					<span class="errors">Erreur LWROI001 : Erreur de paiement vers votre porte-monnaie.</span>
				<?php endif; ?>
			
				<?php if ( $return_lemonway_card == TRUE ): ?>
					<span class="success">Paiement effectué</span>
				<?php elseif ( $return_lemonway_card !== FALSE ): ?>
					<span class="errors">Il y a eu une erreur au cours de votre paiement.</span>
				<?php endif; ?>
				
				<?php if ($result_proceed_roi_list != FALSE): ?>
					Le virement en attente de réception par notre partenaire Mangopay. Rappel du virement à effectuer :<br />
					- Propriétaire du compte : <?php echo $result_proceed_roi_list->BankAccountOwner; ?><br />
					- IBAN : <?php echo $result_proceed_roi_list->BankAccountIBAN; ?><br />
					- BIC : <?php echo $result_proceed_roi_list->BankAccountBIC; ?><br />
					- Code de référence : <?php echo $result_proceed_roi_list->GeneratedReference; ?><br />
					- Montant : <?php echo ($result_proceed_roi_list->Amount / 100); ?> &euro;<br />
				<?php endif; ?>
				
				<?php
				//Init variables utiles
				$keep_going = TRUE;
				$display_rib = FALSE;
				$current_index = 1;
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
								Organisation en cours d'identification.
									<?php /* plus tard :
								Votre organisation n'est pas encore identifi&eacute;e par notre partenaire Lemonway.<br />
								Rendez-vous sur la <a href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref; ?>">page de votre organisation</a>.
									 * 
									 */ ?>
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
				<?php if (!$keep_going || $current_wallet_amount == 0): ?>
				<span class="button disabled"><?php _e('Proc&eacute;der au virement', 'yproject'); ?></span>
				<?php else: ?>
					<form action="" method="POST">
						<input type="hidden" name="mangopaytoaccount" value="1" />
						<input type="hidden" name="action" value="transfer_to_account" />
						<input type="submit" class="button" value="<?php _e('Proc&eacute;der au virement', 'yproject'); ?>" />
					</form>
				<?php endif; ?>
					
				
				
				<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
				<h2 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php _e('Reverser aux investisseurs', 'yproject'); ?></h2>
				<?php if ($keep_going) { ?>
					<h3>Dates de vos versements :</h3>

					<?php
					$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign->ID );
					$nb_fields = $campaign->get_turnover_per_declaration();
					?>
					<?php if ($declaration_list): ?>
						<ul class="payment-list">
						<?php foreach ( $declaration_list as $declaration ): ?>
							<li>
								<h4><?php echo $declaration->get_formatted_date(); ?></h4>
								<div>
									<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
										<form action="" method="POST" id="turnover-declaration" data-roi-percent="<?php echo $campaign->roi_percent(); ?>" data-costs-orga="<?php echo $campaign->get_costs_to_organization(); ?>">
											<?php if ($nb_fields > 1): ?>
											<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>
											<ul>
												<?php
												$date_due = new DateTime($declaration->date_due);
												$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
												?>
												<?php for ($i = 0; $i < $nb_fields; $i++): ?>
												<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <input type="text" name="turnover-<?php echo $i; ?>" id="turnover-<?php echo $i; ?>" /></li>
												<?php $date_due->add(new DateInterval('P1M')); ?>
												<?php endfor; ?>
											</ul>

											<?php else: ?>
											<input type="text" name="turnover-total" id="turnover-total" />
											<?php endif; ?>

											<br /><br />
											Somme à verser : <span class="amount-to-pay">0</span> €.
											<br /><br />

											<input type="hidden" name="action" value="save-turnover-declaration" />
											<input type="hidden" name="declaration-id" value="<?php echo $declaration->id; ?>" />
											<button type="submit" class="button">Enregistrer la déclaration</button>
										</form>

									<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_payment ): ?>
										Chiffre d'affaires déclaré : 
										<?php $declaration_turnover = $declaration->get_turnover(); ?>
										<?php if ($nb_fields > 1): ?>
											<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>
											<ul>
												<?php
												$date_due = new DateTime($declaration->date_due);
												$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
												?>
												<?php for ($i = 0; $i < $nb_fields; $i++): ?>
												<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <?php echo $declaration_turnover[$i]; ?> &euro;</li>
												<?php $date_due->add(new DateInterval('P1M')); ?>
												<?php endfor; ?>
											</ul><br />

										<?php else: ?>
										<?php echo $declaration_turnover[0]; ?> &euro;<br />
										<?php endif; ?>
										
										<b>Total de chiffre d'affaires déclaré : </b><?php echo $declaration->get_turnover_total(); ?> &euro;<br /><br />
										
										<b>Total du versement : </b><?php echo $declaration->amount; ?> &euro; (<?php echo $campaign->roi_percent(); ?> %)<br />
										<b>Frais de gestion : </b><?php echo $declaration->get_commission_to_pay(); ?> &euro;<br />
										<b>Montant à verser : </b><?php echo $declaration->get_amount_with_commission(); ?> &euro;<br /><br />

										<form action="" method="POST" enctype="">
											<input type="hidden" name="action" value="proceed_roi" />
											<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
											<input type="submit" name="payment_card" class="button" value="<?php _e('Payer par carte', 'yproject'); ?>" />
										</form>

									<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_transfer ): ?>
										Votre paiement de <?php echo $declaration->get_amount_with_commission(); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
										Le versement vers vos investisseurs est en cours.
										
										<?php if ($wdg_user->is_admin()): ?>
											<br /><br />
											<a href="#transfer-roi" class="button transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>">Procéder aux versements</a>
											
											<?php ob_start(); ?>
											<h3><?php _e('Reverser aux utilisateurs', 'yproject'); ?></h3>
											<div id="lightbox-content">
												<div class="loading-image align-center"><img id="ajax-email-loader-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading.gif" alt="chargement" /></div>
												<div class="loading-content"></div>
												<div class="loading-form align-center hidden">
													<form action="" method="POST">
														<input type="hidden" name="action" value="proceed_roi_transfers" />
														<input type="hidden" id="hidden-roi-id" name="roi_id" value="" />
														<input type="submit" class="button" value="Transférer" />
													</form>
												</div>
											</div>
											<?php
											$lightbox_content = ob_get_contents();
											ob_end_clean();
											echo do_shortcode('[yproject_lightbox id="transfer-roi"]' . $lightbox_content . '[/yproject_lightbox]');
											?>
										
										<?php endif; ?>

									<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
										Votre paiement de <?php echo $declaration->amount; ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
										Vos investisseurs ont bien reçu leur retour sur investissement.

									<?php endif; ?>


									<?php if ($declaration->file_list != ""): ?>
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
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>

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