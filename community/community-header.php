

	<header class="align-center">
		<div id="site_name" class="center">
			<h1>
				<?php 
				_e(get_the_title(), "yproject");
				$page_name = get_post($post)->post_name;
				if ($page_name == 'communaute') {
					$result = count_users();
					$user_count = $result['total_users'];
					echo '<br />WE ARE ' . $user_count;
				}
				?>
			</h1>
		</div>
	</header>

