<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class Site extends Customizer{
    
    function __construct() {
        if (!isAdmin()) {
            showErrorMsg($_SESSION['text']['label']['Access denied']);
        }
        
        parent::__construct();
    }

	function showSiteDetails($data) {		
		$this->pluginRender('showsitedetails');
	}

	function enterSiteDetails($data) {	

		$result = array();
		$sql = "select * from cust_site_details;";
		$query = $this->db->query($sql);
		while( $row = $query->fetch_assoc() ) {
			$result[$row['col_name']] = $row['col_value'];
		}

		$this->set('post', $result);
		$this->pluginRender('entersitedetails');
	}

	function insertSiteDetails($data){
		
		if (isAdmin()) {
			$userId = empty($listInfo['user_id']) ? isLoggedIn() : intval($listInfo['user_id']);	
		} else {
			$userId = isLoggedIn();
		}
	
		$this->set('post', $data);
		if(!$this->validate->flagErr){
			foreach($data as $key => $value){
				if($key != 'pid' || $key != 'action'){
					$sql = "update cust_site_details set col_value ='".addslashes($value)."' where col_name='".addslashes($key)."';";
					$action1 = $this->db->query($sql);
				}
			}
			if(!isset($data['disable_news'])){
				$sql = "update cust_site_details set col_value ='0' where col_name='disable_news';";
				$action2 =$this->db->query($sql);
			}else{
				$sql = "update cust_site_details set col_value ='1' where col_name='disable_news';";
				$action3 =$this->db->query($sql);
			}

			if(!isset($data['custom_menu'])){
				$sql = "update cust_site_details set col_value ='0' where col_name='custom_menu';";
				$action4 = $this->db->query($sql);
			}else{
				$sql = "update cust_site_details set col_value ='1' where col_name='custom_menu';";
				$action5 = $this->db->query($sql);
			}

			if($action1 || $action2 || $action3 || $action4 || $action5){
				$this->set('updation', 'success');
			}
			$this->enterSiteDetails($data);
			exit;
		}
		$this->set('errMsg', $errMsg);
		$this->enterSiteDetails($errMsg);

	}
	
}