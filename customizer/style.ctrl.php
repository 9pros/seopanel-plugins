<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Style extends Customizer{
    
    var $typeList;
    
    function __construct() {
        if (!isAdmin()) {
            showErrorMsg($_SESSION['text']['label']['Access denied']);
        }
        
        $this->typeList = ['css', 'js'];
        include_once(SP_CTRLPATH."/themes.ctrl.php");        
        parent::__construct();
    }

	function styleManage($data){
		
		$themeCtrler = new ThemesController();
		$themeData = $themeCtrler->__getAllThemes();
		
		$sql = "select *,cust_styles.id as id, themes.name as themename,cust_styles.name as name, cust_styles.status as status 
            from cust_styles join themes on cust_styles.theme_id=themes.id where 1=1";
		$sql .= !empty($data['theme_id']) ? " and cust_styles.theme_id=".intval($data['theme_id']) : "";
		$sql .= !empty($data['type']) ? " and cust_styles.type='".addslashes($data['type'])."'" : "";
		
		$this->db->query($sql, true);
		$styleManagerData = $this->db->select($sql);
		
		$this->set('typeList', $this->typeList);
		$this->set('data',$data);
		$this->set('theme_data', $themeData); 
		$this->set('styleManagerData', $styleManagerData);
		$this->pluginRender('stylemanage');
	}

	function createStyle($data) {
		$themeCtrler = new ThemesController();
		$themeData = $themeCtrler->__getAllThemes();
		$this->set('theme_data', $themeData);
		$this->set('data',$data);
		$this->set('typeList', $this->typeList);
		$this->pluginRender('createstyle');
	}

	function insertStyle($data){

		$errMsg = $this->validateMenuItem($data);
		
		if (!$this->validate->flagErr) {

			$themeId = $data['theme_id'];
			$name = $data['name'];
			$styleContent = $data['style_content'];
			$priority = $data['priority'];
			
			$sql = "insert into cust_styles(theme_id,name,style_content,priority,type)
			values('".addslashes($themeId)."','".addslashes($name)."','".addslashes($styleContent)."',
            '".addslashes($priority)."', '".addslashes($data['type'])."');";

			$this->db->query($sql);
			$this->styleManage($data);
			exit;
		}
		$this->set('errMsg', $errMsg);
		$this->createStyle($data);

	}

	function deleteStyle($data){

		$styleId = $data['style_id'];
		$sql = "delete from cust_styles where id='".addslashes($styleId)."';";
		$query = $this->db->query($sql);
		$this->styleManage($data);	

	}

	function activateStyle($data){
		
		$styleId = $data['style_id'];
		$status = 1;
		
		$sql = "update cust_styles set status='".addslashes($status)."' where id='".addslashes($styleId)."'";
		$this->db->query($sql);
		$this->styleManage($data);	

	}

	function deactivateStyle($data){
		$styleId = $data['style_id'];
		$status = 0;
		
		$sql = "update cust_styles set status='".addslashes($status)."' where id='".addslashes($styleId)."'";
		$this->db->query($sql);
		$this->styleManage($data);
		
	}

	function editStyle($data){
		
		$styleId = $data['style_id'];

		$result = array();
		$sql = "select * from cust_styles where id='".addslashes($styleId)."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();

		if(!empty($query_data)){
			$result = $query_data;
		}
		
		$themeCtrler = new ThemesController();
		$themeData = $themeCtrler->__getAllThemes();
		$this->set('theme_data', $themeData);
		$this->set('style_data', $result);
		$this->set('style_id',$styleId);
		$this->set('data',$data);
		$this->set('typeList', $this->typeList);
		$this->pluginRender('editstyle');
	}


	function updateStyle($data){

		$errMsg = $this->validateMenuItem($data,$data['style_id']);
		
		if (!$this->validate->flagErr) {

			$styleId = $data['style_id'];
			$themeId = $data['theme_id'];
			$name = $data['name'];
			$styleContent = $data['style_content'];
			$priority = $data['priority'];
	
			$sql = "update cust_styles set theme_id='".addslashes($themeId)."',name='".addslashes($name)."',
            style_content='".addslashes($styleContent)."',
            type='".addslashes($data['type'])."',
            priority='".addslashes($priority)."' where id='".addslashes($styleId)."' ";
			$this->db->query($sql);
			$this->styleManage($data);
			exit;
		} else {
			$this->set('errMsg', $errMsg);
			$this->editStyle($data);
		}

	}

	function validateMenuItem($data,$styleId=null) {
		
		$errMsg = [];

		$sql = "select * from cust_styles where name='".addslashes($data['name'])."';";
		$query = $this->db->query($sql);
		$query_data = $query->fetch_assoc();
		
		if(!empty($query_data) && $query_data['id'] != $styleId ){
			$errMsg['name'] = formatErrorMsg('This name already exist! Please use another name.');
			$this->validate->flagErr = true;
		}else{
			$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($data['name']));
		}

		$errMsg['type'] = formatErrorMsg($this->validate->checkBlank($data['type']));
		$errMsg['theme_id'] = formatErrorMsg($this->validate->checkBlank($data['theme_id']));
		$errMsg['style_content'] = formatErrorMsg($this->validate->checkBlank($data['style_content']));
		$errMsg['priority'] = formatErrorMsg($this->validate->checkBlank($data['priority']));
		return $errMsg;
	}	
	
}