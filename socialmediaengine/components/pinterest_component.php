<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 */

require_once(PLUGIN_PATH . '/libs/Pinterest-API-PHP/autoload.php');

use DirkGroenen\Pinterest\Pinterest;

class PinterestComponent extends SocialMediaResources {
    
    function __doConnection($appId, $secret) {
        
        // check for api details
        if (!empty($appId) && !empty($secret)) {
            try {
                $pinterest = new Pinterest($appId, $secret);
                return [$pinterest, NULL];
            } catch (Exception $e) {
                $errorMsg = "SDK Exception: " . $e->getMessage();
                return [false, $errorMsg];
            }
        } else {
        	$actLink = pluginLink('action=listSocialMedia');
            $errorMsg = "Error: Pinterest API details are not Updated.
            			<a href='javascript:void(0);' onclick=\"scriptDoLoad('$actLink', 'content', '')\">Click here</a> to update it.";
            return [false, $errorMsg];
        }
    }
    
    function connectToSocialMedia($connectionId, $apiInfo, $redirectURL) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        
        // do fb connection with api details
        list($pinterest, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($pinterest)) {
        	$loginURL = $pinterest->auth->getLoginUrl($redirectURL, array('read_public', 'write_public'));
            
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
        
        
        // if callback details are empty
        if (empty($callbackInfo['code'])) {
        	return [false, "Connection Error: Conection callback parameters not found.", $dataList];
        }
        
        // do fb connection with api details
        list($pinterest, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($pinterest)) {
        
            try {
                $token = $pinterest->auth->getOAuthToken($callbackInfo['code']);
                $accessToken = $token->access_token;
            } catch (Exception $e) {
                $errorMsg = "Connection Error: Response Exception: " . $e->getMessage();
            }
            
            if (!empty($accessToken)) {
                $dataList['connection_token'] = $accessToken;
                
                // get account name
                $pinterest->auth->setOAuthToken($accessToken);
                $me = $pinterest->users->me();
                $account_name = $me->__get('first_name') ." ". $me->__get('last_name');
                $dataList['account_name'] = $account_name;
                return [true, "Successfully connected to Pinterest.", $dataList];
            } else {
                return [false, $errorMsg, $dataList];
            }
            
        } else {
            return [false, $errorMsg, $dataList];
        }
        
    }
    
    function removeConnection($connectionInfo, $apiInfo) {
        $dataList = ['connection_token' => ''];
        return $dataList;
    }
    
    function formatMessage($statusInfo) {
        $message = $statusInfo['share_title'] .". ". $statusInfo['share_description'];
        $statusInfo['share_tags'] = str_replace(" ", "", "#". trim($statusInfo['share_tags']));
        $statusInfo['share_tags'] = str_replace(",", " #", $statusInfo['share_tags']);
        $message .= ". " . $statusInfo['share_tags'];
        return $message;
    }
    
    function formatPostLink($postLink) {
        $postLink = preg_replace("/.*pinterest\.com\//", "", $postLink);
        return preg_replace('/\/$/', "", $postLink);
    }
    
    function postStatusMessage($linkInfo, $statusInfo, $apiInfo) {
        $appId = $apiInfo['app_id'];
        $secret = $apiInfo['secret_key'];
        $dataList = [];
        
        // if connection token is empty 
        if (empty($linkInfo['connection_token'])) {
        	return [false, "Error: Failed to post to {$linkInfo['url']} : Connection token is missing. Please refresh the connection.", $dataList];
        }
        
        // if image is empty we can not post to pinterest
        if (empty($statusInfo['share_image'])) {
            return [false, "Error: Failed to post to {$linkInfo['url']} : No image file found in the post.", $dataList];
        }
        
        // format image and message to post
        $messge = $this->formatMessage($statusInfo);
        $mediaFile = SP_TMPPATH ."/". $statusInfo['share_image'];
        
        // do social media connection with api details
        list($pinterest, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($pinterest)) {
        	
        	try {
        	    
        	    $pinterest->auth->setOAuthToken($linkInfo['connection_token']);
        	    $response = $pinterest->pins->create(array(
        	        "board" => $this->formatPostLink($linkInfo['url']),
        	        "note" => $messge,
        	        "link" => $statusInfo['share_url'],
        	        "image" => $mediaFile,
        	    ));
        	    
        	    $postId = $response->__get("id");
        	    if (!empty($postId)) {
        	        $dataList['submit_ref_id'] = $postId;
        	        $successMsg = "Successfully posted to {$linkInfo['url']} with ref id: ".$postId;
        	        return [true, $successMsg, $dataList];
        	    } else {
        	        $errorMsg = "Error: Failed to post to {$linkInfo['url']}";
        	    }
        	    
        	} catch (Exception $e) {
        		$errorMsg = "Error: Failed to post to {$linkInfo['url']} : Response Exception: " . $e->getMessage();
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
            return [false, "Error: Connection token is missing", $dataList];
        }
        
        // do social media connection with api details
        list($pinterest, $errorMsg) = $this->__doConnection($appId, $secret);
        if (!empty($pinterest)) {
            try {
                
                $pinterest->auth->setOAuthToken($connectionInfo['connection_token']);
                $result = $pinterest->users->getMeBoards();
                if (is_object($result)) {
                    
                    // loop through board
                    $boardList = $result->all();
                    foreach ($boardList as $board) {
                        $boardUrl = $board->__get("url");
                        $dataList[] = preg_replace('/\/$/', "", $boardUrl);
                    }
                    
                    return [true, "Successfully retrieved user boards.", $dataList];
                }
                
            } catch (Exception $e) {
                $errorMsg = "Error: Failed to get board list : Response Exception: " . $e->getMessage();
            }
        }
        
        return [false, $errorMsg, $dataList];
        
    }
    
}