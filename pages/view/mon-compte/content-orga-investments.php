<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>
<h2><?php _e( 'account.investments.orga.TITLE', 'yproject' ); ?>  <?php echo $WDGOrganization->get_name(); ?></h2>


<div id="investment-synthesis-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis hidden">
	<span class="publish-count">0</span> <?php _e( 'account.investments.INVESTMENTS_VALIDATED', 'yproject' ); ?><span class="pending-str hidden">, <span class="pending-count">0</span> <?php _e( 'account.investments.INVESTMENTS_PENDING', 'yproject' ); ?></span>.
</div>

<div id="investment-synthesis-pictos-<?php echo $WDGOrganization->get_wpref(); ?>" class="investment-synthesis-pictos hidden">
	<div class="funded-projects">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="80" height="80">
		<span class="data">0</span><br>
		<span class="txt"><?php _e( 'account.investments.PROJECTS_FUNDED', 'yproject' ); ?></span>
	</div>
	
	<div class="amount-invested">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="fleche" width="81" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.INVESTED', 'yproject' ); ?></span>
	</div>
	
	<div class="royalties-received">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="monnaie" width="97" height="80">
		<span class="data">0 &euro;</span><br>
		<span class="txt"><?php _e( 'account.investments.ROYALTIES_RECEIVED', 'yproject' ); ?></span>
		
	</div>
</div>
	
<div id="ajax-loader-<?php echo $WDGOrganization->get_wpref(); ?>" class="center" style="text-align: center;"><img id="ajax-loader-img-<?php echo $WDGOrganization->get_wpref(); ?>" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

