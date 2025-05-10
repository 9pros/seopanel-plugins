<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class BCHelper extends BlogCommentor{
    
    var $cronTab = false;
    var $cookieJar = BC_COOKIE_JAR;
    var $cookieFile = BC_COOKIE_FILE;
    
    /*
     * function to get info about blog search engine
     */
    function getBlogSearchEngineInfo($value, $col='id') {        
        $sql = "select * from bc_blog_meta where $col='$value'";
        $info = $this->db->select($sql, true);
		return $info;
    }
    
    /*
     * function to format the blog search results
     */
    function formatBlogSearchResults($value) {
        $search = array('<![CDATA[', ']]>');
        $value = str_replace($search, '', $value);
        return $value;
    }
    
    /*
     * function to get count of blog search links under a project
     */
    function getCountBlogLinks($projectId, $seId=1, $condition=false) {               
        $sql = "select count(*) count from bc_search_results where project_id=$projectId and blog_se_id=$seId";
        $sql .= $condition ? $condition : "";
        $info = $this->db->select($sql, true);
        return empty($info['count']) ? 0 : $info['count'];
    }
    
    /*
     * function to get info of blog search result
     */
    function getInfoBlogSearchLink($value, $col='url', $where='') {       
        $sql = "select * from bc_search_results where $col='$value' $where";
        $info = $this->db->select($sql, true);
		return $info;        
    }
    
	/*
	 * function to save blog search deatils
	 */
	function saveBlogSearchLink($projectId, $seId, $info) {
	    $status = false;
	    $projectId = intval($projectId);
	    $seId = intval($seId);
	    
	    // if url not empty and it doesnot contains /tag/
	    if (!empty($info['url']) && !stristr($info['url'], '/tag/') && !stristr($info['url'], '/category/')) {
	        $info['url'] = addHttpToUrl($info['url']);
	        $resInfo = $this->getInfoBlogSearchLink($info['url'], 'url', " and project_id=$projectId and blog_se_id=$seId");
	        if (empty($resInfo['id'])) {
    	        $info['title'] = $this->formatBlogSearchResults($info['title']);
    	        $info['description'] = $this->formatBlogSearchResults($info['description']);
        	    $sql = "INSERT INTO bc_search_results (project_id,blog_se_id,url,title,description)
        				VALUES ($projectId, $seId, '".addslashes($info['url'])."', '".addslashes($info['title'])."', '".addslashes($info['description'])."')";
        	    $this->db->query($sql);
        	    $status = true;
	        }
	    }
	    return $status;
	}
	
	/*
	 * function to show blog comment reports
	 */
	function viewReports($data) {
	    
		$userId = isLoggedIn();
		$sql = "select w.* from websites w, bc_projects bp where w.id=bp.website_id and bp.status=1 and w.status=1";
		$sql .= isAdmin() ? "" : " and w.user_id=$userId";
		$sql .= " group by w.id order by w.name";
		$websiteList = $this->db->select($sql);
		$this->set('websiteList', $websiteList);
		
		$prjCtrler = $this->createHelper('BCProject');
		if ($data['project_id']) {
	        $prjInfo = $prjCtrler->__getProjectInfo($data['project_id']);
	        $websiteId = $prjInfo['website_id'];
		} else {
		    $websiteId = empty($data['website_id']) ? $websiteList[0]['id'] : $data['website_id'];
		}

		// if no project found
    	if (empty($websiteId)) showErrorMsg("No projects found!");

		$this->set("websiteId", $websiteId);
		
		$userCond = isAdmin() ? "" : " and w.user_id=$userId";
		$projectList = $prjCtrler->getAllProjects(" and website_id=$websiteId and bc.status=1 $userCond order by bc.keyword");
		$projectId = empty($data['project_id']) ? $projectList[0]['id'] : $data['project_id'];
		$this->set("projectId", $projectId);
		$this->set('projectList', $projectList);
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$pgScriptPath .= "&website_id=$websiteId&project_id=$projectId";
		
	    $sql = "select * from bc_search_results where project_id=$projectId";
    		
		if (!empty($data['submitted'])) {
		    $sql .= ($data['submitted'] == 'yes') ? " and submitted=1" : " and submitted=0";
		    $pgScriptPath .= "&submitted=".$data['submitted'];
		}
		
		if (!empty($data['approved'])) {
		    $sql .= ($data['approved'] == 'yes') ? " and approved=1" : " and approved=0";
		    $pgScriptPath .= "&approved=".$data['approved'];
		}
		
		$data['status'] = isset($data['status']) ? $data['status'] : "active";
	    $sql .= ($data['status'] == 'inactive') ? " and status=0" : " and status=1";
		$pgScriptPath .= "&status=".$data['status'];
		
		if (!empty($projectId)) {	
    		
    		// pagination setup		
    		$this->db->query($sql, true);
    		$this->paging->setDivClass('pagingdiv');
    		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
    		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'subcontent', 'action=viewreports&layout=ajax&showhead=0');		
    		$this->set('pagingDiv', $pagingDiv);
    		
    		$sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;		
    		$reportList = $this->db->select($sql);
    				
    		$this->set('list', $reportList);
		    $this->set('pageNo', $_GET['pageno']);
		}
		
		$this->set('showhead', (isset($data['showhead']) && empty($data['showhead'])) ? 0 : 1);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showreportsmanager');    
	}
	
	/*
	 * func to update a field
	 */ 
	function __updateField($val, $col='status', $where='', $table='bc_search_results'){
		
		$sql = "update $table set $col='$val' where 1=1 $where";
		$this->db->query($sql);		
	}
	
	/*
	 * func to deelete blog link
	 */
	function __deleteBlogLink($blogId) {
	    
	    $sql = "delete from bc_search_results where id=$blogId";	    
		$this->db->query($sql);
	}
	
	/*
	 * function to check status of blog comment submission
	 */
	function __checkSubmissionStatus($blogId, $content='') {
	    
	    $approved = false;
	    $blogInfo = $this->getInfoBlogSearchLink($blogId, 'id');	    
	    
	    //if (SP_DEBUG) $blogInfo['url'] = 'http://wordpresstest.com/?p=13';	    
	    if (!empty($content)) {
	        $ret['page'] = $content;
	    } else {
	        $ret = $this->spider->getContent($blogInfo['url']);    
	    }	    
		
	    if(empty($ret['error'])){		    
		    
		    $prjCtrler = $this->createHelper('BCProject');
		    $websiteInfo = $prjCtrler->__getProjectInfo($blogInfo['project_id']);
			if(stristr($ret['page'], 'href="'.$websiteInfo['url'].'"')){
		        $approved = true;
			}elseif(stristr($ret['page'], "href='".$websiteInfo['url']."'")){
		        $approved = true;
			}elseif(stristr($ret['page'], 'href='.$websiteInfo['url'])){
		        $approved = true;
			}
			
			// function to update checked time
			$this->updateCheckedTime($blogId);
		}
		
		$this->__updateField(intval($approved), "approved", " and id=$blogId");
		return $approved;
	}
	
	/*
	 * function to check blog link is active to post the comments
	 */
	function checkBlogStatus($blogId, $blogInfo=false) {
		 
		// if blog info empty query the information
		if (empty($blogInfo)) {
			$blogInfo = $this->getInfoBlogSearchLink($blogId, 'id');
		}
		 
		// check status of the blog using comment column present or not
		$blogSeInfo = $this->getBlogSearchEngineInfo($blogInfo['blog_se_id']);
		$commentPostInfo = $this->getCommentPostInfo($blogInfo['url'], $blogSeInfo, $blogId);
		$blogStatus = empty($commentPostInfo[$blogSeInfo['comment_post_ID_col']]) ? false : true;
		
		// update blog status
		$sql = "update bc_search_results set checked=1, checked_time=CURRENT_TIMESTAMP, status=" . intval($blogStatus) ." where id=".intval($blogId);
		$this->db->query($sql);
		
		return $blogStatus;
		 
	}
	
	/*
	 * function to __submit Blog Comment
	 */
	function __submitBlogComment($blogId) {
	    
	    $submitted = false;	    
	    $blogInfo = $this->getInfoBlogSearchLink($blogId, 'id');
	    
	    $prjCtrler = $this->createHelper('BCProject');
	    $projectInfo = $prjCtrler->__getProjectInfo($blogInfo['project_id']);
	    $linkTitle = $prjCtrler->getProjectLinkTitle($projectInfo);
	    
	    $blogSeInfo = $this->getBlogSearchEngineInfo($blogInfo['blog_se_id']);
	    $commentPostInfo = $this->getCommentPostInfo($blogInfo['url'], $blogSeInfo, $blogId);
	    $commentPostId = empty($commentPostInfo[$blogSeInfo['comment_post_ID_col']]) ? false : $commentPostInfo[$blogSeInfo['comment_post_ID_col']];
	    if ($commentPostId) {
	    	
    	    $projectInfo['comment'] = $prjCtrler->__getRandomComment($projectInfo);
    	    $postData = $blogSeInfo['author_col']."=".urlencode($linkTitle);
    		$postData .= "&".$blogSeInfo['email_col']."=".urlencode($projectInfo['email']);
    		$postData .= "&".$blogSeInfo['comment_col']."=".urlencode($projectInfo['comment']);
    		$postData .= "&".$blogSeInfo['extra_val'];
    		
    		foreach ($commentPostInfo as $col => $val) {
    			if ($col == "comment_post_url") continue;
    			$postData .= "&$col=".urlencode($val);
    		}

    		$postData .= "&".$blogSeInfo['url_col']."=".urlencode($projectInfo['url']);
    		$spider = new Spider(); 
		    $spider->_CURLOPT_POSTFIELDS = $postData;
		    $spider->_CURLOPT_REFERER = $blogInfo['url'];
	    	$spider->_CURLOPT_COOKIEFILE = $this->cookieFile;
	    	$spider->_CURLOPT_COOKIEJAR = $this->cookieJar;
		    $commentPostUrl = $commentPostInfo['comment_post_url'];
    		if (!empty($commentPostUrl)) {
    			
		        $ret = $spider->getContent($commentPostUrl);
		        if (empty($ret['error'])) {
		            $submitted = 1;
		            $this->__updateField($submitted, "submitted", " and id=$blogId");

		            // if cron check status of submission same time
		            if ($this->cronTab) {
        		        $this->__checkSubmissionStatus($blogId);
		            }		            
        		}
    		}
    				    
	    } else {
	        $this->__updateField(0, 'status', " and id=".$blogId);    
	    }	    
	    return $submitted;	    
	}
	
	/*
	 * function to get comment post info
	 */
	function getCommentPostInfo($blogUrl, $blogSeInfo, $blogId) {
	    $commentPostInfo = false;
	    $this->spider->_CURLOPT_COOKIEFILE = $this->cookieFile;
	    $this->spider->_CURLOPT_COOKIEJAR = $this->cookieJar;
	    $ret = $this->spider->getContent($blogUrl);
	    
        if (empty($ret['error'])) {
            
            // if comment posted only one time to a blog
            if (BC_COMMENT_POST_ONCE == 0) {
                if ($this->__checkSubmissionStatus($blogId, $ret['page'])) {
                    $this->__updateField(1, "submitted", " and id=$blogId");
                    if ($this->cronTab) {
                        print $this->pluginText['alreadysubmittedcomment'];
                    } else {
                        print $_SESSION['text']['common']['Yes'];
                        print "<script>alert('".$this->pluginText['alreadysubmittedcomment']."')</script>";    
                    }
                    exit;
                }
            }
            
            $colList = explode(",", $blogSeInfo['extra_cols']);
            array_push($colList, $blogSeInfo['comment_post_ID_col']);
            
            // loop through the columns list
            foreach ($colList as $col) {
				$col = trim($col);
            	
            	// if comment post url needs to be find
            	if ($col == "comment_post_url") {
            		preg_match('/<div.*?id="comments".*?action="(.*'.$blogSeInfo['comment_script'].')"/is', $ret['page'], $match);
            	} else {
	            	if(preg_match("/name='$col'.*?value='(.*?)'/is", $ret['page'], $match)){
	            	} elseif(preg_match('/name="'.$col.'".*?value="(.*?)"/is', $ret['page'], $match)){
	            	}
            	}
            	
            	$commentPostInfo[$col] = empty($match[1]) ? false : trim($match[1]);
            	
            }
            
        }
        
        return $commentPostInfo;
	}
	
	/*
	 * function to update checked time of blog comment status
	 */
	function updateCheckedTime($blogId) {
	    $sql = "update bc_search_results set checked=1, checked_time=CURRENT_TIMESTAMP where id=$blogId";
		$this->db->query($sql);
	}
	
	/*
	 * function to get random blog id
	 */
	function getRandomBlogId($where='') {
	    
	    $sql = "select id from bc_search_results where status=1 $where order by checked_time,id limit 0,1";
	    $info = $this->db->select($sql, true);
        return empty($info['id']) ? 0 : $info['id'];
	}
	
	/*
	 * function to submit comments using cron
	 */
	function submitCommentsByCron($data) {
	    
	    $blogId = $this->getRandomBlogId();
	    if ($blogId) {
	        echo "Submitting blog id - $blogId\n";
	        $status = $this->__submitBlogComment($blogId);
	    }
	    
	    $blogId = $this->getRandomBlogId(" and submitted=1 and approved=0");
	    if ($blogId) {
	        echo "Checking blog id - $blogId\n";
	        $status = $this->__checkSubmissionStatus($blogId);
	    }
	}
}