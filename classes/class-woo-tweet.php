<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * WooTweet Base Class
 *
 * All functionality pertaining to core functionality of the WooTweet plugin.
 *
 * @package WordPress
 * @subpackage WooTweet
 * @author qsheeraz
 * @since 0.0.1
 *
 */

class Woo_Tweet {
	public $version;
	private $file;

	private $prefix;

	private $plugin_url;
	private $assets_url;
	private $plugin_path;

	private $wt_app_key;
	private $wt_app_secret;
	private $wt_access_token;
	private $wt_token_secret;
	
	public $twitter;

	
	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct ( $file ) {
		
		$this->version = '';
		$this->file = $file;
		$this->prefix = 'woo_tweet_';
		$this->wt_app_key = get_option( 'wt_app_key' );
		$this->wt_app_secret = get_option( 'wt_app_secret' );
		$this->wt_access_token = get_option( 'wt_access_token' );
		$this->wt_token_secret = get_option( 'wt_token_secret' );

		/* Plugin URL/path settings. */
		$this->plugin_url = str_replace( '/classes', '', plugins_url( plugin_basename( dirname( __FILE__ ) ) ) );
		$this->plugin_path = str_replace( 'classes', '', plugin_dir_path( __FILE__ ));
		$this->assets_url = $this->plugin_url . '/assets';
		
		$this->twitter = new TwitterOAuth( $this->wt_app_key, $this->wt_app_secret, $this->wt_access_token, $this->wt_token_secret );
		
	} // End __construct()

	/**
	 * init function.
	 *
	 * @access public
	 * @return void
	 */
	public function init () {

		add_action( 'init', array( $this, 'load_localisation' ) );
		add_action( 'admin_init', array( $this, 'wootweet_admin_init' ) );
		add_action( 'admin_menu', array( $this, 'wootweet_admin_menu' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'wootweet_meta_box' ) );
		add_action( 'save_post', array( $this, 'wt_tweet_post' ));
		add_action( 'wp_ajax_wt_editor_action', array( $this, 'wt_editor_action' ));
		add_action( 'wp_ajax_wt_save_app_info', array( $this, 'wt_save_app_info' ));
		add_action( 'woocommerce_single_product_summary', array( $this, 'wt_show_sharing_buttons'), 50, 2  );
		add_filter( 'manage_edit-product_columns', array($this, 'wootweet_columns'), 998);
		add_action( 'manage_product_posts_custom_column', array($this, 'wootweet_custom_product_columns') );
		add_action( 'admin_footer', array($this, 'wt_jquery_change_url') );
		add_action( 'restrict_manage_posts', array($this, 'add_list_wootweet') );
		add_action( 'widgets_init', array( $this, 'register_wt_widget' ) );
		// Run this on activation.
		register_activation_hook( $this->file, array( $this, 'activation' ) );
	} // End init()
	
	function pa($arr){

		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}

	// register Wt_Widget widget
	function register_wt_widget() {
		register_widget( 'WT_Widget' );
	}


	/**
	 * wootweet_columns function.
	 *
	 * @access public
	 * @return columns
	 */
	function wootweet_columns($columns) {
		if ( isset( $_REQUEST['list'] ) && $_REQUEST['list'] == 'wootweet' ) {
		    $columns = array();
			$columns["cb"] = "<input type=\"checkbox\" />";
			$columns['thumb'] = '<span class="wc-image tips" data-tip="' . __( 'Image', 'wootweet' ) . '">' . __( 'Image', 'wootweet' ) . '</span>';
			$columns["name"] = __( 'Name', 'wootweet' );
			$columns["tweet_btn"] = __('Tweet Button?', 'wootweet');
			$columns["tweeted"] = __('Tweeted?', 'wootweet');
			//$columns["custom_msg"] = __('Custom Message', 'wootweet');

			return $columns;
		}
		else
			return $columns;
	}
		
