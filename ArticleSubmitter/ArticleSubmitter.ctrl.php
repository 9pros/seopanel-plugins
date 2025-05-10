<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Raheela Muneer.
 *
 */
class ArticleSubmitter  extends SeoPluginsController{

    // plugin settings controller object
    var $settingsCtrler;

    // the plugin text database table
    var $textTable = "as_texts";

    // the plugin text category
    var $textCategory = "as";

    // plugin directory name
    var $directoryName = "ArticleSubmitter";

    /*
     * function to init plugin details before each plugin action
     */
    function initPlugin($data) {
        $this->setPluginTextsForRender($this->textCategory, $this->textTable);
        $settingsCtrler = $this->createHelper('assettings');
        $this->settingsCtrler = $settingsCtrler;
        $this->settingsCtrler->defineAllPluginSystemSettings();
        
        // include common classes
        include_once(SP_PLUGINPATH."/$this->directoryName/managespinner.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/managesubmitter.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/managereports.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/manageproject.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/managewebsite.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/managearticle.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/manageskip.ctrl.php");
    }
    
    /**
     * function to show projects
     * move to function showProjects in ManageProject.ctrl.php
     */
    function index($data) {
        if(isAdmin() || SME_ALLOW_USER_PROJECT_MGR) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->showProjects($data);
        }
    }

    /**
     * function to inactivate project
     * move to function __changeStatus in ManageProject.ctrl.php
     */
    function Inactivate($data) {
        if (!empty($data['project_id'])) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->__changeStatus($data['project_id'], 0,"inactivate");
            $ctrler->showProjects($data);
        }
    }
    
    /**
     * function to activate project
     * move to function __changeStatus in ManageProject.ctrl.php
     */
    function Activate($data) {
        if (!empty($data['project_id'])) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->__changeStatus($data['project_id'], 1,"activate");
            $ctrler->showProjects($data);
        }
    }

    /**
     *  function to edit project
     *  move to function editproject in ManageProject.ctrl.php to fetch details to be displyed on edit page
     */
    function edit($data) {
        $controller = $this->createHelper('ManageProject');
        $controller->editproject($data['project_id']);
    }

    /**
     * function to update project
     * move to function updateProjects in ManageProject.ctrl.php and update table
     */
    function updateProject($data){
        $controller = $this->createHelper('ManageProject');
        $controller->updateProjects($data);
    }

    /**
     * function to delete project
     *  move to function DeleteProject in ManageProject.ctrl.php and delete from db
     */
    function Delete($data) {
        $controller = $this->createHelper('ManageProject');
        $controller->DeleteProject($data['project_id']);
        $controller->showProjects($data);
    }


    /**
     * function to create new project
     *  move to function newprojectList in ManageProject.ctrl.php
     */
    function newproject($data) {
        $ctrler = $this->createHelper('ManageProject');
        $ctrler->newprojectList($data);
    }

    /**
     * function to add new project to db
     * move to function createprojectList in ManageProject.ctrl.php to add new project to db
     */
    function createProject($data){
        $ctrler = $this->createHelper('ManageProject');
        $ctrler->createprojectList($data);
    }

    /**
     * function to show articles in manage article
     *  move to function showArticles in ManageArticle.ctrl.php to fetch informations from db
     */
    function Article($data) {        
        if(isAdmin() || SME_ALLOW_USER_PROJECT_MGR) {
            $ctrler = $this->createHelper('ManageArticle');
            $ctrler->showArticles($data);
        }
    }

    /**
     * function to Inactivate article
     * move to function __changeStatus in ManageArticle.ctrl.php
     */
    function inactivateArticle($data) {
        if (!empty($data['article_id'])) {
            $ctrler = $this->createHelper('ManageArticle');
            $ctrler->__changeStatus($data['article_id'], 0,"inactivate");
            $ctrler->showArticles($data);
        }
    }

    /**
     * function to activate article
     * move to function __changeStatus in ManageArticle.ctrl.php
     */
    function activateArticle($data) {
        if (!empty($data['article_id'])) {
            $ctrler = $this->createHelper('ManageArticle');
            $ctrler->__changeStatus($data['article_id'], 1,"activate");
            $ctrler->showArticles($data);
        }
    }

    /**
     *  function to edit article
     *  move to function editproject in ManageArticle.ctrl.php to fetch details to be displyed on edit page
     */
    function editArticle($data) {
        $controller = $this->createHelper('ManageArticle');
        $controller->editArticle($data['article_id']);
    }
    
    function copyArticle($data) {
        $controller = $this->createHelper('ManageArticle');
        $controller->copyArticle($data['article_id']);
    }

    /**
     * function to update article
     * move to function updateArticle in ManageArticle.ctrl.php to update db
     */
    function updateArticle($data){
        $controller = $this->createHelper('ManageArticle');
        $controller->updateArticle($data);
    }

    /**
     * function to delete article
     * move to function DeleteArticle in ManageArticle.ctrl.php to delete from db
     */
    function deleteArticle($data){
        $controller = $this->createHelper('ManageArticle');
        $controller->deleteArticle($data['article_id']);
        $controller->Article($data);
    }

    /**
     * function to add new article
     * move to function newArticle in ManageArticle.ctrl.php
     */
    function newArticle($data){
        $ctrler = $this->createHelper('ManageArticle');
        $ctrler->newArticleList($data);
    }

    /**
     * function to create new article
     * move to function createArticleList in ManageArticle.ctrl.php
     */
    function createArticle($data){
        $ctrler = $this->createHelper('ManageArticle');
        $ctrler->createArticleList($data);
    }
    
    /**
     * function to search article using seach engines
     */
    function spinner($data) {
        $ctrler = $this->createHelper('ManageSpinner');
        $ctrler->showManageSpinner($data);
    }
    
    /**
     * function to search article using seach engines
     */
    function articleChecker($data) {
        $ctrler = $this->createHelper('ManageSpinner');
        $ctrler->articleChecker($data);
    }
    
    /**
     * function to save article created throug searching
     * created article will move to article manager
     */
    function saveArticle($data){
        $ctrler = $this->createHelper('ManageSpinner');
        $ctrler->saveArticle($data);
    }

    /**
     * function to submit article
     * move to function manageSubmitterDetails in ManageSubmitter.ctrl.php
     */
    function showSubmitDetails($data) {
        $ctrler = $this->createHelper('ManageSubmitter');
        $ctrler->manageSubmitterDetails($data);
    }

    /**
     * function to submit article
     * move to function submitArticle in ManageSubmitter.ctrl.php
     */
    function submitArticle($data){          
        $ctrler = $this->createHelper('ManageSubmitter');
        $ctrler->submitArticle($data);
    }

    /**
     * function to skip article
     * move to function skipwebsite in ManageSubmitter.ctrl.php
     */
    function skipWebsite($data){
        $ctrler = $this->createHelper('ManageSubmitter');
        $ctrler->skipWebsite($data);
    }

    /**
     * function to submit article
     * move to function submitarticlesite in ManageSubmitter.ctrl.php
     */
    function submitArticleSite($data){ 
        $ctrler = $this->createHelper('ManageSubmitter');
        $ctrler->submitArticleSite($data);
    }

    /**
     * function to show filter in submittion report 
     */
    function report($data){  
        $ctrler = $this->createHelper('ManageReports');
        $ctrler->viewReports($data);
    }    
    
    function removeSubmission($data){
        $ctrler = $this->createHelper('ManageReports');
        $ctrler->removeSubmission($data);
    }

