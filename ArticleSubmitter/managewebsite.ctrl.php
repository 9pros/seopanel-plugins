<?php
/**
 * Description of ManageWebsite
 *
 * @author Raheela Muneer.      
 */
class ManageWebsite extends ArticleSubmitter {

    var $tableName = "as_websites";
    var $scriptType;
    
    function __construct() {
        parent::__construct();
        $this->scriptType = [
            'wordpress' => 'Wordpress',
            'seopanel' => 'Seopanel',
            /*'phpld' => 'phpLD',
            'blogspot' => 'BlogSpot',*/
        ];
    }

    function  showWebsites($info = '') {
        $userId = isLoggedIn();
        $this->set ('post', $info );
        $cond =  !empty( $info ['type'] ) ? " and type='" . addslashes ( $info ['type'] ) . "'" : "";
        $cond .= !empty( $info ['keyword'] ) ? " and website_name LIKE '%" . addslashes ( $info ['keyword'] ) . "%'" : "";
        $cond .= (isset($info['public']) && $info['public'] >= 0) ? " and public=" . intval( $info ['public'] ) : "";
        $cond .= (isset($info['status']) && $info['status'] >= 0) ? " and status=" . intval( $info ['status'] ) : "";
        
        if (isAdmin()) {
            $cond .= !empty( $info ['user_id'] ) ? " and user_id =" . intval ( $info ['user_id'] ) : "";
            $userCtrler = New UserController();
            $userList = $userCtrler->__getAllUsers();
            $this->set('userList', $userList);
        } else {
            $cond .= " and user_id = $userId";
        }
        
        $pgScriptPath = PLUGIN_SCRIPT_URL. "&action=website";
        $sql = "select p.* from $this->tableName p where 1=1 $cond";
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'searchForm', 'scriptDoLoadPost', 'content', '' );
        $this->set('pagingDiv', $pagingDiv);

