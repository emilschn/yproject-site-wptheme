<?php global $campaign, $feedback, $preview, $feedback_sendautomail; ?>
<?php $dashboard_page = get_page_by_path("tableau-de-bord"); ?>

<h1>Envoyer un message à votre communauté</h1>

<?php if ($preview != ''): ?>
	<h3>Aperçu du message</h3>
	<div class="preview"><?php echo $preview; ?></div>
<?php endif; ?>

<?php if ($feedback != ''): ?>
	<div class="feedback"><?php echo $feedback; ?></div>
<?php endif; ?>
<?php if ($feedback_sendautomail != ''): ?>
	<div class="feedback"><?php echo $feedback_sendautomail; ?></div>
<?php endif; ?>

<p>Vous pouvez envoyer ici un mail aux différents groupes de la communaut&eacute; de votre projet.<br/></p>
<p><em>Les utilisateurs qui se sont d&eacute;sabonn&eacute;s de vos actualit&eacute;s 
    ne recevront pas le message. <br/>
    Pour envoyer un message important à vos <?php echo $campaign->funding_type_vocabulary()['investor_name'].'s'; ?>, envoyez directement 
    un mail &agrave; partir des adresses r&eacute;cup&eacute;rables
    dans la liste des investisseurs.</em>
</p>

<form id="direct-mail" method="POST" action="?campaign_id=<?php echo $campaign->ID ?>#dashboardmail">
    <strong>Envoyer à :</strong><br/>
    <div class="selector">
        <label><div class="selection">
            <input type="checkbox" name="jycrois" id="jycrois-send-mail-selector"
                   <?php if (isset($_POST['jycrois'])){echo 'checked';}?>>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/>
            "J'y crois"
        </div></label>

        <label><div class="selection">
            <input type="checkbox" name="voted" id="voted-send-mail-selector"
                   <?php if (isset($_POST['voted'])){echo 'checked';}?>>
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/>
            Votants
        </div></label>
        
        <span id="ajax-id-investors-load" class="ajax-investments-load" data-value="<?php echo $campaign->ID?>">
        <label><div class="selection">
            <img class="ajax-data-inv-loader-img" style="height:32px" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
            <input style="display:none" type="checkbox" name="invested" id="invested-send-mail-selector"
                   <?php if (isset($_POST['invested'])){echo 'checked';}?>>
            <input type="hidden" name="investors_id" value="" id="invested-send-mail-list">
            <img id="img-investors"  src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/>
            <?php echo ucfirst($campaign->funding_type_vocabulary()['investor_name']).'s'; ?>
        </div></label>
	</div>
    
    <strong>Objet du mail : </strong> <input typ="text" name="mail_title" 
         value="<?php if (isset($_POST['mail_title'])){ echo $_POST['mail_title'];}?>"><br/>
    <br/>
	
	<?php
	$previous_content = filter_input(INPUT_POST, 'mail_content');
	wp_editor( $previous_content, 'mail_content', 
		array(
			'media_buttons' => true,
			'quicktags'     => false,
			'tinymce'       => array(
				'plugins'				=> 'paste, wplink, textcolor',
				'paste_remove_styles'   => true
			)
		)
	);
	?>
    <br/>
	
	<p class="align-center">
		<button type="submit" name="send_mail" value="preview" class="button"><?php _e('Prévisualisation', 'yproject'); ?></button>
		<button type="submit" name="send_mail" value="send" class="button"><?php _e('Envoyer le message', 'yproject'); ?></button>
	</p>
</form>


<h2 class="expandator" data-target="automail">+ <?php _e("Messages automatiques", 'yproject'); ?></h2>
<div id="extendable-automail" class="expandable">
	<strong><?php _e("Vous pouvez utiliser les variables suivantes :", 'yproject'); ?></strong>
	<ul>
		<li><i>%projectname%</i> : Nom du projet</li>
		<li><i>%projecturl%</i> : Adresse du projet</li>
		<li><i>%projectauthor%</i> : Nom du porteur de projet</li>
		<li><i>%username%</i> : Nom de l'utilisateur</li>
		<li><i>%investwish%</i> : Intention d'investissement</li>
	</ul>
	<br /><br />
	
	<h3><?php _e("Envoyer un message aux votants", 'yproject'); ?></h3>
	<form method="POST" action="<?php echo get_permalink($dashboard_page); ?>?campaign_id=<?php echo $campaign->ID ?>#dashboardmail">
		
		<?php $minwish_automail_voters = filter_input(INPUT_POST, 'automailvoters_minwish'); ?>
		<label for="automailvoters_minwish"><?php _e("qui ont &eacute;mis une intention d'investissement d'au moins ", 'yproject'); ?>
			<input type="text" id="automailvoters_minwish" name="automailvoters_minwish" value="<?php echo $minwish_automail_voters; ?>" placeholder="0"> &euro;
		</label><br/><br/>
		
		<?php $object_automail_voters = filter_input(INPUT_POST, 'automailvoters_object'); ?>
		<label for="automailvoters_object"><?php _e("Objet :", 'yproject'); ?>
			<input type="text" id="automailvoters_object" name="automailvoters_object" value="<?php echo $object_automail_voters; ?>">
		</label><br/><br/>
		
		<?php
		$content_automail_voters = filter_input(INPUT_POST, 'automailvoters_content');
		if (empty($content_automail_voters)) {
			$content_automail_voters = __("Bonjour ", 'yproject') . "%username%,<br />";
			$content_automail_voters .= __("Vous avez vot&eacute; sur le projet ", 'yproject') . "%projectname%" . __(" et nous vous en remercions.", 'yproject') ."<br />";
			$content_automail_voters .= __("Vous avez indiqu&eacute; vouloir investir ", 'yproject') . "%investwish%&euro;" . __(". Nous avons besoin de vous d&egrave;s maintenant !", 'yproject') ."<br />";
			$content_automail_voters .= __("Nous vous donnons rendez-vous &agrave; l'adresse ", 'yproject') . "%projecturl%" . __(" pour suivre la campagne !", 'yproject') ."<br />";
			$content_automail_voters .= __("A bient&ocirc;t !", 'yproject') ."<br />";
			$content_automail_voters .= "%projectauthor%";
		}
		wp_editor( $content_automail_voters, 'automailvoters_content', 
			array(
				'media_buttons' => true,
				'quicktags'     => false,
				'tinymce'       => array(
					'plugins'				=> 'paste, wplink, textcolor',
					'paste_remove_styles'   => true
				)
			)
		);
		?>
		<p class="align-center">
			<button type="submit" name="send_automail" value="send" class="button"><?php _e('Envoyer', 'yproject'); ?></button>
		</p>
	</form>
</div>