<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class EmailList extends Newsletter {

    var $tableName = "nl_email_list";             // the database table name of the email list
    var $mappingTableName = "nl_list_mapping";    // the database table name of the email list mapping with newsletter
    var $defaultWidth = 400;                      // default width of the subscribe iframe  
    var $defaultHeight =  120;                    // default height of the subscribe iframe
    
	/*
	 * func to get email lis info
	 */
	function __getEmailListInfo($emailListId) {		
		$sql = "select * from $this->tableName where id=$emailListId";
		$info = $this->db->select($sql, true);
		return $info;		
	}

	/*
	 * func to get all email list
	 */
	function getAllEmailLists($userId, $condtions='') {
		$sql = "select * from $this->tableName where status=1 and user_id=$userId";
	    $sql .= empty($condtions) ? "" : $condtions;
		$emailList = $this->db->select($sql);
		return $emailList;		
	}

	/*
	 * func to update email list mapping
	 */
	function updateEmailListmapping($newsletterId, $elList) {
	    $newsletterId = intval($newsletterId);
	    
	    $this->deleteEmailListMapping($newsletterId);
		
		foreach ($elList as $emailListId) {
		    $emailListId = intval($emailListId);
            $sql = "Insert into $this->mappingTableName(newsletter_id, email_list_id) values($newsletterId, $emailListId)";
            $this->db->query($sql);
		}
	}

	/*
	 * func to get all email list added with a newletter 
	 */
	function getNewsletterEmailLists($newsletterId, $userId=false) {
	    $elList = array();
        $sql = "select map.email_list_id from $this->tableName el, $this->mappingTableName map 
    			where el.id=map.email_list_id and el.status=1 and map.newsletter_id=".intval($newsletterId);
        if (!empty($userId)) $sql .= " and user_id=$userId";
		$list = $this->db->select($sql);
		foreach ($list as $listInfo) {
		    $elList[] = $listInfo['email_list_id'];
		}
		return $elList;
	}
	
	/*
	 * function to delete email list mapping
	 */
	function deleteEmailListMapping($newsletterId) {
	    $newsletterId = intval($newsletterId);
        $sql = "delete from $this->mappingTableName where newsletter_id=$newsletterId";
		$this->db->query($sql);    
	}
	
	/*
	 * func to get count of newsletter emails
	 */
	function getCountNewsletterEmails($newsletterId) {
        $sql = "select count(distinct(email)) count from $this->tableName el, $this->mappingTableName map, nl_subscribers ns 
    			where el.id=map.email_list_id and el.id=ns.email_list_id and el.status=1 and ns.status=1 and ns.subscribed=1 and map.newsletter_id=$newsletterId";
        $countInfo = $this->db->select($sql, true);
        $count = empty($countInfo['count']) ? 0 : $countInfo['count'];
        return $count; 
	}
	
	/*
	 * show email list to manage
	 */
	function showEmailListManager($info='') {
		
		$userId = isLoggedIn();
		$pgScriptPath = PLUGIN_SCRIPT_URL . "&action=managerEL";		
		$sql = "select * from $this->tableName where user_id=$userId";
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " order by name limit ".$this->paging->start .",". $this->paging->per_page;		
		$elList = $this->db->select($sql);
		
	    foreach ($elList as $i => $elInfo) {
		    $elInfo['total'] = $this->__getEmailCount($elInfo['id']);
		    $elInfo['subscribed'] = $this->__getEmailCount($elInfo['id'], ' and subscribed=1');
		    $elInfo['unsubscribed'] = $this->__getEmailCount($elInfo['id'], ' and subscribed=0');
		    $elInfo['inactive'] = $this->__getEmailCount($elInfo['id'], ' and status=0'); 
	        $elList[$i] = $elInfo;        
		}
		
		$this->set('list', $elList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showelmanager');
	}

	/*
	 * func to create new email list
	 */ 
	function newEmailList($info=''){
		
		$this->set('sec', 'create');		
		$this->set('post', $info);		
		$this->pluginRender('editemaillist');
	}
	
	/*
	 * function to check whether email list already exists or not
	 */
    function __checkEmailListName($name, $elId=false){
		$userId = isLoggedIn();
		$sql = "select id from $this->tableName where name='".addslashes($name)."' and user_id=$userId";
		$sql .= $elId ? " and id!=$elId" : "";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	/*
	 * func to create EmailList
	 */
	function createEmailList($listInfo){
	    
		$userId = isLoggedIn();
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
				
		if(!$this->validate->flagErr){		    
		    if (!$this->__checkEmailListName($listInfo['name'])) {
		        $sql = "insert into $this->tableName(name, user_id, status)
    					values('".addslashes($listInfo['name'])."', $userId, 1)";
    			$this->db->query($sql);    			
    			$this->showEmailListManager();
    			exit;
		    } else {
		        $errMsg['name'] = formatErrorMsg($this->pluginText['emaillistexists']);
		    }
		}
				
		$this->set('errMsg', $errMsg);
		$this->newEmailList($listInfo);
	}	


	/*
	 * func to edit email list
	 */ 
	function editEmailList($elId, $listInfo=''){
		if(!empty($elId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getEmailListInfo($elId);
			}
			    		
			$this->set('post', $listInfo);
    		$this->set('sec', 'update');
    		$this->pluginRender('editemaillist');
		}		
	}
	
	/*
	 * func to update email list
	 */
	function updateEmailList($listInfo){
		
		$userId = isLoggedIn();
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));		
		$elId = intval($listInfo['id']);
		
		if(!$this->validate->flagErr){
		    if (!$this->__checkEmailListName($listInfo['name'], $elId)) {			    
    			$sql = "update $this->tableName set
    					name = '".addslashes($listInfo['name'])."'
    					where id=$elId";
				$this->db->query($sql);				
				$this->showEmailListManager();
    			exit;
		    } else {
		        $errMsg['name'] = formatErrorMsg($this->pluginText['emaillistexists']);    
		    }
		}
		$this->set('errMsg', $errMsg);
		$this->set('post', $listInfo);
		$this->editEmailList($elId, $listInfo);
	}	

	
	/*
	 * func to delete email list
	 */
	function deleteEmailList($emailListId) {
	    $emailListId = intval($emailListId);
	    
	    // delete email
		$sql = "delete from $this->tableName where id=$emailListId";
		$this->db->query($sql);
		
		// delete all sending logs
		$subscribersList = $this->__getEmailListSubscribers($emailListId);
		foreach ($subscribersList as $subscriberInfo) {
		    $sql = "delete from nl_sending_log where subscriber_id=".$subscriberInfo['id'];
		    $this->db->query($sql);    
		}
		
		// delete all subscribers
		$sql = "delete from nl_subscribers where email_list_id=$emailListId";
		$this->db->query($sql);
		
		// delete mapping 
		$sql = "delete from $this->mappingTableName where email_list_id=$emailListId";
		$this->db->query($sql);
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatusEmailList($emailListId, $status){
		$emailListId = intval($emailListId);
		$sql = "update $this->tableName set status=$status where id=$emailListId";
		$this->db->query($sql);		
	}
	
	/*
	 * func to get count of email of a email list
	 */
	function __getEmailCount($emailListId, $conditions='') {	    
        $sql = "select count(id) count from nl_subscribers where email_list_id=$emailListId $conditions";
        $countInfo = $this->db->select($sql, true);
        $count = empty($countInfo['count']) ? 0 : $countInfo['count'];
        return $count; 
	}

	/*
	 * func to get all email list
	 */
	function __getAllEmailList($condtions='') {		
		$userId = isLoggedIn();
	    $sql = "select * from $this->tableName where user_id=$userId";
		$sql .= empty($condtions) ? "" : $condtions;
		$nlList = $this->db->select($sql);
		return $nlList;		
	}

	/*
	 * func to get all subscribers of email list
	 */
	function __getEmailListSubscribers($emailListId, $condtions='') {		
		$sql = "select * from nl_subscribers where email_list_id=$emailListId";
		$sql .= empty($condtions) ? "" : $condtions;
		$nlList = $this->db->select($sql);
		return $nlList;		
	}

	/*
	 * func to show import email form
	 */ 
	function showImportEmailForm($info=''){
        $elList = $this->__getAllEmailList(' and status=1');
		$this->set('elList', $elList);
		$emailListId = empty($info['email_list_id']) ? $elList[0]['id'] : intval($info['email_list_id']);
		
		// if no email list found
	    if (empty($emailListId)) showErrorMsg("No email list found!");
		
	    $this->set('emailListId', $emailListId);
		$this->set('post', $info);		
		$this->pluginRender('importemails');
	}
	
	/*
	 * function to do import emails from a form 
	 */
	function doImportEmail($info='') {
	    $emailListId = intval($info['email_list_id']);
	    if (!empty($emailListId) && !SP_DEMO) {
	        if (empty($info['email_addresses']) && empty($_FILES['email_csv_file']['name'])) {
	            print "<script>alert('".$this->pluginText['Please enter email addresses or CSV file']."')</script>";    
	        } else {
	            $text = "<p class=\'note\' id=\'note\'><b>Email import process started. It will take some time depends on the number of email adress needs to be imported!</b></p><div id=\'subcontmed\'></div>";
	            print "<script type='text/javascript'>parent.document.getElementById('import_email_div').innerHTML = '$text';</script>";
	            print "<script>parent.showLoadingIcon('subcontmed', 0)</script>";

	            $resultInfo = array(
	                'total' => 0,
	                'valid' => 0,
	                'invalid' => 0,
	                'duplicate' => 0,
	            );
	            
	            // process file upload option
	            $fileInfo = $_FILES['email_csv_file'];
	            if (!empty($fileInfo['name'])) {
	                if ($fileInfo["type"] == "text/csv") {
	                    $targetFile = SP_TMPPATH . "/".$fileInfo['name'];
	                    if(move_uploaded_file($fileInfo['tmp_name'], $targetFile)) {
                            $handle = fopen($targetFile, 'r');
                            while (($emailInfo = fgets($handle, 4096)) !== false) {
                                if (!empty($emailInfo)) {
                                    $status = $this->importEmailAddress($emailInfo, $emailListId);
                                    $resultInfo[$status] += 1;
                                    $resultInfo['total'] += 1;   
                                }    
                            }
	                    }
	                }
	            }
	            
	            // to process email address list
	            if (!empty($info['email_addresses'])) {
	                $list = explode("\n", $info['email_addresses']);
            		foreach ($list as $emailInfo) {
            		    if (!empty($emailInfo)) {
                            $status = $this->importEmailAddress($emailInfo, $emailListId);
                            $resultInfo[$status] += 1;
                            $resultInfo['total'] += 1;   
                        }    
            		}
	            }
	            
	            $spText = $_SESSION['text'];
	            $resText = '<table width="40%" border="0" cellspacing="0" cellpadding="0px" class="summary_tab" align="center">'.
	            '<tr><td class="topheader" colspan="10">'.$this->pluginText['Import Summary'].'</td></tr>'.
	            '<tr><th class="leftcell">'.$spText['common']['Total'].':</th><td>'.$resultInfo['total'].'</td><th>'.$this->pluginText['Valid'].':</th><td>'.$resultInfo['valid'].'</td></tr>'.
	            '<tr><th class="leftcell">'.$this->pluginText['Existing'].':</th><td>'.$resultInfo['duplicate'].'</td><th>'.$this->pluginText['Invalid'].':</th><td>'.$resultInfo['invalid'].'</td></tr>'.
	            '</table>';
	            echo "<script type='text/javascript'>parent.document.getElementById('subcontmed').innerHTML = '$resText'</script>";
	            echo "<script type='text/javascript'>parent.document.getElementById('note').style.display='none';</script>"; 	            
	        }    
	    }
	}
	
	/*
	 * function to import email address
	 */
	function importEmailAddress($emailInfo, $emailListId) {
        $eInfo = explode(',', $emailInfo);
        $status = 'invalid';
        if (!empty($eInfo[0])) {   
            $emailAddress = trim($eInfo[0]);
            $this->validate->flagErr = false;
            $this->validate->checkEmail($emailAddress);
            if (!$this->validate->flagErr) {
                if (!$emailId = $this->__checkEmailExists($emailListId, $emailAddress)) {
                    $emailName = empty($eInfo[1]) ? "" : trim($eInfo[1]);                    
                    $this->createEmailAdress($emailListId, $emailAddress, $emailName);
                    $status = 'valid';
                } else {
                    $status = 'duplicate';
                }               
            }
        }
        return $status;
	}
	
	/*
	 * function to check whether email exists in a email list
	 */
	function __checkEmailExists($emailListId, $emailAddress, $emailId=false) {
		$sql = "select id from nl_subscribers where email_list_id=$emailListId and email='".addslashes($emailAddress)."'";
		$sql .= $emailId ? " and id!=$emailId" : "";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	/*
	 * function to create email id
	 */
	function createEmailAdress($emailListId, $emailAddress, $emailName, $source='manual') {
	    $sql = "insert into nl_subscribers(email_list_id,email,name,subscribed,source,status)
	    		values($emailListId, '".addslashes($emailAddress)."', '".addslashes($emailName)."', 1, '$source', 1)";
	    $this->db->query($sql);    
	}
	
	/*
	 * show email list to manage
	 */
	function showEmailAddressManager($info='') {
		
		$userId = isLoggedIn();		
		$pgScriptPath = PLUGIN_SCRIPT_URL . "&action=managerEmail";		
		
		$elList = $this->__getAllEmailList(' and status=1');
		if (empty($info['email_list_id']) ) {
		    $emailListId = $elList[0]['id'];    
		} else {
		    $emailListId = intval($info['email_list_id']);
		}
		$pgScriptPath .= "&email_list_id=".$emailListId;
	    $this->set('emailListId', $emailListId);
	    $this->set('elList', $elList);
	    
	    // if no email list found
	    if (empty($emailListId)) showErrorMsg("No email list found!");
	    
	    // the query to get email addresess
		$sql = "select * from nl_subscribers where email_list_id=$emailListId";
		
	    // search section for name
	    if (!empty($info['name'])) {
	        $info['name'] = urldecode($info['name']);
	        $sql .= " and name like '%".addslashes($info['name'])."%'";
		    $pgScriptPath .= "&name=".urlencode($info['name']);
	    }
	    
	    // search section for email
	    if (!empty($info['email'])) {
	        $info['email'] = urldecode($info['email']);
	        $sql .= " and email like '%".addslashes($info['email'])."%'";
		    $pgScriptPath .= "&email=".urlencode($info['email']);
	    }
	    
	    // search for subscribed
	    if (isset($info['subscribed']) && ($info['subscribed'] != -1)) {
	        $sql .= " and subscribed=".intval($info['subscribed']);
		    $pgScriptPath .= "&subscribed=".$info['subscribed'];
	    } else {
            $info['subscribed'] = -1;
	    }
	    
	    $labelList = array(
		    "-- ".$_SESSION['text']['common']['Select']." --" => -1,
			$_SESSION['text']['common']['Yes'] => 1,
			$_SESSION['text']['common']['No'] => 0,
		);		
		$this->set('labelList', $labelList);
	    
	    // search for status
	    if (isset($info['status']) && ($info['status'] != -1)) {
	        $sql .= " and status=".intval($info['status']);
		    $pgScriptPath .= "&status=".$info['status'];
	    } else {
            $info['status'] = -1;
	    }	    
	    $statusList = array(
	        "-- ".$_SESSION['text']['common']['Select']." --" => -1,
			$_SESSION['text']['common']['Active'] => 1,
			$_SESSION['text']['common']['Inactive'] => 0,
		);		
		$this->set('statusList', $statusList);
		
		$sourceList = $this->__getAllEmailSources($emailListId);
		$this->set('sourceList', $sourceList);
		
	    // search section for source
	    if (!empty($info['source'])) {
	        $info['source'] = urldecode($info['source']);
	        $sql .= " and source='".addslashes($info['source'])."'";
		    $pgScriptPath .= "&source=".urlencode($info['source']);
	    }
				
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " order by email limit ".$this->paging->start .",". $this->paging->per_page;		
		$elList = $this->db->select($sql);
		
		$this->set('list', $elList);
		$this->set('post', $info);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showemailaddressmanager');
	}
	
	/*
	 * function tto get all email sources
	 */
	function __getAllEmailSources($emailListId) {
		$sql = "select distinct(source) from nl_subscribers where email_list_id=$emailListId";
		$sourceList = $this->db->select($sql);
		return $sourceList;    
	}
	
	/*
	 * func to change status of email address
	 */ 
	function __changeStatusEmailAddress($emailId, $status, $col='status'){
		$emailId = intval($emailId);
		$sql = "update nl_subscribers set $col=".intval($status)." where id=$emailId";
		$this->db->query($sql);
	}
	
	/*
	 * function to delete email address
	 */
    function deleteEmailAddress($emailId) {
	    $emailId = intval($emailId);
	    
	    // delete email
		$sql = "delete from nl_subscribers where id=$emailId";
		$this->db->query($sql);
		
		// delete all sending logs
	    $sql = "delete from nl_sending_log where subscriber_id=".$emailId;
	    $this->db->query($sql);
	}

	/*
	 * func to edit email address
	 */ 
	function editEmailAddress($emailId, $listInfo=''){
		if(!empty($emailId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getEmailAddressInfo($emailId);
			}

			$elList = $this->__getAllEmailList(' and status=1');
    	    $this->set('elList', $elList);
			
			$this->set('post', $listInfo);
    		$this->set('sec', 'update');
    		$this->pluginRender('editemailaddress');
		}		
	}
	
	/*
	 * func to update email adddress
	 */
	function updateEmailAddress($listInfo){
		
		$userId = isLoggedIn();
		$errMsg['email'] = formatErrorMsg($this->validate->checkBlank($listInfo['email']));
		$errMsg['email_list_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['email_list_id']));		
		$emailId = intval($listInfo['id']);
		$emailListId = intval($listInfo['email_list_id']);
		
		if(!$this->validate->flagErr){
		    $listInfo['email'] = trim($listInfo['email']);
		    if (!$this->__checkEmailExists($emailListId, $listInfo['email'], $emailId)) {			    
    			$sql = "update nl_subscribers set
    					email_list_id=$emailListId,
    					email = '".addslashes($listInfo['email'])."',
    					name = '".addslashes($listInfo['name'])."'
    					where id=$emailId";
				$this->db->query($sql);				
				$this->showEmailAddressManager(array('email_list_id' => $emailListId));
    			exit;
		    } else {
		        $errMsg['email'] = formatErrorMsg($this->pluginText['emailexists']);    
		    }
		}
		$this->set('errMsg', $errMsg);
		$this->set('post', $listInfo);
		$this->editEmailAddress($emailId, $listInfo);
	}
	
	/*
	 * func to get email lis info
	 */
	function __getEmailAddressInfo($emailId) {		
		$sql = "select * from nl_subscribers where id=$emailId";
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
	/*
	 * func to generate subscribe code
	 */
	function showGenerateSubscribeCode($emailListId) {	    
	    $elList = $this->__getAllEmailList(' and status=1');
	    $this->set('elList', $elList);
	    
	    // if no email list found
	    if (empty($elList)) showErrorMsg("No email list found!");
	    
	    $this->set('emailListId', $emailListId);
	    $langController = New LanguageController();
		$this->set('langList', $langController->__getAllLanguages());
		$post['lang_code'] = $_SESSION['lang_code'];
		$post['width'] = $this->defaultWidth;
		$post['height'] = $this->defaultHeight;
		$post['bgcolor'] = '#E5E5E5';
		$this->set('post', $post);
	    $this->pluginRender('generatesubscribecode');    
	}
	
	/*
	 * func to generate subscribe code
	 */
	function generateSubscribeCode($data) {
	    $width = empty($data['width']) ? $this->defaultWidth : floatval($data['width']);
	    $height = empty($data['height']) ? $this->defaultHeight : floatval($data['height']);
	    $src = PLUGIN_WEBPATH . "/subscribemaillist.php?email_list_id=".$data['email_list_id']."&lang_code=".$data['lang_code']."&source=".$data['source'];
	    $src .= "&width=$width&height=$height&bgcolor=".urlencode($data['bgcolor']); 
	    $this->set('width', $width);
	    $this->set('height', $height);
	    $this->set('src', $src);
	    $this->pluginRender('showsubscribecode');
	}
	
	/*
	 * function to unsubscribe from email list
	 */
	function unsubscribeMailList($info) {
	    $email = addslashes(urldecode($info['email']));
	    $subscriberId = intval($info['subscriber_id']);
	    if (!empty($subscriberId) && !empty($email)) {
	        $sql = "Update nl_subscribers set subscribed=0 where id=$subscriberId and email='$email'";
	        $this->db->query($sql);     
	    }
	    echo "<font style='color:green;font-size:15px;'>Email address '$email' successfully unsubscribed from the list!</font>";
	}
	
	/*
     * function get all sending logs of newsletter
     */
    function getEmailSentLogs($newsletterEntryId) {
        $sql = "select subscriber_id from nl_sending_log where newsletter_id=$newsletterEntryId";
        $sentList = $this->db->select($sql);
        return $sentList;
    }
}
?>