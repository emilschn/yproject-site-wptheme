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
		<li class="selected"><a href="#projects"><?php _e("Projets et investissements", "yproject"); ?></a></li>
		<li><a href="#organizations"><?php _e("Organisations", "yproject"); ?></a></li>
	</ul>
	
</nav>