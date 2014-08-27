<?php

get_header( 'buddypress' ); 
?>

	<div id="content">
		<div class="padder">
			
			<?php // La barre d'admin n'apparait que pour l'admin du site et si on est l'utilisateur qu'on affiche
			$display_loggedin_user = (bp_loggedin_user_id() == bp_displayed_user_id());
			if ($display_loggedin_user) {
    				//locate_template( array( 'members/single/admin-bar.php' ), true ); 
			}
			?>

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<header id="item-header" role="complementary">
			    <?php 
			    $page_modify = get_page_by_path('modifier-mon-compte');
			    if ($display_loggedin_user): 
			    ?>
			    <div class="center">
				    <div id="settings-img">
					    <a href="<?php echo get_permalink($page_modify->ID); ?>"><img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
				    </div>
			    </div>
			    <?php endif; ?>
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
			    
			    if ($display_loggedin_user) {
				$query_temp = query_posts( array(
				    'post_type' => 'download',
				    'author' => bp_displayed_user_id(),
				    'post_status' => 'pending'
				));
				if ($query_temp) $projects_count += $wp_query->found_posts;
			    }
			    ?>
			    
			    <ul id="item-submenu">
				<li id="item-submenu-activity" class="selected"><a href="javascript:void(0);"><?php _e("Activit&eacute;s", "yproject"); ?></a></li>
				<li id="item-submenu-projects"><a href="javascript:void(0);"><?php _e("Projets et investissements", "yproject"); ?></a></li>
				<li id="item-submenu-community"><a href="javascript:void(0);"><?php _e("Communaut&eacute;", "yproject"); ?></a></li>
			    </ul>
			   
			     
			    <div id="item-body">

				<div id="item-body-activity" class="item-body-tab">
				    <?php locate_template( array( 'members/single/activity.php'  ), true ); ?>
				</div>

				<div id="item-body-projects" class="item-body-tab" style="display:none">
				    <?php locate_template( array( 'members/single/projects.php'  ), true ); ?>
				</div>
				
				<div id="item-body-community" class="item-body-tab" style="display:none">
				    <?php locate_template( array( 'members/single/community.php'  ), true ); ?>
				</div>
			</div><!-- #item-body -->
			 
			    <?php do_action( 'bp_after_member_home_content' ); ?>
		</div>

	</div><!-- .padder -->
</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>
