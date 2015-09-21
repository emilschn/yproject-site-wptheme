<?php function display_photoreporter_menu($page = 'project') { ?>
<nav class="menu-client">
    <div class="center align-center">
	<a href="#">Festival Photo-Reporter</a> |
	
	<?php if ($page == 'projectlist'): ?>
		<strong>Financer un reportage</strong>
	<?php else: 
		$page_photoreporter = get_page_by_path('myphotoreporter'); ?>
		<a href="<?php echo get_permalink($page_photoreporter->ID); ?>">Financer un reportage</a>
	<?php endif; ?> |
		
	<a href="#">Nous contacter</a>
	<a href="#" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.png" width="20" height="20" alt="logo facebook" /></a>
	<a href="#" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.png" width="20" height="20" alt="logo twitter" /></a>
    </div>
</nav>
<?php }