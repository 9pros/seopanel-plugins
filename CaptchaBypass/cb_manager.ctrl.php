<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class CB_Manager extends CaptchaBypass{
	
	var $serviceColList;
	
    
    function __construct() {
    	
    	$this->serviceColList = [
    		"anti-captcha" => ['api_key'],
    		"deathbycaptcha" => ['username', 'password'],
    		"2captcha" => ['api_key'],
    		"rucaptcha" => ['api_key'],
			"captchas.io" => ['api_key']
    	];    	
        
        parent::__construct();
    }

	function showCBServiceList($data = "") {
		$serviceList = $this->dbHelper->getAllRows("cb_services");
		$this->set('serviceList', $serviceList);
		$this->pluginRender('service_manager');
	}
	
	function getRandomActiveCBService() {
		$whereCond = "status=1 ORDER BY RAND() LIMIT 0,1";
		$serviceInfo = $this->dbHelper->getRow("cb_services", $whereCond);
		return $serviceInfo;
	}
	
	function solveCaptchaImage($imgPath) {
		$captchaTxt = "";
		
		if (empty($imgPath)) return $captchaTxt;
		
		// get random bypass service
		$serviceInfo = $this->getRandomActiveCBService();
		if (!empty($serviceInfo['id'])) {
			
		    try {
    			switch($serviceInfo['identifier']) {
    				
    				case "deathbycaptcha":
    					$captchaTxt = $this->solveCaptchaByDeathByCaptcha($serviceInfo, $imgPath);
    					break;
    					
    				case "anti-captcha":
    					$captchaTxt = $this->solveCaptchaByAntiCaptcha($serviceInfo, $imgPath);
    					break;
    					
    				case "2captcha":
    				case "rucaptcha":
    					$captchaTxt = $this->solveCaptchaBy2captchaOrRucaptcha($serviceInfo, $imgPath);
    					break;
    					
    				case "captchas.io":
    					$captchaTxt = $this->solveCaptchaByCAPTCHASIO($serviceInfo, $imgPath);
    					break;						
    				default:
    					break;
    				
    			}
    			
		    } catch (Exception $e) {
		        return "Error: Captcha decoding failed - " . $e->getMessage();
		    } 
			
		}
		
		return $captchaTxt;
	}	
	
	function solveCaptchaByDeathByCaptcha($serviceInfo, $imgPath) {		
		$captchaTxt = "";
		
		if (!empty($serviceInfo['username']) && !empty($serviceInfo['password'])) {
		
			include_once(PLUGIN_PATH . "/dbc_api_php/deathbycaptcha.php");				
			
			// Put your DBC username & password here.
			// Use DeathByCaptcha_HttpClient() class if you want to use HTTP API.
			$client = new DeathByCaptcha_SocketClient($serviceInfo['username'], $serviceInfo['password']);
			
		    // Put your CAPTCHA image file name, file resource, or vector of bytes,
		    // and optional solving timeout (in seconds) here; you'll get CAPTCHA
		    if ($captcha = $client->decode($imgPath, DeathByCaptcha_Client::DEFAULT_TIMEOUT)) {
		    	$captchaTxt = $captcha['text'];
		    }
		}
	    
	    return $captchaTxt;
		
	}
	
	function solveCaptchaByAntiCaptcha($serviceInfo, $imgPath) {
		include_once(PLUGIN_PATH . "/php-anticaptcha/helper.php");
		$captchaTxt = solveCaptcha($serviceInfo['api_key'], $imgPath);
		return $captchaTxt;
	}
	
	function solveCaptchaBy2captchaOrRucaptcha($serviceInfo, $imgPath) {
		$captchaTxt = "";
		
		if (!empty($serviceInfo['api_key'])) {
			include_once(PLUGIN_PATH . "/2captcha/2captcha.php");
			$domainName = ($serviceInfo['identifier'] == 'rucaptcha') ? "rucaptcha.com" : "2captcha.com";
			$captchaTxt = recognize2captcha($imgPath, $serviceInfo['api_key'], false, $domainName);
		}
		
		return $captchaTxt;
	}
	
	function solveCaptchaByCAPTCHASIO($serviceInfo, $imgPath) {
		$captchaTxt = "";
		
		if (!empty($serviceInfo['api_key'])) {
			include_once(PLUGIN_PATH . "/captchas.io/captchas-io.php");
			$domainName = ($serviceInfo['identifier'] == 'captchas.io') ? "api.captchas.io" : "api.captchas.io";
			$captchaTxt = recognizeCAPTCHASIO($imgPath, $serviceInfo['api_key'], false, $domainName);
		}
		
		return $captchaTxt;
	}	
	
	function deleteService($serviceId) {
		$serviceId = intval($serviceId);
		$sql = "delete from cb_services where id=" . intval($serviceId);
		$this->db->query($sql);
		$this->showCBServiceList();
	}
	
	function __changeStatus($serviceId, $status){
		$serviceId = intval($serviceId);
		$status = intval($status);
		$sql = "update cb_services set status=$status where id=$serviceId";
		$this->db->query($sql);
	}

	function __getServiceInfo($serviceId) {	
		$whereCond = "id=".intval($serviceId);
		$info = $this->dbHelper->getRow("cb_services", $whereCond);
		return $info;		
	}

	function __checkName($name, $serviceId = false){		
		$whereCond = "name='".addslashes($name)."'";
		$whereCond .= !empty($serviceId) ? " and id!=".intval($serviceId) : "";
		$listInfo = $this->dbHelper->getRow("cb_services", $whereCond);
		return empty($listInfo['id']) ? false :  $listInfo['id'];
	}
	
	function newService($listInfo='') {
		$this->set('post', $listInfo);
		$this->set('actVal', "createService");
		$this->set('serviceColList', $this->serviceColList);
		$this->pluginRender('edit_service');
		
	}
	
	function editService($serviceId, $listInfo='') {
		$serviceId = intval($serviceId);
		
		if(!empty($serviceId)){
			
			if(empty($listInfo)){
				$listInfo = $this->__getServiceInfo($serviceId);
			}
			
			$this->set('post', $listInfo);
			$this->set('actVal', "updateService");
			$this->set('serviceColList', $this->serviceColList);
			$this->pluginRender('edit_service');
		}
		
	}
	
	function validateService($listInfo) {
		$errMsg = [];
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		
		switch($listInfo['identifier']) {
			
			case "deathbycaptcha":
				$errMsg['username'] = formatErrorMsg($this->validate->checkBlank($listInfo['username']));
				$errMsg['password'] = formatErrorMsg($this->validate->checkBlank($listInfo['password']));
				break;
				
			default:
				$errMsg['api_key'] = formatErrorMsg($this->validate->checkBlank($listInfo['api_key']));
				break;
				
		}
		
		if(!$this->validate->flagErr){
			if ($this->__checkName($listInfo['name'], $listInfo['id'])) {
				$errMsg['name'] = formatErrorMsg('Service already exist');
				$this->validate->flagErr = true;
			}
		}
		
		return $errMsg;
		
	}
	
	function createService($listInfo) {
		$this->set('post', $listInfo);
		$errMsg = $this->validateService($listInfo);
	
		if (!$this->validate->flagErr) {			
			$dataList = [
				'name' => $listInfo['name'],
				'identifier' => $listInfo['identifier'],
				'username' => $listInfo['username'],
				'password' => $listInfo['password'],
				'api_key' => $listInfo['api_key'],
			];			
			$this->dbHelper->insertRow("cb_services", $dataList);
			$this->showCBServiceList();
			exit;
		}
	
		$this->set('errMsg', $errMsg);
		$this->newService($listInfo);
	}
	
	function updateService($listInfo) {
		$this->set('post', $listInfo);
		$errMsg = $this->validateService($listInfo);
	
		if (!$this->validate->flagErr) {			
			$dataList = [
				'name' => $listInfo['name'],
				'identifier' => $listInfo['identifier'],
				'username' => $listInfo['username'],
				'password' => $listInfo['password'],
				'api_key' => $listInfo['api_key'],
			];			
			$this->dbHelper->updateRow("cb_services", $dataList, "id=".intval($listInfo['id']));
			$this->showCBServiceList();
			exit;
		}
	
		$this->set('errMsg', $errMsg);
		$this->editService($listInfo['id'], $listInfo);
	}
	
}