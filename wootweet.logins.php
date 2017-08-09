<?php

global $wootweet, $is_IE;

?>
    
<!doctype html>
<html>
  <head>
    <title><?php _e( 'WooTweet Logins', 'wootweet' ) ?></title>
</head>
<body>
<div class="woosocio_wrap">
  <h1><?php _e( 'WooTweet Logins', 'wootweet' ) ?></h1>
  <p>
  <?php esc_html_e( 'Connect your site to Twitter and automatically share new products with your friends.', 'wootweet' ) ?>
  </p>
  <?php 
	if ($is_IE){
	  echo "<p style='font-size:18px; color:#F00;'>" . __( 'Important Notice:', 'wootweet') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . 
	  		__( 'You are using Internet Explorer. This plugin may not work properly with IE. Please use any other browser.', 'wootweet') . "</p>";
	  echo "<p style='font-size:16px; color:#F00;'>" . __( 'Recommended: Google Chrome.', 'wootweet') . "</p>";
	}
  ?>
  
  <div id="woosocio-services-block">
	<img src="<?php echo $wootweet->assets_url.'/twitter-logo.png' ?>" alt="Twitter Logo">
    <div class="woosocio-service-entry" >
		<div id="twitter" class="woosocio-service-left">
			<a href="https://twitter.com" id="service-link-facebook" target="_top">Twitter</a>
		</div>
		<div class="woosocio-service-right">
           	<div id="app-info">
            <table class="form-table">
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Consumer key:', 'wootweet') ?></label></th>
	  			<td>
	  				<input type="text" name="wt_app_key" id="wt-app-key" placeholder="<?php _e('Consumer key', 'wootweet') ?>" value="<?php echo get_option( 'wt_app_key' ); ?>" size="55" maxlength="128"><br>
                    <p style="font-size:12px"><?php _e("Don't have an app? You can create from ", 'wootweet') ?>
                    <a href="https://apps.twitter.com/" target="_new" style="font-size:12px">apps.twitter.com</a>
	  			</td>
	  		</tr>
            <tr valign="top">
	  			<th scope="row"><label><?php _e('Consumer secret:', 'wootweet') ?></label></th>
	  			<td>
	  				<input type="text" name="wt_app_secret" id="wt-app-secret" placeholder="<?php _e('Consumer secret', 'wootweet') ?>" value="<?php echo get_option( 'wt_app_secret' ); ?>" size="55" maxlength="128">
	  			</td>
	  		</tr>

            <tr valign="top">
	  			<th scope="row"><label><?php _e('Access token:', 'wootweet') ?></label></th>
	  			<td>
                    <input type="text" name="wt_access_token" id="wt-access-token" placeholder="<?php _e('Access token', 'wootweet') ?>" value="<?php echo get_option( 'wt_access_token' ); ?>" size="55" maxlength="128">
	  			</td>
	  		</tr>

            <tr valign="top">
	  			<th scope="row"><label><?php _e('Access token secret:', 'wootweet') ?></label></th>
	  			<td>
	  				<input type="text" name="wt_token_secret" id="wt-token-secret" placeholder="<?php _e('Access token secret', 'wootweet') ?>" value="<?php echo get_option( 'wt_token_secret' ); ?>" size="55" maxlength="128">
	  			</td>
	  		</tr>

            <tr valign="top">
     	  		<th scope="row"></th>
	  			<td>
	                <img id="working" src="<?php echo $wootweet->assets_url.'/spinner.gif' ?>" alt="Wait..." height="22" width="22" style="display: none;">
                	<a id="wt-btn-save" class="button-primary button" href="javascript:"><?php _e('Save', 'wootweet') ?></a>
	  			</td>
	  		</tr>
            </table>
            
            </div>
		</div>
	</div>

	<div class="woosocio-service-entry">    
        <iframe 
            src="https://www.youtube.com/embed/hfFkOZ9USeA"
            width="560" 
            height="315" 
            frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen>
        </iframe> 
        <p>
            <a href="https://www.youtube.com/watch?v=hfFkOZ9USeA" target="_new"><?php _e('How to use WooTweet Free!', 'wootweet') ?></a>
            <?php _e('from', 'wootweet') ?> <a href="http://genialsouls.com/" target="_new">GenialSouls</a>
            <?php _e('on', 'wootweet') ?> <a href="https://www.youtube.com/channel/UC1z3dGUdG5JJEIsEpaDnG5w" target="_new">YouTube</a>.
        </p>            
	</div>
    <h1><?php _e( 'You may also like:', 'wootweet' ) ?></h1>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
        <div class="woosocio-service-left">
            <a href="https://wordpress.org/plugins/wp-instagram-post/" target="_top">
            <img src="<?php echo $wootweet->assets_url.'/wpigp_icon.jpg' ?>" alt="WP Instagram Post">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
				echo '<a href="https://wordpress.org/plugins/wp-instagram-post/" target="_top">'.__('* WP Instagram Post *', 'woosocio').'</a></br>';
				_e('* Post any type of post to Instagram.', 'woosocio'); echo "</br>";
				_e('* Post product price, currency symbol, link, details.', 'woosocio'); echo "</br>";
				_e('* Post products multiple times. (on every update)', 'woosocio'); echo "</br>";
				_e('* Simple to use with Instagram user and password.', 'woosocio'); echo "</br>";
				_e('* And many more...', 'woosocio'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
        <div class="woosocio-service-left">
            <a href="http://genialsouls.com/product/woosocio-pro/" target="_top">
            <img src="<?php echo $wootweet->assets_url.'/woosocio_icon.jpg' ?>" alt="WooSocio Pro" height="128">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
				echo '<a href="http://genialsouls.com/product/woosocio-pro/" target="_top">'.__('* WooSocio Pro version *', 'wootweet').'</a></br>';
				_e('* product gallery images as Facebook gallery', 'wootweet'); echo "</br>";
				_e('* post to multiple pages and/or groups at once', 'wootweet'); echo "</br>";
				_e('* Optional time delay between posting', 'wootweet'); echo "</br>";
				_e('* Bulk posts to pages, groups', 'wootweet'); echo "</br>";
				_e('* Multi user ready', 'wootweet'); echo "</br>";
				_e('* Bulk like/share button on/off option', 'wootweet'); echo "</br>";
				_e('* And many more...', 'wootweet'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
    <div class="woosocio-service-entry" style="font-size:18px; color:#03D">
        <div class="woosocio-service-left">
            <a href="http://genialsouls.com/file-manager/" target="_top">
            <img src="<?php echo $wootweet->assets_url.'/filemanager_icon.png' ?>" alt="File Manager Pro">
            </a>
        </div>
        <div class="woosocio-service-right">
            <div align="left">
            <?php
				echo '<a href="http://genialsouls.com/file-manager/" target="_top">'.__('* File Manager *', 'wootweet').'</a></br>';
				_e('* BuddyPress Group File Share.', 'wootweet'); echo "</br>";
				_e('* Create Download Area.', 'wootweet'); echo "</br>";
				_e('* Group file sharing.', 'wootweet'); echo "</br>";
				_e('* Seven types of input fields.', 'wootweet'); echo "</br>";
				_e('* FTP files upload for Users by Admin ( v 9.3+ )', 'wootweet'); echo "</br>";
				_e('* Front end File Searching and Pagination.', 'wootweet'); echo "</br>";
				_e('* And many more...', 'wootweet'); echo "</br>";
            ?>
            </div>
        </div>
    </div>
  </div>
    <!-- Right Area Widgets -->  
    <?php 
		include_once 'right_area.php';
	 ?>
    <!-- Right Area Widgets -->  
</div>
  </body>
</html>
<script type="text/javascript"><!--
jQuery(document).ready(function($){
		
	$("#wt-btn-save").click(function(){
		$("#working").show();
		
		var data = {
			action: 'wt_save_app_info',
			wt_app_key: $("#wt-app-key").val(),
			wt_app_secret: $("#wt-app-secret").val(),
			wt_access_token: $("#wt-access-token").val(),
			wt_token_secret: $("#wt-token-secret").val()
		};
		
		$.post(ajaxurl, data, function(response) {
			console.log('Got this from the server: ' + response);
		location.reload();
		});	
		
	});

});
//-->
</script>