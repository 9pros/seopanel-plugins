<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class BCSettings extends BlogCommentor{
	
	/*
	 * func to get all plugin settings
	 */ 
	function __getAllSettings() {
		
		$sql = "select * from bc_settings order by id";
		$settingsList = $this->db->select($sql);
		return $settingsList;
	}
	
	/*
	 * function to show  plugin settings
	 */
	function showPluginSettings() {
	    
	    $this->set('list', $this->__getAllSettings());	
		$this->pluginRender('showsettings');
	}
	
	/*
	 * func to update plugin settings
	 */
	function updatePluginSettings($postInfo) {
		
		$setList = $this->__getAllSettings();
		foreach($setList as $setInfo){
			
			$sql = "update bc_settings set set_val='".addslashes($postInfo[$setInfo['set_name']])."' where set_name='{$setInfo['set_name']}'";
			$this->db->query($sql);
		}
		
		$this->set('saved', 1);
		$this->showPluginSettings();
	}
	
	/*
	 * func to show about us plugin
	 */ 
	function showPluginAboutUs() {		

		$this->pluginRender('aboutus');
	}
	
	/*
	 * functo set all plugin settings
	 */
	function defineAllPluginSystemSettings() {
		
		$settingsList = $this->__getAllSettings();		
		foreach($settingsList as $settingsInfo){
			if(!defined($settingsInfo['set_name'])){
				define($settingsInfo['set_name'], $settingsInfo['set_val']);
			}
		}				
	}
}
?>