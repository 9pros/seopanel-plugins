<?php
/**
 * Description of WebsiteImporter
 *
 * @author Raheela Muneer
 */
class ManageImporter extends ArticleSubmitter {
	var $dirCols;
	var $linkTypes; // The types of links allowed by a directory script
	var $tableName_website = "as_websites";
	
	function importWebsite($data) {
		$this->pluginRender ( showimportwebsite );
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
	
	function createNewWebsite($data, $details, $checkStatus = true) {
		$url = trim ( $data ['0'] );
		if ((! empty ( $data ['1'] )) && (! empty ( $data ['2'] ))) {
			$authentication = '1';
			$username = trim ( $data ['1'] );
			$passwrd = trim ( $data ['2'] );
			$enc_password = base64_encode ( $passwrd );
		} else {
			$authentication = '0';
			$username = '';
			$enc_password = '';
		}
		$status = false;
		preg_match ( '/(.*)\//', $url, $matches );
		if (! empty ( $matches [1] )) {
			$domain = addHttpToUrl ( $matches [1] );
			$website_url = addHttpToUrl ( $url );
			
			if ($details ['checkpr'] == '1') {
				include_once (SP_CTRLPATH . "/rank.ctrl.php");
				$rankCtrler = new RankController ();
				$pagerank = $rankCtrler->__getGooglePageRank ( 'domain' );
			} else {
				$pagerank = '0';
			}
			
			if ($authentication == '1') {
				$cookieFile = SP_TMPPATH . "/" . AS_COOKIE_FILE;
				system ( "> $cookieFile" );
				$login_url = $domain . "/login.php";
				$password = $passwrd;
				$postdata = "user=" . $username . "&pass=" . $password . "&formSubmitted=" . "1&login=Login";
				
				// function to login to site and stores cookie in cookie file
				$obj = new ManageSubmitter ();
				$curlReturnValue = $obj->curlToFetch ( $login_url, $postdata, $cookieFile );
				if ($curlReturnValue == '1') {
					$page = $obj->getPageData ( $cookieFile, $website_url );
					$captchaUrl = '';
					if (stristr ( $page, "captcha.php" )) {
						$captchaUrl = "captcha.php";
					}
					if (! empty ( $captchaUrl )) {
						$is_captch = "1";
					} else {
						$is_captch = "0";
					}
					if ($details ['checkstatus'] == '1') {
						if ((stristr ( $page, 'Title:' )) && (stristr ( $page, 'Short Description:' )) && (stristr ( $page, 'Article:' )) && (stristr ( $page, 'Article:' ))) {
							$status = "1";
						} else {
							$status = "0";
						}
					}
				} else {
					$is_captch = "0";
					$status = "0";
				}
			} else {
				$spider = new Spider ();
				$spider->_CURLOPT_HEADER = 1;
				$ret = $spider->getContent ( addHttpToUrl ( $listInfo ['website_url'] ) );
				$page = $ret ['page'];
				$captchaUrl = '';
				
				if (stristr ( $page, "captcha.php" )) {
					$captchaUrl = "captcha.php";
				}
				if (! empty ( $captchaUrl )) {
					$is_captch = "1";
				} else {
					$is_captch = "0";
				}
				if ($details ['checkstatus'] == '1') {
					if ((stristr ( $page, 'Title:' )) && (stristr ( $page, 'Short Description:' )) && (stristr ( $page, 'Article:' )) && (stristr ( $page, 'Article:' ))) {
						$status = "1";
					} else {
						$status = "0";
					}
				}
			}
			
			$sql = "INSERT INTO `as_websites`(`website_name`, `domain`,
                        `website_url`, `authentication`, `username`, `password`,
                        `is_captcha`, `google_pagerank`, `status`)
                        VALUES
                        ('$domain','$domain','$website_url','$authentication',
                        '$username','$enc_password','$is_captch','$pagerank','$status')";
			$this->db->query ( $sql );
			$status = true;
		}
		
		return $status;
	}
	
	function __checkName($siteUrl) {
		$siteUrl = addHttpToUrl ( trim ( $siteUrl ) );
		$sql = "SELECT `id` FROM $this->tableName_website WHERE `website_url` = '" . addslashes ( $siteUrl ) . "'";
		$listInfo = $this->db->select ( $sql, true );
		return empty ( $listInfo ['id'] ) ? false : $listInfo ['id'];
	}
}

?>
