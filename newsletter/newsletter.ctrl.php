<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Newsletter extends SeoPluginsController{
	
    var $settingsCtrler;                            // plugin settings controller object
    var $helperCtrler;                              // plugin helper controller object
    var $textTable = "nl_texts";                    // the plugin text database table
    var $textCategory = "newsletter";               // the plugin text category        
    
    /*
	 * function to init plugin details before each plugin action
	 */
	function initPlugin($data) {
		
	    $pluginDirName = empty($data['plgdirname']) ? false : $data['plgdirname'];		
		$this->setPluginTextsForRender($this->textCategory, $this->textTable);
		$this->settingsCtrler = $this->createHelper('PluginSettings', $pluginDirName);
		$this->settingsCtrler->defineAllPluginSystemSettings();
        $this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));		
		$this->helperCtrler = $this->createHelper('PluginHelper', $pluginDirName);
	}
	
	/*
	 * function to create helpers for main controlller
	 */ 
	function createHelper($helperName, $pluginDirName=false) {
		
	    $helperFileName = strtolower($helperName);
	    if ($pluginDirName) {
            include_once(SP_PLUGINPATH."/$pluginDirName/$helperFileName.ctrl.php");
	    } else {
	        include_once(PLUGIN_PATH."/$helperFileName.ctrl.php");    
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
		if (isAdmin() || NL_ALLOW_USER_CAMP_MGR) {
			$controller = $this->createHelper('NewsletterCampaign');
			$controller->showCampaignManager($data);
		} else {
		    $this->settingsCtrler->showPluginAboutUs();		
		}		
	}

	/*
	 * func to show create new campaign form
	 */ 
	function newcampaign($data){
	    $controller = $this->createHelper('NewsletterCampaign');
	    $controller->newCampaign($data);
	}
	
	/*
	 * func to create new campaign
	 */ 
	function createCampaign($data){
						
		$controller = $this->createHelper('NewsletterCampaign');
		$controller->createCampaign($data);
	}
	
	/*
	 * func to show edit campaign form
	 */ 
	function editcampaign($data){
		$controller = $this->createHelper('NewsletterCampaign');
		$controller->editCampaign($data['campaign_id']);
	}
	
	/*
	 * func to update campaign
	 */ 
	function updateCampaign($data){
						
		$controller = $this->createHelper('NewsletterCampaign');
		$controller->updateCampaign($data);
	}
	
	/*
	 * fucntion to show campaign selct box according to selected website
	 */
	function showprjselbox($data) {
		$ctrler = $this->createHelper('NewsletterCampaign');
		$ctrler->showCampaignSelectBox($data);
	}
	
	/*
	 * func to delete campaign
	 */ 
	function deletecampaign($data){
						
		$controller = $this->createHelper('NewsletterCampaign');
		$controller->deleteCampaign($data['campaign_id']);
		$controller->showCampaignManager($data);
	}
	
	/*
	 * function to activate campaign
	 */
	function Activate($data) {
		
		if (!empty($data['campaign_id'])) {
			$ctrler = $this->createHelper('NewsletterCampaign');
			$ctrler->__changeStatus($data['campaign_id'], 1);
			$ctrler->showCampaignManager($data);
		}		
	}
	
	/*
	 * function to deactivate campaign
	 */
	function Inactivate($data) {
		
		if (!empty($data['campaign_id'])) {
			$ctrler = $this->createHelper('NewsletterCampaign');
			$ctrler->__changeStatus($data['campaign_id'], 0);
			$ctrler->showCampaignManager($data);
		}		
	}
	
	/*
	 * function to show newsletter manager 
	 */
	function newslettermanager($data) {	    
		$nlHelper = $this->createHelper('NewsletterEntry');
	    $nlHelper->showNewsletterEntryManager($data);
	}

	/*
	 * func to show create new newsletter form
	 */ 
	function newNL($data){								
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->newNewsletterEntry($data);
	}
	
	/*
	 * func to create new newsletter
	 */ 
	function createNL($data){						
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->createNewsletter($data);
	}
	
	/*
	 * func to show newsletter select box
	 */ 
	function shownlsel($data){
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->showNewsletterSelectBox($data['campaign_id']);
	}
	
	/*
	 * func to show edit newsletter form
	 */ 
	function editNL($data){
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->editNewsletterEntry($data['newsletter_id']);
	}
	
	/*
	 * func to update newsletter
	 */ 
	function updateNL($data){						
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->updateNewsletter($data);
	}	
	/*
	 * function toggle editor
	 */
	function toggleEditor($data) {
	    $nlHelper = $this->createHelper('NewsletterEntry');
		if(!isset($data['html_mail'])) $data['html_mail'] = 0;
		if(!isset($data['open_tracking'])) $data['open_tracking'] = 0;
		if(!isset($data['unsubscribe_option'])) $data['unsubscribe_option'] = 0;
		
		if (empty($data['html_mail'])) $data['mail_content'] = strip_tags($data['mail_content']); 
		
	    if ($data['sec'] == 'update') {
		    $nlHelper->editNewsletterEntry($data['id'], $data);
	    } else {
		    $nlHelper->newNewsletterEntry($data);
	    }
	}
	
	/*
	 * func to delete Newsletter
	 */ 
	function deleteNL($data){						
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->deleteNewsletter($data['newsletter_id']);
		$nlHelper->showNewsletterEntryManager($data);
	}
	
	/*
	 * function to activate EmailNewsletter
	 */
	function ActivateNL($data) {		
		if (!empty($data['newsletter_id'])) {
			$ctrler = $this->createHelper('NewsletterEntry');
			$ctrler->__changeStatusNewsletter($data['newsletter_id'], 1);
		    $ctrler->showNewsletterEntryManager($data);
		}		
	}
	
	/*
	 * function to activate EmailNewsletter
	 */
	function InactivateNL($data) {		
		if (!empty($data['newsletter_id'])) {
			$ctrler = $this->createHelper('NewsletterEntry');
			$ctrler->__changeStatusNewsletter($data['newsletter_id'], 0);
		    $ctrler->showNewsletterEntryManager($data);
		}		
	}
	
	/*
	 * function to show email list manager 
	 */
	function managerEL($data) {	    
		$elhelper = $this->createHelper('EmailList');
	    $elhelper->showEmailListManager($data);
	}

	/*
	 * func to show create new email list
	 */ 
	function newEL($data){								
		$elhelper = $this->createHelper('EmailList');
		$elhelper->newEmailList($data);
	}
	
	/*
	 * func to create new email list
	 */ 
	function createEL($data){						
		$elhelper = $this->createHelper('EmailList');
		$elhelper->createEmailList($data);
	}
	
	/*
	 * func to show edit email list form
	 */ 
	function editEL($data){
		$elhelper = $this->createHelper('EmailList');
		$elhelper->editEmailList($data['email_list_id']);
	}
	
	/*
	 * func to update email list
	 */ 
	function updateEL($data){						
		$elhelper = $this->createHelper('EmailList');
		$elhelper->updateEmailList($data);
	}
	
	/*
	 * func to delete Email list
	 */ 
	function deleteEL($data){						
		$elhelper = $this->createHelper('EmailList');
		$elhelper->deleteEmailList($data['email_list_id']);
		$elhelper->showEmailListManager($data);
	}
	
	/*
	 * function to activate email list
	 */
	function ActivateEL($data) {		
		if (!empty($data['email_list_id'])) {
			$ctrler = $this->createHelper('EmailList');
			$ctrler->__changeStatusEmailList($data['email_list_id'], 1);
		    $ctrler->showEmailListManager($data);
		}		
	}
	
	/*
	 * function to activate Email list
	 */
	function InactivateEL($data) {		
		if (!empty($data['email_list_id'])) {
			$ctrler = $this->createHelper('EmailList');
			$ctrler->__changeStatusEmailList($data['email_list_id'], 0);
		    $ctrler->showEmailListManager($data);
		}		
	}
	
	/* 
	 * function to import emails to a email list 
	 */
	function importemail($data) {
        $ctrler = $this->createHelper('EmailList');
        $ctrler->showImportEmailForm($data);    
	}
	
	/* 
	 * function to import emails from a form 
	 */
	function doimportemail($data) {
        $ctrler = $this->createHelper('EmailList');
        $ctrler->doImportEmail($data);    
	}
	
	/*
	 * function to show Email Address manager 
	 */
	function managerEmail($data) {	    
		$elhelper = $this->createHelper('EmailList');
	    $elhelper->showEmailAddressManager($data);
	}
	
	/*
	 * func to show edit Email Address form
	 */ 
	function editEmail($data){
		$elhelper = $this->createHelper('EmailList');
		$elhelper->editEmailAddress($data['email_id']);
	}
	
	/*
	 * func to update Email Address
	 */ 
	function updateEmail($data){						
		$elhelper = $this->createHelper('EmailList');
		$elhelper->updateEmailAddress($data);
	}
	
	/*
	 * func to delete Email Address
	 */ 
	function deleteEmail($data){						
		$elhelper = $this->createHelper('EmailList');
		$elhelper->deleteEmailAddress($data['email_id']);
		$elhelper->showEmailAddressManager($data);
	}
	
	/*
	 * function to activate Email Address
	 */
	function ActivateEmail($data) {		
		if (!empty($data['email_id'])) {
			$ctrler = $this->createHelper('EmailList');
			$ctrler->__changeStatusEmailAddress($data['email_id'], 1);
		    $ctrler->showEmailAddressManager($data);
		}		
	}
	
	/*
	 * function to activate EmailAddress
	 */
	function InactivateEmail($data) {		
		if (!empty($data['email_id'])) {
			$ctrler = $this->createHelper('EmailList');
			$ctrler->__changeStatusEmailAddress($data['email_id'], 0);
		    $ctrler->showEmailAddressManager($data);
		}		
	}
	
	/*
	 * function to change subscribe status
	 */
	function changesubscibe($data) {		
		if (!empty($data['email_id'])) {
		    $subscribed = empty($data['subscribe_val']) ? 1 : 0;
			$ctrler = $this->createHelper('EmailList');
			$ctrler->__changeStatusEmailAddress($data['email_id'], $subscribed, 'subscribed');
		    $ctrler->showEmailAddressManager($data);
		}		
	}

	/*
	 * func to activate all email
	 */
	function activateallemail($data) {
	    if (!empty($data['ids'])) {
	        $ctrler = $this->createHelper('EmailList');
		    foreach($data['ids'] as $id) {
			    $ctrler->__changeStatusEmailAddress($id, 1);
		    }
	    }		    			
		$ctrler->showEmailAddressManager($data);
	}

	/*
	 * func to inactivate all email
	 */
	function inactivateallemail($data) {
	    if (!empty($data['ids'])) {
	        $ctrler = $this->createHelper('EmailList');
		    foreach($data['ids'] as $id) {
			    $ctrler->__changeStatusEmailAddress($id, 0);
		    }
	    }		    			
		$ctrler->showEmailAddressManager($data);
	}

	/*
	 * func to change subscribe status all email
	 */
	function changesubscribeall($data) {
	    if (!empty($data['ids'])) {
	        $ctrler = $this->createHelper('EmailList');
	        $subscribed = intval($data['subscribe_val']);
		    foreach($data['ids'] as $id) {		        
			    $ctrler->__changeStatusEmailAddress($id, $subscribed, 'subscribed');
		    }
	    }		    			
		$ctrler->showEmailAddressManager($data);
	}

	/*
	 * func to change sdelete all email
	 */
	function deleteallemail($data) {
	    if (!empty($data['ids'])) {
	        $ctrler = $this->createHelper('EmailList');
	        $subscribed = intval($data['subscribe_val']);
		    foreach($data['ids'] as $id) {
		        $ctrler->deleteEmailAddress($id);
		    }
	    }		    			
		$ctrler->showEmailAddressManager($data);
	}

	/*
	 * func to show generate subscribe html code form
	 */
	function gensubscribecode($data) {
        $ctrler = $this->createHelper('EmailList');	    			
		$ctrler->showGenerateSubscribeCode($data['email_list_id']);
	}

	/*
	 * func to generate subscribe html code
	 */
	function dogensubscribecode($data) {
        $ctrler = $this->createHelper('EmailList');	    			
		$ctrler->generateSubscribeCode($data);
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
	 * function to show subscribe form
	 */
	function showsubscribeform($data) {
	    $this->helperCtrler->showSubscribeForm($data);        
	}
	
	/*
	 * function to process the subscribe form
	 */
	function dosubscribeform($data) {
	    $this->helperCtrler->processSubscribeForm($data);
	}
	
	/*
	 * function show the reports manager
	 */
	function reportmanager($data) {
        $this->helperCtrler->showReportsManager($data);
	}
	
	/*
	 * func to show newletter test mail interface
	 */ 
	function testmail($data){
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->showTestMailNewsletter($data);
	}
	
	/*
	 * func to show send test email
	 */ 
	function sendtestemail($data){
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->sendNewsletterTestEmail($data);
	}
	
	/*
	 * function to track the email opened or not
	 */
	function opentracking($data) {
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->updateEmailOpenTracking($data);
	}
	
	/*
	 * function to track the email opened or not
	 */
	function clicktracking($data) {
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlHelper->updateEmailClickTracking($data);
		$redirectUrl = htmlspecialchars_decode(urldecode($data['link']));
		redirectUrl($redirectUrl);
	}
		
	/*
	 * function to unsubscribe from email list
	 */
	function unsubscribemaillist($data) {
	    $elCtrler = $this->createHelper('EmailList');
	    $elCtrler->unsubscribeMailList($data);
	}
		
	/*
	 * function to send newsletter in cron job
	 */
	function cronjob($data) {
	    $nlCtrler = $this->createHelper('NewsletterEntry');
	    $nlCtrler->startCronJob($data);
	}
	
	/*
	 * function to start sending newsletetr
	 */
	function startsendnewsletter($data) {
	    $nlCtrler = $this->createHelper('NewsletterEntry');
	    $nlCtrler->showSendNewsletter($data);
	}
	
	/*
	 * function to send newletter
	 */
	function dosendnewsletter($data) {
	    $nlCtrler = $this->createHelper('NewsletterEntry');
	    $nlCtrler->doSendNewsletter($data);
	}

	/*
	 * function to show cron command
	 */ 
	function showcroncommand() {		
		$this->pluginRender('croncommand');
	}
}
?>