<?php
class wordpressHelper extends ManageSubmitter {
    var $rpcScript = "xmlrpc.php";
    var $pageScript = "index.php?p=";
    var $siteUsername = "";
    var $sitePassword = "";
    var $xrpcClient;
    
    function __createXMLRPCClient($siteUrl, $userName, $password) {
        include_once(SP_PLUGINPATH . "/$this->directoryName/libs/class-IXR.php");
        $xmlRPCUrl = Spider::addTrailingSlash($siteUrl) . $this->rpcScript;
        $this->xrpcClient = new IXR_Client($xmlRPCUrl);
        $this->siteUsername = $userName;
        $this->sitePassword = $password;
        
        if (SP_DEBUG) {
            $this->xrpcClient->debug = true;
        }
    }
    
    function getArtcileLink($blogUrl, $blogId) {
        return Spider::addTrailingSlash($blogUrl) . $this->pageScript . $blogId;
    }
    
    function checkDirectoryStatus($dirInfo) {
        $this->__createXMLRPCClient($dirInfo['website_url'], $dirInfo['username'], $dirInfo['password']);
        $res = $this->findAuthor($this->siteUsername);
        $resMsg = $_SESSION['text']['label']['Success'];
        if (empty($res)) {
            $resMsg = !empty($this->xrpcClient->error->message) ? $this->xrpcClient->error->message : $_SESSION['text']['common']['Internal error occured'];
        }
        
        return [$res, $resMsg];
    }
    
    /**
     *  example  $categories     =   "chess,coolbeans";
     */
    function submitArticleInfo($websiteInfo, $articleInfo) {
        $subResult = [
            'status' => 'failed', 
            'msg' => $_SESSION['text']['common']['Internal error occured'],
            'ref_id' => '',
        ];
        
        $this->__createXMLRPCClient($websiteInfo['website_url'], $websiteInfo['username'], $websiteInfo['password']);
        $title = $articleInfo['title'];
        $body = convertMarkdownToHtml($articleInfo['article']);
        $author = !empty($articleInfo['author']) ? $articleInfo['author'] : $this->siteUsername;
        $authorID =  $this->findAuthor($author);
        
        // if image needs to be uploaded
        if (!empty($articleInfo['img_location'])) {
            $attachImage = $this->uploadImage($articleInfo['img_location']);
            $body .= "<img src='$attachImage' width='256' height='256' /></a>";
        }
        
        // $categories is a list seperated by ,
        $cats = preg_split('/,/', $articleInfo['categories'], -1, PREG_SPLIT_NO_EMPTY);
        foreach ($cats as $category) {
            $this->createCategory($category, "", "");
        }
        
        $data = array(
            'title' => $title,
            'description' => $body,
            'dateCreated' => (new IXR_Date(time())),
            //'dateCreated' => (new IXR_Date($time)),  //publish in the future
            'mt_allow_comments' => 0, // 1 to allow comments
            'mt_allow_pings' => 0,// 1 to allow trackbacks
            'categories' => $cats,
            'wp_author_id' => $authorID,
        );
        
        $published = 1; // 0 - draft, 1 - published
        $this->xrpcClient->query('metaWeblog.newPost', '', $this->siteUsername, $this->sitePassword, $data, $published);
        if (!empty($this->xrpcClient->error)) {
            $subResult['msg'] = !empty($this->xrpcClient->error->message) ? $this->xrpcClient->error->message : $_SESSION['text']['common']['Internal error occured'];
        } else {
            $subResult['status'] = "success";
            $subResult['msg'] = $_SESSION['text']['label']['Success'];
            $subResult['ref_id'] = $this->xrpcClient->getResponse();
        }
        
        return $subResult;
    }
    
    function uploadImage($filename){
        $fs = filesize($filename);
        $file = fopen($filename, 'rb');
        $filedata = fread($file, $fs);
        fclose($file);
        
        $data = array(
            'name'  => basename($filename),
            'type'  => 'image/jpg',
            'bits'  => new IXR_Base64($filedata),
            false //overwrite
        );
        
        $this->xrpcClient->query('wp.uploadFile', 1, $this->siteUsername, $this->sitePassword, $data);
        $returnInfo = $this->xrpcClient->getResponse();
        return !empty($returnInfo['url']) ? $returnInfo['url'] : false;
    }
    
    function findAuthor($author){
        $this->xrpcClient->query('wp.getAuthors ', 0, $this->siteUsername, $this->sitePassword);
        $authors = $this->xrpcClient->getResponse();
        if (!empty($authors)) {
            foreach ($authors as $key => $data) {
                if($authors[$key]['user_login'] == $author){
                    return $data['user_id'];
                }
            }
        }
        
        return false;
    }
    
    function createCategory($catName,$catSlug,$catDescription) {
        $res = $this->xrpcClient->query('wp.newCategory', '', $this->siteUsername, $this->sitePassword,
            array(
                'name' => $catName,
                'slug' => $catSlug,
                'parent_id' => 0,
                'description' => $catDescription
            ));
        return $res;
    }
}
?>