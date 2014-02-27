<?php if (false): ?>
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
		    <a style="border:none;" href="<?php $page_new_project = get_page_by_path('proposer-un-projet'); echo get_permalink($page_new_project->ID); ?>"><img style="padding-left:60px; padding-top:10px;" src="<?php echo get_stylesheet_directory_uri(); ?>/images/signaler.jpg" width="410px" height="130px" /></a>
		</div>
		<div style="clear: both;"></div>
	    </div>
	</div>
    </div>
    
    
    <div id="home_middle">
	<div id="home_middle_top">
	    <div id="home_middle_content">
		<div class="center">
		    <?php $url = 'https://www.wedogood.co/campaigns/la-ferme-de-milgoulle'; ?>
		    <a href="<?php echo $url; ?>" style="display: block;"><div class="round_title_left">
			<strong>Participez</strong><br />&agrave; un projet
		    </div></a>
		    <a href="<?php echo get_permalink($page_new_project->ID); ?>" style="display: block;"><div class="round_title_right">
			<strong>Proposez</strong><br />un projet
		    </div></a>
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
	    <h2 class="underlined">Actualit&eacute;</h2>
	    <div class="home-activity-list-container">
	    <ul class="home-activity-list">
	    <?php // Affichage du fil d'actualité
	    if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&max=10' ) ) :
		while ( bp_activities() ) : bp_the_activity();
		    locate_template( array( 'activity/entry.php' ), true, false );
		endwhile;
	    endif; ?>
	    </ul>
	    </div>
	    
	    <div class="home-blog-list-container">
	    <ul class="home-blog-list">
		<?php 
		query_posts( array(
		    'post_status' => 'publish',
		    'category_name' => 'wedogood',
		    'orderby' => 'post_date',
		    'order' => 'desc',
		    'showposts' => 6
		) );
		if ( have_posts() ) :
		    while (have_posts()) : the_post(); 
			wdg_showblogitem();
		    endwhile;
		endif;
		?>
		<div style="clear: both;"></div>
	    </ul>
	    <div class="home-blog-list-nav">
		<?php for ($i = 1; $i < 6; $i++) { ?>
		<a href="javascript:void(0);" class="home-blog-btn<?php if($i == 1) echo ' selected'; ?>" data-targetitem="<?php echo ($i-1); ?>"><?php echo $i; ?></a>
		<?php } ?>
	    </div>
	    </div>
	    
	    
	    <div class="home-news-list-container">
	    <ul class="home-news-list">
		<?php
		query_posts( array(
		    'post_status' => 'publish',
		    'category_name' => 'revue-de-presse',
		    'orderby' => 'post_date',
		    'order' => 'desc',
		    'showposts' => 5
		) );
		if ( have_posts() ) :
		    while (have_posts()) : the_post(); 
			wdg_shownewsitem();
		    endwhile; 
		endif;
		?>
		<div style="clear: both;"></div>
	    </ul>
	    </div>
	    
	    
	    
	    <h2 class="underlined"><?php _e("Nos partenaires", "yproject"); ?></h2>
	    <?php 
		$page_makesense = get_page_by_path('makesense');
		$page_partners = get_page_by_path('partenaires');
	    ?>
	    <a href="<?php echo get_permalink($page_makesense->ID); ?>"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo_makesens.jpg"></a>
	    <a href="<?php echo get_permalink($page_partners->ID); ?>"><img border="0" src="<?php echo get_stylesheet_directory_uri(); ?>/images/logos_partenaires.jpg" width="800" height="150"></a>
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
	<div class="blogimg">
	    <a href="<?php the_permalink(); ?>">
		<img src="<?php if (isset($image_src) && !empty($image_src[0])) echo $image_src[0]; ?>" border="0" />
	    </a>
	</div>
	
	<div class="blogexcerpt">
	    <a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a>
	</div>
	
	<div class="clear"></div>
    </li>
    <?php
}

function wdg_shownewsitem(){
    ?>
    <li>
	<a href="<?php the_permalink(); ?>">
	    <?php the_post_thumbnail(); ?>
	</a>
    </li>
    <?php
}
?>