<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 */
class SocialMediaEngine  extends SeoPluginsController {

    // the plugin text database table
    var $textTable = "sme_texts";

    // the plugin text category
    var $textCategory = "sme";

    // plugin directory name
    var $directoryName = "socialmediaengine";
    
    function initPlugin($data) {

        $this->setPluginTextsForRender($this->textCategory, $this->textTable);
        $this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
        $this->set('pluginText', $this->pluginText);

        // create setting object and define all settings
        include_once(SP_PLUGINPATH."/$this->directoryName/smesettings.ctrl.php");
        $settingsCtrler = $this->createHelper('SMESettings');
        $settingsCtrler->defineAllPluginSystemSettings();
        $this->settingsCtrler = $settingsCtrler;

        // include common classes
        include_once(SP_PLUGINPATH."/$this->directoryName/socialmediaresources.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/managestatus.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/manageproject.ctrl.php");
        include_once(SP_PLUGINPATH."/$this->directoryName/statusreports.ctrl.php");
    }
    
    function createComponent($componentName) {
        $componentFileName = strtolower($componentName);
        include_once(PLUGIN_PATH."/components/". $componentFileName ."_component.php");
        $className = $componentName . "Component";
        $componentObj = New $className();
        $componentObj->data = $this->data;
        $componentObj->pluginText = $this->pluginText;
        return $componentObj;
    }

