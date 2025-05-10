<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

// include subscription class
include_once 'Subscription.ctrl.php';

class PaymentGateway extends Subscription{

	/*
	 * show reports list to manage
	 */
	function showPaymentGatewayManager($info='') {
		
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=paymentGatewayManager";
		$sql = "select * from subscription_paymentgateway";
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
		
		$rList = $this->db->select($sql);		
		$this->set('list', $rList);
		$this->set('pageNo', $_GET['pageno']);

		$this->pluginRender('payment_gateway_manager');
	}

	/**
	 * function to get payment info
	 */
	function __getPaymentGatewayInfo($pgId, $withOptions = true, $assignOption = false) {
		$sql = "select * from subscription_paymentgateway where id=" . intval($pgId);
		$pgInfo = $this->db->select($sql, true);

		// if options needed
		if ($withOptions) {
			$pgInfo['option_list'] = $this->__getPaymentOptions($pgId);
			
			// if assign option enabled
			if ($assignOption) {
				
				// loop through the options and assign to vars
				foreach ($pgInfo['option_list'] as $optInfo) {
					$pgInfo[$optInfo['set_name']] = $optInfo['set_val'];
				}
				
			}
			
		}
		
		return $pgInfo;
		
	}
	
	/**
	 * function to get all payment plugins
	 */
	function __getAllPaymentGateway($where = " and status=1") {
		$sql = "select * from subscription_paymentgateway where 1=1 $where";
		$pgList = $this->db->select($sql);
		return $pgList;
	}
	
	/**
	 * function to get payment gateway options
	 */
	function __getPaymentOptions($pgId) {
		$sql = "select * from subscription_gatewayoption where gateway_id=" . intval($pgId);
		$pgOptionList = $this->db->select($sql);
		return $pgOptionList;
	}
	
	/**
	 * function to get default payment gateway
	 */
	function __getDefaultPaymentGateway() {
		$sql = "select set_val from subscription_settings where set_name='SP_PAYMENT_PLUGIN'";
		$pgInfo = $this->db->select($sql, true);
		return empty($pgInfo['set_val']) ? False : $pgInfo['set_val'];
	}
		
	/**
	 * func to edit payment gateway
	 */ 
	function editPaymentGateway($pgId, $listInfo=''){
		
		// if not empty payment gateway id
		if(! empty($pgId)){
						
			if (empty($listInfo)) {
				$listInfo = $this->__getPaymentGatewayInfo($pgId);
				$listInfo['oldName'] = $listInfo['name'];
			}
			
			$this->set('post', $listInfo);
			$this->pluginRender('editpaymentgateway');
		}		
	}
	
	/*
	 * func to update report
	 */
	function updatePaymentGateway($listInfo){
		
		$errMsg['name'] = formatErrorMsg($this->validate->checkBlank($listInfo['name']));
		$pgId = intval($listInfo['id']);
		$optionList = $this->__getPaymentOptions($pgId);
		
		// get options and loop through it and validate
		foreach ($optionList as $optInfo) {
			
			// hidden is set just continue
			if ($optInfo['display'] == 0) continue;
			
			$fieldName = $optInfo['set_name'];
			
			// if required field field or field with some value needs to be validated
			if ($optInfo['required'] || !empty($listInfo[$fieldName])) {
				
				$fieldValue = $listInfo[$fieldName];
				
				// switch through validation category
				switch($optInfo['validation']) {
					
					case "number":
						$errMsg[$fieldName] = formatErrorMsg($this->validate->checkNumber($fieldValue));
						break;
					
					case "email":
						$errMsg[$fieldName] = formatErrorMsg($this->validate->checkEmail($fieldValue));
						break;
					
					case "alpha":
						print $fieldName;
						$errMsg[$fieldName] = formatErrorMsg($this->validate->checkAlpha($fieldValue));
						break;
					
					case "blank":
					default:
						$errMsg[$fieldName] = formatErrorMsg($this->validate->checkBlank($fieldValue));
						break;
				}
				
			}
			
		}
		
		// if no errors
		if (!$this->validate->flagErr) {
			
			$sql = "update subscription_paymentgateway set name='" . addslashes($listInfo['name']) . "' where id=$pgId";
			$this->db->query($sql);

			// get options and loop through it			
			foreach ($optionList as $optInfo) {
					
				// hidden is set just continue
				if ($optInfo['display'] == 0) continue;

				$setVal = $listInfo[$optInfo['set_name']];
				$sql = "update subscription_gatewayoption set set_val='" . addslashes($setVal) . "'
				where gateway_id=$pgId and set_name='" . addslashes($optInfo['set_name']) . "'";
				$this->db->query($sql);
					
			}		
			
			$this->showPaymentGatewayManager($pgInfo);
			return true;
			
			
		}
		
		// assign values to sow in error page
		$listInfo['option_list'] = $optionList;
		foreach ($optionList as $i => $info) {
			$listInfo['option_list'][$i]['set_val'] = isset($listInfo[$info['set_name']]) ? $listInfo[$info['set_name']] : $listInfo['option_list'][$i]['set_val'];
		}
		
		$this->set('errMsg', $errMsg);
		$this->editPaymentGateway($pgId, $listInfo);
		
	}
	
