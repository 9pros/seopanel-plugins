<?php
class ManageReports extends ArticleSubmitter {

    var $tableName = "as_submit_details";

    function viewReports($info = '') {
        $userId = isLoggedIn();
        $projectCtrler = new ManageProject();
        $cond = "and status=1";
        $cond .= isAdmin() ? "" : " and user_id=" . $userId;
        $projectList = $projectCtrler->__getAllProjects($cond);
        $this->set('projectList', $projectList);
        
        if (empty($projectList)) {
            showErrorMsg("No active projects found");
        }
        
        $projectId = !empty($info['project_id']) ? intval($info['project_id']) : $projectList[0]['id'];
        $this->set('projectId', $projectId);

        // To fetch all articles
        $articleCtrler = new ManageArticle();
        $articleList = $articleCtrler->__getArticleByProjectId($projectId);
        $this->set('articleList', $articleList);

        // To fetch all Websites
        $websiteCtrler = new ManageWebsite();
        $cond = "and status=1 and (user_id=$userId or public=1)";
        $websiteList = $websiteCtrler->__getAllWebsiteList($cond);
        $this->set('websiteList', $websiteList);
        
		$fromTime = !empty($info['from_time']) ? $info['from_time'] : date('Y-m-d', strtotime('-7 days'));
		$toTime = !empty($info['to_time']) ? $info['to_time'] : date('Y-m-d');
        $this->set('fromTime', $fromTime);
        $this->set('toTime', $toTime);

        $pgScriptPath = PLUGIN_SCRIPT_URL . "&action=report";
        $sql = "select s.*,w.website_name, a.title
		        from $this->tableName s, as_article a, as_websites w
		        WHERE s.website_id = w.id AND s.article_id = a.id 
		        and a.project_id=$projectId and s.submit_time>='".addslashes("$fromTime 00:00:00")."'
		        and s.submit_time<='" . addslashes("$toTime 23:59:59") . "'";
        
        $sql .= !empty($info['article_id']) ? " and s.article_id=".intval($info['article_id']) : "";
        $sql .= !empty($info['site_id']) ? " and s.website_id=".intval($info['site_id']) : "";
        	
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'search_form', 'scriptDoLoadPost', 'content', '' );
        $this->set('pagingDiv', $pagingDiv);
        
        $sql .= " order by id limit " . $this->paging->start . "," . $this->paging->per_page;
        $report = $this->db->select($sql);
        
        $this->set('report', $report);
        $this->set('pageNo', $info['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->set('post', $info);
        
        $this->pluginRender('viewreports');
    }
    
    function removeSubmission($info) {
        if (!empty($info['id'])) {
            $submissionId = intval($info['id']);
            $this->dbHelper->deleteRows($this->tableName, "id=$submissionId");
        }
        
        $this->viewReports($info);
    }

    /**
     * function to show status select box
     */
    function showArticleSelectBox($projectId) {
        $articleCtrler = new ManageArticle();
        $articleList = $articleCtrler->__getArticleByProjectId($projectId);
        $this->set('articleList', $articleList);
        $this->pluginRender('showarticleselectbox');
    }

    /**
     * function to fetch report details by by
     */
    function _getReportByID($id) {
        $id = intval($id);
        $sql = "select * from $this->tableName where id = $id";
        $info = $this->db->select($sql, true);
        return $info;
    }

    /**
     * function to update confirmation in as_submit_details
     */
    function updateStatus($id) {
        $pluginCtrler = new SeoPluginsController();
        $pluginText = $pluginCtrler->getPluginLanguageTexts('as', $_SESSION['lang_code'], 'as_texts');
        $status = $pluginText['Approved'];
        $id = intval($id);
        $sql = "Update $this->tableName set confirmation ='$status' where id = '$id'";
        $this->db->query($sql);
    }

    /**
     * function to fetch confirmation column fron table
     * fetch data from as_submit_details
     */
    function showStatus($id) {
        $id = intval($id);
        $sql = "select confirmation from $this->tableName where id=" . $id;
        $statusInfo = $this->db->select($sql, true);
        echo $statusInfo['confirmation'];
    }

    /**
     * function to check status of article posted
     */
    function checkstatus($info = '') {
        if (! empty($info)) {
            $reoprtInfo = $this->_getReportByID($info);
            // fetch website information
            $websiteCtrler = new ManageWebsite();
            $websiteInfo = $websiteCtrler->__getwebsiteInfo($reoprtInfo['website_id']);
            // domain
            $domain = $websiteInfo['domain'];
            $mysubmittion = $domain . "/index.php?uid=1";
            // fetch Article information
            $articleCtrler = new ManageArticle();
            $articleInfo = $articleCtrler->__getArticleInfo($reoprtInfo['article_id']);
            // Article Title
            $articleTitle = $articleInfo['title'];
            // curl
            $spider = new Spider();
            $spider->_CURLOPT_HEADER = 1;
            // if authentication exist
            if ($websiteInfo['authentication'] == '1') {
                $cookieFile = SP_TMPPATH . "/" . AS_COOKIE_FILE;
                system("> $cookieFile");
                $url = $websiteInfo['domain'] . "/login.php";
                $username = $websiteInfo['username'];
                $password = $websiteInfo['password'];
                $password = base64_decode($password);
                $postdata = "user=" . $username . "&pass=" . $password . "&formSubmitted=" . "1&login=Login";
                // function to login to site and stores cookie in cookie file
                $submitterCtrler = new ManageSubmitter();
                $curlReturnValue = $submitterCtrler->curlToFetch($url, $postdata, $cookieFile);
                $page = $submitterCtrler->getPageData($cookieFile, $mysubmittion);
            } else {
                // no authentication required
                $ret = $spider->getContent($mysubmittion);
                $page = $ret['page'];
            }

            if (! empty($page)) {
                if (stristr($page, $articleTitle)) {
                    return 1;
                }
            }
            return 0;
        }
    }

    /**
     * function to fetch report by site id and comfirmation
     */
    function _getReportBySiteId($id) {
        $pluginCtrler = new SeoPluginsController();
        $pluginText = $pluginCtrler->getPluginLanguageTexts('as', $_SESSION['lang_code'], 'as_texts');

        $confirmation = $pluginText['Pending'];
        $id = intval($id);
        $sql = "SELECT * FROM $this->tableName WHERE `website_id`='$id' AND `confirmation`='$confirmation'";
        $info = $this->db->select($sql);
        return $info;
    }

    /**
     * function to check status using cron
     */
    function startCheckStatusJOb($info = '') {
        if (! empty($info)) {
            // To fetch site details
            $websiteCtrler = new ManageWebsite();
            $websiteInfo = $websiteCtrler->__getAllWebsiteList();
            if (! empty($websiteInfo)) {
                foreach ($websiteInfo as $wInfo) {
                    // to fetch report
                    $reportInfo = $this->_getReportBySiteId($wInfo['id']);
                    if (! empty($reportInfo)) {
                        foreach ($reportInfo as $rInfo) {
                            $reportId = $rInfo['id'];
                            // to chech status
                            $statusReturn = $this->checkstatus($reportId);
                            if ($statusReturn == '1') {
                                $this->updateStatus($reportId);
                            }
                        }
                    }
                }
            }
        }
    }
}
?>