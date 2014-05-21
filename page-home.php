<?php 
date_default_timezone_set("Europe/Paris");
require_once('requests/projects.php'); 
$cache_result=$WDG_cache_plugin->get_cache('home-top');
if(false===$cache_result){
	ob_start();
 ?>
	<header class="align-center header_home">
	<section id="site_name2" class="center">
		<div id="welcome_text">
			<?php the_content(); ?>
		</div>
		<nav class="home_intro">
			<?php 
			$page_connexion_register = get_page_by_path('register');
			$page_new_project = get_page_by_path('proposer-un-projet');
			$page_faq = get_page_by_path('descriptif');
			?>
			<a href="<?php echo get_permalink($page_connexion_register->ID); ?>" class="top-button" id="top-button-sign-in">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/home_btn_inscrivez-vous.png" alt="Inscrivez-vous" />
				<div class="line1">Inscrivez-vous</div>
				<div class="line2">pour soutenir les projets de votre choix</div>
				<div class="line3"><span>Inscription</span></div>
			</a>
			<a href="<?php echo get_permalink($page_new_project->ID); ?>" class="top-button" id="top-button-offer-project">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/home_btn_proposez-un-projet.png" alt="Proposez un projet" /><br />
				<div class="line1">Pr&eacute;sentez</div>
				<div class="line2">votre projet sur WEDOGOOD.co</div>
				<div class="line3"><span>Proposez un projet</span></div>
			</a>
			<a href="<?php echo get_permalink($page_faq->ID); ?>" class="top-button" id="top-button-how-it-works">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/home_btn_comment-ca-marche.png" alt="Comment ca marche" /><br />
				<div class="line1">Des questions ?</div>
				<div class="line2">Voici les r&eacute;ponses</div>
				<div class="line3"><span>Comment &ccedil;a marche ?</span></div>
			</a>
		</nav>
	</section>
</header>
<?php
	$cache_result=ob_get_contents();
	$WDG_cache_plugin->set_cache('home-top',$cache_result,60*60*24);
	ob_end_clean();
	}
	echo $cache_result;
	?>

