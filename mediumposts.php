<?php
/*
Plugin Name: Medium Posts
Plugin URI: https://github.com/alegorosito/mediumposts
Description: Retreive the lasts posts of medium
Version: 1.0.0
Author: Alejandro Gabriel Gorosito
Author URI: https://alegorosito.com
*/

	if (!defined('ABSPATH')) {
		exit;
	}

	// Load Scripts
	require_once(plugin_dir_path(__FILE__).'/includes/mediumposts-scripts.php');

	// Load Class
	require_once(plugin_dir_path(__FILE__).'/includes/mediumposts-class.php');

	// Register Widget
	function register_mediumposts(){
		register_widget('Medium_Posts_Widget');
	}

	add_action('widgets_init', 'register_mediumposts');

	/*
	* Show Posts from Medium
	*/
	function mediumposts_publications () {
			
			$mediumaccount = get_option('mediumposts_mediumaccount','none');
			$mediumnposts = get_option('mediumposts_nposts','5');

			// Content
			$xmlString = 'https://medium.com/feed/@' . $mediumaccount;
			$xml = simplexml_load_file($xmlString);

			$i = 0;
			$posts = "";
			foreach ($xml->channel->item as $item) {
				$posts.= '<div id="article_'. $i .'" class="mediumpost_article">';
					$posts.= '<h4><a href="'. $item->link .'" target="_blank">' . $item->title . "</a></h4>";
					$posts.= '<p>' . $item->description . '</p>';
				$posts.= '</div>';
				if (++$i == $mediumnposts) break;
			}

			return $posts;
	}

	add_shortcode('mediumposts', 'mediumposts_publications');	




	/*
	* Show Posts from Medium Pre-Formated
	*/
	function mediumposts_publications_preformated () {
			
			$mediumaccount = get_option('mediumposts_mediumaccount','bloomberg');
			$mediumnposts = get_option('mediumposts_nposts','5');

			// Content
			$xmlString = 'https://medium.com/feed/@' . $mediumaccount;
			$xml = simplexml_load_file($xmlString);


			/*
			* Retrieve 1th article
			*/
				$i = 0;
				$posts = "";
				$posts.= '<div class="row">';
				foreach ($xml->channel->item as $item) {
					$posts.= '<div id="mp_pre_formated_'. $i .'" class="col-10">';
						$posts.= '<h4><a href="'. $item->link .'" target="_blank">' . $item->title . "</a></h4>";
						$posts.= '<p>' . $item->description . '</p>';
					$posts.= '</div>';
					
					if (++$i == 1) break;
				
				}

				/*
				* Articles start at 2nd
				*/
				$i = 0;
				$posts.= '<div class="mp_secondary col-2">';
				foreach ($xml->channel->item as $item) {
					if ($i !== 0) {
						$posts.= '<div id="mp_pre_formated_'. $i .'" class="mp_pre_formated_secondary">';
							$posts.= '<h4><a href="'. $item->link .'" target="_blank">' . $item->title . "</a></h4>";
							$posts.= '<p>' . $item->description . '</p>';
						$posts.= '</div>';
					}
					
					if (++$i == $mediumnposts) break;
				
				}
				$posts.= '</div>';

			$posts.= '</div>';

			return $posts;
	}

	add_shortcode('mediumposts_preformated', 'mediumposts_publications_preformated');


	// Admin Menu

	function mp_admin_menu_option () {
		add_menu_page('Medium Posts','Medium Posts','manage_options', 'mp-admin-menu','mp_menu_page','',200);
	}

	add_action('admin_menu', 'mp_admin_menu_option');

	function mp_menu_page() {

		if (array_key_exists('submit_medium_update', $_POST)) {
			
			update_option('mediumposts_mediumaccount',$_POST['mediumaccount']);
			update_option('mediumposts_nposts',$_POST['nposts']);

			?>
				<div id="setting-error-settings-updated" class="updated settings_error notice is-dismissible"><strong>Settings have been saved.</strong></div>
			<?php 

		}

		// Retrieve from database 
		$mediumaccount = get_option('mediumposts_mediumaccount','none');
		$mediumnposts = get_option('mediumposts_nposts','5');

		?> 

			<h1>Medium Posts</h1>
			<div class="wrap">
				
				<h2>Options</h2>
					<form method="post" action="">
						<label for="mediumaccount" class="mp_label">Medium User:</label>
						<input type="text" name="mediumaccount" value="<?php print $mediumaccount ?>" placeholder="medium user i.e. bloomberg">
						<br>
						<label for="nposts" class="mp_label">Number of posts:</label>
						<input type="number" name="nposts" value="<?php print $mediumnposts ?>" min="1" max="4">
						<br>
						<input type="submit" name="submit_medium_update" class="button button-primary" value="UPDATE VALUES">
					</form>
					<br>
					<h2>Shortcodes</h2>
					<p>Display a list of articles: <strong> [mediumposts] </strong></p>
					<p>Display a pre-formated list of articles: <strong> [mediumposts_preformated] </strong></p>
			</div>
		<?php 
	}