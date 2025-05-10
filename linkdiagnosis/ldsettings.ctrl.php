<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class LDSettings extends LinkDiagnosis{
	
	/*
	 * func to get all plugin settings
	 */ 
	function __getAllLDSettings($displayCheck=false) {
		
	    $where = $displayCheck ? "where display=1" : "";
		$sql = "select * from ld_settings $where order by id";
		$settingsList = $this->db->select($sql);
		return $settingsList;
	}
	
	/*
	 * function to show ld plugin settings
	 */
	function showLDPluginSettings() {
		
		$this->set('list', $this->__getAllLDSettings(true));

		include_once(PLUGIN_PATH."/crawler.class.php");
		$ldCrawler = New LDCrawler();
		$this->set('seList', $ldCrawler->seList);
		
		$this->pluginRender('showldsettings');
	}
	
	/*
	 * func to update plugin settings
	 */
	function updatePluginSettings($postInfo) {
		
		$setList = $this->__getAllLDSettings(true);
		foreach($setList as $setInfo){
			
			switch($setInfo['set_name']){
				
				case "LD_MAX_LINKS_REPORT":
					$postInfo[$setInfo['set_name']] = intval($postInfo[$setInfo['set_name']]);
					break;
			}
			
			$sql = "update ld_settings set set_val='".addslashes($postInfo[$setInfo['set_name']])."' where set_name='".addslashes($setInfo['set_name'])."'";
			$this->db->query($sql);
		}
		
		$this->set('saved', 1);
		$this->showLDPluginSettings();
	}
	
	/*
	 * func to show about us plugin
	 */ 
	function showPluginAboutUs() {		

		$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		$this->pluginRender('ldaboutus');
	}
	
	/*
	 * functo set all plugin settings
	 */
	function defineAllPluginSystemSettings() {
		
		$settingsList = $this->__getAllLDSettings();		
		foreach($settingsList as $settingsInfo){
			if(!defined($settingsInfo['set_name'])){
				define($settingsInfo['set_name'], $settingsInfo['set_val']);
			}
		}				
	}
}
?>