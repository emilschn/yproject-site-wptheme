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
	$twitter_message = __( "Faites comme moi, épargnez positif dès 10 € pour soutenir ", 'yproject' );
	$twitter_message .= ' ' . $campaign->data->post_title;
	$twitter_hashtags = 'épargne,impact,investissement,royalties';
} else {
	$twitter_message = __( "Faites comme moi, investissez dès 10 € sur le projet", 'yproject' );
	$twitter_message .= ' ' . $campaign->data->post_title . ' ';
	$twitter_message .= __( "sur @wedogood_co :", 'yproject' );
	$twitter_hashtags = 'royaltycrowdfunding,finpart,investissement';
}

?>
<button class="sharer button" data-sharer="twitter" data-title="<?php echo $twitter_message; ?>" data-hashtags="<?php echo $twitter_hashtags; ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/twitter.png" /></button>
<button class="sharer button" data-sharer="facebook" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/facebook.png" /></button>
<button class="sharer button" data-sharer="linkedin" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/linkedin.png" /></button>
<button class="sharer button" data-sharer="googleplus" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/google-plus.png" /></button>
<button class="sharer button" data-sharer="email" data-title="<?php _e("Je viens d'investir sur le projet", 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>" data-subject="<?php _e("Un projet sur lequel investir !", 'yproject'); ?>" data-to=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/mail.jpg" /></button>
<button class="sharer button only_on_mobile inline" data-sharer="whatsapp" data-title="<?php _e("Je viens d'investir sur le projet", 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/whatsapp.jpg" /></button>