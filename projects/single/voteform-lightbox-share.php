<?php global $stylesheet_directory_uri, $post; ?>

<?php ob_start(); ?>
<i><?php _e( "Vos informations ont bien &eacute;t&eacute; enregistr&eacute;es.", 'yproject' ); ?></i>
<br /><br />
<?php _e( "Merci d'avoir &eacute;valu&eacute; sur ce projet, pensez &agrave; en parler autour de vous !", 'yproject' ); ?>
<br /><br />
<p class="align-center">
	<?php locate_template( array( "projects/common/share-buttons.php" ), true, false );  ?>
</p>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
$campaign_title = $post->post_title;
echo do_shortcode('[yproject_lightbox_cornered id="vote-share" title="'.__( "&Eacute;valuation de ", 'yproject' ).$campaign_title.'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');