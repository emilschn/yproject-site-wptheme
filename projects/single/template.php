<?php 
global $campaign, $can_modify;
$can_modify = $campaign->current_user_can_edit();
$client_context = $campaign->get_client_context();
$campaign_status = $campaign->campaign_status();
?>

<?php if (!is_user_logged_in()){ ?>
<?php echo do_shortcode('[yproject_connexion_lightbox]<p class="align-center">'.__('Afin de soutenir un projet, vous devez &ecirc;tre inscrit et connect&eacute;.', 'yproject').'</p>[/yproject_connexion_lightbox]'); ?>
<?php }else if($campaign_status==ATCF_Campaign::$campaign_status_vote){
			if(isset($_GET['vote_check'])&&($_GET['vote_check']==1)){
				locate_template( array("projects/single/voteform-validated.php"), true );
			}else{
				locate_template( array("projects/single/voteform.php"), true ); 
 			} 
		}
?>

<?php if ($can_modify): ?>
<?php locate_template( array("projects/single/admin.php"), true ); ?>
<?php endif; ?>

<header>
    <div class="header-container">
	<?php locate_template( array("projects/single/banner.php"), true ); ?>
	
	<?php if (!empty($client_context)): ?>
	<?php locate_template( array("projects/" .$client_context. "/header.php"), true ); ?>
	<?php endif; ?>
    </div>
</header>

<?php locate_template( array("projects/single/nav.php"), true ); ?>

<?php locate_template( array("projects/single/rewards.php"), true ); ?>

<?php locate_template( array("projects/single/description.php"), true ); ?>

<?php locate_template( array("projects/single/news.php"), true ); ?>

<?php locate_template( array("projects/single/comments.php"), true ); ?>