<?php

get_header( 'buddypress' ); 
?>

	<div id="content">
		<div class="padder">
			
			<?php // La barre d'admin n'apparait que pour l'admin du site et si on est l'utilisateur qu'on affiche 		
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$displayed_user_id = bp_displayed_user_id();
			if ($current_user_id == $displayed_user_id) {
    				//locate_template( array( 'members/single/admin-bar.php' ), true ); 
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
			    
			    if ($current_user_id == $displayed_user_id) {
				$query_temp = query_posts( array(
				    'post_type' => 'download',
				    'author' => bp_displayed_user_id(),
				    'post_status' => 'pending'
				));
				if ($query_temp) $projects_count += $wp_query->found_posts;
			    }
			    ?>
			    
			    <ul id="item-submenu">
				<li id="item-submenu-activity" class="selected"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('activity')"><?php _e("Activit&eacute;s", "yproject"); ?></a></li>
				<li id="item-submenu-projects"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('projects')"><?php _e("Projets et Investissements", "yproject"); ?></a></li>
				<li id="item-submenu-community"><a href="javascript:void(0);" onclick="javascript:YPUIFunctions.switchProfileTab('community')"><?php _e("Communaut&eacute;", "yproject"); ?></a></li>
			    </ul>
			   
			     
			    <div id="item-body">

				<div id="item-body-activity">
				    <?php do_action( 'bp_before_member_body' );

				    if ( bp_is_user_activity() || !bp_current_component() ) :
					    locate_template( array( 'members/single/activity.php'  ), true );

				    endif;

				    do_action( 'bp_after_member_body' ); ?>
				</div>

				<div id="item-body-projects" style="display:none">
				    <?php 
				    locate_template( array( 'members/single/projects.php'  ), true );
				    ?>
				</div>
				
				<div id="item-body-community" style="display:none">
				    <ul>
				    <?php 
				    locate_template( array( 'members/single/community.php'  ), true );
				    ?>
				   
				    </ul>
				</div>
			</div><!-- #item-body -->
			 
			    <?php do_action( 'bp_after_member_home_content' ); ?>
		</div>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>
