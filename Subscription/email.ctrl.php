<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

// include subscription class
include_once 'Subscription.ctrl.php';

class EmailController extends Subscription{

	/*
	 * Show email template to manage
	 */
	function showEmailTemplateManager($info='') {
		
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=emailTemplateManager";
		$sql = "select * from subscription_email_templates";
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
		
		$rList = $this->db->select($sql);		
		$this->set('list', $rList);
		$this->set('pageNo', $_GET['pageno']);
		$this->pluginRender('email_template_manager');
	}

	/**
	 * function to get email template info
	 */
	function __getEmailTemplateInfo($etId) {
		$sql = "select * from subscription_email_templates where id=" . intval($etId);
		$etInfo = $this->db->select($sql, true);
		return $etInfo;
	}
	
	/**
	 * function to get all email templates
	 */
	function __getAllEmailTemplate($where = " and status=1") {
		$sql = "select * from subscription_email_templates where 1=1 $where";
		$etList = $this->db->select($sql);
		return $etList;
	}

	/**
	 * function to chekc name of email template
	 */
	function __checkName($name){
		$sql = "select id from subscription_email_templates where name='".addslashes($name)."'";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
		
	/**
	 * func to create payment gateway
	 */ 
	function newEmailTemplate($listInfo = ''){
		$this->set('post', $listInfo);
		$this->set('actVal', 'createEmailTemplate');
		$this->pluginRender('edit_email_template');
		
	}
	
	/*
	 * func to create email template
	 */
	function createEmailTemplate($listInfo){
		
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$errMsg['email_subject'] = formatErrorMsg($this->validate->checkBlank($listInfo['email_subject']));
		$errMsg['email_content'] = formatErrorMsg($this->validate->checkBlank($listInfo['email_content']));
		
		// if no errors
		if (!$this->validate->flagErr) {
			
			// check for name
			if (!$this->__checkName($listInfo['name'])) {
			
				$sql = "insert into subscription_email_templates(name, email_subject, email_content) values( 
						'" . addslashes($listInfo['name']) . "', '" . addslashes($listInfo['email_subject']) . "',
						'" . addslashes($listInfo['email_content']) . "')";
				$this->db->query($sql);
				
				$this->showEmailTemplateManager($etInfo);
				return true;
			} else {
				$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($_SESSION['text']['label']['already exist']));
			}
			
		}
		
