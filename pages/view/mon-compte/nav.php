<?php global $page_controler; ?>

<nav>
	
	<div class="nav-header">
		<?php _e( "Bonjour", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?> !
		<br />

		<?php if ( $page_controler->has_user_project_list() ): ?>

			<ul class="project-list">
			<?php $project_list = $page_controler->get_user_project_list(); ?>
			<?php foreach ( $project_list as $project ): ?>

				<li><a href="<?php echo $project[ 'link' ]; ?>"><?php echo $project[ 'name' ]; ?></a></li>

			<?php endforeach; ?>
			</ul>

		<?php endif; ?>
	</div>
	
	<ul class="nav-menu">
		<li id="menu-item-projects" class="selected"><a href="#projects" data-tab="projects"><?php _e("Projets et investissements", "yproject"); ?></a></li>
		<li id="menu-item-organizations"><a href="#organizations" data-tab="organizations"><?php _e("Organisations", "yproject"); ?></a></li>
		<li id="menu-item-parameters"><a href="#parameters" data-tab="parameters"><?php _e("Param&egrave;tres", "yproject"); ?></a></li>
	</ul>
	
</nav>