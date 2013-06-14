		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ); ?>
		<?php do_action( 'bp_before_footer'   ); ?>

		<footer>
		    <div class="center">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) || is_active_sidebar( 'second-footer-widget-area' ) || is_active_sidebar( 'third-footer-widget-area' ) || is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div id="footer-widgets">
					<?php get_sidebar( 'footer' ); ?>
				</div>
			<?php endif; ?>

			<div id="site-generator" role="contentinfo" style="padding:20px; text-align:center; color: white; font-size:12px">
				
				<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
					<img alt="Licence Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/fr/88x31.png" />
				</a><br />
				<?php echo __('CREATIVE COMMONS PART 1', 'yproject'); ?><a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/"><?php echo __('CREATIVE COMMONS PART 2', 'yproject'); ?></a>.
				
			</div>

			<?php do_action( 'bp_footer' ); ?>
			
		    </div>
		</footer>

		<?php do_action( 'bp_after_footer' ); ?>

		<?php wp_footer(); ?>

	</body>

</html>