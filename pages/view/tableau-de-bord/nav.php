<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<nav>
	
	<ul class="nav-menu">
		<li id="menu-item-home" class="selected"><a href="#home" data-tab="home"><?php echo $page_controler->get_campaign_name(); ?></a></li>
		
		<li><?php _e( "Lev&eacute;e de fonds", 'yproject' ); ?></li>
		<li id="menu-item-stats"><a href="#stats" data-tab="stats"><?php _e( "Statistiques", 'yproject' ); ?></a></li>
		<li id="menu-item-contacts"><a href="#contacts" data-tab="contacts"><?php _e( "Contacts", 'yproject' ); ?></a></li>
		<li id="menu-item-presentation"><a href="#presentation" data-tab="presentation"><?php _e( "Pr&eacute;sentation", 'yproject' ); ?></a></li>
		<li id="menu-item-news"><a href="#news" data-tab="news"><?php _e( "Actualit&eacute;s", 'yproject' ); ?></a></li>
		<li id="menu-item-tools"><a href="#tools" data-tab="tools"><?php _e( "Guide et outils", 'yproject' ); ?></a></li>
		<li id="menu-item-documents"><a href="#documents" data-tab="documents"><?php _e( "Documents", 'yproject' ); ?></a></li>
		<li id="menu-item-royalties"><a href="#royalties" data-tab="royalties"><?php _e( "Royalties", 'yproject' ); ?></a></li>
		
		<li><?php _e( "Param&egrave;tres", 'yproject' ); ?></li>
		<li id="menu-item-author"><a href="#author" data-tab="author"><?php _e( "Repr&eacute;sentant l&eacute;gal", 'yproject' ); ?></a></li>
		<li id="menu-item-organization"><a href="#organization" data-tab="organization"><?php _e( "Organisation", 'yproject' ); ?></a></li>
		<li id="menu-item-team"><a href="#team" data-tab="team"><?php _e( "&Eacute;quipe", 'yproject' ); ?></a></li>
		<li id="menu-item-finance"><a href="#finance" data-tab="finance"><?php _e( "Financement", 'yproject' ); ?></a></li>
		<li id="menu-item-contracts"><a href="#contracts" data-tab="contracts"><?php _e( "Contrats", 'yproject' ); ?></a></li>
		<li id="menu-item-campaign"><a href="#campaign" data-tab="campaign"><?php _e( "Campagne", 'yproject' ); ?></a></li>
		
	</ul>
	
</nav>