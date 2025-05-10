<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
class LDUserType extends LinkDiagnosis {
    
    // the category to identify plugin values 
    var $specCategory = "linkdiagnosis";
    
    // spec column array
    var $specColList;
    
    /**
     * constructor
     */
    function LDUserType() {
    	
    	// call parent constructor
    	parent::__construct();
    	
    	/*
    	 * type 				- [float|number|integer|int|text|string]
    	 * field_type 			- ['small', 'bool', 'medium', 'large', 'text']
    	 * validation			- [checkBlank, checkAlpha, checkNumber, checkDate]
    	 * custom_validation	- The custom validation object than normal validation class
    	 */
    	$this->specColList = array(
    		
    		// @TODO - Implement custom validation in future
    		/*"ld_backlink_count" => array('type' => 'int', 'field_type' => 'small', 'default' => '0', 'validation' => 'checkBacklinkCount', 'custom_validation' => $this),*/
    			
    		"ld_backlink_count" => array('type' => 'int', 'field_type' => 'small', 'default' => '0', 'validation' => 'checkNumber'),
    	);
    	
    }
    
    /*
     * function to validate backlink count
     */
    function checkBacklinkCount($backlinkCount) {
    	$errMsg = $this->validate->checkNumber($backlinkCount);
    		
    	// check whether backlink count is greater than plugin settings count
    	if (!$this->validate->flagErr && ($backlinkCount > LD_MAX_LINKS_REPORT)) {
    		$errMsg = $this->pluginText["Backlink count is greater than plugin settings"];
    	}
    	
    	return $errMsg;
    	
    }
	
	/*
	 * function to show  plugin user type settings
	 */
	function showPluginUserTypeSettings($info = '') {
		$userTypeId = !empty($info['user_type_id']) ? $info['user_type_id'] : 0;
		$userTypeCtrler = new UserTypeController();
		$userTypeCtrler->layout = 'ajax';
		$userTypeCtrler->editPluginUserTypeSettings($userTypeId, $this->pluginId, "LDUserType");
	}

	/**
	 * function to validate user type settings
	 */
	function validateUserTypeSettings($listInfo, $errMsg, $userId) {
		
		$msg = $this->validate->checkNumber($listInfo['max_links']);
		
		// check whether backlink count is greater than plugin settings count
		if ($this->validate->flagErr) {
			$errorExist = true;
			$errMsg['max_links'] = formatErrorMsg($msg);
		} else {
			$avlBacklinkCount = $this->getMaxAvailableBacklinkCount($userId, $listInfo['id']);
			$errorExist = $listInfo['max_links'] > $avlBacklinkCount ? true : false;
			
			if ($errorExist) {
				$errMsg['max_links'] = formatErrorMsg($this->pluginText["Backlink count is greater than maximum links allowed"]);
			}
		}
				
		return array($errorExist, $errMsg);
		
	}
	
	function getMaxAvailableBacklinkCount($userId, $reportId = false) {
		$maxLinksCount = LD_MAX_LINKS_REPORT;
		
		// user type settings spec list
		$userTypeCtrl = new UserTypeController();
		$specList = $userTypeCtrl->getUserTypeSpecByUser($userId, $this->specCategory);
		
		// check whether usertype settings is added
		if (!empty($specList['ld_backlink_count'])) {
			$sql = "select sum(max_links) sum from ld_projects p, ld_reports r where p.id=r.project_id and p.user_id=" . intval($userId);
			$sql .= !empty($reportId) ? " and r.id!=" . intval($reportId) : "";
			$sumInfo = $this->db->select($sql, true);
			$maxLinksCount = $specList['ld_backlink_count'] - intval($sumInfo['sum']);
		}
		
		return $maxLinksCount;
		
	}
	
}
?>