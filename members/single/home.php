<?php

get_header( 'buddypress' ); 
?>

	<div id="content">
		<div class="padder">
			
			<?php if(is_user_logged_in()) {
    				locate_template( array( 'members/single/admin-bar.php' ), true ); 
					}
			?>

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<header id="item-header" role="complementary">
			    <div id="item-header-container" class="center">
				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>
			    </div>
			</header><!-- #item-header -->

			<div class="center">
			    <?php
			    $following_count = 0;
			    $following_list_str = bp_get_following_ids();
			    if ($following_list_str) { 
				$following_list = explode(',' , $following_list_str);
				$following_count = count($following_list);
			    }
			    
			    $followers_count = 0;
			    $followers_list_str = bp_get_follower_ids();
			    if ($followers_list_str) { 
				$followers_list = explode(',' , bp_get_follower_ids());
				$followers_count = count($followers_list);
			    }
			    
			    $projects_count = 0;
			    $query_temp = query_posts( array(
				'post_type' => 'download',
				'author' => bp_displayed_user_id()
			    ) );
			    if ($query_temp) $projects_count = $wp_query->found_posts;
			    
			    $current_user = wp_get_current_user();
			    if (bp_displayed_user_id() == $current_user->ID) {
				$query_temp = query_posts( array(
				    'post_type' => 'download',
				    'author' => bp_displayed_user_id(),
				    'post_status' => 'pending'
				));
				if ($query_temp) $projects_count += $wp_query->found_posts;
			    }
			    ?>
			    
			    <ul id="item-submenu">
				<li id="item-submenu-activity" class="selected"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('activity')"><?php _e("Fil d&apos;activit&eacute;", "yproject"); ?></a></li>
				<li id="item-submenu-following"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('following')"><?php _e("Abonnements", "yproject"); ?> (<?php echo $following_count; ?>)</a></li>
				<li id="item-submenu-followers"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('followers')"><?php _e("Abonn&eacute;s", "yproject"); ?> (<?php echo $followers_count; ?>)</a></li>
				<li id="item-submenu-projects"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('projects')"><?php _e("Projets", "yproject"); ?> (<?php echo $projects_count; ?>)</a></li>
			    </ul>
			    
			    <div id="item-body">

				<div id="item-body-activity">
				    <?php do_action( 'bp_before_member_body' );

				    if ( bp_is_user_activity() || !bp_current_component() ) :
					    locate_template( array( 'members/single/activity.php'  ), true );

				    endif;

				    do_action( 'bp_after_member_body' ); ?>
				</div>

				<div id="item-body-following" style="display:none">
				    <ul>
				    <?php 
				    for ($i = 0; $i < $following_count; $i++) {
					$user_temp = get_userdata($following_list[$i]);
					echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">' . $user_temp->display_name . '</a></li>';
				    }
				    ?>
				    </ul>
				</div>
				
				<div id="item-body-followers" style="display:none">
				    <ul>
				    <?php 
				    for ($i = 0; $i < $followers_count; $i++) {
					$user_temp = get_userdata($followers_list[$i]);
					echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">' . $user_temp->display_name . '</a></li>';
				    }
				    ?>
				    </ul>
				</div>
				
				<div id="item-body-projects" style="display:none">
				    <?php
					query_posts( array(
					    'post_type' => 'download',
					    'author' => bp_displayed_user_id(),
					    'post_status' => 'publish'
					) );
					
					if (have_posts()) {
					    echo 'Projets sur le site :<br />';
					    echo '<ul>';
					    while (have_posts()) {
						the_post();
						echo '<li><a href="';
						the_permalink();
						echo '">';
						the_title();
						echo '</a></li>';
					    }
					    echo '</ul>';
					}
					
					if (bp_displayed_user_id() == $current_user->ID) {
					    query_posts( array(
						'post_type' => 'download',
						'author' => bp_displayed_user_id(),
						'post_status' => 'private'
					    ));

					    if (have_posts()) {
						echo 'Projets en attente de validation :<br />';
						echo '<ul>';
						while (have_posts()) {
						    the_post();
						    echo '<li><a href="';
	$preview_link = set_url_scheme( get_permalink( $post->ID ) );
	$preview_link = esc_url( apply_filters( 'preview_post_link', add_query_arg( 'preview', 'true', $preview_link ) ) );
//						    the_permalink();
//						    echo '&preview=true">';
	echo $preview_link . '">';
						    the_title();
						    echo '</a></li>';
						}
						echo '</ul>';
					    }
					}
					    
				    ?>
				</div>

			    </div><!-- #item-body -->

			    <?php do_action( 'bp_after_member_home_content' ); ?>
			</div>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>