	/**
	 * wootweet_custom_product_columns function.
	 *
	 * @access public
	 * @return void
	 */
	function wootweet_custom_product_columns( $column ) {
		global $post;//, $woocommerce, $the_product;

		if ( empty( $the_product ) || $the_product->id != $post->ID )
			$the_product = wc_get_product( $post );

		switch ($column) {
			case "tweet_btn" :
				if ( metadata_exists('post', $post -> ID, '_wootweet_tweet_btn') ){
					$woo_like_fb = get_post_meta( $post -> ID, '_wootweet_tweet_btn', true );
					//$woo_like_fb = metadata_exists('post', $post -> ID, '_woosocio_like_facebook') ? get_post_meta( $post -> ID, '_woosocio_like_facebook', true ) : 'No';
					echo ($woo_like_fb == 'checked') ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" title="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" title="No" width="25">';
				} else 
					echo '<img src="'.$this->assets_url.'/na.png" alt="N/A" title="N/A" width="25">';
				
			break;
			case "tweeted" :
				$tweeted = metadata_exists('post', $post -> ID, '_wt_tweeted') ? get_post_meta( $post -> ID, '_wt_tweeted', true ) : 'No';
				echo $tweeted == 'checked' ? '<img src="'.$this->assets_url.'/yes.png" alt="Yes" width="25">' : '<img src="'.$this->assets_url.'/no.png" alt="No" width="25">';			
			break;
		}
}

