<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2 class="expandator" data-target="votes"><?php _e( "Votes", 'yproject' ); ?> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"></h2>
<div id="extendable-votes" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-votes.php"), true );?>
</div>

<?php $campaign = atcf_get_current_campaign(); ?>
<h2 class="expandator" data-target="investments"><?php _e( "Investissements", 'yproject' ); ?> <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"></h2>
<div id="extendable-investments" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-investments.php"), true );?>
</div>