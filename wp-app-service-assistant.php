<?php
/*
Plugin Name: App Service Assistant
Plugin URI:  http://azassistant.azurewebsites.net/
Description: Settings and configuration on Azure
Version:     20170703
Author:      Mangesh Sangapu
Author URI:  http://msangapu.azurewebsites.net/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages

App Service Assistant for Azure is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
App Service Assistant for Azure is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with App Service Assitant for Azure. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/
//========================================================================================

require "class.assistant.php";

add_action( 'admin_menu', 'azasa_my_plugin_menu' );

function azasa_my_plugin_menu() {
	add_options_page( 'App Service Assistant', 'App Service Assistant', 'manage_options', 'my-unique-identifier', 'azasa_my_plugin_options' );
}

function azasa_print_define($arg){
    if(defined($arg))
      echo "$arg: " . $$arg . "/n";
}

function azasa_my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    
    if (stripos($_SERVER['OS'], 'windows') === false) {
        wp_die( __( 'This plugin is intended for Microsoft Azure with Windows OS.' ) );
    }
    
    //Initialize
	   $assistant = new azasa_assistant();    
       $plugins_url = plugins_url('getLog.php', __FILE__);
       $location = 'd:\home\LogFiles\\';

    //Start Buffering
        ob_start();
    
    //Run Functions
	   $assistant->getTheme();
	   $assistant->getDefines();
	   $assistant->getPlugins();
	   $assistant->getUserini();
	   $assistant->getPHPErrorsLog();
	   $assistant->getDebugLog();
	   $assistant->getAppSettings();
	
    //Output to screen
    	$output = ob_get_contents();
    	ob_end_clean();
    	echo $output;
    
    //Save the File
	    $f = fopen($location.$assistant->logFile, "w");
        fwrite($f, $output);
        fclose($f); 
}


function azasa_myTail($filename, $lines = 25, $buffer = 4096)
{
    $f = fopen($filename, "rb");
    fseek($f, -1, SEEK_END);
    if(fread($f, 1) != "\n") $lines -= 1;

    $output = '';
    $chunk = '';
    
    while(ftell($f) > 0 && $lines >= 0)
    {
        $seek = min(ftell($f), $buffer);
        fseek($f, -$seek, SEEK_CUR);
        $output = ($chunk = fread($f, $seek)).$output;
        fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
        $lines -= substr_count($chunk, "\n");
    }

    while($lines++ < 0)
    {
        $output = substr($output, strpos($output, "\n") + 1);
    }

    fclose($f); 
    return $output; 
}