<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 *
 */
class BRCCampaign extends BulkRankChecker {
	
	// the database table name of the campaigns
	var $tableName = "brc_campaigns";
	var $tableName_link = "brc_links";
	var $tableName_keywords = "brc_keywords";
	var $tableName_searchengines = "brc_searchengines";
	var $tableName_searchresults = "brc_searchresults";
	var $tableName_engines = "searchengines";
	var $keywordCrawledCount = 0;
	
	/*
	 * show campaigns list to manage
	 */
	function showCampaignManager($info = '') {
		$userId = isLoggedIn();
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select c.*, u.username from $this->tableName c,  users u where c.user_id=u.id";
		
		if (isAdmin ()) {
			$userCtrler = new UserController ();
			$userList = $userCtrler->__getAllUsers ();
			$this->set ( 'userList', $userList );
			if (! empty ( $info ['user_id'] )) {
				$pgScriptPath .= "&user_id=" . $info ['user_id'];
				$sql .= " and user_id=" . $info ['user_id'];
				$this->set ( 'userId', $info ['user_id'] );
			}
			$this->set ( 'isAdmin', 1 );
		} else {
			$sql .= " and user_id=$userId";
			$this->set ( 'isAdmin', 0 );
		}
		
		// pagination setup
		$this->db->query ( $sql, true );
		$this->paging->setDivClass ( 'pagingdiv' );
		$this->paging->loadPaging ( $this->db->noRows, SP_PAGINGNO );
		$pagingDiv = $this->paging->printPages ( $pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax' );
		$this->set ( 'pagingDiv', $pagingDiv );
		
		$sql .= " order by c.id limit " . $this->paging->start . "," . $this->paging->per_page;
		$campaignList = $this->db->select ( $sql );
		
		$this->set ( 'list', $campaignList );
		$this->set ( 'pageNo', $_GET ['pageno'] );
		$this->set ( 'pgScriptPath', $pgScriptPath );
		$this->pluginRender ( 'showcampaignsmanager' );
	}
	
	/*
	 * func to create new campaign
	 */
	function newCampaign($info = '') {
		$userId = isLoggedIn();
		
		// if admin select users
		if (isAdmin ()) {
			$userCtrler = new UserController ();
			$userList = $userCtrler->__getAllUsers ();
			$this->set ( 'userList', $userList );
			$this->set ( 'isAdmin', 1 );
		} else {
			
			// check for user type settings
			$pluginUserTypeObj = $this->createHelper("BRCUserType");
			$userTypeCtrl = new UserTypeController();			
			$userTypeSpecList = $userTypeCtrl->getUserTypeSpecByUser($userId, $pluginUserTypeObj->specCategory);
			$this->set('userTypeSpecList', $userTypeSpecList);
			
		}
		
		$langController = new LanguageController ();
		$this->set ( 'langList', $langController->__getAllLanguages () );
		$this->set ( 'langNull', true );
		$countryController = new CountryController ();
		$this->set ( 'countryList', $countryController->__getAllCountries () );
		$this->set ( 'countryNull', true );
		$seController = new SearchEngineController ();
		$this->set ( 'seList', $seController->__getAllSearchEngines () );
		$this->set ( 'sec', 'create' );
		$this->pluginRender ( 'editcampaign' );
	}
	
	/*
	 * function to check whether campaign already exists or not
	 */
	function __checkCampaignName($name, $userId, $campaignId = false) {
		$campaignId = intval($campaignId);
		$userId = intval($userId);
		$sql = "select id from $this->tableName where name='" . addslashes ( $name ) . "'";
		$sql .= $userId ? " and user_id=$userId" : "";
		$sql .= $campaignId ? " and id!=$campaignId" : "";
		$listInfo = $this->db->select ( $sql, true );
		return empty ( $listInfo ['id'] ) ? false : $listInfo ['id'];
	}
	
	/*
	 * func to create campaign
	 */
	function createCampaign($listInfo) {
		$userId = isAdmin () ? intval ( $listInfo ['user_id'] ) : isLoggedIn ();
		$errMsg ['name'] = formatErrorMsg ( $this->validate->checkBlank ( $listInfo ['name'] ) );
		
		// check keywords added
		$list = explode ( ",", $listInfo ['keywords'] );
		$keywordList = array ();
		foreach ( $list as $keyword ) {
			$keyword = trim ( $keyword );
			
			if (! empty ( $keyword )) {
				if (in_array ( $keyword, $keywordList )) {
					$this->validate->flagErr = true;
					$errMsg ['keywords'] = formatErrorMsg ( $this->pluginText ['Duplicate keyword'] . ": $keyword" );
				} else {
					$keywordList [] = $keyword;
				}
			}
		}
		
		$listInfo['keywordList'] = $keywordList;
		
		if (empty ( $keywordList )) {
			$this->validate->flagErr = true;
			$errMsg ['keywords'] = formatErrorMsg ( $_SESSION ['text'] ['common'] ['Entry cannot be blank'] );
		}
		
		// check links added
		$list = explode ( ",", $listInfo ['links'] );
		$linkList = array ();
		
		foreach ( $list as $link ) {
			$link = trim ( $link );
			
			if (! empty ( $link )) {
				if (in_array ( addHttpToUrl ( $link ), $linkList )) {
					$this->validate->flagErr = true;
					$errMsg ['links'] = formatErrorMsg ( $this->pluginText ['Duplicate link'] . ": $link" );
				} else {
					$linkList [] = addHttpToUrl ( $link );
				}
			}
		}
		
		$listInfo['linkList'] = $linkList;
		
		if (empty ( $linkList )) {
			$this->validate->flagErr = true;
			$errMsg ['links'] = formatErrorMsg ( $_SESSION ['text'] ['common'] ['Entry cannot be blank'] );
		}
		
		// check keywords added
		$emailList = explode ( ",", $listInfo ['email_address']);
		$emailAddressList = array();
		foreach ( $emailList as $emailAddress ) {
			$emailAddress = trim($emailAddress);				
			if (!empty($emailAddress)) {
				$errMsg['email_address'] = formatErrorMsg($this->validate->checkEmail($emailAddress));
				$emailAddressList[] = $emailAddress; 
				if (!empty($errMsg['email_address'])) {
					break;
				}
			}
		}
		
		if (empty($errMsg['email_address'])) {
			$listInfo ['email_address'] = implode(",", $emailAddressList);
		}
		
		// check email address added or not
		if ( ($listInfo['send_reports'] != 'Not Send')) {
			$errMsg['email_address'] = formatErrorMsg($this->validate->checkBlank($listInfo ['email_address']));
		}
		
		// check search engines selected
		$listInfo ['searchengines'] = is_array ( $listInfo ['searchengines'] ) ? $listInfo ['searchengines'] : array ();
		$errMsg['searchengines'] = formatErrorMsg ( $this->validate->checkBlank ( implode ( '', $listInfo ['searchengines'] ) ) );

		// check for user type settings
		$pluginUserTypeObj = $this->createHelper("BRCUserType");
		list($errorExist, $errMsg) = $pluginUserTypeObj->validateUserTypeSettings($listInfo, $errMsg);
		
		// if no validation error existing
		if (!$this->validate->flagErr && !$errorExist) {
			
			// if no check for campaign name
			if (!$this->__checkCampaignName ( $listInfo ['name'], $listInfo ['user_id'] )) {
				
				$sql = "insert into $this->tableName(user_id, name, lang_code, country_code, cron_job, report_interval,send_reports,email_address,status, last_generated)
    			values($userId, '" . addslashes ( $listInfo ['name'] ) . "', '" . addslashes ( $listInfo ['lang_code'] ) . "',
    			'" . addslashes ( $listInfo ['country_code'] ) . "', " . intval ( $listInfo ['cron_job'] ) . ", " . intval ( $listInfo ['report_interval'] ) . ",
    			'" . addslashes ( $listInfo ['send_reports'] ) . "','" . addslashes ( $listInfo ['email_address'] ) . "', 1, '0000-00-00')";
				$this->db->query( $sql );
				
				$campaignId = $this->db->getMaxId ( $this->tableName );
				
				// store keywords, links and search engines
				$helperCtrler = new BRCHelper ();
				$helperCtrler->updateCampaignDataLists ( "keyword", $campaignId, $keywordList );
				$helperCtrler->updateCampaignDataLists ( "link", $campaignId, $linkList );
				$helperCtrler->updateCampaignDataLists ( "searchengine", $campaignId, $listInfo ['searchengines'] );
				
				$this->showCampaignManager (array('user_id' => $listInfo ['user_id']));
				exit;
				
			} else {
				$errMsg ['name'] = formatErrorMsg ( $this->pluginText ['campaignexists'] );
			}
		}
		
		$this->set('post', $listInfo );
		$this->set('errMsg', $errMsg );
		$this->newCampaign($listInfo);
	}	
	
	/*
	 * func to edit campaign
	 */
	function editCampaign($campaignId, $listInfo = '') {
		
		if (! empty ( $campaignId )) {
			$userId = isLoggedIn ();
			
			// if admin select users
			if (isAdmin ()) {
				$userCtrler = new UserController ();
				$userList = $userCtrler->__getAllUsers ();
				$this->set ( 'userList', $userList );
				$this->set ( 'isAdmin', 1 );
			} else {
			
				// check for user type settings
				$pluginUserTypeObj = $this->createHelper("BRCUserType");
				$userTypeCtrl = new UserTypeController();			
				$userTypeSpecList = $userTypeCtrl->getUserTypeSpecByUser($userId, $pluginUserTypeObj->specCategory);
				$this->set('userTypeSpecList', $userTypeSpecList);
				
			}
			
			$langController = new LanguageController ();
			$this->set ( 'langList', $langController->__getAllLanguages () );
			$this->set ( 'langNull', true );
			$countryController = new CountryController ();
			$this->set ( 'countryList', $countryController->__getAllCountries () );
			$this->set ( 'countryNull', true );
			$seController = new SearchEngineController ();
			$this->set ( 'seList', $seController->__getAllSearchEngines () );
			
			if (empty ( $listInfo )) {
				$listInfo = $this->__getCampaignInfo ( $campaignId );
				$helperCtrler = new BRCHelper ();
				$list = $helperCtrler->getCampaignDataLists ( "keyword", $campaignId );
				$listInfo ['keywords'] = implode ( ",", $list );
				$list = $helperCtrler->getCampaignDataLists ( "link", $campaignId );
				$listInfo ['links'] = implode ( ",", $list );
				$listInfo ['searchengines'] = $helperCtrler->getCampaignDataLists ( "searchengine", $campaignId );
			}
			
			$this->set ( 'post', $listInfo );
			$this->set ( 'sec', 'update' );
			$this->pluginRender('editcampaign');
			exit ();
		}
	}
	
	/*
	 * func to update campaign
	 */
	function updateCampaign($listInfo) {
		$userId = isAdmin () ? intval ( $listInfo ['user_id'] ) : isLoggedIn ();
		$errMsg ['name'] = formatErrorMsg ( $this->validate->checkBlank ( $listInfo ['name'] ) );
		$errMsg ['keywords'] = formatErrorMsg ( $this->validate->checkBlank ( $listInfo ['keywords'] ) );
		$errMsg ['links'] = formatErrorMsg ( $this->validate->checkBlank ( $listInfo ['links'] ) );
		$campaignId = intval ( $listInfo ['id'] );
		$this->set ( 'post', $listInfo );
		
		// check search engines selected
		$listInfo ['searchengines'] = is_array ( $listInfo ['searchengines'] ) ? $listInfo ['searchengines'] : array ();
		$errMsg ['searchengines'] = formatErrorMsg ( $this->validate->checkBlank ( implode ( '', $listInfo ['searchengines'] ) ) );
		
		// check keywords added
		$list = explode ( ",", $listInfo ['keywords'] );
		$keywordList = array ();
		foreach ( $list as $keyword ) {
			$keyword = trim ( $keyword );
			
			if (! empty ( $keyword )) {
				if (in_array ( $keyword, $keywordList )) {
					$this->validate->flagErr = true;
					$errMsg ['keywords'] = formatErrorMsg ( $this->pluginText ['Duplicate keyword'] . ": $keyword" );
				} else {
					$keywordList [] = $keyword;
				}
			}
		}
		
		$listInfo['keywordList'] = $keywordList;
		
		if (empty ( $keywordList )) {
			$this->validate->flagErr = true;
			$errMsg ['keywords'] = formatErrorMsg ( $_SESSION ['text'] ['common'] ['Entry cannot be blank'] );
		}
		
		// check links added
		$list = explode ( ",", $listInfo ['links'] );
		$linkList = array ();
		foreach ( $list as $link ) {
			$link = trim ( $link );
			
			if (! empty ( $link )) {
				if (in_array ( addHttpToUrl ( $link ), $linkList )) {
					$this->validate->flagErr = true;
					$errMsg ['links'] = formatErrorMsg ( $this->pluginText ['Duplicate link'] . ": $link" );
				} else {
					$linkList [] = addHttpToUrl ( $link );
				}
			}
		}
		
		$listInfo['linkList'] = $linkList;
		
		if (empty ( $linkList )) {
			$this->validate->flagErr = true;
			$errMsg ['links'] = formatErrorMsg ( $_SESSION ['text'] ['common'] ['Entry cannot be blank'] );
		}

		// check keywords added
		$emailList = explode ( ",", $listInfo ['email_address']);
		$emailAddressList = array();
		foreach ( $emailList as $emailAddress ) {
			$emailAddress = trim($emailAddress);
			if (!empty($emailAddress)) {
				$errMsg['email_address'] = formatErrorMsg($this->validate->checkEmail($emailAddress));
				$emailAddressList[] = $emailAddress;
				if (!empty($errMsg['email_address'])) {
					break;
				}
			}
		}
		
		if (empty($errMsg['email_address'])) {
			$listInfo ['email_address'] = implode(",", $emailAddressList);
		
			// check email address added or not
			if ( ($listInfo['send_reports'] != 'Not Send')) {
				$errMsg['email_address'] = formatErrorMsg($this->validate->checkBlank($listInfo ['email_address']));
			}
		}

		// check for user type settings
		$pluginUserTypeObj = $this->createHelper("BRCUserType");
		list($errorExist, $errMsg) = $pluginUserTypeObj->validateUserTypeSettings($listInfo, $errMsg, $campaignId);
		
		if (!$errorExist && !$this->validate->flagErr) {
			if (!$this->__checkCampaignName( $listInfo ['name'], $listInfo ['user_id'], $campaignId )) {
				$sql = "update $this->tableName set
				user_id=$userId,
				name = '" . addslashes ( $listInfo ['name'] ) . "',
				lang_code = '" . addslashes ( $listInfo ['lang_code'] ) . "',
				country_code = '" . addslashes ( $listInfo ['country_code'] ) . "',
				cron_job = " . intval ( $listInfo ['cron_job'] ) . ",
				report_interval = " . intval ( $listInfo ['report_interval'] ) . ",
                send_reports='" . addslashes ( $listInfo ['send_reports'] ) . "',
                email_address='" . addslashes ( $listInfo ['email_address'] ) . "'
				where id=$campaignId";
				$this->db->query ( $sql );
				
				// store keywords, links and search engines
				$helperCtrler = new BRCHelper ();
				$helperCtrler->updateCampaignDataLists( "keyword", $campaignId, $keywordList );
				$helperCtrler->updateCampaignDataLists( "link", $campaignId, $linkList );
				$helperCtrler->updateCampaignDataLists( "searchengine", $campaignId, $listInfo ['searchengines'] );
				$this->showCampaignManager( $listInfo );
				exit ();
			} else {
				$errMsg ['name'] = formatErrorMsg ( $this->pluginText ['campaignexists'] );
			}
		}
		
		$this->set ( 'errMsg', $errMsg );
		$this->editCampaign ( $campaignId, $listInfo );
	}
	
	/*
	 * func to delete campaign
	 */
	function deleteCampaign($campaignId) {
		$campaignId = intval($campaignId);
		$sql = "delete from $this->tableName where id=$campaignId";
		$this->db->query( $sql );
		
		// delete search results, keywords, links and search engines
		$helperCtrler = new BRCHelper ();
		$helperCtrler->deleteAllSearchResults( $campaignId );
		$helperCtrler->deleteAllKeywords( $campaignId );
		$helperCtrler->deleteAllLinks( $campaignId );
		$helperCtrler->deleteAllSearchEngines( $campaignId );
	}
	
	/*
	 * func to change status
	 */
	function __changeStatus($campaignId, $status) {
		$sql = "update $this->tableName set status=$status where id=" . intval ( $campaignId );
		$this->db->query ( $sql );
	}
	
	/*
	 * func to get all campaigns
	 */
	function getAllCampaigns($condtions = '') {
		$sql = "select c.* from $this->tableName c, users u where c.user_id=u.id and c.status=1 and u.status=1";
		$sql .= empty ( $condtions ) ? " order by name" : $condtions;
		$campaignList = $this->db->select ( $sql );
		return $campaignList;
	}
	
	/*
	 * func to get campaign info
	 */
	function __getCampaignInfo($campaignId) {
		$sql = "select c.*,u.username from $this->tableName c,users u where  c.user_id=u.id and c.id=" . intval ( $campaignId );
		$info = $this->db->select ( $sql, true );
		return $info;
	}
	
	/*
	 * function update a single column of campaign
	 */
	function updateCampaignData($campaignId, $dataCol, $dataValue) {
		$sql = "update $this->tableName set $dataCol='" . addslashes( $dataValue ) . "' where id=".intval($campaignId);
		$this->db->query( $sql );
	}
	
	/*
	 * func to check whether report can be generated for campaign
	 */
	function isGenerateReportsForCampaign($campaignInfo) {
		
		$genReport = false;		
		if ($campaignInfo ['report_interval'] > 0) {
			
			$lastGeneratedTime = strtotime($campaignInfo['last_generated']);
			$currentDateTime = time();

			if ($lastGeneratedTime < $currentDateTime) {
			
				// if monthly interval generate on first of each month
				if ($campaignInfo ['report_interval'] == 30) {
					$genReport = (date ( 'd' ) == 1) ? true : false;
				} else {
					$nextGenTime = $lastGeneratedTime + ($campaignInfo ['report_interval'] * 86400);
					$genReport = (time() > $nextGenTime) ? true : false;
				}
			}
			
		}
		
		return $genReport;
	}
	
	/*
	 * function used generate cron reports for a campaigns
	 */
	function startCampaignJOb($data) {
		
		// for each through campaign list
		$campaignList = $this->getAllCampaigns (" and cron_job=1 order by id DESC" );
		$this->keywordCrawledCount = 0;
		
		// loop through campaigns
		foreach ( $campaignList as $campaignInfo ) {
			
			// check whether reports needs to be generated for campaign
			if (! $this->isGenerateReportsForCampaign($campaignInfo )) {
				continue;
			}
			
			// generate report
			$completed = $this->generateCampaignReport($campaignInfo, true);
			
			// if report generation for campain completed
			if ($completed) {
				
				// send email reports if enabled
				if ($campaignInfo['send_reports'] != "Not Send") {
					
					$userController =  New UserController();
					$userInfo = $userController->__getUserInfo($campaignInfo['user_id']);
					
					$emailAddress = $campaignInfo['email_address'];
					$brcReportCtrler = new BRCReport();
					$brcReportCtrler->pluginText = $this->pluginText;
					
					$searchInfo = array(
						'campaign_id' => $campaignInfo['id'],
						'from_time' => date('Y-m-d', strtotime($campaignInfo ['last_generated'])),
						'to_time' => date("Y-m-d"),
					);
					
					$this->set('campaignName', $campaignInfo['name']);
					$this->set('campaignId', $campaignInfo['id']);
					$this->set('fromTime', $searchInfo['from_time']);
					$this->set('toTime', $searchInfo['to_time']);
					
					$reportTexts = $this->getLanguageTexts('reports', $userInfo['lang_code']);
        			$this->set('reportTexts', $reportTexts);
        			$this->set('loginTexts', $this->getLanguageTexts('login', $userInfo['lang_code']));
        			$subject = $reportTexts['report_email_subject'] . " - " . $campaignInfo['name'];
        			
        			$adminInfo = $userController->__getAdminInfo();
        			$adminName = $adminInfo['first_name']."-".$adminInfo['last_name'];
        			$attachment = $reportContent = "";
					
        			// check which type of reports needs to be sent
					if ($campaignInfo ['send_reports'] == "Html") {
						$reportContent = $brcReportCtrler->showReportSummary($searchInfo, true);
					} elseif ($campaignInfo ['send_reports'] == "Pdf") {
						$searchInfo['doc_type'] = 'export';
						$searchInfo['type_export'] = 'pdf';
						$attachment = $brcReportCtrler->showReportSummary($searchInfo, true);
					} else if ($campaignInfo ['send_reports'] == "CSV") {
						$searchInfo['doc_type'] = 'export';
						$searchInfo['type_export'] = 'csv';
						$attachment = $brcReportCtrler->showReportSummary($searchInfo, true);
					}
					
					// send mail using assigned contents
					$this->set('reportContent', $reportContent);
					$this->view->data = $this->data;
					$content = $this->view->getPluginViewContent('email_reports');
					if (sendMail($adminInfo['email'], $adminName, $emailAddress, $subject, $content, $attachment)) {
						echo "Successfully sent reports to $emailAddress\n<br>";
					} else {
						echo "Error occured while sending mails to $emailAddress\n<br>";
					}
					
				}
				
				echo "Report generation of camapaign - {$campaignInfo['name']} completed!<br>\n";
				$this->updateCampaignData($campaignInfo['id'], "last_generated", date( "Y:m:d H:i:s"));
				
			} else {
				break;
			}
		}
	}
	/*
	 * function to generate report for a campaign
	 */
	function generateCampaignReport($campaignInfo, $cronJob = true) {
		$date = date( 'Y-m-d' );
		$campaignId = $campaignInfo['id'];
		$helperCtrler = new BRCHelper();
		$maxKeywordCount = $cronJob ? BRC_NUMBER_OF_KEYWORDS_CRON : 1;
		
		// select keywords with out reports
		$campaignInfo['keywords'] = $helperCtrler->getKeywordsWithOutReports($campaignId, $campaignInfo['last_generated'] );
		
		// if no keywords to generate reports,then campaign is complete
		if (empty($campaignInfo['keywords'])) { return true; }
		
		$campaignInfo ['links'] = $helperCtrler->getCampaignDataLists ("link", $campaignId, true );
		$campaignInfo ['searchengines'] = $helperCtrler->getCampaignDataLists ( "searchengine", $campaignId, true );
		$seController = new SearchEngineController ();
		$seList = $seController->__getAllCrawlFormatedSearchEngines ();
		
		// for each through keywords
		foreach ( $campaignInfo ['keywords'] as $keywordId => $keyword ) {
			
			// for each through search engines
			foreach ( $campaignInfo ['searchengines'] as $searchEngineId ) {
				$keywordInfo = array ();
				$keywordInfo ['name'] = $keyword;
				$keywordInfo ['country_code'] = $campaignInfo ['country_code'];
				$keywordInfo ['lang_code'] = $campaignInfo ['lang_code'];
				$keywordInfo ['searchengines'] = $searchEngineId;
				$linkList = $campaignInfo['links'];
				$keywordInfo ['url'] = array_shift( $linkList );
				
				include_once (SP_CTRLPATH . "/report.ctrl.php");
				$reportCtrler = new ReportController();
				$reportCtrler->showAll = 1;
				$reportCtrler->seList = $seList;
				$crawlResult = $reportCtrler->crawlKeyword( $keywordInfo );
				$resultList = array ();
				
				if (!empty($crawlResult[$searchEngineId]['status'])) {
					$resultList = $crawlResult[$searchEngineId]['matched'];
				}
				
				// foreach through the links
				foreach ($campaignInfo['links'] as $linkId => $link ) {
					
					$link = formatUrl($link, false);
					$resultInfo = array();
					$resultInfo ['keyword_id'] = $keywordId;
					$resultInfo ['campaign_id'] = $campaignId;
					$resultInfo ['searchengine_id'] = $searchEngineId;
					$resultInfo ['link_id'] = $linkId;
					
					if (empty( $resultList )) {
						$resultInfo ['rank'] = -1;
					} else {
						
						// for each through the results
						foreach ( $resultList as $rInfo ) {
							
							// check for link exist or not
							if (stristr($rInfo['url'], "http://" . $link) || stristr($rInfo['url'], "https://" . $link)) {
								$resultInfo['rank'] = $rInfo['rank'];
								$resultInfo['link_found'] = $rInfo['url'];
								break;
							} else {
								$resultInfo['rank'] = 0;
							}
							
						}
					}
					
					// save results
					$this->saveSearchResult( $resultInfo );
				}
				
				echo "Saved results of keyword: $keyword for search engine: " . $reportCtrler->seList [$searchEngineId] ['domain'] . "<br/>\n";
			}
			
			// check whether the number keywords crawled needs to be checked
			if ($maxKeywordCount > 0) {
				$this->keywordCrawledCount++;
				if ($this->keywordCrawledCount >= $maxKeywordCount) {
					if ($cronJob) echo "Reached maximun number of keywords:" . $maxKeywordCount . "\n";
					return false;
				}
			}
		}
		
		return true;
	}
	
	/*
	 * function to save search results
	 */
	function saveSearchResult($listInfo) {
		
		if (!empty( $listInfo )) {
			$date = date('Y-m-d');
			$sql = "INSERT INTO $this->tableName_searchresults(keyword_id, campaign_id, searchengine_id, link_id, rank, link_found, time)
            VALUES(" . intval ( $listInfo ['keyword_id'] ) . ", " . intval ( $listInfo ['campaign_id'] ) . ", " . intval ( $listInfo ['searchengine_id'] ) . ",
            " . intval ( $listInfo ['link_id'] ) . ", " . intval ( $listInfo ['rank'] ) . ", '" . addslashes ( $listInfo ['link_found'] ) . "', '$date')";
			$this->db->query( $sql );
		}
		
	}
	
	/**
	 * function to generate campaign report and save to db
	 * @param array $info        	
	 */
	function showRunCampaign($info) {
		$helperCtrler = new BRCHelper();
		$campaignInfo = $this->__getCampaignInfo($info['campaign_id']);
		$campaignId = $campaignInfo['id'];
		
		$keywordList = $helperCtrler->getCampaignDataLists("keyword", $campaignId );
		$campaignInfo['keyword_count'] = count($keywordList);
		$isGenerateReports = $this->isGenerateReportsForCampaign($campaignInfo);
		
		// if report needs to be generated
		if ($isGenerateReports) {
			$crawledKeywordList = $helperCtrler->getKeywordsWithOutReports($campaignId, $campaignInfo['last_generated']);
			$campaignInfo['crawled_keyword_count'] = $campaignInfo['keyword_count'] - count($crawledKeywordList);
			$campaignInfo['crawling_keyword'] = reset($crawledKeywordList);
		} else {
			$campaignInfo['crawled_keyword_count'] = $campaignInfo['keyword_count'];
			$campaignInfo['crawling_keyword'] = "";
			$completed = true;
		}
		
		$this->set('campaignInfo', $campaignInfo);	    
		$this->set('completed', $completed);
		$this->pluginRender('showruncampaign');
		
	}

	// function to run project, save blog links to database
	function runCampaign($campaignId) {
		$helperCtrler = new BRCHelper();
		$campaignInfo = $this->__getCampaignInfo($campaignId);
		$isGenerateReports = $this->isGenerateReportsForCampaign($campaignInfo);
		$completed = false;
		
		// if report needs to be generated
		if ($isGenerateReports) {
			
			$this->generateCampaignReport($campaignInfo, False);
			$crawlKeywordList = $helperCtrler->getKeywordsWithOutReports($campaignId, $campaignInfo['last_generated']);
			$keywordList = $helperCtrler->getCampaignDataLists("keyword", $campaignId);
			$campaignInfo['keyword_count'] = count($keywordList);
			
			// if keywords to be crawled
			if (!empty($crawlKeywordList)) {
				$campaignInfo['crawled_keyword_count'] = $campaignInfo['keyword_count'] - count($crawlKeywordList);
				$campaignInfo['crawling_keyword'] = reset($crawlKeywordList);				
			} else {
				$completed = true;
				$campaignInfo['crawled_keyword_count'] = $campaignInfo['keyword_count'];
				$campaignInfo['crawling_keyword'] = "";
			}
			
			updateJsLocation('crawled_keywords', $campaignInfo['crawled_keyword_count']);
			updateJsLocation('crawling_keyword', $campaignInfo['crawling_keyword']);
			 
		} else {
			$completed = true;
		}
		
		$this->set('campaignInfo', $campaignInfo);
		$this->set('completed', $completed);
		$this->pluginRender('runcampaign');
		
	}
}
?>