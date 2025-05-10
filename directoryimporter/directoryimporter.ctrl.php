<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class DirectoryImporter extends SeoPluginsController{
	
    var $dirCtrler; // directory controller object to help plugin for core functions
    
	/*
	 * function to init plugin details before each plugin action
	 */
	function initPlugin($data) {
		
	    $pluginDirName = empty($data['plgdirname']) ? false : $data['plgdirname'];
		$settingsCtrler = $this->createHelper('disettings', $pluginDirName);
		$settingsCtrler->defineAllPluginSystemSettings();
		$this->setPluginTextsForRender('diplugin', 'di_texts');
        $this->spTextDir = $this->getLanguageTexts('directory', $_SESSION['lang_code']);
        $this->set('spTextDir', $this->spTextDir);
        $this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		
		// create directory controller object
        include_once(SP_CTRLPATH."/directory.ctrl.php");
        $this->dirCtrler = New DirectoryController();
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
		$helperObj->dirCtrler = $this->dirCtrler;
		if ($helperName == 'DIHelper') $helperObj->loadDIHelper();
		return $helperObj;
	}

	/*
	 * function to show the first pagewhile access plugin
	 */
	function index($data) {	
		if (isAdmin() || DI_ALLOW_USER_DIR_MGR) {
			$diHelper = $this->createHelper('DIHelper');
			$diHelper->showDirectoryManager($data);
		} else {		
		    $settingsCtrler = $this->createHelper('disettings');
		    $settingsCtrler->showPluginAboutUs();			
		}
		
	}
	
	/*
	 * function to show import directories page
	 */
	function import($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->showImportDirectories($data);
	}
	
	/*
	 * function to import directories 
	 */
	function doimport($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->importDirectories($data);	    
	}
	
	/*
	 * function to check status of imported directories 
	 */
	function checkimportdir($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->checkImportedDirectoryStatus($data);	    
	}
	
	/*
	 * function to edit directoriy
	 */
	function editdir($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->showEditDirectory($data['dirid']);	    
	}
	
	/*
	 * function to edit directoriy
	 */
	function updatedir($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->updateDirectory($data);	    
	}	

	
	/*
	 * function to delete directoriy
	 */
	function deletedir($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->deleteDirectory($data['dirid']);
	    $diHelper->showDirectoryManager($data);  
	}	

	
	/*
	 * function to show check status of directories
	 */
	function showcheckdir($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->showCheckDirectoryStatus($data);
	}
	
	/*
	 * function to check status of directories
	 */
	function checkdirstatus($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->checkDirectoryStatus($data);
	}
	
	/*
	 * function for directory checker cron
	 */
	function crondirchecker($data) {
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->checkCronDirectoryStatus($data);
	}
	
	/*
	 * function for checking status of directory submission
	 */
	function dirsubmitcheckercron($data) {
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->checkCronDirectorySubmissionStatus($data);
	}

	/*
	 * function to show cron command
	 */ 
	function showcroncommand(){		
		$this->pluginRender('croncommand');
	}
	
	/*
	 * function to set type of a directory
	 */
	function settype($data) {
	    
	    $diHelper = $this->createHelper('DIHelper');
	    $diHelper->updateDirType($data);
	}

	/*
	 * function show system settings
	 */
	function settings($data) {
		
		$settingsCtrler = $this->createHelper('disettings');
		$settingsCtrler->showDIPluginSettings();
	}
	
	/*
	 * function to save plugin settings
	 */
	function updateSettings($data) {
		
		$settingsCtrler = $this->createHelper('disettings');
		$settingsCtrler->updatePluginSettings($data);
	}
	
	/*
	 * func to show about us
	 */
	function aboutus() {
		
		$settingsCtrler = $this->createHelper('disettings');
		$settingsCtrler->showPluginAboutUs();		
	}
}