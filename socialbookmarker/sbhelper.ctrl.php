<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class SBHelper extends SocialBookmarker{
    
    var $orderBy = " order by rank ASC,engine_name ASC"; 
    
    /*
     * function to get all social bookmarker sites
     */    
    function getAllSocialBookMarkers($conditions="") {        
        $sql = "select * from sb_engines where status=1";
		$sql .= empty($condtions) ? "" : $condtions;
		$sql .= $this->orderBy;
		$projectList = $this->db->select($sql);
		return $projectList;
    }

	/*
	 * func to get social bokkmarker info
	 */
	function __getSBEngineInfo($sbEngineId) {
		
		$sql = "select * from sb_engines where id=$sbEngineId";
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
    /*
     * function to get next or previous SB engine
     */
	function __getNextSBEngineInfo($sbEngineId, $previous=false) {	    
		$sbId = 0;
		if (!empty($sbEngineId)) {
    	    $sql = "select id from sb_engines where status=1 $this->orderBy";
    		$sbList = $this->db->select($sql);
    		
    		foreach ($sbList as $i => $sbInfo) {
    		    if ($sbInfo['id'] == $sbEngineId) {
    		        $key = $previous ? $i - 1 :  $i + 1;;
    		        $sbId = $sbList[$key]['id']; 
    		        break;
    		    }    
    		}
		}		
		return $sbId;
	}
	
	/*
	 * create submit url for social bookmark engine
	 */
	function __createSBSubmitUrl($sbEngineInfo, $projectInfo) {
	    
	    $sbSubmitUrl = str_ireplace('[[url]]', urlencode($projectInfo['share_url']), $sbEngineInfo['engine_submit_link']);
	    $sbSubmitUrl = str_ireplace('[[title]]', urlencode($projectInfo['share_title']), $sbSubmitUrl);
	    $sbSubmitUrl = str_ireplace('[[description]]', urlencode($projectInfo['share_description']), $sbSubmitUrl);
	    $sbSubmitUrl = str_ireplace('[[tags]]', urlencode($projectInfo['share_tags']), $sbSubmitUrl);
	    return $sbSubmitUrl;    
	}
	
	/*
	 * show all SB engines
	 */
	function showSocialBookmarkerManager($info='') {		
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$info['stscheck'] = isset($info['stscheck']) ? intval($info['stscheck']) : 1;
		$pgScriptPath .= "&action=showsbmanager&stscheck=".$info['stscheck'];
		$sql = "select * from sb_engines where status=".$info['stscheck'];
		
		// search string not empty
		if (!empty($info['engine_name'])) {
		    $sql .= " and engine_name like '%{$info['engine_name']}%'";		    
		    $pgScriptPath .= "&engine_name=".$info['engine_name'];
		}
				
		$this->set('info', $info);		
		$statusList = array(
			$_SESSION['text']['common']['Active'] => 1,
			$_SESSION['text']['common']['Inactive'] => 0,
		);		
		$this->set('statusList', $statusList);
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		
		$sql .= " $this->orderBy limit ".$this->paging->start .",". $this->paging->per_page;		
		$projectList = $this->db->select($sql);		
		
		$this->set('list', $projectList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showsbmanager');
	}

	/*
	 * func to create new SocialBookmarker
	 */ 
	function newSocialBookmarker($info=''){

	    if (!isset($info['rank'])) {
	        $info['rank'] = BC_DEFAULT_RANK;
	        $info['iframe'] = 1;
	        $this->set('post', $info);
	    }	    
		$this->pluginRender('newsb');
	}
	
	/*
	 * func to create SocialBookmarker
	 */
	function createSocialBookmarker($listInfo){
	    
		$userId = isLoggedIn();		
		$this->set('post', $listInfo);
		
		$errMsg['rank'] = formatErrorMsg($this->validate->checkNumber($listInfo['rank']));
		$errMsg['engine_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_name']));
		$errMsg['engine_submit_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_submit_link']));
		$errMsg['engine_register_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_register_link']));
		$errMsg['engine_login_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_login_link']));
		$this->setPluginTextsForRender('socialbookmarker', 'sb_texts');
		if(!$this->validate->flagErr){
		    
		    $errorFlag = false;
		    
		    // check name of the SocialBookmarker
		    if ($this->__isEngineExists($listInfo['engine_name'], 'engine_name')) {
		        $errMsg['engine_name'] = formatErrorMsg($this->pluginText['Social bookmarker already exist!']);
		        $errorFlag = true;
		    }
		    
		    // check submit link of the SocialBookmarker
		    if ($this->__isEngineExists($listInfo['engine_submit_link'], 'engine_submit_link')) {
		        $errMsg['engine_submit_link'] = formatErrorMsg($this->pluginText['Social bookmarker already exist!']);
		        $errorFlag = true;
		    }		    
		    
			if (!$errorFlag) {
			    $iframe = intval($listInfo['iframe']);			    
			    $sql = "insert into sb_engines(engine_name,engine_submit_link,engine_register_link,engine_login_link,rank,iframe,status)
						values('".addslashes($listInfo['engine_name'])."', '".addslashes(addHttpToUrl($listInfo['engine_submit_link']))."', '".addslashes(addHttpToUrl($listInfo['engine_register_link']))."', 
						'".addslashes(addHttpToUrl($listInfo['engine_login_link']))."', ".$listInfo['rank'].",$iframe,1)";
				$this->db->query($sql);
				$this->showSocialBookmarkerManager();
				exit;
			}
		}
		$this->set('errMsg', $errMsg);
		$this->newSocialBookmarker($listInfo);
	}

	/*
	 * function to check whether SocialBookmarker already exists
	 */
	function __isEngineExists($value, $col='engine_name', $sbId=false) {
	    $sql = "select id from sb_engines where $col='".addslashes($value)."'";
	    $sql .= $sbId ? " and id!=$sbId" : "";
        $listInfo = $this->db->select($sql, true);
        return empty($listInfo['id']) ? false :  $listInfo['id'];
	}

	/*
	 * func to edit SocialBookmarker
	 */ 
	function editSocialBookmarker($sbId, $listInfo=''){
		
	    $userId = isLoggedIn();
		if(!empty($sbId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getSBEngineInfo($sbId);
			}
			$this->set('post', $listInfo);
			$this->pluginRender('editsb');
			exit;
		}		
	}
	
	/*
	 * func to update SocialBookmarker
	 */
	function updateSocialBookmarker($listInfo){
		
		$userId = isLoggedIn();		
		$this->set('post', $listInfo);
		
		$errMsg['rank'] = formatErrorMsg($this->validate->checkNumber($listInfo['rank']));
		$errMsg['engine_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_name']));
		$errMsg['engine_submit_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_submit_link']));
		$errMsg['engine_register_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_register_link']));
		$errMsg['engine_login_link'] = formatErrorMsg($this->validate->checkBlank($listInfo['engine_login_link']));
		$this->setPluginTextsForRender('socialbookmarker', 'sb_texts');
		if(!$this->validate->flagErr){
		    $errorFlag = false;
		    
		    // check name of the SocialBookmarker
		    if ($this->__isEngineExists($listInfo['engine_name'], 'engine_name', $listInfo['id'])) {
		        $errMsg['engine_name'] = formatErrorMsg($this->pluginText['Social bookmarker already exist!']);
		        $errorFlag = true;
		    }
		    
		    // check submit link of the SocialBookmarker
		    if ($this->__isEngineExists($listInfo['engine_submit_link'], 'engine_submit_link', $listInfo['id'])) {
		        $errMsg['engine_submit_link'] = formatErrorMsg($this->pluginText['Social bookmarker already exist!']);
		        $errorFlag = true;
		    }
		    
			if (!$errorFlag) {
			    $iframe = intval($listInfo['iframe']);			    
			    $sql = "Update sb_engines set
			    		engine_name = '".addslashes($listInfo['engine_name'])."',
			    		engine_submit_link = '".addslashes(addHttpToUrl($listInfo['engine_submit_link']))."',
			    		engine_register_link = '".addslashes(addHttpToUrl($listInfo['engine_register_link']))."',
			    		engine_login_link = '".addslashes(addHttpToUrl($listInfo['engine_login_link']))."',
			    		rank = ".$listInfo['rank'].",
			    		iframe = $iframe 
			            where id={$listInfo['id']}";
				$this->db->query($sql);
				$this->showSocialBookmarkerManager();
				exit;
			}
		}
		$this->set('errMsg', $errMsg);
		$this->editSocialBookmarker($listInfo['id'], $listInfo);
	}
	
	/*
	 * func to delete social bookmarker
	 */
	function deleteSocialBookmarker($sbId) {

		$sql = "delete from sb_engines where id=$sbId";
		$this->db->query($sql);
	}
	
	/*
	 * func to change status of SocialBookmarker
	 */ 
	function __changeStatusSocialBookmarker($sbId, $status){
		
		$sql = "update sb_engines set status=$status where id=$sbId";
		$this->db->query($sql);		
	}
    
}