<?php 
	//Add Scripts
	function mp_add_scripts() {
		// Add main CSS
		wp_enqueue_style('mp-main-style', plugins_url(). '/mediumposts/css/style.css');
		// Add main JS
		wp_enqueue_script('mp-main-script', plugins_url(). '/mediumposts/js/main.js');

	}

	add_action('wp_enqueue_scripts', 'mp_add_scripts');
	