	/*
	 * function to change report status
	 */
	function changePaymentGatewayStatus($pgId, $status) {
		$pgId = intval($pgId);
		$status = intval($status);
		$sql = "update subscription_paymentgateway set status='$status' where id=$pgId";
		$this->db->query($sql);
	}
	
	// function to create payment gateway instance
	function createPaymentGatewayInstance($pgInfo) {
		$fileName = strtolower($pgInfo['gateway_code']);
		$className = ucfirst($fileName);
		include_once(SP_PLUGINPATH."/$this->directoryName/payment/$fileName.ctrl.php");
		$gatewayCtrler = New $className();
		return $gatewayCtrler;
	}
	
	/**
	 * function to get payment form to proceed payment
	 * @param int $pgId  The payment gateway id
	 */
	function getPaymentForm($pgId, $userId, $utypeInfo, $quantity = 1, $invoiceType = "register") {
		$pgInfo = $this->__getPaymentGatewayInfo($pgId, true, true);		
		$invoiceInfo = array();
		$itemName = SP_COMPANY_NAME . " " . $utypeInfo['user_type'] . " account";
		$invoiceInfo['user_id'] = $userId;
		$invoiceInfo['item_id'] = $utypeInfo['id'];
		$invoiceInfo['item_name'] = $itemName;
		$invoiceInfo['item_quantity'] = $quantity;
		$invoiceInfo['item_amount'] = $utypeInfo['price'];
		$invoiceInfo['currency'] = SP_PAYMENT_CURRENCY;
		$invoiceInfo['invoice_date'] = date('Y-m-d H:i:s');
		$invoiceInfo['invoice_type'] = $invoiceType;
		$invoiceInfo['payment_plugin_id'] = $pgId;
		$orderCtrler = new OrderController();
		$invoiceId = $orderCtrler->saveInvoice($invoiceInfo);
		@Session::setSession('invoice_id', $invoiceId);
		$totalAmount = $invoiceInfo['item_amount'] * $quantity;
		
		$gatewayCtrler = $this->createPaymentGatewayInstance($pgInfo);
		$paymentInfo = array(
			'item_name' => $itemName,
			'item_number' => $invoiceId,
			'item_quantity' => $quantity,
			'item_amount' => $invoiceInfo['item_amount'],
			'currency_code' => SP_PAYMENT_CURRENCY,
			'cancel_return' => SP_PAYMENT_CANCEL_LINK . "?sec=subscription",
			'return' => SP_PAYMENT_RETURN_LINK . "?sec=subscription",
		);
		
		return $gatewayCtrler->__getPaymentForm($paymentInfo, $pgInfo);
	}
	
	// function process transaction after payment
	function processTransaction($pgId, $invoiceId, $retGetInfo, $retPostInfo) {
		
		$pgInfo = $this->__getPaymentGatewayInfo($pgId, true, true);
		$gatewayCtrler = $this->createPaymentGatewayInstance($pgInfo);
		$orderCtrler = new OrderController();
		$invoiceInfo = $orderCtrler->getInvoiceInfo($invoiceId);
		
		// if payment success
		$resInfo = $gatewayCtrler->verifyTransaction($pgInfo, $retGetInfo, $retPostInfo);
		if ($resInfo['status'] == 'success') {
			
			// if both invoice id are equal
			if ($invoiceId == $resInfo['invoice_id']) {
				$orderCtrler->updateInvoiceInfo($invoiceId, $resInfo);
				$userCtrler = new UserController();
				
				// update expiry date
				$expiryDate = $userCtrler->calculateUserExpiryDate($invoiceInfo['item_quantity']);
				$userCtrler->updateUserInfo($invoiceInfo['user_id'], 'expiry_date', $expiryDate);
				
				// update user status, etc
				if ($invoiceInfo['invoice_type'] == "register") {
					$userCtrler->updateUserInfo($invoiceInfo['user_id'], 'status', 1);
					
					// make user login to the system
					$userInfo = $userCtrler->__getUserInfo($invoiceInfo['user_id']);
					$uInfo['userId'] = $userInfo['id'];
					$userTypeCtrler = new UserTypeController();
					$uTypeInfo = $userTypeCtrler->__getUserTypeInfo($invoiceInfo['item_id']);
					$uInfo['userType'] = $uTypeInfo['user_type'];
					$userCtrler->setLoginSession($uInfo);
				} elseif($invoiceInfo['invoice_type'] == "renew") {
					$userCtrler->updateUserInfo($invoiceInfo['user_id'], 'utype_id', $invoiceInfo['item_id']);
				}
				
				redirectUrl(SP_WEBPATH."/admin-panel.php?sec=myprofile&success=1");
				
			} else {
				$resInfo['status'] = "error";
				$orderCtrler->updateInvoiceInfo($invoiceId, $resInfo);
			}
						
		} else {
			$orderCtrler->updateInvoiceInfo($invoiceId, $resInfo);
		}
		
		// if error occured return to corresponding pages
		if ($invoiceInfo['invoice_type'] == "register") {
			redirectUrl(SP_WEBPATH."/register.php?failed=1");
		} else {
			redirectUrl(SP_WEBPATH."/admin-panel.php?sec=myprofile&failed=1");
		}
		
	}
	
}
