<?php get_header(); ?>

	<div id="content" class="center">
	    <div class="padder_more">
		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ); ?>" method="post">
		    <label><?php _e('Identifiant', 'yproject'); ?></label>
		    <input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" />
		    <br />

		    <label><?php _e('Mot de passe', 'yproject'); ?></label>
		    <input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" />
		    <br />

		    <p class="forgetmenot">
			<input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" /> <label><?php _e('Se souvenir de moi', 'yproject'); ?></label>
			<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e('Connexion', 'yproject'); ?>" />
		    </p>

		    <input type="hidden" name="testcookie" value="1" />
		</form>

		<div><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
		<br />
		<div><a href="<?php echo get_permalink($page_connexion_register->ID); ?>"><?php _e('Sinscrire', 'yproject'); ?></a></div>
		
	    </div>
	</div>


<?php get_footer(); ?>