<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Report extends LinkDiagnosis{
	
	var $bklimit = 10;
	var $limitadd = true;
	var $cronJob = false;

	/*
	 * show reports list to manage
	 */
	function showReportsManager($info='') {
		
		$userId = isLoggedIn();
		
		$cond = " and p.status=1";
		if(isAdmin()){								
			$this->set('isAdmin', 1);
		} else {
			$cond .= " and user_id=$userId";
			$this->set('isAdmin', 0);
		}
		
		$projectCtrler = $this->createHelper('Project');
		$projectList = $projectCtrler->getAllProjects($cond);
		$this->set('projectList', $projectList);
		
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=reports";
		$sql = "select r.*,p.name pname from ld_reports r,ld_projects p where r.project_id=p.id $cond";
		if(!empty($info['project_id']) ) {
			$this->set('projectId', $info['project_id']);
			$sql .= " and p.id=". intval($info['project_id']);
			$pgScriptPath .= "&project_id=".$info['project_id'];	
		}						
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by r.updated DESC limit ".$this->paging->start .",". $this->paging->per_page;
		
		$rList = $this->db->select($sql);
		$reportList =  array();
		foreach ($rList as $repInfo) {
			$repInfo['tot_backlinks'] = $this->__getCountOfReportInfo($repInfo['id']);
			$repInfo['tot_crawled'] = $this->__getCountOfReportInfo($repInfo['id'], " and crawled=1");
			$reportList[] = $repInfo;
		}
		
		$this->set('list', $reportList);
		$this->set('pageNo', $_GET['pageno']);

		$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		$this->pluginRender('showreportsmanager');
	}

	/*
	 * show import backlinks interface
	 */
	function showImportBacklinks($data = '') {
		
		$userId = isLoggedIn();		
		$cond = " and p.status=1";
		
		if(isAdmin()){								
			$this->set('isAdmin', 1);
		} else {
			$cond .= " and user_id=$userId";
			$this->set('isAdmin', 0);
		}
		
		$projectCtrler = $this->createHelper('Project');
		$projectList = $projectCtrler->getAllProjects($cond);
		$this->set('projectList', $projectList);
		
		if (empty($data['report_id'])) {
			$projectId = $projectList[0]['id'];
		} else {
			$reportId = $data['report_id'];
			$reportInfo = $this->__getReportInfo($reportId);
			$projectId = $reportInfo['project_id'];
		}
		
		$reportList = $this->__getAllReports($projectId);
		$reportId = empty($reportId) ? $reportList[0]['id'] : $reportId;
		$this->set('reportList', $reportList);		
		$this->set('projectId', $projectId);
		$this->set('reportId', $reportId);		
		$this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));
		$this->set('post', $data);

		$this->pluginRender('showimportbacklinks');
	}
	
	/**
	 * function to process backlinks from import form
	 */
	function doImportBacklinks($listInfo) {
		$userId = isLoggedIn();
		$errMsg['links'] = formatErrorMsg($this->validate->checkBlank($listInfo['links']));
		$errMsg['project_id'] = formatErrorMsg($this->validate->checkNumber($listInfo['project_id']));
		$errMsg['report_id'] = formatErrorMsg($this->validate->checkNumber($listInfo['report_id']));
		
		// check whether input values are correct or not
		if (!$this->validate->flagErr) {

			$projectId = intval($listInfo['project_id']);
			$reportId = intval($listInfo['report_id']);
			$totalIndexed = $this->__getCountOfReportInfo($reportId, '', 'ld_indexed');
			$reportInfo = $this->__getReportInfo($reportId);
			
			// if indexed count is none
			if (empty($totalIndexed)) {
				$matchInfo['url'] = preg_match('/\/$/', $reportInfo['url']) ? $reportInfo['url'] : $reportInfo['url'] ."/";
				$this->saveIndexedPages($reportId, $matchInfo, false);
			}
			
			$indexedList = $this->__getAllIndexedPages($reportId);
			$indexId = $indexedList[0]['id'];
			
			// if empty index id show error
			if (empty($indexId)) {
				$errMsg['links'] = formatErrorMsg($_SESSION['text']['common']['failed']);
			} else {
				
				$validLinks = array(); 
				$links = explode(",", $listInfo['links']);
				$linkList = array(
					'invalid' => 0,
					'valid' => 0,
					'duplicate' => 0,
				);
				
				// lopp through links
				foreach ($links as $i => $link) {
					$link = Spider::formatUrl(trim($link));
					if (empty($link)) {
						$linkList['invalid'] += 1;
						continue;
					}
					
					$validLinks[] = $link;					
					
				}
				
				$totBacklinks = $this->__getTotalBacklinks($reportId);
				$totBacklinks += count($validLinks);
				
				// if count of valid links is greater than reports maximum links
				if ($totBacklinks > $reportInfo['max_links']) {
					$msgErr = str_replace(array('[TOTAL_LINKS]', '[MAX_LINKS]'), array("<b>$totBacklinks</b>", "<b>{$reportInfo['max_links']}</b>"), $this->pluginText['total_link_max_link_error']);
					$errMsg['links'] = formatErrorMsg($msgErr);
					$this->set('errMsg', $errMsg);
					$this->showImportBacklinks($listInfo);
					exit;
				}
				
				// loop through the valid links
				foreach ($validLinks as $link) {
					
					// save valid links
					if ($this->saveMatchedBacklink(array('url' => $link), $indexId, $reportId)) {
						$linkList['valid'] += 1;
					} else {
						$linkList['duplicate'] += 1;
					}
					
				}
				
				$this->set('linkList', $linkList);
				
				// update project status if valid links found
				if ($linkList['valid'] > 0) {
					$this->changeReportStatus($reportId, 1);
					$this->updateReportTime($reportId);
				}
				
			}
			
		}
		
		$this->set('errMsg', $errMsg);
		$this->showImportBacklinks($listInfo);
		
	}
	
	/*
	 * func to create new report
	 */ 
	function newReport($info=''){
						
		$userId = isLoggedIn();
										
		# get all projects		
		$projectCtrler = $this->createHelper('Project');
		$cond = " and p.status=1";
		
		if(isAdmin()){								
			$this->set('isAdmin', 1);
		} else {
			$cond .= " and user_id=$userId";
			$this->set('isAdmin', 0);
		}
		
		$projectList = $projectCtrler->getAllProjects($cond);
		$this->set('projectList', $projectList);
		$info['project_id'] = !empty($info['project_id']) ? intval($info['project_id']) : $projectList[0]['id'];
		$this->set('projectId', $info['project_id']);
		
		// find available backlink count
		$projectInfo = $projectCtrler->__getProjectInfo($info['project_id']);
		$pluginUserTypeObj = $this->createHelper("LDUserType");
		$avlBacklinkCount = $pluginUserTypeObj->getMaxAvailableBacklinkCount($projectInfo['user_id']);
		$this->set('avlBacklinkCount', $avlBacklinkCount);
		
		// if post action not occured
		if (!isset($info['max_links'])) {
			$info['max_links'] = $avlBacklinkCount;
			$this->set('post', $info);
		}
		
		include_once(PLUGIN_PATH."/crawler.class.php");
		$ldCrawler = New LDCrawler();
		$this->set('seList', $ldCrawler->seList);
		
		$this->pluginRender('newreport');
	}
	
	/*
	 * func to create report
	 */
	function createReport($listInfo){		
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));		
		$errMsg['url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['url'])));
		$listInfo['url'] = addHttpToUrl($listInfo['url']);
		$this->set('post', $listInfo);
		$this->setPluginTextsForRender('ldplugin', 'ld_texts');
		$listInfo['project_id'] = intval($listInfo['project_id']);
		$projectCtrler = $this->createHelper('Project');
		$projectInfo = $projectCtrler->__getProjectInfo($listInfo['project_id']);
		
		// check for user type settings
		$pluginUserTypeObj = $this->createHelper("LDUserType");
		list($errorExist, $errMsg) = $pluginUserTypeObj->validateUserTypeSettings($listInfo, $errMsg, $projectInfo['user_id']);
		
		// check whether error occured while validation
		if(!$this->validate->flagErr && !$errorExist){			
			
			if (!$this->__checkName($listInfo['name'], $listInfo['project_id'])) {
				$sql = "insert into ld_reports(name,project_id,url,max_links,searchengine_id,status)
							values('".addslashes($listInfo['name'])."',{$listInfo['project_id']},'".addslashes($listInfo['url'])."',".intval($listInfo['max_links']).",".intval($listInfo['searchengine_id']).",0)";
				$this->db->query($sql);
				$this->showReportsManager();
				exit;
			}else{				
				$errMsg['name'] = formatErrorMsg($this->pluginText['Report already exist']);
			}
			
		}
		
		$this->set('errMsg', $errMsg);
		$this->newReport($listInfo);
	}
	
	/*
	 * func to edit report
	 */ 
	function editReport($reportId, $listInfo=''){
		
		$userId = isLoggedIn();
		if(!empty($reportId)){
						
			if (empty($listInfo)) {
				$listInfo = $this->__getReportInfo($reportId);
				$listInfo['oldName'] = $listInfo['name'];
				$listInfo['old_searchengine_id'] = $listInfo['searchengine_id'];
			}
			
			$this->set('post', $listInfo);
			
			// get all projects		
			$projectCtrler = $this->createHelper('Project');
			$cond = " and p.status=1";
			$this->set('projectId', $listInfo['project_id']);
			if(isAdmin()){								
				$this->set('isAdmin', 1);
			} else {
				$cond .= " and user_id=$userId";
				$this->set('isAdmin', 0);
			}
			
			$projectList = $projectCtrler->getAllProjects($cond);
			$this->set('projectList', $projectList);

			// find available backlink count
			$projectInfo = $projectCtrler->__getProjectInfo($listInfo['project_id']);
			$pluginUserTypeObj = $this->createHelper("LDUserType");
			$avlBacklinkCount = $pluginUserTypeObj->getMaxAvailableBacklinkCount($projectInfo['user_id'], $reportId);
			$this->set('avlBacklinkCount', $avlBacklinkCount);

			include_once(PLUGIN_PATH."/crawler.class.php");
		    $ldCrawler = New LDCrawler();
		    $this->set('seList', $ldCrawler->seList);
			
			$this->pluginRender('editreport');
		}		
	}
	
	/*
	 * func to update report
	 */
	function updateReport($listInfo){		
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$errMsg['url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['url'])));
		$listInfo['url'] = addHttpToUrl($listInfo['url']);
		$this->set('post', $listInfo);
		$this->setPluginTextsForRender('ldplugin', 'ld_texts');
		$listInfo['project_id'] = intval($listInfo['project_id']);
		$listInfo['id'] = intval($listInfo['id']);
		$projectCtrler = $this->createHelper('Project');
		$projectInfo = $projectCtrler->__getProjectInfo($listInfo['project_id']);

		// check for user type settings
		$pluginUserTypeObj = $this->createHelper("LDUserType");
		list($errorExist, $errMsg) = $pluginUserTypeObj->validateUserTypeSettings($listInfo, $errMsg, $projectInfo['user_id']);
		
		// chekc for error occured or not
		if(!$this->validate->flagErr && !$errorExist){

			if($listInfo['name'] != $listInfo['oldName']){
				if ($this->__checkName($listInfo['name'], $listInfo['project_id'])) {
					$errMsg['name'] = formatErrorMsg($this->pluginText['Report already exist']);
					$this->validate->flagErr = true;
				}
			}

			if (!$this->validate->flagErr) {			    
			    
				$sql = "update ld_reports set
						project_id = {$listInfo['project_id']},
						name = '".addslashes($listInfo['name'])."',
						url = '".addslashes($listInfo['url'])."',
						searchengine_id = '".intval($listInfo['searchengine_id'])."',
						max_links = ".intval($listInfo['max_links'])."
						where id={$listInfo['id']}";
				$this->db->query($sql);
				
				// update all indexed pages with -1 to search for backlinks with new domain
			    if($listInfo['old_searchengine_id'] != $listInfo['searchengine_id']){
			        $sql = "update ld_indexed set crawled=0,crawling_page='-1' where report_id=".$listInfo['id'];
			        $this->db->query($sql);
			        
			        // change status of report to incomplete
			        $listInfo = $this->__getReportInfo($listInfo['id']);
			        $reportStatus = empty($listInfo['status']) ? 0 : 1;
			        $this->changeReportStatus($listInfo['id'], $reportStatus);
			    }
				
				$this->showReportsManager();				
				exit;
			}
		}
		
		$this->set('errMsg', $errMsg);
		$this->editReport($listInfo['id'], $listInfo);
		
	}
	
	/*
	 * func to delete report
	 */
	function deleteReport($reportId, $showManager=true) {
		$reportId = intval($reportId);
		
		$sql = "delete from ld_reports where id=$reportId";
		$this->db->query($sql);		
		
		$sql = "delete from ld_indexed where report_id=$reportId";
		$this->db->query($sql);
		
		$sql = "delete from ld_backlinks where report_id=$reportId";
		$this->db->query($sql);
		
		if($showManager) $this->showReportsManager();				
	}
	
	/*
	 * func re run report
	 */
	function reRunReport($reportId) {
		$reportId = intval($reportId);
		
		$sql = "delete from ld_indexed where report_id=$reportId";
		$this->db->query($sql);
		
		$sql = "delete from ld_backlinks where report_id=$reportId";
		$this->db->query($sql);
		
		$this->changeReportStatus($reportId, 0);
		
		$this->runReport($reportId);				
	}
	
	/*
	 * func to verify the existing backlinks
	 */
	function verifyBacklinks($reportId) {
		$reportId = intval($reportId);
		
	    $sql = "update ld_backlinks set crawled=0,url_found='' where report_id=$reportId";
	    $this->db->query($sql);
	    
		$this->changeReportStatus($reportId, 1);
		
		$this->runReport($reportId);				
	}
	
	
	/*
	 * function to check name of report
	 */
	function __checkName($name, $projectId){
		$sql = "select id from ld_reports where name='".addslashes($name)."' and project_id=" . intval($projectId);
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	/*
	 * func to get report info
	 */
	function __getReportInfo($reportId) {
		$sql = "select p.name pname,r.* from ld_reports r,ld_projects p where r.project_id=p.id and r.id=" . intval($reportId);
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
	/*
	 * func to get all report info
	 */
	function __getAllReports($projectId, $condtions='') {
		$sql = "select p.name pname,r.* from ld_reports r,ld_projects p where r.project_id=p.id";
		$sql .= empty($projectId) ? "" : " and r.project_id=".intval($projectId);		
		$sql .= empty($condtions) ? "" : $condtions;
		$list = $this->db->select($sql);
		return $list;		
	}
	
	/*
	 * function to change report status
	 */
	function changeReportStatus($reportId, $status) {
		$reportId = intval($reportId);
		$sql = "update ld_reports set status='$status' where id=$reportId";
		$this->db->query($sql);
	}
	
	/*
	 * function to run Report
	 */
	function runReport($reportId) {
		$this->set('reportId', $reportId);
		$this->pluginRender('runreport');
	}

	/*
	 * function to update report time
	 */
	function updateReportTime($reportId, $time='CURRENT_TIMESTAMP') {
		$reportId = intval($reportId);
		$sql = "update ld_reports set updated=$time where id=$reportId";
		$this->db->query($sql);
	}

	/**
	 * function to execute cron job
	 */
	function startCronJob() {
	
		$this->cronJob = true;
		$sql = "select id from ld_reports where status < 2 order by id";
		$sql .= LD_CRON_REPORT_DAILY_LIMIT ? " limit 0, " . LD_CRON_REPORT_DAILY_LIMIT : "";
		$repList = $this->db->select($sql);
		
		if (count($repList) > 0) {
			foreach ($repList as $repInfo) {
				$this->generateReport($repInfo['id']);
			}
		} else {
			echo "Report generated for all the reports!";
		}
	
	}
	
	/*
	 * function to generate Report
	 */
	function generateReport($reportId) {	
		
		// update report updated time
		$this->updateReportTime($reportId);
		
		$reportInfo = $this->__getReportInfo($reportId);
		switch ($reportInfo['status']) {
            	            	
            case 2:
            	if ($this->cronJob) {
            		print $this->pluginText["Links report generated successfully"];
            	} else {
                	$reportLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=viewreport&report_id=$reportId", $_SESSION['text']['label']["Click Here"]);
			    	showSuccessMsg($this->pluginText["Links report generated successfully"] ."! ".$reportLink .$this->pluginText["to view the reports"]);
            	}
                break;
            
            case 1:
            	$this->generateBacklinks($reportInfo);
            	break;
            
            default:
            	$this->generateIndexedPages($reportInfo);	
        }
	}
	
	/*
	 *  func to generate indexed pages
	 */
	function generateIndexedPages($reportInfo) {
		
		$matchInfo['url'] = preg_match('/\/$/', $reportInfo['url']) ? $reportInfo['url'] : $reportInfo['url'] ."/";
		$this->saveIndexedPages($reportInfo['id'], $matchInfo, false);
		
		// if max indexed page is 1 then generate backlinks 
		if (LD_MAX_INDEXED_PAGES == 1) {
		    
		    // change status of report
			$this->changeReportStatus($reportInfo['id'], 1);

			//  generate backlinks for indexed pages
			$this->generateBacklinks($reportInfo);
			return;
		}
		
		include_once(SP_CTRLPATH."/searchengine.ctrl.php");
		include_once(SP_CTRLPATH."/report.ctrl.php");
		
		$reportController = New ReportController();		
		$seController = New SearchEngineController();
		$reportController->seList = $seController->__getAllCrawlFormatedSearchEngines();
		
		$uInfo = parse_url($reportInfo['url']);
		$hostName = $uInfo['host'];
		
		$keywordInfo = array(
			'name' => 'site:'.$reportInfo['url'],
			'searchengines' => 1,
			'url' => $reportInfo['url'],	
		);
		$reportController->showAll = true;
		$crawlResult = $reportController->crawlKeyword($keywordInfo);
		$maxIndexedpages = LD_MAX_INDEXED_PAGES - 1;		
		foreach($crawlResult as $matchList){
			if($matchList['status']){
				foreach($matchList['matched'] as $i => $matchInfo){				    
					if( $i == $maxIndexedpages) break;					
					if (stristr($matchInfo['url'], $hostName)) {
					    $st = $this->saveIndexedPages($reportInfo['id'], $matchInfo);
					    $maxIndexedpages = $st ? $maxIndexedpages : ($maxIndexedpages +1);
					} else {
					    $maxIndexedpages++;
					}
				}
				
				// change status of report
				$this->changeReportStatus($reportInfo['id'], 1);
				
				if ($this->limitadd) {					
					$this->set('reportId', $reportInfo['id']);
                    $msg = $this->showRunInfo($reportInfo['id'], false);
					$this->set('msg', $msg.$this->pluginText['savedindexpageswaiting']);
					$this->pluginRender('generatereport');
				} else {					
					//  generate backlinks for indexed pages
					$this->generateBacklinks($reportInfo);
				}
			}
		}				
	}
	
	/*
	 * function to save indexed pages
	 */
	function saveIndexedPages($reportId, $matchInfo, $remove=false) {
		$reportId = intval($reportId);
		
		if ($remove) {			
			$sql = "delete from ld_indexed where report_id=$reportId";
			$this->db->query($sql);
		}
		
		$sql = "select id from ld_indexed where report_id=$reportId and url='".addslashes($matchInfo['url'])."'";
		$info = $this->db->select($sql, true);
		if (empty($info['id'])) {		
    		$sql = "Insert into ld_indexed(report_id,url) values($reportId, '".addslashes($matchInfo['url'])."')";
    		$this->db->query($sql);
    		return true;
		} else {
		    return false;
		}
	}
	
	/*
	 * function to get indexed pages
	 */
	function __getAllIndexedPages($reportId, $cond='') {
		$reportId = intval($reportId);
		$sql = "select * from ld_indexed where report_id=$reportId $cond";
		$list = $this->db->select($sql);
		return $list;
	}
	
	/*
	 * function to get count of backlinks of a report
	 */
	function __getTotalBacklinks($reportId) {
		$reportId = intval($reportId);
		$sql = "select count(*) count from ld_backlinks where report_id=$reportId";
		$info = $this->db->select($sql, true);
		return $info['count'];
	}
	
	/*
	 * function to generate backlinks of indexed pages
	 */
	function generateBacklinks($reportInfo) {
		
		$reportId = intval($reportInfo['id']);
		$totBacklinks = $this->__getTotalBacklinks($reportId);
		if ($totBacklinks < $reportInfo['max_links']) {
		
			$indexedList = $this->__getAllIndexedPages($reportId, " and crawled=0 order by id");
			
			// if any indexed page is not crawled for backlinks 
			if (count($indexedList) > 0) {
				
				include_once(PLUGIN_PATH."/crawler.class.php");
				$ldCrawler = New LDCrawler();
				$seId = empty($reportInfo['searchengine_id']) ? LD_SEARCH_ENGINE : $reportInfo['searchengine_id']; 			
				if ($seInfo = $ldCrawler->seList[$seId]) {	
				
					// loop through indexed pages to generate backlinks
					$limitExeeds = false;
					$crawlDelay = (SP_CRAWL_DELAY < 5) ? 5 : SP_CRAWL_DELAY; 
					foreach ($indexedList as $indexInfo) {
						
						if($limitExeeds) break;
										
						$totBacklinks = $this->__getTotalBacklinks($reportId);
						if ($totBacklinks < $reportInfo['max_links']) {
							
							$start = ($indexInfo['crawling_page'] == '-1') ? $seInfo['start'] : $indexInfo['crawling_page'];
							while(!$limitExeeds) {
								
								$beforeTotLinks = $totBacklinks;
								if (stristr($ldCrawler->seList[$seId]['domain'], 'alexa')) {
								    $keyword = formatUrl($indexInfo['url']);    
								} else {
								    $keyword = formatUrl($indexInfo['url'], false);
								}
								
								$crawlResult = $ldCrawler->crawlKeyword($keyword, $seInfo['id'], $start);
								
								$resCount = count($crawlResult['matched']);
								
								foreach($crawlResult['matched'] as $matchedInfo) {
								    
								    $this->maintainDBConn();
									if($this->saveMatchedBacklink($matchedInfo, $indexInfo['id'], $reportId)) {
										$totBacklinks++;									
									}
									if ($totBacklinks >= $reportInfo['max_links']) {
										$limitExeeds = true;
										break;
									}
								}
														
								// if crawling failed or crawled result does not addd any new links to the report
								if (empty($resCount) || ($beforeTotLinks==$totBacklinks) || $limitExeeds) {
									break;
								}
								
								// check whetehr lokking for next page link
								if (empty($seInfo['next_link_regex'])) {
									$start += $seInfo['start_offset'];
									$this->changeIndexedCrawlingPage($indexInfo['id'], $start);
								} else {
									if (empty($ldCrawler->nextLink)) {
										break;
									} else {
										$this->changeIndexedCrawlingPage($indexInfo['id'], $ldCrawler->nextLink);
									}
								}

								// if cron job
								if ($this->cronJob) {
									print "\n". $this->pluginText["Waiting for crawling next set of backlinks"] . " for ".LD_CRAWL_DELAY_BACKLINKS_CRON." seconds\n";
									sleep(LD_CRAWL_DELAY_BACKLINKS_CRON);
									$this->generateBacklinks($reportInfo);
								} else {
									$this->set('reportId', $reportId);
				                    $msg = $this->showRunInfo($reportId, false);
	            					$this->set('msg', $msg.$this->pluginText["Waiting for crawling next set of backlinks"]);
	            					$this->pluginRender('generatereport');
								}
            					exit;
							}
							
						} else {
							break;
						}
						
						// to change crawling status of indexed page
						$this->maintainDBConn();
						$this->changeIndexedCrawlingStatus($indexInfo['id']);
						
						sleep($crawlDelay);
					}
					
					// if cron job
					if ($this->cronJob) {
						print "\n". $this->pluginText["crawlcompletedwaitmsg"] . " for ".LD_CRAWL_DELAY_BACKLINKS_CRON." seconds\n";
						sleep(LD_CRAWL_DELAY_BACKLINKS_CRON);
						$this->crawlBacklinkInfo($reportInfo);
					} else {
						$this->set('reportId', $reportId);
	                    $msg = $this->showRunInfo($reportId, false);
						$this->set('msg', $msg.$this->pluginText["crawlcompletedwaitmsg"]);
						$this->pluginRender('generatereport');
					}
					exit;
				}
			}
		}
		
		// crawl the backlinks pages to update the details
		$this->crawlBacklinkInfo($reportInfo);
	}
    
    /*
     * function to create mysql connect again
     */
    function maintainDBConn() {
       $dbObj = New Database(DB_ENGINE);
       $this->db = $dbObj->dbConnect();
    }
	
	/*
	 * function to crawl back link info
	 */
	function crawlBacklinkInfo($reportInfo) {
		$this->bklimit = LD_BACKLINK_INFO_CHECK_LIMIT;
		$reportId = $reportInfo['id'];
		$indexedList = $this->__getAllIndexedPages($reportId, " and crawled=1 order by id");
		
		// if any indexed page is crawled for backlinks 
		if (count($indexedList) > 0) {			
			
			include_once(SP_CTRLPATH."/rank.ctrl.php");
			$rankCtrler = New RankController();
			include_once(PLUGIN_PATH."/crawler.class.php");
			$ldCrawler = New LDCrawler();
			
			$limit = $this->limitadd ? " limit 0,$this->bklimit" : "";
			foreach ($indexedList as $indexInfo) {
				
				$backlinkList = $this->__getAllBacklinks($indexInfo['id'], " and crawled=0 order by rand() $limit");
				foreach ($backlinkList as $backInfo) {
					
					$reportInfo['url'] = $ldCrawler->removeTrailingSlash(formatUrl($reportInfo['url']));
					$pageInfo = $ldCrawler->getBacklinkPageInfo($backInfo['url'], $reportInfo['url']);
					
					// if pagerank is empty
					if (!empty($backInfo['url_found'])) {
						$pageInfo['url_found'] = $backInfo['url_found'];
						$pageInfo['title'] = $backInfo['link_title'];
						$pageInfo['pr'] = $backInfo['google_pagerank'];
						$pageInfo['domain_authority'] = $backInfo['domain_authority'];
						$pageInfo['page_authority'] = $backInfo['page_authority'];
					} else {
						/*$pageInfo['pr'] = $rankCtrler->__getGooglePageRank(urldecode($backInfo['url']));*/
						include_once(SP_CTRLPATH."/moz.ctrl.php");
						$mozCtrler = new MozController();
						$mozRankList = $mozCtrler->__getMozRankInfo(array(urldecode($backInfo['url'])));
						$pageInfo['pr'] = !empty($mozRankList[0]['moz_rank']) ? $mozRankList[0]['moz_rank'] : 0;
						$pageInfo['domain_authority'] = !empty($mozRankList[0]['domain_authority']) ? $mozRankList[0]['domain_authority'] : 0;
						$pageInfo['page_authority'] = !empty($mozRankList[0]['page_authority']) ? $mozRankList[0]['page_authority'] : 0;
					}
					
					$pageInfo['id'] = $backInfo['id'];
					$this->maintainDBConn();
					
					$pageInfo['google_pagerank'] = $pageInfo['pr'];
					$scoreInfo = $this->__getLinkType($pageInfo);
					$pageInfo['link_score'] = $scoreInfo['score'];
					$this->updateBacklinkPageInfo($pageInfo);
					
					// if cron job
					if ($this->cronJob) {
						Print "\n Saved details of backlink - {$backInfo['url']} \n";
						print "\n wait to check next backlink details for ".LD_CRAWL_DELAY_INFO_CRON." seconds\n";
						sleep(LD_CRAWL_DELAY_INFO_CRON);
					}
					
				}
				
				if ($this->limitadd && ($this->bklimit == count($backlinkList))) break;
			}
		}
		
		$tocrawl = $this->__getCountOfReportInfo($reportId, " and crawled=0");
		if ($tocrawl == 0) {			
			// change status of report
			$this->changeReportStatus($reportInfo['id'], 2);
			
			// if cron job
			if ($this->cronJob) {
				print "\n-----".$this->pluginText["Links report generated successfully"] ."--------\n";
			} else {
				$reportLink = scriptAJAXLinkHref(PLUGIN_SCRIPT_URL, 'content', "action=viewreport&report_id=$reportId", $_SESSION['text']['label']["Click Here"]);
				$msg = $this->showRunInfo($reportId, false);
				showSuccessMsg($msg.$this->pluginText["Links report generated successfully"] ."! ".$reportLink .$this->pluginText["to view the reports"]);
			}
		} else {
			
			// if cron job
			if ($this->cronJob) {
				$this->crawlBacklinkInfo($reportInfo);
			} else {
				$this->set('reportId', $reportId);
				$msg = $this->showRunInfo($reportId, false);
				$this->set('msg', $msg.$this->pluginText['waitcrawlbacklinks']);
				$this->pluginRender('generatereport');
			}
		}
		
	}
	
	/*
	 * function to update backlink page info
	 */
	function updateBacklinkPageInfo($pageInfo) {
		$pageInfo['link_score'] = round($pageInfo['link_score']);
		$sql = "update ld_backlinks set
				link_title='".addslashes($pageInfo['title'])."',
				google_pagerank={$pageInfo['pr']},
				domain_authority={$pageInfo['domain_authority']},
				page_authority={$pageInfo['page_authority']},
				link_score={$pageInfo['link_score']},
				nofollow={$pageInfo['nofollow']},
				outbound_links={$pageInfo['external']},
				url_found='".addslashes($pageInfo['url_found'])."',
				crawled=1
				where id={$pageInfo['id']} 			 
				";
		$this->db->query($sql);
	}
	
	/*
	 * function to get all backlinks 
	 */
	function __getAllBacklinks($indexId, $cond='') {
		$indexId = intval($indexId);
		$sql = "select * from ld_backlinks where indexed_id=$indexId $cond";
		$list = $this->db->select($sql);
		return $list;
	}

	/*
	 * function to check whetehr backlink exists for a report
	 */
	function isBacklinkExists($url, $id, $col='report_id') {
		$id = intval($id);
		$sql = "select id from ld_backlinks where $col=$id and url='".addslashes($url)."'";
		$list = $this->db->select($sql, true);
		return empty($list['id']) ? false : $list['id'];
		
	}

	/*
	 * function to save backlinks
	 */
	function saveMatchedBacklink($matchedInfo, $indexId, $reportId) {

		if($this->isBacklinkExists($matchedInfo['url'], $reportId)) {
			return false;
		}
		
		$colExtra = "";
		$valExtra = "";
		
		// loop through values
		foreach ($matchedInfo as $col => $val) {
			
			switch ($col) {
				
				case "moz_rank":
					$colExtra .= ", google_pagerank";
					$valExtra .= ",'" . addslashes($val) . "'";
					break;

				case "url_found":
				case "link_title":
				case "domain_authority":
				case "page_authority":
					$colExtra .= ", $col";
					$valExtra .= ",'" . addslashes($val) . "'";
					break;
				
			}
			
		}
		
		$indexId = intval($indexId);
		$reportId = intval($reportId);
		$sql = "Insert into ld_backlinks(indexed_id,report_id,url $colExtra) values($indexId, $reportId, '".addslashes($matchedInfo['url'])."' $valExtra)";
		
		$this->db->query($sql);
		return true;
	}
	
	/*
	 * function to delete backlinks of indexed page
	 */
	function deleteBacklinks($indexId) {
		$sql = "delete from ld_backlinks where indexed_id=" . intval($indexId);
		$this->db->query($sql);
	}
	
	/*
	 * function to change crawling status of indexed pages
	 */
	function changeIndexedCrawlingStatus($indexId, $status=1) {
		$sql = "update ld_indexed set crawled=$status where id=" . intval($indexId);		
		$this->db->query($sql);
	}
	
	/*
	 * function to change crawling page number of indexed pages
	 */
	function changeIndexedCrawlingPage($indexId, $page) {
		$page = addslashes($page);
		$sql = "update ld_indexed set crawling_page='$page' where id=" . intval($indexId);		
		$this->db->query($sql);
	}
	
	
	/*
	 * function to get count of unique anchors
	 */
	function __getCountOfUniqueAnchors($reportId, $cond='') {
		$reportId = intval($reportId);		
		$sql = "select count(distinct link_title) count from ld_backlinks where report_id=$reportId $cond";
		$info = $this->db->select($sql, true);
		return empty($info['count']) ? 0 : $info['count'];
	}
	
	/*
	 * function to get count of report information
	 */
	function __getCountOfReportInfo($reportId, $cond='', $table='ld_backlinks', $col='report_id') {
		$reportId = intval($reportId);
		$sql = "select count(*) count from $table where $col=$reportId $cond";
		$info = $this->db->select($sql, true);
		return empty($info['count']) ? 0 : $info['count'];
	}
	
	/*
	 * function to show report run information
	 */
	function showRunInfo($reportId, $renderCtp=true) {
		$reportId = intval($reportId);
		$runInfo['tot_backlinks'] = $this->__getCountOfReportInfo($reportId);
		$runInfo['tot_indexed'] = $this->__getCountOfReportInfo($reportId, '', 'ld_indexed');
		$runInfo['tot_anchors'] = $this->__getCountOfUniqueAnchors($reportId);
		$runInfo['tot_crawled'] = $this->__getCountOfReportInfo($reportId, " and crawled=1");
		
		$runInfo['tot_exel'] = $this->__getCountOfReportInfo($reportId, " and link_title!='' and nofollow=0 and outbound_links<=".LD_EXCEL_OUTBOUND." and google_pagerank>=".LD_EXCEL_PR);
		$runInfo['tot_good'] = $this->__getCountOfReportInfo($reportId, " and link_title!='' and nofollow=0");
		$runInfo['tot_nofollow'] = $this->__getCountOfReportInfo($reportId, " and nofollow=1 and crawled=1");
		$runInfo['tot_missing'] = $this->__getCountOfReportInfo($reportId, " and link_title='' and crawled=1");		
		
		// find pagerank count
		for ($i=0;$i<=10;$i++) {
			$runInfo['tot_pr_'.$i] = $this->__getCountOfReportInfo($reportId, " and google_pagerank=$i and crawled=1");
		}
		
		if ($renderCtp) {
    		$this->set('reportId', $reportId);
    		$this->set('runInfo', $runInfo);				
    		$this->pluginRender('showruninfo');
		} else {
		    $runInfoScript = "<script>";
		    foreach ($runInfo as $divId => $count) {
		        $runInfoScript .= "document.getElementById('$divId').innerHTML = $count;";
		    }
		    $runInfoScript .= "</script>";
		    return $runInfoScript;
		}
	}
	
	/*
	 * function to show reoport 
	 */
	function viewReports($data) {
		
		$userId = isLoggedIn();
		$cond = isAdmin() ? "" : " and user_id=$userId";
		$sql = "select p.* from ld_projects p,ld_reports r where p.id=r.project_id and p.status=1 and r.status>=1 $cond group by p.id";
		$projectList = $this->db->select($sql);		
		$this->set('projectList', $projectList);
		
		if (empty($data['report_id'])) {
			$projectId = $projectList[0]['id'];
		} else {
			$reportId = $data['report_id'];
			$reportInfo = $this->__getReportInfo($reportId);
			$projectId = $reportInfo['project_id'];
		}
		
		$reportList = $projectId ? $this->__getAllReports($projectId, " and r.status>=1") : array();
		$reportId = empty($reportId) ? $reportList[0]['id'] : $reportId;
		$this->set('reportList', $reportList);		
		$this->set('projectId', $projectId);
		$this->set('reportId', $reportId);
		$typeList = array(
			'rp_links' => $this->pluginText["Backlinks Reports"],
			'rp_summary' => $this->pluginText["Report Summary"],
			'rp_indexed' => $this->pluginText["Pages Indexed"],
			'rp_anchor' => $this->pluginText["Popular Anchors"],
			'rp_not_crawled' => $this->pluginText["Not Crawled"],
		);
		
		$this->set('typeList', $typeList);
		
		$linkType = array(
			'excellent' => $this->pluginText["Excellent"],
			'great' => $this->pluginText["Great"],
			'good' => $this->pluginText["Good"],
			'poor' => $this->pluginText["Poor"],
			'nofollow' => $this->pluginText["Nofollow"],
			'missing' => $this->pluginText["Missing Title"],
		);
		
		$this->set('linkType', $linkType);
		$this->set('searchInfo', $data);

		$anchorList = $reportId ? $this->__getCommonAnchor($reportId) : array();
		$this->set('anchorList', $anchorList);
		$this->set('spTextHome', $this->getLanguageTexts('home', $_SESSION['lang_code']));
		$this->pluginRender('viewreports');		
	}
	
	/*
	 * function to show reports
	 */
	function showReports($data) {
		
		$reportInfo = $this->__getReportInfo($data['report_id']);		
		$this->set('repInfo', $reportInfo);
		$this->set('reportId', $data['report_id']);
		$this->set('searchInfo', $data);
		
		$exportVersion = false;
		switch($data['doc_type']){
						
			case "export":
				$exportVersion = true;
				break;
			
			case "print":
				$this->set('printVersion', true);
				break;
		}
		
		switch($data['report_type']) {
			
			case "rp_indexed":
				$this->showPagesIndexed($data, $exportVersion, $reportInfo);
				break;
			
			case "rp_anchor":
				$this->showAnchorReports($data, $exportVersion, $reportInfo);
				break;
			
			case "rp_summary":
				$this->showReportSummary($data, $exportVersion, $reportInfo);
				break;
			
			case "rp_not_crawled":
				$this->showLinksReport($data, $exportVersion, $reportInfo, 0);
				break;

			case "rp_links":
			default:
				$this->showLinksReport($data, $exportVersion, $reportInfo);
				break;
			
		}
		
	}
	
	/*
	 * function to show report summary
	 */
	function showReportSummary($data, $exportVersion, $repInfo) {
		$reportId = $data['report_id'];
		$reportInfo['tot_indexed'] = $this->__getCountOfReportInfo($reportId, '', 'ld_indexed');
		$reportInfo['tot_backlinks'] = $this->__getCountOfReportInfo($reportId);
		$reportInfo['tot_anchors'] = $this->__getCountOfUniqueAnchors($reportId);
		$reportInfo['tot_crawled'] = $this->__getCountOfReportInfo($reportId, " and crawled=1");
		
		$reportInfo['tot_exel'] = $this->__getCountOfReportInfo($reportId, " and link_score > " . LD_LINK_SCORE_EXCEL);
		$reportInfo['tot_great'] = $this->__getCountOfReportInfo($reportId, " and link_score > " . LD_LINK_SCORE_GREAT . " and link_score <= " . LD_LINK_SCORE_EXCEL);
		$reportInfo['tot_good'] = $this->__getCountOfReportInfo($reportId, " and link_score > " . LD_LINK_SCORE_GOOD . " and link_score <= " . LD_LINK_SCORE_GREAT);
		$reportInfo['tot_poor'] = $this->__getCountOfReportInfo($reportId, " and link_score > 0 and link_score <= " . LD_LINK_SCORE_GOOD);
		$reportInfo['tot_nofollow'] = $this->__getCountOfReportInfo($reportId, " and nofollow=1 and crawled=1");
		$reportInfo['tot_missing'] = $this->__getCountOfReportInfo($reportId, " and link_title='' and crawled=1");		
		
		// find pagerank count
		for ($i = 0; $i <= 10; $i++) {
			$prMax = $i + 0.5;
			$prMin = $i - 0.5;
			$sql = " and google_pagerank<$prMax and google_pagerank>=$prMin";
			$reportInfo['tot_pr_'.$i] = $this->__getCountOfReportInfo($reportId, $sql);
		}
		
		if ($exportVersion) {
			$exportContent = createExportContent(array('', $this->pluginText['Links Report Summary'], ''));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Report Url'], $repInfo['url']));
			$exportContent .= createExportContent(array($this->pluginText['Last Updated'], date('M d Y H:i:s', strtotime($repInfo['updated']))));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Report Summary']));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Paged Indexed'], $reportInfo['tot_indexed']));
			$exportContent .= createExportContent(array($this->pluginText['Total Backlinks'], $reportInfo['tot_backlinks']));
			$exportContent .= createExportContent(array($this->pluginText['Crawled Backlinks'], $reportInfo['tot_crawled']));
			$exportContent .= createExportContent(array($this->pluginText['Unique Anchors'], $reportInfo['tot_anchors']));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Link Summary']));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Excellent'], $reportInfo['tot_exel']));
			$exportContent .= createExportContent(array($this->pluginText['Great'], $reportInfo['tot_great']));
			$exportContent .= createExportContent(array($this->pluginText['Good'], $reportInfo['tot_good']));
			$exportContent .= createExportContent(array($this->pluginText['Poor'], $reportInfo['tot_poor']));
			$exportContent .= createExportContent(array($this->pluginText['Nofollow'], $reportInfo['tot_nofollow']));
			$exportContent .= createExportContent(array($this->pluginText['Missing Title'], $reportInfo['tot_missing']));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			
			$exportContent .= createExportContent(array($this->pluginText['Page Rank Summary']));
			$exportContent .= createExportContent(array());			
			for ($i=10;$i>=0;$i--) {
				$exportContent .= createExportContent(array('PR'.$i, $reportInfo['tot_pr_'.$i]));
			}
			exportToCsv('link_report_summary', $exportContent);			
		} else {		
			$this->set('data', $data);
			$this->set('reportInfo', $reportInfo);					
			$this->pluginRender('reportsummary');			
		}
	}
	
	// function to get link score label
	function getLinkScoreLabel($linkScore) {
		
		// check link score
		if ($linkScore > LD_LINK_SCORE_EXCEL) {
			$linkLabel = $this->pluginText["Excellent"];
		} else if ($linkScore > LD_LINK_SCORE_GREAT) {
			$linkLabel = $this->pluginText["Great"];
		} else if ($linkScore > LD_LINK_SCORE_GOOD) {
			$linkLabel = $this->pluginText["Good"];
		} else if ($linkScore > 0) {
			$linkLabel = $this->pluginText["Poor"];
		} else {
			$linkLabel = $this->pluginText["Nofollow"];
		}
		
		return $linkLabel;
	}
	
	/*
	 * get link type - excelent,good etc
	 */
	function __getLinkType($linkInfo) {
		$spTextSA = $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']);
		$scoreInfo = array(
			'score' => 0,
			'label' => $this->pluginText["Nofollow"],
			'comments' => 'Nofollow link',
		);
				
		// if not a nofollow link
		if (!$linkInfo['nofollow']) {
			$scoreInfo['score'] = 3;
			$scoreInfo['comments'] = "";
			
			// check total outbound links of a page
			if ($linkInfo['outbound_links'] > LD_EXCEL_OUTBOUND_POOR) {
				$scoreInfo['score'] += -1;
				$msg = $this->pluginText["The number of outbound links"] . " > " . LD_EXCEL_OUTBOUND_POOR;
				$scoreInfo['comments'] = formatErrorMsg($msg, 'error', '') . "<br>";
			} else if ($linkInfo['outbound_links'] < LD_EXCEL_OUTBOUND) {
				$scoreInfo['score'] += 1;
				$msg .= $this->pluginText["The number of outbound links"] . " < " . LD_EXCEL_OUTBOUND;
				$scoreInfo['comments'] = formatSuccessMsg($msg) . "<br>";
			}
			
			// if link title is missing
			if (empty($linkInfo['link_title'])) {
				$scoreInfo['score'] -= 1;
				$scoreInfo['comments'] .= formatErrorMsg($this->pluginText["Missing Title"], 'error', '') . "<br>";
			}
			
			// check pagerank
			if ($linkInfo['google_pagerank'] >= SA_PR_CHECK_LEVEL_SECOND) {
				$scoreInfo['score'] += $linkInfo['google_pagerank'] * 3;
				$msg = $spTextSA["The page is having exellent pagerank"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['google_pagerank'] >= SA_PR_CHECK_LEVEL_FIRST) {
				$scoreInfo['score'] += $linkInfo['google_pagerank'] * 2;
				$msg = $spTextSA["The page is having very good pagerank"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['google_pagerank']) {
				$scoreInfo['score'] += 1;
				$msg = $spTextSA["The page is having good pagerank"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else {
				$msg = $spTextSA["The page is having poor pagerank"];
				$scoreInfo['comments'] .= formatErrorMsg($msg, 'error', '');
			}
				
			$scoreInfo['comments'] .= "<br>";
			
			// check page authority value
			if ($linkInfo['page_authority'] >= SA_PA_CHECK_LEVEL_SECOND) {
				$scoreInfo['score'] += 6;
				$msg = $spTextSA["The page is having excellent page authority value"];
				$scoreInfom['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['page_authority'] >= SA_PA_CHECK_LEVEL_FIRST) {
				$scoreInfo['score'] += 3;
				$msg = $spTextSA["The page is having very good page authority value"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['page_authority']) {
				$scoreInfo['score'] += 1;
				$msg = $spTextSA["The page is having good page authority value"];
				$msg = !empty($msg) ? $msg : $spTextSA["The page is having good page authority valu"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else {
				$msg = $spTextSA["The page is having poor page authority value"];
				$scoreInfo['comments'] .= formatErrorMsg($msg, 'error', '');
			}
			
			$scoreInfo['comments'] .= "<br>";
			
			// check domain authority value
			if ($linkInfo['domain_authority'] >= SA_PA_CHECK_LEVEL_SECOND) {
				$scoreInfo['score'] += 6;
				$msg = $this->pluginText["The page is having excellent domain authority value"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['domain_authority'] >= SA_PA_CHECK_LEVEL_FIRST) {
				$scoreInfo['score'] += 3;
				$msg = $this->pluginText["The page is having very good domain authority value"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else if ($linkInfo['domain_authority']) {
				$scoreInfo['score'] += 1;
				$msg = $this->pluginText["The page is having good domain authority value"];
				$scoreInfo['comments'] .= formatSuccessMsg($msg);
			} else {
				$msg = $this->pluginText["The page is having poor domain authority value"];
				$scoreInfo['comments'] .= formatErrorMsg($msg, 'error', '');
			}
			
			$scoreInfo['label'] = $this->getLinkScoreLabel($scoreInfo['score']);
			
		}
		
		return $scoreInfo;
	}
	
	/*
	 * function to detailed backlinks reports
	 */
	function showLinksReport($data, $exportVersion, $repInfo, $crawled = -1) {		
		$reportId = intval($data['report_id']);		
		$sql = "select * from ld_backlinks where report_id=$reportId";
		$sql .= ($crawled != -1) ? " and crawled=$crawled" : "";
		$filter = "";
		if(isset($data['google_pagerank']) && ($data['google_pagerank'] != -1)) {
			$prMax = intval($data['google_pagerank']) + 0.5;
			$prMin = intval($data['google_pagerank']) - 0.5;
			$sql .= " and google_pagerank<$prMax and google_pagerank>=$prMin";
			$filter .= "&google_pagerank=".$data['google_pagerank'];
		} 
		
		// if link type filter selected
		if(!empty($data['link_type'])) {
			$filter .= "&link_type=".$data['link_type'];			
			switch ($data['link_type']) {
				
				case "excellent":
					$sql .= " and link_score > " . LD_LINK_SCORE_EXCEL;
					break;
				
				case "great":
					$sql .= " and link_score > " . LD_LINK_SCORE_GREAT . " and link_score <= " . LD_LINK_SCORE_EXCEL;
					break;
				
				case "good":
					$sql .= " and link_score > " . LD_LINK_SCORE_GOOD . " and link_score <= " . LD_LINK_SCORE_GREAT;
					break;
				
				case "poor":
					$sql .= " and link_score > 0 and link_score <= " . LD_LINK_SCORE_GOOD;
					break;
				
				case "missing":
					$sql .= " and link_title=''"; 
					break;
					
				case "nofollow":
					$sql .= " and nofollow=1";
					break;				
			}
			
		}
		
		if(!empty($data['link_title'])) {
			$linkTitle = urldecode($data['link_title']);
			$filter .= "&link_title=".urlencode($linkTitle);
			$sql .= " and link_title='$linkTitle'"; 
		}
		
		if (!empty($data['search'])) {
			$search = urldecode($data['search']);
			$filter .= "&search=".urlencode($search);
			$sql .= " and url like '%".addslashes($search)."%'"; 
		}
		
		$reportType = $crawled ? "rp_links" : "rp_not_crawled";
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=showreport&report_type=$reportType&report_id=$reportId".$filter;
		$this->set('filter', $filter);
				
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'subcontent', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by link_score DESC,outbound_links ASC,nofollow ASC,link_title ASC";
		
		if(!$exportVersion) $sql .= " limit ".$this->paging->start .",". $this->paging->per_page; 
		
		$reportList = $this->db->select($sql);
		if ($exportVersion) {
			
			$spText = $_SESSION['text'];
			$exportContent = createExportContent(array('', $this->pluginText['Backlinks Reports'], ''));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Report Url'], $repInfo['url']));
			$exportContent .= createExportContent(array($this->pluginText['Last Updated'], date('M d Y H:i:s', strtotime($repInfo['updated']))));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array(
				$spText['common']['No'], $this->pluginText['Backlink Url'],	$this->pluginText['Link Found'],
				$spText['common']['MOZ Rank'], $spText['common']['Domain Authority'], $spText['common']['Page Authority'],
				$this->pluginText['Anchor'], $this->pluginText['Outbound Links'], $spText['label']['Score'], $this->pluginText['Link Type'], 
				$spText['common']['label']['Comments']
			));
			
			foreach($reportList as $i => $listInfo) {
				$scoreInfo = $this->__getLinkType($listInfo);
				$scoreInfo['comments'] = strip_tags(str_replace('<br>', '. ', $scoreInfo['comments']));
				$exportContent .= createExportContent(array(
					$i+1, $listInfo['url'],$listInfo['url_found'],$listInfo['google_pagerank'],$listInfo['domain_authority'],$listInfo['page_authority'],
					$listInfo['link_title'], $listInfo['outbound_links'], $listInfo['link_score'], $scoreInfo['label'], $scoreInfo['comments'],
				));
			}
						
			exportToCsv('backlink_report', $exportContent);
		} else {					
			$this->set('totalResults', $this->db->noRows);					
			$this->set('reportType', $reportType);					
			$this->set('list', $reportList);
			$this->set('pageNo', $_GET['pageno']);
			$this->set('data', $data);
			$this->set('repObj', $this);
			$this->set('reportInfo', $reportInfo);					
			$this->pluginRender('reportlinks');
		}
		
	}
	
	/**
	 * function to get backlink info
	 */
	function __getBacklinkInfo($linkId) {
		$sql = "select * from ld_backlinks where id=". intval($linkId);
		$linkInfo = $this->db->select($sql, true);
		return $linkInfo;
	}
	
	/**
	 * function check a report links and update the details
	 */
	function checkReportLink($linkId) {
		$linkInfo = $this->__getBacklinkInfo($linkId);
		$reportInfo = $this->__getReportInfo($linkInfo['report_id']);
		include_once(PLUGIN_PATH."/crawler.class.php");
		$ldCrawler = New LDCrawler();
		$reportInfo['url'] = $ldCrawler->removeTrailingSlash(formatUrl($reportInfo['url']));
		$pageInfo = $ldCrawler->getBacklinkPageInfo($linkInfo['url'], $reportInfo['url']);
		include_once(SP_CTRLPATH . "/rank.ctrl.php");
		$rankCtrler = new RankController();
		$pageInfo['title'] = !empty($pageInfo['title']) ? $pageInfo['title'] : $linkInfo['link_title'];
		
		// get moz rank details
		include_once(SP_CTRLPATH."/moz.ctrl.php");
		$mozCtrler = new MozController();
		$mozRankList = $mozCtrler->__getMozRankInfo(array(urldecode($linkInfo['url'])));
		$pageInfo['pr'] = !empty($mozRankList[0]['moz_rank']) ? $mozRankList[0]['moz_rank'] : 0;
		$pageInfo['domain_authority'] = !empty($mozRankList[0]['domain_authority']) ? $mozRankList[0]['domain_authority'] : 0;
		$pageInfo['page_authority'] = !empty($mozRankList[0]['page_authority']) ? $mozRankList[0]['page_authority'] : 0;
		
		$pageInfo['google_pagerank'] = $pageInfo['pr'];
		$pageInfo['id'] = $linkInfo['id'];
		$scoreInfo = $this->__getLinkType($pageInfo);
		$pageInfo['link_score'] = $scoreInfo['score'];
		
		$this->updateBacklinkPageInfo($pageInfo);
		$linkInfo = $this->__getBacklinkInfo($linkId);
		$linkInfo['link_type'] = $scoreInfo['label'];
		?>
		<script>
		$('#link_id_<?php echo $linkId?> td:nth-child(2)').html('<?php echo $linkInfo['google_pagerank']?>');
		$('#link_id_<?php echo $linkId?> td:nth-child(3)').html('<?php echo $linkInfo['domain_authority']?>');
		$('#link_id_<?php echo $linkId?> td:nth-child(4)').html('<?php echo $linkInfo['page_authority']?>');
		$('#link_id_<?php echo $linkId?> td:nth-child(5)').html('<?php echo $linkInfo['link_title']?>');
		$('#link_id_<?php echo $linkId?> td:nth-child(6)').html('<?php echo $linkInfo['outbound_links']?>');
		$('#link_id_<?php echo $linkId?> td:nth-child(8)').html('<?php echo $linkInfo['link_type']?>');
		</script>
		<?php
		
	}
	
	/*
	 * function to find out common anchors
	 */
	function __getCommonAnchor($id, $col='report_id', $limit='') {
		$id = intval($id);
		$sql = "SELECT link_title,count(*) count FROM ld_backlinks where $col=$id and link_title!='' group by link_title order by count DESC $limit";
		$list = $this->db->select($sql);
		return $list;
	}
	
	/*
	 * function to show pages indexed and their backlinks reports
	 */
	function showPagesIndexed($data, $exportVersion, $repInfo) {
		
		$reportId = intval($data['report_id']);		
		$sql = "select * from ld_indexed where report_id=$reportId";
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=showreport&report_type=rp_indexed&report_id=$reportId";		
				
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'subcontent', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by id";
		if(!$exportVersion) $sql .= " limit ".$this->paging->start .",". $this->paging->per_page;		 
		
		$reportList = $this->db->select($sql);
		
		// add more details
		foreach($reportList as $i => $listInfo) {
			$reportList[$i]['tot_backlinks'] = $this->__getCountOfReportInfo($listInfo['id'], " and crawled=1", "ld_backlinks", "indexed_id");
			$anchorList = $this->__getCommonAnchor($listInfo['id'], 'indexed_id', ' limit 0,1');
			$reportList[$i]['pop_anchor'] = empty($anchorList[0]['link_title']) ? "N/A" : $anchorList[0]['link_title'];
			$reportList[$i]['tot_nofollow'] = $this->__getCountOfReportInfo($listInfo['id'], " and crawled=1 and nofollow=1", "ld_backlinks", "indexed_id");
			$reportList[$i]['tot_missing'] = $this->__getCountOfReportInfo($listInfo['id'], " and link_title='' and crawled=1", "ld_backlinks", "indexed_id");
			$reportList[$i]['tot_exel'] = $this->__getCountOfReportInfo($listInfo['id'], " and link_score > " . LD_LINK_SCORE_EXCEL, "ld_backlinks", "indexed_id");
			$reportList[$i]['tot_great'] = $this->__getCountOfReportInfo($listInfo['id'], " and link_score > " . LD_LINK_SCORE_GREAT . " and link_score <= " . LD_LINK_SCORE_EXCEL, "ld_backlinks", "indexed_id");
			$reportList[$i]['tot_good'] = $this->__getCountOfReportInfo($listInfo['id'], " and link_score > " . LD_LINK_SCORE_GOOD . " and link_score <= " . LD_LINK_SCORE_GREAT, "ld_backlinks", "indexed_id");	
			$reportList[$i]['tot_poor'] = $this->__getCountOfReportInfo($listInfo['id'], " and link_score > 0 and link_score <= " . LD_LINK_SCORE_GOOD, "ld_backlinks", "indexed_id");		
		}		
		
		// if export report
		if ($exportVersion) {
			
			$spText = $_SESSION['text'];
			$exportContent = createExportContent(array('', $this->pluginText['Indexed Page Reports'], ''));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Report Url'], $repInfo['url']));
			$exportContent .= createExportContent(array($this->pluginText['Last Updated'], date('M d Y H:i:s', strtotime($repInfo['updated']))));
			$exportContent .= createExportContent(array());
			
			$exportContent .= createExportContent(array(
				$spText['common']['No'], $spText['common']['Url'], $this->pluginText['Total Backlinks'], $this->pluginText['Popular Title'],
				$this->pluginText['Excellent'], $this->pluginText['Great'], $this->pluginText['Good'], $this->pluginText['Poor'], $this->pluginText['Nofollow'], $this->pluginText['Missing']
			));
			
			foreach($reportList as $i => $listInfo) {
				$exportContent .= createExportContent(array(
					$i+1, $listInfo['url'],$listInfo['tot_backlinks'],$listInfo['pop_anchor'],
					$listInfo['tot_exel'],$listInfo['tot_great'],$listInfo['tot_good'],$listInfo['tot_poor'],$listInfo['tot_nofollow'],$listInfo['tot_missing']
				));
			}
						
			exportToCsv('indexed_page_report', $exportContent);
		} else {			
			
			$this->set('list', $reportList);
			$this->set('pageNo', $_GET['pageno']);
			$this->set('data', $data);
			$this->set('repObj', $this);
			$this->set('reportInfo', $reportInfo);					
			$this->pluginRender('indexedpagereports');
		}
		
	}
	
	/*
	 * function to backlink anchor reports
	 */
	function showAnchorReports($data, $exportVersion, $repInfo) {
		
		$reportId = intval($data['report_id']);
		$sql = "SELECT report_id,link_title,count(*) count FROM ld_backlinks where crawled=1 and report_id=$reportId and link_title!='' group by link_title";		
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=showreport&report_type=rp_anchor&report_id=$reportId";		
				
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'subcontent', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by count DESC";
		if(!$exportVersion) $sql .= " limit ".$this->paging->start .",". $this->paging->per_page; 
		
		$this->set('pagingStart', $this->paging->start);
		$rList = $this->db->select($sql);
		
	    // find pagerank count
	    $reportList = array();
	    foreach($rList as $reportInfo) {
	    	$linkTitle = addslashes($reportInfo['link_title']);
	    	$reportInfo['tot_excel'] = $this->__getCountOfReportInfo($reportId, " and crawled=1 and link_title='$linkTitle' and link_score > " . LD_LINK_SCORE_EXCEL);
	    	$reportInfo['tot_great'] = $this->__getCountOfReportInfo($reportId, " and crawled=1 and link_title='$linkTitle' and link_score > " . LD_LINK_SCORE_GREAT . " and link_score <= " . LD_LINK_SCORE_EXCEL);
	    	$reportInfo['tot_good'] = $this->__getCountOfReportInfo($reportId, " and crawled=1 and link_title='$linkTitle' and link_score > " . LD_LINK_SCORE_GOOD . " and link_score <= " . LD_LINK_SCORE_GREAT);
	    	$reportInfo['tot_poor'] = $this->__getCountOfReportInfo($reportId, " and crawled=1 and link_title='$linkTitle' and link_score > 0 and link_score <= " . LD_LINK_SCORE_GOOD);
	    	
    		for ($i = 0; $i <= 10; $i++) {
    			$prMax = $i + 0.5;
    			$prMin = $i - 0.5;
    			$reportInfo['tot_pr_'.$i] = $this->__getCountOfReportInfo($reportId, "  and crawled=1 and google_pagerank<$prMax and google_pagerank>=$prMin and link_title='$linkTitle'");
    		}
    		
    		$reportInfo['tot_nofollow'] = $this->__getCountOfReportInfo($reportId, " and nofollow=1 and crawled=1 and link_title='$linkTitle'");
    		$reportList[] = $reportInfo;
	    }
		
		if ($exportVersion) {
			
			$spText = $_SESSION['text'];
			$exportContent = createExportContent(array('', $this->pluginText['Most Popular Anchors'], ''));
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array());
			$exportContent .= createExportContent(array($this->pluginText['Report Url'], $repInfo['url']));
			$exportContent .= createExportContent(array($this->pluginText['Last Updated'], date('M d Y H:i:s', strtotime($repInfo['updated']))));
			$exportContent .= createExportContent(array());
			$headList = array($spText['common']['No'], $spText['label']['Title'], $this->pluginText['Excellent'], $this->pluginText['Great'], $this->pluginText['Good'], $this->pluginText['Poor']);
			
			for ($i=10;$i>=0;$i--) {
			    $headList[] = "PR$i";
			}
			
			$headList[] = $this->pluginText['Nofollow'];
			$headList[] = $spText['common']['Total'];
			$exportContent .= createExportContent($headList);
			foreach($reportList as $i => $listInfo) {
				$listInfo['link_type'] = $this->__getLinkType($listInfo);
				$valList = array($i+1, $listInfo['link_title'], $listInfo['tot_excel'],$listInfo['tot_great'],$listInfo['tot_good'],$listInfo['tot_poor']);			
    			for ($i=10;$i>=0;$i--) {
    			    $valList[] = $listInfo['tot_pr_'.$i];
    			}
    			$valList[] = $listInfo['tot_nofollow'];
    			$valList[] = $listInfo['count'];
				$exportContent .= createExportContent($valList);
			}			
			exportToCsv('anchor_report', $exportContent);
		} else {					
			$this->set('list', $reportList);
			$this->set('pageNo', $_GET['pageno']);
			$this->set('data', $data);
			$this->set('repObj', $this);
			$this->set('reportInfo', $reportInfo);					
			$this->pluginRender('anchorreports');
		}
		
	}
	
	/*
	 * fucn to show report slect box
	 */
	function showReportSelectBox($projectId, $allReports) {
		
		$cond = empty($allReports) ? " and r.status>=1" : "";
		$reportList = $this->__getAllReports($projectId, $cond);
		$this->set('reportList', $reportList);
		$this->set('reportId', empty($reportList[0]['id']) ? 0 : $reportList[0]['id']);
		$this->pluginRender('showreportselectbox');
	}
	
	/*
	 * fucn to show anchor slect box
	 */
	function showAnchorSelectBox($reportId) {
		
		$anchorList = $this->__getCommonAnchor($reportId);
		$this->set('anchorList', $anchorList);
		$this->pluginRender('showanchorselectbox');
	}
	
	/**
	 * function to show link informations
	 */
	function showLinkInfo($linkId) {
		$linkInfo = $this->__getBacklinkInfo($linkId);
		$scoreInfo = $this->__getLinkType($linkInfo);
		$this->set('linkInfo', $linkInfo);
		$this->set('scoreInfo', $scoreInfo);
		$this->pluginRender('showlinkinfo');
	}
}