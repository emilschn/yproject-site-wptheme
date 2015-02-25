		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

<?php
//*******************
//CACHE PROJECT CONTENT BOTTOM
global $WDG_cache_plugin;
$cache_footer = $WDG_cache_plugin->get_cache('footer', 1);
if ($cache_footer !== FALSE) { echo $cache_footer; }
else {
	ob_start();
?>
		<footer>
		    <div class="center">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<?php get_sidebar( 'footer' ); ?>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo" style="text-align:center; color: white; font-size:12px;">
			    <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
				<img alt="Licence Creative Commons" width="25" height="25" style="vertical-align: middle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/cc_logo.png" /> Some rights reserved
			    </a>
			    <span>Plateforme d&apos;investissement participatif &agrave; impact positif.</span>
			</div>

			<?php do_action( 'bp_footer' ); ?>
			
		     </div>
		</footer>

<?php
	$cache_footer = ob_get_contents();
	$WDG_cache_plugin->set_cache('footer', $cache_footer, 60*60*24, 1);
	ob_end_clean();
	echo $cache_footer;
}
//FIN CACHE PROJECT CONTENT SUMMARY
//*******************
?>

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>