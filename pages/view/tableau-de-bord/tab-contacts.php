<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$send_mail_success = filter_input( INPUT_GET, 'send_mail_success' );
?>
<h2><?php _e( "Contacts", 'yproject' ); ?></h2>

<?php if (!empty($send_mail_success)): ?>
<div class="success"><?php _e( "E-mails envoy&eacute;s avec succ&egrave;s !", 'yproject' ); ?></div>
<?php endif; ?>

<?php if ( $page_controler->can_add_check() ): ?>
<div class="align-center margin-height">
	<button class="button-contacts-add-check button blue"><?php _e( "Ajouter un chèque", 'yproject' ); ?></button>
</div>
<?php endif; ?>

<div class="tab-content-large">
	<div id="ajax-contacts-load" class="ajax-investments-load align-center" data-value="<?php echo $page_controler->get_campaign_id(); ?>">
		<img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
	</div>
</div>

<div class="tab-content" id="send-mail-tab" style="display: none">
	<h2><?php _e("Envoyer un mail", 'yproject')?></h2>
	<form id="direct-mail" method="POST" action="<?php echo admin_url( 'admin-post.php?action=send_project_mail'); ?>" class="db-form v3 full center bg-white">
		<p><?php _e("Le message sera envoyé &agrave", 'yproject')?> <strong id="nb-mailed-contacts">0</strong> personnes</p>
		<input type="hidden" id="mail_recipients" name="mail_recipients"/>
		<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>"/>
		<div class="step-write field field-container">
			<p><strong><?php _e("Vous pouvez utiliser les variables suivantes : ", 'yproject'); ?></strong>
			<?php DashboardUtility::get_infobutton('Au moment de l\'envoi, les variables seront remplacées par les valeurs correspondantes.<br/><br/>
				Ainsi, par exemple, <b>%userfirstname%</b> sera remplacé par le prénom de l\'utilisateur qui recevra le message.',true)?></p>
			<ul style="list-style-type: square;">
				<li><i>%userfirstname%</i> : Prénom de l'utilisateur destinataire</li>
				<li><i>%userlastname%</i> : Nom de famille de l'utilisateur destinataire</li>
				<li><i>%investwish%</i> : Intention d'investissement</li>
			</ul>
			<label>Objet du mail :</label>
			<input type="text" name="mail_title" id="mail-title" value="">
			<br><br>

			<?php
			$previous_content = filter_input(INPUT_POST, 'mail_content');
			if (empty($previous_content)) {
				$previous_content = __("Bonjour ", 'yproject') . "%userfirstname%,<br />";
				$previous_content .= __("Merci d'avoir investi dans notre projet !", 'yproject') ."<br />";
				$previous_content .= __("A bient&ocirc;t !", 'yproject');
			}
			wp_editor( $previous_content, 'mail_content',
				array(
					'media_buttons' => true,
					'quicktags'     => false,
					'tinymce'       => array(
						'plugins'				=> 'wordpress, paste, wplink, textcolor',
						'paste_remove_styles'   => true
					)
				)
			);
			?>
			<br>

			<p class="align-center">
				<a id="mail-preview-button" class="button red"><?php _e('Prévisualisation', 'yproject'); ?></a>
			</p>
		</div>
		<div class="step-confirm" hidden>
			<h3>Aperçu du message</h3>
			<div class="preview-title"></div>
			<div class="preview"></div>

			<p class="align-center">
				<a id="mail-back-button" class="button blue"><?php _e('Editer', 'yproject'); ?></a><br><br>
				<button type="submit" id="mail-send-button" class="button red"><?php _e('Envoyer le message', 'yproject'); ?></button>
			</p>
		</div>
	</form>
</div>


<?php if ( $page_controler->can_add_check() ): ?>

<div class="align-center margin-height">
	<button class="button-contacts-add-check button blue"><?php _e( "Ajouter un chèque", 'yproject' ); ?></button>
</div>

<form action="" method="post" id="form-contacts-add-check" class="db-form v3 full bg-white hidden">
	<div class="align-justify">
		<h3><?php _e( "Ajouter un investissement par ch&egrave;que", 'yproject' ); ?></h3>
		<?php _e( "Pour ajouter un investissement par ch&egrave;que, vous aurez besoin des informations compl&egrave;tes de votre investisseur (et de sa structure/entreprise si il investit en tant que personne morale).", 'yproject' ); ?><br>
		<?php _e( "Vous aurez aussi besoin de nous transmettre les fichiers permettant de l'authentifier (ainsi que la personne morale &eacute;ventuelle).", 'yproject' ); ?><br>
		<?php _e( "Enfin, il nous faudra une photo du ch&egrave;que ainsi que du contrat paraph&eacute; et sign&eacute; correspondant &agrave; l'investissement.", 'yproject' ); ?><br>
		<?php _e( "L'investissement sera mis en attente, en attendant que nos &eacute;quipes valident les informations.", 'yproject' ); ?>
	</div>
