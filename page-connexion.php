<?php
    if (is_user_logged_in()) wp_redirect(home_url());
?>
<?php get_header(); ?>
<?php require_once("common.php"); ?>

    <div id="content">
	<div class="padder">
	    <?php printMiscPagesTop("Connexion"); ?>
	    
    <div id="post_bottom_bg">
	<div id="post_bottom_content" class="center_small">
	    <div class="left post_bottom_desc_small">
		<div class="login_fail">
		<?php if (isset($_GET["login"]) && $_GET["login"] == "failed") {?>
		    <?php _e('Erreur d&apos;identification', 'yproject'); ?>
		<?php } ?>
		</div>

		<div id="submenu_item_connection_login"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_noir_connexion.jpg" class="vert-align" width="25" height="25" />&nbsp;Connexion (<a href="<?php echo get_permalink($page_forgotten->ID); ?>">Mot de passe oubli&eacute;</a>)</div>
		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
		    <label class="standard-label"><?php _e('Identifiant', 'yproject'); ?></label>
		    <input type="text" name="log" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" />
		    <br />

		    <label class="standard-label"><?php _e('Mot de passe', 'yproject'); ?></label>
		    <input type="password" name="pwd" class="input" value="" />
		    <br />

		    <p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
			<input type="submit" name="wp-submit" value="<?php _e('Connexion', 'yproject'); ?>" />
		    </p>

		    <input type="hidden" name="testcookie" value="1" />
		</form>

		<div id="connexion_facebook_container"><a href="javascript:void(0);" class="social_connect_login_facebook"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" class="vert-align" width="25" height="25"/><span>&nbsp;Se connecter avec Facebook</span></a></div>
		<div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
			    
		<br />
		<?php $page_connexion_register = get_page_by_path('register'); ?>
		<div class="post_bottom_buttons"><div class="dark"><a href="<?php echo get_permalink($page_connexion_register->ID); ?>">Cr&eacute;er un compte</a></div></div>
	    </div>

	    <div style="clear: both"></div>
	</div>
    </div>
	</div>
    </div>

<?php get_footer(); ?>