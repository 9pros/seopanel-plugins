<?php
/**
 * Description of BRCReport
 *
 * @author Raheela Muneer
 */
class BRCReport extends BulkRankChecker {
	
	var $tableName = "brc_searchresults";
	var $tableName_engine = "searchengines";
	var $tableName_link = "brc_links";
	var $tableName_keywords = "brc_keywords";
	var $tableName_campaigns = "brc_campaigns";
	
	function detailedViewFilter($info = '') {
	
		$userId = isLoggedIn();
		$campaignCtrler = New BRCCampaign();
		$helperCtrler = new BRCHelper();
	
		// if admin logged in
		if (isAdmin()) {
			$campaignList = $campaignCtrler->getAllCampaigns();
		}  else {
			$campaignList = $campaignCtrler->getAllCampaigns(" and u.id=".intval($userId));
		}
	
		$campaignId = empty($info['campaign_id']) ? $campaignList[0]['id'] : $info['campaign_id'];
	
		// if capmpaign found
		if ($campaignId) {
	
			$this->set('campaignList', $campaignList);
			$this->set('campaignId', $campaignId);
				
			// get all keywords
			$keywordList = $helperCtrler->getCampaignDataLists("keyword", $campaignId, true);
			$this->set('keywordList', $keywordList);
			$keywordId = isset($info['keyword_id']) ? intval($info['keyword_id']) : key($keywordList);
			$this->set('keywordId', $keywordId);
			$this->set('keywordNull', false);
				
			$seController = New SearchEngineController();
			$seList = $seController->__getAllSearchEngines();
			$seId = isset($info['se_id']) ? intval($info['se_id']) : $seList[0]['id'];
			$this->set('seId', $seId);
			$this->set('seList', $seList);
			$this->set('seNull', false);
				
			// get from time
			if (empty( $info['from_time'] )) {
				$info['from_time'] = SP_DEMO ?  "2018-04-23" : date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 7, date('Y')));
			}
				
			// get to time
			if (empty( $info['to_time'] )) {
				$info['to_time'] = SP_DEMO ?  "2018-04-24" : date("Y-m-d");
			}
			
			// assign campaign details
			$cmpLinkList = $helperCtrler->getCampaignDataLists("link", $campaignId, true);
			$this->set('cmpLinkList', $cmpLinkList);
			$this->set('cmpLinkId', isset($info['link_id']) ? intval($info['link_id']) : 0);

