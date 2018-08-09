<?php
/*
Plugin Name: Funnels Embed for T4S
Plugin URI: https://www.t4s.site
Description: Funnels Embed
Version: 1.4.1
Author: Simon Goodchild
Author URI: https://www.t4s.site
*/

add_action( 'wp_head', 'embed_head' );
function embed_head() {

	global $post;
	if ($post) {
		$c = get_post_field('post_content', $post->ID);
		// Stops anything showing
		if (strpos($c, '[t4s') !== false) {
		
			echo '<style>';

				echo '::-webkit-scrollbar {'; 
					echo 'display: none !important;';
				echo '}';
			
				echo 'html, body {';
     				echo '-webkit-backface-visibility: visible;';
					echo 'height: 100%;';
    				echo 'overflow: auto;';
    				echo '-webkit-overflow-scrolling: touch;';
				echo '}';
				
				echo '*';
				echo '{ color: #fff !important; background-color: #fff !important; }';

				echo '.t4s-scroll-wrapper {';
  					echo 'position: fixed;';
  					echo 'right: 0;';
  					echo 'bottom: 0;';
  					echo 'left: 0;';
  					echo 'top: 0;';
  					echo '-webkit-overflow-scrolling: touch;';
  					echo 'overflow-y: scroll;';
  					echo 'z-index:9999999999;';
				echo '}';

				echo '.t4s-scroll-wrapper iframe {';
  					echo 'height: 100%;';
  					echo 'width: 100%;';
  					echo 'background-color:#fff;';
				echo '}';
				
			echo '</style>';
		}
	}
}

add_action( 'wp_enqueue_scripts', 't4s_script' );
function t4s_script() {
	wp_enqueue_script('t4s-js', plugins_url('t4s.js?a='.time(), __FILE__), array('jquery'));	
}


function t4s($atts) {

	if ($atts && $atts['funnel']) {

		$html = '';

		$funnel = $atts['funnel'];
		if (strpos($funnel, 'localhost') === false) {
			$funnel = str_replace('https://t4s.site/', '', $funnel);
			$funnel = ltrim($funnel, "/");
			$url = 'https://t4s.site/'.$funnel;
		} else {
			$url = $funnel;
		}

		echo '<script type="text/javascript">';
			echo 'var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);';
			echo 'if (iOS) {';
				echo 'window.location = "'.$url.'";';
				echo 'die();';
			echo '}';
		echo '</script>';		

		$html .= '<div class="t4s-scroll-wrapper">';
			$html .= '<iframe id="t4s_iframe" src="'.$url.'" frameborder="0" scrolling="yes" wmode="transparent"></iframe>';
		$html .= '</div>';
		
	} else {
		$html = 'You need to set the funnel web address as a parameter in the shortcode. For example [t4s funnel="my-funnel/my-page"]';
	}
	return $html;

}
if (!is_admin()) {
    add_shortcode('t4s', 't4s');
}

?>
