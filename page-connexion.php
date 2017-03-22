<?php if (is_user_logged_in()) { wp_redirect( WDGUser::get_login_redirect_page() ); } ?>

<?php get_header(); ?>

<div id="content" style="margin-top: 90px;">
	<div class="padder_more">
		<div class="center_small margin-height">
			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>