<div id="content">
	<?php 
		$cache_result=$WDG_cache_plugin->get_cache('home-collecte-projects');
		if(false===$cache_result){
		ob_start();
		$nb_collecte_projects=count(query_projects_collecte()); 
		if($nb_collecte_projects>0){?>
	<div class="part-title-separator" >
		<span class="part-title"> 
			En cours de financement
		</span>
	</div>
	<?php } ?>
	
	<div id="home_top" class="center">
		<div class="padder">
			<?php require('projects/home-large.php'); ?>
		</div>
	</div>
	<?php $cache_result=ob_get_contents();
		 $WDG_cache_plugin->set_cache('home-collecte-projects',$cache_result,2*60*60);
	ob_end_clean();
	}
	echo $cache_result; 
		?>

	    <?php 	$cache_result=$WDG_cache_plugin->get_cache('home-small-projects');
			if(false===$cache_result){
			ob_start();
                require('projects/home-small.php');
                $is_right_project=true;
				$preview_projects=query_projects_preview();
                $vote_projects= query_projects_vote();
				$nb_vote_projects=count($vote_projects);
				$nb_preview_projects=count($preview_projects);
				$nb_total_projects=$nb_vote_projects+$nb_preview_projects;
                if($nb_total_projects>0){
				?>
                	<div class="part-title-separator">
                        <span class="part-title"> 
                            Prochainement
						</span>
                    </div>
                    <div  class="center">
						<div class="padder">
					<?php
                        $nb_printed_post=0;
                        $is_last_post=false;
						if ($nb_vote_projects>0) {
							foreach ($vote_projects as $vote_post) {
                                $nb_printed_post++;
                                if($nb_printed_post==$nb_total_projects&&$nb_total_projects%2!=0){
                               		$is_right_project=false;
                                 	$is_last_post=true;
                                }
                                $is_right_project=print_vote_post($vote_post,$is_right_project);
							}
                            if($is_last_post)print_empty_post ();
						}
						if ($nb_preview_projects>0) {
							foreach ($preview_projects as $preview_post) {
                                $nb_printed_post++;
                                if($nb_printed_post==$nb_total_projects&&$nb_total_projects%2!=0){
                                	$is_right_project=false;
                                    $is_last_post=true;
                                }
                                $is_right_project=print_preview_post($preview_post,$is_right_project);
                            }
                            if($is_last_post) print_empty_post();
                        }
                    ?>
                     	</div>
                    </div>
            <?php
                 }
                 $cache_result=ob_get_contents();
				$WDG_cache_plugin->set_cache('home-small-projects',$cache_result,2*60*60);
				ob_end_clean();
			}
			echo $cache_result;

			$cache_result=$WDG_cache_plugin->get_cache('home-funded-projects');
			if(false===$cache_result){
			ob_start();
			  ?>
			<div  class="center">
			 	<div class="part-title-separator">
					<?php
	 			  			$nb_funded_projects=count(query_projects_funded()); 
	 			  			if($nb_funded_projects>0){?>
	 			  				<span class="part-title" > 
                           			 D&#201;j&agrave; financ&#201;
								</span>	
	 			 	<?php } ?>
   				</div>
   				<?php 	
   					if($nb_funded_projects>0){
   						require('projects/home-large.php'); ?>
   						<div class="part-title-separator"></div>
   				<?php } ?>
    	 	</div>
    	 	<?php 
    	 	$cache_result=ob_get_contents();
    	 	$WDG_cache_plugin->set_cache('home-funded-projects',$cache_result,2*60*60);
				ob_end_clean();
			}
			echo $cache_result;
		?>
    <div id="home_middle">
		<div id="home_middle_top">
	    	<div id="home_middle_content">
				<div class="center">
		   			<?php $url = 'https://www.wedogood.co/campaigns/la-ferme-de-milgoulle'; ?>
		    		<a href="<?php echo $url; ?>" style="display: block;">
		    			<div class="round_title_left">
							<strong>Participez</strong><br/>&agrave; un projet
		    			</div>
					</a>
		    		<a href="<?php echo get_permalink($page_new_project->ID); ?>" style="display: block;">
		    			<div class="round_title_right">
							<strong>Proposez</strong><br />un projet
		    			</div>
		    		</a>
		    		<div style="clear: both">
		    		</div>
				</div>
				<div class="center">
		    		<?php 
						the_content();
		    		?>
				</div>
	    	</div>
		</div>
		<div class="home_middle_desc">
			<div id="home_middle_desc_left">
				<p>Soyez acteurs et influenceurs</p>
				<p>de la communaut&#201;</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/pieces.jpg"/>
				<p>Investissez à partir de 10€</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/main.jpg"/>
				<p>Participez à l'aventure</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/fusee.jpg"/>
				<p>Boostez l'économie positive</p>
			</div>
			<div id="home_middle_desc_right">
				<p>B&#201;n&#201;ficiez d'un financement souple </p>
				<p>et adapt&#201; &Agrave; vos besoin.</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/sous.jpg"/>
				<p>Trouvez un financement pour votre projet</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/hp.jpg"/>
				<p>Faites connaître votre projet</p>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/communaute.jpg"/>
				<p>Fédérez une communauté sur la durée</p>
			</div>
		</div>
    </div>
    <div id="home_bottom" class="center">
		<div class="padder">
	   		<div class="part-title-separator">
				<span class="part-title"> 
					Actualit&#201;s
				</span>
			</div>
	    	<div class="home-activity-list-container">
				<ul class="home-activity-list">
					<?php // Affichage du fil d'actualité
						date_default_timezone_set("Europe/London");
						if ( bp_has_activities( bp_ajax_querystring( 'activity' ).'&max=10' ) ) :
		    				while ( bp_activities() ) : bp_the_activity();
								locate_template( array( 'activity/entry.php' ), true, false );
		    				endwhile;
						endif; 
		?>
					
				</ul>
	    	</div>
	    	<div class="home-blog-list-container">
				<ul class="home-blog-list">
					<?php 	$cache_result=$WDG_cache_plugin->get_cache('home-blog');
							if(false===$cache_result){
							ob_start(); 
		   				$nb_posts = 3;
		   				query_posts( array(
							'post_status' => 'publish',
							'category_name' => 'wedogood',
							'orderby' => 'post_date',
							'order' => 'desc',
							'showposts' => $nb_posts
		    				) );
		   			if ( have_posts() ) :
						while (have_posts()) : the_post(); 
			   				wdg_showblogitem();
						endwhile;
		    		endif;
		    		$cache_result=ob_get_contents();
		    	 	$WDG_cache_plugin->set_cache('home-blog',$cache_result,2*60*60);
					ob_end_clean();
					}
					echo $cache_result;  ?>
		    		<div style="clear: both;">
		    		</div>
				</ul>
				<div class="home-blog-list-nav">
		    		<?php for ($i = 1; $i <= 3; $i++) { ?>
		    			<a href="javascript:void(0);" class="home-blog-btn<?php if($i == 1) echo ' selected'; ?>" data-targetitem="<?php echo ($i-1); ?>"><?php echo $i; ?></a>
		    		<?php } ?>
				</div>
				<div class="home-blog-list-more">
		    		<?php $page_blog = get_page_by_path('blog');?>
		    		<a href="<?php echo get_permalink($page_blog->ID); ?>">
		    			Tout le blog
		    		</a>
				</div>
	    	</div>
	   		<div class="home-news-list-container">
				<ul class="home-news-list">
					<?php $cache_result=$WDG_cache_plugin->get_cache('home-news');
							if(false===$cache_result){
							ob_start(); 
							query_posts(array(
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
		    		$cache_result=ob_get_contents();
		    		$WDG_cache_plugin->set_cache('home-news',$cache_result,2*60*60);
					ob_end_clean();
					}
					echo $cache_result;  ?>
		    	<div style="clear: both;">
		   		</div>
				</ul>
				<div class="home-news-list-more">
		    		<?php $page_news = get_page_by_path('espace-presse'); ?>
		    		<a href="<?php echo get_permalink($page_news->ID); ?>">Espace presse</a>
				</div>
	    	</div>
	      	<div class="part-title-separator">
				<span class="part-title"> 
					Nos partenaires
				</span>
			</div>
	    	<?php 
				$page_partners = get_page_by_path('partenaires');
	    	?>
	    	<div class="partners_zone">
				<a href="<?php echo get_permalink($page_partners->ID); ?>"><img src="<?php echo $stylesheet_directory_uri; ?>/images/frise_partenaires_wedogood.png" width="3135" height="150" alt="logos partenaires"></a>
	    	</div>
		</div>
    </div>
</div><!-- #content -->

<?php
function wdg_showblogitem() {
    ?>
    <li>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<div class="description-separator "></div>

	<div class="blogimg">
	    <a href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail('thumbnail'); ?>
	    </a>
	</div>
	
	<div class="blogexcerpt">
	    <a href="<?php the_permalink(); ?>"><?php the_excerpt( ); ?></a>
	</div>
	
	<div class="clear"></div>
    </li>
    <?php
}

function wdg_shownewsitem(){
    ?>
    <li>
	<div class="news-img">
	    <a href="<?php the_permalink(); ?>">
		<?php the_post_thumbnail(); ?>
	    </a>
	</div>
	<div class="news-title">
	    <a href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
		
	    </a>
	</div>
    </li>
    <?php
}
?>