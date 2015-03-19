<?php
/**
 * Plugin Name: DW Twitter Base
 * Plugin URI: http://decadeweb.it/italian/twitter-base.php
 * Description: Twitter Base è un plugin che crea una completa integrazione tra il vostro blog WordPress e il vostro account Twitter.
 * Version: 1.1.1
 * Author: Simone Marchese
 * Author URI: http://www.decadeweb.it
 * Text Domain: twitter-base
 * Domain Path: /languages/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPLv3 or later
 */

if (!defined('ABSPATH')) die("Accesso diretto al file non permesso");
/*
* Plugin path & url
*/             
   
define('TWITTER_BASE_PATH', dirname(__FILE__).'/' ); // Patch used for include
define('TWITTER_BASE_URL', plugin_dir_url(__FILE__)); // Patch user for load file

define('PLUGIN_TWITTER_BASE_CSS', TWITTER_BASE_URL . 'css/');
define('PLUGIN_TWITTER_BASE_JS', TWITTER_BASE_URL . 'js/');
define('PLUGIN_TWITTER_BASE_IMG', TWITTER_BASE_URL .'img/'); 
define('PLUGIN_TWITTER_BASE_INCLUDE', TWITTER_BASE_PATH . 'include/');
define('PLUGIN_TWITTER_BASE_LIB', TWITTER_BASE_PATH . 'lib/');

/*
* Twitter Base Library
*/
@require(PLUGIN_TWITTER_BASE_LIB.'twitteroauth/twitteroauth.php');

/**
 * Load plugin textdomain
 */
