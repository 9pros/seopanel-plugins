<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
class SMESettings extends socialmediaengine {
    
    // the variable to store the settings database table name
    var $settingsTable = "sme_settings";
	
	/*
	 * func to get all plugin settings
	 */ 
	function __getAllSMESettings() {		
		$sql = "select * from $this->settingsTable order by id";
		$settingsList = $this->db->select($sql);
		return $settingsList;
	}
	
	/*
	 * function to show  plugin settings
	 */
	function showPluginSettings() {
	    checkAdminLoggedIn();
	    $this->set('list', $this->__getAllSMESettings());	
		$this->pluginRender('showsettings');
	}
	
	/*
	 * func to update plugin settings
	 */
	function updatePluginSettings($postInfo) {
		checkAdminLoggedIn();
		$setList = $this->__getAllSMESettings();
		foreach($setList as $setInfo){
			
			$sql = "update $this->settingsTable set set_val='".addslashes($postInfo[$setInfo['set_name']])."' where set_name='{$setInfo['set_name']}'";
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
	 * function to set all plugin settings
	 */
	function defineAllPluginSystemSettings() {
		
		$settingsList = $this->__getAllSMESettings();		
		foreach($settingsList as $settingsInfo){
			if(!defined($settingsInfo['set_name'])){
				define($settingsInfo['set_name'], $settingsInfo['set_val']);
			}
		}				
	}
}
?>