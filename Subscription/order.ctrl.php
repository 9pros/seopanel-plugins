<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class OrderController extends Subscription{

	/*
	 * show order manager
	 */
	function showOrderManager($info = '') {
		$userId = isLoggedIn();
		$pgScriptPath = PLUGIN_SCRIPT_URL ."&action=orderManager";
		$sql = "select i.*, u.username from subscription_invoice i, users u where u.id=i.user_id";
		
		// if admin user
		if (isAdmin()) {
			$userCtrler = New UserController();
			$userList = $userCtrler->__getAllUsers();
			$this->set('userList', $userList);
			
			// if user id is not empty
			if (!empty($info['user_id'])) {
				$sql .= " and i.user_id=".intval($info['user_id']);
				$pgScriptPath .= "&user_id=" . intval($info['user_id']);
				$this->set('userId', $info['user_id']);
			}
			
		} else {
			$sql .= " and i.user_id=$userId";
		}

		// search for user name
		if (!empty($info['search_name'])) {
			$sql .= " and (i.item_name like '%".addslashes($info['search_name'])."%'
			or i.id like '%".addslashes($info['search_name'])."%' or i.invoice_type like '%".addslashes($info['search_name'])."%')";
			$pgScriptPath .= "&search_name=" . $info['search_name'];
		}
		
		// pagination setup		
		$this->db->query($sql, true);
		$this->paging->setDivClass('pagingdiv');
		$this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
		$pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');		
		$this->set('pagingDiv', $pagingDiv);
		$sql .= " order by last_updated desc limit ".$this->paging->start .",". $this->paging->per_page;
		
		$rList = $this->db->select($sql);		
		$this->set('list', $rList);
		$this->set('pageNo', $_GET['pageno']);	
		$this->set('info', $info);
		
		$submitLink = pluginPOSTMethod('listform', 'content', 'action=orderManager');
		$this->set('submitLink', $submitLink);
		
		$currencyCtrler = new CurrencyController();
		$this->set('currencyList', $currencyCtrler->getCurrencyCodeMapList());
		
		$this->pluginRender('order_manager');
	}
	
	/**
	 * function to show order info
	 * @param int $orderId	The id of the order
	 */
	function viewOrder($orderId) {
		$userId = isLoggedIn();
		$orderInfo = $this->getInvoiceInfo($orderId);
		
		// check the invoice can be accessed by user if not admin
		if (!isAdmin() && ($orderInfo['user_id'] != $userId)) {
			$this->set('errMsg', "Not authorized to access this invoice");
		}
		
		$currencyCtrler = new CurrencyController();
		$this->set('currencyList', $currencyCtrler->getCurrencyCodeMapList());
		
		$userCtrler = new UserController();
		$this->set("userInfo", $userCtrler->__getUserInfo($orderInfo['user_id']));
		
		$pgCtrler = new PaymentGateway();
		$this->set("pgInfo", $pgCtrler->__getPaymentGatewayInfo($orderInfo['payment_plugin_id'], false));
		
		$this->set('orderInfo', $orderInfo);
		$this->pluginRender('view_order');
	}

	// function save invoice
	function saveInvoice($invoiceInfo = '') {
		$invoiceId = false;
	
		// loop through the invoice details and save in database
		foreach ($invoiceInfo as $key => $value) {
			$invoiceInfo[$key] = addslashes($value);
		}
	
		$sql = "insert into subscription_invoice(".implode(',', array_keys($invoiceInfo)).")
		values('".implode("','", array_values($invoiceInfo))."')";
		$this->db->query($sql);
		$invoiceId =  $this->db->getMaxId('subscription_invoice');
	
		return $invoiceId;
	}
	
	// function to get invoice details
	function getInvoiceInfo($invoiceId) {
		$invoiceId = intval($invoiceId);
		$sql = "select * from subscription_invoice where id=$invoiceId";
		$invoiceInfo = $this->db->select($sql, true);
		return $invoiceInfo;
	}
	
	// function to update order info
	function updateInvoiceInfo($invoiceId, $invoiceInfo) {
		$sql = "update subscription_invoice set ";
		
		foreach ($invoiceInfo as $col => $val) {
			if ($col == "invoice_id") continue;
			$sql .= "$col='".addslashes($val)."',";
		}
	
		$sql .= "last_updated=CURRENT_TIMESTAMP where id='".intval($invoiceId)."'";
		$this->db->query($sql);
	
	}
	
}
