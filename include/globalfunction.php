<?php 
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
    $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
    return $connection;
}

function getURL($text){
	// The Regular Expression filter
	$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
	// Check if there is a url in the text
	if(preg_match($reg_exUrl, $text, $url)) {	
	    // make the urls hyper links
	    return preg_replace($reg_exUrl, '<a href="'.$url[0].'" rel="nofollow" target="_blank">'.$url[0].'</a>', $text);
	}else{
	    // if no urls in the text just return the text
	    return $text;
	}
}

function tiny_convert($url) {
	return file_get_contents("http://tinyurl.com/api-create.php?url=" . $url); 
}

function TruncateString($string, $caratteri=50){
	$string = strip_tags($string); // elimino l'HTML dalla stringa passata 
	if (strlen($string) <= $caratteri) return $string;
	$nuovo = wordwrap($string, $caratteri, "|");
	$nuovotesto=explode("|",$nuovo); 
	return stripslashes($nuovotesto[0]." ...");
}
 ?>