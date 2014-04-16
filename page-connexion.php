<?php
    if (is_user_logged_in()) wp_redirect(home_url());
?>
<?php get_header(); ?>

    <div id="content">
	<div class="padder">
	   <?php locate_template( array( 'basic/basic-header.php' ), true ); ?>
	    
    <div id="post_bottom_bg">
	<div style="width: 450px;" id="post_bottom_content" class="center_small">
	    <div style="width: 450px;" class="left post_bottom_desc_small">
		<div class="login_fail">
		<?php if (isset($_GET["login"]) && $_GET["login"] == "failed") {?>
		    <?php _e('Erreur d&apos;identification', 'yproject'); ?>
		<?php } ?>
		</div>

		<div style="text-transform: uppercase; margin-bottom: 10px;" id="submenu_item_connection_login"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_noir_connexion.jpg" class="vert-align" width="25" height="25" />&nbsp;Connexion</div>
		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
		    <label class="standard-label"><?php _e('Identifiant', 'yproject'); ?></label>
		    <input style="margin-bottom: 5px; width: 254px;" type="text" name="log" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" />
		    <br />


		    <label class="standard-label"><?php _e('Mot de passe', 'yproject'); ?></label>
		    <input type="password" name="pwd" class="input" value="" /> 
		    <input type="submit" name="wp-submit" id="sidebar-wp-submit" style="width: 100px; background: #FFF;" value="<?php _e('Connexion', 'yproject'); ?>" />
		    <br />
		    <?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
		    (<a style="color: #333333; text-align: right; font-size: 10px; font-style: italic;" href="<?php echo get_permalink($page_forgotten->ID); ?>">Mot de passe oubli&eacute;</a>)

		    <p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
		    </p>

		    <input type="hidden" name="testcookie" value="1" />
		</form>

		<hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

		<div style="margin-left: 130px;" id="connexion_facebook_container"><a href="javascript:void(0);" class="social_connect_login_facebook"><img style="border-right: 1px solid #FFFFFF; width:25px; height:25px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" class="vert-align"/><span style=" font-size:12px;">&nbsp;Se connecter avec Facebook</span></a></div>

		<div class="hidden"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>

		<hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>
			    
		<?php $page_connexion_register = get_page_by_path('register'); ?>

		<div class="post_bottom_buttons_connexion" ><div style="margin-left: 130px;" id="submenu_item_connection_register" class="dark">
		<a href="<?php echo get_permalink($page_connexion_register->ID); ?>"><img width="25" height="25" src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blc_connexion.jpg"><span style="font-size: 9pt; vertical-align: 8px; color: #FFF; ">Cr&eacute;er un compte</span></a></div></div>

		<br />
	    </div>

	    <div style="clear: both"></div>
	</div>
    </div>
	</div>
    </div>

<?php get_footer(); ?>