load_plugin_textdomain( 'twitter-base', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 


/*
* Twitter Base Globalfunction
*/
@require(PLUGIN_TWITTER_BASE_INCLUDE.'globalfunction.php');


/*
* Twitter Base Widget
*/
@require(PLUGIN_TWITTER_BASE_INCLUDE.'widget.php');

/*
* Twitter Base Shortcode
*/
@require(PLUGIN_TWITTER_BASE_INCLUDE.'shortcode.php');


function set_options(){
    add_option('dw_twitteruser', '');
    add_option('dw_consumerkey', '');
    add_option('dw_consumersecret', '');
	add_option('dw_accesstoken','');
	add_option('dw_accesstokensecret','');	
}
 
register_activation_hook( __FILE__, 'set_options');

function register_options_group()
{
    register_setting('dw_options_group', 'dw_twitteruser');
    register_setting('dw_options_group', 'dw_consumerkey');
    register_setting('dw_options_group', 'dw_consumersecret');
	register_setting('dw_options_group', 'dw_accesstoken');
	register_setting('dw_options_group', 'dw_accesstokensecret');
}
 
add_action ('admin_init', 'register_options_group');


function moduleStyle() {
	wp_register_style('stylesheet-css', PLUGIN_TWITTER_BASE_CSS . 'stylesheet.css');
	wp_enqueue_style('stylesheet-css');	
}
add_action( 'admin_enqueue_scripts', 'moduleStyle' );



function add_option_page(){
   global $twb_hook;
   # Add item Menu   
   $twb_hook = add_options_page('Twitter Base', 'Twitter Base', 'manage_options', 'my-unique-identifier', 'update_options_form');
	
   # register CSS Plugin
   add_action( 'admin_head-'. $twb_hook, 'register_css' );
}
 
add_action('admin_menu', 'add_option_page');

# Help Admin - For plugin Twitter Base
add_action('contextual_help', 'twb_help', 10, 3);
function twb_help($contextual_help, $screen_id, $screen) {
	global $twb_hook;
	if ($screen_id == $twb_hook) {
        $screen->remove_help_tabs();
		
		# Add Tab Support For Plugin Twitter Base
        $screen->add_help_tab(array(
            'id' => 'twb_help_support',
            'title' => 'Support',
            'content' => "<p>Se hai bisogno di informazioni sul plugin contattami!<br ><br />
			<b>Simone Marchese</b> - simone.marchese[@]gmail.com<br />
			<a hef='http://google.com/+simonemarcheseDW' target='_blank'><img src='".PLUGIN_TWITTER_BASE_IMG."google_support.png' border='0' /></a>
			&nbsp;<a hef='http://it.linkedin.com/in/marchesesimone' target='_blank'><img src='".PLUGIN_TWITTER_BASE_IMG."linkedin_support.png' border='0' /></a>
			
			<br /><br />
			Grazie per l'utilizzo di Twitter Base</p>",
        ));
		
		# Add Tab Info For Plugin Twitter Base
		
		$infoTab = __("<p>Questo plugin è stato scritto basandosi sulla versione 1.1 di Twitter OAuth API.<br /> Collegati all'indirizzo <a href='https://dev.twitter.com/apps' target='_blank'>https://dev.twitter.com/apps</a> e crea la propria applicazione, il sistema vi fornirà dei parametri univoci da utilizzare nel modulo sottostante.</p>","twitter-base");
		$screen->add_help_tab(array(
            'id' => 'twb_help_info',
            'title' => 'Twitter Base',
            'content' => $infoTab,
        ));
        
		if (count($help_tabs))
            foreach ($help_tabs as $help_tab)
                $screen->add_help_tab($help_tab);
    }
}


/*** Inseriamo la voce Twitter Base sotto impostazioni **/
function update_options_form()
{
	
    ?>
    <div class="wrap">
    	
    	<div id="icon-tools" class="icon32"></div>
    		
	    	<h2><?php _e("Configurazione di Twitter Base","twitter-base"); ?></h2>
	    	<br />
			<?php _e("Questo plugin è stato scritto basandosi sulla versione 1.1 di Twitter OAuth API.<br />","twitter-base");
				  _e("Collegati all'indirizzo <a href='https://dev.twitter.com/apps' target='_blank'>https://dev.twitter.com/apps</a> e crea la propria applicazione, il sistema vi fornirà dei parametri univoci da utilizzare nel modulo sottostante.","twitter-base"); 
			?>
	    	
	    	
	    	
	    	<br /><br />
            	<div id="setting" class="imp">           		
                    <h2><img src="<?php echo PLUGIN_TWITTER_BASE_IMG; ?>gear.png" border="0" align="left" /> <?php _e("Impostazioni","twitter-base"); ?></h2>
                 </div>
	    	    <div id="wrapper" class="config" status="active">
						        
					<form method="post" action="options.php">
	    			<?php settings_fields('dw_options_group'); ?>
					
					<div class="row">
						<div class="col-md-4">
							<label for="dw_twitteruser"><?php _e("Twitter Username","twitter-base"); ?></label>
							<input placeholder="@" type="text" id="dw_twitteruser" class="form-control" value="<?php echo get_option('dw_twitteruser'); ?>" name="dw_twitteruser" />
						</div>
						<div class="col-md-4">
							<label for="dw_consumerkey"><?php _e("Consumer Key","twitter-base"); ?></label>
							<input type="text" id="dw_consumerkey" class="form-control" value="<?php echo get_option('dw_consumerkey'); ?>" name="dw_consumerkey" />
						</div>
						<div class="col-md-4">
							<label for="dw_consumersecret"><?php _e("Consumer Secret","twitter-base"); ?></label>
							<input type="text" id="dw_consumersecret" class="form-control" value="<?php echo get_option('dw_consumersecret'); ?>" name="dw_consumersecret" />
						</div>
					</div>
					<div class="clearfix">&nbsp;</div>
					<div class="row">
						<div class="col-md-4">
							<label for="dw_accesstoken"><?php _e("Access Token","twitter-base"); ?></label>
							<input type="text" id="dw_accesstoken" class="form-control" value="<?php echo get_option('dw_accesstoken'); ?>" name="dw_accesstoken" />
						</div>
						<div class="col-md-4">
							<label for="dw_accesstokensecret"><?php _e("Access Token Secret","twitter-base"); ?></label>
							<input type="text" id="dw_accesstokensecret" class="form-control" value="<?php echo get_option('dw_accesstokensecret'); ?>" name="dw_accesstokensecret" />
						</div>
						<div class="col-md-4 text-right">
							<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes') ?>" />
						</div>
					</div>
					</form>
			
						</div><!-- end wrapper -->
				 <div id="setting"><h2>Shortcode</h2></div>
                 <div id="wrapper" class="shortcode" >
				 
				 			
				<pre>[twitter]</pre>
				 <table class="table" width="100%">
  					<thead>
  						<tr>
  							<th align="left" width="20%"><?php _e('Parametro',"twitter-base"); ?></th>
  							<th align="left" width="60%"><?php _e('Descrizione',"twitter-base"); ?></th>
  							<th align="left" width="20%"><?php _e('Valore',"twitter-base"); ?></th>
  						</tr>
  					</thead>
  					<tbody>
  						
  						<tr>
  							<td valign="top"><?php _e('num',"twitter-base"); ?></td>
  							<td valign="top"><em><?php _e('numero di twitter da visualizzare',"twitter-base"); ?></em></td>
  							<td valign="top">0</td>
  						</tr>
  						
  						
  					</tbody>
				</table>
				
				
				<div class="exampleHeader"><?php _e('Esempio:',"twitter-base"); ?></div>
				<div class="example">[twitter num="2"]</div>
				
				<pre>[twitter_card]</pre>
				 <table class="table" width="100%">
  					<thead>
  						<tr>
  							<th align="left" width="20%"><?php _e('Parametro',"twitter-base"); ?></th>
  							<th align="left" width="60%"><?php _e('Descrizione',"twitter-base"); ?></th>
  							<th align="left" width="20%"><?php _e('Valore',"twitter-base"); ?></th>
  						</tr>
  					</thead>
  					<tbody>
  						
  						<tr>
  							<td valign="top"><?php _e('tweet',"twitter-base"); ?></td>
  							<td valign="top"><em><?php _e('contenuto del tweet',"twitter-base"); ?></em></td>
  							<td valign="top"></td>
  						</tr>
  						
  						
  					</tbody>
				</table>
				
				
				<div class="exampleHeader"><?php _e('Esempio:',"twitter-base"); ?></div>
				<div class="example">[twitter_cart tweet="lorem ipsum"]</div>
		
		
	</div>
    <br />
	<div class="credits">Power <a href="http://decadeweb.it" target="_blank">Decadeweb</a> &bullet; Twitter Base versione 1.1.1</div>
    
	<?php
}





?>