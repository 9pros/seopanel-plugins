<?php
class Subscription extends SeoPluginsController {

    // plugin settings controller object
    var $settingsCtrler;
    var $pgCtrler;

    // the plugin text database table
    var $textTable = "texts";
    
    // the plugin text category
    var $textCategory = "subscription";
    
    // plugin directory name
    var $directoryName = "Subscription";
	
    
    /*
     * function to init plugin details before each plugin action
    */
    function initPlugin($data) {
    
    	$this->setPluginTextsForRender($this->textCategory, $this->textTable);
    	$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
    	$this->set('pluginText', $this->pluginText);
    
    	// create setting object and define all settings
    	include_once(SP_PLUGINPATH."/$this->directoryName/subscription_settings.ctrl.php");
    	$this->settingsCtrler = New SubscriptionSettings();
    	$this->settingsCtrler->defineAllPluginSystemSettings();
    	$this->settingsCtrler = $this->assignCommonDataToObject($this->settingsCtrler);
    	
		// create payment gateway ctrler
    	include_once(SP_PLUGINPATH."/$this->directoryName/paymentgateway.ctrl.php");
    	$this->pgCtrler = new PaymentGateway();
    	$this->pgCtrler = $this->assignCommonDataToObject($this->pgCtrler);
    	
		// create order ctrler
    	include_once(SP_PLUGINPATH."/$this->directoryName/order.ctrl.php");
    	$this->orderCtrler = new OrderController();
    	$this->orderCtrler = $this->assignCommonDataToObject($this->orderCtrler);
    	
    	// include controllers
    	include_once(SP_PLUGINPATH."/$this->directoryName/email.ctrl.php");
    	$this->emailCtrler = new EmailController();
    	$this->emailCtrler = $this->assignCommonDataToObject($this->emailCtrler);
    	 
    }
    
    /*
     * func to assign common data to an object
    */
    function assignCommonDataToObject($object) {
    	$object->data = $this->data;
    	$object->pluginText = $this->pluginText;
    	return $object;
    }
    
    /*
     * function to show the first pagewhile access plugin
    */
    function index($data) {
    	$orderCtrler = new OrderController();
    	$orderCtrler->showOrderManager($data);
    }
    
    /**
     * function to show paymentgateway manager
     */
    function paymentGatewayManager($data) {
    	checkAdminLoggedIn();
    	$this->pgCtrler->showPaymentGatewayManager($data);
    }
    
    /**
     * function to activate payment gateway
     */
    function activatePG($data) {
    	checkAdminLoggedIn();
    	$this->pgCtrler->changePaymentGatewayStatus($data['pg_id'], 1);
    	$this->pgCtrler->showPaymentGatewayManager($data);
    }
    
    /**
     * function inactvate payment gateway
     */
    function inactivatePG($data) {
    	checkAdminLoggedIn();
    	$this->pgCtrler->changePaymentGatewayStatus($data['pg_id'], 0);
    	$this->pgCtrler->showPaymentGatewayManager($data);
    }
    
    /**
     * function to edit payment gateway
     */
    function editPaymentGateway($data) {
    	checkAdminLoggedIn();
    	$this->pgCtrler->editPaymentGateway($data['pg_id']);
    }
    
    /**
     * function to show email template manager
     */
    function emailTemplateManager($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->showEmailTemplateManager($data);
    }
    
    /**
     * function to activate email template
     */
    function activateEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->changeEmailTemplateStatus($data['id'], 1);
    	$this->emailCtrler->showEmailTemplateManager($data);
    }
    
    /**
     * function inactvate email template
     */
    function inactivateEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->changeEmailTemplateStatus($data['id'], 0);
    	$this->emailCtrler->showEmailTemplateManager($data);
    }
    
    /**
     * function to add new email template
     */
    function newEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->newEmailTemplate($data);
    }
    
    /**
     * function to create email template
     */
    function createEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->createEmailTemplate($data);
    }
    
    /**
     * function to edit email template
     */
    function editEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->editEmailTemplate($data['id']);
    }
    
    /**
     * function to update email template
     */
    function updateEmailTemplate($data) {
    	checkAdminLoggedIn();
    	$this->emailCtrler->updateEmailTemplate($data);
    }
    
    /**
     * function to show order manager
     */
    function orderManager($data) {
    	checkLoggedIn();
    	$this->orderCtrler->showOrderManager($data);
    }
    
    /**
     * function to view order
     */
    function viewOrder($data) {
    	checkLoggedIn();
    	$this->orderCtrler->viewOrder($data['id']);
    }
    
    /**
     * function to update payment gateway
     */
    function updatePaymentGateway($data) {
    	checkAdminLoggedIn();
    	$this->pgCtrler->updatePaymentGateway($data);
    }

    /*
     * function show system settings
    */
    function settings($data) {
    	checkAdminLoggedIn();
    	$this->settingsCtrler->showPluginSettings();
    }
    
    /*
     * function to save plugin settings
    */
    function updateSettings($data) {
    	checkAdminLoggedIn();
    	$this->settingsCtrler->updatePluginSettings($data);
    }

    /*
     * function to show cron command
     */
    function showcroncommand() {
    	checkAdminLoggedIn();
    	$this->pluginRender('croncommand');
    }
    
    function cronJob($data) {
    	$this->emailCtrler->processMembershipStatusNotifications();
    }
    
    /*
     * func to show about us
    */
    function aboutus() {
    	$this->settingsCtrler->showPluginAboutUs();
    }
	
}