		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<div id="footer" style="background-color:#8F8FB2; height:120px; border-radius:5px;">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo">
			
				<?php do_action( 'bp_dtheme_credits' ); ?>
				<p><?php printf( __( 'Proudly powered by <a href="%1$s">YProjetct</a>  -   <a href="%2$s">2013</a>.', 'buddypress' ), 'http://www.yproject.co/', 'http://www.yproject.co/'); ?></p>
				<p><?php printf( __( ' <a href="%1$s">CrowFunding</a>  -  <a href="%2$s">CrowSourcing</a>.', 'buddypress' ), 'http://www.yproject.co/', 'http://buddypress.org' ); ?></p>
			</div>

			<?php do_action( 'bp_footer' ); ?>
			
			

		</div><!-- #footer -->

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>