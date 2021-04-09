		</div> <!-- #container -->

<?php
//*******************
//CACHE FOOTER
global $WDG_cache_plugin, $client_context, $stylesheet_directory_uri;
$cache_footer = $WDG_cache_plugin->get_cache('footer', 3);
if ($cache_footer !== FALSE && empty($client_context)) {
	echo $cache_footer;
} else {
	ob_start(); ?>
		<footer class="bg-light-grey<?php if (!empty($client_context)) { ?> theme-<?php echo $client_context; ?><?php } ?>">
			<div class="footer-container">
				<section>

					<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
						<div>
							<span class="footer-subtitle clickable border-hidden"><?php _e( 'footer.WHO_WE_ARE', 'yproject' ); ?></span>
							<ul>
								<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
						<div>
							<span class="footer-subtitle clickable"><?php _e( 'footer.ENTREPRENEURS', 'yproject' ); ?></span>
							<ul>
								<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
							</ul>

							<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
							<span class="footer-subtitle clickable"><?php _e( 'footer.INVESTORS', 'yproject' ); ?></span>
							<ul>
								<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
							</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div>
						<span class="footer-subtitle"><?php _e( 'footer.FOLLOW_US', 'yproject' ); ?></span>

						<div style="margin: 16px 0px;">
							<a class="social_network" href="https://www.facebook.com/wedogood.co" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-facebook.jpg" alt="facebook"/></a>
							<a class="social_network" href="https://twitter.com/wedogood_co" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-twitter.jpg" alt="twitter"/></a>
							<a class="social_network" href="https://www.linkedin.com/company/we-do-good/" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-linkedin.jpg" alt="linkedin"/></a>
							<a class="social_network" href="<?php echo site_url(); ?>/rss.xml" target="_blank"><img src="<?php echo $stylesheet_directory_uri; ?>/images/reseaux/icon-rss.jpg" alt="<?php _e( 'footer.RSS_FEED', 'yproject' ); ?>"/></a>
						</div>

						<div>
							<a class="link" href="<?php echo WDG_Redirect_Engine::override_get_page_url('a-propos/contact'); ?>"><?php _e( 'footer.CONTACT_US', 'yproject' ); ?></a><br>
							<a class="link" href="<?php echo WDG_Redirect_Engine::override_get_page_url('a-propos/newsletter'); ?>"><?php _e( 'footer.SUBSCRIBE_NEWSLETTER', 'yproject' ); ?></a><br>
							<a class="link change-cookies" href="#"><?php _e( 'footer.MODIFY_COOKIE_CHOICES', 'yproject' ); ?></a>
						</div>

						<?php if ( is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) ): ?>
						<?php
						$url_suffix = '';
	if ( isset( $_GET[ 'campaign_id' ] ) ) {
		$url_suffix = '?campaign_id=' . $_GET[ 'campaign_id' ];
	} ?>
						<?php $active_languages = apply_filters( 'wpml_active_languages', NULL ); ?>
						<div class="select">
							<select id="footer-switch-lang">
								<?php foreach ( $active_languages as $language_key => $language_item ): ?>
									<option value="<?php echo $language_item[ 'url' ] . $url_suffix; ?>" <?php if ( $language_item[ 'active' ] ) {
		echo 'selected="selected"';
	} ?>><?php echo mb_strtoupper( $language_item[ 'native_name' ], 'UTF-8' ); ?></option>
								<?php endforeach; ?>
							</select>
							<div class="select_arrow"></div>
						</div>
						<?php endif; ?>
					</div>
				</section>

				<div class="logo-list clear">
					<div>
						<a href="https://acpr.banque-france.fr/agrements-et-autorisations/le-financement-participatif-crowdfunding.html" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/ifp.png" alt="logo label IFP" width="160"></a>
						<span><?php _e( 'footer.REGISTERED_ORIAS', 'yproject' ); ?> <strong>17002712</strong></span>
					</div>
					
					<div>
						<a href="http://www.financeparticipative.org/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/membre-fpf-2020.png" alt="logo membre financement participatif France" width="140"></a>
					</div>

					<div>					
						<a href="https://finance-innovation.org/" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/finance-innovation-labellise.png" alt="logo finance innovation" width="200"></a>
					</div>

					<div>
						<a href="http://www.lemonway.fr" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/lemonway-gris.png" alt="logo Lemonway" width="258"></a><br>
						<p class="lines"><?php _e( 'footer.LEMONWAY_PARTNER', 'yproject' ); ?> 16568J.</p>
					</div>

					<div>
						<a href="https://bcorporation.net/directory/we-do-good" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/bcorp.png" alt="logo BCorp" width="120"></a>
					</div>
				</div>

				<div class="term-links aligncenter">
					<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>
						<hr>
						<ul>
							<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
						</ul>
					<?php endif; ?>
				</div>

				<div class="licence aligncenter" role="contentinfo">
					<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/fr/" target="_blank">
						<img alt="Licence Creative Commons" width="25" height="25" style="vertical-align: middle" src="<?php echo get_stylesheet_directory_uri(); ?>/images/footer/cc-01.png" /> Some rights reserved
					</a>
				</div>
			</div>
		</footer>

<?php
	$cache_footer = ob_get_contents();
	$WDG_cache_plugin->set_cache('footer', $cache_footer, 60*60*24, 1);
	ob_end_clean();
	echo $cache_footer;
}
//FIN CACHE FOOTER
//*******************
?>

	<?php $cookie_small_text = WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_term_cookies_retracted ); ?>
	<?php $cookie_long_text = WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_term_cookies_extended ); ?>

	<?php if ( !empty( $cookie_small_text ) ): ?>
		<div id="cookies-params" class="has-gris-clair-background-color has-noir-color">
			<div class="center">
				<div class="small">
					<?php echo nl2br( $cookie_small_text ); ?>
					<br><br>
				</div>
				<div class="long hidden">
					<?php echo nl2br( $cookie_long_text ); ?>
					<br><br>
				</div>

				<form class="db-form v3">
					<button type="button" class="button left transparent read-more"><?php _e( 'common.READ_MORE', 'yproject' ); ?></button>
					<button type="button" class="button left red refuse"><?php _e( 'common.REFUSE', 'yproject' ); ?></button>
					<button type="button" class="button right red accept"><?php _e( 'common.ACCEPT', 'yproject' ); ?></button>
				</form>
			</div>
		</div>
		<script>
			var hidecookieparams = YPUIFunctions.getCookie( 'hidecookieparams' );
			if ( hidecookieparams === '1' ) {
				$( '#cookies-params' ).hide();
			} else {
				$( 'a.link.change-cookies' ).hide();
			}

			var hubspotcookies = YPUIFunctions.getCookie( 'hubspotcookies' );
			if ( hubspotcookies === 'accepted' ) {
				$.getScript( '//js.hs-scripts.com/1860698.js' );
			}
		</script>
	<?php endif; ?>

	<?php wp_footer(); ?>
	
	</body>
</html>