<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class BlogCommentor extends SeoPluginsController{
	
    var $settingsCtrler;        // plugin settings controller object
    var $helperCtrler;          // plugin helper controller object        
    
    /*
	 * function to init plugin details before each plugin action
	 */
	function initPlugin($data) {
		
	    $pluginDirName = empty($data['plgdirname']) ? false : $data['plgdirname'];		
		$this->setPluginTextsForRender('blogcommentor', 'bc_texts');
		$this->settingsCtrler = $this->createHelper('bcsettings', $pluginDirName);
		$this->settingsCtrler->defineAllPluginSystemSettings();
        $this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		
		$this->helperCtrler = $this->createHelper('bchelper', $pluginDirName);		
        include_once(SP_CTRLPATH."/keyword.ctrl.php");
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
		if (isAdmin() || BC_ALLOW_USER_PRJ_MGR) {
			$projectCtrler = $this->createHelper('BCProject');
			$projectCtrler->showProjectsManager($data);
		}		
	}

	/*
	 * func to show create new project form
	 */ 
	function newproject($data){
								
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->newProject($data);
	}

	/*
	 * func to show copy old project to new project form
	 */ 
	function copyproject($data){				
		$projectCtrler = $this->createHelper('BCProject');
		$projectId = intval($data['project_id']);
	    if(!empty($projectId)){
			$listInfo = $projectCtrler->__getProjectInfo($projectId);
		}
		$projectCtrler->newProject($listInfo);
	}
	
	/*
	 * func to create new project
	 */ 
	function createProject($data){
						
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->createProject($data);
	}
	
	/*
	 * func to show edit project form
	 */ 
	function editproject($data){
						
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->editProject($data['project_id']);
	}
	
	/*
	 * func to update project
	 */ 
	function updateProject($data){
						
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->updateProject($data);
	}
	
	/*
	 * function to run project
	 */
	function runproject($data) {
						
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->runProject($data['project_id'], $data['showhead'], 10, $data['import_count']);
            
	}	
	
	/*
	 * func to delete project
	 */ 
	function deleteproject($data){
						
		$projectCtrler = $this->createHelper('BCProject');
		$projectCtrler->deleteProject($data['project_id']);
		$projectCtrler->showProjectsManager($data);
	}
	
	/*
	 * function to activate project
	 */
	function Activate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('BCProject');
			$ctrler->__changeStatus($data['project_id'], 1);
			$ctrler->showProjectsManager($data);
		}		
	}
	
	/*
	 * function to deactivate project
	 */
	function Inactivate($data) {
		
		if (!empty($data['project_id'])) {
			$ctrler = $this->createHelper('BCProject');
			$ctrler->__changeStatus($data['project_id'], 0);
			$ctrler->showProjectsManager($data);
		}		
	}
	
	/*
	 * fundtion to show reports of blog comments
	 */
	function viewreports($data) {
	    $this->helperCtrler->viewReports($data);
	}
	
	/*
	 * fucntion to show project selct box according to selected website
	 */
	function showprjselbox($data) {
		$ctrler = $this->createHelper('BCProject');
		$ctrler->showProjectSelectBox($data);
	}
	
	/*
	 * function to activate blog link
	 */
	function linkactivate($data) {
		
		if (!empty($data['blog_id'])) {
			$this->helperCtrler->__updateField(1, 'status', " and id=".$data['blog_id']);
			$this->helperCtrler->viewReports($data);
		}		
	}
	
	/*
	 * function to check link status
	 */
	function checkStatus($data) {
	    
	    if (!empty($data['blog_id'])) {
	        $spText = $_SESSION['text'];
	        $blogStatus = $this->helperCtrler->checkBlogStatus($data['blog_id']);
	        print $blogStatus ? $spText['common']['Active'] : $spText['common']['Inactive'];
		}
	}
	
	/*
	 * function to check link submission status
	 */
	function checkSubmission($data) {
	    
	    if (!empty($data['blog_id'])) {
	        $spText = $_SESSION['text'];
			$status = $this->helperCtrler->__checkSubmissionStatus($data['blog_id']);
			print $status ? $spText['common']['Yes'] : $spText['common']['No'];
		}
	}
	
	/*
	 * function to show import links to project form
	 */
	function importLinks($data) {
		$ctrler = $this->createHelper('BCProject');
		$ctrler->showImportProjectLinks($data);
	}
	
	/*
	 * function to import links to project
	 */
	function doImportProjectLinks($data) {
		$ctrler = $this->createHelper('BCProject');
		$ctrler->importProjectLinks($data);
	}
	
	/*
	 * function to submit comment to blogs
	 */
	function submitcomment($data) {
	    
	    if (!empty($data['blog_id'])) {
    	    $status = $this->helperCtrler->__submitBlogComment($data['blog_id']);    	    
	        $spText = $_SESSION['text'];
    	    print $status ? $spText['common']['Yes'] : $spText['common']['No'];
    	    if ($status) {
        	    $blogId = $data['blog_id'];
        	    print "<script>scriptDoLoad('".PLUGIN_SCRIPT_URL."', 'approved_$blogId', '&blog_id=$blogId&action=checkstatus');</script>";
    	    }
	    }
	}
	
	
	/*
	 * function to submit comment using cron
	 */
	function cronsubmitcomment($data) {
	    
	    $this->helperCtrler->cronTab = true;
        $this->helperCtrler->submitCommentsByCron($data);
	}
	
	/*
	 * function to inactivate blog link
	 */
	function linkinactivate($data) {
		
		if (!empty($data['blog_id'])) {
			$this->helperCtrler->__updateField(0, 'status', " and id=".$data['blog_id']);
			$this->helperCtrler->viewReports($data);
		}		
	}
	
	/*
	 * function to delete blog link
	 */
	function deletebloglink($data) {
		
		if (!empty($data['blog_id'])) {
			$this->helperCtrler->__deleteBlogLink($data['blog_id']);
			$this->helperCtrler->viewReports($data);
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

	/*
	 * function to show cron command
	 */ 
	function showcroncommand(){		
		$this->pluginRender('croncommand');
	}
}