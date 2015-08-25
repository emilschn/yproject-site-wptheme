<?php global $campaign, $feedback, $preview; ?>
<h1>Envoyer un message à votre communauté</h1>
<?php 
    if ($preview!=''){
        echo '<h3>Aperçu du message</h3><div class="preview">'.$preview.'</div>';
    }

    if ($feedback!=''){
        echo '<div class="feedback">'.$feedback.'</div>';
    }
    
    
?>
<p> Vous pouvez envoyer ici un mail aux différents groupes de la communaut&eacute; de votre projet.<br/></p>
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
    </span>
    
    <strong>Objet du mail : </strong> <input typ="text" name="mail_title" 
         value="<?php if (isset($_POST['mail_title'])){ echo $_POST['mail_title'];}?>"><br/>
    <br/>
<?php
if (isset($_POST['mail_content'])){
    $previous_content = $_POST['mail_content'];
} else {
    $previous_content = '';
}

wp_editor( $previous_content, 'mail_content', 
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
    <button type="submit" name="send_mail" value="preview" class="button"><?php _e('Prévisualisation', 'yproject'); ?></button>
    <button type="submit" name="send_mail" value="send" class="button"><?php _e('Envoyer le message', 'yproject'); ?></button><br />

</form>