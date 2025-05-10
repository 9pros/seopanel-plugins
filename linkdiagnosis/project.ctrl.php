<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Project extends LinkDiagnosis{

	/*
	 * show projects list to manage
	 */
	function showProjectsManager($info='') {
		
		$userId = isLoggedIn();
		$info['user_id'] = intval($info['user_id']);
		
		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select p.*,u.username from ld_projects p,users u where p.user_id=u.id ";
		if(isAdmin()){
			$userCtrler = New UserController();
			$userList = $userCtrler->__getAllUsers();
			$this->set('userList', $userList);			
			if (!empty($info['user_id'])) {
				$pgScriptPath .= "&user_id=".$info['user_id'];
				$sql .= " and user_id=".$info['user_id'];
				$this->set('userId', $info['user_id']);
			}			
			$this->set('isAdmin', 1);
		} else {
			$sql .= " and user_id=$userId";
			$this->set('isAdmin', 0);
		}				
		
		# pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " limit ".$this->paging->start .",". $this->paging->per_page;
		
		$projectList = $this->db->select($sql);
		$this->set('list', $projectList);
		$this->set('pageNo', $_GET['pageno']);				
		$this->pluginRender('showprojectsmanager');
	}

	/*
	 * func to create new project
	 */ 
	function newProject($info=''){
						
		$userId = isLoggedIn();
								
		# get all users
		if(isAdmin()){
			$userCtrler = New UserController();
			$userList = $userCtrler->__getAllUsers();
			$this->set('userList', $userList);
			$this->set('userSelected', empty($info['user_id']) ? $userId : $info['user_id']);  			
			$this->set('isAdmin', 1);
		}
		$this->pluginRender('newproject');
	}
	
	/*
	 * func to create project
	 */
	function createProject($listInfo){
		
		if (isAdmin()) {
			$userId = empty($listInfo['user_id']) ? isLoggedIn() : intval($listInfo['user_id']);	
		} else {
			$userId = isLoggedIn();
		}
		
		$this->set('post', $listInfo);
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$this->setPluginTextsForRender('ldplugin', 'ld_texts');
		if(!$this->validate->flagErr){
			if (!$this->__checkName($listInfo['name'], $userId)) {
				$sql = "insert into ld_projects(name,user_id,status)
							values('".addslashes($listInfo['name'])."',$userId,1)";
				$this->db->query($sql);
				$this->showProjectsManager();
				exit;
			}else{				
				$errMsg['name'] = formatErrorMsg($this->pluginText['Project already exist']);
			}
		}
		
		$this->set('errMsg', $errMsg);
		$this->newProject($listInfo);
	}	


	/*
	 * func to edit project
	 */ 
	function editProject($projectId, $listInfo=''){
		
		if(!empty($projectId)){			
			if(empty($listInfo)){
				$listInfo = $this->__getProjectInfo($projectId);
				$listInfo['oldName'] = $listInfo['name'];
			}
			$this->set('post', $listInfo);
			
			# get all users
			if(isAdmin()){
				$userCtrler = New UserController();
				$userList = $userCtrler->__getAllUsers();
				$this->set('userList', $userList);  			
				$this->set('isAdmin', 1);
			}
			$this->pluginRender('editproject');
			exit;
		}		
	}
	
	/*
	 * func to update project
	 */
	function updateProject($listInfo){
		
		if (isAdmin()) {
			$userId = empty($listInfo['user_id']) ? isLoggedIn() : intval($listInfo['user_id']);	
		} else {
			$userId = isLoggedIn();
		}
		
		$this->set('post', $listInfo);
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$this->setPluginTextsForRender('ldplugin', 'ld_texts');
		if(!$this->validate->flagErr){

			if($listInfo['name'] != $listInfo['oldName']){
				if ($this->__checkName($listInfo['name'], $userId)) {
					$errMsg['name'] = formatErrorMsg($this->pluginText['Project already exist']);
					$this->validate->flagErr = true;
				}
			}

			if (!$this->validate->flagErr) {
				$sql = "update ld_projects set
						user_id = $userId,
						name = '".addslashes($listInfo['name'])."'
						where id=" . intval($listInfo['id']);
				$this->db->query($sql);
				$this->showProjectsManager();				
				exit;
			}			
		}
		
		$this->set('errMsg', $errMsg);
		$this->editProject($listInfo['id'], $listInfo);
	}
	
	/*
	 * func to delete project
	 */
	function deleteProject($projectId) {
		$projectId = intval($projectId);

		// delete all reports under the project
		$reportCtrler = $this->createHelper('report');
		$repList = $reportCtrler->__getAllReports($projectId);
		
		foreach($repList as $info) {
			$reportCtrler->deleteReport($info['id'], false);
		}	
		
		$sql = "delete from ld_projects where id=" . intval($projectId);
		$this->db->query($sql);
		
		$this->showProjectsManager();		
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatus($projectId, $status){
		$projectId = intval($projectId);
		$status = intval($status);
		$sql = "update ld_projects set status=$status where id=$projectId";
		$this->db->query($sql);		
	}


	/*
	 * function to check name of project
	 */
	function __checkName($name, $userId){		
		$sql = "select id from ld_projects where name='".addslashes($name)."' and user_id=" . intval($userId);
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}

	/*
	 * func to get all projects
	 */
	function getAllProjects($condtions='') {		
		$sql = "select p.*,u.username from ld_projects p,users u where p.user_id=u.id ";
		$sql .= empty($condtions) ? "" : $condtions;
		$projectList = $this->db->select($sql);
		return $projectList;		
	}

	/*
	 * func to get project info
	 */
	function __getProjectInfo($projectId) {		
		$sql = "select p.*,u.username from ld_projects p,users u where p.user_id=u.id and p.id=" . intval($projectId);
		$info = $this->db->select($sql, true);
		return $info;		
	}
	
}