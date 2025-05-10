<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

require_once(PLUGIN_PATH . '/libs/Linkedin/vendor/autoload.php');

class LinkedinComponent extends SocialMediaResources {
    
    function __doConnection($clientId, $clientSecret, $redirectURL) {
        
        // check for api details
        if (!empty($clientId) && !empty($clientSecret)) {
            
            try {
                $client = new League\OAuth2\Client\Provider\LinkedIn([
                    'clientId'          => $clientId,
                    'clientSecret'      => $clientSecret,
                    'redirectUri'       => $redirectURL,
                ]);
                
                return [$client, NULL];
            } catch (Exception $e) {
                $errorMsg = "Client Exception: " . $e->getMessage();
                return [false, $errorMsg];
            }
        } else {
            $actLink = pluginLink('action=listSocialMedia');
            $errorMsg = "Error: Linkedin API details are not Updated.
            			<a href='javascript:void(0);' onclick=\"scriptDoLoad('$actLink', 'content', '')\">Click here</a> to update it.";
            return [false, $errorMsg];
        }
    }
    
    function connectToSocialMedia($connectionId, $apiInfo, $redirectURL) {
        $clientId = $apiInfo['linkedin_client_id'];
        $clientSecret = $apiInfo['linkedin_client_secret'];
        
        // do connection with api details
        list($client, $errorMsg) = $this->__doConnection($clientId, $clientSecret, $redirectURL);
        if (!empty($client)) {
            
            try {
                $options = [
                    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
                    'scope' => ['r_liteprofile', 'r_emailaddress', 'w_member_social', 'rw_company_admin']
                ];
                
                $loginURL = $client->getAuthorizationUrl($options);
                $_SESSION['oauth2state'] = $client->getState();
            } catch (Exception $e) {
                $errorMsg = "Login URL Exception: " . $e->getMessage();
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
        $clientId = $apiInfo['linkedin_client_id'];
        $clientSecret = $apiInfo['linkedin_client_secret'];
        $dataList = [];
        
        if (!empty($callbackInfo['error'])) {
            return [false, "Connection Error: {$callbackInfo['error']} - {$callbackInfo['error_description']}", $dataList];
        }
        
        // if callback details are empty
        if (empty($callbackInfo['code']) || empty($callbackInfo['state']) || $callbackInfo['state'] != $_SESSION['oauth2state']) {
            unset($_SESSION['oauth2state']);
            return [false, "Connection Error: Conection callback parameters not found.", $dataList];
        }
        
        // do connection with api details
        list($client, $errorMsg) = $this->__doConnection($clientId, $clientSecret, $this->redirectURL);
        if (!empty($client)) {
            
            try {
                
                // Try to get an access token (using the authorization code grant)
                $accessToken = $client->getAccessToken('authorization_code', [
                    'code' => $callbackInfo['code'],
                ]);
                
                if (!empty($accessToken)) {
                    $dataList['connection_token'] = $accessToken->getToken();
                    $dataList['token_expire_at'] = date("Y-m-d H:i:s", $accessToken->getExpires());
                    
                    if (!empty($dataList['connection_token']) && !empty($dataList['token_expire_at'])) {
                        
                        // We got an access token, let's now get the user's details
                        $user = $client->getResourceOwner($accessToken);
                        $dataList['account_name'] = $user->getEmail();
                        $dataList['connection_ref'] = $user->getId();
                        return [true, "Successfully connected to Linkedin.", $dataList];
                    } else {
                        return [false, "Connection error: Access token parsing failed.", $dataList];
                    }
                    
                } else {
                    return [false, "Connection error: Access token not generated.", $dataList];
                }
                
            } catch (Exception $e) {
                $errorMsg = "Callback Exception: " . $e->getMessage();
                return [false, $errorMsg, $dataList];
            }
        } else {
            return [false, $errorMsg, $dataList];
        }
    }
    
    function removeConnection($connectionInfo, $apiInfo) {
        $dataList = ['connection_token' => ''];
        $dataList['token_expire_at'] = date('Y-m-d H:i:s');
        return $dataList;
    }
    
    function formatMessage($statusInfo) {
        $message = !empty($statusInfo['share_image']) ? $statusInfo['share_title'].". ".$statusInfo['share_url'] : $statusInfo['share_title'] . ".";
        $message .= " " . $statusInfo['share_description'];
        $statusInfo['share_tags'] = str_replace(" ", "", "#". trim($statusInfo['share_tags']));
        $statusInfo['share_tags'] = str_replace(",", " #", $statusInfo['share_tags']);
        $message .= ". " . $statusInfo['share_tags'];
        return $message;
    }
    
    function postStatusMessage($linkInfo, $statusInfo, $apiInfo) {
        $clientId = $apiInfo['linkedin_client_id'];
        $clientSecret = $apiInfo['linkedin_client_secret'];
        $dataList = [];
        
        // if connection token is empty
        if (empty($linkInfo['connection_token'])) {
            return [false, "Error: Failed to post to {$linkInfo['url']} : Connection token is missing. Please refresh the connection.", $dataList];
        }
        
        // if connection account id is empty
        if (empty($linkInfo['connection_ref'])) {
            return [false, "Error: Failed to post to {$linkInfo['url']} : Connection account id is missing. Please refresh the connection.", $dataList];
        }
        
        // format message
        $message = $this->formatMessage($statusInfo);
        
        // do connection with api details
        list($client, $errorMsg) = $this->__doConnection($clientId, $clientSecret, $this->redirectURL);
        if (!empty($client)) {
            
            try {
                
                // if image needs to be uploaded
                if (!empty($statusInfo['share_image'])) {
                    $mediaFile = SP_TMPPATH ."/". $statusInfo['share_image'];
                    $postInfo = $client->linkedInPhotoPost($linkInfo['connection_token'], $linkInfo['connection_ref'], $message,
                        $mediaFile, $statusInfo['share_title'], $statusInfo['share_description']);
                } elseif(!empty($statusInfo['share_url'])) {
                    $postInfo = $client->linkedInLinkPost($linkInfo['connection_token'], $linkInfo['connection_ref'], $message,
                        $statusInfo['share_title'], $statusInfo['share_description'], $statusInfo['share_url']);
                } else {
                    $postInfo = $client->linkedInTextPost($linkInfo['connection_token'], $linkInfo['connection_ref'], $message);
                }
                
                // check the results
                $postInfo = json_decode($postInfo);
                
                if (!empty($postInfo->id)) {
                    $dataList['submit_ref_id'] = $postInfo->id;
                    $successMsg = "Successfully posted to {$linkInfo['url']} with ref id: ".$postInfo->id;
                    return [true, $successMsg, $dataList];
                } else {
                    $errorMsg = !empty($postInfo->message) ? $postInfo->message : "";
                    $errorMsg = "Error: Failed to post to {$linkInfo['url']} : $errorMsg";
                }
                
            } catch (Exception $e) {
                $errorMsg = "Error: Failed to post to {$linkInfo['url']} : SDK Exception: " . $e->getMessage();
            }
        }
        
        return [false, $errorMsg, $dataList];
    }
    
}