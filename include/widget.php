<?php 
/*  Copyright 2015  Marchese Simone (email : simone.marchese@gmail.com) */

if (!defined('ABSPATH')) die("Accesso diretto al file non permesso");

class DwTwWidget extends WP_Widget {
    function DwTwWidget() {
        $widget_ops = array('description' => 'Use this widget to show your twitter in your sidebar.' );
        parent::__construct( false, 'Twitter Base', $widget_ops);
    }
    function widget( $args, $instance ) {
        extract($args);
		
		$count =  $instance['numtwitter'];
		
		$consumerkey = get_option('dw_consumerkey');		
		$consumersecret = get_option('dw_consumersecret');
		$accesstoken = get_option('dw_accesstoken');
        $accesstokensecret = get_option('dw_accesstokensecret');
        $twitterusername = get_option('dw_twitteruser');
        $textfollow = $instance['textfollow'];
		$title = $instance['title'];
		
		$error = 0;
		$error_empty = array();
		
        echo $before_widget;
        if($title!="") echo "<h4>".$title."</h4>";
      //  echo $before_title.$instance['title'].$after_title;
 
                      
		# Controllo che siano stati impostati i dati per far funzionare il plugin 
		if($twitterusername==""){
			$error = 1;
			$error_empty[0] = "Twitter username";
		}
		if($consumerkey==""){
			$error = 1;
			$error_empty[1] = "Consumerkey";
		}
		if($consumersecret==""){
			$error = 1;
			$error_empty[2] = "Consumersecret";
		}
		if($accesstoken==""){
			$error = 1;
			$error_empty[3] = "Accesstoken";
		}
		if($accesstokensecret==""){
			$error = 1;
			$error_empty[4] = "Accesstokensecret";
		}
		if($error==0){
				
			$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
			$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitterusername."&count=".$count);	
			
        	echo "<ul>";
			for($i=0; $i<$count; $i++){
				echo "<li>".getURL($tweets[$i]->text)."</li>";	
			}
			echo "</ul>";
			
			# Add Text Follow User
			if($textfollow == 1){
				echo "<a href=\"https://twitter.com/intent/user?screen_name=".$twitterusername."\" target='_blank'>Follow $twitterusername</a>";	
			}
		
		}else{
			echo "Field missing:";
			foreach($error_empty as $key){
				echo "<br /> - ".$key;
			}
		}
        		
			 
       //FINE WIDGET
 
       echo $after_widget;
    }
    function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
    function form( $instance ) {
        if($instance['numtwitter']!=""){
        	$numtwitter = esc_attr($instance['numtwitter']);	
        }else $numtwitter = 1;	
        	
        
		$title = esc_attr($instance['title']);
		$textfollow = esc_attr($instance['textfollow']);
		if($textfollow==1) { $checked = ' checked="checked" '; }
		
	?>  
		<p><label for="<?php echo $this->get_field_id('title');?>">
        Titolo 
        <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>" />
        </label></p> 
	    <p><label for="<?php echo $this->get_field_id('numtwitter');?>">
        Numero di twitter 
        <input class="widefat" id="<?php echo $this->get_field_id('numtwitter');?>" name="<?php echo $this->get_field_name('numtwitter');?>" type="number" step="1" min="1" max="15" 
        value="<?php echo $numtwitter; ?>" />
        </label></p>
       	<p><label for="<?php echo $this->get_field_id('textfollow');?>">
        Visualizza testo follow? 
        <input type="checkbox" value="1" id="<?php echo $this->get_field_id('textfollow');?>" name="<?php echo $this->get_field_name('textfollow');?>"  <?php echo $checked; ?> />
        </label></p>
        <?php
        
        wp_reset_postdata();
    }
	
 

}

function my_register_widgets() {
    register_widget( 'DwTwWidget' );
}
 
add_action( 'widgets_init', 'my_register_widgets' );
?>