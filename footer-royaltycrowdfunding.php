		</div> <!-- #container -->

		<style>
			footer {
				width: -webkit-calc(100% - 20px);
				width: -moz-calc(100% - 20px);
				width: calc(100% - 20px);
				max-width: 1280px;
				margin: auto;
			}
			footer ul { text-align: center; }
			footer li { display: inline; margin: 5px 30px; }
			footer a { color: #333; }
			footer a::before { content: ""; margin-right: 0px; }
			footer div.align-center img { vertical-align: middle; }
			footer div.align-center span { display: inline-block; }
		</style>
		<footer>
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
				<div>
					<ul>
						<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
					</ul>
				</div>
			<?php endif; ?>

			<hr />
			
			<div>
				<div class="align-center">
					<a href="http://www.lemonway.fr" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/lemonway-gris.png" alt="logo Lemonway" width="258" height="67" /></a>
					<span>Partenaire de Lemon Way, &eacute;tablissement de paiement agr&eacute;&eacute; par l’ACPR en France le 24/12/2012 sous le num&eacute;ro 16568J.</span>
				</div>
			</div>
		</footer>

		<?php $hidecookiealert = filter_input( INPUT_COOKIE, 'hidecookiealert' ); ?>
		<?php if ( empty( $hidecookiealert ) ): ?>
		<div id="cookies-alert" class="bg-dark-gray aligncenter">
			<?php if ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' ): ?>
			<?php _e( "En poursuivant votre navigation sur WE DO GOOD.co, vous acceptez l'utilisation de cookies afin de nous permettre d'am&eacute;liorer votre exp&eacute;rience utilisateur", 'yproject' ); ?> (<a href="<?php echo home_url( '/cgu/' ); ?>"><?php _e( "en savoir plus", 'yproject' ); ?></a>).
			<?php else: ?>
			<?php _e( "En poursuivant votre navigation, vous acceptez l'utilisation de cookies afin de nous permettre d'am&eacute;liorer votre exp&eacute;rience utilisateur", 'yproject' ); ?> (<a href="<?php echo home_url( '/cgu/' ); ?>"><?php _e( "en savoir plus", 'yproject' ); ?></a>).
			<?php endif; ?>
			
			<button id="cookies-alert-close" class="red"><?php _e( "OK", 'yproject' ); ?></button>
		</div>
		<?php endif; ?>

		<?php wp_footer(); ?>

		<?php if (!WP_IS_DEV_SITE): ?>
		<script>
			window.intercomSettings = {
				app_id: "rm15pauy"
			};
		</script>
		<script>
			(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/rm15pauy';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()
		</script>
		<?php endif; ?>
	
	</body>
</html>