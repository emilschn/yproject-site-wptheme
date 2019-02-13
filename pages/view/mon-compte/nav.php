<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
?>

<nav>
	<div class="nav-header">
        <?php _e( "Bonjour", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?> !
	</div>
	
	<ul class="nav-menu">
		<li id="menu-item-wallet" class="selected"><a href="#wallet" data-tab="wallet"><?php _e( "Mon porte-monnaie", 'yproject' ); ?></a></li>
		<li id="menu-item-investments"><a href="#investments" data-tab="investments" data-usertype="user" data-userid="<?php echo $WDGUser_displayed->get_wpref(); ?>"><?php _e( "Mes investissements", 'yproject' ); ?></a></li>
		<li id="menu-item-documents"><a href="#documents" data-tab="documents"><?php _e( "Mes documents", 'yproject' ); ?></a></li>
		<li id="menu-item-parameters"><a href="#parameters" data-tab="parameters"><?php _e( "Mes informations personnelles", 'yproject' ); ?></a></li>
		<li id="menu-item-identitydocs"><a href="#identitydocs" data-tab="identitydocs"><?php _e( "Mes justificatifs d'identit&eacute;", 'yproject' ); ?></a></li>
		<li id="menu-item-bank"><a href="#bank" data-tab="bank"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></a></li>
		<li id="menu-item-authentication"><a href="#authentication" data-tab="authentication" <?php if ( !$WDGUser_displayed->is_lemonway_registered() && !$page_controler->has_user_project_list() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Mon authentification", 'yproject' ); ?></a></li>
		<li id="menu-item-notifications"><a href="#notifications" data-tab="notifications"><?php _e( "Mes notifications", 'yproject' ); ?></a></li>
	</ul>
	
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
		<?php foreach ( $list_current_organizations as $WDGOrganization ): ?>
			<div class="nav-header header-orga"><?php echo $WDGOrganization->get_name(); ?></div>
			<ul class="nav-menu">
				<li id="menu-item-orga-wallet-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-wallet-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-wallet-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Porte-monnaie", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-investments-<?php echo $WDGOrganization->get_wpref(); ?>" data-usertype="organization" data-userid="<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Investissements", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-documents-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-documents-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-documents-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Documents", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Informations", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-identitydocs-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Justificatifs d'identification", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Coordonn&eacute;es bancaires", 'yproject' ); ?></a></li>
				<li id="menu-item-orga-authentication-<?php echo $WDGOrganization->get_wpref(); ?>"><a href="#orga-authentication-<?php echo $WDGOrganization->get_wpref(); ?>" data-tab="orga-authentication-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Authentification", 'yproject' ); ?></a></li>
			</ul>
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if ( $page_controler->has_user_project_list() ): ?>
		<div class="nav-header"><?php _e( "Projets li&eacute;s", 'yproject' ); ?></div>

		<ul class="project-list">
		<?php $project_list = $page_controler->get_user_project_list(); ?>
		<?php foreach ( $project_list as $project ): ?>

			<li><a href="<?php echo $project[ 'link' ]; ?>" <?php if ( !$project[ 'authentified' ] ): ?>class="needs-authentication"<?php endif; ?>><?php echo $project[ 'name' ]; ?></a></li>

		<?php endforeach; ?>
		</ul>

	<?php endif; ?>
	
</nav>