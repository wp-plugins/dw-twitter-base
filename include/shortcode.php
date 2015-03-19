<?php 
/*  Copyright 2015  Marchese Simone (email : simone.marchese@gmail.com) */

# Shortcode Twitter Base - Insert Username twitter
if (!defined('ABSPATH')) die("Accesso diretto al file non permesso");

# Shortcode Twitter Base - Username set 

function twb_shortcode($atts) {
  
  $consumerkey = get_option('dw_consumerkey');		
  $consumersecret = get_option('dw_consumersecret');
  $accesstoken = get_option('dw_accesstoken');
  $accesstokensecret = get_option('dw_accesstokensecret');
  $twitterusername = get_option('dw_twitteruser');
  $username = get_option('dw_twitteruser');
  
  
  extract(shortcode_atts(array(
      'num' => 0,
   ), $atts));
   
   if($num==0) $num = 1;
   

   $output = "";
   $output .= "<br />";
   $connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
   $tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$num);	
			 
	for($i=0;$i<$num;$i++){
		$output .= "<div class='twitter-list'>".getURL($tweets[$i]->text)."</div>";	
	}
	return $output;
}

add_shortcode('twitter', 'twb_shortcode');


# Shortcode Twitter Base - Card
function twb_card_shortcode($atts) {
    $username = get_option('dw_twitteruser'); 
    extract(shortcode_atts(array(
      'tweet' => 0,
    ), $atts));
   
    $msg = TruncateString($atts['tweet'],90);
   
    $output = "";
    $output .= "<div class='twb-clear'></div>";
    $output .= "<div class='twb-ct-text'>";
		$output .= "<p>".$msg."</p>";	
		$output .="<div class='twb-ct-button'><a href='https://twitter.com/share?text=".$msg."&url=".tiny_convert(get_permalink())."&via=".$username."' target='_blank'>click to tweet</a></div>";
    $output .= "</div>";
   
	return $output;
}
add_shortcode('twitter_card', 'twb_card_shortcode');
