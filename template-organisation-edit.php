<?php
/**
 * Template Name: Editer une organisation
 *
 */
?>

<?php 
locate_template( array("requests/organisations.php"), true );
$organisation_obj = YPOrganisation::get_current();
YPOrganisationLib::edit($organisation_obj);
get_header();
?>

<div id="content">
    
	<div class="padder">
	    
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	    
			<?php $post->post_title = 'Organisation ' . $organisation_obj->get_name(); ?>
	    
			<?php locate_template( array("basic/basic-header.php"), true ); ?>
	    
			<div class="center margin-height">
	    
				<?php if ($organisation_obj !== FALSE): ?>
			    
					<?php if (is_user_logged_in()): ?>

						<?php the_content(); ?>

						<?php global $errors_edit; ?>
						<?php if (count($errors_edit->errors) > 0): ?>
						<ul class="errors">
							<?php $error_messages = $errors_edit->get_error_messages(); ?>
							<?php foreach ($error_messages as $error_message): ?>
								<li><?php echo $error_message; ?></li>
							<?php endforeach; ?>
						</ul>
						<?php elseif (filter_input(INPUT_POST, 'action') == 'edit-organisation'): ?>
						<p class="success">
							<?php _e('Modifications enregistr&eacute;es.'); ?>
						</p>
						<?php endif; ?>

						<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">

							<?php
							/**
							 * Données générales
							 */
							?>
							<label for="org_name"><?php _e('D&eacute;nomination sociale', 'yproject'); ?></label>
							<em><?php echo $organisation_obj->get_name(); ?></em><br />

							<label for="org_type"><?php _e('Type d&apos;organisation', 'yproject'); ?></label>
							<em><?php if ($organisation_obj->get_type() == "society") { echo "Société"; } ?></em><br />

							<label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?></label>
							<input type="text" name="org_legalform" value="<?php echo $organisation_obj->get_legalform(); ?>" /><br />

							<label for="org_idnumber"><?php _e('Num&eacute;ro d&apos;immatriculation', 'yproject'); ?></label>
							<input type="text" name="org_idnumber" value="<?php echo $organisation_obj->get_idnumber(); ?>" /><br />

							<label for="org_rcs"><?php _e('RCS', 'yproject'); ?></label>
							<input type="text" name="org_rcs" value="<?php echo $organisation_obj->get_rcs(); ?>" /><br />

							<label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?></label>
							<input type="text" name="org_capital" value="<?php echo $organisation_obj->get_capital(); ?>" /><br />

							<label for="org_ape"><?php _e('Code APE', 'yproject'); ?></label>
							<input type="text" name="org_ape" value="<?php echo $organisation_obj->get_ape(); ?>" /><br />

							<h2><?php _e('Si&egrave;ge social', 'yproject'); ?></h2>
							<label for="org_address"><?php _e('Adresse', 'yproject'); ?></label>
							<input type="text" name="org_address" value="<?php echo $organisation_obj->get_address(); ?>" /><br />

							<label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?></label>
							<input type="text" name="org_postal_code" value="<?php echo $organisation_obj->get_postal_code(); ?>" /><br />

							<label for="org_city"><?php _e('Ville', 'yproject'); ?></label>
							<input type="text" name="org_city" value="<?php echo $organisation_obj->get_city(); ?>" /><br />

							<label for="org_nationality"><?php _e('Pays', 'yproject'); ?></label>
							<select name="org_nationality" id="org_nationality">
								<?php 
								require_once("country_list.php");
								global $country_list;
								$selected_country = $organisation_obj->get_nationality();
								foreach ($country_list as $country_code => $country_name): ?>
									<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
								<?php endforeach; ?>
							</select><br />
						
						
							<?php
							/**
							 * Informations bancaires
							 */
							?>
							<h2><?php _e('Informations bancaires - si vous souhaitez faire un virement d&apos;une somme obtenue', 'yproject'); ?></h2>
							<label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
							<input type="text" name="org_bankownername" value="<?php echo $organisation_obj->get_bank_owner(); ?>" /> <br />

							<label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
							<input type="text" name="org_bankowneraddress" value="<?php echo $organisation_obj->get_bank_address(); ?>" /> <br />

							<label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
							<input type="text" name="org_bankowneriban" value="<?php echo $organisation_obj->get_bank_iban(); ?>" /> <br />

							<label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
							<input type="text" name="org_bankownerbic" value="<?php echo $organisation_obj->get_bank_bic(); ?>" /> <br />

							
							<?php
							/**
							 * Pièces d'identité
							 */
							?>
							<h2><?php _e('Pi&egrave;ces d&apos;identit&eacute;', 'yproject'); ?></h2>
							
							<?php 
							$organisation_obj->check_strong_authentication();
							$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status($organisation_obj->get_wpref());
							if ($strongauth_status['message'] != '') { echo $strongauth_status['message'] . '<br />'; }
							
							switch ($organisation_obj->get_strong_authentication()) {
								case '0':
							?>
								Afin de lutter contre le blanchiment d&apos;argent, pour tout investissement de plus de <strong><?php echo YP_STRONGAUTH_AMOUNT_LIMIT; ?>&euro;</strong> sur l&apos;ann&eacute;e,
								ou pour retirer plus de <strong><?php echo YP_STRONGAUTH_REFUND_LIMIT; ?>&euro;</strong>,
								nous devons transmettre les pi&egrave;ces d&apos;identit&eacute; suivantes &agrave; notre partenaire Mangopay
								(Les fichiers doivent &ecirc;tre de type jpeg, gif, png ou pdf et leur poids inf&eacute;rieur &agrave; 2 Mo) :<br /><br />

								<label for="org_file_cni">CNI et fonction de la personne physique qui agit pour son compte</label>
								<input type="file"name="org_file_cni" /> <br />

								<label for="org_file_status">Statuts sign&eacute;s</label>
								<input type="file"name="org_file_status" /> <br />

								<label for="org_file_extract">Extrait du registre de commerce datant de moins de 3 mois</label>
								<input type="file"name="org_file_extract" /> <br />

								<label for="org_file_declaration">D&eacute;claration de b&eacute;n&eacute;ficiaire &eacute;conomique (si on n&apos;identifie pas d&apos;actionnaires personnes physiques dans les statuts)</label>
								<input type="file"name="org_file_declaration" /><br />
							<?php
								    break;
								case '1':
							?>
								Cette organisation est identifi&eacute;e et valid&eacute;e par notre partenaire Mangopay. Vous pouvez maintenant investir les sommes que vous souhaitez.<br /><br />
							<?php
								    break;
								case '5':
							?>
								Les fichiers permettant de valider vos investissements sont en cours d&apos;&eacute;tude chez notre partenaire Mangopay. Merci de votre compr&eacute;hension.<br /><br />
							<?php
								    break;
							}
							?>
							
							
							<input type="hidden" name="action" value="edit-organisation" />

							<input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" />
						</form>
							
								
						<?php
						/**
						 * Demande de transfert
						 */
						?>
						<?php
						$args = array(
						    'author'    => $organisation_obj->get_wpref(),
						    'post_type' => 'withdrawal_order',
						    'post_status'   => 'pending'
						);
						$pending_transfers = get_posts($args);

						if (!$pending_transfers && isset($_POST['mangopaytoaccount'])) {
							//Teste d'abord de la somme qu'on tente de retirer : si plus de 1000€, on doit mettre en place une strong auth sur cet utilisateur
							$mp_amount = ypcf_mangopay_get_user_personalamount_by_wpid($organisation_obj->get_wpref());
							$real_amount_invest = $mp_amount / 100;
							if ($real_amount_invest < YP_STRONGAUTH_REFUND_LIMIT || $organisation_obj->get_strong_authentication() == '1') {
								//Crée le beneficiary
								$errors = '';

								//Teste si il existe un beneficiary correspondant à l'utilisateur, sinon tente de le créer
								$beneficiary_id = ypcf_mangopay_get_mp_user_beneficiary_id($organisation_obj->get_wpref());
								if ($beneficiary_id == "") {
									$beneficiary_id = ypcf_init_mangopay_beneficiary(
										$organisation_obj->get_wpref(), 
										$organisation_obj->get_bank_owner(), 
										$organisation_obj->get_bank_address(), 
										$organisation_obj->get_bank_iban(), 
										$organisation_obj->get_bank_bic()
									);
								}

								if ($beneficiary_id == "") {
									global $mp_errors;
									$errors = 'Erreur lors du transfert : ' . $mp_errors;
									echo '<span class="error">' . $errors . '</span><br />';

								} else {
									//Faire un withdrawal avec le userid, le beneficiaryid et $mp_amount
									$withdrawal_obj = ypcf_mangopay_make_withdrawal($organisation_obj->get_wpref(), $beneficiary_id, $mp_amount);

									//Enregistrer le withdrawal pour garder une trace
									if (is_string($withdrawal_obj)) {
										echo '<span class="error">Erreur durant la transaction : ' . $withdrawal_obj . '</span>';

									} else {
										//Enregistrement de l'id du withdrawal (en tant que post wp)
										$withdrawal_post = array(
											'post_author'   => $organisation_obj->get_wpref(),
											'post_title'    => $mp_amount,
											'post_content'  => $withdrawal_obj->ID,
											'post_status'   => 'pending',
											'post_type'	=> 'withdrawal_order'
										);
										wp_insert_post( $withdrawal_post );

										//Affichage message état
										?>
										La transaction est en cours..
										<?php
									}
								}
							}
						} ?>
										
							
						<?php
						/**
						 * Porte-monnaie
						 */
						?>
						<h2 class="underlined"><?php _e( 'Porte-monnaie', 'yproject' ); ?></h2>
						<?php $real_amount_invest = ypcf_mangopay_get_user_personalamount_by_wpid($organisation_obj->get_wpref()) / 100; ?>
						Vous disposez de <?php echo $real_amount_invest; ?>&euro; dans votre porte-monnaie.<br /><br />

						<?php if ($pending_transfers) : ?>
						    Vous avez un transfert en cours.
						<?php else :
							if ($real_amount_invest > 0) { ?>
						    <form action="<?php echo get_permalink($page_mes_investissements->ID); ?>" method="post" enctype="multipart/form-data">
							<input type="hidden" name="mangopaytoaccount" value="1" />
							<input type="submit" value="Reverser sur mon compte bancaire" class="button" />
						    </form>
						    <br /><br />
						<?php	}
						endif; ?>
	
						    
						<?php
						/**
						 * Transferts d'argent
						 */
						?>
						<h2 class="underlined"><?php _e( 'Transferts d&apos;argent', 'yproject' ); ?></h2>
						<?php
						$args = array(
						    'author'	    => $organisation_obj->get_wpref(),
						    'post_type'	    => 'withdrawal_order',
						    'post_status'   => 'any',
						    'orderby'	    => 'post_date',
						    'order'	    =>  'ASC'
						);
						$transfers = get_posts($args);
						if ($transfers) :
						?>
						<ul class="user_history">
							<?php foreach ( $transfers as $post ) :
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
							endforeach; ?>
						</ul>
						<?php else: ?>
							Aucun transfert.
						<?php endif; ?>


					<?php else: ?>

						<?php $page_connexion = get_page_by_path('connexion'); ?>

						<a href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a>

					<?php endif; ?>
					
				<?php else: ?>
						
					<?php _e('Cette page n&apos;est pas accessible.', 'yproject'); ?>
						
				<?php endif; ?>
						
			</div>
		
		<?php endwhile; endif; ?>
	    
	</div><!-- .padder -->
	
</div><!-- #content -->
	
<?php get_footer();