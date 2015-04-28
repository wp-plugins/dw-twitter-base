<?php 
/*  Copyright 2015  Marchese Simone (email : simone.marchese@gmail.com) */

# Shortcode Twitter Base - Insert Username twitter
if (!defined('ABSPATH')) die("Accesso diretto al file non permesso");

# Detect plugin. For use on Front End only.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );



# Shortcode Twitter Base - Username set 

add_action( 'init', function() {
	
	/**
	* Register a UI for the Shortcode. Twitter Base Card
	*/
	add_shortcode( 'dw-twitter-card', function( $attr, $content = '' ) {
		$username = get_option('dw_twitteruser');
        $attr = wp_parse_args( $attr, array(
            'tweet' => ''
    ));

        ob_start();

        if ( isset($attr['tweet']) && $attr['tweet'] != ""){ ?>
        <div class='twb-clear'></div>
			<div class='twb-ct-text'>
				<p><?php echo esc_html( $attr['tweet'] ); ?></p>	
			<div class='twb-ct-button'><a href="https://twitter.com/share?text=<?php echo esc_html( $attr['tweet'] ); ?>&url=<?php echo tiny_convert(get_permalink()); ?>&via=<?php echo $username; ?>" target="_blank">click to tweet</a></div>
		</div>
		<?php }else{ echo "<pre>Twitter Base Warning: Inserisci contenuto</pre>"; } 

        return ob_get_clean();
    });
	
	// check for plugin using plugin name
	if ( is_plugin_active( 'shortcode-ui/shortcode-ui.php' ) ) {

		shortcode_ui_register_for_shortcode(
			'dw-twitter-card',
			array(
				'label' => 'Twitter Base Card',
				'listItemImage' => 'dashicons-twitter',

				// Attribute model expects 'attr', 'type' and 'label'
				// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
				'attrs' => array(
					array(
					'label'       => 'Contenuto tweet',
					'attr'        => 'tweet',
					'type'        => 'textarea',
				   ),
				),
			)
		);
	
	  
	} 

	/**
	* Register a UI for the Shortcode. Twitter Base Stream
	*/
	
	add_shortcode( 'dw-twitter-stream', function( $attr, $content = '' ) {
		/**
		* Paramaters
		*/
		$consumerkey = get_option('dw_consumerkey');		
		$consumersecret = get_option('dw_consumersecret');
		$accesstoken = get_option('dw_accesstoken');
		$accesstokensecret = get_option('dw_accesstokensecret');
		$twitterusername = get_option('dw_twitteruser');
		$username = get_option('dw_twitteruser');
		
		
        $attr = wp_parse_args( $attr, array(
            'num' => ''
        ) );

        ob_start();
		$num = $attr['num'];
		if($num == 0) $num = 1;
		
        if ( ! empty( $attr['num'] ) ){ 
			$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
			$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$num);	
				 
			for($i=0;$i<$num;$i++){
				echo "<div class='twitter-list'>".getURL($tweets[$i]->text)."</div>";	
			}
		}else{} 

        return ob_get_clean();
    });
	
	if ( is_plugin_active( 'shortcode-ui/shortcode-ui.php' ) ) {
		shortcode_ui_register_for_shortcode(
			'dw-twitter-stream',
			array(

				// Display label. String. Required.
				'label' => 'Twitter Base Stream',

				// Icon/image for shortcode. Optional. src or dashicons-$icon. Defaults to carrot.
				'listItemImage' => 'dashicons-twitter',

				// Available shortcode attributes and default values. Required. Array.
				// Attribute model expects 'attr', 'type' and 'label'
				// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
				'attrs' => array(
					
					array(
					'label'       => 'Numero di tweet',
					'attr'        => 'num',
					'type'        => 'number',
				   ),
				),
			)
		);
	}
} );