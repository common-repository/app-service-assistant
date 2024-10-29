<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class azasa_assistant{
    
	public $logFile = 'AppAssistant.html';
	public $logPath = 'd:\home\LogFiles\\';
	
	public function getAppSettings(){
		echo '<div class="wrap"><h3>App and Website Settings</h3>';
	
		$settings = array();
		foreach ($_SERVER as $key => $value) {
		    if ((strpos($key, "APPSETTING_") !== 0) && (strpos($key, "WEBSITE_") !== 0)) {
		        continue;
		    }

			$settings[$key] = $value;  
		}
	
		ksort($settings);
		foreach ($settings as $key => $value){
			//if (defined($value))
				echo $key.': ' .$value.'<br>';
		}

		echo "</div>";
	}
	
	public function getDefines(){
		echo '<div class="wrap"><h3>WP Defines</h3>';
	
		$configArray = array('WP_DEBUG','WP_DEBUG_LOG','WP_DEBUG_DISPLAY','DB_HOST','DB_NAME','WPCACHEHOME','WP_HOME','WP_SITEURL','WP_CONTENT_URL','DOMAIN_CURRENT_SITE');
		foreach ($configArray as $key => $value){
			if (defined($value))
				echo $value.': ' .constant($value).'<br>';
		}
		echo '</div>';
	}

	public function getPlugins(){
		
		$plugindir = get_plugins();
		$active = array();
		$inactive = array();
		
		foreach($plugindir as $key => $value){
			is_plugin_active($key)?$active[]=$key:$inactive[]=$key;
		}
		
		echo '<div class="wrap"><h3>List of plugins in WP</h3><h4>Active:</h4><ul>';
		
		foreach($active as $key => $value){
			echo "<li>$value</li>";
		}
		
		echo "</ul><h4>Inactive:</h4><ul>";
		foreach($inactive as $key => $value){
			echo "<li>$value</li>";
		}

		echo '</ul></div>';
	}
		
	public function getTheme(){
	    echo '<div class="wrap"><h3>Current WP Theme</h3><pre>';
		echo "Theme Name: ". wp_get_theme().'<br>Theme Path: '.get_template_directory();
		echo '</pre></div>';
	}

	public function getUserini(){
		echo '<div class="wrap"><h3>.user.ini Contents</h3><pre>';
		
		$file = get_home_path().'.user.ini';
		
		if (file_exists($file))
			readfile($file);
		else
			echo "No .user.ini found at path: ".$file;
		echo '</pre></div>';
	}
		
	public function getPHPErrorsLog(){
		echo '<div class="wrap"><h3>php_errors.log Contents</h3><pre>';
		
		$file = $this->logPath . 'php_errors.log';
		
		if (file_exists($file)){
			echo azasa_mytail($file);
		}
		else{
			echo "No php_errors.log found at path: ".$file;
		}
			
		echo '</pre></div>';
	}

	public function getDebugLog(){
		
		$file = WP_CONTENT_DIR."/debug.log";
		
		echo '<div class="wrap"><h3>debug.log Contents</h3><pre>';

		if (file_exists($file)){
			echo azasa_mytail($file);
		}
		else{
			echo "No debug.log found at path: ".$file;
		}
		
		echo '</pre></div>';
	}
	
}