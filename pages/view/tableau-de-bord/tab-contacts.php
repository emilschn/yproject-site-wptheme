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

<div class="db-form v3 center">
	<br>
	<p class="align-justify">
		<?php _e( "Cliquez sur les lignes du tableau pour voir plus d’informations.", 'yproject' ); ?>
	</p>
	<br><br>
</div>

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
			<?php DashboardUtility::get_infobutton('Au moment de l\'envoi, les variables seront remplacées par les valeurs correspondantes.<br/><br/>Ainsi, par exemple, <b>%userfirstname%</b> sera remplacé par le prénom de l\'utilisateur qui recevra le message.', true); ?></p>
			
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
	<a href="#contacts" class="button-contacts-add-check button blue" data-lightbox="add-check"><?php _e("Ajouter un ch&egrave;que","yproject") ?></a>
	<br><br>
	<?php locate_template( array( 'pages/view/tableau-de-bord/tab-contacts/add-check.php'  ), true ); ?>
</div>
<?php locate_template( array( 'pages/view/tableau-de-bord/tab-contacts/view-investment-draft.php'  ), true ); ?>
<?php endif; ?>
			
<?php if ( $page_controler->can_access_admin() ): ?>
	<br><br>
	<div class="admin-theme-block db-form">
		<div class="field admin-theme align-center">
			<?php
			$editor_params = array( 
				'media_buttons' => true,
				'quicktags'     => true,
				'editor_height' => 500,
				'tinymce'       => array(
					'plugins'		=> 'wordpress, paste, wplink, textcolor, charmap, hr, colorpicker, lists',
					'toolbar1'		=> 'bold,italic,underline,|,hr,bullist,numlist,|,alignleft,aligncenter,alignright,alignjustify,|,link,unlink,video,wp_adv',
					'toolbar2'		=> 'formatselect,fontsizeselect,removeformat,charmap,forecolor,forecolorpicker,pastetext,table,undo,redo',
					'paste_remove_styles' => true,
					'wordpress_adv_hidden' => FALSE,
				)
			);
			?>
			
			<?php if ( $page_controler->get_campaign_status() == ATCF_Campaign::$campaign_status_vote ): ?>
				<a href="#contacts" data-mailtype="preinvestment" class="button admin-theme show-notifications"><?php _e("Envoyer les relances de pr&eacute;-investissement","yproject") ?></a>
				<a href="#contacts" data-mailtype="prelaunch" class="button admin-theme show-notifications"><?php _e("Envoyer les relances de pr&eacute;-lancement","yproject") ?></a>
			<?php endif; ?>
			<?php if ( $page_controler->get_campaign_status() == ATCF_Campaign::$campaign_status_collecte ): ?>
				<a href="#contacts" data-mailtype="investment-2days" class="button admin-theme show-notifications"><?php _e( "Envoyer les relances d'investissement J-2", 'yproject' ); ?></a>
				<?php if ( $page_controler->is_campaign_funded() ): ?>
				<a href="#contacts" data-mailtype="investment-100" class="button admin-theme show-notifications"><?php _e( "Envoyer les relances d'investissement 100 %", 'yproject' ); ?></a>
				<?php else: ?>
				<a href="#contacts" data-mailtype="investment-30" class="button admin-theme show-notifications"><?php _e( "Envoyer les relances d'investissement 30 %", 'yproject' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>
			<br><br>
		
			<form id="form-notifications" action="<?php echo admin_url( 'admin-post.php?action=send_project_notifications' ); ?>" method="POST" class="hidden align-left">
				<b>Champs des notifications :</b><br><br>
				Témoignages :<br>
				<?php wp_editor( '', 'testimony', $editor_params ); ?><br><br>
				URL de l'image (au moins 590px de large) :<br>
				<input type="text" name="image_url"><br><br>
				Description sous l'image :<br>
				<input type="text" name="image_description"><br><br>
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign()->ID; ?>">
				<input type="hidden" id="mail_type" name="mail_type" value="">
				<input type="submit" name="send_option" value="Envoyer test" class="button admin-theme">
				<input type="submit" name="send_option" value="Envoyer" class="button admin-theme">
			</form>

			<br><br>
			<hr>
			<br><br>

			<?php if ( $page_controler->is_campaign_funded() ): ?>
				<?php if ( $page_controler->get_campaign()->is_hidden() ): ?>
					<a href="#contacts" data-mailtype="end-success-private" class="button admin-theme show-notifications-end"><?php _e( "Envoyer les notifications de succ&egrave;s de campagne priv&eacute;e", 'yproject' ); ?></a><br><br>
				<?php else: ?>
					<a href="#contacts" data-mailtype="end-success-public" class="button admin-theme show-notifications-end"><?php _e( "Envoyer les notifications de succ&egrave;s de campagne publique", 'yproject' ); ?></a><br><br>
				<?php endif; ?>
			<?php else: ?>
				<a href="#contacts" data-mailtype="end-pending-goal" class="button admin-theme show-notifications-end"><?php _e( "Envoyer les notifications d'attente de validation de seuil de campagne", 'yproject' ); ?></a><br><br>
				<a href="#contacts" data-mailtype="end-failure" class="button admin-theme show-notifications-end"><?php _e( "Envoyer les notifications d'&eacute;chec de campagne", 'yproject' ); ?></a><br><br>
			<?php endif; ?>
		
			<form id="form-notifications-end" action="<?php echo admin_url( 'admin-post.php?action=send_project_notifications_end' ); ?>" method="POST" class="hidden align-left">
				<span id="notifications_content"></span><br>
				<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign()->ID; ?>">
				<input type="hidden" id="mail_type" name="mail_type" value="">
				<input type="submit" name="send_option" value="Envoyer test" class="button admin-theme">
				<input type="submit" name="send_option" value="Envoyer" class="button admin-theme">
			</form>
				
		</div>
	
	
		<br><br><br>
		
		<?php $campaign_emails = $page_controler->get_campaign_emails(); ?>
		<?php if ( !empty( $campaign_emails ) ): ?>
		<div class="field admin-theme">
			<b>Liste des emails envoyés en rapport avec la lev&eacute;e de fonds</b><br><br>
			<table>
				<tr>
					<td><strong>Date</strong></td>
					<td><strong>Destinataire</strong></td>
					<td><strong>Template</strong></td>
				</tr>

				<?php foreach ( $campaign_emails as $campaign_email ): ?>
				<tr>
					<td><?php echo $campaign_email[ 'date' ]; ?></td>
					<td><?php echo $campaign_email[ 'recipient' ]; ?></td>
					<td><?php echo $campaign_email[ 'template_str' ]; ?> (<?php echo $campaign_email[ 'template_id' ]; ?>)</td>
				</tr>
				<?php endforeach; ?>
			</table>
			
		<br><br>
		</div>
		<?php endif; ?>
		
		<?php $campaign_poll_answers = $page_controler->get_campaign()->get_api_data( 'poll_answers' ); ?>
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
						if ( !empty( $WDGUser ) ) {
							$name = $WDGUser->get_lastname() .' '. $WDGUser->get_firstname();
						}
					} else {
						$WDGOrganization = WDGOrganization::get_by_api_id( $investment_contract->investor_id );
						if ( !empty( $WDGOrganization ) ) {
							$name = $WDGOrganization->get_name();
						}
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
		
		<div class="field admin-theme">

			<b>Sondage lié à l'investissement en continu</b><br><br>

			<table>
				<tr>
					<td>Date</td>
					<td>Contexte</td>
					<td>Montant (contexte)</td>
					<td>Notifié si nv. lancement</td>
					<td>Notifié si nv. thème</td>
					<td>Inv. ponctuel</td>
					<td>Inv. mensuel</td>
					<td>Inv. trimestriel</td>
					<td>Inv. nv. lancement</td>
					<td>Inv. autre</td>
					<td>Inv. autre Txt</td>
					<td>Connu WDG</td>
					<td>Connu Projet</td>
					<td>Connu Autre</td>
					<td>Connu Autre Txt</td>
					<td>E-mail</td>
					<td>Age</td>
					<td>Code postal</td>
					<td>Sexe</td>
				</tr>

				<?php foreach ( $campaign_poll_answers as $answer ): ?>
					<?php if ( $answer->poll_slug != 'continuous' ) { continue; } ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo ( $answers_decoded->{ 'notifications' }->{ 'new-campaign' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'notifications' }->{ 'new-subject' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'invest-rythm' }->{ 'invest-ponctual' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'invest-rythm' }->{ 'invest-monthly' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'invest-rythm' }->{ 'invest-quarterly' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'invest-rythm' }->{ 'invest-campaign' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'invest-rythm' }->{ 'invest-other' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $answers_decoded->{ 'other-invest-rythm' }; ?></td>
						<td><?php echo ( $answers_decoded->{ 'known-by' }->{ 'known-by-wedogood' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'known-by' }->{ 'known-by-project' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->{ 'known-by' }->{ 'known-by-other' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $answers_decoded->{ 'other-known-by-source' }; ?></td>
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