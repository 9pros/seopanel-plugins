<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Menu extends Customizer{
	
	var $navBarBg;
	var $navBarColor;
    
    function __construct() {
        if (!isAdmin()) {
            showErrorMsg($_SESSION['text']['label']['Access denied']);
        }
        
        parent::__construct();
        
        $this->navBarBg = array(
        	'bg-primary', 'bg-success', 'bg-info', 
        	'bg-warning', 'bg-danger', 'bg-secondary', 
        	'bg-dark', 'bg-light',
        );
        
        $this->navBarColor = array('navbar-dark' => 'Light', 'navbar-light' => 'Dark');       
        
    }

	function menuDetails($data){		
		$sql = "select * from cust_menu ";
		$this->db->query($sql, true);
		$menuManager_data = $this->db->select($sql); 

		$this->set('menuManager_data', $menuManager_data);
        $this->set('navBarColor', $this->navBarColor);
		$this->pluginRender('menumanage');
	}
	
	function editMenu($data){
		
		$menuId = $data['menu_id'];
		$result = array();
		
		$sql = "select * from cust_menu where id='".addslashes($menuId)."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();

		if(!empty($query_data)){
			$result = $query_data;
		}

		$this->set('menu_data', $result);        
        $this->set('navBarBg', $this->navBarBg);
        $this->set('navBarColor', $this->navBarColor);
		$this->pluginRender('editmenu');

	}
	
	function updateMenu($data){

		$menuId = $data['menu_id'];
		$bgColor = '';
		$fontColor = '';

		if(isset($data['bg_color'])){
			$bgColor = $data['bg_color'];
		}
		
		if(isset($data['font_color'])){
			$fontColor = $data['font_color'];
		}

		$sql = "update cust_menu set bg_color='".addslashes($bgColor)."',font_color='".addslashes($fontColor)."'where id='".addslashes($menuId)."' ";
		$this->db->query($sql);
		$this->menuDetails($data);
		exit;

	}

	function menuItemDetails($data){

		$sql = "select * from cust_menu_items ";
		
		if(!empty($data['menu_id'])){
			$sql .= "where menu_id='".addslashes($data['menu_id'])."'";
		}

		$menuitemData = $this->db->select($sql);
		$this->set('menu_item_data', $menuitemData);

		$menusql = "select * from cust_menu ";
		$this->db->query($menusql, true);
		$menuList = $this->db->select($menusql);
		$menuData = [];
		foreach ($menuList as $menuInfo) $menuData[$menuInfo['id']] = $menuInfo; 		

		$this->set('menu_data', $menuData);
		$this->set('menuId', $data['menu_id']);
		$this->pluginRender('menuitemmanage');
	}

	function createMenuItem($data){

		$menusql = "select * from cust_menu ";
		$this->db->query($menusql, true);
		$menuData = $this->db->select($menusql);

		$this->set('menu_data', $menuData);
		$this->set('data',$data);
		$this->pluginRender('createmenuitem');
	}

	function insertMenuItem($data) {

		$errMsg = $this->validateMenuItem($data);
		
		if (!$this->validate->flagErr) {
			$menuId = $data['menu_id'];
			$name = $data['name'];
			$url = $data['url'];
			$floatType = $data['float_type'];
			$priority = $data['priority'];
			$windowTarget = $data['window_target'];
			
			$sql = "insert into cust_menu_items(menu_id,float_type,name,url,window_target,priority)
			values('".addslashes($menuId)."','".addslashes($floatType)."','".addslashes($name)."','".addslashes($url)."','".
			addslashes($windowTarget)."','".addslashes($priority)."');";

			$this->db->query($sql);
			$this->menuItemDetails($data);
			exit;
		}
		$this->set('errMsg', $errMsg);
		$this->createMenuItem($data);

	}

	function deleteMenuItem($data){
		$menuItemId = $data['menu_item_id'];		
		$sql = "delete from cust_menu_items where id='".addslashes($menuItemId)."';";
		$query = $this->db->query($sql);
		$this->menuItemDetails($data);
	}

	function editMenuItem($data){
		
		$menuItemId = $data['menu_item_id'];

		$result = array();
		$sql = "select * from cust_menu_items where id='".addslashes($menuItemId)."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();

		if(!empty($query_data)){
			$result = $query_data;
		}

		$menusql = "select * from cust_menu ";
		$this->db->query($menusql, true);
		$menuData = $this->db->select($menusql);

		$this->set('menu_data', $menuData);
		$this->set('menu_item_data', $result);
		$this->set('data',$data);
		$this->pluginRender('editmenuitem');
	}

	function updateMenuItem($data){
		
		$errMsg = $this->validateMenuItem($data);
		
		if (!$this->validate->flagErr) {
			$menuItemId = $data['menu_item_id'];
			$menuId = $data['menu_id'];
			$name = $data['name'];
			$url = $data['url'];
			$floatType = $data['float_type'];
			$priority = $data['priority'];
			$windowTarget = $data['window_target'];
	
			$sql = "update cust_menu_items set menu_id='".addslashes($menuId)."',float_type='".addslashes($floatType)."',name='".addslashes($name)."',url='".addslashes($url)."',window_target='".addslashes($windowTarget)."',priority='".addslashes($priority)."' where id='".addslashes($menuItemId)."' ";
			$this->db->query($sql);
			$this->menuItemDetails($data);
			exit;
		} else {
			$this->set('errMsg', $errMsg);
			$this->editMenuItem($data);
		}

	}
	
	function menuTranslation($data,$insert = '',$update = '') {		
		
		// get all menu items
		$sql = "select i.id, concat(i.name, ' -- ', m.label) as item_name from cust_menu_items i, cust_menu m where m.id=i.menu_id order by m.id";
		$menuItemList = $this->db->select($sql);
		$menuItemId = !empty($data['menu_item_id']) ? intval($data['menu_item_id']) : $menuItemList[0]['id'];
		$this->set('menuItemList', $menuItemList);

		$translationData = array();
		$lang = array();
		$result = array();

		//$tsql = "select * from cust_menu_item_texts where menu_item_id='".addslashes($menuItemId)."';";
		$tsql = "select * from cust_menu_item_texts join languages on cust_menu_item_texts.lang_code=languages.lang_code where cust_menu_item_texts.menu_item_id='".addslashes($menuItemId)."';";

		$tquery = $this->db->query($tsql);
		while( $row = $tquery->fetch_assoc() ) {
			$translationData[] = $row;
		}

		if(empty($translationData)){
			$sql = "select * from languages;";
			$query = $this->db->query($sql);
			while( $row = $query->fetch_assoc() ) {
				$lang[$row['lang_name']] = $row['lang_code'];
			}
		}
		
		$sql = "select * from cust_menu_items where id='".intval($menuItemId)."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();

		if(!empty($query_data)){
			$result = $query_data;
		}

		if($insert == 1){
			$this->set('insertion', 'success');
		}

		if($update == 1){
			$this->set('updation', 'success');
		}

		$this->set('lang', $lang);
		$this->set('menu_item_data', $result);
		$this->set('data',$translationData);
		$this->pluginRender('menutranslation');
	}

	function updateTranslation($data){
		
		$insert = 0 ;
		$update = 0;
		if(!empty($data['translationdata'])){
			foreach($data['translationdata'] as $k => $val){
				
				// check if already exists
				$result = array();
				$sql = "select * from cust_menu_item_texts where menu_item_id='".addslashes($val['menu_item_id'])."' and lang_code='".addslashes($val['lang_code'])."';";
				$query = $this->db->query($sql);
				$query_data = $query->fetch_assoc();

				if(!empty($query_data)){
					$result = $query_data;
				}

				if(count($result) > 0){
					// data already exist
					$sql2 = "update cust_menu_item_texts set content='".addslashes($val['translation'])."' where menu_item_id='".addslashes($val['menu_item_id'])."' and lang_code='".addslashes($val['lang_code'])."' ";
					$update = $this->db->query($sql2);
				}else{
					// data doesn't exist
					$sql3 = "insert into cust_menu_item_texts(menu_item_id,content,lang_code)
					values('".addslashes($val['menu_item_id'])."','".addslashes($val['translation'])."','".addslashes($val['lang_code'])."');";
					$insert = $this->db->query($sql3);

				}
				
			}
		}
		$this->menuTranslation($data,$insert, $update);
	}

	function deactivateMenuItem($data){

		$menuItemId = $data['menu_item_id'];
		$status = 0;
		
		$sql = "update cust_menu_items set status='".addslashes($status)."' where id='".addslashes($menuItemId)."'";
		$this->db->query($sql);
		$this->menuItemDetails($data);

	}

	function activateMenuItem($data){

		$menuItemId = $data['menu_item_id'];
		$status = 1;
		
		$sql = "update cust_menu_items set status='".addslashes($status)."' where id='".addslashes($menuItemId)."'";
		$this->db->query($sql);
		$this->menuItemDetails($data);

	}

	function validateMenuItem($data) {
		$errMsg = [];

		$sql = "select * from cust_menu_items where name='".addslashes($data['name'])."'";
		$sql .= !empty($data['menu_item_id']) ? " and id!=".intval($data['menu_item_id']) : "";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();
		
		if(!empty($query_data)){
			$errMsg['name'] = formatErrorMsg('This name already exist! Please use another name.');
			$this->validate->flagErr = true;
		}else{
			$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($data['name']));
		}

		$errMsg['menu_id'] = formatErrorMsg($this->validate->checkBlank($data['menu_id']));
		$errMsg['url'] = formatErrorMsg($this->validate->checkBlank($data['url']));
		$errMsg['priority'] = formatErrorMsg($this->validate->checkBlank($data['priority']));
		return $errMsg;
	}	
	
}