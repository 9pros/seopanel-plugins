<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class PluginHelper extends Newsletter{
	
	/*
	 * func to get count of email sent for a newsletter
	 */
	function getNewsletterEmailSentCount($newsletterId, $sec='', $conditions="") {
	    
	    
	    $func = "count(id)";        	    
	    switch ($sec) {        
	        case "failed":
	            $conditions .= " and status=0";
	            break;
	                    
	        case "success":
	            $conditions .= " and status=1";
	            break;
	            	        
	        case "click_count":
	            $func = "sum(click_count)";
	            break;
	        
	        case "opened":
	            $conditions .= " and opened=1";
	            break;
	    }
	    
        $sql = "select $func count from  nl_sending_log	where newsletter_id=$newsletterId $conditions";
        $countInfo = $this->db->select($sql, true);
        $count = empty($countInfo['count']) ? 0 : $countInfo['count'];
        return $count; 
	}
	
	/*
	 * func to show email list subscribtion
	 */
    function showSubscribeForm($info) {
        $emailListId = intval($info['email_list_id']);
        if (!empty($emailListId)) {	         
    		$this->set('post', $info);		
    		$this->pluginRender('showsubscribeform');
        }    
    }
    
    /*
     * function to process subscribe form 
     */
    function processSubscribeForm($info) {    
        $emailAddress = urldecode($info['subscribe_email']);
        $errMsg = $this->validate->checkEmail($emailAddress);        
        if ($this->validate->flagErr) {
            echo "<font class='error'>$errMsg<font>";
        } else {
		    $elhelper = $this->createHelper('EmailList');
		    $emailListId = intval($info['email_list_id']);
            if (!$emailId = $elhelper->__checkEmailExists($emailListId, $emailAddress)) {
                $source = empty($info['source']) ? "manual" : $info['source'];
                $emailName = empty($info['name']) ? "" : $info['name'];                   
                $elhelper->createEmailAdress($emailListId, $emailAddress, $emailName, $source);
                ?>
                <font style='color: green;'><?=$this->pluginText['successsubscribemsg']?><font>
                <script>$('subscribe_email').value='';</script>
                <?php
            } else {
                ?>
                <font class='error'><?=$this->pluginText['emailexists']?><font>
                <?php        
            }
        }
    }
    
    /*
     * function to show reports manager
     */
    function showReportsManager($info) {
        
        $userId = isLoggedIn();
        $spText = $_SESSION['text'];
        $nlHelper = $this->createHelper('NewsletterEntry');        
        $conditions = isAdmin() ? "" : " and w.user_id=$userId";
		$campaignList = $nlHelper->getAllCampaigns($conditions);
		$this->set('campaignList', $campaignList);
        
        $campaignId = false;
        $newsletterId = false;
        if (!empty($info['newsletter_id'])) {
            $newsletterId = intval($info['newsletter_id']);
            $nlInfo = $nlHelper->__getNewsletterEntryInfo($newsletterId);
            $campaignId = $nlInfo['campaign_id'];
        } else {
            if (empty($info['campaign_id']) ) {
    		    $campaignId = $campaignList[0]['id'];    
    		} else {
    		    $campaignId = intval($info['campaign_id']);
    		}
        }
	    $this->set('campaignId', $campaignId);
	    
	    // if no campaigns found
	    if (empty($campaignId)) showErrorMsg("No campaigns found!");
	           
        $nlList = $nlHelper->getAllNewsletters(' and status=1 and campaign_id='.$campaignId);
		$this->set('nlList', $nlList);
		if (empty($newsletterId)) {
		    if (empty($info['newsletter_id']) ) {
    		    $newsletterId = $nlList[0]['id'];    
    		} else {
    		    $newsletterId = intval($info['newsletter_id']);
    		}
		}
		$this->set('newsletterId', $newsletterId);
		
		// if no newsletter found
	    if (empty($newsletterId)) showErrorMsg("No newsletter found!");		
		
		if (!empty ($info['from_time'])) {
			$fromTime = strtotime($info['from_time'] . ' 00:00:00');
		} else {
			$fromTime = SP_DEMO ? mktime(0, 0, 0, 1, 1, 2012) : mktime(0, 0, 0, date('m'), date('d') - 30, date('Y'));
		}
		if (!empty ($info['to_time'])) {
			$toTime = strtotime($info['to_time'] . ' 23:59:59');
		} else {
			$toTime = mktime();
		}
		$this->set('fromTime', date('Y-m-d', $fromTime));
		$this->set('toTime', date('Y-m-d', $toTime));
		
		// siteauditor language texts
        $this->spTextSA = $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']);
        $this->set("spTextSA", $this->spTextSA);
        
		$reportTypeList = array(
		    'report_summary' => $this->spTextSA["Report Summary"],
		    'report_daily' => "Daily Reports",
		    'report_graph' => "Graphical Reports",
		    'report_email' => "Email Reports",
		);
		$this->set('reportTypeList', $reportTypeList);
		$reportType = empty($info['report_type']) ? 'report_daily' : $info['report_type'];
		$this->set("reportType", $reportType);
		
		$statusList = array(
            'success' => $this->pluginText['Success'],
            'failed' => ucfirst($spText['common']['failed']),
            'opened' => $this->pluginText['Opened'],
            'clicked' => $this->pluginText['Clicked'],
        );
        $this->set('statusList', $statusList);
        $sentStatus = empty($info['sent_status']) ? '' : $info['sent_status'];
        $this->set("sentStatus", $sentStatus);
		
        $fromTime = date('Y-m-d H:i:s', $fromTime);
        $toTime = date('Y-m-d H:i:s', $toTime);
		switch ($reportType) {
		    case "report_summary":
		        $this->getReportSummary($newsletterId, $fromTime, $toTime);
		        break;
		    
		    case "report_daily":
		        $this->getDailyReport($newsletterId, $fromTime, $toTime);
		        break;
		    
		    case "report_graph":
		        $this->getGraphicalReport($newsletterId, $fromTime, $toTime);
		        break;
		    
		    case "report_email":
		        $this->getEmailReport($newsletterId, $fromTime, $toTime, $sentStatus, $info);
		        break;
		}
		
        $submitLink = pluginPOSTMethod('listform', 'content', 'action=reportmanager');
        $this->set('submitLink', $submitLink);
        $this->set('onChange', $submitLink);
		$this->pluginRender('showreportmanager');
    }

    /*
     * function to get summary of newsletter sending report
     */
    function getReportSummary($newsletterId, $fromTime, $toTime) {
		$elCtrler = $this->createHelper('EmailList');        
        $listInfo['total'] = $elCtrler->getCountNewsletterEmails($newsletterId);
        $conditions = " and sent_time>='$fromTime' and sent_time<='$toTime'";
	    $listInfo['sent'] = $this->getNewsletterEmailSentCount($newsletterId, '', $conditions);
	    $listInfo['success'] = $this->getNewsletterEmailSentCount($newsletterId, 'success', $conditions);
	    $listInfo['failed'] = $this->getNewsletterEmailSentCount($newsletterId, 'failed', $conditions);			    
	    $listInfo['opened'] = $this->getNewsletterEmailSentCount($newsletterId, 'opened', $conditions);
	    $listInfo['click_count'] = $this->getNewsletterEmailSentCount($newsletterId, 'click_count', $conditions);
	    $this->set("listInfo", $listInfo); 
    }
    
    /*
     * function to show daily reports
     */
    function getDailyReport($newsletterId, $fromTime, $toTime) {
        $sql = "select DATE(sent_time) day, count(*) sent, sum(status) success, sum(opened) opened, sum(click_count) click_count 
        		from  nl_sending_log 
        		where newsletter_id=$newsletterId and sent_time>='$fromTime' and sent_time<='$toTime'
        		group by DATE(sent_time)
        		order by sent_time DESC";
        $list = $this->db->select($sql);
        $this->set('list', $list);
    }
    
    /*
     * function to show the graphical reports of daily reports
     */
    function getGraphicalReport($newsletterId, $fromTime, $toTime) {
        $sql = "select DATE(sent_time) day, count(*) sent, sum(status) success, sum(opened) opened, sum(click_count) click_count 
        		from  nl_sending_log 
        		where newsletter_id=$newsletterId and sent_time>='$fromTime' and sent_time<='$toTime'
        		group by DATE(sent_time)
        		order by sent_time DESC";
        $reportList = $this->db->select($sql);
        
        $spText = $_SESSION['text'];
        $colList = array(
            'sent' => $this->pluginText['Sent'],
            'success' => $this->pluginText['Success'],
            'failed' => ucfirst($spText['common']['failed']),
            'opened' => $this->pluginText['Opened'],
            'click_count' => $this->pluginText['Clicked'],
        );
        
        $dataList = array();
		$maxValue = 0;
		foreach($reportList as $repInfo){
		    foreach ($colList as $col => $val) {
		        if ($col == 'failed') $repInfo[$col] = $repInfo['sent'] - $repInfo['success'];
		        $dataList[$repInfo['day']][$col] = $repInfo[$col];
		        $maxValue = ($repInfo[$col] > $maxValue) ? $repInfo[$col] : $maxValue;    
		    }			
		}
		
		// check whether the records are available for drawing graph
		if(empty($dataList) || empty($maxValue)) {
		    $this->set('dataList', false);
		    return false;		    
		}
		$this->set('dataList', true);
		
		# Dataset definition
		$dataSet = new pData;
		foreach($dataList as $dataInfo){
			$i = 1;	
			foreach ($colList as $col => $val) {
				$val = empty($dataInfo[$col]) ? 0 : $dataInfo[$col];
				$dataSet->AddPoint($val, "Serie".$i++);				  
			}
		}
		
		$i = 1;
		foreach ($colList as $col => $val) {
			$dataSet->AddSerie("Serie$i");
			$dataSet->SetSerieName($val, "Serie$i");
			$i++;
		}
		
		$serieCount = count($seList) + 1;
		$dataSet->AddPoint(array_keys($dataList), "Serie$serieCount");
		$dataSet->SetAbsciseLabelSerie("Serie$serieCount");
		
		$dataSet->SetXAxisName("Date");		
		$dataSet->SetYAxisName("Count");
		$dataSet->SetXAxisFormat("date");		

		# Initialise the graph
		$chart = new pChart(720, 520);
		$chart->setFixedScale(0, $maxValue);		
		$chart->setFontProperties("fonts/tahoma.ttf", 8);
		$chart->setGraphArea(85, 30, 670, 425);
		$chart->drawFilledRoundedRectangle(7, 7, 713, 513, 5, 240, 240, 240);
		$chart->drawRoundedRectangle(5, 5, 715, 515, 5, 230, 230, 230);

		$chart->drawGraphArea(255, 255, 255, TRUE);
		$chart->drawScale($dataSet->GetData(), $dataSet->GetDataDescription(), SCALE_NORMAL, 150, 150, 150, TRUE, 90, 2);
		$chart->drawGrid(4, TRUE, 230, 230, 230, 50);

		# Draw the 0 line   
		$chart->setFontProperties("fonts/tahoma.ttf", 6);
		$chart->drawTreshold(0, 143, 55, 72, TRUE, TRUE);

		# Draw the line graph
		$chart->drawLineGraph($dataSet->GetData(), $dataSet->GetDataDescription());
		$chart->drawPlotGraph($dataSet->GetData(), $dataSet->GetDataDescription(), 3, 2, 255, 255, 255);
		
		$j = 1;
		$chart->setFontProperties("fonts/tahoma.ttf", 10);
		foreach ($colList as $col => $val) {
			$chart->writeValues($dataSet->GetData(), $dataSet->GetDataDescription(), "Serie".$j++);
		}

		# Finish the graph
		$chart->setFontProperties("fonts/tahoma.ttf", 8);
		$chart->drawLegend(90, 35, $dataSet->GetDataDescription(), 255, 255, 255);
		$chart->setFontProperties("fonts/tahoma.ttf", 10);
		$chart->drawTitle(60, 22, "Newsletter Reports", 50, 50, 50, 585);
		
		$imgFile = "newsletterreport.png";
		$this->set("imgFile", $imgFile);
		$chart->render(SP_TMPPATH."/".$imgFile);
    }
    
    /*
     * function to show email reports of news letter
     */
    function getEmailReport($newsletterId, $fromTime, $toTime, $sentStatus='', $info='') {
        $pgScriptPath = PLUGIN_SCRIPT_URL . "&action=reportmanager&report_type=report_email&newsletter_id=$newsletterId";
        $pgScriptPath .= "&from_time=".date('Y-m-d', strtotime($fromTime))."&to_time=".date('Y-m-d', strtotime($toTime))."&sent_status=$sentStatus";
        $sql = "select l.*,s.email email from  nl_sending_log l, nl_subscribers s where s.id=l.subscriber_id and newsletter_id=$newsletterId and sent_time>='$fromTime' and sent_time<='$toTime'";
        switch ($sentStatus) {
            case "success":
                $sql .= " and l.status=1";
                break;
                
            case "failed":
                $sql .= " and l.status=0";
                break;
                
            case "opened":
                $sql .= " and opened=1";
                break;
                
            case "clicked":
                $sql .= " and click_count>0";
                break;
                
        }
        
        // to create backward link
        if (!empty($info['back_report_type'])) {
		    $backLink = PLUGIN_SCRIPT_URL . "&action=reportmanager&report_type={$info['back_report_type']}&newsletter_id=$newsletterId";
		    $backLink .= "&from_time={$info['back_from_time']}&to_time={$info['back_to_time']}";
		    $pgScriptPath .= "&back_report_type={$info['back_report_type']}&back_from_time={$info['back_from_time']}&back_to_time={$info['back_to_time']}";
		    $this->set('backLink', $backLink);
		}
        
        // pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " order by sent_time DESC limit ".$this->paging->start .",". $this->paging->per_page;		
		$elList = $this->db->select($sql);		
		$this->set('list', $elList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);
    }
    
}
?>