	/**
	 * show_sharing_buttons function.
	 *
	 * @access public
	 * @return void
	 */
	public function wt_show_sharing_buttons() {
		$post_id = get_the_ID();
		$wt_tweet_btn = metadata_exists('post', $post_id, '_wootweet_tweet_btn') ? get_post_meta( $post_id, '_wootweet_tweet_btn', true ) : 'checked';
		if ($wt_tweet_btn) {
		  ?>
		  <div style="margin-top:5px">
          <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a> 
          </div>
		  <script><!--
		  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
		  if(!d.getElementById(id)){js=d.createElement(s);js.id=id;
		  js.src=p+'://platform.twitter.com/widgets.js';
		  fjs.parentNode.insertBefore(js,fjs);}}
		  (document, 'script', 'twitter-wjs');
          //--></script>
		  <?php
		}
	}

	/**
	 * wootweet_meta_box function.
	 *
	 * @access public
	 * @return void
	 */
	public function wootweet_meta_box() {
		global $post;
		global $post_type;
		$post_id = get_the_ID();
		
		if ( $post_type == 'product' )
		{
			?>

		<div id="wootweet" class="misc-pub-section misc-pub-section-last">
			<?php
			$content = '';

			_e( 'WooTweet:', 'wootweet' );
			//metadata_exists('post', $post_id, '_woosocio_facebook');
			$wt_tweet_btn_chkbox = metadata_exists('post', $post_id, '_wootweet_tweet_btn') ? get_post_meta( $post_id, '_wootweet_tweet_btn', true ) : 'checked';
			$wt_tweet_chkbox = metadata_exists('post', $post_id, '_wt_tweet') ? get_post_meta( $post_id, '_wt_tweet', true ) : 'checked';
			//$saved_msg = ( get_post_meta( $post_id, '_woosocio_msg', true ) ? get_post_meta( $post_id, '_woosocio_msg', true ) : $post->title );
			?>
            	
                <input type="checkbox" name="wt_tweet" id="wt-tweet" <?php echo $wt_tweet_chkbox; ?> />
                <label for="wt-tweet"><b><?php _e( 'Tweet it?', 'wootweet' ); ?></b></label><br />
                <input type="checkbox" name="wt_tweet_btn" id="wt-tweet-btn" <?php echo $wt_tweet_btn_chkbox; ?> />
                <label for="wt-tweet-btn"><b><?php _e( 'Show Tweet button?', 'wootweet' ); ?></b></label><br />
                <input type="hidden" name="postid" id="postid" value="<?php echo get_the_ID()?>" />

		</div> 
        
		<script type="text/javascript"><!--
        jQuery(document).ready(function($){
           
		    $("input:checkbox[name^='wt_tweet']").click(function() {
				//var custom_msg;
       			//custom_msg = $("#woosocio-custom-msg").val();
				var data = {
					action: 'wt_editor_action',
					postid: $("#postid").val(),
					wt_tweet: $("#wt-tweet").attr("checked"),
					wt_tweet_btn: $("#wt-tweet-btn").attr("checked"),
				};
				$.post(ajaxurl, data, function(response) {
					console.log('Got this from the server: ' + response);
				});
            });
        });
		//-->
        </script>
		<?php 
		}
	}

	/**
	 * wt_editor_action function.
	 *
	 * @access public
	 * @return void
	 */	
	public function wt_editor_action($post) {

		if ( ! update_post_meta ($_POST['postid'], '_wt_tweet', 
								 $_POST['wt_tweet'] ) ) 
			   add_post_meta(    $_POST['postid'], '_wt_tweet', 
			   				     $_POST['wt_tweet'], true );

		if ( ! update_post_meta ($_POST['postid'], '_wootweet_tweet_btn', 
								 $_POST['wt_tweet_btn'] ) ) 
			   add_post_meta(    $_POST['postid'], '_wootweet_tweet_btn', 
			   				     $_POST['wt_tweet_btn'], true );

		die(0);
	}
	
	/**
	 * wootweet_admin_init function.
	 *
	 * @access public
	 * @return void
	 */		
	public function wootweet_admin_init() {
       /* Register stylesheet. */
        wp_register_style( 'wootweetStylesheet', $this->plugin_url.'/wootweet.css' );
		
		register_setting( 'wootweet_options', 'wootweet_settings' );
	
		add_settings_section(
			'wootweet_options_section', 
			__( 'WooTweet Options', 'wootweet' ), 
			array($this, 'wootweet_settings_section_callback'), 
			'wootweet_options'
		);
	
		add_settings_field( 
			'wootweet_checkbox_post_update', 
			__( 'Tweet everytime on product update?', 'wootweet' ), 
			array($this, 'wootweet_checkbox_post_update'), 
			'wootweet_options', 
			'wootweet_options_section' 
		);
	
		add_settings_field( 
			'wootweet_checkbox_notifications', 
			__( 'Get error notifications by email?', 'wootweet' ), 
			array($this, 'wootweet_checkbox_notifications'), 
			'wootweet_options', 
			'wootweet_options_section' 
		);

    }

	/**
	 * wootweet_options function.
	 *
	 * @access public
	 * @return void
	 */		
	public function wootweet_options () {
		
	?>
	<form action='options.php' method='post'>
		
		<h2>WooTweet</h2>
		
		<?php
		settings_fields( 'wootweet_options' );
		do_settings_sections( 'wootweet_options' );
		submit_button();
		?>
		
	</form>
	<?php

	}

	function wootweet_checkbox_post_update(  ) { 
		$options = get_option( 'wootweet_settings' );
		if ( !isset ( $options['wootweet_checkbox_post_update'] ) )
			$options['wootweet_checkbox_post_update'] = 0;
		?>
		<input type='checkbox' name='wootweet_settings[wootweet_checkbox_post_update]' <?php checked( $options['wootweet_checkbox_post_update'], 1 ); ?> value='1'>
		<?php
	
	}
	
	
	function wootweet_checkbox_notifications(  ) { 
		$options = get_option( 'wootweet_settings' );
		if ( !isset ( $options['wootweet_checkbox_notifications'] ) )
			$options['wootweet_checkbox_notifications'] = 0;
		?>
		<input type='checkbox' name='wootweet_settings[wootweet_checkbox_notifications]' <?php checked( $options['wootweet_checkbox_notifications'], 1 ); ?> value='1'>
		<?php
	
	}

	function wootweet_settings_section_callback(  ) { 
	
		echo __( 'Settings', 'wootweet' );
	
	}

	/**
	 * socialize_post function.
	 *
	 * @access public
	 * @return void
	 */		
	public function wt_tweet_post($post_id){

		if( get_post_type( $post_id ) == "product" and get_post_status($post_id) == "publish" ){		
		
			$message = get_the_title($post_id);
			$wt_tweet = metadata_exists('post', $post_id, '_wt_tweet') ? get_post_meta( $post_id, '_wt_tweet', true ) : 'checked';
			$wt_tweeted = metadata_exists('post', $post_id, '_wt_tweeted') ? get_post_meta( $post_id, '_wt_tweeted', true ) : '';
			//$message.= metadata_exists('post', $post_id, '_woosocio_msg') ? " - ".get_post_meta( $post_id, '_woosocio_msg', true ) : '';
			//$fb_page_value = get_option( $this->fb_user_profile['id'].'_fb_page_id', array('me' => $this->fb_user_profile['id']));
			$options = get_option( 'wootweet_settings' );
			$repost = !$wt_tweet ? true : $options['wootweet_checkbox_post_update'];
			
			if ( $wt_tweet && $repost )
			{

				$_pf = new WC_Product_Factory();  
				$_product = $_pf->get_product($post_id);

				//$post_desc = strip_tags( get_post_field( 'post_content', $post_id ) );
				$curr_symb = get_woocommerce_currency_symbol();
				$message.= "\n" . __( 'Price: ', 'wootweet') 
						. html_entity_decode($curr_symb, ENT_COMPAT, "UTF-8") 
						. $_product->get_price() . "\n"
						//. $post_desc . "\n" 
						. __( 'Link: ', 'wootweet') 
						. get_permalink( $post_id );

				if ( strlen( $message ) > 140 ) {
					$raw_message = __( 'Link: ', 'wootweet') . get_permalink( $post_id );
					$message = strip_tags( mb_strimwidth($raw_message, 0, 137, "...") );
				}

				$media = $this->twitter->upload('media/upload', array('media' => get_attached_file( get_post_thumbnail_id( $post_id ) )));
				$param = array( 'status' 	=> $message,
								'media_ids' => $media->media_id_string
							  );


				$ret_obj = $this->twitter->post("statuses/update", $param);

				if ($this->twitter->getLastHttpCode() == 200) {

					if ( ! update_post_meta ($post_id, '_wt_tweeted', 'checked' ) ) 
						   add_post_meta(    $post_id, '_wt_tweeted', 'checked', true );

				} else {

					if ( $options['wootweet_checkbox_notifications'] ){

						$admin_email = get_option( 'admin_email' );
						if ( empty( $admin_email ) ) {

							$current_user = wp_get_current_user();
							$admin_email = $current_user->user_email;
						}
						
						$msg = "Dear user,\r\n";
						$msg.= "Your product ID ".$post_id." not posted on Twitter due to following reason.\r\n";
						$msg.= 'Error code: ' . $ret_obj->errors[0]->code . "\r\n" . $ret_obj->errors[0]->message;
						
						wp_mail($admin_email, 'WooTweet - Notification', $msg, $this->wootweet_headers());
					}
					return false;
				}

				if ( ! update_post_meta ($post_id, '_wt_tweet', 'checked' ) ) 
					   add_post_meta(    $post_id, '_wt_tweet', 'checked', true );

			} else return;
		} else return;
	}

	/**
	 * wootweet_admin_menu function.
	 *
	 * @access public
	 * @return void
	 */		
	public function wootweet_admin_menu () {

		add_menu_page( 'WooTweet', 'WooTweet', 'manage_woocommerce', 'wootweet', '', $this->assets_url.'/Twitter-icon.png', 52 );
		$page_logins   = add_submenu_page( 'wootweet', 'WooTweet Logins', 'WooTweet Logins', 'manage_woocommerce', 'wootweet', array( $this, 'socio_settings' ) );
		$page_products = add_submenu_page( 'wootweet', 'WooTweet Products', 'WooTweet Products', 'manage_woocommerce', 'wt_products_list', array( $this, 'wt_products_list' ) );
		$page_options  = add_submenu_page( 'wootweet', 'WooTweet Options', 'WooTweet Options', 'manage_woocommerce', 'wootweet_options', array( $this, 'wootweet_options' ) );

		add_action( 'admin_print_styles-' . $page_logins, array( $this, 'wootweet_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_products, array( $this, 'wootweet_admin_styles' ) );
		add_action( 'admin_print_styles-' . $page_options, array( $this, 'wootweet_admin_styles' ) );

	}

	/**
	 * wootweet_admin_styles function.
	 *
	 * @access public
	 * @return void
	 */			
	public function wootweet_admin_styles() {
       /*
        * It will be called only on plugin admin page, enqueue stylesheet here
        */
       wp_enqueue_style( 'wootweetStylesheet' );
   }

	/**
	 * add list function.
	 *
	 * @access public
	 */	
	function add_list_wootweet(){

		if ( isset($_REQUEST['list']) && $_REQUEST['list'] == 'wootweet') 
		{
			echo '<input type="hidden" name="list" value="wootweet">';
		}
	}

	/**
	 * change url function.
	 *
	 * @access public
	 * @return columns
	 */
	function wt_jquery_change_url(){
		if ( isset($_REQUEST['list']) && $_REQUEST['list'] == 'wootweet'){
			?>
			<script type="text/javascript"><!--
			jQuery(function(){
				jQuery(".all a").attr('href', function() {
					return this.href + '&list=wootweet';
				});
				
				jQuery(".publish a").attr('href', function() {
					return this.href + '&list=wootweet';
				});
				
				jQuery(".trash a").attr('href', function() {
					return this.href + '&list=wootweet';
				});
			});
			//-->
			</script>
			<?php
		}
	}

	/**
	 * socio_settings function.
	 *
	 * @access public
	 * @return void
	 */		
	public function socio_settings () {
		
		$filepath = $this->plugin_path.'wootweet.logins.php';
		if (file_exists($filepath))
			include_once($filepath);
		else
			die('Could not load file '.$filepath);
	}

	/**
	 * wt_products_list function.
	 *
	 * @access public
	 * @return void
	 */		
	public function wt_products_list () {
		
		?>
		<script type="text/javascript"><!--
			url = '<?php echo add_query_arg( array('post_type' => 'product',
											   	   'list'	   => 'wootweet'), admin_url('edit.php')) ?>';
			window.location.replace(url);
		//-->
		</script>
        <?php
	}


	/**
	 * creating email headers.
	 *
	 * @access public
	 */
	public function wootweet_headers(){
		$admin_email = get_option( 'admin_email' );
		if ( empty( $admin_email ) ) {
			$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
		}

		$from_name = get_option( 'blogname' );

		$header = "From: \"{$from_name}\" <{$admin_email}>\n";
		$header.= "MIME-Version: 1.0\r\n"; 
		$header.= "Content-Type: text/plain; charset=\"" . get_option( 'blog_charset' ) . "\"\n";
		$header.= "X-Priority: 1\r\n"; 

		return $header;
	}

	/**
	 * save facebook app id and secret function.
	 *
	 * @access public
	 */
	public function wt_save_app_info() {
		
		update_option( 'wt_app_key', 	  $_POST['wt_app_key'] );
		update_option( 'wt_app_secret',   $_POST['wt_app_secret'] );
		update_option( 'wt_access_token', $_POST['wt_access_token'] );
		update_option( 'wt_token_secret', $_POST['wt_token_secret'] );
 	}

	/**
	 * load_localisation function.
	 *
	 * @access public
	 * @return void
	 */
	public function load_localisation () {
		$lang_dir = trailingslashit( str_replace( 'classes', 'lang', plugin_basename( dirname(__FILE__) ) ) );
		load_plugin_textdomain( 'wootweet', false, $lang_dir );
	} // End load_localisation()

	/**
	 * activation function.
	 *
	 * @access public
	 * @return void
	 */
	public function activation () {
		$this->register_plugin_version();
	} // End activation()

	/**
	 * register_plugin_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function register_plugin_version () {
		if ( $this->version != '' ) {
			update_option( 'wootweet' . '-version', $this->version );
		}
	} // End register_plugin_version()
} // End Class
?>