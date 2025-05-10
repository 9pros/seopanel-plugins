<?php
class ManageSubmitter extends ArticleSubmitter {

    // the database table name
    var $tableName_website = "as_websites";
    var $tableName_article = "as_article";
    var $tableName_submit = "as_submit_details";
    var $tableName_project ="as_project";
    var $tableName_skip = "as_skip_websites";
    
    function manageSubmitterDetails($info ='') {            
        $userId = isLoggedIn();
        $articleCtrler = New ManageArticle();
        $articleList = $articleCtrler->__getArticleByUserId($userId);
        $this->set('list', $articleList);
        $this->pluginRender('managesubmitter');
    }
    
    function __getArticleSubmittedList($articleId, $cond = "") {
        $articleId = intval($articleId);
        $sql = "SELECT `website_id` FROM $this->tableName_submit WHERE article_id=$articleId $cond";
        $list = $this->db->select($sql);
        return $list;
    }
    
    function __getArticleSubmitSkippedList($articleId, $cond = "") {
        $articleId = intval($articleId);
        $sql = "SELECT `website_id` FROM $this->tableName_skip WHERE article_id=$articleId $cond";
        $list = $this->db->select($sql);
        return $list;
    }
    
    /**
     * function to submit article to check selected options
     */
    function submitArticle($submitInfo, $userId = false) {
        if(empty($submitInfo['article_id'])) {
            showErrorMsg("Please select an article to proceed.");
        } else {
            
            $articleId = intval($submitInfo['article_id']);
            $userId = !empty($userId) ? intval($userId) : isLoggedIn();
            if (empty($userId)) {
                showErrorMsg("Invalid user.");
            }
            
            // get submitted list
            $dirList = [];
            $list = $this->__getArticleSubmittedList($articleId);
            foreach($list as $listInfo){
                $dirList[] = $listInfo['website_id'];
            }
            
            // get skipped list
            $list = $this->__getArticleSubmitSkippedList($articleId);
            foreach($list as $listInfo){
                $dirList[] = $listInfo['website_id'];
            }
       	    
       	    // get available article directory list for submisison
            $sql = "select * from $this->tableName_website where status=1 and (user_id=$userId or public=1)";
            $sql .= !empty($dirList) ? " and id not in (".implode(',', $dirList).")" : "";            
            $dirInfo = $this->db->select($sql, true);
            
            // if no directory found
            if (empty($dirInfo)) {
                showErrorMsg("No active article directory found.");
            }
            
            $this->set('dirInfo', $dirInfo);
            $this->set('submitInfo', $submitInfo);
            $this->pluginRender('managesubmitterdetails');
        }
    }
    
    /**
     * function to skip website, skiped site will be inserted into as_skip_websites table
     */
    function skipWebsite($info) {
        $info['article_id'] = intval($info['article_id']);
        $info['website_id'] = intval($info['website_id']);
        $sql = "insert into as_skip_websites
                (`website_id`,`article_id`) values ({$info['website_id']}, {$info['article_id']})";
        $this->db->query($sql);
        $this->submitArticle($info);
    }

    /**
     * function to post article to website will access page to submit first
     */
    function submitArticleSite($info){
        
        $articleCtrler = new ManageArticle();
        $articleInfo = $articleCtrler->__getArticleInfo($info['article_id']);
        
        $websiteCtrler = new ManageWebsite();
        $websiteInfo = $websiteCtrler->__getwebsiteInfo($info['website_id']);
        
        if (!empty($articleInfo['id']) && !empty($websiteInfo['id'])) {
            
            // call corresponding helper for submission
            $helperName = $websiteInfo['type'] . "Helper";
            include_once(SP_PLUGINPATH . "/$this->directoryName/helper/$helperName.ctrl.php");
            $helperCtrler = new $helperName();
            $websiteInfo['password'] = base64_decode($websiteInfo['password']);
            $articleInfo['categories'] = $articleInfo['category'];
            $submissionRes = $helperCtrler->submitArticleInfo($websiteInfo, $articleInfo);
                        
            // save submission info
            $this->saveSubmitDetails($articleInfo['id'], $websiteInfo['id'], $submissionRes);
            
            if ($submissionRes['status'] == 'success') {
                showSuccessMsg($submissionRes['msg'], false);
            } else {
                showErrorMsg($submissionRes['msg'], false);
            }
            
            $this->submitArticle(['article_id' => $info['article_id']]);
        } else {
            showErrorMsg("Please provide valid website and article.");
        }
    }

    /**
     * function to save send details to as_submit_details table
     */
    function saveSubmitDetails($articleId, $websiteId, $submissionRes) {
        $articleId = intval($articleId);
        $websiteId = intval($websiteId);
        $submitStatus = addslashes($submissionRes['status']);
        $submitDesc = addslashes($submissionRes['msg']);
        $refId = !empty($submissionRes['ref_id']) ? addslashes($submissionRes['ref_id']) : ""; 
        $sql = "insert into $this->tableName_submit(`article_id`, `website_id`,`submit_status`, `submit_status_desc`, `ref_id`)
               VALUES ($articleId, $websiteId, '$submitStatus', '$submitDesc', '$refId')";
        $this->db->query($sql);
    }

}
?>