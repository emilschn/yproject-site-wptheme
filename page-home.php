<?php if (true): ?>
<script type="text/javascript">
    (function(){
	var tId = setInterval(function(){if(document.readyState == "complete") onComplete()},11);
	function onComplete(){
	    ClickSheepAPI.sheepPath = "<?php echo get_stylesheet_directory_uri(); ?>";
	    ClickSheepAPI.create6Sheeps();
	    ClickSheepAPI.init();
	    clearInterval(tId);
	};
    })();
</script>
<?php endif; ?>

<header class="align-center header_home">
    <div id="site_name2" class="center">
       <div id="welcome_text">
	   <?php 
	       wp_reset_query();
	       the_content();
	   ?>
       </div>
    </div>
</header>


<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>


<div id="content">
    <div id="home_top" class="center">
	<div class="padder">
	    <?php /* Affichage des projets 
	    <div class="projects_vote projects_preview">
		<h1><?php echo __('Votez !', 'yproject'); ?></h1>
		<?php printPreviewProjectsVote(2); ?>
	    </div>

	    <div class="projects_current projects_preview">
		<h1><?php echo __('Les projets en cours', 'yproject'); ?></h1>
		<?php printHomePreviewProjects(3); ?>
	    </div>	

	    <div style="clear: both"></div>
	     * 
	     * 
	     * 
	     *  
	    <?php printHomePreviewProjectsTemp(4); ?>
	    
	    <div style="clear: both"></div> */ ?>


	    <div style="width:960px;">
		<div style="width:480px; float:left;">
		    <?php printHomePreviewProjectsTemp(1); ?>
		    <div style="float: left; padding-left: 10px;"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/retrouver2.jpg" width="235" height="400">  </div>
		    <div style="clear: both"></div>
		</div>

		<div style="width:480px; float:right; padding-top:65px;">
		    <a style="border:none;" href="<?php $page_connexion_register = get_page_by_path('register'); echo get_permalink($page_connexion_register->ID); ?>"><img style="padding-left:60px; padding-bottom:10px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/inscription.jpg" width="410px" height="130px" /></a><br />
		    <a style="border:none;" href="<?php $page_new_project = get_page_by_path('reveler-un-projet'); echo get_permalink($page_new_project->ID); ?>"><img style="padding-left:60px; padding-top:10px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/signaler.jpg" width="410px" height="130px" /></a>
		</div>
		<div style="clear: both;"></div>
	    </div>
	</div>
    </div>
    
    
    <div id="home_middle">
	<div id="home_middle_top">
	    <div id="home_middle_content">
		<div class="center">
		    <div class="round_title_left">
			<strong>Participez</strong><br />&agrave; un projet
		    </div>
		    <div class="round_title_right"><strong>Proposez</strong><br />un projet</div>
		    <div style="clear: both"></div>
		</div>
		<div class="center">
		    <?php // Participer à un projet | Proposer un projet ?>
		    <?php 
			wp_reset_query();
			the_content();
		    ?>
		</div>
	    </div>
	</div>
    </div>
	
	
    <div id="home_bottom" class="center">
	<div class="padder">
	    <?php /*$projects_page = get_page_by_path('projects'); ?>
	    <a href="<?php echo get_permalink($projects_page->ID); ?>"><?php _e('Decouvrir les projets', 'yproject'); ?></a><br /><br />*/ ?>
	    
	    <h2 class="underlined">Actualit&eacute;</h2>
	    <ul class="home-activity-list">
	    <?php // Affichage du fil d'actualité
	    if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&max=5' ) ) :
		while ( bp_activities() ) : bp_the_activity();
		    locate_template( array( 'activity/entry.php' ), true, false );
		endwhile;
	    endif; ?>
	    </ul>
	    
	    <?php 
	    query_posts( array(
		'post_status' => 'publish',
		'category_name' => 'wedogood',
		'orderby' => 'post_date',
		'order' => 'desc',
		'showposts' => 4
	    ) );
	    if ( have_posts() ) : ?>
	    <ul class="home-blog-list">
		<?php 
		while (have_posts()) : the_post(); 
		    wdg_showblogitem();
		endwhile; 
		?>
		<div style="clear: both;"></div>
	    </ul>
	    <?php endif; ?>
	    
	    
	    
	    <h2 class="underlined"><?php _e("Nos partenaires", "yproject"); ?></h2>
	    <?php 
		$page_makesense = get_page_by_path('makesense');
		$page_partners = get_page_by_path('partenaires');
	    ?>
	    <a href="<?php echo get_permalink($page_makesense->ID); ?>"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo_makesens.jpg"></a>
	    <a href="<?php echo get_permalink($page_partners->ID); ?>"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logos_partenaires.jpg" width="800" height="150"></a>
		
	    <?php
	    /*
	    <?php // Rechercher ?>
	    <h1><?php echo __('Trouvez les projets', 'yproject'); ?></h1>
	    TODO: une carte de France qui a un roll sur chacune des régions. Quand on clique sur une région, ça filtre les projets.<br />
	    TODO: recherche des projets par centres d'intérêts<br />
	    
	    <form action="<?php echo bp_search_form_action(); ?>" method="post" id="search-form">
		    <label for="search-terms" class="accessibly-hidden"><?php _e( 'Search for:', 'buddypress' ); ?></label>
		    <input type="text" id="search-terms" name="search-terms" value="<?php echo isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''; ?>" />

		    <?php echo bp_search_form_type_select(); ?>

		    <input type="submit" name="search-submit" id="search-submit" value="<?php _e( 'Search', 'buddypress' ); ?>" />

		    <?php wp_nonce_field( 'bp_search_form' ); ?>

	    </form><!-- #search-form -->

	    
	    <?php // Communauté : actualités | derniers investisseurs ?>
	    <strong>Ces projets ont réussi :</strong><br />
	    <div id="projects_finished">
		<?php printPreviewProjectsFinished(4); ?>
		<div style="clear: both"></div>
	    </div>
	     * 
	     */
	    ?>
	</div>
    </div>
</div><!-- #content -->

<?php
function wdg_showblogitem() {
    ?>
    <li>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	
	<?php
	    global $post;
	    $attachments = get_posts(
		array('post_type' => 'attachment',
		'post_parent' => $post->ID,
		'post_mime_type' => 'image')
	    );
	    if ($attachments) $image_src = wp_get_attachment_image_src($attachments[0]->ID, "thumbnail");
	?>
	<a href="<?php the_permalink(); ?>">
	    <img src="<?php if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; else echo $debug_src; ?>" class="project_preview_item_img" border="0" />
	</a>
	
	<div>
	    <a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a>
	</div>
	
    </li>
    <?php
}
?>