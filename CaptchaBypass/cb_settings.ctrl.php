<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class CB_Settings extends CaptchaBypass {
	
	var $tableName = "cb_settings";
	
	/*
	 * func to get all plugin settings
	 */ 
	function __getAllPluginSettings($displayCheck=false) {
		
	    $where = $displayCheck ? "where display=1" : "";
		$sql = "select * from $this->tableName $where order by id";
		$settingsList = $this->db->select($sql);
		return $settingsList;
	}
	
	/*
	 * function to show plugin settings
	 */
	function showPluginSettings() {
		$this->set('list', $this->__getAllPluginSettings(true));
		$this->set("spTextPanel", $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		$this->set("spSettingsText", $this->getLanguageTexts('settings', $_SESSION['lang_code']));
		$this->pluginRender('show_plugin_settings');
	}
	
	/*
	 * func to update plugin settings
	 */
	function updatePluginSettings($postInfo) {
		
		$setList = $this->__getAllPluginSettings(true);
		foreach($setList as $setInfo) {
			
			switch($setInfo['set_name']){
				
				default:
					$postInfo[$setInfo['set_name']] = intval($postInfo[$setInfo['set_name']]);
					break;
			}
			
			$sql = "update $this->tableName set set_val='".addslashes($postInfo[$setInfo['set_name']])."' where set_name='".addslashes($setInfo['set_name'])."'";
			$this->db->query($sql);
		}
		
		$this->set('saved', 1);
		$this->showPluginSettings();
	}
	
	/*
	 * functo set all plugin settings
	 */
	function defineAllPluginSystemSettings() {
		
		$settingsList = $this->__getAllPluginSettings();		
		foreach($settingsList as $settingsInfo){
			if(!defined($settingsInfo['set_name'])){
				define($settingsInfo['set_name'], $settingsInfo['set_val']);
			}
		}				
	}
}
?>