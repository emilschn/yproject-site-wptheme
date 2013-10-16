		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<footer>
		    <div class="center">
			<span>Plateforme d&apos;investissement participatif &agrave; impact positif.</span>
			
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<?php get_sidebar( 'footer' ); ?>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo" style="text-align:center; color: white; font-size:12px;">
			    <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
				<img alt="Licence Creative Commons" width="25" height="25" border="0" style="vertical-align: middle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/cc_logo.png" /> SOME RIGHTS RESERVED
			    </a>
			    <br />

			    <!-- GeoTrust QuickSSL [tm] Smart Icon tag. Do not edit. -->
			    <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="https://smarticon.geotrust.com/si.js"></SCRIPT>
			    <!-- end GeoTrust Smart Icon tag -->
			</div>

			<?php do_action( 'bp_footer' ); ?>
			
		    </div>
		</footer>

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>