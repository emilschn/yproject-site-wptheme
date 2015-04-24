<div style="width: 450px;" id="post_bottom_content" class="center_small">
    <div style="width: 450px;" class="left post_bottom_desc_small">
        <div class="login_fail">
            <?php if (isset($_GET["login"]) && $_GET["login"] == "failed") { ?>
                <?php _e('Erreur d&apos;identification', 'yproject'); ?>
            <?php } ?>
        </div>
         
        
        
        <form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url('wp-login.php', 'login_post'); ?>" method="post">
            <label for="identifiant" class="standard-label"><?php _e('Identifiant :', 'yproject'); ?></label>
            <input id="identifiant" type="text" name="log" class="input" placeholder="Identifiant" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" />
            <br />

            <label for="password" class="standard-label"><?php _e('Mot de passe :', 'yproject'); ?></label>
            <input id="password" type="password" name="pwd" class="input" value="" /> 
            
            <br />
            <div id="submit-center">
            <input type="submit"  name="wp-submit" id="sidebar-wp-submit" id="connect" value="<?php _e('Connexion', 'yproject'); ?>" />
            <input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" />
            <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
            <br />
            <input type="hidden" name="redirect-page-error" id="redirect-page-error" value="<?php echo get_permalink($page) ?>" />
            <?php 
                if( get_permalink($page) === home_url()."/"){
                    $valeur = "home";
                } else {
                    $valeur = get_the_ID();
                }
                $redirect_value = "";
                if (isset($_GET["redirect"]) && $_GET["redirect"] == "invest") $redirect_value = "true";
            ?>
            <input type="hidden" name="redirect-page" id="redirect-page" value="<?php echo $valeur; ?>" />   
            <input type="hidden" name="redirect-page-investir" id="redirect-page-investir" value="<?php echo $redirect_value; ?>" />
             </div>
          
            <?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
            <a href="<?php echo get_permalink($page_forgotten->ID); ?>">(Mot de passe oubli&eacute;)</a>
             <br />
           

            <input type="hidden" name="testcookie" value="1" />
        </form>

        <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

        <div id="connexion_facebook_container">
            <a href="javascript:void(0);" class="social_connect_login_facebook"><img style="border-right: 1px solid #FFFFFF; width:25px; height:25px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook_connexion.jpg" alt="connexion facebook"class="vert-align"/><span style=" font-size:12px;">&nbsp;Se connecter avec Facebook</span></a>
        </div>

        <div class="hidden"><?php dynamic_sidebar('sidebar-1'); ?></div>

        <hr style="-moz-border-bottom-colors: none; -moz-border-left-colors: none; -moz-border-right-colors: none; -moz-border-top-colors: none; border-color: -moz-use-text-color; border-image: none; border-right: 0 none; border-style: dotted none none; border-width: 1px 0 0; color: #808080; margin: 15px 0;"/>

        <?php $page_connexion_register = get_page_by_path('register'); ?>
        
        <div id="connexion_facebook_container">
            <div class="post_bottom_buttons_connexion" >
                <div id="submenu_item_connection_register" class="dark">
                    <a href="<?php echo get_permalink($page_connexion_register->ID); ?>"><img width="25" height="25" src="<?php echo get_stylesheet_directory_uri(); ?>/images/triangle_blc_connexion.jpg" alt="triangle blanc"><span style="font-size: 9pt; vertical-align: 8px; color: #FFF; ">Cr&eacute;er un compte</span></a>
                </div>
            </div>
        </div>

        <br />

    </div>
    <div style="clear: both"></div>
</div>

