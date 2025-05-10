<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class SocialBookmarker extends SeoPluginsController{
	
    var $settingsCtrler;        // plugin settings controller object
    var $helperCtrler;          // plugin helper controller object        
    
    /*
	 * function to init plugin details before each plugin action
	 */
	function initPlugin($data) {
		
	    $pluginDirName = empty($data['plgdirname']) ? false : $data['plgdirname'];		
		$this->setPluginTextsForRender('socialbookmarker', 'sb_texts');
		$this->settingsCtrler = $this->createHelper('sbsettings', $pluginDirName);
		$this->settingsCtrler->defineAllPluginSystemSettings();
        $this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));		
		$this->helperCtrler = $this->createHelper('sbhelper', $pluginDirName);
	}
	
	/*
	 * function to create helpers for main controlller
	 */ 
	function createHelper($helperName, $pluginDirName=false) {
		
	    if ($pluginDirName) {
            include_once(SP_PLUGINPATH."/$pluginDirName/$helperName.ctrl.php");
	    } else {
	        include_once(PLUGIN_PATH."/".strtolower($helperName).".ctrl.php");    
	    }		
		$helperObj = New $helperName();
		$helperObj->data = $this->data;
		$helperObj->pluginText = $this->pluginText;
		return $helperObj;
	}

	/*
	 * function to show the first pagewhile access plugin
	 */
	function index($data) {	
		if (isAdmin() || SB_ALLOW_USER_PRJ_MGR) {
			$projectCtrler = $this->createHelper('SBProject');
			$projectCtrler->showProjectsManager($data);
		} else {
		    $this->settingsCtrler->showPluginAboutUs();		
		}		
	}

	/*
	 * func to show create new project form
	 */ 
	function newproject($data){
								
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->newProject($data);
	}
	
	/*
	 * func to create new project
	 */ 
	function createProject($data){
						
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->createProject($data);
	}
	
	/*
	 * func to show edit project form
	 */ 
	function editproject($data){
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->editProject($data['project_id']);
	}
	
	/*
	 * func to update project
	 */ 
	function updateProject($data){
						
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->updateProject($data);
	}
	
	/*
	 * function to run project
	 */
	function runproject($data) {
						
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->runProject($data);            
	}
	
	/*
	 * fucntion to show project selct box according to selected website
	 */
	function showprjselbox($data) {
		$ctrler = $this->createHelper('SBProject');
		$ctrler->showProjectSelectBox($data);
	}
	
	/*
	 * func to delete project
	 */ 
	function deleteproject($data){
						
		$projectCtrler = $this->createHelper('SBProject');
		$projectCtrler->deleteProject($data['project_id']);
		$projectCtrler->showProjectsManager($data);
	}
	
	/*
	 * function to activate project
	 */
	function Activate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('SBProject');
			$ctrler->__changeStatus($data['project_id'], 1);
			$ctrler->showProjectsManager($data);
		}		
	}
	
	/*
	 * function to deactivate project
	 */
	function Inactivate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('SBProject');
			$ctrler->__changeStatus($data['project_id'], 0);
			$ctrler->showProjectsManager($data);
		}		
	}
	
	/*
	 * function to show social bookmarker manager 
	 */
	function showsbmanager($data) {	    
		$sbHelper = $this->createHelper('SBHelper');
	    $sbHelper->showSocialBookmarkerManager($data);
	}

	/*
	 * func to show create new social bookmarker form
	 */ 
	function newSB($data){								
		$sbHelper = $this->createHelper('SBHelper');
		$sbHelper->newSocialBookmarker($data);
	}
	
	/*
	 * func to create new social bookmarker
	 */ 
	function createSB($data){						
		$sbHelper = $this->createHelper('SBHelper');
		$sbHelper->createSocialBookmarker($data);
	}
	
	/*
	 * func to show edit project form
	 */ 
	function editSB($data){
		$sbHelper = $this->createHelper('SBHelper');
		$sbHelper->editSocialBookmarker($data['sb_id']);
	}
	
	/*
	 * func to update project
	 */ 
	function updateSB($data){						
		$sbHelper = $this->createHelper('SBHelper');
		$sbHelper->updateSocialBookmarker($data);
	}
	
	/*
	 * func to delete SocialBookmarker
	 */ 
	function deleteSB($data){						
		$sbHelper = $this->createHelper('SBHelper');
		$sbHelper->deleteSocialBookmarker($data['sb_id']);
		$sbHelper->showSocialBookmarkerManager($data);
	}
	
	/*
	 * function to activate SocialBookmarker
	 */
	function ActivateSB($data) {		
		if (!empty($data['sb_id'])) {
			$ctrler = $this->createHelper('SBHelper');
			$ctrler->__changeStatusSocialBookmarker($data['sb_id'], 1);
		    $ctrler->showSocialBookmarkerManager($data);
		}		
	}
	
	/*
	 * function to activate SocialBookmarker
	 */
	function InactivateSB($data) {		
		if (!empty($data['sb_id'])) {
			$ctrler = $this->createHelper('SBHelper');
			$ctrler->__changeStatusSocialBookmarker($data['sb_id'], 0);
		    $ctrler->showSocialBookmarkerManager($data);
		}		
	}

	/*
	 * function show system settings
	 */
	function settings($data) {
	    
		$this->settingsCtrler->showPluginSettings();
	}
	
	/*
	 * function to save plugin settings
	 */
	function updateSettings($data) {
		$this->settingsCtrler->updatePluginSettings($data);
	}
	
	/*
	 * func to show about us
	 */
	function aboutus() {
		
		$this->settingsCtrler->showPluginAboutUs();		
	}
}