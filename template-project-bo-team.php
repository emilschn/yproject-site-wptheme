<?php 
/**
 * Template Name: Projet Edition Equipe
 *
 */
global $campaign, $post;
yproject_check_user_can_see_project_page();
locate_template( array("requests/projects.php"), true );
$feedback = '';
if (isset($_REQUEST['action'])) $feedback = YPProjectLib::edit_team();

$campaign_id = $_GET['campaign_id'];
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php if (YPProjectLib::current_user_can_edit($campaign_id)): ?>
		    
					<?php require_once('projects/single-admin-bar.php'); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					    
						<?php require_once('projects/single-header.php'); ?>

						<div class="center margin-height">
						    
							<span class="success"><?php if ($feedback === TRUE) {
								_e('Modification effectu&eacute;e', 'yproject');
							} ?></span>
						    
							<span class="errors"><?php if ($feedback !== TRUE && !empty($feedback)) {
								_e($feedback, 'yproject');
							} ?></span>
						    
							<h2><?php _e('Administrateur du projet', 'yproject'); ?></h2>
							
							<?php
								$author_id = $post->post_author;
								$author_data = get_userdata($author_id);
								echo $author_data->first_name . ' ' . $author_data->last_name . ' (' . $author_data->user_nicename . ')';
							?>
						    
							<h2><?php _e('&Eacute;quipe projet', 'yproject'); ?></h2>
							
							<?php 
								$project_api_id = BoppLibHelpers::get_api_project_id($_GET['campaign_id']);
								$team_member_list = BoppLib::get_project_members_by_role($project_api_id, YPProjectLib::$project_team_member_role['slug']);
								if (count($team_member_list) > 0):
							?>
								<ul>
							<?php
									foreach ($team_member_list as $team_member): ?>
										<li>
											<?php echo $team_member->user_name . ' ' . $team_member->user_surname; ?>
											<form action="" method="POST" style="display: inline-block">
												<input type="hidden" name="action" value="yproject-remove-member" />
												<input type="hidden" name="user_to_remove" value="<?php echo $team_member->wp_user_id; ?>" />
												<input type="submit" value="<?php _e('Supprimer', 'yproject'); ?>" />
											</form>
										</li>
									<?php endforeach;
							?>
								</ul>
							<?php	
								else:
									_e('Aucun membre dans l&apos;&eacute;quipe pour l&apos;instant.', 'yproject');
								endif;
							?>
						    
							<h2><?php _e('Ajouter un utilisateur dans l&apos;&eacute;quipe', 'yproject'); ?></h2>
							
							<form action="" method="POST">
								<input type="text" name="new_team_member" style="width: 200px;" placeholder="<?php _e('Saisissez l&apos;e-mail ou l&apos;identifiant d&apos;un utilisateur inscrit sur WEDOGOOD.co', 'ypoject'); ?>" />
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