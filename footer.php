		</div> <!-- #container -->

<?php
//*******************
//CACHE FOOTER
global $WDG_cache_plugin, $client_context, $stylesheet_directory_uri;
$cache_footer = $WDG_cache_plugin->get_cache('footer', 3);
if ($cache_footer !== FALSE && empty($client_context)) { echo $cache_footer; }
else {
	ob_start();
?>
		<footer class="bg-dark-gray<?php if (!empty($client_context)) { ?> theme-<?php echo $client_context; ?><?php } ?>">
		    <section class="center-lg">

				<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
					<div>
						<h3 class="clickable border-hidden"><?php _e('Qui sommes-nous ?', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
					<div>
						<h3 class="clickable"><?php _e('Entrepreneurs', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
						</ul>

						<h3 class="clickable"><?php _e('Investisseurs', 'yproject'); ?></h3>
						<ul>
							<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
					<div>
						<h3><?php _e('Nous suivre', 'yproject'); ?></h3>
						
						<form action="" method="POST">
							<img src="<?php echo $stylesheet_directory_uri; ?>/images/common/mail.jpg" alt="MAIL" width="48" height="48" />
							<input type="text" id="subscribe-nl-mail" name="subscribe-nl-mail" placeholder="<?php _e("Je m'inscris à la newsletter", 'yproject'); ?>" />
							<input type="submit" id="subscribe-nl-submit" value="OK" class="hidden" />
							<input type="hidden" name="action" value="subscribe_newsletter_sendinblue" />
						</form>
						
						<div style="margin: 30px 0px;">
								<a class="social_network" href="https://www.facebook.com/wedogood.co" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-facebook.jpg" alt="facebook"/></a>
								<a class="social_network" href="https://twitter.com/wedogood_co" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-twitter.jpg" alt="twitter"/></a>
								<a class="social_network" href="https://www.linkedin.com/company/3171289" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-linkedin.jpg" alt="linkedin"/></a>
								<a class="social_network" href="https://www.wedogood.co/rss.xml" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-rss.jpg" alt="<?php _e("Flux RSS", 'yproject'); ?>"/></a>
						</div>

						<div>
							<a class="link" href="<?php echo home_url('/contact'); ?>"><?php _e( "Contactez-nous", 'yproject' ); ?></a>
						</div>
					</div>
				<?php endif; ?>

				<div class="clear"></div>
				
			</section>
			
			<section class="center-lg">							
				<div>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/membre-FPF.png" alt="logo membre financement participatif France" width="150px"/>
				</div>

				<div class="lines" id="finance_innovation">
					<p class="label"><?php _e("Agréée par", 'yproject')?></p>
					<a href="http://www.finance-innovation.org/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/finance-innovation-logo.png" alt="logo finance innovation" width="180px"/></a>
				</div>

				<div id="lemonway">
					<a href="http://www.lemonway.fr" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/lemonway-gris.png" alt="logo Lemonway" width="278px"/></a><br />
					<p class="lines">Partenaire de Lemon Way, &eacute;tablissement de paiement agr&eacute;&eacute; par l’ACPR en France le 24/12/2012 sous le num&eacute;ro 16568J.</p>
				</div>
				<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
				<div>
					<ul>
						<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
					</ul>
				</div>
				<?php endif; ?>

				<div class="clear"></div>

				<div class="aligncenter" role="contentinfo">
					<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/">
						<img alt="Licence Creative Commons" width="20" height="20" style="vertical-align: middle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/cc_logo_noir.jpg" /> Some rights reserved
					</a>
				</div>
                                
			</section>
		</footer>
		
		<?php $hidecookiealert = filter_input( INPUT_COOKIE, 'hidecookiealert' ); ?>
		<?php if ( empty( $hidecookiealert ) ): ?>
		<div id="cookies-alert" class="bg-dark-gray aligncenter">
			<?php _e( "En poursuivant votre navigation sur WE DO GOOD.co, vous acceptez l'utilisation de cookies afin de nous permettre d'am&eacute;liorer votre exp&eacute;rience utilisateur", 'yproject' ); ?> (<a href="<?php echo home_url( '/cgu' ); ?>"><?php _e( "en savoir plus", 'yproject' ); ?></a>).
			<button id="cookies-alert-close" class="red"><?php _e( "OK", 'yproject' ); ?></button>
		</div>
		<?php endif; ?>

<?php
	$cache_footer = ob_get_contents();
	$WDG_cache_plugin->set_cache('footer', $cache_footer, 60*60*24, 1);
	ob_end_clean();
	echo $cache_footer;
}
//FIN CACHE FOOTER
//*******************
?>

	<?php wp_footer(); ?>

	</body>
</html>