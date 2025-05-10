<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class LDCrawler{

	var $seList = array();
	
	var $nextLink = "";
	
	function LDCrawler() {
		
		$sql = "select * from ld_seconfig where status=1";
		$controller = New Controller();
		$list = $controller->db->select($sql);
		$seList = array();
		foreach($list as $seInfo){
			$seId = $seInfo['id'];
			$seInfo['regex'] = "/".$seInfo['regex']."/is";
			$search = array('[--num--]');
			$replace = array($seInfo['no_of_results_page']);
			$seInfo['url'] = str_replace($search, $replace, $seInfo['url']);
			$seList[$seId] = $seInfo;
		}
		$this->seList = $seList;
	}
	
	// func to crawl keyword
	function crawlKeyword( $keyword, $seInfoId, $start='', $langCode='', $countryCode='' ) {
		
		// if moz then use different function
		if ($this->seList[$seInfoId]['domain'] == 'moz') {
			$crawlResult['matched'] = $this->__getMozBackLinks($keyword, $start); 
			return $crawlResult;
		}
		
		$controller = New Controller();
		$crawlResult = array();		
		if(empty($keyword)) return $crawlResult;
		
		// if site explorer
		if ($this->seList[$seInfoId]['domain'] == 'siteexplorer.info') {
			if (empty($start)) {
				$keyword = formatUrl($keyword, false);
				$seUrl = str_replace('[--keyword--]', urlencode(stripslashes($keyword)), $this->seList[$seInfoId]['url']);
			} else {
				$seUrl = "http://". $this->seList[$seInfoId]['domain']. $start;
			}
		} else {
			$searchUrl = str_replace('[--keyword--]', urlencode(stripslashes($keyword)), $this->seList[$seInfoId]['url']);
			$searchUrl = str_replace('[--lang--]', $langCode, $searchUrl);
			$searchUrl = str_replace('[--country--]', $countryCode, $searchUrl);
			$this->seList[$seInfoId]['start'] = empty($start) ? $this->seList[$seInfoId]['start'] : $start; 
			$seUrl = str_replace('[--start--]', $this->seList[$seInfoId]['start'], $searchUrl);
		}
		
		if(!empty($this->seList[$seInfoId]['cookie_send'])){
			$this->seList[$seInfoId]['cookie_send'] = str_replace('[--lang--]', $langCode, $this->seList[$seInfoId]['cookie_send']);
			$controller->spider->_CURLOPT_COOKIE = $this->seList[$seInfoId]['cookie_send'];				
		}
		
		if (stristr($this->seList[$seInfoId]['url'], 'exalead.com')) {
		    $refererStart = $start - $this->seList[$seInfoId]['no_of_results_page']; 
		    if ($refererStart < 0) {
		        $controller->spider->_CURLOPT_REFERER = "http://www.exalead.com/search/web/";    
		    } else {
		        $controller->spider->_CURLOPT_REFERER = str_replace('[--start--]', $refererStart, $searchUrl);
		    }            
		}
		
		$result = $controller->spider->getContent($seUrl);		
		$pageContent = $result['page'];

		# to check whether utf8 conversion needed
		if(!empty($this->seList[$seInfoId]['encoding'])){
			$pageContent = mb_convert_encoding($pageContent, "UTF-8", $this->seList[$seInfoId]['encoding']);
		}
		
		$crawlStatus = 0;
		$crawlResult['matched'] = array();
		if(empty($result['error'])){				
			if (preg_match_all($this->seList[$seInfoId]['regex'], $pageContent, $matches)) {
				
				// find next link
				if (!empty($this->seList[$seInfoId]['next_link_regex'])) {
					if (preg_match("/".$this->seList[$seInfoId]['next_link_regex']."/is", $pageContent, $rsMatch)) {
						$this->nextLink = $rsMatch[1];	
					}		
				}
				
				$urlList = $matches[$this->seList[$seInfoId]['url_index']];
				
				foreach($urlList as $i => $url){
					$url = trim(urldecode(strip_tags($url)));
						
					// if alexa add http infront of the url
					if (stristr($this->seList[$seInfoId]['url'], 'alexa.com')) {
				        $url = addHttpToUrl($url);
					}
					
					$matchInfo['url'] = $url;
					$crawlResult['matched'][] = $matchInfo;
				}
				
			    $crawlStatus = 1;
			}else{
				if(SP_DEBUG){
					echo "<p class='note' style='text-align:left;'>Error occured while parsing $seUrl ".formatErrorMsg("Regex not matched <br>\n")."</p>";
				}
			}	
		}else{
			if(SP_DEBUG){
				echo "<p class='note' style='text-align:left;'>Error occured while crawling $seUrl ".formatErrorMsg($result['errmsg']."<br>\n")."</p>";
			}
		}
					
		$crawlResult['status'] = $crawlStatus;
		return  $crawlResult;
	}
	
	
	// function to get moz rank
	function __getMozBackLinks ($url, $start, $returnLog = false) {
		$mozBackLinkList = array();
	
		if (SP_DEMO && !empty($_SERVER['REQUEST_METHOD'])) return $mozBackLinkList;
	
		if (empty($url)) return $mozBackLinkList;
	
		// Get your access id and secret key here: https://moz.com/products/api/keys
		$accessID = !empty($accessID) ? $accessID : SP_MOZ_API_ACCESS_ID;
		$secretKey = !empty($secretKey) ? $secretKey : SP_MOZ_API_SECRET;
	
		// if empty no need to crawl
		if (empty($accessID) || empty($secretKey)) return $mozBackLinkList;
	
		// Set your expires times for several minutes into the future.
		// An expires time excessively far in the future will not be honored by the Mozscape API.
		$expires = time() + 300;
	
		// Put each parameter on a new line.
		$stringToSign = $accessID."\n".$expires;
	
		// Get the "raw" or binary output of the hmac hash.
		$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
	
		// Base64-encode it and then url-encode that.
		$urlSafeSignature = urlencode(base64_encode($binarySignature));
		$encodedUrl = urlencode($url);
	
		// Put it all together and you get your request URL.
		$requestUrl = SP_MOZ_API_LINK . "/links/$encodedUrl?AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
		
		// Metrics to retrieve (links_constants.php)
		$options = array(
			'Scope' => "page_to_page",
			'Filter' => "external",
			'Sort' => "page_authority",
			'SourceCols' => 103079231492,
			'TargetCols' => 4,
			'LinkCols' => 8,
			'Limit' => 50,
			'Offset' => $start,
		);		
		
		// loop through the array and create link
		foreach ($options as $optKey => $optVal) {
			$requestUrl .= "&$optKey=$optVal";
		}		
	
		$spider = new Spider();
		$ret = $spider->getContent($requestUrl);
		
		// parse rank from the page
		if (!empty($ret['page'])) {
			$linkList = json_decode($ret['page']);
				
			// if no errors occured
			if (empty($linkList->error_message)) {
					
				// loop through rank list
				foreach ($linkList as $linkInfo) {
					
					$mozLinkInfo = array(
						'url' => addHttpToUrl($linkInfo->uu),
						'url_found' => addHttpToUrl($linkInfo->luuu),
						'link_title' => $linkInfo->lnt,
						'moz_rank' => round($linkInfo->umrp, 2),
						'domain_authority' => round($linkInfo->pda, 2),
						'page_authority' => round($linkInfo->upa, 2),
					);
						
					$mozBackLinkList[] = $mozLinkInfo;
				}
	
			} else {
				$crawlInfo['crawl_status'] = 0;
				$crawlInfo['log_message'] = $linkList->error_message;
			}
				
		} else {
			$crawlInfo['crawl_status'] = 0;
			$crawlInfo['log_message'] = $ret['errmsg'];
		}
	
		// update crawl log
		$crawlLogCtrl = new CrawlLogController();
		$crawlInfo['crawl_type'] = 'backlink';
		$crawlInfo['ref_id'] = $encodedDomains;
		$crawlInfo['subject'] = "moz link metrics";
		$crawlLogCtrl->updateCrawlLog($ret['log_id'], $crawlInfo);
	
		return $returnLog ? array($mozBackLinkList, $crawlInfo) : $mozBackLinkList;
	}
	
	/*
	 * function get crawler info
	 */
	function __getCrawlerInfo($val, $col='domain') {
		
		foreach($this->seList as $i => $seInfo) {			
			if ($seInfo[$col] == $val) {
				return $this->seList[$i];
			}
		}
		return false; 
	}
	
	/*
	 * func to get backlink page info
	 */ 
	function getBacklinkPageInfo($url, $checkurl){

		$urlInfo = parse_url($url);
		$hostName = str_replace('www.', '', $urlInfo['host']);
		$spider = New Spider();
		$ret = $spider->getContent($url, false);
		$pageInfo['external'] = 0;
		$pageInfo['title'] = '';
		$pageInfo['nofollow'] = 0;
		
		if( !empty($ret['page'])){
			$string = strtolower($ret['page']);
			$string = str_replace(array("\n",'\n\r','\r\n','\r'), "", $string);
					
			$pattern = "/<a(.*?)>(.*?)<\/a>/is";	
			preg_match_all($pattern, $string, $matches, PREG_PATTERN_ORDER);		
			for($i=0;$i<count($matches[1]);$i++){
				$href = $this->__getTagParam("href",$matches[1][$i]);
				$href = preg_replace('/\/{3}/', '/', $href);
				if(!empty($href)){
					if( !preg_match( '/mailto:/', $href ) && ($href!="#") && !preg_match( '/javascript:|;/', $href ) ){
						if($href != "/"){
							
							// check whether it is external url or not
							if (stristr($href, 'http://') ||  stristr($href, 'https://')) {
								if (!stristr($href, $hostName)) {
									$pageInfo['external'] += 1;
									$href = $this->removeTrailingSlash($href);
									if (stristr($href, $checkurl)) {
										$pageInfo['url_found'] = $href;
										if(stristr($matches[2][$i], '<img')) {
											$pageInfo['title'] = $this->__getTagParam("alt", $matches[2][$i]);
										} else {
											$pageInfo['title'] = strip_tags($matches[2][$i]);
										}										
										$pageInfo['nofollow'] = stristr($matches[1][$i], 'nofollow') ? 1 : 0;
									}						
								}
							}							
						}
					}
				}
			}			
		}
		return $pageInfo;
	}
	
	/*
	 * function to remove last trailing slash
	 */
	function removeTrailingSlash($url) {
		
		$url = preg_replace('/\/$/', '', $url);
		return $url;
	}

	/*
	 * function to get value of a parameter in a tag
	 */ 
	function __getTagParam($param, $tag){
		
		preg_match('/'.$param.'="(.*?)"/is', $tag, $matches);
		if(empty($matches[1])){
			preg_match("/$param='(.*?)'/is", $tag, $matches);
			if(empty($matches[1])){
				preg_match("/$param=(.*?) /is", $tag, $matches);
			}		
		}				
		if(isset($matches[1])) return trim($matches[1]) ;
	}
}