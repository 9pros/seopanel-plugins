<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class SBProject extends SocialBookmarker {

    var $tableName = "sb_projects";    // the database table name of teh projects
    
	/*
	 * show projects list to manage
	 */
	function showProjectsManager($info='') {
		
		$userId = isLoggedIn();			
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select p.*,w.name website from $this->tableName p,websites w where p.website_id=w.id";				
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
		
		$sql .= " order by last_shared_time DESC limit ".$this->paging->start .",". $this->paging->per_page;		
		$projectList = $this->db->select($sql);

		$sbHelper = $this->createHelper('SBHelper');
		foreach ($projectList as $i => $projectInfo) {
		    $lastShared = '';
		    if (!empty($projectInfo['last_shared_id'])) {
		        $sbInfo = $sbHelper->__getSBEngineInfo($projectInfo['id']);
		        $lastShared = $sbInfo['engine_name'];
		    }
		    $projectList[$i]['last_shared_name'] = $lastShared;    
		}
		
		$this->set('list', $projectList);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('pgScriptPath', $pgScriptPath);				
		$this->pluginRender('showprojectsmanager');
	}

	/*
	 * func to create new project
	 */ 
	function newProject($info=''){
						
		$userId = isLoggedIn();		
		$websiteController = New WebsiteController();
		$websiteList = $websiteController->__getAllWebsites($userId, true);
		$this->set('websiteList', $websiteList);		
		$websiteId = empty($info['website_id']) ? $websiteList[0]['id'] : intval($info['website_id']);
		$this->set('websiteId', $websiteId);		
		$this->pluginRender('newproject');
	}
	
	/*
	 * func to create project
	 */
	function createProject($listInfo){
	    
		$userId = isLoggedIn();		
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['share_url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['share_url'])));
		$errMsg['share_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_title']));
		$errMsg['share_description'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_description']));
		$errMsg['share_tags'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_tags']));
		$listInfo['share_url'] = addHttpToUrl($listInfo['share_url']);		
		if(!$this->validate->flagErr){
		    $sql = "insert into $this->tableName(share_url,website_id,share_title,share_description,share_tags,status)
					values('".addslashes($listInfo['share_url'])."',{$listInfo['website_id']},'".addslashes($listInfo['share_title'])."','".addslashes($listInfo['share_description'])."','".addslashes($listInfo['share_tags'])."',1)";
			$this->db->query($sql);
			$this->showProjectsManager();
			exit;
		}				
		$this->set('post', $listInfo);
		$this->set('errMsg', $errMsg);
		$this->newProject($listInfo);
	}	


	/*
	 * func to edit project
	 */ 
	function editProject($projectId, $listInfo=''){
		
	    $userId = isLoggedIn();
		if(!empty($projectId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getProjectInfo($projectId);
				$listInfo['oldName'] = $listInfo['keyword'];
			}
			$this->set('post', $listInfo);
			
			$websiteController = New WebsiteController();
    		$websiteList = $websiteController->__getAllWebsites($userId, true);
    		$this->set('websiteList', $websiteList);		
    		$websiteId = empty($listInfo['website_id']) ? $websiteList[0]['id'] : intval($listInfo['website_id']);
    		$this->set('websiteId', $websiteId);
    		
    		$langController = New LanguageController();
    		$this->set('langList', $langController->__getAllLanguages());
			$this->pluginRender('editproject');
			exit;
		}		
	}
	
	/*
	 * func to update project
	 */
	function updateProject($listInfo){
		
		$userId = isLoggedIn();		
		$this->set('post', $listInfo);			
		$errMsg['website_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_id']));
		$errMsg['share_url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['share_url'])));
		$errMsg['share_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_title']));
		$errMsg['share_description'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_description']));
		$errMsg['share_tags'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_tags']));
		$listInfo['share_url'] = addHttpToUrl($listInfo['share_url']);
		if(!$this->validate->flagErr){		    
			$sql = "update $this->tableName set
					website_id = {$listInfo['website_id']},
					share_url = '".addslashes($listInfo['share_url'])."',
					share_title = '".addslashes($listInfo['share_title'])."',
					share_description = '".addslashes($listInfo['share_description'])."',
					share_tags = '".addslashes($listInfo['share_tags'])."'
					where id={$listInfo['id']}";
    				$this->db->query($sql);
    				$this->showProjectsManager();				
			exit;
		}
		$this->set('errMsg', $errMsg);
		$this->editProject($listInfo['id'], $listInfo);
	}
	
	/*
	 * function to run project, save blog links to database
	 */
	function runProject($info="") {	    
        
	    $userId = isLoggedIn();
	    $projectId = empty($info['project_id']) ? 0 : $info['project_id'];
	    $completed = 0;
	    
	    $sql = "select w.* from websites w,$this->tableName p where w.id=p.website_id and w.status=1 and p.status=1";
		if(!isAdmin() ){
			$sql .= " and user_id=$userId";
		} 
		$sql .= " group by w.id order by w.name";
		$websiteList = $this->db->select($sql);
		$this->set('websiteList', $websiteList);
				
		$helperObj = $this->createHelper('SBHelper');
	    $sbEngineList = $helperObj->getAllSocialBookMarkers();
	    $this->set('onSBChange', pluginPOSTMethod('submitform', 'content', 'action=runproject'));
	    $this->set('sbEngineList', $sbEngineList);
	    
	    //submit social bookmarker id
	    $sbEngineId = empty($info['sbengine_id']) ? 0 : $info['sbengine_id'];
		
		//switch through sub actions
		switch ($info['subact']) {
		    
		    case "next":
		        if ($nextId = $helperObj->__getNextSBEngineInfo($sbEngineId)) {
	                $sbEngineId = $nextId;
		        }
		        break;
		    
		    case "previous":
		        if ($nextId = $helperObj->__getNextSBEngineInfo($sbEngineId, true)) {
	                $sbEngineId = $nextId;
		        }
		        break;
		}
		
	    
	    // if project id is empty
	    if (empty($projectId)) {
            $websiteId = $websiteList[0]['id'];
	    } else {
	        $projectInfo = $this->__getProjectInfo($projectId);
	        $websiteId = $projectInfo['website_id'];
	    }	    
	    $this->set('websiteId', $websiteId);
	    
	    if (empty($websiteId)) showErrorMsg($this->pluginText['No active websites found!']);
	    
	    $projectList = $this->getAllProjects(" and sb.website_id=$websiteId order by last_shared_time DESC");
	    $this->set('projectList', $projectList);	    
	    $this->set('onChange', pluginPOSTMethod('submitform', 'content', 'action=runproject&prjchange=1'));
	    
	    // empty project id
	    if (empty($projectId)) {
	        $projectId = $projectList[0]['id'];
	        $projectInfo = $this->__getProjectInfo($projectId);
	    }
	    $this->set('projectId', $projectId);
	    
	    if (empty($projectId)) showErrorMsg($this->pluginText['No active projects found']);	    
	    
	    // if project changed from filter or sb engine is empty 
	    if (!empty($info['prjchange']) || empty($sbEngineId)) {
	        if (!empty($projectInfo['last_shared_id'])) {
                if ($sbEngineId = $helperObj->__getNextSBEngineInfo($projectInfo['last_shared_id'])) {
                    $completed = true;
                }
	        } else {
	            $sbEngineId = $sbEngineList[0]['id'];    
	        }
	            
	    }	    
	    
	    // no social bookmarker found
	    if (empty($sbEngineId)) {
    	    $this->set('completed', 1);
    	    $lastId = count($sbEngineList) - 1;
    	    $this->set('sbEngineId', $sbEngineList[$lastId]['id']);	        
	    } else {	    
    	    $this->set('sbEngineId', $sbEngineId);	    	    
    	    $sbEngineInfo = $helperObj->__getSBEngineInfo($sbEngineId);
    	    
    	    switch ($info['subact']) {
    	        
    	        case "login":
    	            $sbSubmitUrl = $sbEngineInfo['engine_login_link'];
    	            $this->set('loginClass', 'curr');
    	            break;
    	        
    	        case "register":
    	            $sbSubmitUrl = $sbEngineInfo['engine_register_link'];
    	            $this->set('registerClass', 'curr');
    	            break;
    	            
    	        default:
    	            $sbSubmitUrl = $helperObj->__createSBSubmitUrl($sbEngineInfo, $projectInfo);
    	            $this->set('submitClass', 'curr');
    	            $this->updatelastShareId($projectId, $sbEngineId);
    	            break;
    	    }
    	    
    	    $this->set('sbSubmitUrl', $sbSubmitUrl);
    	    $this->set('subact', $info['subact']);
    	    $this->set('sbEngineInfo', $sbEngineInfo);
    	    $this->set('completed', 0);
	    }
	    $this->pluginRender('runproject');	   
	}
	
	/*
	 * update last shared id of a project
	 */
	function updatelastShareId($projectId, $sbId) {
        $sql = "Update sb_projects set last_shared_id=$sbId, last_shared_time=CURRENT_TIMESTAMP where id=$projectId";
        $this->db->query($sql);
	}
	
	/*
	 * func to delete project
	 */
	function deleteProject($projectId) {

		$sql = "delete from $this->tableName where id=$projectId";
		$this->db->query($sql);
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatus($projectId, $status){
		
		$sql = "update $this->tableName set status=$status where id=$projectId";
		$this->db->query($sql);		
	}

	/*
	 * func to get all projects
	 */
	function getAllProjects($condtions='') {
		
		$sql = "select sb.* from $this->tableName sb,websites w where sb.website_id=w.id and sb.status=1";
		$sql .= empty($condtions) ? "" : $condtions;
		$projectList = $this->db->select($sql);
		return $projectList;		
	}

	/*
	 * func to get project info
	 */
	function __getProjectInfo($projectId) {
		
		$sql = "select p.*,w.url from $this->tableName p,websites w where  p.website_id=w.id and p.id=$projectId";
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
	/*
	 * func to show project select box
	 */
    function showProjectSelectBox($data) {
        
        $condtions = isAdmin() ? "" : " and w.user_id=".isLoggedIn();
        $condtions .= empty($data['website_id']) ? "" : " and sb.website_id=".$data['website_id'];
        $projectList = $this->getAllProjects($condtions);
        $projectId = empty($data['project_id']) ? 0 : $data['project_id'];
        $this->set("projectId", $projectId);
        $this->set("projectList", $projectList);
        $this->set("submitForm", 1);
        $this->pluginRender('showprjselbox');
    }
}