    function index($data) {
        if(isAdmin() || SME_ALLOW_USER_PROJECT_MGR) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->showProjectManager($data);
        } else {
            showErrorMsg($_SESSION['text']['label']['Access denied']); 
        }
    }
    
    function Activate($data) {
        if (!empty($data['project_id'])) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->__changeStatus($data['project_id'], 1,"activate");
            $ctrler->showProjectManager($data);
        }
    }
    
    function Inactivate($data) {
        if (!empty($data['project_id'])) {
            $ctrler = $this->createHelper('ManageProject');
            $ctrler->__changeStatus($data['project_id'], 0,"inactivate");
            $ctrler->showProjectManager($data);
        }
    }
    
    function Delete($data) {
        $controller = $this->createHelper('ManageProject');
        $controller->DeleteProject($data['project_id']);
        $controller->showProjectManager($data);
    }
    
    function edit($data) {
        $controller = $this->createHelper('ManageProject');
        $controller->editproject($data['project_id']);
    }
    
    function newproject($data) {
        $ctrler = $this->createHelper('ManageProject');
        $ctrler->newprojectList($data);
    }
    
    function updateProject($data){
        $controller = $this->createHelper('ManageProject');
        $controller->updateProjects($data);
    }
    
    function createProject($data){
        $ctrler = $this->createHelper('ManageProject');
        $ctrler->createprojectList($data);
    }
    
    /**
     * Post management functions
     */
    
    function manageStatus($data) {
        $ctrler = $this->createHelper('ManageStatus');
        $ctrler->showStatusManager($data);
    }
    
    function updateSocialMediaSources($data) {
        $ctrler = $this->createHelper('ManageStatus');
        echo $ctrler->updateSocialMediaSources($data['status_id']);
    }
    
    function Activate_Status($data) {
        if (!empty($data['id'])) {
            $ctrler = $this->createHelper('ManageStatus');
            $ctrler->__changeStatus($data['id'], 1);
            $ctrler->showStatusManager($data);
        }
    }
    
    function Inactivate_status($data) {
        if (!empty($data['id'])) {
            $ctrler = $this->createHelper('ManageStatus');
            $ctrler->__changeStatus($data['id'], 0);
            $ctrler->showStatusManager($data);
        }
    }
    
    function Delete_status($data) {
        $controller = $this->createHelper('ManageStatus');
        $controller->DeleteStatus($data['id']);
        $controller->showStatusManager();
    }
    
    function newstatus($data) {
        $ctrler = $this->createHelper('ManageStatus');
        $ctrler->newStatus($data);
    }
    
    function createStatus($data){
        $controller = $this->createHelper('ManageStatus');
        $controller->createStatus($data);
    }
    
    function edit_status($data) {
        $controller = $this->createHelper('ManageStatus');
        $controller->editStatus($data['id']);         
    }
    
    function updateStatus($data){
        $controller = $this->createHelper('ManageStatus');
        $controller->updateStatus($data);
    }
    
    function duplicateStatus($data) {
        $controller = $this->createHelper('ManageStatus');
        $controller->duplicateStatus($data['id']);
    }
    
    function imageUpload($data) {
        $controller = $this->createHelper('ManageStatus');
        $controller->updateImage($data);        
    }
    
    /**
     * settings management
     */
    
    function settings($data) {         
        $this->settingsCtrler->showPluginSettings();
    }
    
    function updateSettings($data) {
        $this->settingsCtrler->updatePluginSettings($data);
    }
    
    function aboutus() {
        $this->settingsCtrler->showPluginAboutUs();
    }
    
    function showcroncommand() {
        $this->pluginRender('croncommand');
    }
    
    /**
     * Social media resource manager
     */
    
    function listSocialMedia($data) {
        checkAdminLoggedIn();
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->showSocialMediaManager($data);
    }
    
    function InactivateSocialMedia($data){
        checkAdminLoggedIn();
        
        if (!empty($data['media_id'])) {
            $ctrler = $this->createHelper('SocialMediaResources');
            $ctrler->__changeStatus($data['media_id'], 0,"inactivate");
            $ctrler->showSocialMediaManager($data);
        }
    }
    
    function ActivateSocialMedia($data){
        checkAdminLoggedIn();
        
        if (!empty($data['media_id'])) {
            $ctrler = $this->createHelper('SocialMediaResources');
            $ctrler->__changeStatus($data['media_id'], 1,"activate");
            $ctrler->showSocialMediaManager($data);
        }
    }
    
    function editSocialMedia($data){
        checkAdminLoggedIn();
        $controller = $this->createHelper('SocialMediaResources');
        $controller->editmedia($data['media_id']);
    }
    
    function updatemedia($data){
        checkAdminLoggedIn();
        $controller = $this->createHelper('SocialMediaResources');
        $controller->updateSocialMedia($data);
    }
    
    /**
     * social media connection manager
     */
    
    function connectionManager($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->showConnectionManager($data);
    }
    
    function newConnection($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->newConnection($data);
    }
    
    function createConnection($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->createConnection($data);
    }
    
    function editConnection($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->editConnection($data['id']);
    }
    
    function updateConnection($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->updateConnection($data);
    }
    
    function deleteConnection($data) {
        $resourceCtrler = $this->createHelper('SocialMediaResources');
        $resourceCtrler->deleteConnection($data['id']);
    }
    
    function doSMConnection($data){
        $controller = $this->createHelper('SocialMediaResources');
        $controller->doSocialMediaConnection($data['id']);
    }
    
    function callbackSM($data){
        $controller = $this->createHelper('SocialMediaResources');
        $controller->callbackSMConnection($data);
    }
    
    function removeSMConnection($data){
        $controller = $this->createHelper('SocialMediaResources');
        $controller->removeSocialMediaConnection($data['id']);
    }
    
    function crawlSubmissionPages($data){
        $controller = $this->createHelper('SocialMediaResources');
        $controller->crawlSubmissionPages($data['id']);
        $controller->editConnection($data['id']);
    }
    
    function postStatus($data){
        $ctrler = $this->createHelper('managestatus');
        $ctrler->postMediaStatus(intval($data['id']));
    }
    
    function viewreport($data) {
        $ctrler = $this->createHelper('StatusReports');
        $ctrler->viewReports($data);
    }
    
    function showreport($data) {
        $ctrler = $this->createHelper('StatusReports');
        $ctrler->showStatusReport($data);
    }
    
    function cronjob($data=[]) {
        $controller = $this->createHelper('ManageStatus');
        $controller->startCronJob($data);
    }
    
    function showStatusSelectBox($data) {
        $controller = $this->createHelper('ManageStatus');
        $controller->showStatusSelectBox($data['project_id']);
    }
}
?>