        $sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
        $websiteList = $this->db->select($sql);
        $this->set('list', $websiteList);
        $this->set('pageNo', $info['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->set('scriptType', $this->scriptType);
        $this->pluginRender('ManageWebsite');
    }
    
    function __changeStatus($websiteId, $status){
        $websiteId = intval($websiteId);
        $status = intval($status);
        $sql = "update $this->tableName set status=$status where id=$websiteId";
        $this->db->query($sql);
    }
    
    function __getwebsiteInfo($websiteId){
        $sql = "SELECT * FROM $this->tableName WHERE `id` = " . intval($websiteId);
        $info = $this->db->select($sql, true);
        return $info;
    }
    
    function __getAllWebsiteList($cond = ""){
        $sql = "select * from $this->tableName where 1=1 $cond";
        $websiteList = $this->db->select($sql);
        return $websiteList;
    }
    
    function __checkWebsiteName($name, $userId, $websiteId = false){
        $sql = "select * from $this->tableName where website_name='".addslashes($name)."' and user_id=" . intval($userId);
        $sql .= !empty($websiteId) ? " and id!=".intval($websiteId) : "";
        $data = $this->db->select($sql, true);
        return empty($data['id']) ? false :  $data['id'];
    }

    function editwebsite($websiteId, $listInfo='') {
        
        if(!empty($websiteId)) {
            if(empty($listInfo)) {
                $listInfo = $this->__getwebsiteInfo($websiteId);
                $listInfo['password'] = base64_decode($listInfo['password']);
            }
            
            $this->set('post', $listInfo);
            $this->set('sec', 'update');
            $this->set('scriptType', $this->scriptType);
            $this->pluginRender('editwebsite');
        }
    }

    function updateWebsite($listInfo){        
        $userId = isLoggedIn();
        $errMsg = [];
        $errMsg['type'] = formatErrorMsg($this->validate->checkBlank($listInfo['type']));
        $errMsg['website_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_name']));
        $errMsg['website_url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['website_url'])));
        
        if (!empty($listInfo['authentication'])) {
            $errMsg['username'] = formatErrorMsg($this->validate->checkBlank($listInfo['username']));
            $errMsg['password'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['password'])));
        }
        
        if(!$this->validate->flagErr){
            if($this->__checkWebsiteName($listInfo['website_name'], $userId, $listInfo['id'])){
                $errMsg['website_name'] = formatErrorMsg('Article Directory already exists.');
            } else {
                $publicVal = isAdmin() ? intval($listInfo['public']) : 0;
                $listInfo['website_url'] = addHttpToUrl($listInfo['website_url']);
                $enc_password = base64_encode($listInfo['password']);
                $sql = "UPDATE  $this->tableName SET
                        `type`='". addslashes($listInfo['type'])."',
                        `website_name`='". addslashes($listInfo['website_name'])."',
                        `website_url`='". addslashes($listInfo['website_url'])."',
                        `username`='". addslashes($listInfo['username'])."',
                        `password`='$enc_password',
                        `authentication` =".intval($listInfo['authentication']).",
                        `public` = $publicVal  
                WHERE id = " . intval($listInfo['id']);
                $this->db->query($sql);
                $this->showWebsites($listInfo);
                return true;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->editwebsite($listInfo['id'], $listInfo);
    }

    function newwebsiteList($info=''){         
        $this->set('sec', 'create');
        $this->set('post', $info);
        $this->set('scriptType', $this->scriptType);
        $this->pluginRender('editwebsite');
    }

    function createwebsiteList($listInfo){                                  
        $userId = isLoggedIn();
        $errMsg['type'] = formatErrorMsg($this->validate->checkBlank($listInfo['type']));
        $errMsg['website_name'] = formatErrorMsg($this->validate->checkBlank($listInfo['website_name']));
        $errMsg['website_url'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['website_url'])));
        
        if (!empty($listInfo['authentication'])) {
            $errMsg['username'] = formatErrorMsg($this->validate->checkBlank($listInfo['username']));
            $errMsg['password'] = formatErrorMsg($this->validate->checkBlank(formatUrl($listInfo['password'])));
        }
        
        if(!$this->validate->flagErr){
            if($this->__checkWebsiteName($listInfo['website_name'], $userId)){
                $errMsg['website_name'] = formatErrorMsg('Article Directory already exists.');
            } else {
	            $enc_password = base64_encode($listInfo['password']);
	            $listInfo['website_url'] = addHttpToUrl($listInfo['website_url']);
	            $sql = "INSERT INTO `as_websites`
	                ( `type`,`website_name`, `website_url`, `username`, `password`,`status`,`user_id`,`public`, `authentication`)
	                VALUES
	                ('".addslashes($listInfo['type'])."','".addslashes($listInfo['website_name'])."','".addslashes($listInfo['website_url'])."',
	                '".addslashes($listInfo['username'])."', '$enc_password', 1, $userId, ".intval($listInfo['public']).", ".intval($listInfo['authentication']).")";
	            $this->db->query($sql);
	            $this->showWebsites($listInfo);
	            return true;
            }
        }

        $this->set('errMsg', $errMsg); 
        $this->newwebsiteList( $listInfo);
    }

    function DeleteWebsite($websiteid){
        $sql = "delete from $this->tableName where id=" . intval($websiteid);
        $this->db->query($sql);
    }
    
    function importWebsite($data) {
    	$this->pluginRender("showimportwebsite");
    }
    
    function doimportWebsite($data = '') {
    	$errMsg ['websites'] = formatErrorMsg ( $this->validate->checkBlank ( $data ['websites'] ) );
    	if (! $this->validate->flagErr) {
    		$resInfo ['invalid'] = $resInfo ['existing'] = $resInfo ['valid'] = 0;
    		$checkDirFromId = 0;
    		$webList = explode ( ",", $data ['websites'] );
    		foreach ( $webList as $webDetails ) {
    			if (preg_match ( '/\w/', $webDetails )) {
    				$website_url = explode ( " ", $webDetails );
    				if ($this->__checkName ( $website_url ['0'] )) {
    					$resInfo ['existing'] ++;
    				} else {
    					if ($status = $this->createNewWebsite ( $website_url, $data )) {
    						if (empty ( $resInfo ['valid'] ))
    							$checkDirFromId = $this->db->lastInsertId;
    							$resInfo ['valid'] ++;
    					} else {
    						$resInfo ['invalid'] ++;
    					}
    				}
    			} else {
    				$resInfo ['invalid'] ++;
    			}
    		}
    		$this->set ( 'checkDirFromId', $checkDirFromId );
    		$this->set ( 'resInfo', $resInfo );
    		$this->set ( 'post', $data );
    		$this->pluginRender ( 'showdirimportresult' );
    		exit ();
    	}
    	
    	$this->set ( 'errMsg', $errMsg );
    	$this->importWebsite ( $data );
    }

    function checkwebsitestatus($info =''){ 
        $websiteId = intval($info);
        $websiteInfo = $this->__getwebsiteInfo($websiteId);
        $spider = new Spider();
        $spider->_CURLOPT_HEADER = 1;
        if($websiteInfo['authentication'] == '1'){ 
            $cookieFile = SP_TMPPATH . "/" . AS_COOKIE_FILE;
            system("> $cookieFile");
            $url = $websiteInfo['domain']."/login.php";
            $username = $websiteInfo['username'];
            $password = $websiteInfo['password'];
            $password = base64_decode($password);
            $postdata = "user=".$username."&pass=".$password. "&formSubmitted=" . "1&login=Login" ;

            // function to login to site and stores cookie in cookie file
            $submitterCtrler =  new ManageSubmitter();
            $curlReturnValue = $submitterCtrler->curlToFetch($url,$postdata,$cookieFile);
            if($curlReturnValue == '1'){
                $page = $submitterCtrler->getPageData($cookieFile,$websiteInfo['website_url']);
                if((stristr($page, 'Title:')) && (stristr($page,'Short Description:'))&& (stristr($page,'Article:'))&& (stristr($page,'Article:'))){
                    return 1;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else{
            $page = $spider->getContent($websiteInfo['website_url']);
            if((stristr($page, 'Title:')) && (stristr($page,'Short Description:'))&& (stristr($page,'Article:'))&& (stristr($page,'Article:'))){
                return 1;
            }else{
                return 0;
            }
        }
    }

    function updateStatus($id,$status){
        $id = intval($id);
        $status = intval($status);
        $sql = "UPDATE $this->tableName SET `status`= $status WHERE `id` = $id ";
        $this->db->query($sql);
    }

    function _getWebsiteForCron(){
        $sql = "select * from $this->tableName";
        $sql .= " order by `website_name`";
        $websiteList = $this->db->select($sql);
        return $websiteList;
    }
    
    function startwebsiteStatusJOb($info){
        $websiteInfo = $this->_getWebsiteForCron();
        if(!empty($websiteInfo)){ 
            foreach ($websiteInfo as $website){                   
                if(!empty($website['id'])){
                    $websiteId = intval($website['id']);
                    $returnValue = $this->checkwebsitestatus($websiteId);                             
                    $this->updateStatus($websiteId, $returnValue);
                }
            }
        } 
    }

}
?>