<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 *
 */
class StatusReports extends SocialMediaEngine{

    // the variable to store the resources
    var $tableName = "sme_submissions";
    
    function viewReports($info = '') {
    	
    	if (!empty($info['id'])) {
    		$info['status_id'] = $info['id'];
    		$statusCtrler = New ManageStatus();
    		$statusInfo = $statusCtrler->__getStatusInfo($info['id']);
    		$info['project_id'] = $statusInfo['project_id'];
    		$info['from_time'] = $info['to_time'] = "";
    	}
        
        $userId = isLoggedIn();
        $projectCtrler = New ManageProject();
        if (isAdmin()) {
            $projectList = $projectCtrler->__getAllProjects();
        } else {
            $projectList = $projectCtrler->__getAllProjects($userId, true);
        }
        
        $this->set('projectList', $projectList);
        $this->set('projectId', $info['project_id']);        
        
        $statusCtrler = New ManageStatus();
        if (isAdmin()) {
            $statusList = $statusCtrler->__getAllStatusList($info['project_id']);
        } else {
            $statusList = $statusCtrler->__getAllStatusList($info['project_id'], $userId);
        }
        
        $this->set('statusList', $statusList);
        $this->set('statusId', $info['status_id']);
        
        $socialMediaCtrler = New SocialMediaResources();        
        if (isAdmin()) {
            $smList = $socialMediaCtrler->__getAllConnections();
        } else {
            $smList = $socialMediaCtrler->__getAllConnections($userId);
        }
        
        $this->set('smList', $smList);
        
        if (!empty ($info['from_time'])) {
			$fromTime = strtotime($info['from_time'] . ' 00:00:00');
		} else {
			$fromTime = isset($info['from_time']) ? "" : mktime(0, 0, 0, date('m'), date('d') - 7, date('Y'));
		}
		if (!empty ($info['to_time'])) {
			$toTime = strtotime($info['to_time'] . ' 23:59:59');
		} else {
			$toTime = isset($info['to_time']) ? "" : mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		}	
		
		if (!empty($fromTime)) {
			$this->set('fromTime', date('Y-m-d', $fromTime));
		}
		
		if (!empty($toTime)) {
			$this->set('toTime', date('Y-m-d', $toTime));
		}
		
		$subStatusList = array(
		    'Success' => $_SESSION['text']['label']['Success'],
		    'Failed' => $_SESSION['text']['label']['Fail'],
		);
		$this->set('subStatusList', $subStatusList);
        $this->pluginRender('viewreports');
    }
    
    function showStatusReport($info = '') {

        $userId = isLoggedIn();
        $pgScriptPath = PLUGIN_SCRIPT_URL. "&action=showreport";
        
        // query to fetch data from sme_submission
        $sql = "select s.*, q.share_title, p.project, l.url, q.schedule_time, c.resource_id, c.connection_name
        from $this->tableName s, sme_project p, sme_status q, sme_connection_links l, sme_connections c
        where q.project_id = p.id and q.id = s.status_id and l.id = s.connection_link_id and l.connection_id=c.id";
         
        if (!empty($info['project_id'])) {
            $projectId = intval($info['project_id']);
            $sql .= " and p.id=".$projectId;
        }
        
        if (!empty($info['keyword'])) {
            $projectId = intval($info['project_id']);
            $sql .= " and  q.share_title like '%".addslashes($info['keyword'])."%'";
        }
        
        if (!empty($info['status_id'])) {
            $statusId = intval($info['status_id']);
            $sql .= " and q.id=".$statusId;
        } 
        
        if (!empty($info['sm_id'])) {
            $smId = intval($info['sm_id']);
            $sql .= " and l.connection_id=".$smId;
        }
        
        if (!empty($info['sub_status'])) {
            $subStatus = addslashes($info['sub_status']);
            $sql .= " and s.submit_status='$subStatus'";
        }
        
        if (!empty ($info['from_time'])) {
            $fromTime = strtotime($info['from_time'] . ' 00:00:00');
            $sql .= " and s.submit_time>='$fromTime'";
        }
        
        if (!empty($info['to_time'])) {
            $toTime = strtotime($info['to_time'] . ' 23:59:59');
            $sql .= " and s.submit_time>='$toTime'";
        }
        
        if(!isAdmin()){
            $sql .= " AND p.user_id = '$userId'";
        }

        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'search_form', 'scriptDoLoadPost', 'subcontent', '');
        $this->set('pagingDiv', $pagingDiv);

        $sql .= " order by submit_time DESC limit ".$this->paging->start .",". $this->paging->per_page;
        $report = $this->db->select($sql);        
        $this->set('list', $report);
        $this->set('pageNo', !empty($info['pageno']) ? $info['pageno'] : 1);
        $this->set('pgScriptPath', $pgScriptPath);
        
        $resourceCtrler = New SocialMediaResources();
        $list = $resourceCtrler->__getAllResources();
        $resourceList = [];
        foreach ($list as $listInfo) {
            $resourceList[$listInfo['id']] = $listInfo['engine_name'];
        }
        
        $this->set('resourceList', $resourceList);
        $this->pluginRender('statusreports');
    }

    function saveSubmitionLog($statusId, $linkId, $dataList) {
        $dataList['status_id|int'] = $statusId;
        $dataList['connection_link_id|int'] = $linkId;
        $ret = $this->dbHelper->insertRow("sme_submissions", $dataList);
        return $ret;
    }
    
    function __isStatusAlreadyPostedToLink($statusId, $linkId) {
        $linkId = intval($linkId);
        $statusId = intval($statusId);
        $info = $this->dbHelper->getRow("sme_submissions", "status_id=$statusId and connection_link_id=$linkId order by id desc");
        return $info;
    }

}
?>