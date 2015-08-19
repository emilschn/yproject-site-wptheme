<?php global $campaign; ?>
<h1>Envoyer un message à votre communauté</h1>
<p> Vous pouvez envoyer ici un mail aux différents groupes de la communaut&eacute; de votre projet.<br/></p>
<p><em>Les utilisateurs qui se sont d&eacute;sabonn&eacute;s de vos actualit&eacute;s 
    ne recevront pas le message. <br/>
    Pour envoyer un message important à vos <?php echo $campaign->funding_type_vocabulary()['investor_name'].'s'; ?>, envoyez directement 
    un mail &agrave; partir des adresses r&eacute;cup&eacute;rables
    dans la liste des investisseurs.</em>
</p>

<form id="direct-mail" method="POST" action="?campaign_id=<?php echo $campaign->ID ?>">
    <strong>Envoyer à :</strong><br/>
    <div class="selector">
        <label><div class="selection">
            <input type="checkbox" name="jycrois" id="jycrois-send-mail-selector">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/>
            "J'y crois"
        </div></label>

        <label><div class="selection">
            <input type="checkbox" name="voted" id="voted-send-mail-selector">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/>
            Votants
        </div></label>

        <label><div class="selection">
            <input type="checkbox" name="invested" id="invested-send-mail-selector">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/>
            <?php echo ucfirst($campaign->funding_type_vocabulary()['investor_name']).'s'; ?>
        </div></label>
    </div>
    
    <strong>Objet du mail : </strong> <input typ="text" name="mail_title"><br/>
    <br/>
<?php
wp_editor( '', 'postcontent', 
    array(
        'media_buttons' => true,
        'quicktags'     => false,
        'tinymce'       => array(
            'plugins'		    => 'paste, wplink, textcolor',
            'paste_remove_styles'   => true
        )
    )
);
?>
    <br/>
    <input type="hidden" name="send_mail" value="1">
    <input type="submit" value="<?php _e('Envoyer le message', 'yproject'); ?>" class="button" />
</form>