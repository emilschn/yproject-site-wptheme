<?php
get_header( 'buddypress' );
$display_loggedin_user = (bp_loggedin_user_id() == bp_displayed_user_id());
$page_modify = get_page_by_path('modifier-mon-compte');
?>

<div id="content">
	<div class="padder">

		<header id="item-header">
		    
			<?php if ($display_loggedin_user): ?>
			<div class="center">
				<div id="settings-img">
					<a href="<?php echo get_permalink($page_modify->ID); ?>"><img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
				</div>
			</div>
			<?php endif; ?>

			<div id="item-header-container" class="center">
				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
			</div>
		    
		</header>

		<div class="center">
		    
			<ul id="item-submenu">
				<li id="item-submenu-projects" class="selected"><a href="#projects"><?php _e("Projets et investissements", "yproject"); ?></a></li>
				<li id="item-submenu-community"><a href="#community"><?php _e("Communaut&eacute;", "yproject"); ?></a></li>
				<li id="item-submenu-activity"><a href="#activity"><?php _e("Activit&eacute;s", "yproject"); ?></a></li>
			</ul>


			<div id="item-body">
				<div id="item-body-projects" class="item-body-tab">
					<?php locate_template( array( 'members/single/projects.php'  ), true ); ?>
				</div>

				<div id="item-body-community" class="item-body-tab" style="display:none">
					<?php locate_template( array( 'members/single/community.php'  ), true ); ?>
				</div>
			    
				<div id="item-body-activity" class="item-body-tab" style="display:none">
					<?php locate_template( array( 'members/single/activity.php'  ), true ); ?>
				</div>
			</div>

		</div>

	</div>
</div>

<?php get_footer( 'buddypress' ); ?>
