<div id="yp_admin_bar">		
	<div class="center">		
		<?php // Lien page profil ?>		
		<a href="<?php echo bp_loggedin_user_domain(); ?>"><?php echo __('Mon profil', 'yproject'); ?></a>		
		.:|:.		
		<?php $page_investments = get_page_by_path('mes-investissements'); // Lien page investissements  ?>		
		<a href="<?php echo get_permalink($page_investments->ID); ?>"><?php echo __('Mes investissements', 'yproject'); ?></a>		
		.:|:.		
		<?php $page_update = get_page_by_path('modifier-mon-compte'); // Lien page paramètres ?>		
		<a href="<?php echo get_permalink($page_update->ID); ?>"><?php echo __('Param&egrave;tres', 'yproject'); ?></a>		
		.:|:.
		<?php  // Lien page paramètres ?>		
		<a href="<?php echo bp_loggedin_user_domain(); ?>settings/notifications/"><?php echo __('Notifications', 'yproject'); ?></a>		
	</div>		
</div>		
