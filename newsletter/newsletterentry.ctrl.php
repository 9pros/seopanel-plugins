<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */
include_once(PLUGIN_PATH."/newslettercampaign.ctrl.php"); 
class NewsletterEntry extends NewsletterCampaign {

    var $dbTableName = "nl_entry_list";    // the database table name of the newsletter entry list
    var $sentCount = 0;                    // variable to determine how many emails sent
    
	/*
	 * show newsletter entry list to manage
	 */
	function showNewsletterEntryManager($info='') {
		
		$userId = isLoggedIn();
		$pgScriptPath = PLUGIN_SCRIPT_URL . "&action=newslettermanager";
		$conditions = isAdmin() ? "" : " and w.user_id=$userId";
		$campaignList = $this->getAllCampaigns($conditions);
		$this->set('campaignList', $campaignList);
		if (empty($info['campaign_id']) ) {
		    $campaignId = $campaignList[0]['id'];    
		} else {
		    $campaignId = intval($info['campaign_id']);
		    $pgScriptPath .= "&campaign_id=".$campaignId;
		}
	    $this->set('campaignId', $campaignId);
		
	    // if no campaigns found
	    if (empty($campaignId)) showErrorMsg("No campaigns found!");
	    
		$sql = "select nl.*,date(start_date) start_date,date(end_date) end_date from $this->dbTableName nl,$this->tableName cmp where nl.campaign_id=cmp.id and nl.campaign_id=$campaignId";
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " order by start_date limit ".$this->paging->start .",". $this->paging->per_page;		
		$newsletterList = $this->db->select($sql);
		
		$elCtrler = $this->createHelper('EmailList');
		$helperCtrler = $this->createHelper('PluginHelper');
		foreach ($newsletterList as $i => $nlInfo) {
		    $nlInfo['total'] = $elCtrler->getCountNewsletterEmails($nlInfo['id']);
		    $nlInfo['sent'] = $helperCtrler->getNewsletterEmailSentCount($nlInfo['id']);
		    $nlInfo['failed'] = $helperCtrler->getNewsletterEmailSentCount($nlInfo['id'], 'failed');			    
		    $nlInfo['opened'] = $helperCtrler->getNewsletterEmailSentCount($nlInfo['id'], 'opened');
		    $nlInfo['click_count'] = $helperCtrler->getNewsletterEmailSentCount($nlInfo['id'], 'click_count'); 
	        $newsletterList[$i] = $nlInfo;        
		}
		
		$this->set('list', $newsletterList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('shownewslettermanager');
	}

	/*
	 * func to create new newsletter entry
	 */ 
	function newNewsletterEntry($info=''){
						
		$userId = isLoggedIn();		
		$conditions = isAdmin() ? "" : " and w.user_id=$userId";
		$campaignList = $this->getAllCampaigns($conditions);
		$this->set('campaignList', $campaignList);
		
		$campaignId = empty($info['campaign_id']) ? $campaignList[0]['id'] : intval($info['campaign_id']);
	    $this->set('campaignId', $campaignId);
		$this->set('sec', 'create');
		
		$elCtrler = $this->createHelper('EmailList');
		$userEmailList = $elCtrler->getAllEmailLists($userId);
		$this->set('userEmailList', $userEmailList);
		
		$this->set('post', $info);		
		$this->pluginRender('editnewsletter');
	}
	
	/*
	 * function to check whether newsletter already exists or not
	 */
    function __checkNewsletterName($name, $campaignId, $newsletterId=false){
		
		$sql = "select id from $this->dbTableName where name='".addslashes($name)."' and campaign_id=$campaignId";
		$sql .= $newsletterId ? " and id!=$newsletterId" : "";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	/*
	 * func to create campaign
	 */
	function createNewsletter($listInfo){
	    
		$userId = isLoggedIn();		
		$errMsg['campaign_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['campaign_id']));
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$errMsg['subject'] = formatErrorMsg($this->validate->checkBlank($listInfo['subject']));
		$errMsg['mail_content'] = formatErrorMsg($this->validate->checkBlank($listInfo['mail_content']));
		
		if (count($listInfo['email_list']) <= 0) {
		    $errMsg['email_list'] = formatErrorMsg("Please select atleast one email list");
			$this->validate->flagErr = true;
		}
		
		$currDate = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	    if (empty($listInfo['start_date']) || (strtotime($listInfo['start_date']) < $currDate) ) {
		    $errMsg['start_date'] = formatErrorMsg("Please select a valid start date");
			$this->validate->flagErr = true;
		}
		$listInfo['start_date'] = $listInfo['start_date']. " 00:00:00";
		
		$listInfo['end_date'] = $listInfo['end_date']. " 23:59:59";
	    if (empty($listInfo['end_date']) || (strtotime($listInfo['end_date']) < strtotime($listInfo['start_date'])) ) {
		    $errMsg['end_date'] = formatErrorMsg("Please select a valid end date greater or equal to start date.");
			$this->validate->flagErr = true;
		}
		
	    if ( !empty($listInfo['track_clicks']) && stristr(SP_WEBPATH, 'http://localhost/')) {
		    $errMsg['track_clicks'] = formatErrorMsg("Click tracking will not work in seo panel installed on local server");
			$this->validate->flagErr = true;
		}
				
		if(!$this->validate->flagErr){		    
		    if (!$this->__checkNewsletterName($listInfo['name'], $listInfo['campaign_id'])) {		    
    		    $htmlMail = empty($listInfo['html_mail']) ? 0 : 1;		    
    		    $clickTracking = empty($listInfo['track_clicks']) ? 0 : 1;  
    		    $cron = empty($listInfo['cron']) ? 0 : 1;	    
    		    $openTracking = empty($listInfo['open_tracking']) ? 0 : 1;		    
    		    $unsubscribeOption = empty($listInfo['unsubscribe_option']) ? 0 : 1;
		        $sql = "insert into $this->dbTableName(campaign_id,name,subject,mail_content,html_mail,
		        		track_clicks,open_tracking,unsubscribe_option,cron,status,start_date,end_date)
    					values(".intval($listInfo['campaign_id']).",'".addslashes($listInfo['name'])."','".addslashes($listInfo['subject'])."',
    					'".addslashes($listInfo['mail_content'])."',$htmlMail,$clickTracking,$openTracking,
		                $unsubscribeOption,$cron,1,'".addslashes($listInfo['start_date'])."','".addslashes($listInfo['end_date'])."')";
    			$this->db->query($sql);    			
    			
    			if ($newsletterId = $this->db->getMaxId($this->dbTableName) ) {
    			    $elCtrler = $this->createHelper('EmailList');
    			    $userEmailList = $elCtrler->updateEmailListmapping($newsletterId, $listInfo['email_list']);    
    			}
    			
    			$this->showNewsletterEntryManager(array('campaign_id' => $listInfo['campaign_id']));
    			exit;
		    } else {
		        $errMsg['name'] = formatErrorMsg($this->pluginText['newsletterexists']);
		    }
		}
				
		$this->set('errMsg', $errMsg);
		if(!isset($listInfo['html_mail'])) $listInfo['html_mail'] = 0;
		if(!isset($listInfo['open_tracking'])) $listInfo['open_tracking'] = 0;
		if(!isset($listInfo['unsubscribe_option'])) $listInfo['unsubscribe_option'] = 0;
		$this->newNewsletterEntry($listInfo);
	}	


	/*
	 * func to edit newletter entry
	 */ 
	function editNewsletterEntry($newsletterId, $listInfo=''){
		
	    $userId = isLoggedIn();
		if(!empty($newsletterId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getNewsletterEntryInfo($newsletterId);
			}
			
			$conditions = isAdmin() ? "" : " and w.user_id=$userId";
		    $campaignList = $this->getAllCampaigns($conditions);
		    $this->set('campaignList', $campaignList);		
		    $campaignId = empty($listInfo['campaign_id']) ? $campaignList[0]['id'] : intval($listInfo['campaign_id']);
	        $this->set('campaignId', $campaignId);
		
    		$elCtrler = $this->createHelper('EmailList');
    		$userEmailList = $elCtrler->getAllEmailLists($userId);
    		$this->set('userEmailList', $userEmailList);
    		
    		$listInfo['email_list'] = $elCtrler->getNewsletterEmailLists($newsletterId, $userId);
    		
			$this->set('post', $listInfo);
    		$this->set('sec', 'update');
    		$this->pluginRender('editnewsletter');
			exit;
		}		
	}
	
	/*
	 * func to update newsletter
	 */
	function updateNewsletter($listInfo){
		
		$userId = isLoggedIn();
		$errMsg['campaign_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['campaign_id']));
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$errMsg['subject'] = formatErrorMsg($this->validate->checkBlank($listInfo['subject']));
		$errMsg['mail_content'] = formatErrorMsg($this->validate->checkBlank($listInfo['mail_content']));
		
		if (count($listInfo['email_list']) <= 0) {
		    $errMsg['email_list'] = formatErrorMsg("Please select atleast one email list");
			$this->validate->flagErr = true;
		}		
		$newsletterId = intval($listInfo['id']);
		
	    if (empty($listInfo['start_date'])) {
		    $errMsg['start_date'] = formatErrorMsg("Please select a valid start date");
			$this->validate->flagErr = true;
		}
		$listInfo['start_date'] = $listInfo['start_date']. " 00:00:00";
		
		$listInfo['end_date'] = $listInfo['end_date']. " 23:59:59";
	    if (empty($listInfo['end_date']) || (strtotime($listInfo['end_date']) < strtotime($listInfo['start_date'])) ) {
		    $errMsg['end_date'] = formatErrorMsg("Please select a valid end date greater or equal to start date.");
			$this->validate->flagErr = true;
		}
		
	    if ( !empty($listInfo['track_clicks']) && stristr(SP_WEBPATH, 'http://localhost/')) {
		    $errMsg['track_clicks'] = formatErrorMsg("Click tracking will not work in seo panel installed on local server");
			$this->validate->flagErr = true;
		}
		
		if(!$this->validate->flagErr){
		    if (!$this->__checkNewsletterName($listInfo['name'], $listInfo['campaign_id'], $newsletterId)) {		        		    
    		    $htmlMail = empty($listInfo['html_mail']) ? 0 : 1;		    
    		    $clickTracking = empty($listInfo['track_clicks']) ? 0 : 1;  
    		    $cron = empty($listInfo['cron']) ? 0 : 1;	    
    		    $openTracking = empty($listInfo['open_tracking']) ? 0 : 1;		    
    		    $unsubscribeOption = empty($listInfo['unsubscribe_option']) ? 0 : 1;		    
    			$sql = "update $this->dbTableName set
    					campaign_id = '".intval($listInfo['campaign_id'])."',
    					name = '".addslashes($listInfo['name'])."',
    					subject = '".addslashes($listInfo['subject'])."',
    					start_date = '".addslashes($listInfo['start_date'])."',
    					end_date = '".addslashes($listInfo['end_date'])."',
    					mail_content = '".addslashes($listInfo['mail_content'])."',
    					html_mail = $htmlMail,
    					track_clicks = $clickTracking,
    					cron = $cron,
    					open_tracking = $openTracking,
    					unsubscribe_option = $unsubscribeOption
    					where id=$newsletterId";
				$this->db->query($sql);
        		
    			$elCtrler = $this->createHelper('EmailList');
			    $userEmailList = $elCtrler->updateEmailListmapping($newsletterId, $listInfo['email_list']);		
				
				$this->showNewsletterEntryManager($listInfo);
    			exit;
		    } else {
		        $errMsg['name'] = formatErrorMsg($this->pluginText['newsletterexists']);    
		    }
		}
		$this->set('errMsg', $errMsg);
		
		if(!isset($listInfo['html_mail'])) $listInfo['html_mail'] = 0;
		if(!isset($listInfo['open_tracking'])) $listInfo['open_tracking'] = 0;
		if(!isset($listInfo['unsubscribe_option'])) $listInfo['unsubscribe_option'] = 0;
		$this->set('post', $listInfo);
		
		$this->editNewsletterEntry($listInfo['id'], $listInfo);
	}
	
	/*
	 * func to delete newsletter
	 */
	function deleteNewsletter($newsletterId) {
	    $newsletterId = intval($newsletterId);

	    // delete newsletter
		$sql = "delete from $this->dbTableName where id=$newsletterId";
		$this->db->query($sql);
		
		// delete newsletter email mapping
		$elCtrler = $this->createHelper('EmailList');
		$elCtrler->deleteEmailListMapping($newsletterId);
		
		// delete newsletter sending logs
		$sql = "delete from nl_sending_log where newsletter_id=$newsletterId";
		$this->db->query($sql);
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatusNewsletter($newsletterId, $status){
		$newsletterId = intval($newsletterId);
		$sql = "update $this->dbTableName set status=$status where id=$newsletterId";
		$this->db->query($sql);		
	}

	/*
	 * func to get newsletter info
	 */
	function __getNewsletterEntryInfo($newsletterId) {		
		$sql = "select nl.*,cmp.campaign_name from $this->dbTableName nl,$this->tableName cmp where nl.campaign_id=cmp.id and nl.id=$newsletterId";
		$info = $this->db->select($sql, true);
		return $info;		
	}

	/*
	 * func to get all newsletters
	 */
	function getAllNewsletters($condtions='') {
		
		$sql = "select * from $this->dbTableName where 1=1";
		$sql .= empty($condtions) ? "" : $condtions;
		$nlList = $this->db->select($sql);
		return $nlList;		
	}
		
	/*
	 * func to show newsletter select box
	 */
    function showNewsletterSelectBox($campaignId) {
        $nlList = $this->getAllNewsletters(' and status=1 and campaign_id='.intval($campaignId));
		$this->set('nlList', $nlList);
        $this->pluginRender('newsletterselectbox');
    }
	
	/*
	 * function to format newletter content add click tracking and un-subscribe option 
	 */
	function formatNewsletterContent($nlInfo, $subscriberInfo) {

	    $newsletterId = $nlInfo['id'];
	    $content = stripslashes($nlInfo['mail_content']);
	    
	    // replace common variables
	    $name = empty($subscriberInfo['name']) ? $this->pluginText[NL_COMMON_NAME] : $subscriberInfo['name'];
	    $content = str_ireplace('[name]', $name, $content);
	    $content = str_ireplace('[email]', $subscriberInfo['email'], $content);    
	    
	    // add click tracking vars to links
	    if ($nlInfo['track_clicks'] && $nlInfo['html_mail']) {
	        $content = $this->addClickTracking($content, $newsletterId, $subscriberInfo['id']);
	    }
	    
	    // add unsubscribe option
	    if ($nlInfo['unsubscribe_option']) {
	        $content .= $this->addUnsubscribeOption($subscriberInfo, $nlInfo['html_mail']);
	    }
	    
	    // add open tracking option
	    if ($nlInfo['open_tracking'] && $nlInfo['html_mail']) {
            $content .= $this->addOpenTracking($newsletterId, $subscriberInfo['id']);
	    }
	    
	    return $content;
	}
	
	/*
	 * function to add click tracking code to urls of the email content
	 */
	function addClickTracking($content, $newsletterId, $subscriberId) {
        $clickTrackLink = PLUGIN_WEBPATH . "/trackclicks.php?subscriber_id=".$subscriberId."&newsletter_id=".$newsletterId."&doc_type=export&link=";
        if (preg_match_all('/ href="(.*?)"/is', $content, $matches)) {
            foreach ($matches[1] as $link) {
                if (!empty($link) && !stristr($link, SP_WEBPATH)) {
                    if (stristr($link, 'http://') || stristr($link, 'https://')) {
                        $trackLink = $clickTrackLink . urlencode($link);
                        $content = str_ireplace(' href="'.$link.'"', ' href="'.$trackLink.'"', $content);    
                    }    
                }
            }
        }
        return $content;
	}
	
	/*
	 * function to add unsubscribe option to email link
	 */
	function addUnsubscribeOption($subscriberInfo, $htmlMail) {
	    $unsubscribeLink = PLUGIN_WEBPATH . "/unsubscribemaillist.php?subscriber_id=".$subscriberInfo['id']."&email=".urlencode($subscriberInfo['email']);
	    $clickHereText = $_SESSION['text']['label']['Click Here'];
	    $addtoListText = $this->pluginText['to unsubscribe from email list'];
	    
	    ob_start();
		include(PLUGIN_VIEWPATH."/unsubscribe.ctp.php");
		$unsubscribeCode = ob_get_contents();
		ob_end_clean();
		
	    return $unsubscribeCode;
	}

	/*
	 * function to add email open tracking code to the email content
	 */
	function addOpenTracking($newsletterId, $subscriberId) {
	    $openTrackLink = PLUGIN_WEBPATH . "/opentracking.php?subscriber_id=".$subscriberId."&newsletter_id=".$newsletterId;
	    $trackingCode = '<p><img src="'.$openTrackLink.'" width="0" height="0"></p>';
	    return $trackingCode;
	}
	
	/*
	 * function to track the email opened or not
	 */
	function updateEmailOpenTracking($info='') {
	    $newsletterId = intval($info['newsletter_id']);
	    $subscriberId = intval($info['subscriber_id']);
	    if (!empty($newsletterId) && !empty($subscriberId)) {
	        $sql = "Update nl_sending_log set opened=1 where newsletter_id=$newsletterId and subscriber_id=$subscriberId";
	        $this->db->query($sql);    
	    }  
	}
	
	/*
	 * function to track the email links clicked
	 */
	function updateEmailClickTracking($info='') {
	    $newsletterId = intval($info['newsletter_id']);
	    $subscriberId = intval($info['subscriber_id']);
	    if (!empty($newsletterId) && !empty($subscriberId)) {
	        $sql = "Update nl_sending_log set click_count=click_count+1 where newsletter_id=$newsletterId and subscriber_id=$subscriberId";
	        $this->db->query($sql);    
	    }  
	}
	
	/*
	 * function to load php mailer
	 */
	function loadPHPMailer($campaignInfo, $nlInfo) {
	    // check whether already class exists
        if (!class_exists('PHPMailer')) {
            include_once(PLUGIN_PATH."/libs/phpmailer.class.php");    
        }
        
    	$mail = new PHPMailer();
    	
    	// if smtp mail enabled
    	if ($campaignInfo['is_smtp']) {
    	    $mail->IsSMTP();
    	    $mail->SMTPAuth = true;
    	    $mail->Host = $campaignInfo['smtp_host'];    
    	    $mail->Username = $campaignInfo['smtp_username'];
    	    $mail->Password = $campaignInfo['smtp_password'];    
    	} else {
    	    $mail->IsMail();
    	}
    	
    	// set identities
    	$mail->From = $campaignInfo['from_email'];
    	$mail->FromName = $campaignInfo['from_name'];
    	$mail->AddReplyTo($campaignInfo['reply_to']);
    	
    	// set content features
    	if($nlInfo['html_mail']) $mail->IsHTML(true);
    	$mail->WordWrap = NL_CONTENT_WORDWRAP;
    	
    	// set language for mailer
    	$mail->SetLanguage("en", PLUGIN_PATH . "/libs/language/");

    	return $mail;
	}
	
	/*
	 * function to send newsletetr to the subscribers
	 */
    function sendNewsletter($mail, $newsletterInfo, $subscriberInfo, $testMail=false) {
    	if (!empty($newsletterInfo['content'])) {
    	    $mail->ClearAddresses();
    	    $mail->AddAddress($subscriberInfo['email'], $subscriberInfo['name']);
    	    
    	    $mail->Subject = $newsletterInfo['subject'];
    	    $mail->Body = $newsletterInfo['content'];
    	    
    	    $sendLog['newsletter_id'] = $newsletterInfo['id'];
            $sendLog['subscriber_id'] = $subscriberInfo['id'];
            
            // if sendgrid api should be used
            if ($mail->Host == 'smtp.sendgrid.net') {
            	$sendLog = $this->sendMailBySendgridAPI($mail, $subscriberInfo['email'], $subscriberInfo['name']);
            } else {
            
	        	if($mail->Send()){
	        	    $sendLog['status'] = 1;
	        		$sendLog['log_message'] = "Success";
	        	} else {
	        	    $sendLog['status'] = 0;
	        		$sendLog['log_message'] = $mail->ErrorInfo;
	        	}
            }
        	
    	} else {
    	    $sendLog['status'] = 0;
    		$sendLog['log_message'] = "Newletter content is empty!";
    	}
    	return $sendLog;
    }
    
    
    function sendMailBySendgridAPI($mail, $subscriberEmail, $subscriberName = '') {
    	
    	include_once(PLUGIN_PATH."/libs/sendgrid-php/sendgrid-php.php");    	
    	$from = new SendGrid\Email($mail->FromName, $mail->From);
    	$subject = $mail->Subject;
    	$to = new SendGrid\Email($subscriberName, $subscriberEmail);
    	$content = new SendGrid\Content($mail->ContentType, $mail->Body);
    	$apiKey = $mail->Password;
    	$mail = new SendGrid\Mail($from, $subject, $to, $content);    	
    	$sg = new \SendGrid($apiKey);
    	$response = $sg->client->mail()->send()->post($mail);
    	
    	$statusCode = $response->statusCode();
    	if (in_array($statusCode, array("202", "200"))) {
			$sendLog['status'] = 1;
        	$sendLog['log_message'] = "Success";
    	} else {
			$sendLog['status'] = 0;
	        $sendLog['log_message'] = $response->body();
    	}
    	
    	return $sendLog;    	 
    	
    }
    

	/*
	 * func to show newsletter test mail form
	 */ 
	function showTestMailNewsletter($info='') {

	    $newsletterId = intval($info['newsletter_id']);
        $nlInfo = $this->__getNewsletterEntryInfo($newsletterId);
        $nlList = $this->getAllNewsletters(' and campaign_id='.$nlInfo['campaign_id']);
		$this->set('nlList', $nlList);
        $this->set('campaignId', $nlInfo['campaign_id']);
        $this->set('newsletterId', $newsletterId);
		
		$this->set('post', $info);		
		$this->pluginRender('testemail');
	}
	
	/*
	 * func to create campaign
	 */
	function sendNewsletterTestEmail($listInfo) {
	    
		$userId = isLoggedIn();		
		$errMsg['newsletter_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['newsletter_id']));
		$errMsg['email'] = formatErrorMsg($this->validate->checkEmail($listInfo['email']));
				
		if (!$this->validate->flagErr) {
		    echo "<script>$('email_error').innerHTML=''</script>";
            $newsletterId = intval($listInfo['newsletter_id']);
            $nlInfo = $this->__getNewsletterEntryInfo($newsletterId);
            $subscriberInfo = array('id' => 0, 'email' => $listInfo['email']);            
		    $content = $this->formatNewsletterContent($nlInfo, $subscriberInfo);
		    if (!empty($content)) {
		        $nlInfo['content'] = $content;
		        $campaignId = $nlInfo['campaign_id'];
		        $campaignCtrler = $this->createHelper('NewsletterCampaign');
		        $campaignInfo = $campaignCtrler->__getCampaignInfo($campaignId);
		        $mail = $this->loadPHPMailer($campaignInfo, $nlInfo);
		        $sendLog = $this->sendNewsletter($mail, $nlInfo, $subscriberInfo);
		        if ($sendLog['status'] == 1) {
        		    echo "<script>$('email').value=''</script>";    
        		    showSuccessMsg("Newsletter sent successfully to email address '{$listInfo['email']}'");
		        } else {
        		    showErrorMsg($sendLog['log_message'].". Newsletter sent to email address '{$listInfo['email']}' failed!.");
		        }    
		    }
		}
		echo "<script>$('email_error').innerHTML=\"{$errMsg['email']}\"</script>";
	}
	
	/*
	 * function to start cron job
	 */
	function startCronJob($info='') {
	    $sql = "select nl.* from nl_entry_list nl, nl_campaigns nc, websites w 
	    		where nl.campaign_id=nc.id and nc.website_id=w.id 
	    		and w.status=1 and nc.status=1 and nl.status=1 and cron=1 and mail_content!='' and subject!=''
	    		and start_date<'".date('Y-m-d H:i:s')."' and end_date>'".date('Y-m-d H:i:s')."' order by start_date ASC";
	    $nlList = $this->db->select($sql);	    
	    
	    $this->sentCount = 0;
	    foreach ($nlList as $nlInfo) {
	        $this->startSendingNewsletter($nlInfo, NL_MAX_EMAIL_SEND_PER_CRON, true);
	        if ( NL_MAX_EMAIL_SEND_PER_CRON && ($this->sentCount >= NL_MAX_EMAIL_SEND_PER_CRON) ) break;            
	    }
	    
	    if ($this->sentCount == 0) echo "Newsletter sending completed!"; 
	}
	
	/*
	 * function to execute newsletter
	 */
	function startSendingNewsletter($nlInfo, $maxSent=false, $cron=false) {
	    $newsletterId = $nlInfo['id'];
        $elCtrler = $this->createHelper('EmailList');
        $emailList = $elCtrler->getNewsletterEmailLists($newsletterId);        
        
        $campaignId = $nlInfo['campaign_id'];
        $campaignCtrler = $this->createHelper('NewsletterCampaign');
        $campaignInfo = $campaignCtrler->__getCampaignInfo($campaignId);
        
        // load mail object
        $mail = $this->loadPHPMailer($campaignInfo, $nlInfo);
        
        foreach ($emailList as $emailListId) {
            $sql = "select * from nl_subscribers where email_list_id=$emailListId and subscribed=1 and status=1";            
            $sentList = $elCtrler->getEmailSentLogs($newsletterId);
            if (!empty($sentList)) {
                $checkList = array();
                foreach ($sentList as $sentInfo) {
                    $checkList[] = $sentInfo['subscriber_id'];
                }                
                if (!empty($checkList)) $sql .= " and id not in(".implode(',', $checkList).")";
            }
            
            $subscriberList = $this->db->select($sql);
            foreach ($subscriberList as $subscriberInfo) {
                $content = $this->formatNewsletterContent($nlInfo, $subscriberInfo);
    		    if (!empty($content)) {
    		        $nlInfo['content'] = $content;
    		        $sendLog = $this->sendNewsletter($mail, $nlInfo, $subscriberInfo);
    		        if ($sendLog['status'] == 1) {
    		            $msg =  "Newsletter sent successfully to email address '{$subscriberInfo['email']}'";  
            		    if ($cron) {
            		        echo $msg."\n";
            		    } else {
            		        echo "<p class='success left'>$msg</p>";
            		    }
    		        } else {
    		            $sendLog['status'] = 0;
    		            $msg = $sendLog['log_message']." Newsletter sent to email address '{$subscriberInfo['email']}' failed!";
    		            if ($cron) {
            		        echo $msg."\n";
            		    } else {
    		                echo "<p class='error left'>$msg</p>";
            		    }
    		        }

    		        // update the sending log
    		        $sql = "insert into nl_sending_log(newsletter_id,subscriber_id,log_message,status)
    		        		values($newsletterId,{$subscriberInfo['id']},'".addslashes($sendLog['log_message'])."', {$sendLog['status']})";
    		        $this->db->query($sql);
    		        
    		        if ( $maxSent > 0 ) {
    		            $this->sentCount++;
    		            if ($this->sentCount >= $maxSent) return;
    		        }
    		    }
            }   
        }        
	}

	/*
	 * function to show sending newsletter
	 */
	function showSendNewsletter($info) {
	    $this->set('newsletterId', intval($info['newsletter_id']));
	    $this->pluginRender('showsendnewsletter');    
	}
		
	/*
	 * function to send particular newsletter
	 */
	function doSendNewsletter($info='') {
	    $newsletterId = intval($info['newsletter_id']);
	    $nlInfo = $this->__getNewsletterEntryInfo($newsletterId);
	    if (!empty($nlInfo['mail_content'])) {
	        $this->sentCount = 0;
	        $this->startSendingNewsletter($nlInfo, NL_EMAIL_SEND_PER_EXE);
	        if ($this->sentCount == 0) {
	            showSuccessMsg("Newsletter sending completed!");    
	        } else {
	            $msg = "Waiting for ".(NL_SEND_DELAY/1000)." seconds to send newsletter to next set of subscribers!";
	            ?>
	            <p class="bold left"><?=$msg?></p>
	            <script>
					setTimeout('scriptDoLoad(\'<?=PLUGIN_SCRIPT_URL?>&action=dosendnewsletter&newsletter_id=<?=$newsletterId?>\', \'subcontmed\')', <?=NL_SEND_DELAY?>);
				</script>
	            <?php    
	        }
	    } else {
	        showErrorMsg("Newsletter content is empty!");
	    }
	}	    
}
?>