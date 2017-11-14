<?php 
global $campaign;
$campaign_id = filter_input( INPUT_GET, 'campaign_id' );
if ( !empty( $campaign_id ) ) {
	$campaign = new ATCF_Campaign( $campaign_id );
}
$campaign_url = get_permalink( $campaign->ID );
?>
<button class="sharer button" data-sharer="twitter" data-title="<?php _e("Faites comme moi, investissez sur le projet", 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-hashtags="royalty, crowdfunding" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/twitter.png" /></button>
<button class="sharer button" data-sharer="facebook" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/facebook.png" /></button>
<button class="sharer button" data-sharer="linkedin" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/linkedin.png" /></button>
<button class="sharer button" data-sharer="googleplus" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/google-plus.png" /></button>
<button class="sharer button" data-sharer="email" data-title="<?php _e("Je viens d'investir sur le projet", 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>" data-subject="<?php _e("Un projet sur lequel investir !", 'yproject'); ?>" data-to=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/mail.jpg" /></button>
<button class="sharer button only_on_mobile inline" data-sharer="whatsapp" data-title="<?php _e("Je viens d'investir sur le projet", 'yproject'); ?> <?php echo $campaign->data->post_title ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/whatsapp.jpg" /></button>