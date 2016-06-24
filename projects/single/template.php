<?php 
global $campaign, $can_modify;
$client_context = $campaign->get_client_context();
$campaign_status = $campaign->campaign_status();
?>

<?php if (!empty($client_context)): ?>
<?php locate_template( array("projects/" .$client_context. "/header.php"), true ); ?>
<?php endif; ?>

<?php locate_template( array("projects/single/header.php"), true ); ?>

<?php if (!is_user_logged_in()){ ?>
<?php echo do_shortcode('[yproject_connexion_lightbox]<p class="align-center">'.__('Afin de soutenir un projet, vous devez &ecirc;tre inscrit et connect&eacute;.', 'yproject').'</p>[/yproject_connexion_lightbox]'); ?>
<?php echo do_shortcode('[yproject_register_lightbox]'); ?>
<?php }else if($campaign_status=="vote"){?>
<?php locate_template( array("projects/single/voteform.php"), true ); ?>
<?php } ?>

<?php if ($can_modify): ?>
<?php locate_template( array("projects/single/admin.php"), true ); ?>
<?php endif; ?>

<?php
global $wp;
$current_url = add_query_arg( $wp->query_string, home_url( $wp->request ) );
$vote_check_bool=strstr( $current_url,'vote_check=1');
if($vote_check_bool){
	locate_template( array("projects/single/check_vote_1.php"), true );
}
?>

<?php
global $wp;
$current_url = add_query_arg( $wp->query_string, home_url( $wp->request ) );
$vote_check_bool=strstr( $current_url,'vote_check=0');
if($vote_check_bool){
	locate_template( array("projects/single/check_vote_0.php"), true );
}
?>

<div class="padder">
    
	<?php locate_template( array("projects/single/banner.php"), true ); ?>
    
	<?php locate_template( array("projects/single/pitch.php"), true ); ?>
    
	<?php locate_template( array("projects/single/rewards.php"), true ); ?>
    
	<?php locate_template( array("projects/single/description.php"), true ); ?>
    
	<?php locate_template( array("projects/single/news.php"), true ); ?>
    
	<?php locate_template( array("projects/single/comments.php"), true ); ?>
    
</div>
    
<?php locate_template( array("projects/single/responsive-buttons.php"), true ); ?>