			$this->set ('info', $info);
			$action = ($info['action'] == 'reportDetail') ? 'showDetailedReport' : 'showGraphicalReport';
			$submitAction = pluginPOSTMethod('search_form', 'subcontent', "action=$action");
			$this->set('submitAction', $submitAction);
			$this->set('onChange', $submitAction);
			$this->pluginRender('view_detailed_filter' );
				
		} else {
			showErrorMsg("No campaigns found.");
		}
	}
	
	function viewFilter($info = '') {
		
		$userId = isLoggedIn();
		$campaignCtrler = New BRCCampaign();
		$helperCtrler = new BRCHelper ();
		
		// if admin logged in
		if (isAdmin()) {
			$campaignList = $campaignCtrler->getAllCampaigns();
		}  else {
			$campaignList = $campaignCtrler->getAllCampaigns(" and u.id=".intval($userId));
		}

		$campaignId = empty($info['campaign_id']) ? $campaignList[0]['id'] : $info['campaign_id'];
		
		// if capmpaign found
		if ($campaignId) {
		
			$this->set('campaignList', $campaignList);
			$this->set('campaignId', $campaignId);
			
			// get all keywords
			$keywordList = $helperCtrler->getCampaignDataLists("keyword", $campaignId, true);
			$this->set('keywordList', $keywordList);
			$keywordId = isset($info['keyword_id']) ? intval($info['keyword_id']) : key($keywordList);
			$this->set('keywordId', $keywordId);
			
			$seController = New SearchEngineController();
			$seList = $seController->__getAllSearchEngines();
			$this->set('seList', $seList);
			$this->set('seNull', true);
			
			// get from time
			if (empty( $info['from_time'] )) {
				$info['from_time'] = SP_DEMO ?  "2018-04-23" : date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
			}
				
			// get to time
			if (empty( $info['to_time'] )) {
				$info['to_time'] = SP_DEMO ?  "2018-04-24" : date("Y-m-d");
			}
			
			$this->set ('fromTime', $info['from_time']);
			$this->set ('toTime', $info['to_time']);
			$submitAction = pluginPOSTMethod('search_form', 'subcontent', 'action=showreport');
			$this->set('submitAction', $submitAction);
			$this->set('onChange', $submitAction);
			$this->pluginRender('viewfilter' );
			
		} else {
			showErrorMsg("No campaigns found.");
		}
	}
	
	/**
	 * function to get informations to generate report
	 */
	function _getReportInfo($info, $detailed = false) {
		$sql = "select *, `time` report_date from $this->tableName where campaign_id=".intval($info['campaign_id']);
		$sql .= empty($info['se_id']) ? "" : " and searchengine_id=".intval($info['se_id']);
		$sql .= empty($info['keyword_id']) ? "" : " and keyword_id=".intval($info['keyword_id']);
		$sql .= empty($info['link_id']) ? "" : " and link_id=".intval($info['link_id']);
		
		if ($detailed) {
			$sql .= " and `time`>='".addslashes($info['from_time'])."' and `time`<='".addslashes($info['to_time'])."'";
		} else {
			$sql .= " and (`time`='".addslashes($info['from_time'])."' or `time`='".addslashes($info['to_time'])."')";
		}
		
		$sql .= " order by `time`";
		$reportList = $this->db->select($sql);
		
		if ($detailed) {
			return $reportList;
		}
		
		// loop through reports
		$repDetailList = array();
		foreach ($reportList as $reportInfo) {
			
			$seId = $reportInfo['searchengine_id'];
			$date = $reportInfo['report_date'];
			$rank = $reportInfo['rank'];
			$linkId = $reportInfo['link_id'];
			$keywordId = $reportInfo['keyword_id'];
			$repDetailList['reports'][$keywordId][$linkId][$seId][$date] = $rank;
			$repDetailList['se_list'][$seId] = 1;
		}
		
		return $repDetailList;
	}
	
	/**
	 * function to get detailed report information
	 */
	function _getDetailedReportInfo($info) {
		
		// get reports details
		$reportList = $this->_getReportInfo($info, true);
		$repDetailList = array();
		$rankDiff = array();
		
		// loop through report list
		foreach ($reportList as $reportInfo) {
			$linkId = $reportInfo['link_id'];
			$rankDiff = 0;
				
			// if previous rank available
			if (isset($rankDiffList[$linkId . "_rank"])) {
				$rankDiff = $rankDiffList[$linkId . "_rank"] - $reportInfo['rank'];
			}
		
			$repDetailList[$reportInfo['report_date']][$linkId] = array(
					'rank' => $reportInfo['rank'],
					'link_found' => $reportInfo['link_found'],
					'rank_diff' => $rankDiff,
			);
				
			// if no error occured while crawling
			if ($reportInfo['rank'] >= 0) {
				$rankDiffList[$linkId . "_rank"] = $reportInfo['rank'];
			}
				
		}
		
		return $repDetailList;
		
	}
	
	/**
	 * function to show detailed report
	 */
	function showDetailedReport($info) {
	
		// get detailed reports
		$repDetailList = $this->_getDetailedReportInfo($info);
		krsort($repDetailList);
	
		// if empty reports, show error message
		if (empty($repDetailList)) {
			showErrorMsg($_SESSION['text']['common']['No Records Found']);
		}
		
		// assign campaign details
		$helperCtrler = new BRCHelper();
		$cmpLinkList = $helperCtrler->getCampaignDataLists("link", $info['campaign_id'], true);
		$this->set('cmpLinkList', $cmpLinkList);
		
		$this->set('repDetailList', $repDetailList);
		$this->pluginRender('show_detailed_report');
	
	}
	
	/**
	 * function to show graphical report
	 */
	function showGraphicalReport($info) {
	
		// get detailed reports
		$repDetailList = $this->_getDetailedReportInfo($info);
	
		// if empty reports, show error message
		if (empty($repDetailList)) {
			showErrorMsg($_SESSION['text']['common']['No Records Found']);
		}
		
		// assign campaign details
		$helperCtrler = new BRCHelper();
		$linkList = $helperCtrler->getCampaignDataLists("link", $info['campaign_id'], true);
		$cmpLinkList = $linkList;
		
		// if link id is passed
		if (!empty($info['link_id'])) {
			$cmpLinkList = array($info['link_id'] => $linkList[$info['link_id']]);
		}
		
		$dataArr = "['Date', '" . implode("', '", array_values($cmpLinkList)) . "']";
		
		// loop through data list
		foreach ($repDetailList as $date => $dataInfo) {
		    
			$valStr = "";
			foreach (array_keys($cmpLinkList) as $linkId) {
				$valStr .= ", ";
				$valStr .= !empty($dataInfo[$linkId]['rank']) ? $dataInfo[$linkId]['rank'] : 100;
			}
		
			$dataArr .= ", ['$date' $valStr]";
		}
		
		$this->set('reverseDir', true);
		$this->set('dataArr', $dataArr);
		$spTextKeyword = $this->getLanguageTexts('keyword', $_SESSION['lang_code']);
		$this->set('graphTitle', $spTextKeyword["Keyword Position Report"]);
		$graphContent = $this->getViewContent('report/graph');
		print $graphContent;
		
	}
	
	/**
	 * function to show and generate report
	 */
	function showReportSummary($info, $saveFile = false) {
		
		// get reports details
		$repDetailList = $this->_getReportInfo($info);
		
		// if empty reports, show error message
		if (empty($repDetailList)) {
			showErrorMsg($_SESSION['text']['common']['No Records Found']);	
		}
		
		// set campaign name
		$campaignCtrler = New BRCCampaign();
		$campaignInfo = $campaignCtrler->__getCampaignInfo($info['campaign_id']);
		$this->set('campaignName', $campaignInfo['name']);
		
		// get search engine list
		$requiredSEList = array_keys($repDetailList['se_list']);
		$seController = New SearchEngineController();
		$allSEList = $seController->__getAllSearchEngines();
		$this->set('seId', $info['se_id']);

		// assign campaign details
		$helperCtrler = new BRCHelper();
		$cmpLinkList = $helperCtrler->getCampaignDataLists("link", $info['campaign_id'], true);
		$this->set('cmpLinkList', $cmpLinkList);
		$cmpKwdList = $helperCtrler->getCampaignDataLists("keyword", $info['campaign_id'], true);
		$this->set('cmpKwdList', $cmpKwdList);
		$this->set ('fromTime', $info['from_time']);
		$this->set ('toTime', $info['to_time']);
		
		// loop through the all se list
		$seList = array();
		foreach ($allSEList as $seInfo) {
			if (in_array($seInfo['id'], $requiredSEList)) {
				$seList[$seInfo['id']] = formatUrl($seInfo['domain']);
			}
		}
		
		// assign se list
		$this->set('seList', $seList);
		$this->set('repDetailList', $repDetailList['reports']);
		$this->set('keywordId', $info['keyword_id']);
		$this->set('campaignId', $info['campaign_id']);
		$exportFile = 'campaign_report_' . str_replace(' ', '_', $campaignInfo['name']);
		
		// switch through doc type
		switch ($info['doc_type']) {
				
			case "export" :
				
				// create headers
				$spText = $_SESSION['text'];
				$headerList = array($spText['common']['Keyword'], $spText['common']['Url']);
				$headerList = array_merge($headerList, $seList);
		
				// check pdf or csv export type
				if ($info ['type_export'] == 'pdf') {
					
					define('FPDF_FONTPATH', SP_PLUGINPATH."/$this->directoryName/fpdf/font");
					require (PLUGIN_PATH . '/fpdf/cellfit.php');
					$pdf = new FPDF_CellFit();
					$pdf->AddPage();
					$pdf->SetFont('Arial', 'B', 16 );
					$pdf->Cell(60);
					$pdf->Cell(60, 10, $this->pluginText['Campaign Report'], 0, 1, 'C' );
					$pdf->Ln();
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(30, 10, $this->pluginText['Campaign'].': ', 0, 0, 'R');
					$pdf->SetFont('Arial', '', 9);
					$pdf->Cell( 10, 10, $campaignInfo['name']);
					$pdf->Ln ();
					$pdf->SetFont('Arial', 'B', 8);
					$pdf->Cell(30, 10, $spText['common']['Period'] . ': ', 0, 0, 'R');
					$pdf->SetFont('Arial', '', 9);
					$pdf->Cell ( 10, 10, $info['from_time'] . " - " . $info['to_time']);
					$pdf->Ln ();
					
					// create headers
					$wdList = array(50, 75);
					$pdf->SetFont('Arial', 'B', 8);
					foreach ($headerList as $i => $header) {
						$lnWidth = ($i > 1) ? 17 : $wdList[$i];
						$pdf->Cell($lnWidth, 7, $header, 1, 0, 'C' );
					}
					
					$pdf->Ln();
					$pdf->SetFont('Arial', '', 7);
										
					// loop through the keyword reports
					foreach($repDetailList['reports'] as $kwId => $list) {
					
						// loop through link reports
						foreach ($list as $linkId => $listInfo) {
								
							$dataList = array($cmpKwdList[$kwId], $cmpLinkList[$linkId]);
								
							// loop through search engine reports
							foreach ($seList as $searchEngineId => $seName) {
								$rankStr = $this->getRankCompInfo($listInfo, $searchEngineId, $info['from_time'], $info['to_time']);
								$dataList[] = $rankStr;
							}
							
							// create data
							foreach ($dataList as $i => $dataVal) {
								$lnWidth = ($i > 1) ? 17 : $wdList[$i];
								$align = ($i > 1) ? "C" : "L";

								// if rank difference there
								if (stristr($dataVal, '(')) {
									$rInfo = explode('(', $dataVal);
									$rank = trim($rInfo[0]);
									$rankDiff = trim(str_replace(")", "", $rInfo[1]));
									$xCord = $pdf->GetX();
									$yCord = $pdf->GetY();
									$pdf->Cell($lnWidth, 7, $rank, 1, 0, $align);
									
									// if negative value show red color
									if ($rankDiff < 0) {
										$pdf->SetTextColor(235, 47, 59);
									} else {
										$pdf->SetTextColor(8, 161, 28);
									}
									
									$pdf->SetXY($xCord, $yCord);
									$pdf->Cell($lnWidth, 7, "            ($rankDiff)", 1, 0, $align);
									$pdf->SetTextColor(0, 0, 0);
									
								} else {								
									$pdf->Cell($lnWidth, 7, $dataVal, 1, 0, $align);
								}
								
							}
							
							$pdf->Ln ();
						}
					
					}
					
					// ouput pdf file
					if ($saveFile) {
						$fileName = SP_TMPPATH . "/$exportFile.pdf";
						$pdf->Output($fileName, "F");
						return $fileName;
					} else {
						$pdf->Output();
						ob_end_flush();
					}
										
				} else {
					
					// create csv export content
					$exportContent = createExportContent ( array('', $this->pluginText['Campaign Report'], '') );
					$exportContent .= createExportContent (array());
					$exportContent .= createExportContent (array());
					$exportContent .= createExportContent (array($this->pluginText['Campaign'] . ': ' . $campaignInfo['name']));
					$exportContent .= createExportContent (array($spText['common']['Period'] . ': ' . $info['from_time'] . " - " . $info['to_time']));
					$exportContent .= createExportContent (array());

					//create headers
					$exportContent .= createExportContent($headerList);
					
					// loop through the keyword reports
					foreach($repDetailList['reports'] as $kwId => $list) {
								
						// loop through link reports
						foreach ($list as $linkId => $listInfo) {
							
							$dataList = array($cmpKwdList[$kwId], $cmpLinkList[$linkId]);
							
							// loop through search engine reports
							foreach ($seList as $searchEngineId => $seName) {								
								$rankStr = $this->getRankCompInfo($listInfo, $searchEngineId, $info['from_time'], $info['to_time']);
								$dataList[] = $rankStr;
								
							}
						
							// create csv content for data
							$exportContent .= createExportContent($dataList);
						}
						
					}
					
					// create export file
					if ($saveFile) {
						$fileName = SP_TMPPATH . "/$exportFile.csv";
						$fp = fopen($fileName, 'w');
						fwrite($fp, $exportContent);
						fclose($fp);
						return $fileName;
					} else {
						exportToCsv($exportFile, $exportContent);
					}
					
				}
		
				break;
		
			case "print" :
				$this->set('printVersion', true);
				break;
		}
		
		// render report summary page
		if ($saveFile) {
			$this->view->data = $this->data;
			return $this->view->getPluginViewContent('showreport');
		} else {
			$this->pluginRender('showreport');
		}
		
	}
	
	function getRankCompInfo($listInfo, $searchEngineId, $fromTime, $toTime) {
		
		// check wheteher crawling done for this time
		if (isset($listInfo[$searchEngineId][$toTime])) {
		
			$fromRank = $listInfo[$searchEngineId][$fromTime];
			$toRank = $listInfo[$searchEngineId][$toTime];
		
			// check whether crawling failed
			if ($toRank == -1) {
				$rankStr = "Fail";
			} else {
		
				$rankStr = $toRank;
		
				// check from rank values and find rank diff
				if ( $fromRank <= 0 ) {
					$rankDiff = 0;
				} else {
					$rankDiff = $toRank ? ($fromRank - $toRank) : ($fromRank * -1);
				}
		
				// if rank diff not zero
				if ($rankDiff != 0) {
					$rankStr .= " ($rankDiff)";
				}
		
			}		
				
		} else {
			$rankStr = "-";
		}
		
		return $rankStr;
		
	}	
	
	function sendCronReport($info) {
		
		switch ($info ['doc_type']) {
			
			case "export" :
				if ($info ['typeexport'] != 'pdf') {
					$exportVersion = true;
				} else {
					$pdfVersion = true;
				}
				
				break;
				
			case "Html" :
				$this->set('printVersion', true );
				break;
		}
	}
	
	function showOverviewDashboard($info) {
	    $spTextHome = $this->getLanguageTexts('home', $_SESSION['lang_code']);
	    $headList = [
			"report" => $_SESSION["text"]["common"]["Dashboard"],
		    "keyword_overview" => $spTextHome["Keyword Overview Report"],
		    "link_overview" => $spTextHome["Page Overview Report"]
		];
		
		$this->set("headList", $headList);
		$this->pluginRender('dashboard_overview');
	}
	
	function viewOverviewFilter($info = '') {
	    $userId = isLoggedIn();
	    $campaignCtrler = New BRCCampaign();
	    
	    // if admin logged in
	    if (isAdmin()) {
	        $campaignList = $campaignCtrler->getAllCampaigns();
	    }  else {
	        $campaignList = $campaignCtrler->getAllCampaigns(" and u.id=".intval($userId));
	    }
	    
	    $campaignId = empty($info['campaign_id']) ? $campaignList[0]['id'] : $info['campaign_id'];
	    
	    // if capmpaign found
	    if ($campaignId) {
	        
	        $this->set('campaignList', $campaignList);
	        $this->set('campaignId', $campaignId);
	        $actionVal = $info['action'];
	        $this->set('actionVal', $actionVal);
	        
	        // get from time
	        if (empty( $info['from_time'] )) {
	            $info['from_time'] = SP_DEMO ?  "2018-04-23" : date('Y-m-d', strtotime('-2 days'));
	        }
	        
	        // get to time
	        if (empty( $info['to_time'] )) {
	            $info['to_time'] = SP_DEMO ?  "2018-04-24" : date("Y-m-d");
	        }
	        
	        $this->set ('fromTime', $info['from_time']);
	        $this->set ('toTime', $info['to_time']);
	        $submitAction = pluginPOSTMethod("search_form_$actionVal", "content_$actionVal", "action=show_report_$actionVal");
	        $this->set('submitAction', $submitAction);
	        $this->set('onChange', $submitAction);
	        $this->pluginRender('overview_filter' );
	    } else {
	        showErrorMsg("No campaigns found.");
	    }
	}
	
	function showKeywordOverviewReport($info) {
		$helperCtrler = new BRCHelper();
		$campaignId = intval($info['campaign_id']);
		$cmpSEList = $helperCtrler->getCampaignDataLists("searchengine", $campaignId, true);
		$seList = $this->dbHelper->getAllRows("searchengines", "id in (".implode(',', $cmpSEList).")");
		
		$this->set('seList', $seList);
		$args = "action=keyword_overview_data&campaign_id=$campaignId&from_time={$info['from_time']}&to_time={$info['to_time']}";
		$keywordOVUrl = pluginLink($args);
		
		$this->set('keywordOVUrl', $keywordOVUrl);
		$this->pluginRender('keyword_overview_report');
	}
	
	function showKeywordOverviewReportData($seachInfo) {
		$campaignId = intval($seachInfo['campaign_id']);
		$seId = intval($seachInfo['se_id']);
		$conditions = !empty($seachInfo['from_time']) ? " and sr.time>='".addslashes($seachInfo['from_time'])."'" : "";
		$conditions .= !empty($seachInfo['to_time']) ? " and sr.time<='".addslashes($seachInfo['to_time'])."'" : "";
		$sql = "select * from (
					select distinct sr.keyword_id, sr.link_found, sr.rank,sr.time, k.keyword, l.url
					from brc_searchresults sr, brc_keywords k, brc_links l
					where sr.keyword_id=k.id and sr.link_id=l.id and sr.campaign_id=$campaignId 
					and sr.searchengine_id=$seId and sr.rank > 0 $conditions
					order by rank asc, time DESC
				) as p group by p.keyword_id";
		 
		$keywordResultList = $this->db->select($sql);
		$this->set("keywordResultList", $keywordResultList);
		$this->pluginRender('keyword_overview_data');	
	}
	
	function showLinkOverviewReport($info) {
		$helperCtrler = new BRCHelper();
		$campaignId = intval($info['campaign_id']);
		$cmpSEList = $helperCtrler->getCampaignDataLists("searchengine", $campaignId, true);
		$seList = $this->dbHelper->getAllRows("searchengines", "id in (".implode(',', $cmpSEList).")");
		
		$this->set('seList', $seList);
		$args = "action=link_overview_data&campaign_id=$campaignId&from_time={$info['from_time']}&to_time={$info['to_time']}";
		$linkOVUrl = pluginLink($args);
		
		$this->set('linkOVUrl', $linkOVUrl);
		$this->pluginRender('link_overview_report');
	}
	
	function showLinkOverviewReportData($seachInfo) {
		$campaignId = intval($seachInfo['campaign_id']);
		$seId = intval($seachInfo['se_id']);
		$conditions = !empty($seachInfo['from_time']) ? " and sr.time>='".addslashes($seachInfo['from_time'])."'" : "";
		$conditions .= !empty($seachInfo['to_time']) ? " and sr.time<='".addslashes($seachInfo['to_time'])."'" : "";
		$sql = "select * from (
					select distinct sr.link_id, sr.link_found, sr.rank,sr.time, k.keyword, l.url
					from brc_searchresults sr, brc_keywords k, brc_links l
					where sr.keyword_id=k.id and sr.link_id=l.id and sr.campaign_id=$campaignId 
					and sr.searchengine_id=$seId and sr.rank > 0 $conditions
					order by rank asc, time DESC
				) as p group by p.link_id";
		 
		$pageResultList = $this->db->select($sql);
		$this->set("pageResultList", $pageResultList);
		$this->pluginRender('link_overview_data');
	}
	
}
?>