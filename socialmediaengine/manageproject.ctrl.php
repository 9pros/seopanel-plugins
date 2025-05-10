<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 */

class ManageProject extends socialmediaengine {

    var $tableName = "sme_project";

    function showProjectManager($info='') { 

        $pgScriptPath = PLUGIN_SCRIPT_URL;
        $userId = isLoggedIn();
        $sql = "select p.*,u.username users from `sme_project` p, users u where p.user_id = u.id";
        
        if(isAdmin()){
            $userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
            if (!empty($info['user_id'])) {
                $pgScriptPath .= "&user_id=".$info['user_id'];
                $sql .= " and user_id=". intval($info['user_id']);
                $this->set('userId', $info['user_id']);
            }
            $this->set('isAdmin', 1);
        } else {
            $sql .= " and user_id=$userId";
            $this->set('isAdmin', 0);
        }
        
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');
        $this->set('pagingDiv', $pagingDiv);

        $sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
        $campaignList = $this->db->select($sql);
        $this->set('list', $campaignList);
        $this->set('pageNo', !empty($info['pageno']) ? $info['pageno'] : 1);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->set('spTextDiary', $this->getLanguageTexts('seodiary', $_SESSION['lang_code']));
        $this->pluginRender('manageproject');
    }

    function __changeStatus($id, $status, $type){
        $id = intval($id);
        $status = intval($status);
        $sql = "update $this->tableName set status=$status where id=$id";
        $this->db->query($sql);
    }
     
    function __getProjectInfo($projectId) {
        $projectId = intval($projectId);
        $sql = "SELECT * FROM $this->tableName WHERE `id` = $projectId";
        $info = $this->db->select($sql, true);
        return $info;
    }
  
    function __getAllProjects($userId='', $isAdminCheck=false){
        $userId = intval($userId);
        $sql = "select * from $this->tableName where status=1";
        
        if(!$isAdminCheck || !isAdmin() ){
            if(!empty($userId)) $sql .= " and user_id=$userId";
        }
        
        $sql .= " order by project";
        $projectList = $this->db->select($sql);
        return $projectList;
    }

    function DeleteProject($projectId){
        $projectId = intval($projectId);
        $sql = "delete from $this->tableName where id=$projectId";
        $this->db->query($sql);
    }
    
    function editproject($projectId, $listInfo=''){
        if(!empty($projectId)){

            if(empty($listInfo)){
                $listInfo = $this->__getProjectInfo($projectId);
            }

            if(isAdmin()){
                $userCtrler = New UserController();
                $userList = $userCtrler->__getAllUsers();
                $this->set('userList', $userList);
                $this->set('isAdmin', 1);
                $this->set('userSelected', $listInfo['user_id']);
            }
            	
            $this->set('post', $listInfo);
            $this->set('sec', 'update');
            $this->pluginRender('editproject');
        }
    }
    
    function updateProjects($listInfo){
        $errMsg = [];
        $userId = isAdmin() ? intval($listInfo['user_id']) : isLoggedIn();
        $errMsg['project'] = formatErrorMsg($this->validate->checkBlank($listInfo['project']));

        $this->set('post', $listInfo);
        $this->set('post', $listInfo);

        if(!$this->validate->flagErr){
            
            if($this->__checkProjectNameExists($listInfo['project'], $userId, $listInfo['id'])) {
                $this->validate->flagErr = true;
                $errMsg['project'] = formatErrorMsg( $_SESSION['text']['label']['already exist']);
            }
            
            if(!$this->validate->flagErr){
                $sql = "UPDATE  $this->tableName SET
                        `project`='".addslashes($listInfo['project'])."', 
                        `user_id`='$userId'
                        WHERE id = '$listInfo[id]' ";
                $this->db->query($sql);
                $this->showProjectManager($listInfo);
                exit;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->editproject($listInfo['id'], $listInfo);
    }
    
    function newprojectList($info=''){ 
        $userId = isLoggedIn();
        if(isAdmin()){
            $userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
            $this->set('userSelected', empty($info['user_id']) ? $userId : $info['user_id']);
            $this->set('isAdmin', 1);
        }

        $this->set('sec', 'create');
        $this->set('post', $info);
        $this->pluginRender('editproject');
    }
     
    function createprojectList($listInfo) { 
        $userId = isAdmin() ? intval($listInfo['user_id']) : isLoggedIn();
        $errMsg = [];
        $errMsg['project'] = formatErrorMsg($this->validate->checkBlank($listInfo['project']));

        if(!$this->validate->flagErr){
            
            if($this->__checkProjectNameExists($listInfo['project'], $userId)) {
                $this->validate->flagErr = true;
                $errMsg['project'] = formatErrorMsg($_SESSION['text']['label']['already exist']);
            }
            
            if(!$this->validate->flagErr){
                $sql = "INSERT INTO `sme_project`(`project`, `status`, `user_id`) 
                        VALUES ('".addslashes($listInfo['project'])."', 1, '$userId')";
                $this->db->query($sql);
                $this->showProjectManager();
                exit;
            }
        }

        $this->set('errMsg', $errMsg);
        $this->newprojectList($listInfo);
    }
    
    function __checkProjectNameExists($projectName, $userId, $projectId = 0) {
        $userId = intval( $userId );
        $projectId = intval($projectId);
        $projectName = addslashes($projectName);
        $sql = "select id from $this->tableName where user_id=$userId and project='$projectName'";
        $sql .= !empty( $projectId ) ? " and id!=$projectId" : "";
        $listInfo = $this->db->select( $sql, true );
        return !empty( $listInfo ['id'] ) ? $listInfo ['id'] : false;
    }
    
}
?>