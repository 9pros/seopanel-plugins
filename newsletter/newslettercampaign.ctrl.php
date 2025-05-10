<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class NewsletterCampaign extends Newsletter {

    var $tableName = "nl_campaigns";    // the database table name of the campaigns
    
	/*
	 * show campaigns list to manage
	 */
	function showCampaignManager($info='') {
		
		$userId = isLoggedIn();			
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select p.*,w.name website from $this->tableName p,websites w where p.website_id=w.id";				
	    if (!empty($info['website_id'])) {
		    $websiteId = intval($info['website_id']);
		    $pgScriptPath .= "&website_id=".$websiteId;
		    $sql .= " and p.website_id=".$websiteId;
		    $this->set('websiteId', $websiteId);    
		} else {
		    $sql .= isAdmin() ? "" : " and w.user_id=$userId";
		}
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;		
		$campaignList = $this->db->select($sql);
		
		$this->set('list', $campaignList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showcampaignsmanager');
	}

	/*
	 * func to create new campaign
	 */ 
	function newCampaign($info=''){
						
		$userId = isLoggedIn();		
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);		
		$websiteId = empty($info['website_id']) ? $websiteList[0]['id'] : intval($info['website_id']);
		$this->set('websiteId', $websiteId);
		$this->set('sec', 'create');
		if (NL_ALLOW_SYSTEM_EMAIL_SERVER) {
		    $this->set('disableSMTPCheck', '');
		    $this->set('checkSMTP', empty($info['is_smtp']) ? "" : "checked");
		} else {
		    $this->set('checkSMTP', 'checked');
		    $this->set('disableSMTPCheck', 'disabled="disabled"');
		}
		
		$this->pluginRender('editcampaign');
	}
	
	/*
	 * function to check whether campaign already exists or not
	 */
    function __checkCampaignName($name, $websiteId, $campaignId=false){
		
		$sql = "select id from $this->tableName where campaign_name='".addslashes($name)."' and website_id=$websiteId";
		$sql .= $campaignId ? " and id!=$campaignId" : "";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	/*
	 * func to create campaign
	 */
	function createCampaign($listInfo){
	    
		$userId = isLoggedIn();		
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['campaign_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['campaign_name']));
		$errMsg['from_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['from_name']));
		$errMsg['from_email'] = formatErrorMsg($this->validate->checkEmail($listInfo['from_email']));
		$errMsg['reply_to'] = formatErrorMsg($this->validate->checkEmail($listInfo['reply_to']));
		
		if (!NL_ALLOW_SYSTEM_EMAIL_SERVER || !empty($listInfo['is_smtp'])) {
		    $listInfo['is_smtp'] = 1;	    
		    $errMsg['smtp_host'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_host']));	    
		    $errMsg['smtp_username'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_username']));		    
		    $errMsg['smtp_password'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_password']));
		} else {
		    $listInfo['is_smtp'] = 0;
		}
				
		if(!$this->validate->flagErr){		    
		    if (!$this->__checkCampaignName($listInfo['campaign_name'], $listInfo['website_id'])) {		    
    		    $sql = "insert into $this->tableName(website_id,campaign_name,from_name,from_email,reply_to,is_smtp,smtp_host,smtp_username,smtp_password,status)
    					values({$listInfo['website_id']},'".addslashes($listInfo['campaign_name'])."','".addslashes($listInfo['from_name'])."',
    					'".addslashes($listInfo['from_email'])."','".addslashes($listInfo['reply_to'])."',{$listInfo['is_smtp']},
    					'".addslashes($listInfo['smtp_host'])."','".addslashes($listInfo['smtp_username'])."','".addslashes($listInfo['smtp_password'])."',1)";
    			$this->db->query($sql);
    			$this->showCampaignManager(array('website_id' => $listInfo['website_id']));
    			exit;
		    } else {
		        $errMsg['campaign_name'] = formatErrorMsg($this->pluginText['campaignexists']);
		    }
		}				
		$this->set('post', $listInfo);
		$this->set('errMsg', $errMsg);
		$this->newCampaign($listInfo);
	}	


	/*
	 * func to edit campaign
	 */ 
	function editCampaign($campaignId, $listInfo=''){
		
	    $userId = isLoggedIn();
		if(!empty($campaignId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getCampaignInfo($campaignId);
			}
			$this->set('post', $listInfo);
			
			$websiteController = New WebsiteController();
    		$websiteList = $websiteController->__getAllWebsites($userId, true);
    		$this->set('websiteList', $websiteList);		
    		$websiteId = empty($listInfo['website_id']) ? $websiteList[0]['id'] : intval($listInfo['website_id']);
    		$this->set('websiteId', $websiteId);
    		
    		if (NL_ALLOW_SYSTEM_EMAIL_SERVER) {
    		    $this->set('disableSMTPCheck', '');
    		    $this->set('checkSMTP', empty($listInfo['is_smtp']) ? "" : "checked");
    		} else {
    		    $this->set('checkSMTP', 'checked');
    		    $this->set('disableSMTPCheck', 'disabled="disabled"');
    		}
    		
    		$this->set('sec', 'update');
    		$this->pluginRender('editcampaign');
			exit;
		}		
	}
	
	/*
	 * func to update campaign
	 */
	function updateCampaign($listInfo){
		
		$userId = isLoggedIn();	
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['campaign_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['campaign_name']));
		$errMsg['from_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['from_name']));
		$errMsg['from_email'] = formatErrorMsg($this->validate->checkEmail($listInfo['from_email']));
		$errMsg['reply_to'] = formatErrorMsg($this->validate->checkEmail($listInfo['reply_to']));
		
	    if (!NL_ALLOW_SYSTEM_EMAIL_SERVER || !empty($listInfo['is_smtp'])) {
		    $listInfo['is_smtp'] = 1;	    
		    $errMsg['smtp_host'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_host']));	    
		    $errMsg['smtp_username'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_username']));		    
		    $errMsg['smtp_password'] = formatErrorMsg($this->validate->checkBlank($listInfo['smtp_password']));
		} else {
		    $listInfo['is_smtp'] = 0;
		}				
		$this->set('post', $listInfo);
		
		if(!$this->validate->flagErr){
		    if (!$this->__checkCampaignName($listInfo['campaign_name'], $listInfo['website_id'], $listInfo['id'])) {		    
    			$sql = "update $this->tableName set
    					website_id = '".intval($listInfo['website_id'])."',
    					campaign_name = '".addslashes($listInfo['campaign_name'])."',
    					from_name = '".addslashes($listInfo['from_name'])."',
    					from_email = '".addslashes($listInfo['from_email'])."',
    					reply_to = '".addslashes($listInfo['reply_to'])."',
    					is_smtp = '".intval($listInfo['is_smtp'])."',
    					smtp_host = '".addslashes($listInfo['smtp_host'])."',
    					smtp_username = '".addslashes($listInfo['smtp_username'])."',
    					smtp_password = '".addslashes($listInfo['smtp_password'])."'
    					where id={$listInfo['id']}";
        				$this->db->query($sql);
        				$this->showCampaignManager($listInfo);
    			exit;
		    } else {
                $errMsg['campaign_name'] = formatErrorMsg($this->pluginText['campaignexists']);
		    }
		}
		$this->set('errMsg', $errMsg);
		$this->editCampaign($listInfo['id'], $listInfo);
	}
	
	/*
	 * func to delete campaign
	 */
	function deleteCampaign($campaignId) {

		$sql = "delete from $this->tableName where id=$campaignId";
		$this->db->query($sql);
		
		$nlHelper = $this->createHelper('NewsletterEntry');
		$nlList = $nlHelper->getAllNewsletters(" and campaign_id=$campaignId");
		foreach ($nlList as $nlInfo) {
            $nlHelper->deleteNewsletter($nlInfo['id']);    
		}
		
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatus($campaignId, $status){
		
		$sql = "update $this->tableName set status=$status where id=$campaignId";
		$this->db->query($sql);		
	}

	/*
	 * func to get all campaigns
	 */
	function getAllCampaigns($condtions='') {
		
		$sql = "select nc.* from $this->tableName nc,websites w where nc.website_id=w.id and nc.status=1 and w.status=1";
		$sql .= empty($condtions) ? "" : $condtions;
		$campaignList = $this->db->select($sql);
		return $campaignList;		
	}

	/*
	 * func to get campaign info
	 */
	function __getCampaignInfo($campaignId) {
		
		$sql = "select p.*,w.url from $this->tableName p,websites w where  p.website_id=w.id and p.id=$campaignId";
		$info = $this->db->select($sql, true);
		return $info;		
	}
}
?>