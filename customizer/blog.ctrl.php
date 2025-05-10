<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Blog extends Customizer{
    
    function __construct() {
        if (!isAdmin()) {
            showErrorMsg($_SESSION['text']['label']['Access denied']);
        }
        
        parent::__construct();
    }

	function blogManage($data) {	

		$pgScriptPath = PLUGIN_SCRIPT_URL;
		$sql = "select * from cust_blogs where 1=1";
		$sql .= !empty($data['search']) ? " and (blog_title LIKE '%".addslashes($data['search'])."%' or blog_content LIKE '%".addslashes($data['search'])."%')" : "";
		$sql .= !empty($data['link_page']) ? " and link_page='".addslashes($data['link_page']) ."'" : "";
		
		if (!empty($data['status'])) {
		    $sql .= ($data['status'] == 'active') ? " and status=1" : " and status=0";
		}
		
		# pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, 'searching_form', 'scriptDoLoadPost', 'content', '');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " limit ".$this->paging->start .",". $this->paging->per_page;

		$blog_data = $this->db->select($sql); 
		$this->set('blog_data', $blog_data);
		$this->set('pageNo', $_GET['pageno']);
		$this->set('data',$data);				
		$this->pluginRender('blogmanage');

	}

	function addBlogForm($data){

		$result = array();
		$sql = "select * from languages;";
		$query = $this->db->query($sql);
		while( $row = $query->fetch_assoc() ) {
			$result[$row['lang_name']] = $row['lang_code'];
		}

		$this->set('lang', $result);
		$this->set('data', $data);
		$this->pluginRender('enterblogdetails');
	}

	function insertBlogDetails($data){
		
		if (isAdmin()) {
			$userId = empty($listInfo['user_id']) ? isLoggedIn() : intval($listInfo['user_id']);	
		} else {
			$userId = isLoggedIn();
		}

		$errMsg['blog_title'] = formatErrorMsg($this->validate->checkBlank($data['blog_title']));
		$errMsg['meta_title'] = formatErrorMsg($this->validate->checkBlank($data['meta_title']));
		$errMsg['meta_description'] = formatErrorMsg($this->validate->checkBlank($data['meta_description']));
		$errMsg['meta_keywords'] = formatErrorMsg($this->validate->checkBlank($data['meta_keywords']));
		$errMsg['tags'] = formatErrorMsg($this->validate->checkBlank($data['tags']));

		$blog_id = 0;
		$blog_title = $data['blog_title'];
		if($blog_title){
			if ($this->__checkBlogTitle($blog_title,$blog_id)) {
				$errMsg['blog_title'] = formatErrorMsg('Title already exist!');
				$this->validate->flagErr = true;
			}
		}

		$blog_title = $data['blog_title'];
		$blog_content = $data['blog_content'];
		$meta_title = $data['meta_title']; 
		$meta_description = $data['meta_description'];
		$meta_keywords = $data['meta_keywords'];
		$lang_code = $data['lang_code'];
		$tags = $data['tags'];
		$link_page = $data['link_page'];
		$status = 0;
		$created_time = date('Y-m-d H:i:s');
		$updated_time = date('Y-m-d H:i:s');
		
		if(!$this->validate->flagErr){
			$sql = "insert into cust_blogs(blog_title,blog_content,meta_title,meta_description,meta_keywords,lang_code,tags,link_page,status,created_time,updated_time)
			values('".addslashes($blog_title)."','".addslashes($blog_content)."','".addslashes($meta_title)."','".addslashes($meta_description)."','".
			addslashes($meta_keywords)."','".addslashes($lang_code)."','".addslashes($tags)."','".addslashes($link_page)."','".addslashes($status)."','".$created_time."', '".$updated_time."');";
			$this->db->query($sql);
			$this->blogManage($data);
			exit;
		}

		$this->set('errMsg', $errMsg);
		$this->addBlogForm($data);

	}

	function editBlogDetails($data){
		
		$blog_id = $data['blog_id'];
		
		$result = array();
		$sql = "select * from cust_blogs where id='".addslashes($blog_id)."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();

		if(!empty($query_data)){
			$result = $query_data;
		}

		$lang = array();
		$sql = "select * from languages;";
		$query = $this->db->query($sql);
		while( $row = $query->fetch_assoc() ) {
			$lang[$row['lang_name']] = $row['lang_code'];
		}

		$this->set('lang', $lang);
		$this->set('blog_data', $result);
		$this->set('data',$data);
		$this->pluginRender('editblogdetails');


	}

	function updateBlogDetails($data){
		
		if (isAdmin()) {
			$userId = empty($listInfo['user_id']) ? isLoggedIn() : intval($listInfo['user_id']);	
		} else {
			$userId = isLoggedIn();
		}

		$errMsg['blog_title'] = formatErrorMsg($this->validate->checkBlank($data['blog_title']));
		$errMsg['meta_title'] = formatErrorMsg($this->validate->checkBlank($data['meta_title']));
		$errMsg['meta_description'] = formatErrorMsg($this->validate->checkBlank($data['meta_description']));
		$errMsg['meta_keywords'] = formatErrorMsg($this->validate->checkBlank($data['meta_keywords']));
		$errMsg['tags'] = formatErrorMsg($this->validate->checkBlank($data['tags']));
		
		$blog_id = $data['blog_id'];
		$blog_title = $data['blog_title'];

		if($blog_title){
			if ($this->__checkBlogTitle($blog_title,$blog_id)) {
				$errMsg['blog_title'] = formatErrorMsg('Title already exist!');
				$this->validate->flagErr = true;
			}
		}

		$blog_content = $data['blog_content'];
		$meta_title = $data['meta_title']; 
		$meta_description = $data['meta_description'];
		$meta_keywords = $data['meta_keywords'];
		$lang_code = $data['lang_code'];
		$tags = $data['tags'];
		$link_page = $data['link_page'];
		//$status = 1;
		$updated_time = date('Y-m-d H:i:s');

		if(!$this->validate->flagErr){
			$sql = "update cust_blogs set blog_title='".addslashes($blog_title)."',blog_content='".addslashes($blog_content)."',meta_title='".addslashes($meta_title)."',meta_description='".addslashes($meta_description)."',meta_keywords='".addslashes($meta_keywords)."',lang_code='".addslashes($lang_code)."',tags='".addslashes($tags)."',link_page='".addslashes($link_page)."',updated_time='".addslashes($updated_time)."' where id='".addslashes($blog_id)."' ";
			$this->db->query($sql);
			$this->blogManage($data);
			exit;
		}

		$this->set('errMsg', $errMsg);
		$this->editBlogDetails($data);


	}

	function deleteBlog($data){

		$blog_id = $data['blog_id'];		
		$sql = "delete from cust_blogs where id='".addslashes($blog_id)."';";
		$query = $this->db->query($sql);
		$this->blogManage($data);

	}

	function deactivateBlog($data){

		$blog_id = $data['blog_id'];
		$status = 0;
		$sql = "update cust_blogs set status='".addslashes($status)."' where id='".addslashes($blog_id)."' ";
		$this->db->query($sql);
		$this->blogManage($data);

	}

	function activateBlog($data){

		$blog_id = $data['blog_id'];
		$status = 1;
		$sql = "update cust_blogs set status='".addslashes($status)."' where id='".addslashes($blog_id)."' ";
		$this->db->query($sql);
		$this->blogManage($data);

	}

	function __checkBlogTitle($title,$id){		
		if($id == 0){
			$sql = "select id from cust_blogs where blog_title='".addslashes($title)."'";
			$data = $this->db->select($sql, true);
			return empty($data['id']) ? false :  $data['id'];
		}else{ 
			$sql = "select blog_title from cust_blogs where id='".addslashes($id)."'";
			$data = $this->db->select($sql, true);
			if($data['blog_title'] == $title){ 
				return false;
			}else{
				$sql2 = "select id from cust_blogs where blog_title='".addslashes($title)."'";
				$data2 = $this->db->select($sql2, true);
				return empty($data2['id']) ? false :  $data2['id'];
			}
		}
		
		
	}
	
}