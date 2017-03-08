<div id="post_bottom_content" class="align-center">
	<?php if (WDGUser::has_login_errors()): ?>
	<div class="errors">
		<?php echo WDGUser::display_login_errors(); ?>
	</div>
	<?php endif; ?>
    
        <form method="post" action="" name="login-form" id="sidebar-login-form" class="standard-form">
			<h2><?php _e('Inscription et connexion', 'yproject'); ?></h2>
			<div class="warning">
				Afin de soutenir un projet, vous devez &ecirc;tre inscrit et connect&eacute;
			</div>
            <input id="identifiant" type="text" name="log" placeholder="Identifiant ou e-mail" value="<?php if (isset($_POST["log"])) echo $_POST["log"]; ?>" />
            <br />

            <input id="password" type="password" name="pwd" placeholder="Mot de passe" value="" style="margin: 5px;" />
            <br />
	    
            <div id="sidebar-login-form-lightbox">
				<?php $page_forgotten = get_page_by_path('mot-de-passe-oublie'); ?>
				<a href="<?php echo get_permalink($page_forgotten->ID); ?>" >(Mot de passe oubli&eacute;)</a>
            </div>

			<input id="sidebar-rememberme" type="checkbox" name="rememberme" value="forever" />
			<label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
			<br />
            
            <div class="box_connection_buttons red" id="submit-center">
                <input type="submit"  name="wp-submit" id="sidebar-wp-submit-lightbox" id="connect" value="<?php _e('Connexion', 'yproject'); ?>" />
                <input type="hidden" id="redirect-page" name="redirect-page" value="<?php echo WDGUser::get_login_redirect_page(); ?>" />
				<input type="hidden" name="login-form" value="1" />
            </div>
	    
            <br />
        </form>

        <div id="connexion_facebook_container" class="box_connection_buttons red">
			<div id="submenu_item_connection_register" class="" >
				<a href="#register" class="wdg-button-lightbox-open" data-lightbox="register"><span style="color: #FFF; ">Cr&eacute;er mon compte</span></a>
			</div>
        </div>

        <div class="box_connection_buttons blue" id="connexion_facebook_container">
            <a href="javascript:void(0);" class="social_connect_login_facebook"><span style=" font-size: 11pt;">&nbsp;Se connecter avec Facebook</span></a>
        </div>

        <div class="hidden"><?php dynamic_sidebar('sidebar-1'); ?></div>

</div>

