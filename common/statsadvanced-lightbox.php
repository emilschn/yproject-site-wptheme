
<h1 class="expandator" data-target="general">G&eacute;n&eacute;ral <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></h1>
<div id="extendable-general" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-main.php"), true );?>
</div>

<h1 class="expandator" data-target="votes">Votes <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus"/></h1>
<div id="extendable-votes" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-votes.php"), true );?>
    <a href="#votecontact" class="wdg-button-lightbox-open button" data-lightbox="votecontact">&#x1f50e; Liste des votants</a>
</div>

<h1 class="expandator" data-target="investments">Investissements <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/plus.png" alt="signe plus" /></h1>
<div id="extendable-investments" class="expandable">
    <?php locate_template( array("projects/single-stats-advanced-investments.php"), true );?>
    <a href="#listinvestors" class="wdg-button-lightbox-open button" data-lightbox="listinvestors">&#x1f50e; Liste des investisseurs</a>
</div>