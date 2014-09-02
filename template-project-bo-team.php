<?php 
/**
 * Template Name: Projet Edition Equipe
 *
 */
global $campaign, $post;
yproject_check_user_can_see_project_page();
$feedback = '';
if (isset($_REQUEST['action'])) $feedback = YPProjectLib::edit_team();
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php if (yproject_user_can_manage_project_page()): ?>
		    
					<?php require_once('projects/single-admin-bar.php'); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					    
						<?php require_once('projects/single-header.php'); ?>

						<div class="center">
						    
							<span class="success"><?php if ($feedback === TRUE) {
								_e('Modification effectu&eacute;e', 'yproject');
							} ?></span>
						    
							<span class="error"><?php if ($feedback !== TRUE && !empty($feedback)) {
								_e($feedback, 'yproject');
							} ?></span>
						    
							<h2><?php _e('Administrateur du projet', 'yproject'); ?></h2>
							
							<?php
								$author_id = $post->post_author;
								$author_data = get_userdata($author_id);
								echo $author_data->first_name . ' ' . $author_data->last_name . ' (' . $author_data->user_nicename . ')';
							?>
						    
							<h2><?php _e('&Eacute;quipe projet', 'yproject'); ?></h2>
							
							<?php _e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant.', 'yproject'); ?>
						    
							<h2><?php _e('Ajouter un utilisateur dans l&apos;&eacute;quipe', 'yproject'); ?></h2>
							
							<form action="" method="POST">
								<input type="text" name="new_team_member" style="width: 200px;" placeholder="<?php _e('Saisissez l&apos;e-mail ou l&apos;identifiant', 'ypoject'); ?>" />
								<input type="hidden" name="action" value="yproject-add-member" />
								<input type="submit" value="<?php _e('Ajouter', 'yproject'); ?>" />
							</form>
						</div>
					    
					</div>
		    
				<?php else: ?>
				
					<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>
		    
				<?php endif; ?>
		    
			<?php endwhile; endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>