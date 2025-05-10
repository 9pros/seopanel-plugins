<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class BCProject extends BlogCommentor{

	/*
	 * show projects list to manage
	 */
	function showProjectsManager($info='') {
		
		$userId = isLoggedIn();			
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);
		$this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select p.*,w.name website,l.lang_name from bc_projects p,websites w,languages l where p.website_id=w.id and l.lang_code=p.lang_code";				
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
		$projectList = $this->db->select($sql);
		
		$helperCtrler = $this->createHelper('bchelper', $pluginDirName);			    
	    $seInfo = $helperCtrler->getBlogSearchEngineInfo(BC_DEF_BLOG_SEARCH_ENGINE, 'domain');
		foreach ($projectList as $i => $prInfo) {
	        $projectList[$i]['crawled_links'] = $helperCtrler->getCountBlogLinks($prInfo['id'], $seInfo['id']);
		}
		
		$this->set('list', $projectList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showprojectsmanager');
	}

	/*
	 * func to create new project
	 */ 
	function newProject($info='') {						
		$userId = isLoggedIn();		
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);		
		$websiteId = empty($info['website_id']) ? $websiteList[0]['id'] : intval($info['website_id']);
		$this->set('websiteId', $websiteId);
    	$this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));    	
    	$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
		
		$langController = New LanguageController();
		$this->set('langList', $langController->__getAllLanguages());
		if (!isset($info['lang_code'])) {
	        $post['lang_code'] = $_SESSION['lang_code'];
	        $post['max_links'] = BC_MAX_BLOG_LINKS;
	        $this->set('post', $post);
		} else {
		    $this->set('post', $info);    
		}
		
		$this->pluginRender('newproject');
	}
	
	/*
	 * func to create project
	 */
	function createProject($listInfo){
		$errMsg['project_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_name']));
		$errMsg['keyword'] = formatErrorMsg($this->validate->checkBlank($listInfo['keyword']));
		$listInfo['link_title'] = $this->formatLinkTitleList($listInfo['link_title']);
		$errMsg['link_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['link_title']));
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['max_links'] = formatErrorMsg($this->validate->checkNumber($listInfo['max_links']));
		$errMsg['email'] = formatErrorMsg($this->validate->checkEmail($listInfo['email']));
		$errMsg['comment1'] = formatErrorMsg($this->validate->checkBlank($listInfo['comment1']));
		$this->setPluginTextsForRender('blogcommentor', 'bc_texts');
		if(!$this->validate->flagErr){

		    $errorFlag = 0;
		    if ($listInfo['max_links'] > BC_MAX_BLOG_LINKS) {
		        $errorFlag = 1;
		        $errMsg['max_links'] = formatErrorMsg($this->pluginText['numberlinksgreater']);
		    }
		    
		    for($i=1;$i<=10;$i++) {
		        if (!empty($listInfo['comment'.$i]) && (strlen($listInfo['comment'.$i]) < BC_MIN_CHARS_COMMENT) ) {
		            $errorFlag = 1;		            
		            $errMsg['comment'.$i] = formatErrorMsg($this->pluginText['numbercharslessincomment']." ". BC_MIN_CHARS_COMMENT);
		        }
		    }
		    
		    if (!$errorFlag) {

    			if (!$this->__checkName($listInfo['keyword'], $listInfo['website_id'])) {
    			    
    			    $commCols = "";
    			    $commVals = "";
    			    for($i=1;$i<=10;$i++) {
    			        $commCols .= ",comment".$i;
    			        $commVals .= ",'".addslashes($listInfo['comment'.$i])."'";
    			    }
    			    
    			    $listInfo['website_id'] = intval($listInfo['website_id']);
    			    $listInfo['email'] =  addslashes($listInfo['email']);
    			    $listInfo['max_links'] = intval($listInfo['max_links']);
    			    $listInfo['lang_code'] = addslashes($listInfo['lang_code']);
    				$sql = "insert into bc_projects(project_name,keyword,website_id,link_title,email,max_links,lang_code,crawled_page,status $commCols)
					values('".addslashes($listInfo['project_name'])."','".addslashes($listInfo['keyword'])."',{$listInfo['website_id']},'".addslashes($listInfo['link_title'])."',
					'{$listInfo['email']}',{$listInfo['max_links']},'{$listInfo['lang_code']}',0,1 $commVals)";
    				$this->db->query($sql);
    				$this->showProjectsManager();
    				exit;
    			}else{				
    				$errMsg['keyword'] = formatErrorMsg($this->pluginText['Project already exist']);
    			}
		    }
		}

		$this->set('errMsg', $errMsg);
		$this->newProject($listInfo);
	}

	function editProject($projectId, $listInfo=''){
		
	    $userId = isLoggedIn();
		if(!empty($projectId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getProjectInfo($projectId);
			}

			$this->set('post', $listInfo);			
			$websiteController = New WebsiteController();
    		$websiteList = $websiteController->__getAllWebsites($userId, true);
    		$this->set('websiteList', $websiteList);		
    		$websiteId = empty($listInfo['website_id']) ? $websiteList[0]['id'] : intval($listInfo['website_id']);
    		$this->set('websiteId', $websiteId);
    		$this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));
    		$this->set('spTextPanel', $this->getLanguageTexts('panel', $_SESSION['lang_code']));
    		
    		$langController = New LanguageController();
    		$this->set('langList', $langController->__getAllLanguages());
			$this->pluginRender('editproject');
			exit;
		}		
	}
	
	function formatLinkTitleList($linkTitle) {
		$linkTitleList = array();
		
		// if not empty
		if (!empty($linkTitle)) {
			$titleList = explode(",", trim($linkTitle));
			
			// lopp through the title list
			foreach($titleList as $title) {
				$title = trim($title);
				
				// if not empty
				if (!empty($title)) {
					$linkTitleList[$title] = 1;
				}
				
			}		
		}
		
		$linkTitle  = implode(",", array_keys($linkTitleList));
		return $linkTitle;		
	}
	
	/*
	 * func to update project
	 */
	function updateProject($listInfo) {
		$errMsg['project_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_name']));
		$errMsg['keyword'] = formatErrorMsg($this->validate->checkBlank($listInfo['keyword']));
	    $listInfo['link_title'] = $this->formatLinkTitleList($listInfo['link_title']);
		$errMsg['link_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['link_title']));
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['max_links'] = formatErrorMsg($this->validate->checkNumber($listInfo['max_links']));
		$errMsg['comment1'] = formatErrorMsg($this->validate->checkBlank($listInfo['comment1']));
		$errMsg['email'] = formatErrorMsg($this->validate->checkEmail($listInfo['email']));
		$this->setPluginTextsForRender('blogcommentor', 'bc_texts');
		if(!$this->validate->flagErr){

		    $errorFlag = 0;
		    if ($listInfo['max_links'] > BC_MAX_BLOG_LINKS) {
		        $errorFlag = 1;
		        $errMsg['max_links'] = formatErrorMsg($this->pluginText['numberlinksgreater']);
		    }

		    for($i=1;$i<=10;$i++) {
		        if (!empty($listInfo['comment'.$i]) && (strlen($listInfo['comment'.$i]) < BC_MIN_CHARS_COMMENT) ) {
		            $errorFlag = 1;		            
		            $errMsg['comment'.$i] = formatErrorMsg($this->pluginText['numbercharslessincomment']." ". BC_MIN_CHARS_COMMENT);
		        }
		    }
		    
		    // if no error occured
		    if (!$errorFlag) {
				
				if ($this->__checkName($listInfo['project_name'], $listInfo['website_id'], $listInfo['id'])) {
					$errMsg['project_name'] = formatErrorMsg($this->pluginText['Project already exist']);
					$this->validate->flagErr = true;
				}
    
    			if (!$this->validate->flagErr) {
    			    $commVals = "";
    			    for($i=1;$i<=10;$i++) {
    			        $commVals .= ",comment$i='".addslashes($listInfo['comment'.$i])."'";
    			    }

    				$sql = "update bc_projects set
    						project_name = '".addslashes($listInfo['project_name'])."',
    						website_id = " . intval($listInfo['website_id']) .",
    						email = '".addslashes($listInfo['email'])."',
    						lang_code = '".addslashes($listInfo['lang_code'])."',
    						max_links = " . intval($listInfo['max_links']) .",
    						link_title = '".addslashes($listInfo['link_title'])."',
    						keyword = '".addslashes($listInfo['keyword'])."'
    						$commVals
    						where id=" . intval($listInfo['id']);
    				$this->db->query($sql);
    				$this->showProjectsManager();				
    				exit;
    			}
		    }
		}

		$this->set('errMsg', $errMsg);
		$this->editProject($listInfo['id'], $listInfo);
	}
	
	/*
	 * function to run project, save blog links to database
	 */
	function runProject($projectId, $showhead=false, $limit = 10, $importCount = 0) {	    
        $projectId = intval($projectId);
	    $helperCtrler = $this->createHelper('bchelper');
	    $projectInfo = $this->__getProjectInfo($projectId);
	    $completed = 0;
	    $errorMsg = '';
	    
	    // find new blog links from using the search
    	$seInfo = $helperCtrler->getBlogSearchEngineInfo(BC_DEF_BLOG_SEARCH_ENGINE, 'domain');	    
    	$countLinks =  $helperCtrler->getCountBlogLinks($projectId, $seInfo['id']);

    	if (empty($showhead)) {
    	
    	    // check whether any blog links needs to be checked
    	    $sql = "select bs.*,bm.comment_post_ID_col 
    	    from bc_search_results bs, bc_blog_meta bm 
    	    where bs.blog_se_id = bm.id and bs.project_id=$projectId and bs.checked=0 limit 0, $limit";
    	    $blogList = $this->db->select($sql);
    	    if (count($blogList) > 0) {
    	        foreach ($blogList as $blogInfo) {
    	            $blogStatus = $helperCtrler->checkBlogStatus($blogInfo['id'], $blogInfo);
    	            /*$sql = "update bc_search_results set checked=1, checked_time=CURRENT_TIMESTAMP, status='$blogStatus' where id=".$blogInfo['id'];
    		        $this->db->query($sql);*/
    	        }
    	    } else {
        	    
        	    // if import action or check whether crawling blog links is greater than allowed max blog links allowed for a page
        	    if (!empty($importCount) or ($countLinks >= $projectInfo['max_links'])) {
                    $completed = 1;
        	    } else {
        	        
        	        include_once(SP_CTRLPATH."/searchengine.ctrl.php");
        		    include_once(SP_CTRLPATH."/report.ctrl.php");
        		
        		    $page = $projectInfo['crawled_page'];
            		$reportController = New ReportController();		
            		$seController = New SearchEngineController();
            		$reportController->seList = $seController->__getAllCrawlFormatedSearchEngines();
            		
            		$keywordInfo = array(
            			'name' => $projectInfo['keyword'].' site:wordpress.com',
            			'searchengines' => BC_SEARCH_ENGINE_ID,
            			'url' => $projectInfo['url'],
            		    'lang_code' =>  $projectInfo['lang_code'],
            		);
            		$reportController->seList[BC_SEARCH_ENGINE_ID]['url'] .= "&filter=0";
        		    $reportController->seList[BC_SEARCH_ENGINE_ID]['start'] = $page * $reportController->seList[BC_SEARCH_ENGINE_ID]['no_of_results_page'];
            		
            		$reportController->showAll = true;
            		$crawlResult = $reportController->crawlKeyword($keywordInfo, BC_SEARCH_ENGINE_ID, false, false);
        
            		$linkFound = false;
            		foreach($crawlResult as $matchList){
        		        if($matchList['status']){
        			        foreach($matchList['matched'] as $i => $linkInfo){
        			            $helperCtrler = $this->createHelper('bchelper');
        	                    
        			            // save blog link
        			            if ($helperCtrler->saveBlogSearchLink($projectId, $seInfo['id'], $linkInfo)) {
        	                        $linkFound = true;
                                    $countLinks++;
                                    
                                    // if link count greater or equal max project link count
                                    if ($countLinks >= $projectInfo['max_links']) {
                                        break;
                                    }
                                    
        	                    }
        			        }
        		        }
            		}
        
            		// if no blog link found mark report as complete
            		if (empty($linkFound)) {
            		    $completed = 1;        
            		}
                		
                    // update crwaled page
        	        $page++;
            		$this->updateProjectCrwaledPage($projectId, $page);
        	    }
    	    }
    	}
	    
	    $this->set('countLinks', $countLinks);
	    $checkedLinks = $helperCtrler->getCountBlogLinks($projectId, $seInfo['id'], " and checked=1");
	    $this->set('checkedLinks', $checkedLinks);
	    $activeLinks = $helperCtrler->getCountBlogLinks($projectId, $seInfo['id'], " and status=1");
	    $this->set('activeLinks', $activeLinks);
	    
	    $this->set('completed', $completed);
	    $this->set('errorMsg', $errorMsg);
	    $this->set('projectId', $projectId);
	    $this->set('showhead', $showhead);
	    $this->set('importCount', $importCount);
	    
	    // display head values
	    if ($showhead) {
            $this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));
	        $this->set('projectInfo', $projectInfo);
	    } else {
	        updateJsLocation('total_links', $countLinks);
	        updateJsLocation('checked_links', $checkedLinks);
	        updateJsLocation('active_links', $activeLinks);
	        updateJsLocation('inactive_links', $countLinks - $activeLinks);
	    }
	    
	    $this->pluginRender('runproject');
	}
	
	/*
	 * function to update crawled page of project
	 */
	function updateProjectCrwaledPage($projectId, $page) {
	    $sql = "update bc_projects set crawled_page=$page where id=$projectId";
		$this->db->query($sql);
	}	
	
	/*
	 * func to delete project
	 */
	function deleteProject($projectId) {

		$sql = "delete from bc_projects where id=$projectId";
		$this->db->query($sql);

		$sql = "delete from bc_search_results where project_id=$projectId";
		$this->db->query($sql);
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatus($projectId, $status){
		$sql = "update bc_projects set status=$status where id=" . intval($projectId);
		$this->db->query($sql);		
	}

	/*
	 * function to check name of project
	 */
	function __checkName($name, $websiteId, $projectId = false){
		$websiteId = intval($websiteId);
		$sql = "select id from bc_projects where project_name='".addslashes($name)."' and website_id=$websiteId";
		$sql .= $projectId ? " and id!=". intval($projectId) : "";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}

	/*
	 * func to get all projects
	 */
	function getAllProjects($condtions='') {
		
		$sql = "select bc.* from bc_projects bc,websites w where bc.website_id=w.id";
		$sql .= empty($condtions) ? "" : $condtions;
		$projectList = $this->db->select($sql);
		return $projectList;		
	}

	/*
	 * func to get project info
	 */
	function __getProjectInfo($projectId) {
		$sql = "select p.*,w.url from bc_projects p,websites w where  p.website_id=w.id and p.id=".intval($projectId);
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
	/*
	 * func to show project select box
	 */
    function showProjectSelectBox($data) {
        
        $condtions = isAdmin() ? "" : " and w.user_id=".isLoggedIn();
        $condtions .= empty($data['website_id']) ? "" : " and bc.website_id=".intval($data['website_id']);
        $projectList = $this->getAllProjects($condtions);
        $projectId = empty($data['project_id']) ? 0 : $data['project_id'];
        $this->set("projectId", $projectId);
        $this->set("projectList", $projectList);
        $this->pluginRender('showprjselbox');
    }

    /*
     * fucntion to get random comment
     */
    function __getRandomComment($projectInfo) {
        
        $commList = array();
        for($i=1;$i<=10;$i++) {
            if (!empty($projectInfo['comment'.$i])) {
                $commList[] = $projectInfo['comment'.$i];
            }
        }
        
        $key = array_rand($commList, 1);
        return $commList[$key];
    }
    

	/**function to get project link title
	 */
    function getProjectLinkTitle($projectInfo) {
    
    	$linkTitle = $projectInfo['keyword'];
    
    	// if link title text existing
    	if (!empty($projectInfo['link_title'])) {
    		$titleList = explode(",", stripslashes($projectInfo['link_title']));
    		$titleKey = array_rand($titleList, 1);
    		$linkTitle = $titleList[$titleKey];
    	}
    
    	return $linkTitle;
    
    }

    /*
     * func to show import project links form
    */
    function showImportProjectLinks($info = ''){
    	$projectList = $this->getAllProjects(' and bc.status=1 order by bc.keyword');
    	$this->set('projectList', $projectList);
    	$projectId = empty($info['project_id']) ? $projectList[0]['id'] : intval($info['project_id']);
    
    	// if no email list found
    	if (empty($projectId)) showErrorMsg("No projects found!");
    
    	$this->set('projectId', $projectId);
    	$this->set('post', $info);
    	$this->set('spTextSA', $this->getLanguageTexts('siteauditor', $_SESSION['lang_code']));
    	$this->pluginRender('import_project_links');
    }

    /*
     * function to do import links from a form
    */
    function importProjectLinks($info = '') {
    	$erroFlag = True;
    	$errMsg['project_id'] = formatErrorMsg($this->validate->checkBlank($info['project_id']));
    	$errMsg['links'] = formatErrorMsg($this->validate->checkBlank($info['links']));
    	
    	// if no error occured
    	if (!$this->validate->flagErr) {
    		$erroFlag = FALSE;
    		$projectId = intval($info['project_id']);
    		$links = explode(",", $info['links']);
    		$linkList = array();
    		foreach ($links as $i => $link) {
    			$link = $this->spider->formatUrl(trim($link));
        		if (empty($link)) continue;
        		$linkList[] = $link;
    		}
    		
    		// if demo not enabled, add link to db
    		if (!SP_DEMO) {
    			
    			$helperCtrler = $this->createHelper('bchelper');
    			$projectInfo = $this->__getProjectInfo($projectId);
    			$countLinks =  $helperCtrler->getCountBlogLinks($projectId, BC_SEARCH_ENGINE_ID);
    			
    			// if total links less than allowed
    			if ($countLinks < $projectInfo['max_links']) {
    			
	    			$linkCount = 0;
	    			foreach($linkList as $link){
	
	    				$linkInfo['url'] = $link;
	    				$linkInfo['title'] = formatUrl($link);
	    				$linkInfo['description'] = $linkInfo['title'];
	    				
	    				// save blog link
	    				if ($helperCtrler->saveBlogSearchLink($projectId, BC_SEARCH_ENGINE_ID, $linkInfo)) {
	    					$linkCount++;
	    					$countLinks++;
	    			
	    					// if link count greater or equal max project link count
	    					if ($countLinks >= $projectInfo['max_links']) {
	    						break;
	    					}
	    				}
	    			}
	    			
	    			// if link not found
	    			if ($linkCount == 0) {
		    			$erroFlag = TRUE;
		    			$errMsg['links'] = formatErrorMsg("No valid links found, Also check link is not duplicate.");
	    			}
    			} else {
	    			$erroFlag = TRUE;
	    			$errMsg['links'] = formatErrorMsg("Project already having maximum link allowed");
    			}
    		}
    		
    	} 
    	
    	// if error occured
    	if ($erroFlag) {
    		$this->set('errMsg', $errMsg);
    		$this->showImportProjectLinks($info);
    	} else {
    		$this->runProject($projectId, true, 10, $linkCount);
    	}
	}
}