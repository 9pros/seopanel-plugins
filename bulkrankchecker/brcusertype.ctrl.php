<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
class BRCUserType extends BulkRankChecker {
    
    // the category to identify plugin values 
    var $specCategory = "bulkrankchecker";
    
    // spec column array
    var $specColList;
    
    /**
     * constructor
     */
    function __construct() {
    	
    	// call parent constructor
    	parent::__construct();
    	
    	$spTextReport = $this->getLanguageTexts('report', $_SESSION['lang_code']);
    	
    	/*
    	 * type 		- [float|number|integer|int|text|string]
    	 * field_type 	- ['small', 'bool', 'medium', 'large', 'text']
    	 * validation	- [checkBlank, checkAlpha, checkNumber, checkDate]
    	 */
    	$this->specColList = array(
    		"brc_keyword_count" => array('type' => 'int', 'field_type' => 'small', 'default' => '0', 'validation' => 'checkNumber'),
    		"brc_website_count" => array('type' => 'int', 'feild_type' => 'small', 'default' => '0', 'validation' => 'checkNumber'),
    		"brc_search_engine_count" => array('type' => 'int', 'field_type' => 'small', 'default' => '0', 'validation' => 'checkNumber'),
    		"brc_report_interval_limit" => array(
    			'type' => 'int',
    			'field_type' => 'select',
    			'default' => '2',
    			'validation' => 'checkNumber',
    			'options' => array(
    				1 => $_SESSION['text']['label']['Daily'],
    				2 => $spTextReport['2 Days'],
    				7 => $_SESSION['text']['label']['Weekly'],
    				30 => $_SESSION['text']['label']['Monthly'],
    			),
    		),
    	);
    	
    }
	
	/*
	 * function to show  plugin user type settings
	 */
	function showPluginUserTypeSettings($info = '') {
		$userTypeId = !empty($info['user_type_id']) ? $info['user_type_id'] : 0;
		$userTypeCtrler = new UserTypeController();
		$userTypeCtrler->layout = 'ajax';
		$userTypeCtrler->editPluginUserTypeSettings($userTypeId, $this->pluginId, "BRCUserType");
	}	

	/**
	 * function to validate user type settings
	 */
	function validateUserTypeSettings($listInfo, $errMsg, $campaignId = 0) {
		$userId = isAdmin() ? intval($listInfo ['user_id']) : isLoggedIn();
		$userCtrl = new UserController();
		$userInfo = $userCtrl->__getUserInfo($userId);
	
		// user type settings spec list
		$userTypeCtrl = new UserTypeController();
		$userTypeSpecList = $userTypeCtrl->getUserTypeSpec($userInfo['utype_id'], $this->specCategory);
		
		// if spec existing
		if (!empty($userTypeSpecList)) {
		
			// loop through plugin settings values
			foreach ($userTypeSpecList as $specCol => $specVal) {
				$errorExist = false;
				
				// if value is set
				if (!empty($specVal)) {
					
					switch($specCol) {
						
						case "brc_keyword_count":							
							$keywordCount = 0;
							$campaignList = $this->dbHelper->getAllRows("brc_campaigns", "user_id=$userId", "id");
							$campaignIdList = array();
							
							foreach ($campaignList as $campaignInfo) {
								if ($campaignInfo['id'] != $campaignId) {
									$campaignIdList[] = $campaignInfo['id'];
								}
							}
							
							if (!empty($campaignIdList)) {
								$countInfo = $this->dbHelper->getRow("brc_keywords", "campaign_id in (".implode(",", $campaignIdList).")", "count(*) as count");
								$keywordCount = $countInfo['count'];
							}
							
							$keywordCount += count($listInfo['keywordList']);
							$errorExist = $keywordCount > $specVal ? true : false;
							break;
						
						case "brc_website_count":
							$errorExist = count($listInfo['linkList']) > $specVal ? true : false;
							break;
						
						case "brc_search_engine_count":
							$errorExist = count($listInfo['searchengines']) > $specVal ? true : false;
							break;
						
						case "brc_report_interval_limit":
							$errorExist = $listInfo['report_interval'] < $specVal ? true : false;
							break;
						
					}
					
					// if error existing
					if ($errorExist) {
						
						if ($specCol == 'brc_report_interval_limit') {
							$errMsg[$specCol] = formatErrorMsg($this->pluginText["Selected interval is less than account limit"]);
						} else {
							$errMsg[$specCol] = formatErrorMsg(str_replace("[limit]", $specVal, $this->pluginText['total_count_greater_account_limit']));
						}
						
						break;
					}
					
				}
				
			}
		}
		
		return array($errorExist, $errMsg);
	
	}
	
}
?>