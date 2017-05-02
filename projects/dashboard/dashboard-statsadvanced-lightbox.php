<h1 class="expandator" data-target="votes">Votes <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></h1>
<div id="extendable-votes" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-votes.php"), true );?>
</div>

<?php $campaign = atcf_get_current_campaign(); ?>
<h1 class="expandator" data-target="investments"><?php echo ucfirst($campaign->funding_type_vocabulary()['investor_action']);?>s <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus" /></h1>
<div id="extendable-investments" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-investments.php"), true );?>
</div>