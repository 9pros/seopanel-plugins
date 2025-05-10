<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class DISettings extends DirectoryImporter{
	
	/*
	 * func to get all plugin settings
	 */ 
	function __getAllDISettings() {
		
		$sql = "select * from di_settings order by id";
		$settingsList = $this->db->select($sql);
		return $settingsList;
	}
	
	/*
	 * function to show di plugin settings
	 */
	function showDIPluginSettings() {
		
		$this->set('list', $this->__getAllDISettings());
		$cronchecklist = array(
		    'notchecked' => $this->pluginText['Not Checked'],
		    'inactive' => $_SESSION['text']['common']['Inactive'],
		    'active' => $_SESSION['text']['common']['Active'],
		    'all' => $_SESSION['text']['common']['All'],
		);
		$this->set('cronchecklist', $cronchecklist);		
		$this->pluginRender('showdisettings');
	}
	
	/*
	 * func to update plugin settings
	 */
	function updatePluginSettings($postInfo) {
		
		$setList = $this->__getAllDISettings();
		foreach($setList as $setInfo){
			
			$sql = "update di_settings set set_val='".addslashes($postInfo[$setInfo['set_name']])."' where set_name='{$setInfo['set_name']}'";
			$this->db->query($sql);
		}
		
		$this->set('saved', 1);
		$this->showDIPluginSettings();
	}
	
	/*
	 * func to show about us plugin
	 */ 
	function showPluginAboutUs() {		

		$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		$this->pluginRender('diaboutus');
	}
	
	/*
	 * functo set all plugin settings
	 */
	function defineAllPluginSystemSettings() {
		
		$settingsList = $this->__getAllDISettings();		
		foreach($settingsList as $settingsInfo){
			if(!defined($settingsInfo['set_name'])){
				define($settingsInfo['set_name'], $settingsInfo['set_val']);
			}
		}				
	}
}
?>