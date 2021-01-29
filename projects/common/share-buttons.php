<?php 
global $campaign;
$campaign_id = filter_input( INPUT_GET, 'campaign_id' );
if ( !empty( $campaign_id ) ) {
	$campaign = new ATCF_Campaign( $campaign_id );
}
$campaign_url = '';
if ( !empty( $campaign ) ) {
    $campaign_url = $campaign->get_public_url();
}

$twitter_message = '';
$twitter_hashtags = '';
if ( $campaign->is_positive_savings() ) {
	$twitter_message = __( 'share-buttons.DO_LIKE_ME_POSITIVE_SAVINGS', 'yproject' );
	$twitter_message .= ' ' . $campaign->data->post_title;
	$twitter_hashtags = __( 'share-buttons.HASHTAGS_POSITIVE_SAVINGS', 'yproject' );
} else {
	$twitter_message = __( 'share-buttons.DO_LIKE_ME_PROJECT', 'yproject' );
	$twitter_message .= ' ' . $campaign->data->post_title;
	$twitter_hashtags = __( 'share-buttons.HASHTAGS_PROJECT', 'yproject' );
}

?>
<button class="sharer button" data-sharer="twitter" data-title="<?php echo $twitter_message; ?>" data-hashtags="<?php echo $twitter_hashtags; ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/twitter.png" /></button>
<button class="sharer button" data-sharer="facebook" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/facebook.png" /></button>
<button class="sharer button" data-sharer="linkedin" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/linkedin.png" /></button>
<button class="sharer button" data-sharer="googleplus" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/google-plus.png" /></button>
<button class="sharer button" data-sharer="email" data-title="<?php _e( 'share-buttons.email.TITLE', 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>" data-subject="<?php _e( 'share-buttons.email.SUBJECT', 'yproject' ); ?>" data-to=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/mail.jpg" /></button>
<button class="sharer button only_on_mobile inline" data-sharer="whatsapp" data-title="<?php _e( 'share-buttons.email.TITLE', 'yproject' ); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/whatsapp.jpg" /></button>