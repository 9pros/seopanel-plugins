<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 */

class ManageStatus extends socialmediaengine {

    var $tableName = "sme_status";
     
    function showStatusManager($info='') {
        $userId = isLoggedIn();
        $pgScriptPath = PLUGIN_SCRIPT_URL . "&action=manageStatus";
        $projectCtrler = New ManageProject();
        $projectList = $projectCtrler->__getAllProjects($userId, true);
        $this->set('projectList', $projectList);        
        
        if (isset($info['from_time']) && empty($info['from_time'])) {
        	$fromTime = "";
        } else {
        	$fromTime = !empty($info['from_time']) ? $info['from_time'] : "";
        }
        
        if (isset($info['to_time']) && empty($info['to_time'])) {
        	$toTime = "";
        } else {
            $toTime = !empty($info['to_time']) ? $info['to_time'] : "";
        }
        
        $this->set('fromTime', $fromTime);
        $this->set('toTime', $toTime);
                
        $sql = "select s.*, p.project 
		        from sme_status s join sme_project p on s.project_id=p.id
		        where 1=1";
        
        $sql .= !empty($fromTime) ? " and schedule_time>='".addslashes("$fromTime 00:00:00")."'" : "";
        $sql .= !empty($toTime) ? " and schedule_time<='" . addslashes("$toTime 23:59:59")."'" : "";
        
        $searchKeyword = addslashes($info['keyword']);
        $sql .= !empty($searchKeyword) ? " and (share_title like '%$searchKeyword%' or share_description like '%$searchKeyword%')" : "";
        
        $projectId = intval($info['project_id']);
        $sql .= !empty($projectId) ? " and s.project_id=".$projectId : "";
        $this->set('projectId', $projectId);
         
        if(!isAdmin()){
            $sql .= " AND p.user_id = '$userId'";
        }

        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'listform', 'scriptDoLoadPost', 'content', '');
        $this->set('pagingDiv', $pagingDiv);

