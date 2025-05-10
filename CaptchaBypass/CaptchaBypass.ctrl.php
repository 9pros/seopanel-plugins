<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 *
 */

// include plugins controller if not included
include_once(SP_CTRLPATH.'/seoplugins.ctrl.php');

class CaptchaBypass extends SeoPluginsController {

    // plugin settings controller object
    var $settingsCtrler;

    // the plugin text database table
    var $textTable = "cb_texts";

    // the plugin text category
    var $textCategory = "CaptchaBypass";

    // plugin directory name
    var $directoryName = "CaptchaBypass";

    /*
     * function to init plugin details before each plugin action
     */
    function initPlugin($data) {

        $this->setPluginTextsForRender($this->textCategory, $this->textTable);
        $this->set('pluginText', $this->pluginText);
        $settingsCtrler = $this->createHelper('CB_Settings');
        $settingsCtrler->defineAllPluginSystemSettings();
        
        if (!defined('PLUGIN_PATH')) {
        	define('PLUGIN_PATH', $this->pluginPath);
        }
     
    }

    /*
     * function to show the first page while access plugin
     */
    function index($data) {
    	checkAdminLoggedIn();
        $cbMgrCtrler = $this->createHelper('CB_Manager');
        $cbMgrCtrler->showCBServiceList($data);
    }

    /*
     * function to show the first page while access plugin
     */
    function serviceManager($data) {
    	checkAdminLoggedIn();
        $cbMgrCtrler = $this->createHelper('CB_Manager');
        $cbMgrCtrler->showCBServiceList($data);
    }
    
    function solveCaptchaImage($data) {
        $cbMgrCtrler = $this->createHelper('CB_Manager');
        return $cbMgrCtrler->solveCaptchaImage($data['img_path']);
    }
	
	/*
	 * func to mew service
	 */ 
	function newService($data){
		checkAdminLoggedIn();						
		$ctrler = $this->createHelper('CB_Manager');
		$ctrler->newService($data['id']);
	}
	
	/*
	 * func to create service
	 */ 
	function createService($data){	
		checkAdminLoggedIn();						
		$ctrler = $this->createHelper('CB_Manager');
		$ctrler->createService($data);
	}
	
	/*
	 * func to edit service
	 */ 
	function editService($data){
		checkAdminLoggedIn();						
		$ctrler = $this->createHelper('CB_Manager');
		$ctrler->editService($data['id']);
	}
	
	/*
	 * func to update service
	 */ 
	function updateService($data){	
		checkAdminLoggedIn();						
		$ctrler = $this->createHelper('CB_Manager');
		$ctrler->updateService($data);
	}
	
	/*
	 * func to delete service
	 */ 
	function deleteService($data){
		checkAdminLoggedIn();						
		$ctrler = $this->createHelper('CB_Manager');
		$ctrler->deleteService($data['id']);
	}
	
	/*
	 * function to activate service
	 */
	function Activate($data) {
		checkAdminLoggedIn();
		
		if (!empty($data['id'])) {
			$ctrler = $this->createHelper('CB_Manager');
			$ctrler->__changeStatus($data['id'], 1);
			$ctrler->showCBServiceList();
		}		
	}
	
	/*
	 * function to deactivate service
	 */
	function Inactivate($data) {
		checkAdminLoggedIn();
		
		if (!empty($data['id'])) {
			$ctrler = $this->createHelper('CB_Manager');
			$ctrler->__changeStatus($data['id'], 0);
			$ctrler->showCBServiceList();
		}
	}
	
	/*
	 * function show system settings
	 */
	function settings($data) {
		checkAdminLoggedIn();
		$settingsCtrler = $this->createHelper('CB_Settings');
		$settingsCtrler->showPluginSettings();
	}
	
	/*
	 * function to save plugin settings
	 */
	function updateSettings($data) {
		checkAdminLoggedIn();
		$settingsCtrler = $this->createHelper('CB_Settings');
		$settingsCtrler->updatePluginSettings($data);
	}
  
}
?>