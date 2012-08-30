<?php
/*
Plugin Name: ChangeStaticUri
Plugin URI: http://www.shankka.net
Description: 更换blog静态资源url
Version: 0.1
Author: shankka
Author URI: http://www.shankka.net/
*/
class csu_changer
{
	static public $change = array(
		'stylesheet_uri',
		'theme_root_uri',
		'template_directory_uri'
	);
	
	static public $base_uri_option_key = 'csu_static_base_uri';
	
	static public function filter($content)
	{
		$base_uri = get_option(self::$base_uri_option_key);
		if ($base_uri)
			return str_replace(get_bloginfo('url'), $base_uri, $content);
		else 
			return $content;
	}
	
	static public function plugin_menu()
	{
		add_options_page('静态资源根链接设置', 'change-static-uri', 'manage_options', 'change-static-uri', array('csu_changer', 'plugin_options'));
	}
	
	static public function plugin_options() 
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		if (isset($_POST['base_uri']))
			update_option(csu_changer::$base_uri_option_key, trim($_POST['base_uri']));
		$base_uri = get_option(csu_changer::$base_uri_option_key);
		$default_uri = get_bloginfo('url');
		
		$html =<<<html
<div class="wrap">
	<h2>静态资源根链接设置</h2>
	<form name="form1" method="post" action="">
	<p>
		默认设置: {$default_uri},
	</p>
	<p>
		静态资源根链接<input type="text" name="base_uri" value="{$base_uri}" size="20">
	</p>
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="保存" />
	</p>
	</form>
</div>
html;
		echo $html;
	}
}

foreach (csu_changer::$change as $f)
	add_filter($f, array('csu_changer', 'filter'));
	
add_action('admin_menu', array('csu_changer', 'plugin_menu'));
?>