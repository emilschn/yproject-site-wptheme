<?php 
global $campaign, $can_modify;
$client_context = $campaign->get_client_context();
?>

<?php if (!empty($client_context)): ?>
<?php locate_template( array("projects/" .$client_context. "/header.php"), true ); ?>
<?php endif; ?>

<?php locate_template( array("projects/single/header.php"), true ); ?>

<?php if (!is_user_logged_in()): ?>
<?php echo do_shortcode('[yproject_connexion_lightbox]<p class="align-center">'.__('Afin de soutenir un projet, vous devez &ecirc;tre inscrit et connect&eacute;.', 'yproject').'</p>[/yproject_connexion_lightbox]'); ?>
<?php echo do_shortcode('[yproject_register_lightbox]'); ?>
<?php endif; ?>

<?php if ($can_modify): ?>
<?php locate_template( array("projects/single/admin.php"), true ); ?>
<?php endif; ?>

<div class="padder">
    
	<?php locate_template( array("projects/single/banner.php"), true ); ?>
    
	<?php locate_template( array("projects/single/timeline.php"), true ); ?>
    
	<?php locate_template( array("projects/single/pitch.php"), true ); ?>
    
	<?php locate_template( array("projects/single/rewards.php"), true ); ?>
    
	<?php locate_template( array("projects/single/description.php"), true ); ?>
    
	<?php locate_template( array("projects/single/news.php"), true ); ?>
    
	<?php locate_template( array("projects/single/comments.php"), true ); ?>
    
</div>
    
<?php locate_template( array("projects/single/responsive-buttons.php"), true ); ?>