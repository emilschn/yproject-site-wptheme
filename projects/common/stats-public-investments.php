<?php function print_investments($id_campaign, $is_advanced = FALSE) { 
    $campaign = atcf_get_campaign($id_campaign);
    $voc = $campaign->funding_type_vocabulary();
    ?>

    <div class="ajax-investments-load-short" data-value="<?php echo $id_campaign?>">
    <h3><?php _e("G&eacute;n&eacute;ral", 'yproject'); ?></h3>
    <div class="ajax-data-inv-loader-img"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>
    <p><strong class="data-inv-count_validate_investments">&hellip;</strong> <?php echo $voc['investor_action']?>s <?php _e("valid&eacute;", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'e';}?>s par 
    <strong class="data-inv-count_validate_investors">&hellip;</strong> <?php echo $voc['investor_name']?>s <?php _e("distincts", 'yproject'); ?>.<br />
    <?php if ($is_advanced) {?>
        <strong class="data-inv-count_not_validate_investments">&hellip;</strong> <?php echo $voc['investor_action']?>s <?php _e("non-valid&eacute;", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'e';}?>s<br/>
    <?php } ?>
    <?php _e("Les", 'yproject'); ?> <?php echo $voc['investor_name']?>s <?php _e("ont", 'yproject'); ?> <strong class="data-inv-average_age">&hellip;</strong> <?php _e("ans de moyenne", 'yproject'); ?>.<br />
    <?php _e("Ce sont", 'yproject'); ?> <strong class="data-inv-percent_female">&hellip;</strong><?php _e("% de femmes et", 'yproject'); ?> <strong class="data-inv-percent_male">&hellip;</strong><?php _e("% d&apos;hommes", 'yproject'); ?>.<br />
    <strong class="data-campaign_days_remaining"><?php echo $campaign->time_remaining_fullstr()?></strong><br />
    <?php echo ucfirst($voc['investor_action'])?> <?php _e("moyen", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'ne';}?> <?php _e("par personne :", 'yproject'); ?> <strong class="data-inv-average_invest">&hellip;</strong> &euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> <?php _e("minimal", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-min_invest">&hellip;</strong> &euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> <?php _e("m&eacute;dian", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-median_invest">&hellip;</strong> &euro;<br />
    <?php echo ucfirst($voc['investor_action'])?> <?php _e("maximal", 'yproject'); ?><?php if ($voc['action_feminin']){echo 'e';}?> : <strong class="data-inv-max_invest">&hellip;</strong> &euro;<br />
    
    </p>

	<?php if ( !$campaign->get_hide_investors() ): ?>
    <h3><?php _e("Ils ont", 'yproject'); ?> <?php echo $voc['investor_verb']?></h3>
    <p class="data-inv-investors_string">&hellip;</p>
    </div>
	<?php endif; ?>
    
<?php }?>
