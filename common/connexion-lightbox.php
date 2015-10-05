<div id="post_bottom_content" class="center_small align-center">
	<?php if (WDGUser::has_login_errors()): ?>
	<div class="errors">
		<?php echo WDGUser::display_login_errors(); ?>
	</div>
	<?php endif; ?>
    
        <form method="post" action="" name="login-form" id="sidebar-login-form" class="standard-form">
            <input id="identifiant" type="text" name="log" placeholder="Identifiant ou e-mail" value="<?php if (isset($_POST["log"])) echo $_POST["log"]; ?>" />
            <br />

            <input id="password" type="password" name="pwd" placeholder="Mot de passe" value="" style="margin: 5px;" />
            <br />
	    
	    <input id="sidebar-rememberme" type="checkbox" name="rememberme" value="forever" />
	    <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
	    <br />
            
            <div id="submit-center">
                <input type="submit"  name="wp-submit" id="sidebar-wp-submit-lightbox" id="connect" value="<?php _e('Connexion', 'yproject'); ?>" style="margin: 5px;" />
                <input type="hidden" id="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
		<input type="hidden" name="login-form" value="1" />
            </div>
	    
            <div id="sidebar-login-form-lightbox">
		<?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
		<a href="<?php echo get_permalink($page_forgotten->ID); ?>" >(Mot de passe oubli&eacute;)</a>
            </div>
            <br />
        </form>
            
        <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

        <div id="connexion_facebook_container">
            <a href="javascript:void(0);" class="social_connect_login_facebook"><img style="border-right: 1px solid #FFFFFF; width:25px; height:25px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" alt="connexion facebook"class="vert-align"/><span style=" font-size:12px;">&nbsp;Se connecter avec Facebook</span></a>
        </div>

        <div class="hidden"><?php dynamic_sidebar('sidebar-1'); ?></div>

        <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

        <div id="connexion_facebook_container">
            <div class="post_bottom_buttons_connexion" >
                <div id="submenu_item_connection_register" class="dark" style="text-align: left; background-color: #3E3E40;">
                    <a href="#register" class="wdg-button-lightbox-open" data-lightbox="register"><img width="25" height="25" src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blc_connexion.jpg" alt="triangle blanc"><span style="font-size: 9pt; vertical-align: 8px; color: #FFF; ">Cr&eacute;er un compte</span></a>
                </div>
            </div>
        </div>
        <br />
</div>

