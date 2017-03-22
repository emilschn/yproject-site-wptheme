<div class="head"><?php _e('Gestion financi&egrave;re', 'yproject'); ?></div>

<div id="project-wallet" class="tab-content">

	<?php
	global $can_modify, $disable_logs,
		   $campaign_id, $campaign, $post_campaign,
		   $WDGAuthor, $WDGUser_current,
		   $is_admin, $is_author;

	$disable_logs = FALSE;
	WDGFormProjects::form_submit_turnover();
	WDGFormProjects::form_submit_account_files();
	$return_roi_payment = WDGFormProjects::form_submit_roi_payment();
	$return_lemonway_card = WDGFormProjects::return_lemonway_card();
	WDGFormProjects::form_proceed_roi_transfers();
	$campaign_organization = $campaign->get_organization();
	$organization_obj = new WDGOrganization( $campaign_organization->wpref );
	$mandate_conditions = $campaign->mandate_conditions();
	$declaration_info = $campaign->declaration_info();

	$params_full = ''; $params_partial = '';
	if (isset($_GET['preview']) && $_GET['preview'] = 'true') { $params_full = '?preview=true'; $params_partial = '&preview=true'; }
	$campaign_id_param = '?campaign_id=' . $campaign_id;
	?>

	<?php if ( $return_roi_payment == 'error_lw_payment' ): ?>
		<span class="errors">Erreur LWROI001 : Erreur de paiement vers votre porte-monnaie.</span>
	<?php endif; ?>

	<?php if ( $return_lemonway_card == TRUE ): ?>
		<span class="success">Paiement effectué</span>
	<?php elseif ( $return_lemonway_card !== FALSE ): ?>
		<span class="errors">Il y a eu une erreur au cours de votre paiement.</span>
	<?php endif; ?>
		

	<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
		<h2><?php _e('Autorisation de pr&eacute;l&egrave;vement', 'yproject'); ?></h2>
		
		
		<?php if ( $is_admin ): ?>
			<form action="" id="forcemandate_form" class="db-form" data-action="save_project_force_mandate">
				<?php DashboardUtility::create_field( array(
					"id"			=> "new_force_mandate",
					"type"			=> "select",
					"label"			=> __( "Forcer l'entrepreneur &agrave; signer l'autorisation de pr&eacute;l&egrave;vement ?", 'yproject' ),
					"value"			=> $campaign->is_forced_mandate(),
					"editable"		=> $is_admin,
					"admin_theme"	=> $is_admin,
					"visible"		=> $is_admin,
					"options_id"	=> array( 0, 1 ),
					"options_names"	=> array( 
						__( "Non", 'yproject' ),
						__( "Oui", 'yproject' )
					)
				) ); ?>

				<?php DashboardUtility::create_field(array(
					"id"			=> "new_mandate_conditions",
					"type"			=> "editor",
					"label"			=> __( "Conditions contractuelles", 'yproject' ),
					"value"			=> $mandate_conditions,
					"editable"		=> $is_admin,
					"admin_theme"	=> $is_admin,
					"visible"		=> $is_admin,
				)); ?>

				<?php DashboardUtility::create_save_button( "forcemandate-form", $is_admin ); ?>
			</form>
		<?php elseif ( !empty( $mandate_conditions ) ) : ?>
		
			<strong><?php _e( "Conditions contractuelles pour la signature du mandat de pr&eacute;l&egrave;vement", 'yproject' ) ?></strong><br />
			<?php echo $mandate_conditions; ?><br /><br />
		
		<?php endif; ?>
		
		
		<?php 
		//Si il n'y a pas de RIB enregistré, demander d'éditer l'organisation
		//TODO : permettre l'édition du RIB directement ici
		$keep_going = true;
		?>
		<?php if ( !$organization_obj->has_saved_iban() ): ?>
			<?php
			$keep_going = false;
			$page_edit_orga = get_page_by_path('editer-une-organisation');
			?>
			<?php _e( "Afin de signer votre autorisation de pr&eacute;l&egrave;vement, vous devez au pr&eacute;alable renseigner le RIB de l'organisation.", 'yproject' ); ?><br />
			<p class="align-center"><a class="button" href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$organization_obj->get_wpref(); ?>"><?php _e('Editer', 'yproject'); ?></a></p><br /><br />
			<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
			
		<?php endif; ?>
		
		<?php
		//Si il y a un RIB enregistré
		?>
		<?php if ( $keep_going ): ?>
			<?php
			//Récupérer la liste des mandats liés au wallet de l'organisation
			//Si il n'y en a pas : enregistrer un mandat lié
			?>
			<?php
			$organization_obj->register_lemonway();
			$saved_mandates_list = $organization_obj->get_lemonway_mandates();
			if ( empty( $saved_mandates_list ) ) {
				$keep_going = false;
				if ( !$organization_obj->add_lemonway_mandate() ) {
                                        $page_edit_orga = get_page_by_path('editer-une-organisation');
					echo LemonwayLib::get_last_error_message(); ?>
					<a class="button" href="<?php echo get_permalink($page_edit_orga->ID) .'?orga_id='.$organization_obj->get_wpref(); ?>"><?php _e('Editer', 'yproject'); ?></a><br /><br />
					<button class="button disabled"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
					<?php
				} else {
					_e( "Cr&eacute;ation de mandat en cours", 'yproject' );
				}
			}
			?>
		<?php endif; ?>

		<?php if ( $keep_going ): ?>
			<?php
			//Récupérer le dernier de la liste, vérifier le statut
			/**
			 * 0 	non validé
			 * 5 	utilisable avec prélèvement effectif dans un délai de 6 jours ouvrés bancaire
			 * 6 	utilisable avec prélèvement effectif dans un délai de 3 jours ouvrés bancaire
			 * 8 	désactivé
			 * 9 	rejeté
			 */
			?>
			<?php 
			$last_mandate = end( $saved_mandates_list );
			$last_mandate_status = $last_mandate[ "S" ];
			?>
			<?php if ( $last_mandate_status == 0 ): //Si 0, proposer de signer ?>
				<?php $phone_number = $WDGUser_current->wp_user->get('user_mobile_phone'); ?>
			
				<?php 
				//Indication pour rappeler qu'ils se sont engagés dans le contrat à autoriser les prélévements automatiques
				?>
				<?php if ( $campaign->is_forced_mandate() ): ?>
					<?php _e( "Selon votre contrat, vous vous &ecirc;tes engag&eacute; &agrave; signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />
				<?php endif; ?>
					
					
				<?php if ( empty( $phone_number ) ): ?>
					<?php _e( "Afin de signer l'autorisation de pr&eacute;l&eacute;vement automatique, merci de renseigner votre num&eacute;ro de t&eacute;l&eacute;phone mobile dans votre compte utilisateur.", 'yproject' ); ?><br /><br />
				
				<?php elseif ( !$organization_obj->is_registered_lemonway_wallet() ): ?>
					<?php _e( "L'organisation doit &ecirc;tre authentifi&eacute;e par notre prestataire de paiement afin de pouvoir signer l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?><br /><br />
						
				<?php else: ?>
				<form action="<?php echo admin_url( 'admin-post.php?action=organization_sign_mandate'); ?>" method="post" class="align-center">
					<input type="hidden" name="organization_id" value="<?php echo $organization_obj->get_wpref(); ?>" />
					<button type="submit" class="button"><?php _e( "Signer l'autorisation de pr&eacute;l&egrave;vement automatique", 'yproject' ); ?></button>
				</form>
				<?php endif; ?>
				
			<?php elseif ( $last_mandate_status == 5 || $last_mandate_status == 6 ): //Si 5 ou 6, afficher que OK ?>
				<?php _e( "Merci d'avoir signé l'autorisation de pr&eacute;l&egrave;vement automatique.", 'yproject' ); ?>
			
			<?php elseif ( $last_mandate_status == 8 ): //Si 8, demander de nous contacter ?>
				<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; d&eacute;sactiv&eacute;e. Merci de nous contacter.", 'yproject' ); ?>
			
			<?php elseif ( $last_mandate_status == 9 ): //Si 9, demander de nous contacter ?>
				<?php _e( "L'autorisation de pr&eacute;l&egrave;vement automatique a &eacute;t&eacute; rejet&eacute;e. Merci de nous contacter.", 'yproject' ); ?>
			
			<?php endif; ?>
		<?php endif; ?>
		

		<?php if ($campaign->campaign_status() == ATCF_Campaign::$campaign_status_funded): ?>
			<h2><?php _e('Reverser aux investisseurs', 'yproject'); ?></h2>
			
			
			<?php if ( $is_admin ): ?>
				<form action="" id="forcemandate_form" class="db-form" data-action="save_project_declaration_info">
					<?php DashboardUtility::create_field(array(
						"id"			=> "new_declaration_info",
						"type"			=> "editor",
						"label"			=> __( "Informations de reversement", 'yproject' ),
						"value"			=> $declaration_info,
						"editable"		=> $is_admin,
						"admin_theme"	=> $is_admin,
						"visible"		=> $is_admin,
					)); ?>

					<?php DashboardUtility::create_save_button( "forcemandate-form", $is_admin ); ?>
				</form>
			<?php elseif ( !empty( $declaration_info ) ) : ?>

				<strong><?php _e( "Informations relatives &agrave; votre d&eacute;claration", 'yproject' ) ?></strong><br />
				<?php echo $declaration_info; ?><br /><br />

			<?php endif; ?>

				
			<h3>Dates de vos versements :</h3>
			<?php
			$declaration_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign->ID );
			$nb_fields = $campaign->get_turnover_per_declaration();
			?>
			<?php if ($declaration_list): ?>
				<ul class="payment-list">
					<?php foreach ( $declaration_list as $declaration ): ?>
						<?php $declaration_message = $declaration->get_message(); ?>
						<li>
							<h4><?php echo $declaration->get_formatted_date(); ?></h4>
							<div>
								<?php $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'); ?>

								<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
									<form action="" method="POST" id="turnover-declaration" data-roi-percent="<?php echo $campaign->roi_percent(); ?>" data-costs-orga="<?php echo $campaign->get_costs_to_organization(); ?>">
										<?php if ($nb_fields > 1): ?>
											<ul>
												<?php
												$date_due = new DateTime($declaration->date_due);
												$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
												?>
												<?php for ($i = 0; $i < $nb_fields; $i++): ?>
													<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <input type="text" name="turnover-<?php echo $i; ?>" id="turnover-<?php echo $i; ?>" /> &euro; HT</li>
													<?php $date_due->add(new DateInterval('P1M')); ?>
												<?php endfor; ?>
											</ul>

										<?php else: ?>
											<input type="text" name="turnover-total" id="turnover-total" />
										<?php endif; ?>
										<br /><br />

										Somme à verser : <span class="amount-to-pay">0</span> &euro;.
										<br /><br />
										
										<?php _e("Informez vos investisseurs de l'&eacute;tat d'avancement de votre projet et de votre chiffre d'affaires, ", 'yproject'); ?>
										<?php _e("et exprimez-leur clairement quels sont vos enjeux du moment.", 'yproject'); ?>
										<?php _e("Eux aussi sont int&eacute;ress&eacute;s &agrave; la r&eacute;ussite de votre projet et peuvent vous soutenir de nouveau pour avancer !", 'yproject'); ?>
										<?php _e("Nous leur transmettrons la nouvelle lors du versement des royalties.", 'yproject'); ?><br /><br />
										<textarea name="declaration-message"></textarea>
										<br /><br />

										<input type="hidden" name="action" value="save-turnover-declaration" />
										<input type="hidden" name="declaration-id" value="<?php echo $declaration->id; ?>" />
										<button type="submit" class="button">Enregistrer la déclaration</button>
									</form>

								<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_payment ): ?>
									Chiffre d'affaires déclaré :
									<?php $declaration_turnover = $declaration->get_turnover(); ?>
									<?php if ($nb_fields > 1): ?>
										<ul>
											<?php
											$date_due = new DateTime($declaration->date_due);
											$date_due->sub(new DateInterval('P'.$nb_fields.'M'));
											?>
											<?php for ($i = 0; $i < $nb_fields; $i++): ?>
												<li><?php echo ucfirst(__($months[$date_due->format('m') - 1])); ?> : <?php echo $declaration_turnover[$i]; ?> &euro; HT</li>
												<?php $date_due->add(new DateInterval('P1M')); ?>
											<?php endfor; ?>
										</ul><br />

									<?php else: ?>
										<?php echo $declaration_turnover[0]; ?> &euro;<br />
									<?php endif; ?>

									<b>Total de chiffre d'affaires déclaré : </b><?php echo $declaration->get_turnover_total(); ?> &euro; HT<br /><br />

									<b>Total du versement : </b><?php echo $declaration->amount; ?> &euro; (<?php echo $campaign->roi_percent(); ?> %)<br />
									<b>Frais de gestion : </b><?php echo $declaration->get_commission_to_pay(); ?> &euro;<br />
									<b>Montant à verser : </b><?php echo $declaration->get_amount_with_commission(); ?> &euro;<br /><br />
									
									<?php if ( empty( $declaration_message ) ): ?>
									Aucun message ne sera envoyé aux investisseurs.<br /><br />
									<?php else: ?>
									<b>Ce message sera envoyé à vos investisseurs :</b><br />
									<?php echo $declaration->get_message(); ?><br /><br />
									<?php endif; ?>

									<form action="" method="POST" enctype="">
										<input type="hidden" name="action" value="proceed_roi" />
										<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
										<input type="submit" name="payment_card" class="button" value="<?php _e('Payer par carte', 'yproject'); ?>" />
									</form>
									<br />


									<hr />

									Si vous souhaitez payer par virement bancaire, voici les informations dont vous aurez besoin :
									<ul>
										<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> LEMON WAY</li>
										<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
										<li><strong>BIC :</strong> BNPAFRPPIFE</li>
										<li>
											<strong><?php _e("Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject'); ?></strong> wedogood-<?php echo $organization_obj->get_lemonway_id(); ?><br />
											<ul>
												<li><?php _e("Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject'); ?></li>
											</ul>
										</li>
									</ul>
									<br />

									Ensuite, cliquez sur "Payer par virement bancaire", et nous validerons ce paiement une fois la somme réceptionnée par notre prestataire.<br />
									<br />

									<form action="" method="POST" enctype="">
										<input type="hidden" name="action" value="proceed_roi" />
										<input type="hidden" name="proceed_roi_id" value="<?php echo $declaration->id; ?>" />
										<input type="submit" name="payment_bank_transfer" class="button" value="<?php _e('Payer par virement bancaire', 'yproject'); ?>" />
									</form>


								<?php elseif (  $declaration->get_status() == WDGROIDeclaration::$status_transfer ||  $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
									Chiffre d'affaires déclaré :
									<?php $declaration_turnover = $declaration->get_turnover(); ?>
									<?php if ($nb_fields > 1): ?>
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
									<b>Frais de gestion : </b><?php echo $declaration->get_commission_to_pay(); ?> &euro;<br /><br />
									
									<?php if ( empty( $declaration_message ) ): ?>
									Aucun message ne sera envoyé aux investisseurs.<br /><br />
									<?php else: ?>
									<b>Ce message sera envoyé à vos investisseurs :</b><br />
									<?php echo $declaration->get_message(); ?><br /><br />
									<?php endif; ?>

									<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
									Nous attendons la réception de la somme par notre prestataire de paiement et procèderons au versement par la suite.
									<?php else: ?>
									Votre paiement de <?php echo $declaration->get_amount_with_commission(); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
									Le versement vers vos investisseurs est en cours.
									<?php endif; ?>

									<?php if ($is_admin): ?>
										<br /><br />
										<a href="#transfer-roi" class="button transfert-roi-open wdg-button-lightbox-open" data-lightbox="transfer-roi" data-roideclaration-id="<?php echo $declaration->id; ?>">Procéder aux versements</a>

										<?php ob_start(); ?>
										<h3><?php _e('Reverser aux utilisateurs', 'yproject'); ?></h3>
										<div id="lightbox-content">
											<div class="loading-image align-center"><img id="ajax-email-loader-img" src="<?php echo get_stylesheet_directory_uri(); ?>/images/loading.gif" alt="chargement" /></div>
											<div class="loading-content"></div>
											<div class="loading-form align-center hidden">
												<form action="" method="POST">
													<input type="checkbox" name="send_notifications" value="1" checked="checked" /> Envoyer un mail automatique aux investisseurs<br /><br />
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
									Chiffre d'affaires déclaré :
									<?php $declaration_turnover = $declaration->get_turnover(); ?>
									<?php if ($nb_fields > 1): ?>
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
									<b>Frais de gestion : </b><?php echo $declaration->get_commission_to_pay(); ?> &euro;<br /><br />
									
									<?php if ( empty( $declaration_message ) ): ?>
									Aucun message n'a été envoyé aux investisseurs.<br /><br />
									<?php else: ?>
									<b>Ce message sera envoyé à vos investisseurs :</b><br />
									<?php echo $declaration->get_message(); ?><br /><br />
									<?php endif; ?>

									Votre paiement de <?php echo $declaration->get_amount_with_commission(); ?> &euro; a bien été effecuté le <?php echo $declaration->get_formatted_date( 'paid' ); ?>.<br />
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
		<?php endif; ?>
		
	<?php endif; ?>


	<h2><?php _e('Liste des op&eacute;rations bancaires', 'yproject'); ?></h2>
	<?php $transfers = $organization_obj->get_transfers();
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
</div>