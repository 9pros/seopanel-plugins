<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class DIHelper extends DirectoryImporter{
    
    var $dirCols;      // The columns for directory script
    var $linkTypes;    // The types of links allowed by a directory script

    function loadDIHelper() {
        $this->dirCols = array('captcha_script', 'search_script', 'title_col', 'url_col', 'description_col', 'name_col', 'email_col', 'category_col', 'cptcha_col', 'imagehash_col', 'imagehashurl_col', 'reciprocal_col', 'extra_val');
        $this->linkTypes = array('normal', 'free', 'reciprocal');
    }
		
	/*
	 * func to show directory manager
	 */ 
	function showDirectoryManager($info=''){
	    
	    $info['lang_code'] = empty($info['langcode']) ? $info['lang_code'] : $info['langcode'];
		$capcheck = isset($info['capcheck']) ? (($info['capcheck'] == 'yes') ? 1 : 0 ) : "";  
		$sql = "SELECT d.*,l.lang_name,dm.name as scriptname FROM directories d,languages l,di_directory_meta dm where d.lang_code=l.lang_code and d.script_type_id=dm.id";	
		if(preg_match('/^\d+$/', $info['stscheck'])) $sql .= " and working=".$info['stscheck'];	
		if(!empty($info['dir_name'])) $sql .= " and domain like '%{$info['dir_name']}%'";
		if($info['capcheck'] != '') $sql .= " and is_captcha=$capcheck";
		if(preg_match('/^\d+$/', $info['google_pagerank'])) $sql .= " and google_pagerank={$info['google_pagerank']}";
		if(!empty($info['lang_code'])) $sql .= " and d.lang_code='{$info['lang_code']}'";
		$sql .= " order by d.id";		
		
		// pagination setup
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&dir_name=".urlencode($info['dir_name'])."&stscheck={$info['stscheck']}&capcheck={$info['capcheck']}&langcode={$info['lang_code']}&google_pagerank={$info['google_pagerank']}";		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " limit ".$this->paging->start .",". $this->paging->per_page;
		$this->set('pgScriptPath', $pgScriptPath);
		$this->set('pageNo', $this->paging->current_page);
		
		$statusList = array(
		    "-- ".$_SESSION['text']['common']['Select']." --" => '',
			$_SESSION['text']['common']['Active'] => 1,
			$_SESSION['text']['common']['Inactive'] => 0,
		);
		
		$captchaList = array(
			$_SESSION['text']['common']['Yes'] => 'yes',
			$_SESSION['text']['common']['No'] => 'no',
		);
		
		$scriptList = $this->getAllDirectoryScripts();
		$dirScriptList = array();
		foreach ($scriptList as $sInfo) {
		    $dirScriptList[$sInfo['id']] = $sInfo;
		}
	    $this->set('dirScriptList', $dirScriptList);
	    $this->set('helperObj', $this);
		
		$langCtrler = New LanguageController();
		$langList = $langCtrler->__getAllLanguages();
		$this->set('langList', $langList);
		
		$this->set('statusList', $statusList);
		$this->set('captchaList', $captchaList);				
		$dirList = $this->db->select($sql);		
		$this->set('list', $dirList);
		$this->set('info', $info);
		$this->set('dirCtrler', $this->dirCtrler);
		
		$this->pluginRender('directorylist');
	}

	/*
	 * function to update directory type
	 */
	function updateDirType($data) {
	    
	    $dirInfo = $this->dirCtrler->__getDirectoryInfo($data['dirid']);
	    $scriptInfo = $this->getDirectoryScriptInfo($dirInfo['script_type_id']);	    
	    
	    $linkType = $data['link_type_'.$data['dirid']];
        $replace = $scriptInfo['link_type_col'].'='.$linkType.'&';
        $dirType = preg_replace('/'.$scriptInfo['link_type_col'].'=.*?&/', $replace, $dirInfo['extra_val']);
        $sql = "Update directories set extra_val='$dirType' where id=".$data['dirid'];
        $this->db->query($sql);
        print "<script>alert('".$dirInfo['domain']." - ".$this->pluginText['Directory type changed to']." $linkType')</script>";
	}
	
	/*
	 * function to show import directory
	 */
	function showImportDirectories($data) {
        
	    $data['lang_code'] = empty($data['lang_code']) ? 'en' : $data['lang_code'];
	    $this->set('post', $data);
	    $dirScriptList = $this->getAllDirectoryScripts();
	    $dirScriptId = empty($data['script_id']) ?  $dirScriptList[0]['id'] : $data['script_id'];
	    $this->set('dirScriptList', $dirScriptList);
	    $this->set('dirScriptId', $dirScriptId);
	    
	    $dirScriptInfo = array();
	    foreach ($dirScriptList as $info) {
	        if ($info['id'] == $dirScriptId) {	            
	            $dirScriptInfo = $info;
	            break;
	        }
	    }
	    $this->set('dirScriptInfo', $dirScriptInfo);
	    $this->set('helperObj', $this);
	    	    
		$langController = New LanguageController();
		$this->set('langList', $langController->__getAllLanguages());
	    
	    $this->pluginRender('showimportdirectory');
	}
	
	/*
	 * function to import directories to database
	 */
	function importDirectories($data) {
	    
	    $errMsg['directories'] = formatErrorMsg($this->validate->checkBlank($data['directories']));
		if(!$this->validate->flagErr){
		    $resInfo['invalid'] = $resInfo['existing'] = $resInfo['valid'] = 0;
		    $checkDirFromId = 0;
		    $dirList = explode(",", $data['directories']);
		    foreach ($dirList as $directory) {
		        if (preg_match('/\w/', $directory)) {
		            if ($this->__checkName($directory)) {
		                $resInfo['existing']++;
		            } else {
		                $data['submit_url'] = $directory;
		                if ($status = $this->createNewDirectory($data) ) {
	                        if (empty($resInfo['valid'])) $checkDirFromId = $this->db->lastInsertId;
	                        $resInfo['valid']++;
		                } else {
		                    $resInfo['invalid']++;
		                }
		            }
		        } else {
		            $resInfo['invalid']++;
		        }
		    }
		    $this->set('checkDirFromId', $checkDirFromId);
		    $this->set('resInfo', $resInfo);
		    $this->set('post', $data);
		    $this->pluginRender('showdirimportresult');
		    exit;
		}
		$this->set('errMsg', $errMsg);
		$this->showImportDirectories($data);
	}
	
	/*
	 * function to create new directory
	 */
	function createNewDirectory($data, $checkStatus=true) {
	    $status = false;	    
        $directory = trim($data['submit_url']);
        preg_match('/(.*)\//', $directory, $matches);
	    if (!empty($matches[1])) {
	        $domain = addHttpToUrl($matches[1]);
	        $directory = addHttpToUrl($directory);	        
	        $sql = "INSERT INTO directories(domain,submit_url,working,lang_code,script_type_id, ".implode(',', $this->dirCols).") 
	        values('".addslashes($domain)."', '".addslashes($directory)."', 0, '{$data['lang_code']}', {$data['script_id']}";
	        $scriptInfo = $this->getDirectoryScriptInfo($data['script_id']);
	        $data['extra_val'] = str_replace('[--type--]', $scriptInfo[$data['link_type']], $data['extra_val']);
	        foreach ($this->dirCols as $col) {
	            $sql .= ",'{$data[$col]}'";
	        }
	        $sql .= ")";
	        $this->db->query($sql);
	        $status = true;
	    }
	    return $status; 
	}
	
	/*
	 * function to check the status of imported directory
	 */
	function checkImportedDirectoryStatus($data) {
	    
    	$where = " id >= ".$data['checkdirid'];	
    	$dirList = $this->getDirectoryList($where, 'id', 'ASC', DI_CHECK_DIR_COUNT);
    	
    	if (!empty($dirList)) {
    	    $dirCtrler = New DirectoryController();        	
    	    $dirCtrler->checkPR = empty($data['checkpr']) ? 0 : 1; 
        	foreach($dirList as $dirInfo){
        		$dirCtrler->checkDirectoryStatus($dirInfo['id']);
        	}
        	$checkDirFromId = $data['checkdirid'] + DI_CHECK_DIR_COUNT;
        	$dirInfo = $this->dirCtrler->__getDirectoryInfo($checkDirFromId);
        	if (!empty($dirInfo['id'])) { 
            	echo "<b>".$this->pluginText['waitingcheckstatus']."..</b>";
            	?>
            	<script>
    				setTimeout('scriptDoLoad(\'<?=PLUGIN_SCRIPT_URL?>&action=checkimportdir&checkdirid=<?=$checkDirFromId?>\', \'checkdirstat\')', <?=Di_CHECK_STATUS_DELAY?>);
    			</script>
            	<?php
    	    }
    	}
    	showSuccessMsg($this->pluginText["alldirstatuschecked"]);	    
	}
	
	/*
	 * function to show import directory
	 */
	function showEditDirectory($dirId, $data='') {        
	     
	    $data = isset($data['id']) ? $data : $this->dirCtrler->__getDirectoryInfo($dirId);
	    $dirScriptList = $this->getAllDirectoryScripts();
	    $this->set('dirScriptList', $dirScriptList);
	    
	    $dirScriptInfo = array();
	    foreach ($dirScriptList as $info) {
	        if ($info['id'] == $data['script_type_id']) {	            
	            $dirScriptInfo = $info;
	            break;
	        }
	    }
	    $this->set('dirScriptInfo', $dirScriptInfo);
	    $this->set('helperObj', $this);	    

	    $linkTypes = array();
	    foreach($this->linkTypes as $type){
	        $linkTypes[$dirScriptInfo[$type]] = ucfirst($type);
	        if (strstr($data['extra_val'], $dirScriptInfo['link_type_col'].'='.$dirScriptInfo[$type])) {
	            $data['link_type'] = $dirScriptInfo[$type];
	        }
	    }
	    $this->set('linkTypes', $linkTypes);
	    
		$langController = New LanguageController();
		$this->set('langList', $langController->__getAllLanguages());
	    
	    $this->set('post', $data);
	    $this->pluginRender('showeditdirectory');
	}
	
	/*
	 * function to update directory
	 */
	function updateDirectory($data) {
	    
	    $errMsg['submit_url'] = formatErrorMsg($this->validate->checkBlank($data['submit_url']));
		if(!$this->validate->flagErr){		    
		    
	        $directory = trim($data['submit_url']);
            preg_match('/(.*)\//', $directory, $matches);
            if (!empty($matches[1])) {
                	            
	            $directory = addHttpToUrl($directory);
	            $dirInfo = $this->dirCtrler->__getDirectoryInfo($data['id']);
	            $exists = false;
	            if ($dirInfo['submit_url'] != $data['submit_url']) {
	                if ($this->__checkName($directory)) {
	                    $exists = true;
	                }
	            }
	            
	            if (!$exists) {	                
	                $scriptInfo = $this->getDirectoryScriptInfo($data['script_type_id']);
    	            $domain = addHttpToUrl($matches[1]);
    	            $sql = "update directories set 
    	            domain = '".addslashes($domain)."',
    	            submit_url = '".addslashes($directory)."',
    	            lang_code = '{$data['lang_code']}',
    	            script_type_id = {$data['script_type_id']}";
    	            $replace = $scriptInfo['link_type_col'].'='.$data['link_type'].'&';
    	            $data['extra_val'] = preg_replace('/'.$scriptInfo['link_type_col'].'=.*?&/', $replace, $data['extra_val']);
        	        foreach ($this->dirCols as $col) {
        	            $sql .= ",$col = '{$data[$col]}'";
        	        }
        	        $sql .= " where id=".$data['id'];
        	        $this->db->query($sql);
        	        $this->showDirectoryManager();
        	        exit;
	            } else {
                    $errMsg['submit_url'] = formatErrorMsg($this->pluginText["Directory already exists"]);	                
	            }
            } else {
                $errMsg['submit_url'] = formatErrorMsg($this->pluginText["Invalid url"]);
            }
		    
	    }
		$this->set('errMsg', $errMsg);
		$this->showEditDirectory($data['id'], $data); 
	}
	
	/*
	 * function to delete directory
	 */
	function deleteDirectory($dirId) {
	    if (!empty($dirId)) {
	        
	        $sql = "delete from directories where id=$dirId";
	        $this->db->query($sql);
	        
	        $sql = "delete from dirsubmitinfo where directory_id=$dirId";
	        $this->db->query($sql);
	    }
	}
	
	
	/*
	 * Function to get directory list
	 */
	function getDirectoryList($where='', $orderBy='id', $orderType='ASC', $limit=0, $start=0) {
	    
	    $where = stristr($where, 'where') ? $where : " where $where";
	    $sql = "SELECT * FROM directories $where order by $orderBy $orderType";
	    $sql .= $limit ? " limit $start, $limit" : "";
	    $dirList = $this->db->select($sql);		
		return $dirList; 
	}
		
	/*
	 * func to show directory status checker
	 */ 
	function showCheckDirectoryStatus($info=''){	    
		
		$statusList = array(
		    "-- ".$_SESSION['text']['common']['Select']." --" => '',
			$_SESSION['text']['common']['Active'] => 1,
			$_SESSION['text']['common']['Inactive'] => 0,
		);
		$this->set('statusList', $statusList);
		
		$captchaList = array(
			$_SESSION['text']['common']['Yes'] => 'yes',
			$_SESSION['text']['common']['No'] => 'no',
		);
		$this->set('captchaList', $captchaList);
		
		$langCtrler = New LanguageController();
		$langList = $langCtrler->__getAllLanguages();
		$this->set('langList', $langList);
		
		$this->pluginRender('showcheckdirstatus');
	}
	
	/*
	 * function to check directory status
	 */
	function checkDirectoryStatus($info='') {
	    
	    $info['lang_code'] = empty($info['langcode']) ? $info['lang_code'] : $info['langcode'];
		$capcheck = isset($info['capcheck']) ? (($info['capcheck'] == 'yes') ? 1 : 0 ) : "";  
		$sql = "SELECT id FROM directories where 1=1";
		if(preg_match('/^\d+$/', $info['stscheck'])) $sql .= " and working=".$info['stscheck'];	
		if($info['capcheck'] != '') $sql .= " and is_captcha=$capcheck";
		if(!empty($info['lang_code'])) $sql .= " and lang_code='{$info['lang_code']}'";
		if(preg_match('/^\d+$/', $info['check_status'])) {
		    $sql .= " and checked={$info['check_status']}";
		}
		if (!empty($info['start'])) $sql .= " and id>".$info['start'];
		$sql .= " order by id limit 0 , ". DI_CHECK_DIR_COUNT;		
		$dirList = $this->db->select($sql);
		$checkPR = empty($info['checkpr']) ? 0 : 1;
    	
    	if (!empty($dirList)) {
    	    $dirCtrler = New DirectoryController();        	
    	    $dirCtrler->checkPR = $checkPR; 
        	foreach($dirList as $dirInfo){
        		$dirCtrler->checkDirectoryStatus($dirInfo['id']);
                $start = $dirInfo['id'];
        	}       	
        	
        	if (count($dirList) >= DI_CHECK_DIR_COUNT) {
        		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=checkdirstatus&stscheck={$info['stscheck']}&capcheck={$info['capcheck']}&langcode={$info['lang_code']}";
        		$pgScriptPath .= "&checkpr=$checkPR&check_status=".$info['check_status']."&start=".$start;
        		echo "<b>".$this->pluginText['waitingcheckstatus']."..</b>";
            	?>
            	<script>
    				setTimeout('scriptDoLoad(\'<?=$pgScriptPath?>\', \'checkdirstat\')', <?=Di_CHECK_STATUS_DELAY?>);
    			</script>
            	<?php
            	exit;
        	}
    		
    	}
    	showSuccessMsg($this->pluginText["alldirstatuschecked"]);		
	}
	
	/*
	 * function to check directory status using cron
	 */
	function checkCronDirectoryStatus($info='') {
	    
	    $where = "";
	    switch (DI_CHECK_STATUS_DIR_TYPE_CRON) {
	        	        
	        case "active":
	            $where .= " working=1";
            break;
	        
	        case "inactive":
	            $where .= " working=0";
            break;
	        
	        case "notchecked":
	            $where .= " checked=0";
            break;

            default:
                $where .= " 1=1";
	    }	    	
		$dirList = $this->getDirectoryList($where);
    	
    	if (!empty($dirList)) {
    	    $dirCtrler = New DirectoryController();        	
    	    $dirCtrler->checkPR = DI_CHECK_PR_CRON; 
        	foreach($dirList as $i => $dirInfo){
        	    if (DI_CHECK_PR_CRON) {
            	    $remainder = $i % DI_CHECK_DIR_COUNT;
            	    if (!$remainder && $i) {
            	        echo "\n\n==".$this->pluginText['waitingcheckstatus']."==\n\n";
            	        sleep(Di_CHECK_STATUS_DELAY / 1000);
            	    }
        	    }
        		$dirCtrler->checkDirectoryStatus($dirInfo['id']);
        	}    		
    	}
    	echo "\n\n====".$this->pluginText["alldirstatuschecked"]."====\n\n";		
	}
	
	/*
	 * function to check directory submission status using cron
	 */
	function checkCronDirectorySubmissionStatus($info='') {
	    
	    $websiteCtrler = New WebsiteController();
		$userCtrler = New UserController();
		include_once(SP_CTRLPATH."/directory.ctrl.php");
		$dirCtrler = New DirectoryController();
		
		$userList = $userCtrler->__getAllUsers();		
		$allWebsiteList = array();
		foreach($userList as $userInfo){			
			$websiteList = $websiteCtrler->__getAllWebsites($userInfo['id']);			
			foreach($websiteList as $websiteInfo){
				$allWebsiteList[] = $websiteInfo;				
			}
		}
		
		foreach ($allWebsiteList as $websiteInfo) {
		    $sql = "select id 
    			from dirsubmitinfo ds 
    			where website_id={$websiteInfo['id']} and active=0   
    			order by id ASC";
		    $subList = $this->db->select($sql);
		    foreach ($subList as $i => $subInfo) {
		        $remainder = $i % DI_CHECK_DIR_COUNT;
        	    if (!$remainder && $i) {
        	        echo "\n\n==".$this->pluginText['waitingcheckstatusnextsub']."==\n\n";
        	        sleep(Di_CHECK_STATUS_DELAY / 1000);
        	    }		        
        	    
			    $status = $dirCtrler->checkSubmissionStatus($subInfo);
			    $dirCtrler->updateSubmissionStatus($subInfo['id'], $status);
			    print "\nChecked Submission Id: {$subInfo['id']} Status:$status";
		    }
		}
		echo "\n\n====All directory submission status checked successfully====\n\n";	    
	}
	

	/*
	 * function to get all directory script types
	 */
	function getAllDirectoryScripts() {
	    
	    $sql = "SELECT * FROM di_directory_meta where status=1";
	    $dirScriptList = $this->db->select($sql); 
	    return $dirScriptList;
	}
	
	/*
	 * function to get directory script type info
	 */
	function getDirectoryScriptInfo($id) {
	    
	    $sql = "SELECT * FROM di_directory_meta where id=$id";
	    $scriptInfo = $this->db->select($sql, true); 
	    return $scriptInfo;
	}

	/*
	 * function to check name directory existing or not
	 */
	function __checkName($directory){
		
	    $directory = addHttpToUrl(trim($directory));
		$sql = "select id from directories where submit_url='".addslashes($directory)."'";
		$listInfo = $this->db->select($sql, true);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	
}