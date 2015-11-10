<?php 
global $campaign;
$client_context = $campaign->get_client_context();
?>

<?php if (!empty($client_context)): ?>
<?php locate_template( array("projects/" .$client_context. "/header.php"), true ); ?>
<?php endif; ?>

<?php locate_template( array("projects/single/header.php"), true ); ?>

<div class="padder">
    
	<?php locate_template( array("projects/single/banner.php"), true ); ?>
    
	<?php locate_template( array("projects/single/timeline.php"), true ); ?>
    
	<?php locate_template( array("projects/single/pitch.php"), true ); ?>
    
	<?php locate_template( array("projects/single/rewards.php"), true ); ?>
    
	<?php locate_template( array("projects/single/description.php"), true ); ?>
    
	<?php locate_template( array("projects/single/news.php"), true ); ?>
    
	<?php locate_template( array("projects/single/comments.php"), true ); ?>
    
</div>