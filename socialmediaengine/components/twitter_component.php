<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 */

require_once(PLUGIN_PATH . '/libs/twitteroauth/autoload.php');
use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;

class TwitterComponent extends SocialMediaResources {
    
    function __doConnection($consumerKey, $consumerSecret, $authToken=false, $authSecret=false) {
        
        // check for twitter api details
        if (!empty($consumerKey) && !empty($consumerSecret)) {
            
            try {
                $twitteroauth = new TwitterOAuth($consumerKey, $consumerSecret, $authToken,  $authSecret);
                return [$twitteroauth, NULL];
            } catch (TwitterOAuthException $e) {
                $errorMsg = "SDK Exception: " . $e->getMessage();
                return [false, $errorMsg];
            }
        } else {
            $actLink = pluginLink('action=listSocialMedia');
            $errorMsg = "Error: Twitter API details are not Updated.
            			<a href='javascript:void(0);' onclick=\"scriptDoLoad('$actLink', 'content', '')\">Click here</a> to update it.";
            return [false, $errorMsg];
        }
    }
    
    function connectToSocialMedia($connectionId, $apiInfo, $redirectURL) {
        $consumerKey = $apiInfo['twitter_con_key'];
        $consumerSecret = $apiInfo['twitter_con_secret'];
        
        // do connection with api details
        list($twitteroauth, $errorMsg) = $this->__doConnection($consumerKey, $consumerSecret);
        if (!empty($twitteroauth)) {
            
            try {
                // request token of application
                $request_token = $twitteroauth->oauth(
                    'oauth/request_token', [
                        'oauth_callback' => $redirectURL
                    ]
                );
                
                // throw exception if something gone wrong
                if($twitteroauth->getLastHttpCode() != 200) {
                    return [false, "Error: Connection link generation request failed."];
                }
                
                // save token of application to session
                $_SESSION['oauth_token'] = $request_token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
                
                // generate the URL to make request to authorize our application
                $loginURL = $twitteroauth->url(
                    'oauth/authorize', [
                        'oauth_token' => $request_token['oauth_token']
                    ]
                );                
            } catch (TwitterOAuthException $e) {
                $errorMsg = "SDK Exception: " . $e->getMessage();
                return [false, $errorMsg];
            }
            
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
        $consumerKey = $apiInfo['twitter_con_key'];
        $consumerSecret = $apiInfo['twitter_con_secret'];
        $dataList = [];
        
        // if callback details are empty
        if (empty($callbackInfo['oauth_verifier']) || empty($_SESSION['oauth_token']) || empty($_SESSION['oauth_token_secret'])) {
            return [false, "Connection Error: Conection callback parameters not found.", $dataList];
        }
        
        // do connection with api details
        list($twitteroauth, $errorMsg) = $this->__doConnection($consumerKey, $consumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        if (!empty($twitteroauth)) {
            
            try {
                
                // request user token
                $accessToken = $twitteroauth->oauth(
                    'oauth/access_token', [
                        'oauth_verifier' => $callbackInfo['oauth_verifier']
                    ]
                );
                
                if (!empty($accessToken)) {
                    $dataList['auth_token'] = $accessToken['oauth_token'];
                    $dataList['auth_token_secret'] = $accessToken['oauth_token_secret'];
                    $dataList['account_name'] = $accessToken['screen_name'];
                    return [true, "Successfully connected to Twitter.", $dataList];
                } else {
                    return [false, "Connection error: Access token not generated.", $dataList];
                }                
            } catch (TwitterOAuthException $e) {
                $errorMsg = "SDK Exception: " . $e->getMessage();
                return [false, $errorMsg, $dataList];
            }
        } else {
            return [false, $errorMsg, $dataList];
        }        
    }
    
    function removeConnection($connectionInfo, $apiInfo) {
        $consumerKey = $apiInfo['twitter_con_key'];
        $consumerSecret = $apiInfo['twitter_con_secret'];
        $dataList = [];
        
        // if connection token is empty
        if (empty($connectionInfo['auth_token']) || empty($connectionInfo['auth_token_secret'])) {
            showErrorMsg("Warning: Connection token is missing to remove the connection.", false);
        }
        
        // do connection with api details
        list($twitteroauth, $errorMsg) = $this->__doConnection($consumerKey, $consumerSecret, $connectionInfo['auth_token'], $connectionInfo['auth_token_secret']);
        if (!empty($twitteroauth)) {
            try {
                $twitteroauth->post("/oauth/invalidate_token", []);
            } catch (TwitterOAuthException $e) {
                $errorMsg = "Warning: Failed to delete twitter access token: SDK Exception: " . $e->getMessage();
            }
        } else {
            showErrorMsg($errorMsg, false);
        }
        
        $dataList = ['auth_token' => ''];
        $dataList['auth_token_secret'] = '';
        return $dataList;
    }
    
    function formatMessage($statusInfo) {
    	
    	$tweetText = $statusInfo['share_title'];
    	
    	// if length less than 280 add share url
    	if (strlen($tweetText . " " . $statusInfo['share_url']) <= 280) {
    		$tweetText = $tweetText . " " . $statusInfo['share_url'];
    	}
        
        // if length less than 280 add share tags
        $statusInfo['share_tags'] = str_replace(" ", "", "#". trim($statusInfo['share_tags']));
        $statusInfo['share_tags'] = str_replace(",", " #", $statusInfo['share_tags']);
        if (strlen($tweetText ." ". $statusInfo['share_tags']) <= 280) {
            $tweetText = $tweetText ." ". $statusInfo['share_tags'];
        }
        
        if (strlen($tweetText ." ". $statusInfo['share_description']) <= 280) {
        	$tweetText = $tweetText ." ". $statusInfo['share_description'];
        }
        
        $tweetText = substr($tweetText, 0, 280);
        return $tweetText;
        
    }
    
    function formatPostLink($postLink) {
        $postLink = "statuses/update";
        return $postLink;
    }
    
    function postStatusMessage($linkInfo, $statusInfo, $apiInfo) {
        $consumerKey = $apiInfo['twitter_con_key'];
        $consumerSecret = $apiInfo['twitter_con_secret'];
        $dataList = [];
        
        // if connection token is empty
        if (empty($linkInfo['auth_token']) || empty($linkInfo['auth_token_secret'])) {
            return [false, "Error: Failed to post to {$linkInfo['url']} : Connection token is missing. Please refresh the connection.", $dataList];
        }
        
        // do connection with api details
        list($twitteroauth, $errorMsg) = $this->__doConnection($consumerKey, $consumerSecret, $linkInfo['auth_token'], $linkInfo['auth_token_secret']);
        if (!empty($twitteroauth)) {
            
            // format tweet message
            $tweetText = $this->formatMessage($statusInfo);
            
            try {
                $postUrl = $this->formatPostLink($linkInfo['url']);
                $postData = ["status" => $tweetText];
                
                // if image needs to be uploaded
                if (!empty($statusInfo['share_image'])) {
                    $mediaFile = SP_TMPPATH ."/". $statusInfo['share_image'];
                    $media = $twitteroauth->upload('media/upload', ['media' => $mediaFile]);
                    $postData['media_ids'] = $media->media_id_string;
                }
                
                // post data and check status
                $status = $twitteroauth->post($postUrl, $postData);
                if (!empty($status->id)) {
                    $dataList['submit_ref_id'] = $status->id;
                    $successMsg = "Successfully posted to {$linkInfo['connection_name']} with ref id: ".$dataList['submit_ref_id'];
                    return [true, $successMsg, $dataList];
                } else {
                    $errorMsg = !empty($status->errors[0]->message) ? $status->errors[0]->message : "";
                    $errorMsg = "Error: Failed to post to {$linkInfo['connection_name']} : $errorMsg";
                }
                
            } catch (TwitterOAuthException $e) {
                $errorMsg = "Error: Failed to post to {$linkInfo['url']} : SDK Exception: " . $e->getMessage();
            }
        }
        
        return [false, $errorMsg, $dataList];
    }
    
}