<?php
	/**
	 * The footer widget area is triggered if any of the areas
	 * have widgets.
	 *
	 * If none of the sidebars have widgets, bail early.
	 */
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
	return; ?>

			<div id="footer-widget-area" role="complementary">

				<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>

					<div id="first" class="widget-area">
						<h1><?php echo __('Informations', 'yproject'); ?></h1>
						
						<ul class="xoxo">
							<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
						</ul>
					</div><!-- #first .widget-area -->

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>

					<div id="second" class="widget-area">
						<h1><?php echo __('Questions', 'yproject'); ?></h1>
						
						<ul class="xoxo">
							<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
						</ul>
					</div><!-- #second .widget-area -->

				<?php endif; ?>

				<?php /* if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>

					<div id="third" class="widget-area">
						<ul class="xoxo">
							<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
						</ul>
					</div><!-- #third .widget-area -->

				<?php endif; ?>

				<?php if ( is_active_sidebar( 'fourth-footer-widget-area' ) ) : ?>

					<div id="fourth" class="widget-area">
						<ul class="xoxo">
							<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
						</ul>
					</div><!-- #fourth .widget-area -->

				<?php endif; */ ?>
					
					<div id="third" class="widget-area">
						<ul class="xoxo">
						    <li id="nav_menu-4" class="widget widget_nav_menu">
							<div class="menu-footer-3-container">
							    <ul id="menu-footer-3" class="menu">
								<?php 
								    global $facebook_infos, $twitter_infos;
								?>
								<li class="menu-item"><a href="https://www.facebook.com/pages/Y-Project/381460615282040" target="_blank">Facebook</a><?php if ($facebook_infos) echo ' : ' . $facebook_infos; ?></li>
								<li class="menu-item"><a href="https://twitter.com/yproject_co" target="_blank">Twitter</a><?php if ($twitter_infos) echo ' : ' . $twitter_infos; ?></li>
								<li class="menu-item"><a href="">LinkedIn</a></li>
								<li class="menu-item"><a href="">Viadeo</a></li>
								<?php $page_community_blog = get_page_by_path('blog'); ?>
								<li class="menu-item"><a href="<?php echo get_permalink($page_community_blog->ID); ?>">Blog</a></li>
								<li class="menu-item">Newsletter</li>
								<li class="menu-item">
								    <?php 
								    $snsf_args = array("thanks" => __("Merci", "yproject"));
								    echo do_newsletter($snsf_args);
								    if (!isset($_POST['snsf-subscriber-email'])) {
								    ?>
								    <form method="post" name="simple-newsletter-signup-form" id="snsf-form">
									<input type="text" name="snsf-subscriber-email" />
									<input type="submit" value="<?php _e('Sinscrire', 'yproject'); ?>" id="snsf-submit-button" />
								    </form>
								    <?php
								    }
								    ?>
								</li>
								
							    </ul>
							</div>
						    </li> 
						</ul>
					</div><!-- #third .widget-area -->

					<div style="clear: both" />
			</div><!-- #footer-widget-area -->
