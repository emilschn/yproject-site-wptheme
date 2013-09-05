<?php get_header(); ?>

<?php 
    date_default_timezone_set("Europe/Paris");
    require_once("common.php");
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div id="content">
		<div class="padder">
				
			<div class="page" id="blog-single" role="main">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content">
					<div class="entry">
				 		<?php printPageTop($post); ?>
						<div id="post_bottom_bg">

							<div id="post_bottom_content" class="center">
							    <div class="left post_bottom_desc">
									<div>
										<h2>COMMENT CA MARCHE?</h2>
										<p>
											Le site wedogood.co est une plate-forme de financement participatif sur Internet (crowdfunding) dédiée à des projets économiques responsables, c'est à dire qui vont chercher à être rentables tout en ayant un impact sociétal positif (social, environnemental, etc.).

											wedogood.co fonctionne sur le principe de l'investissement. En effet, nous croyons que l'économie peut et doit être responsable, et que la valeur doit être partagée. Vous placez donc de l'argent sur un projet, et s'il réussit vous retrouvez votre argent au bout d'un certain temps avec des bénéfices. Si au contraire il échoue, ce qui est une issue possible quand on se lance ("celui qui ne se plante jamais n'a aucune chance de pousser"), Vous risquez de perdre tout ou partie de votre investissement, ni plus ni moins.
										</p>
										<div style="width: 630px; height: 180px; border:1px solid red;">
											TODO: IMAGE
										</div>
									</div>
									<div>
										<h2>LE FINANCEMENT</h2>
										<p>
											L'investissement : vous pouvez investir sur un projet le montant de votre souhait (un nombre entier compris entre 1 et le montant demandé par le projet). Au moment d'investir, il vous est demandé de valider le contrat lié au projet. Certaines informations supplémentaires peuvent vous êtres demandées pour être en conformité avec la législation. Si la collecte d'un projet que vous financez réussit, votre investissement est mis à disposition du porteur de projet pour la durée convenue. Si le projet réussit, vous recevez votre investissement à l'issue de la durée du projet, plus votre part des bénéfices. S'il échoue, vous recevez une partie ou rien de votre investissement initial.
										</p>

									</div>
									<div>
										<h2>LE PORTEUR DE PROJET</h2>
										<p>
											Tout utilisateur inscrit sur la plate-forme peut proposer un projet. En soumettant un projet sur le site, vous vous engagez à suivre le processus jusqu'à son terme.

											Le projet est soumis au vote des internautes, à l'issue duquel il est ou non validé et le contrat est fixé.

											Le projet est publié et les internautes peuvent investir dessus.
											</p>
											<div style="width: 630px; height: 80px; border:1px solid red;">
											TODO: METTRE IMAGE LOGO COURT EN ARRIERE PLAN DES LIENS
											<ul>
											<?php /* Menu Proposer un projet */ $page_start = get_page_by_path('proposer-un-projet'); ?>
											<li class="page_item"><a href="<?php echo get_permalink($page_start->ID); ?>"><?php echo __('Proposer un projet', 'yproject'); ?></a></li>
											</ul>

											<ul>
											<?php /* Menu Découvrir les projets */ $page_discover = get_page_by_path('projects'); ?>
											 	<li class="page_item"><a href="<?php echo get_permalink($page_discover->ID); ?>"><?php echo __('Decouvrir les projets', 'yproject'); ?></a>
												</li>
											</ul>
				
										</div>
										</div>
								 </div> 
							   

							    <div class="left post_bottom_infos">

							    	<div class="post_bottom_buttons">
							    		<div class="dark" style="color:white;">FAQ</div>
							    		<div class="light" >
							    			DERNIERES FAQ							    		
							    			<div class="light" id="last-faq" style="font-size:12px; font-weight:600; text-transform:none;">
							    			<p style="background-color:white;border:1px solid white;"><?php showFaq(5); ?></p>
							    		    </div>
							    		</div>
							    	</div>
							    	<div class="post_bottom_buttons">
							    		<div class="dark" style="color:white;">QUESTIONS (FORUM)</div>
							    		<div class="light">DERNIERES QUESTIONS
							    		<div class="light" id="last-questions" style="font-size:12px; text-transform:none;">
							    			<p style="background-color:white;border:1px solid white;">
							    				<?php echo do_shortcode('[bbp-topic-index]'); ?>
							    			</p>
							    		</div>
							    		</div>
							    	</div>

							    </div>
							    <div style="clear: both"></div>

							</div>
						 </div> 
							   
					</div>

					</div>
				</div>

			</div>
			

			<?php do_action( 'bp_after_blog_single_post' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->


<?php endwhile; else: ?>
	<div id="content">
	    <div class="padder center">
		<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?></p>
	    </div><!-- .padder -->
	</div><!-- #content -->
<?php endif; ?>
	
<?php get_footer(); ?>