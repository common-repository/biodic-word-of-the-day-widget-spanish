<?php
/**
 * Plugin Name: BioDic Widget - Diccionario de Biología
 * Plugin URI: https://www.biodic.net
 * Description: Este plugin te permite tener en tu sidebar la palabra del d&iacute;a que se muestra en BioDic https://www.biodic.net/ y as&iacute; aprender cada d&iacute;a una palabra nueva.
 * Version: 1.6.3
 * Author: BioScripts - Centro de Investigación y Desarrollo de Recursos Científicos
 * Author URI: https://www.bioscripts.net
 * License: GPL2
 * Text Domain: biodic-word-of-the-day-widget-spanish
 * Domain Path: /languages
 */

add_action('plugins_loaded', function() {
    load_plugin_textdomain( 'biodic-word-of-the-day-widget-spanish', false, __DIR__ );
});

 class wp_my_plugin extends WP_Widget {

	// constructor
	 function wp_my_plugin() {
        parent::WP_Widget(false, $name = __('BIODIC - Palabra del día ES', 'biodic-word-of-the-day-widget-spanish') );
    }


	// widget form creation
	function form($instance) {
	
	// Check values
	if( $instance) {
	     $title = esc_attr($instance['title']);
	} else {
	     $title = '';
	}
	?>
	
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('BioDic - Palabra del día ES', 'biodic-word-of-the-day-widget-spanish'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	
	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
	      $instance = $old_instance;
	      // Fields
	      $instance['title'] = strip_tags($new_instance['title']);
	     return $instance;
	}

	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="widget-text wp_widget_plugin_box">';
	
	   // Check if title is set
	   if ( $title ) {
	      echo $before_title . $title . $after_title;
	   }
	
	   //WORD OF THE DAY
	   $url = "https://www.biodic.net/rss_dia_es.php";
	   
		 //create new document object
		 $dom_object = new DOMDocument();
		 //load xml file
		 $dom_object->load($url);
		 
		 $item = $dom_object->getElementsByTagName("item");
		 
		 foreach( $item as $value )
		 {
		 /*$codes = $value->getElementsByTagName("code");
			$code  = $codes->item(0)->nodeValue;*/
		 $titles = $value->getElementsByTagName("title");
		 $title  = $titles->item(0)->nodeValue;
		 
		 $descriptions  = $value->getElementsByTagName("description");
		 $description  = $descriptions->item(0)->nodeValue;
		 
		 $links  = $value->getElementsByTagName("link");
		 $link  = $links->item(0)->nodeValue;
		 
		 $description = substr($description, 0, 75);	

		 echo "<blockquote><strong><a href=\"".$link."\">".$title."</a></strong>: ".$description."... <a href=\"".$link."\">Leer m&aacute;s</a></blockquote>";
		 }
	   echo '<div style="clear: both;"></div>';
	   echo '</div>';
	   echo $after_widget;
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));

?>