<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Seo Panel
 *
 */
class SocialMediaResources extends SocialMediaEngine{

    // the variable to store the resources
    var $tableName = "sme_resources";
    
    var $connStatusList;
    var $redirectURL = '';
    
    function __construct() {
    	parent::__construct();
    	$spTextMyAcct = $this->getLanguageTexts('myaccount', $_SESSION['lang_code']);
    	$this->connStatusList = array(
    		'connected' => $spTextMyAcct["Connected"],
    		'pending' => $spTextMyAcct["Disconnected"],
    	);
    }

    function __getAllResources($searchCol = 'status', $searchvalue = 1) {
        $sql = "SELECT * FROM $this->tableName where 1=1";
        $sql .= empty($searchCol) ? "" : " and $searchCol='" . addslashes($searchvalue) . "'";
        $list = $this->db->select($sql);
        $resourceList = array();
        foreach ($list as $listInfo) {
            $resourceList[$listInfo['id']] = $listInfo;       
        }
        return $resourceList;
    }
    
    function showSocialMediaManager($info =' ') {
        $pgScriptPath = PLUGIN_SCRIPT_URL. "&action=listSocialMedia";
        $sql = "select * from $this->tableName";
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');
        $this->set('pagingDiv', $pagingDiv);
        
        $sql .= " order by engine_name limit ".$this->paging->start .",". $this->paging->per_page;
        $mediaList = $this->db->select($sql);
        
        $this->set('list', $mediaList);
        $this->set('pageNo', $_GET['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->pluginRender('socialmedialist');
    }
    
    function __changeStatus($id, $status){
    	$id = intval($id);
    	$status = intval($status);
        $sql = "update $this->tableName set status=$status where id=$id";
        $this->db->query($sql);
    }
    
    function editmedia($mediaId, $mediaInfo='') {
        if (!empty($mediaId)) {
             
            // get media details
            if(empty($mediaInfo)){
                $mediaInfo = $this->__getmediaInfo($mediaId);
            }
                
            // get media api details
            $mediaInfo['api_list'] = $this->__getAllAPIDetails($mediaId);
                        
            $this->set('post', $mediaInfo);
            $this->set('sec', 'update');
            $this->pluginRender('editmedia');
            exit;
        }
    }
    
    function updateSocialMedia($listInfo){
        $errMsg = [];
        
        // check api keys added
        $apiKeyList = $this->__getAllAPIDetails($listInfo['id']);
        foreach ($apiKeyList as $apiInfo) {
            $errMsg[$apiInfo['api_key_name']] = formatErrorMsg($this->validate->checkBlank($listInfo[$apiInfo['api_key_name']]));
        }
        
        if(!$this->validate->flagErr) {
            $this->updateAPIDetails($apiKeyList, $listInfo);
            $this->showSocialMediaManager($listInfo);
            exit;
        }
        
        $this->set('errMsg', $errMsg);
        $this->editmedia($listInfo['id'], $listInfo);
    }
    
    function __getmediaInfo($mediaId) {
        $mediaId = intval($mediaId);
        $sql = "SELECT * FROM $this->tableName WHERE `id` = $mediaId";
        $info = $this->db->select($sql, true);
        return $info;
    }
    
    function __getAllAPIDetails($resourceId, $format = false) {
        $sql = "SELECT * FROM `sme_api_details` WHERE `resource_id`=". intval($resourceId);
        $apiList = $this->db->select($sql);
        
        // if needs to get api details as an array
        if ($format) {
            $apiInfo = array();
            foreach ($apiList as $info) {
                $apiInfo[$info['api_key_name']] =  $info['api_key_value'];   
            }
            return $apiInfo;
        } else {
            return $apiList;    
        }
    }
    
    function updateAPIDetails($apiKeyList, $listInfo) {
        $listInfo['id'] = intval($listInfo['id']);
        foreach ($apiKeyList as $apiInfo) {
            $apiInfo['api_key_name'] = addslashes($apiInfo['api_key_name']);
            $sql = "update sme_api_details set api_key_value='".addslashes($listInfo[$apiInfo['api_key_name']])."'";
            $sql .= " where api_key_name='{$apiInfo['api_key_name']}' and resource_id=".$listInfo['id'];
            $this->db->query($sql);
        }
    }
    
    function getAllUserActiveConnections($userId) {
        $sourceConnList = [];
        $userId = intval($userId);
        $sql = "select r.engine_name,c.* from sme_connections c, sme_resources r
                where c.resource_id=r.id and c.user_id=$userId and c.status=1 and r.status=1
                and c.connection_status='connected'";
        $connectionList = $this->db->select($sql);
        
        if (!empty($connectionList)) {
            foreach ($connectionList as $connInfo) {
                $connInfo['links'] = $this->getConnectionLinks($connInfo['id']);
                
                if (empty($sourceConnList[$connInfo['resource_id']])) {
                    $sourceConnList[$connInfo['resource_id']] = [];
                }
                
                $sourceConnList[$connInfo['resource_id']][] = $connInfo;                
            }
        }
        
        return $sourceConnList;
    }
    
    function __getAllConnections($userId = false, $resourceId = false, $searchInfo = array()) {
        $whereCond = "1=1";
        $whereCond .= !empty($userId) ? " and user_id=".intval($userId) : "";
        $whereCond .= !empty($resourceId) ? " and resource_id=".intval($resourceId) : "";
        $list = $this->dbHelper->getAllRows('sme_connections', $whereCond);
        return $list;
    }
    
    function getConnectionLinks($connectionId) {
        $connectionId = intval($connectionId);
        return $this->dbHelper->getAllRows("sme_connection_links", "connection_id=$connectionId");
    }
    
    function updatePostConnectionMapping($statusId, $linkId) {
    	$statusId = intval($statusId);
    	$linkId = intval($linkId);
    	$sql = "insert into sme_status_connection_mapping(status_id, connection_link_id) values($statusId, $linkId) 
    			ON DUPLICATE KEY UPDATE status_id=$statusId";
    	$ret = $this->db->query($sql);
    	return $ret;
    }
    
    function getAllPostConnectionMapping($statusId) {
        $statusId = intval($statusId);
        $mappingList = $this->dbHelper->getAllRows("sme_status_connection_mapping", "status_id=$statusId");
        $linkIdList = [];
        
        foreach ($mappingList as $mappInfo) {
        	$linkIdList[] = $mappInfo['connection_link_id'];
        }
        
        return $linkIdList;
    }
    
    function deletePostConnectionMapping($deletedLinkList) {
    	$cond = "connection_link_id in (";
    	foreach ($deletedLinkList as $id) {
    		$cond .= "$id,";	
    	}
    	
    	$cond .= "0)";
    	$ret = $this->dbHelper->deleteRows("sme_status_connection_mapping", $cond);
    	return $ret;
    }    
    
    function showConnectionManager($info='') {
        $userId = isLoggedIn();
        $pgScriptPath = PLUGIN_SCRIPT_URL . "&action=connectionManager";
        
        $resourceList = $this->__getAllResources();
        $this->set('resourceList', $resourceList);
        
        $sql = "select c.*, engine_name, u.username
		        from sme_connections c join sme_resources r on c.resource_id=r.id join users u on c.user_id=u.id
		        where 1=1";
        
        if (empty($info)) {
            $info = [];
            $info['keyword'] = '';
            $info['user_id'] = '';
            $info['resource_id'] = '';
        } else {
            $info['resource_id'] = isset($info['media_id']) ? $info['media_id'] : $info['resource_id'];
        }
        
        $searchKeyword = addslashes($info['keyword']);
        $sql .= !empty($searchKeyword) ? " and connection_name like '%$searchKeyword%'" : "";
        
        $sourceId = intval($info['resource_id']);
        $sql .= !empty($sourceId) ? " and c.resource_id=".$sourceId : "";
        $this->set('sourceId', $sourceId);
        
        if(isAdmin()){
            $userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
            $sql .= !empty($info['user_id']) ? " and user_id=". intval($info['user_id']) : "";
        } else {
            $sql .= " AND c.user_id = '$userId'";
        }
        
        $info['connection_status'] = addslashes($info['connection_status']);
        $sql .= !empty($info['connection_status']) ? " and connection_status='{$info['connection_status']}'" : "";
        
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'listform', 'scriptDoLoadPost', 'content', '');
        $this->set('pagingDiv', $pagingDiv);
        
        $sql .= " order by id desc limit ".$this->paging->start .",". $this->paging->per_page;
        $statusList = $this->db->select($sql);
        $this->set('list', $statusList);
        $this->set('pageNo', !empty($info['pageno']) ? $info['pageno'] : 1);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->set('post', $info);
        $this->set('spTextMyAccount', $this->getLanguageTexts('myaccount', $_SESSION['lang_code']));
        $this->set('connStatusList', $this->connStatusList);
        $this->pluginRender('connection_list');
    }
    
    function newConnection($info='') {
        $this->set('sec', 'create');
        $resourceCtrler = New SocialMediaResources();
        $this->set('resourceList', $resourceCtrler->__getAllResources());
        
        if (empty($info['connection_links'])) {
            $info['connection_links'] = [''];
        }
        
        $this->set('post', $info);
        $this->pluginRender('edit_connection');
    }
    
    function createConnection($listInfo) {
        $userId = isLoggedIn();
        $errMsg = [];
        $errMsg['connection_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['connection_name']));
        $errMsg['resource_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['resource_id']));
        
        if(!$this->validate->flagErr){
            
            if($this->__checkConnectionNameExists($listInfo['connection_name'], $userId)) {
                $this->validate->flagErr = true;
                $errMsg['connection_name'] = formatErrorMsg($_SESSION['text']['label']['already exist']);
            }
            
            if(!$this->validate->flagErr){
                $sql = "INSERT INTO `sme_connections`(`connection_name`, `resource_id`, `user_id`, connection_status, creation_date)
                        VALUES ('".addslashes($listInfo['connection_name'])."', ".intval($listInfo['resource_id']).", '$userId', 'pending', '".date('Y-m-d H:i:s')."')";
                $this->db->query($sql);
                $connectionId = $this->db->getMaxId('sme_connections');
                
                // insert connection links
                foreach ($listInfo['connection_links'] as $link) {
                    if (!empty($link)) {
                        $this->__insertConnectionLink($connectionId, $link);
                    }
                }
                
                $this->showConnectionManager();
                exit;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->newConnection($listInfo);
    }
    
    function deleteConnection($connectionId) {
        $ret = $this->dbHelper->deleteRows("sme_connections", "id=".intval($connectionId));
        $this->showConnectionManager();
        return $ret;
    }
    
    function __getConnectionInfo($connectionId) {
        $connInfo = $this->dbHelper->getRow("sme_connections", "id=".intval($connectionId));
        return $connInfo;
    }
    
    function __checkConnectionNameExists($connName, $userId, $connId = 0) {
        $userId = intval( $userId );
        $connId = intval($connId);
        $connName = addslashes($connName);
        $sql = "select id from sme_connections where user_id=$userId and connection_name='$connName'";
        $sql .= !empty( $connId ) ? " and id!=$connId" : "";
        $listInfo = $this->db->select( $sql, true );
        return !empty( $listInfo ['id'] ) ? $listInfo ['id'] : false;
    }
    
    function __insertConnectionLink($connectionId, $link) {
        $connectionId = intval($connectionId);
        $link = addHttpToUrl(addslashes($link));
        $sql = "INSERT INTO `sme_connection_links`(`connection_id`, `url`)
                VALUES ($connectionId, '$link') ON DUPLICATE KEY UPDATE url='$link'";
        $ret = $this->db->query($sql);
        return $ret;
    }
    
    function __deleteConnectionLink($linkId) {
        $ret = $this->dbHelper->deleteRows("sme_connection_links", "id=".intval($linkId));
        return $ret;
    }
    
    function __updateConnectionLink($linkId, $link) {
        $ret = $this->dbHelper->updateRow("sme_connection_links", ['url' => addslashes($link)], "id=".intval($linkId));
        return $ret;
    }
    
    function __getAllConnectionLinks($connectionId) {
        $linkList = $this->dbHelper->getAllRows("sme_connection_links", "connection_id=".intval($connectionId));
        return $linkList;
    }
    
    function editConnection($connectionId, $info='') {
        if(!empty($connectionId)){
            $this->set('sec', 'update');
            
            if(empty($info)){
                $info = $this->__getConnectionInfo($connectionId);
                $info['connection_links'] = [];
                $linkList = $this->__getAllConnectionLinks($connectionId);
                foreach($linkList as $linkInfo) {
                    $info['connection_links']["id_" . $linkInfo['id']] = $linkInfo['url'];
                }
            }
            
            if (empty($info['connection_links'])) {
                $info['connection_links'] = [''];
            }
            
            $resourceCtrler = New SocialMediaResources();
            $this->set('resourceList', $resourceCtrler->__getAllResources());
            
            $this->set('post', $info);
            $this->pluginRender('edit_connection');
        }
    }
    
    function updateConnection($listInfo) {
        $userId = isLoggedIn();
        $errMsg = [];
        $errMsg['connection_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['connection_name']));
        $errMsg['resource_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['resource_id']));
        $connectionId = intval($listInfo['id']);
        
        if(!$this->validate->flagErr){
            
            if($this->__checkConnectionNameExists($listInfo['connection_name'], $userId, $connectionId)) {
                $this->validate->flagErr = true;
                $errMsg['connection_name'] = formatErrorMsg($_SESSION['text']['label']['already exist']);
            }
            
            if(!$this->validate->flagErr){
                $sql = "Update `sme_connections` set
                        connection_name = '".addslashes($listInfo['connection_name'])."', 
                        resource_id = ".intval($listInfo['resource_id']).", 
                        user_id = $userId
                        where id=$connectionId";
                $this->db->query($sql);
                
                // update existing links
                $existingLinks = $this->__getAllConnectionLinks($connectionId);
                foreach($existingLinks as $linkInfo) {
                    $linkIdCol = "id_".$linkInfo['id'];
                    if (empty($listInfo['connection_links'][$linkIdCol])) {
                        $this->__deleteConnectionLink($linkInfo['id']);
                    } else {
                        $this->__updateConnectionLink($linkInfo['id'], $listInfo['connection_links'][$linkIdCol]);
                    }
                }
                
                // insert new connection links
                foreach ($listInfo['connection_links'] as $linkId => $link) {
                    if (!empty($link) && !stristr($linkId, "id_")) {
                        $this->__insertConnectionLink($connectionId, $link);
                    }
                }
                
                $this->showConnectionManager();
                exit;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->newConnection($listInfo);
    }    
    
    function doSocialMediaConnection($connectionId) {
        if (!empty($connectionId)) {
            $connectionInfo = $this->__getConnectionInfo($connectionId);
            $mediaId = $connectionInfo['resource_id'];
            $mediaInfo = $this->__getmediaInfo($mediaId);
            $apiInfo = $this->__getAllAPIDetails($mediaId, true);
            
            $redirectURL = PLUGIN_WEBPATH . "/" . SME_CALLBACK_PAGE;
            $componentObj = $this->createComponent($mediaInfo['engine_name']);
            list($status, $loginUrlRes) = $componentObj->connectToSocialMedia($connectionId, $apiInfo, $redirectURL);
            
            // set connection id in session to verify during callback
            Session::setSession('SP_SME_CONN_ID', $connectionId);
            
            // check for status of the call
            if ($status) {
                $this->set('loginLink', $loginUrlRes);
                $this->pluginRender('connection_redirect');
            } else {
                Session::setSession('SP_SME_CONN_ID', "");
                Session::setSessionMessages($loginUrlRes);
                $this->showConnectionManager(['keyword' => $connectionInfo['connection_name']]);
            }            
        }
    }
    
    function callbackSMConnection($data) {
        $connectionId = Session::readSession("SP_SME_CONN_ID");
        if (!empty($connectionId)) {
            $connectionInfo = $this->__getConnectionInfo($connectionId);
            $mediaId = $connectionInfo['resource_id'];
            $mediaInfo = $this->__getmediaInfo($mediaId);
            $apiInfo = $this->__getAllAPIDetails($mediaId, true);
            
            $componentObj = $this->createComponent($mediaInfo['engine_name']);
            $componentObj->redirectURL = PLUGIN_WEBPATH . "/" . SME_CALLBACK_PAGE;
            list($status, $resultMsg, $dataList) = $componentObj->connectionCallback($connectionId, $apiInfo, $data);
            
            // check for status of the call
            if ($status) {
                $dataList['connection_status'] = "connected";
                Session::setSessionMessages($resultMsg, false);
            } else {
                $dataList['connection_status'] = "pending";
                Session::setSessionMessages($resultMsg);
            }
            
            $dataList['connection_date'] = date('Y-m-d H:i:s');
            $dataList['connection_log'] = $resultMsg;
            $this->updatConnectionResultsInfo($connectionId, $dataList);
            $redirectUrl = PLUGIN_SCRIPT_URL . "&sec=show&action=connectionManager&keyword=".$connectionInfo['connection_name'];
            redirectUrl($redirectUrl);
        } else {
            showErrorMsg("Error: Connection id is empty.");
        }
    }
    
    function updatConnectionResultsInfo($connectionId, $dataList) {
        $ret = $this->dbHelper->updateRow("sme_connections", $dataList, "id=".intval($connectionId));
        return $ret;
    }
    
    function removeSocialMediaConnection($connectionId) {
        if (!empty($connectionId)) {
            $connectionInfo = $this->__getConnectionInfo($connectionId);
            $mediaId = $connectionInfo['resource_id'];
            $mediaInfo = $this->__getmediaInfo($mediaId);
            $apiInfo = $this->__getAllAPIDetails($mediaId, true);
            
            $componentObj = $this->createComponent($mediaInfo['engine_name']);
            $dataList = $componentObj->removeConnection($connectionInfo, $apiInfo);
            
            $dataList['connection_log'] = "";
            $dataList['connection_status'] = "pending";
            $this->updatConnectionResultsInfo($connectionId, $dataList);
            Session::setSessionMessages("Successfully disconnected", false);
            $this->showConnectionManager(['keyword' => $connectionInfo['connection_name']]);
        } 
    }
    
    function crawlSubmissionPages($connectionId) {
        if (!empty($connectionId)) {
            $connectionInfo = $this->__getConnectionInfo($connectionId);
            $mediaId = $connectionInfo['resource_id'];
            $mediaInfo = $this->__getmediaInfo($mediaId);
            $apiInfo = $this->__getAllAPIDetails($mediaId, true);
            $componentObj = $this->createComponent($mediaInfo['engine_name']);
            
            // get submission pages
            list($status, $resultMsg, $dataList) = $componentObj->getSubmissionPages($connectionInfo, $apiInfo);
            if ($status) {
                
                // update existing links
                $links = $this->__getAllConnectionLinks($connectionId);
                $existingLinks = [];
                foreach($links as $linkInfo) {
                    $existingLinks[] = $linkInfo['url'];    
                }
                
                // insert new connection links
                foreach ($dataList as $link) {
                    if (!empty($link) && !in_array($link, $existingLinks)) {
                        $this->__insertConnectionLink($connectionId, $link);
                    }
                }
                
                showSuccessMsg($resultMsg, false);
            } else {
                showErrorMsg($resultMsg, false);
            }
            
        }
    }
    
}
?>