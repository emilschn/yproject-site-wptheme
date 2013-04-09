<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
		<?php if ( current_theme_supports( 'bp-default-responsive' ) ) : ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" /><?php endif; ?>
		

	</head>

	<body >


		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer" style="background-color:#8F8FB2; height:120px; border-radius:5px;">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo" style="padding:20px; text-align:center; color: white; font-size:12px">
				
				<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
					<img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/fr/88x31.png" />
					</a><br />Ce(tte) œuvre est mise à disposition selon les termes de la 
					<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
						<!--Licence Creative Commons Attribution -  Partage dans les Mêmes Conditions 3.0 France-->
					</a>.
				
			</div>

			<?php do_action( 'bp_footer' ); ?>
			
			

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>