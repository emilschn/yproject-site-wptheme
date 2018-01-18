<?php
get_header();
$display_loggedin_user = true;
if ($display_loggedin_user) {
	$result_form = WDGFormUsers::wallet_to_bankaccount();
}
?>

<div id="content">
	<div class="padder">
		
		<?php if ($result_form != FALSE): ?>
			<?php if ($result_form == "success"): ?>
				<div class="success">Transfert effectué</div>
			<?php else: ?>
				<div class="errors center"><?php echo $result_form; ?></div>
			<?php endif; ?>
		<?php endif; ?>

		<header id="item-header">

			<div id="item-header-container">
				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
			</div>
		    
		</header>

		<div>
		    
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

<?php get_footer();
