<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class LinkDiagnosis extends SeoPluginsController{

	/*
	 * function to init plugin details before each plugin action
	 */
	function initPlugin($data) {		
		$settingsCtrler = $this->createHelper('ldsettings');
		$settingsCtrler->defineAllPluginSystemSettings();
		$this->setPluginTextsForRender('ldplugin', 'ld_texts');
	}

	function index($data) {	

		if (isAdmin() || LD_ALLOW_USER_REPORTS) {
			$projectCtrler = $this->createHelper('Project');
			$projectCtrler->showProjectsManager($data);
		} else {		
			$reportCtrler = $this->createHelper('Report');
			$reportCtrler->viewReports($data);			
		}
		
	}

	/*
	 * func to show create new project form
	 */ 
	function newproject($data){								
		$projectCtrler = $this->createHelper('Project');
		$projectCtrler->newProject($data);
	}
	
	/*
	 * func to create new project
	 */ 
	function createProject($data){						
		$projectCtrler = $this->createHelper('Project');
		$projectCtrler->createProject($data);
	}
	
	/*
	 * func to show edit project form
	 */ 
	function editproject($data){						
		$projectCtrler = $this->createHelper('Project');
		$projectCtrler->editProject($data['project_id']);
	}
	
	/*
	 * func to update project
	 */ 
	function updateProject($data){						
		$projectCtrler = $this->createHelper('Project');
		$projectCtrler->updateProject($data);
	}
	
	/*
	 * func to delete project
	 */ 
	function deleteproject($data){						
		$projectCtrler = $this->createHelper('Project');
		$projectCtrler->deleteProject($data['project_id']);
	}
	
	/*
	 * function to activate project
	 */
	function Activate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('Project');
			$ctrler->__changeStatus($data['project_id'], 1);
			$ctrler->showProjectsManager();
		}		
	}
	
	/*
	 * function to deactivate project
	 */
	function Inactivate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('Project');
			$ctrler->__changeStatus($data['project_id'], 0);
			$ctrler->showProjectsManager();
		}		
	}

	/*
	 * func to show reports manager
	 */
	function reports($data) {		
		$projectCtrler = $this->createHelper('Report');
		$projectCtrler->showReportsManager($data);
	}

	/*
	 * func to import backlinks to reports
	 */
	function importBacklinks($data) {		
		$projectCtrler = $this->createHelper('Report');
		$projectCtrler->showImportBacklinks($data);
	}

	/*
	 * func to process import backlinks form
	 */
	function doImportBacklinks($data) {		
		$projectCtrler = $this->createHelper('Report');
		$projectCtrler->doImportBacklinks($data);
	}
	
	/*
	 * func to show create new report form
	 */ 
	function newreport($data){								
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->newReport($data);
	}
	
	/*
	 * func to create new report
	 */ 
	function createReport($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->createReport($data);
	}
	
	/*
	 * func to show edit report form
	 */ 
	function editreport($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->editReport($data['report_id']);
	}
	
	/*
	 * func to update report
	 */ 
	function updateReport($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->updateReport($data);
	}
	
	/*
	 * func to delete report
	 */ 
	function deletereport($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->deleteReport($data['report_id']);
	}
	
	/*
	 * func to delete report
	 */ 
	function rerunreport($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->reRunReport($data['report_id']);
	}
	
	/*
	 * func to verify the existing backlinks
	 */ 
	function verifybacklinks($data){						
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->verifyBacklinks($data['report_id']);
	}
	
	/*
	 * function run report
	 */
	function runreport($data) {		
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->runReport($data['report_id']);
	}
	
	/*
	 * function generate report
	 */
	function generatereport($data) {
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->generateReport($data['report_id']);
	}
	
	/*
	 * function to show run info
	 */
	function runinfo($data) {		
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->showRunInfo($data['report_id']);
	}
	
	/*
	 * function to views report section
	 */
	function viewreport($data) {		
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->viewReports($data);
	}
	
	/*
	 * function to show reports
	 */
	function showreport($data) {		
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->showReports($data);
	}
	
	/*
	 * function to show report select box
	 */
	function reportselbox($data) {
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->showReportSelectBox($data['project_id'], $data['all_reports']);
	}
	
	/*
	 * function to show anchor select box
	 */
	function anchorselbox($data) {		
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->showAnchorSelectBox($data['report_id']);
	}
	
	/*
	 * function show system settings
	 */
	function settings($data) {
		checkAdminLoggedIn();
		$settingsCtrler = $this->createHelper('ldsettings');
		$settingsCtrler->showLDPluginSettings();
	}
	
	/*
	 * function to save plugin settings
	 */
	function updateSettings($data) {
		checkAdminLoggedIn();
		$settingsCtrler = $this->createHelper('ldsettings');
		$settingsCtrler->updatePluginSettings($data);
	}
	
	/*
	 * func to show about us
	 */
	function aboutus() {		
		$settingsCtrler = $this->createHelper('ldsettings');
		$settingsCtrler->showPluginAboutUs();		
	}

	/**
	 * function for start sending status to social media networks like fb, twitter, linkedin using cron
	 */
	function cronjob() {
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->startCronJob($data);
	}

    /**
     * function to show cron command
     */
    function showcroncommand() {
    	checkAdminLoggedIn();
    	$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
        $this->pluginRender('croncommand');
    }
    
    /**
     * function to check report link
     */
    function checkReportLink($data) {
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->checkReportLink($data['id']);
    }
    
    /**
     * function to show link informations
     */
    function viewLinkInfo($data) {
		$reportCtrler = $this->createHelper('Report');
		$reportCtrler->showLinkInfo($data['id']);
    }

    function userTypeSettings($data){
    	checkAdminLoggedIn();
    	$userTypeObj = parent::createHelper("LDUserType");
    	$userTypeObj->showPluginUserTypeSettings($data);
    }

    /*
     * func to show reports manager
     */
    function searchEngineManager($data) {
    	checkAdminLoggedIn();
    	$ctrler = $this->createHelper('LDSearchEngine');
    	$ctrler->showSearchEngineManager($data);
    }

    /*
     * function to activate search engine
     */
    function activateSearchEngine($data) {
    
    	if (!empty($data['se_id'])) {
    		$ctrler = $this->createHelper('LDSearchEngine');
    		$ctrler->__changeStatus($data['se_id'], 1);
    		$ctrler->showSearchEngineManager();
    	}
    	
    }
    
    /*
     * function to deactivate search engine
     */
    function inactivateSearchEngine($data) {
    
    	if (!empty($data['se_id'])) {
    		$ctrler = $this->createHelper('LDSearchEngine');
    		$ctrler->__changeStatus($data['se_id'], 0);
    		$ctrler->showSearchEngineManager();
    	}
    	
    }
    
}