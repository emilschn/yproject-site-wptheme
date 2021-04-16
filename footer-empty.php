		</div> <!-- #container -->

	<?php wp_footer(); ?>

	<?php if (!WP_IS_DEV_SITE): ?>
		<script>
			var hubspotcookies = YPUIFunctions.getCookie( 'hubspotcookies' );
			if ( hubspotcookies === 'accepted' ) {
				$.getScript( '//js.hs-scripts.com/1860698.js' );
			}
		</script>
	<?php endif; ?>
	
	</body>
</html>