        $sql .= " order by schedule_time desc";
        $statusList = $this->db->select($sql);
        $this->set('list', $statusList);
        $this->set('pageNo', !empty($info['pageno']) ? $info['pageno'] : 1);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->set('post', $info);
        $this->pluginRender('managestatus');
    }
     
    function __changeStatus($id, $status){
    	$status = intval($status);
        $sql = "update $this->tableName set status=$status where id=".intval($id);
        $this->db->query($sql);
    }

    function DeleteStatus($statusId){
        $statusId = intval($statusId);
        $sql = "delete from $this->tableName where id=$statusId";
        $this->db->query($sql);
    }
    
    function updateSocialMediaSources($statusId) {
        $sourceHtml = "";
        
        if (!empty($statusId)) {
            $statusId = intval($statusId);
            $sql = "select distinct(engine_name) from sme_status_connection_mapping cm, sme_connection_links l, sme_connections c, sme_resources r
                    where cm.connection_link_id=l.id and l.connection_id=c.id and r.id=c.resource_id and cm.status_id=$statusId";
            $sourceList = $this->db->select($sql);
            
            foreach ($sourceList as $sourceInfo) {
                $sourceHtml .= '<i class="fab fa-'.strtolower($sourceInfo['engine_name']).'"></i>&nbsp';
            }
            
        }
        
        return $sourceHtml;
    }

    function newStatus($info='') {
        $this->set('post', $info);
        $this->set('sec', 'create');
        $userId = isLoggedIn();

        $projectCtrler = New ManageProject();
        $projectList = $projectCtrler->__getAllProjects($userId);
        $this->set('projectList', $projectList);
        $projectId = !empty($info['project_id']) ? intval($info['project_id']) : $projectList[0]['project_id'];

        $resourceCtrler = New SocialMediaResources();
        $this->set('resourceList', $resourceCtrler->__getAllResources());
        $this->set('sourceConnList', $resourceCtrler->getAllUserActiveConnections($userId));

        $this->set('projectId', $projectId);
        $this->pluginRender('editstatus');
    }
    
    function createStatus($listInfo) {
        $userId = isLoggedIn();
        $errMsg = [];
        $errMsg['project_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_id']));
        $errMsg['share_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_title']));
        $errMsg['share_description'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_description']));
        $errMsg['share_url'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_url']));
        
        // if schedule time is set
        if (!empty($listInfo['from_time'])) {
            $listInfo['schedule_time'] = $listInfo['from_time'] . " " . $listInfo['hour'] . ":00:00";
            if (strtotime($listInfo['schedule_time']) < time()) {
                $this->validate->flagErr = true;
                $errMsg['schedule_time'] = formatErrorMsg("Please enter a time greater than current time");
            } else {
                $listInfo['schedule_time'] = "'".addslashes($listInfo['schedule_time'])."'";
            }
        } else {
            $listInfo['schedule_time'] = 'NULL';
        }
        
        // if no error occured
        if(!$this->validate->flagErr){
            $listInfo['share_url'] = addHttpToUrl($listInfo['share_url']);
            $sql = "INSERT INTO $this->tableName(project_id, share_url, share_title, share_description,status, share_tags, share_image, schedule_time)
            		VALUES('".intval($listInfo['project_id'])."', '".addslashes($listInfo['share_url'])."', '".addslashes($listInfo['share_title'])."',
                    '".addslashes($listInfo['share_description'])."',1,'".addslashes($listInfo['share_tags'])."','".addslashes($listInfo['share_image'])."',
                    {$listInfo['schedule_time']})";
            $this->db->query($sql);
            $statusId = $this->db->getMaxId($this->tableName);
            
            // update post and connection mapping
            $resourceCtrler = New SocialMediaResources();
            $sourceConnList = $resourceCtrler->getAllUserActiveConnections($userId);
            foreach ($sourceConnList as $connectionList) {
            	foreach ($connectionList as $connectionInfo) {
            	
	            	// if connection links selected
	            	$postVar = "conn_link_" . $connectionInfo['id'];
	            	if (!empty($listInfo[$postVar])) {
	            
	            		// update link mapping
	            		foreach ($listInfo[$postVar] as $linkId) {
	            			$resourceCtrler->updatePostConnectionMapping($statusId, $linkId);
	            		}
	            	}
            	}
            }
            
            $this->showStatusManager($listInfo);
            exit;
        }
        
        $this->set('errMsg', $errMsg);
        $this->newStatus($listInfo);
    }
    
    function duplicateStatus($id) {
        
        $listInfo = [];
        if(!empty($id)){
            $listInfo = $this->__getStatusInfo($id);
        }
        
        $this->newStatus($listInfo);
    }
    
    function editStatus($id, $listInfo=''){
        $userId = isLoggedIn();
        
        if(!empty($id)){

            if(empty($listInfo)){
                $listInfo = $this->__getStatusInfo($id);
            }
            
            $projectCtrler = New ManageProject();
            $projectList = $projectCtrler->__getAllProjects($userId);
            $this->set('projectList', $projectList);
            $projectId = empty($listInfo['project_id']) ? $projectList[0]['project_id'] : intval($listInfo['project_id']);
            $this->set('projectId', $projectId);

            $resourceCtrler = New SocialMediaResources();
            $this->set('resourceList', $resourceCtrler->__getAllResources());
            $this->set('sourceConnList', $resourceCtrler->getAllUserActiveConnections($userId));
            $this->set('mappedLinkIdList', $resourceCtrler->getAllPostConnectionMapping($id));

            $this->set('post', $listInfo);
            $this->set('sec', 'update');
            $this->pluginRender('editstatus');
            exit;
        }

    }
    
    function updateStatus($listInfo) {
        $userId = isLoggedIn();
        $errMsg = [];
        $errMsg['project_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_id']));
        $errMsg['share_url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['share_url'])));
        $errMsg['share_title'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_title']));
        $errMsg['share_description'] = formatErrorMsg($this->validate->checkBlank($listInfo['share_description']));
        
        // if schedule time is set
        if (!empty($listInfo['from_time'])) {
            $listInfo['schedule_time'] = $listInfo['from_time'] . " " . $listInfo['hour'] . ":00:00";
            if (strtotime($listInfo['schedule_time']) < time()) {
                $this->validate->flagErr = true;
                $errMsg['schedule_time'] = formatErrorMsg("Please enter a time greater than current time");
            } else {
                $listInfo['schedule_time'] = "'".addslashes($listInfo['schedule_time'])."'";
            }
        } else {
            $listInfo['schedule_time'] = 'NULL';
        }

        if(!$this->validate->flagErr){
            $listInfo['share_url'] = addHttpToUrl($listInfo['share_url']);
            $sql = "UPDATE $this->tableName SET
					project_id='".intval($listInfo['project_id'])."', share_url='".addslashes($listInfo['share_url'])."',
                    share_title='".addslashes($listInfo['share_title'])."', share_description='".addslashes($listInfo['share_description'])."',
                    share_tags='".addslashes($listInfo['share_tags'])."', share_image='".addslashes($listInfo['share_image'])."', 
                    schedule_time={$listInfo['schedule_time']}
                    WHERE id = '".intval($listInfo['id'])."'";
            $this->db->query($sql);
            
            // update post and connection mapping
            $resourceCtrler = New SocialMediaResources();
            $mappedLinkIdList = $resourceCtrler->getAllPostConnectionMapping($listInfo['id']);
            $sourceConnList = $resourceCtrler->getAllUserActiveConnections($userId);
            $updateLinkIdList = [];
            foreach ($sourceConnList as $connectionList) {
            	foreach ($connectionList as $connectionInfo) {
            		 
            		// if connection links selected
            		$postVar = "conn_link_" . $connectionInfo['id'];
            		if (!empty($listInfo[$postVar])) {
            			 
            			// update link mapping
            			foreach ($listInfo[$postVar] as $linkId) {
            				$resourceCtrler->updatePostConnectionMapping($listInfo['id'], $linkId);
            				$updateLinkIdList[] = $linkId;
            			}
            		}
            	}
            }
            
            // delete mappings not selected now
            $deletedLinkList = array_diff($mappedLinkIdList, $updateLinkIdList);
            if (!empty($deletedLinkList)) {
            	$resourceCtrler->deletePostConnectionMapping($deletedLinkList);
            }
            
            $this->showStatusManager($listInfo);
            exit;
        }
        
        $this->set('errMsg', $errMsg);
        $this->editStatus($listInfo['id'], $listInfo);
    }
    
    function __getStatusInfo($id) {
        $sql = "select * from $this->tableName where id = " . intval($id);
        $info = $this->db->select($sql, true);
        return $info;
    }
    
    function __getAllStatusList($projectId = "", $userId = '') {
        $sql = "select s.*, p.project from $this->tableName s, sme_project p where s.project_id=p.id";
        $sql .= empty($projectId) ? "" : " and project_id=".intval($projectId);
        $sql .= empty($userId) ? "" : " and p.user_id=".intval($userId);
        $list = $this->db->select($sql);
        return $list;
    }
    
    function isStatusCronJobExecuted($statusId, $resourceId, $scheduledTime) {
        $sql = "select id from sme_submissions where status_id=$statusId and resource_id=$resourceId and cron=1 and submit_time>='$scheduledTime'";
        $submitInfo = $this->db->select($sql, true);
        return empty($submitInfo['id']) ? false : $submitInfo['id'];        
    }
    
    function startCronJob($info = '') {
        $sql = "select s.* from `sme_status` s, sme_project p
        where s.project_id=p.id and s.cron_job=1 and p.status=1 and s.status=1 
        and DATE(schedule_time)='".date('Y-m-d')."' and  schedule_time <= '".date('Y-m-d H:i:s')."'
        order by schedule_time ASC ";
        $statusList = $this->db->select($sql);
        
        // for each through the status list
        if (!empty($statusList)) {
            foreach ($statusList as $statusInfo) {
                echo "Processing status message: ". $statusInfo['share_title'] . "\n";
                $this->postMediaStatus($statusInfo['id'], true);                
            }            
        } else {
            echo "Scheduled Status List is Empty";
        }
    }
    
    function postMediaStatus($statusId, $cronJob = 0) {
    	$statusId = intval($statusId);
    	$errorMsg = "";
    	$succesMsg = "";
    	
    	if (!empty($statusId)) {
    		$statusInfo = $this->__getStatusInfo($statusId);
    		
    		$sql = "select c.*, l.url, m.connection_link_id from sme_status_connection_mapping m, sme_connections c, sme_connection_links l
    				where m.connection_link_id=l.id and c.id=l.connection_id and c.connection_status='connected' and m.status_id=$statusId
    				order by c.id";
    		$postLinkList = $this->db->select($sql);
    		
    		// if post link list is not empty
    		if (!empty($postLinkList)) {
    			$smResourcectrler = new SocialMediaResources();
    			$reportManager = new StatusReports();
    			
    			// loop through the links
    			foreach ($postLinkList as $linkInfo) {
    			    $linkId = $linkInfo['connection_link_id'];
    			    $submitInfo = $reportManager->__isStatusAlreadyPostedToLink($statusId, $linkId);
    			    
    			    // if status is not submitted already
    			    if (empty($submitInfo) || $submitInfo['submit_status'] != 'Success') {
    			        $mediaId = $linkInfo['resource_id'];
    			        $mediaInfo = $smResourcectrler->__getmediaInfo($mediaId);
    			        $apiInfo = $smResourcectrler->__getAllAPIDetails($mediaId, true);
    			        $componentObj = $this->createComponent($mediaInfo['engine_name']);
    			        $componentObj->redirectURL = PLUGIN_WEBPATH . "/" . SME_CALLBACK_PAGE;
    			        list($status, $resultMsg, $dataList) = $componentObj->postStatusMessage($linkInfo, $statusInfo, $apiInfo);
    			        
    			        // successfully posted or not
    			        if ($status) {
    			            $succesMsg .= $resultMsg."<br>\n";
    			            $dataList['submit_status'] = "Success";
    			        } else {
    			            $errorMsg .= $resultMsg."<br>\n";;
    			            $dataList['submit_status'] = "Failed";
    			        }
    			        
    			        $dataList['cron'] = $cronJob;
    			        $dataList['submit_log'] = $resultMsg;
    			        $dataList['submit_time'] = date("Y-m-d H:i:s");
    			        $reportManager->saveSubmitionLog($statusId, $linkId, $dataList);
    			    } else {
    			        $succesMsg .= "Already posted to {$linkInfo['url']}<br>\n";
    			    }
    			}
    		} else {
    		    $errorMsg .= "No links found to post status message.<br>\n";
    		}
    		
    		if ($cronJob) {
    		    echo $succesMsg;
    		    echo $errorMsg;
    		} else {
    		    Session::setSessionMessages($succesMsg, false);
    		    Session::setSessionMessages($errorMsg);
    		    $this->set('keyword', $statusInfo['share_title']);
    		    $this->pluginRender('statuspostmessage');
    		}
    	}
    }
    
    function showStatusSelectBox($projectId = '') {
        $statusList = $this->__getAllStatusList($projectId);
        $this->set('statusList', $statusList);
        $this->pluginRender('showstatusselectbox');
    }
    
    function updateImage($info) {
        $filename = "sme_". time() ."_". $_FILES['file']['name'];
        
        // Location
        $location = SP_TMPPATH ."/". $filename;
        $uploadOk = 1;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        
        // Valid Extensions
        $valid_extensions = array("jpg","jpeg","png");
        
        // Check file extension
        if( !in_array(strtolower($imageFileType),$valid_extensions) ) {
            $uploadOk = 0;
        }
        
        if ($uploadOk == 0) {
            echo 0;
        } else {
            
            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)){
                echo $filename;
            }else{
                echo 0;
            }
            
        }
    }
}
?>