		$this->set('errMsg', $errMsg);
		$this->newEmailTemplate($listInfo);
		
	}
		
	/**
	 * func to edit email template
	 */ 
	function editEmailTemplate($etId, $listInfo = ''){
		
		// if not empty email template id
		if(! empty($etId)){
						
			if (empty($listInfo)) {
				$listInfo = $this->__getEmailTemplateInfo($etId);
			}
			
			$this->set('post', $listInfo);
			$this->set('actVal', 'updateEmailTemplate');
			$this->pluginRender('edit_email_template');
		}		
	}
	
	/*
	 * func to update email template
	 */
	function updateEmailTemplate($listInfo){
		
		$errMsg['email_subject'] = formatErrorMsg($this->validate->checkBlank($listInfo['email_subject']));
		$errMsg['email_content'] = formatErrorMsg($this->validate->checkBlank($listInfo['email_content']));
		$etId = intval($listInfo['id']);
		
		// if no errors
		if (!$this->validate->flagErr) {
			$sql = "update subscription_email_templates set 
					email_subject='" . addslashes($listInfo['email_subject']) . "',
					email_content='" . addslashes($listInfo['email_content']) . "'
					where id=$etId";
			$this->db->query($sql);
			
			$this->showEmailTemplateManager($etInfo);
			return true;
		}
		
		$this->set('errMsg', $errMsg);
		$this->editEmailTemplate($etId, $listInfo);
		
	}
	
	/*
	 * function to change email template status
	 */
	function changeEmailTemplateStatus($etId, $status) {
		$etId = intval($etId);
		$status = intval($status);
		$sql = "update subscription_email_templates set status='$status' where id=$etId";
		$this->db->query($sql);
	}
	
	/**
	 * function to process membership expiry
	 */
	function processMembershipStatusNotifications() {
		
		$todayTime = time();
		$userController = new UserController();
		$adminInfo = $userController->__getAdminInfo();
		$adminName = $adminInfo['first_name']."-".$adminInfo['last_name'];
		
		// get users with expiry date
		$userList = $this->dbHelper->getAllRows("users", "utype_id!=1 and expiry_date IS NOT NULL and expiry_date!='0000-00-00'");
		
		// loop through user list
		foreach ($userList as $userInfo) {
			$expireTime = strtotime($userInfo['expiry_date']);
			
			// check if user not expired
			if ($todayTime < $expireTime) {
				$daysToExpire = ceil(($expireTime - $todayTime) / (60 * 60 * 24));
				$sendRemainder = false;
				
				// check for remainder conditions
				if ($daysToExpire <= SUBSCRIPTION_RENEWAL_REMINDER_2) {
					$sendRemainder = true;
				} else if ($daysToExpire == SUBSCRIPTION_RENEWAL_REMINDER_1) {
					$sendRemainder = true;
				}
				
				// if remainder needs to be send
				if ($sendRemainder) {
					$emailInfo = $this->dbHelper->getRow("subscription_email_templates", "status=1 and name='SUBSCRIPTION_RENEWAL_REMINDER'");
					$emailContent = $this->formatEmailMessage($emailInfo['email_content'], $userInfo);
					$emailContent = str_replace('[EXPIRE_DAYS]', $daysToExpire, $emailContent);
					sendMail($adminInfo['email'], $adminName, $userInfo['email'], $emailInfo['email_subject'], $emailContent);
					echo "Sent renewal reminder mail to " . $userInfo['email'] . "\n";
				}
				
			} else {
				$daysAfterExpired = floor(($todayTime - $expireTime) / (60 * 60 * 24));
			
				// commented to allow user to upgrade
				/*// if status is active, make them inactive
				if ($userInfo['status']) {
					$userController->__changeStatus($userInfo['id'], 0);
				}*/			
				
				// send notification for expired plans
				if ($daysAfterExpired <= SUBSCRIPTION_EXPIRED_NOTIFICATION) {
					
					$websiteCtrler = New WebsiteController();
					$websiteList = $websiteCtrler->__getAllWebsites($userInfo['id']);
					echo "Deactivating websites of user - " . $userInfo['username'] . "\n";
					foreach ($websiteList as $websiteInfo){
						$websiteCtrler->__changeStatus($websiteInfo['id'], 0);
					}

					$emailInfo = $this->dbHelper->getRow("subscription_email_templates", "status=1 and name='SUBSCRIPTION_EXPIRED_NOTIFICATION'");
					$emailContent = $this->formatEmailMessage($emailInfo['email_content'], $userInfo);
					sendMail($adminInfo['email'], $adminName, $userInfo['email'], $emailInfo['email_subject'], $emailContent);
					echo "Sent expired notification mail to " . $userInfo['email'] . "\n";
				}
				
			}
			
		}
		
	}
	
	/**
	 * function to format email message
	 */
	function formatEmailMessage($emailContent, $userInfo) {
		$renewLink = SP_WEBPATH . "/admin-panel.php?sec=myprofile"; 
		$search = array('[FIRST_NAME]', '[LAST_NAME]', '[USERNAME]', '[EMAIL]', '[EXPIRY_DATE]', '[RENEW_LINK]');
		$replace = array($userInfo['first_name'], $userInfo['last_name'], $userInfo['username'], $userInfo['email'], $userInfo['expiry_date'], $renewLink);
		$emailContent = str_replace($search, $replace, $emailContent);
		return $emailContent;
	}
	
}
?>
