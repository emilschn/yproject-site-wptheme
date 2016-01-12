<?php 

/**
 * Charge des données : nombre de j'y crois, de votes et d'investissements
 * @global type $nb_jcrois
 * @global type $nb_votes
 * @global type $nb_invests
 */
function block_community_data(){
    global $campaign, $nb_jcrois, $nb_votes, $nb_invests;
    //Recuperation du nombre de j'y crois
	$nb_jcrois = $campaign->get_jycrois_nb();
    //Recuperation du nombre de votants
	$nb_votes = $campaign->nb_voters();
    //Recuperation du nombre d'investisseurs
	$nb_invests = $campaign->backers_count();
}

function block_community_lightbox(){
    echo do_shortcode('[yproject_votecontact_lightbox]');
    echo do_shortcode('[yproject_listinvestors_lightbox]');
	
	ob_start();
	locate_template('common/dashboard-mail-lightbox.php', true);
	$content_dashboard_mail_lightbox = ob_get_contents();
	ob_end_clean();
	echo do_shortcode('[yproject_lightbox id="dashboardmail"]' .$content_dashboard_mail_lightbox . '[/yproject_lightbox]');
}

function print_block_community() { 
    global $campaign,
            $nb_jcrois,
            $nb_votes,
            $nb_invests,
            $stylesheet_directory_uri; ?>

<div id ="block-community" class="block">
    <div class="head">Communaut&eacute;</div>
    <div class="body" style="text-align:center">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/good.png"/><br/>
            <strong><?php echo $nb_jcrois?></strong> y croi<?php if($nb_jcrois>1){echo 'en';}?>t</div>
        <a href="#votecontact" class="wdg-button-lightbox-open" data-lightbox="votecontact">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodvote.png"/><br/>
            <strong><?php echo $nb_votes?></strong> <?php if($nb_votes>1){echo 'ont';} else {echo 'a';}?> vot&eacute;
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></div>
            </a>
        <a href="#listinvestors" class="wdg-button-lightbox-open" data-lightbox="listinvestors">
        <div class="card-com"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/goodmains.png"/><br/>
            <strong><?php echo $nb_invests?></strong> <?php if($nb_invests>1){echo 'ont';} else {echo 'a';}?> <?php echo $campaign->funding_type_vocabulary()['investor_verb'];?>
            <img src="<?php echo $stylesheet_directory_uri; ?>/images/plus.png" alt="signe plus"/></div>
            </a>
    <div class="clear"></div>
        <div class="list-button">
           <a href="#dashboardmail" class="wdg-button-lightbox-open button" data-lightbox="dashboardmail">&#9993; Envoyer un mail</a>
        </div>
    </div>
</div>

<?php } ?>