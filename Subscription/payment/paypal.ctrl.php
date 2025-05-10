<?php
class Paypal extends Subscription {
	
	/**
	 * functio to get the payment form
	 */
	function __getPaymentForm($paymentInfo, $pgInfo) {
		
		$this->set('pgInfo', $pgInfo);
		$this->set('paymentInfo', $paymentInfo);
		return $this->getPluginViewContent('payment/paypal');
	}
	
	// function to verify the paypal transaction was valid for not
	function paypalPDTCheck($pgInfo) {
	
		$responseInfo =   array();
	
		// if paypal send the transaction id
		if (isset($_GET['tx'])) {
	
			$tx = strtoupper($_GET['tx']);
			$rs = $pgInfo['PP_TOKEN'];
			
			$request = curl_init();
			
			// Set request options
			curl_setopt_array($request, array(
				CURLOPT_URL => $pgInfo['PP_URL'],
				CURLOPT_POST => TRUE,
				CURLOPT_POSTFIELDS => http_build_query(array(
					'cmd' => '_notify-synch',
					'tx' => $tx,
					'at' => $rs,
				)),
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HEADER => FALSE,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_SSL_VERIFYPEER => 0,
			));
	
			// Execute request and get response and status code
			$response = curl_exec($request);
			$status   = curl_getinfo($request, CURLINFO_HTTP_CODE);
			$responseInfo['status'] = $status;
			
			// if success transaction
			if ($status) {
				$responseInfo['response'] = $response;
			} else {
				$errorCode = curl_errno( $request);
				$errMsg = curl_error( $request );
				$responseInfo['response'] = "Error Code: $errorCode $errMsg";
			}
			
			curl_close($request);
		}		
		 
		return $responseInfo;
	}
	
	// function to verify transaction
	function verifyTransaction($pgInfo, $retGetInfo, $retPostInfo) {
	
		$responseInfo = $this->paypalPDTCheck($pgInfo);
	
		if (($responseInfo['status'] == 200) && (strpos($responseInfo['response'] , 'SUCCESS') === 0)) {
				
			if (preg_match('/item_number=(\d+)/', $responseInfo['response'], $matches)) {
	
				// if match not empty
				if (!empty($matches[1])) {
					$invoiceId = intval($matches[1]);
					preg_match('/txn_id=(.+)/', $responseInfo['response'], $matches);
					$transId = $matches[1];
					$resInfo = array(
						'status' => 'success',
						'txn_log' => $responseInfo['response'],
						'invoice_id' => $invoiceId,
						'txn_id' => $transId,
					);
					
					return $resInfo;
				}
			}
		}	
		
		$resInfo = array(
			'status' => 'error',
			'txn_log' => $responseInfo['response'],
			'invoice_id' => 0,
		);
		
		return $resInfo;
	}
	
}