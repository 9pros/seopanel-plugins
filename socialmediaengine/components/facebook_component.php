<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 */

require_once(PLUGIN_PATH . '/libs/Facebook/autoload.php');

class FacebookComponent extends SocialMediaResources {
    
    function __doConnection($appId, $secret) {
        
        // chekc for FB api details
        if (!empty($appId) && !empty($secret)) {
            try {
                $FB = new \Facebook\Facebook([
                    'app_id' => $appId,
                    'app_secret' => $secret,
                    'default_graph_version' => 'v5.0'
                ]);
                return [$FB, NULL];
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $errorMsg = "SDK Exception: " . $e->getMessage();
                return [false, $errorMsg];
            }
        } else {
        	$actLink = pluginLink('action=listSocialMedia');
            $errorMsg = "Error: Facebook API details are not Updated.
            			<a href='javascript:void(0);' onclick=\"scriptDoLoad('$actLink', 'content', '')\">Click here</a> to update it.";
            return [false, $errorMsg];
        }
    }
    
    function connectToSocialMedia($connectionId, $apiInfo, $redirectURL) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        
        // do fb connection with api details
        list($FB, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($FB)) {
            $helper = $FB->getRedirectLoginHelper();
            $permissions = ['publish_pages', 'manage_pages'];
            $loginURL = $helper->getLoginUrl($redirectURL, $permissions);
            
            if (!empty($loginURL)) {
                return [true, $loginURL];
            } else {
                return [false, "Error: Connection link generated is empty."];
            }
        } else {
            return [false, $errorMsg];
        }
    }
    
    function connectionCallback($connectionId, $apiInfo, $callbackInfo) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        $dataList = [];
        
        if (!empty($callbackInfo['error'])) {
            return [false, "Connection Error: {$callbackInfo['error_description']} - {$callbackInfo['error_reason']}", $dataList];
        }
        
        // do fb connection with api details
        list($FB, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($FB)) {
        
            try {
                $helper = $FB->getRedirectLoginHelper();
                $accessToken = $helper->getAccessToken();
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                $errorMsg = "Connection Error: Response Exception: " . $e->getMessage();
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $errorMsg = "Connection Error: SDK Exception: " . $e->getMessage();
            }
            
            if (!empty($accessToken)) {
                $oAuth2Client = $FB->getOAuth2Client();
                if (!$accessToken->isLongLived()) {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                }
            }
            
            if (!empty($accessToken)) {
                $dataList['connection_token'] = $accessToken;
                $response = $FB->get('/me?fields=id,name', $accessToken);
                $user = $response->getGraphUser();
                $dataList['account_name'] = $user['name'];
                return [true, "Successfully connected to Facebook.", $dataList];
            } else {
                return [false, $errorMsg, $dataList];
            }
            
        } else {
            return [false, $errorMsg, $dataList];
        }
        
    }
    
    function removeConnection($connectionInfo, $apiInfo) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        
        // if connection token is empty
        if (empty($connectionInfo['connection_token'])) {
        	showErrorMsg("Warning: Connection token is missing to remove the connection.", false);
        }
        
        // do fb connection with api details
        list($FB, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($FB)) {
        	try {
        		$FB->delete('/me/permissions', [], $connectionInfo['connection_token']);
        	} catch (\Facebook\Exceptions\FacebookResponseException $e) {
        		showErrorMsg("Warning: Response Exception: <br>" . $e->getMessage(), false);
        	} catch (\Facebook\Exceptions\FacebookSDKException $e) {
        		showErrorMsg("Warning: SDK Exception: " . $e->getMessage(), false);
        	}
        } else {
        	showErrorMsg($errorMsg, false);
        }
        
        $dataList = ['connection_token' => ''];
        return $dataList;
    }
    
    function formatMessage($statusInfo) {
        $message = !empty($statusInfo['share_image']) ? $statusInfo['share_title'].". ".$statusInfo['share_url'] : $statusInfo['share_title'].".";
        $message .= " " . $statusInfo['share_description'];
        $statusInfo['share_tags'] = str_replace(" ", "", "#". trim($statusInfo['share_tags']));
        $statusInfo['share_tags'] = str_replace(",", " #", $statusInfo['share_tags']);
        $message .= ". " . $statusInfo['share_tags'];
        return $message;
    }
    
    function formatPostLink($postLink, $returnPageId = false) {
        $replace = array("http://www.facebook.com", "https://www.facebook.com", "http://facebook.com", "https://facebook.com", "/feed");
        $postLink = str_replace($replace, "", $postLink);
        
        // if page name with page id, extract the page id only
        $matches = [];
        if (preg_match('/\-(\d+)/', $postLink, $matches)) {
            $postLink = $matches[1];
        }
        
        if ($returnPageId) {
        	return $postLink;
        }
        
        $postLink .= preg_match('/\/$/', $postLink) ? "" : "/";
        return "/".$postLink;
    }
    
    function postStatusMessage($linkInfo, $statusInfo, $apiInfo) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        $dataList = [];
        
        // if connection token is empty 
        if (empty($linkInfo['connection_token'])) {
        	return [false, "Error: Failed to post to {$linkInfo['url']} : Connection token is missing. Please refresh the connection.", $dataList];
        }
        
        // do fb connection with api details
        list($FB, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($FB)) {
        	
        	// if image needs to be uploaded
            if (!empty($statusInfo['share_image'])) {
                $postAction = "photos";
                $mediaFile = SP_TMPPATH ."/". $statusInfo['share_image'];
        	    $linkData = [
        	        'message' => $this->formatMessage($statusInfo),
        	        'source' => $FB->fileToUpload($mediaFile),
        	    ];
            } else {
                $postAction = "feed";
        	    $linkData = [
        	        'message' => $this->formatMessage($statusInfo),
        	        'link' => $statusInfo['share_url'],
            	];
        	}
        	
        	try {
        		$postUrl = $linkInfo['url'];
	        	$pageId = $this->formatPostLink($linkInfo['url'], true);
	        	$response = $FB->get('/'.$pageId.'?fields=access_token', (string)$linkInfo['connection_token']);
				$json = json_decode($response->getBody());
				$page_token = $json->access_token;
	        	
	        	$postUrl = $this->formatPostLink($linkInfo['url']);
	        	$response = $FB->post($postUrl.$postAction, $linkData, $page_token);
        		$graphNode = $response->getGraphNode();
        		$dataList['submit_ref_id'] = $graphNode['id'];
        		$successMsg = "Successfully posted to {$linkInfo['url']} with ref id: ".$graphNode['id'];
        		return [true, $successMsg, $dataList];
        	} catch (\Facebook\Exceptions\FacebookResponseException $e) {
        		$errorMsg = "Error: Failed to post to $postUrl <br><br> Response Exception: <br>" . $e->getMessage();
        	} catch (\Facebook\Exceptions\FacebookSDKException $e) {
        		$errorMsg = "Error: Failed to post to {$linkInfo['url']} : SDK Exception: " . $e->getMessage();
        	}
        }
        
        return [false, $errorMsg, $dataList];
    }
    
    function getSubmissionPages($connectionInfo, $apiInfo) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        $dataList = [];
        
        // if connection token is empty
        if (empty($connectionInfo['connection_token'])) {
            showErrorMsg("Warning: Connection token is missing.", false);
        }
        
        // do fb connection with api details
        list($FB, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($FB)) {
            try {
                
                $response = $FB->get('/me/accounts?fields=id,name,link', (string)$connectionInfo['connection_token']);                
                $pages = json_decode($response->getBody());                
                if (!empty($pages->data)) {
                    foreach ($pages->data as $page) {
                        $dataList[] = $page->link;
                    }
                }
                
                return [true, "Successfully retrieved user pages.", $dataList];
            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
                $errorMsg = "Error: Failed to get page list : Response Exception: <br>" . $e->getMessage();
            } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                $errorMsg = "Error: Failed to get page list : SDK Exception: " . $e->getMessage();
            }
        }
        
        return [false, $errorMsg, $dataList];        
    }
    
}