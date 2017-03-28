<?php 
/**
 * Template Name: Single User Account
 *
 */
?>

<?php
if (!is_user_logged_in()) {
	wp_redirect( home_url( '/connexion' ) . '?redirect-page=mon-compte' );
}
$display_loggedin_user = true;
if ($display_loggedin_user) {
	$result_form = WDGFormUsers::wallet_to_bankaccount();
}
$page_modify = get_page_by_path('modifier-mon-compte');
?>

<?php get_header(); ?>

<div id="content">
    
	<div class="padder padder-top">
		
		<?php if ($result_form != FALSE): ?>
			<?php if ($result_form == "success"): ?>
				<div class="success">Transfert effectué</div>
			<?php else: ?>
				<div class="errors center"><?php echo $result_form; ?></div>
			<?php endif; ?>
		<?php endif; ?>

		<header id="item-header">
		    
			<?php if ($display_loggedin_user): ?>
			<div>
				<div id="settings-img">
					<a href="<?php echo get_permalink($page_modify->ID); ?>"><img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
				</div>
			</div>
			<?php endif; ?>

			<div id="item-header-container">
				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
			</div>
		    
		</header>

		<div>
		    
			<ul id="item-submenu">
				<li id="item-submenu-projects" class="selected"><a href="#projects"><?php _e("Projets et investissements", "yproject"); ?></a></li>
				<li id="item-submenu-community"><a href="#community"><?php _e("Communaut&eacute;", "yproject"); ?></a></li>
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

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer();