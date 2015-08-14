<?php global $campaign; ?>
<h1>Envoyer un message à votre communauté</h1>
<p> Vous pouvez envoyer ici un mail aux différents groupes de la communaut&eacute; de votre projet.<br/></p>
<p><em>Les utilisateurs qui se sont d&eacute;sabonn&eacute;s de vos actualit&eacute;s 
    ne recevront pas le message. <br/>
    Pour envoyer un message important à vos <?php echo $campaign->funding_type_vocabulary()['investor_name'].'s'; ?>, envoyez directement 
    un mail &agrave; partir des adresses r&eacute;cup&eacute;rables
    dans la liste des investisseurs.</em>
</p>

<form id="direct-mail">
    Envoyer à :
    <label>
    <input type="checkbox" name="filter" value="jycrois">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/>
    Mention "J'y crois"
    </label>

    <label>
    <input type="checkbox" name="filter" value="voted">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/>
    Votants
    </label>

    <label>
    <input type="checkbox" name="filter" value="invested">
    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/>
    <?php echo ucfirst($campaign->funding_type_vocabulary()['investor_name']).'s'; ?>
    </label>

<?php
wp_editor( '', 'postcontent', 
        array(
                'media_buttons' => true,
                'quicktags'     => false,
                'tinymce'       => array(
                    'plugins'		    => 'paste',
                    'paste_remove_styles'   => true
                )
        ) 
);
?>
    <input type="submit" value="<?php _e('Envoyer le message', 'yproject'); ?>" class="button" />
</form>