</form>

<?php endif; ?>






<?php // Affichage des résultats des sondages ?>
<?php if ( $page_controler->can_access_admin() ): ?>
	<?php $campaign_poll_answers = $page_controler->get_campaign()->get_api_data( 'poll_answers' ); ?>
	<br><br><br>
	<div class="db-form">
		
		<?php $investment_contracts = WDGInvestmentContract::get_list( $page_controler->get_campaign()->ID ); ?>
		<?php if ( !empty( $investment_contracts ) ): ?>
		<div class="field admin-theme">

			<b>Etat des contrats li&eacute;s &agrave; la lev&eacute;e de fonds</b><br><br>

			<table>
				<tr>
					<td>Nom</td>
					<td>Montant inv.</td>
					<td>Statut</td>
					<td>Pourcent du CA</td>
					<td>Montant per&ccedil;u</td>
				</tr>

				<?php foreach ( $investment_contracts as $investment_contract ): ?>
					<?php
					$name = '';
					if ( $investment_contract->investor_type == WDGInvestmentContract::$investor_type_user ) {
						$WDGUser = WDGUser::get_by_api_id( $investment_contract->investor_id );
						$name = $WDGUser->get_lastname() .' '. $WDGUser->get_firstname();
					} else {
						$WDGOrganization = WDGOrganization::get_by_api_id( $investment_contract->investor_id );
						$name = $WDGOrganization->get_name();
					}
					$status = ( $investment_contract->status == 'active' ) ? 'Actif' : 'Arrêté';
					?>
					<tr>
						<td><?php echo $name; ?></td>
						<td><?php echo $investment_contract->subscription_amount; ?></td>
						<td><?php echo $status; ?></td>
						<td><?php echo $investment_contract->turnover_percent; ?> %</td>
						<td><?php echo $investment_contract->amount_received; ?> €</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php endif; ?>
		
		
		<div class="field admin-theme">

			<b>Sondage lié à la garantie</b><br><br>

			<table>
				<tr>
					<td>Date</td>
					<td>Contexte</td>
					<td>Montant (contexte)</td>
					<td>Investirait montant différent</td>
					<td>Investirait montant</td>
					<td>Investirait sur d'autres projets</td>
					<td>Investirait nombre</td>
					<td>E-mail</td>
					<td>Age</td>
					<td>Code postal</td>
					<td>Sexe</td>
				</tr>

				<?php foreach ( $campaign_poll_answers as $answer ): ?>
					<?php if ( $answer->poll_slug != 'warranty' ) { continue; } ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-amount' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-amount-with-warranty' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-number' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-number-per-year-with-warranty' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		
		<div class="field admin-theme">

			<b>Sondage lié à la motivation</b><br><br>

			<table>
				<tr>
					<td>Date</td>
					<td>Contexte</td>
					<td>Montant (contexte)</td>
					<td>Connait le PP</td>
					<td>Intéret secteur</td>
					<td>Diversifier</td>
					<td>Impact</td>
					<td>Autre</td>
					<td>Autre (txt)</td>
					<td>Connu par</td>
					<td>Autre (txt)</td>
					<td>Venu via</td>
					<td>Autre (txt)</td>
					<td>E-mail</td>
					<td>Age</td>
					<td>Code postal</td>
					<td>Sexe</td>
				</tr>

				<?php foreach ( $campaign_poll_answers as $answer ): ?>
					<?php if ( $answer->poll_slug != 'source' ) { continue; } ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'know-project-manager' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'interrested-by-domain' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'diversify-savings' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'looking-for-positive-impact' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'other-motivations' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $answers_decoded->{ 'other-motivations-to-invest' }; ?></td>
						<td><?php echo $answers_decoded->{ 'how-the-fundraising-was-known' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-to-know-the-fundraising' }; ?></td>
						<td><?php echo $answers_decoded->{ 'where-user-come-from' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-where-the-user-come-from' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	
<?php endif;