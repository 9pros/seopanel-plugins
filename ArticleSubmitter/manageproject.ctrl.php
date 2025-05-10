<?php
/**
 * Description of ManageProject
 *
 * @author Raheela Muneer.
 */
class ManageProject extends ArticleSubmitter{
    
    // the database table name
    var $tableName = "as_project";
    var $tableName_article = "as_article";

    function  ShowProjects($info = ''){ 
        $pgScriptPath = PLUGIN_SCRIPT_URL;
        $userId = isLoggedIn();
        
        // query to fetch from tables
        $sql = "select asp.*, u.username as user_name from as_project asp, users u where asp.user_id=u.id";
        if(isAdmin()){
        	$this->set('isAdmin', 1);
        	$userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
            if (!empty($info['user_id'])) {
                $pgScriptPath .= "&user_id=".$info['user_id'];
                $sql .= " and user_id =" . intval($info['user_id']);
                $this->set('userId', $info['user_id']);
            }
        } else {
            $sql .= " and user_id = $userId";
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
        $this->set('pageNo', $_GET['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->pluginRender('ManageProject');
    }

    function __changeStatus($Id, $status,$type){
        $Id = intval($Id);
        $status = intval($status);
        $sql = "update $this->tableName set status=$status where id=$Id";
        $this->db->query($sql);
        
        if($type == "inactivate") {
            $sql = "update as_article set status=$status where 	project_id = $Id";
            $this->db->query($sql);
        }
        
    }

    function editproject($projectId, $listInfo='') {
    	
        if(!empty($projectId)){
        	
            if(empty($listInfo)){
                $listInfo = $this->__getProjectInfo($projectId);
                $listInfo['project_name'] = $listInfo['project'];
            }
            
            // get all users
            if(isAdmin()){
                $userCtrler = New UserController();
                $userList = $userCtrler->__getAllUsers();
                $this->set('userList', $userList);
                $this->set('isAdmin', 1);
            }

            $this->set('post', $listInfo);
            $this->set('sec', 'update');
            $this->pluginRender('editproject');
        }

    }
    
    function updateProjects($listInfo){
        $userId = isAdmin() ? intval($listInfo['user_id']) : isLoggedIn();
        $projectId = intval($listInfo['id']);
        $errMsg['project_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_name']));
        if(!$this->validate->flagErr){
        	if ($this->checkProjectName($listInfo['project_name'], $userId, $projectId)) {
	        	$errMsg['project_name'] = formatErrorMsg('Project name already exists.');
        	} else {
	            $sql = "UPDATE  $this->tableName SET
	            `project`='".addslashes($listInfo['project_name'])."',
	            `user_id`= $userId
	            WHERE id = $projectId";
	            $this->db->query($sql);
	            $this->ShowProjects(['user_id' => $userId]);
	            return true;
        	}
        }
        
        $this->set('errMsg', $errMsg);
        $this->editproject($listInfo['id'], $listInfo);
    }

    
	function DeleteProject($projectId){
        $projectId = intval($projectId);
        $sql = "delete from $this->tableName where id=$projectId";
        $this->db->query($sql);
    }
    
    function newprojectList($info='') {
    	
        // get all users
        if(isAdmin()){
            $userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
            $this->set('isAdmin', 1);
        }

        $this->set('sec', 'create');
        $this->set('post', $info);
        $this->pluginRender('editproject');
    }

    function createprojectList($listInfo) {
        $userId = isAdmin() ? intval($listInfo['user_id']) : isLoggedIn();
        $errMsg['project_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_name']));
        
        // insert query
        if(!$this->validate->flagErr){
        	if($this->checkProjectName($listInfo['project_name'], $userId)){
	        	$errMsg['project_name'] = formatErrorMsg('Project name already exists.');
        	} else {
	            $sql = "INSERT INTO $this->tableName(`project`, `user_id`, `status`) 
	            VALUES ('".addslashes($listInfo['project_name'])."', '$userId', 1 )";
	            $this->db->query($sql);
	            $this->ShowProjects(['user_id' => $userId]);
	            return true;
        	}
        }

        $this->set('errMsg', $errMsg);
        $this->newprojectList($listInfo);
    }
    
    function checkProjectName($projectName, $userId, $projectId = false){
        $sql = "select * from $this->tableName where project='".addslashes($projectName)."' and user_id=" . intval($userId);
        $sql .= !empty($projectId) ? " and id!=".intval($projectId) : "";
        $data = $this->db->select($sql, true);
        return empty($data['id']) ? false :  $data['id'];
    }

    function __getAllProjects($cond= ''){
        $sql = "select * from $this->tableName where 1=1 $cond order by project";
        $projectList = $this->db->select($sql);
        return $projectList;
    }
    
    function __getProjectInfo($projectId) {
        $sql = "SELECT * FROM $this->tableName WHERE `id` = " . intval($projectId);
        $info = $this->db->select($sql, true);
        return $info;
    }

}
?>