//     /**
//      * function to show submittion report
//      */
//     function showreport($data){
//        $ctrler = $this->createHelper('ManageReports');
//         $ctrler->showReports($data);
//     }

     /**
     * function to show article box
     */
    function showArticleSelectBox($data) {
        $ctrler = $this->createHelper('ManageReports');
        $ctrler->showArticleSelectBox($data['project_id']);
    }

    /**
     * function to show skipped Submittions
     */
    function skippedSubmittions($data){
        $ctrler = $this->createHelper('ManageSkip');
        $ctrler->showSkipDetails($data);
    }

    /**
     * function to show unskip skipped Submittions
     */
    function unskip($data){
        $ctrler = $this->createHelper('ManageSkip');
        $ctrler->Unskip($data);
    }

    function checkstatus($data){
        $ctrler = $this->createHelper('ManageReports');
        $return_value = $ctrler->checkstatus($data[id]);
        if($return_value == "1"){
            $ctrler->updateStatus($data['id']);
        }
        $ctrler->showStatus($data['id']);       
    }
    
    /**
     * function to show websites in manage website
     *  move to function showWebsites in ManageWebsite.ctrl.php to fetch informations from db
     */
    function website($data) {
        if(isAdmin() || AS_ALLOW_USER_WEBSITE_MGR) {
            $ctrler = $this->createHelper('ManageWebsite');
            $ctrler->showWebsites($data);
        }
    }
    
    /**
     * function to activate website
     * move to function __changeStatus in ManageWebsite.ctrl.php
     */
    function ActivateWebsite($data) {
        if (!empty($data['website_id'])) {
            $ctrler = $this->createHelper('ManageWebsite');
            $ctrler->__changeStatus($data['website_id'], 1);
            $ctrler->showWebsites($data);
        }
    }
    
    /**
     * function to inactivate website
     * move to function __changeStatus in ManageWebsite.ctrl.php
     */
    function InactivateWebsite($data) {
        if (!empty($data['website_id'])) {
            $ctrler = $this->createHelper('ManageWebsite');
            $ctrler->__changeStatus($data['website_id'], 0);
            $ctrler->showWebsites($data);
        }
    }
    
    /**
     *  function to edit website
     *  move to function editwebsite in ManageWebsite.ctrl.php to fetch details to be displyed on edit page
     */
    function editwebsite($data) {
        $controller = $this->createHelper('ManageWebsite');
        $controller->editwebsite($data['website_id']);
    }
    
    /**
     * function to update  website
     * move to function updateWebsite in ManageWebsite.ctrl.php and update table
     */
    function updateWebsite($data){
        $controller = $this->createHelper('ManageWebsite');
        $controller->updateWebsite($data);
    }
    
    /**
     * function to create new website
     *  move to function newwebsiteList in ManageWebsite.ctrl.php
     */
    function newwebsite($data){
        $ctrler = $this->createHelper('ManageWebsite');
        $ctrler->newwebsiteList($data);
    }
    
    /**
     * function to add new website to db
     * move to function createwebsiteList in ManageWebsite.ctrl.php to add new project to db
     */
    function createwebsite($data){
        $ctrler = $this->createHelper('ManageWebsite');
        $ctrler->createwebsiteList($data);
    }
    
    /**
     * function to delete website
     *  move to function DeleteWebsite in ManageWebsite.ctrl.php and delete from db
     */
    function deletewebsite($data) {
        $controller = $this->createHelper('ManageWebsite');
        $controller->DeleteWebsite($data['website_id']);
        $controller->website($data);
    }
    
    /**
     * function show system settings
     */
    function settings($data) {
        $this->settingsCtrler->showPluginSettings();
    }
    
    /**
     * function to save plugin settings
     */
    function updateSettings($data) {
        $this->settingsCtrler->updatePluginSettings($data);
    }

    function importer($data){
    	$ctrler = $this->createHelper('ManageWebsite');
    	$ctrler->importWebsite($data);
    }
    
    function doimport($data){
    	$ctrler = $this->createHelper('ManageWebsite');
    	$ctrler->doimportWebsite($data);
    }

    function cronJob($data){
          $ctrler = $this->createHelper('ManageReports');
          $ctrler->startCheckStatusJOb($data);
    }

    function croncommand(){
		$this->pluginRender('croncommand');
    }

    function checkwebsitestatus($data){
        $controller = $this->createHelper('ManageWebsite');
        $return_value = $controller->checkwebsitestatus($data['website_id']);
        if($return_value == '1'){
               $return_value = $controller->updateStatus($data['website_id'],1);
        }else{
                $return_value = $controller->updateStatus($data['website_id'],0);
        }
        $this->website($data);
    }

    function WebstatusCronjob($data){
          $ctrler = $this->createHelper('ManageWebsite');
          $ctrler->startwebsiteStatusJOb($data);
    }
}
?>