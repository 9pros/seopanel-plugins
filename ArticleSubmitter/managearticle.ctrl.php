<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * Description of ManageArticlesctrl
 *
 * @author Raheela Muneer.
 */
class ManageArticle extends ArticleSubmitter {

    // the database table name
    var $tableName = "as_article";
    var $tableName_project = "as_project";
    var $tableName_website = "as_article_post_websites";
    var $tableName_searchengine = "searchengines";

    /**
     * function to show article
     * function fetch saved data from table as_article
     * @param array $info
     */
    function showArticles($info = '') {
        $pgScriptPath = PLUGIN_SCRIPT_URL. "&action=Article";
        $userId = isLoggedIn();
        $this->set('post', $info);

        # to fetch all projects
        $projectCtrler = new ManageProject();
        $cond = " and status=1";
        $cond .= isAdmin() ? "" : " and user_id=".$userId;
        $projectList = $projectCtrler->__getAllProjects($cond);
        $this->set('projectList', $projectList);

        # to fetch articles and their details
        $sql = "select a.*,p.project project_name from $this->tableName a,$this->tableName_project p where a.project_id = p.id";
        $sql .= !empty($info['project_id']) ? " and p.id=". intval($info['project_id']) : "";
        $sql .= !empty($info['keyword']) ? " and title like '%". addslashes($info['keyword']) ."%'" : "";
        $sql .= !isAdmin() ? " and p.user_id=". $userId : "";

        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, 'searchForm', 'scriptDoLoadPost', 'content', '' );
        $this->set('pagingDiv', $pagingDiv);
        
        $sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
        $articleList = $this->db->select($sql);
        $this->set('list', $articleList);
        $this->set('pageNo', $info['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->pluginRender('ManageArticle');
    }
   
    /**
     * function to activate/deactivate article
     */
    function __changeStatus($id, $status){
        $sql = "update $this->tableName set status=".intval($status)." where id=".intval($id);
        $this->db->query($sql);
    }
    
    /**
     * query to fetch data about a project
     */
    function __getArticleInfo($articleId){
        $sql = "SELECT * FROM  $this->tableName WHERE `id`=".intval($articleId);
        $info = $this->db->select($sql, true);
        return $info;
    }
    
    /**
     * function to get all article
     */
    function __getAllArticle($cond = ''){
        $sql = "select * from $this->tableName where 1=1 $cond";
        $articleList = $this->db->select($sql);
        return $articleList;
    }
    
    /**
     * function to get article by project id
     */
    function __getArticleByProjectId($projectId = "") {
        $sql = "select * from $this->tableName where status=1 and project_id = " . intval($projectId);
        $list = $this->db->select($sql);
        return $list;
    }
    
    /**
     * function to get article by user id
     */
    function __getArticleByUserId($userId) {
        $userId = intval($userId);
        $sql = "select a.*,p.project from $this->tableName a, $this->tableName_project p
        		where p.id = a.project_id AND a.status = 1 AND  p.user_id =" . intval($userId);
        $list = $this->db->select($sql);
        return $list;
    }
    
    /**
     * function to edit article, fetch article details
     */
    function editArticle($articleId, $listInfo=''){
        $userId = isLoggedIn();
        if(!empty($articleId)){
            
            if(empty($listInfo)){
                $listInfo = $this->__getArticleInfo($articleId);
            }
   
            $this->set('post', $listInfo);
            $projectCtrler = new ManageProject();
            $cond .= isAdmin() ? "" : " and user_id=".$userId;
            $projectList = $projectCtrler->__getAllProjects($cond);
            $this->set('projectList', $projectList);
            $this->set('sec', 'update');
            $this->pluginRender('editarticle');
        }
    }
    
    function copyArticle($articleId){
        $userId = isLoggedIn();
        if(!empty($articleId)) {
            $listInfo = $this->__getArticleInfo($articleId);
            $this->newArticle($listInfo);
        }
    }
    
    /**
     * function to check article name
     */
    function __checkArticleName($articleName, $projectId, $articletId = false){
        $sql = "select * from $this->tableName where title='".addslashes($articleName)."' and project_id=" . intval($projectId);
        $sql .= !empty($articletId) ? " and id!=".intval($articletId) : "";
        $data = $this->db->select($sql, true);
        return empty($data['id']) ? false :  $data['id'];
    }

    /**
     * function to update article
     */
    function updateArticle($listInfo) {
        $errMsg = [];
        $errMsg['title'] = formatErrorMsg($this->validate->checkBlank($listInfo['title']));
        $errMsg['short_desc'] = formatErrorMsg($this->validate->checkBlank($listInfo['short_desc']));
        $errMsg['article'] = formatErrorMsg($this->validate->checkBlank($listInfo['article']));
        $errMsg['category'] = formatErrorMsg($this->validate->checkBlank($listInfo['category']));
        $errMsg['project_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_id']));
        
        if(!$this->validate->flagErr){
            if($this->__checkArticleName($listInfo['title'], $listInfo['project_id'], $listInfo['id'])){
                $errMsg['title'] = formatErrorMsg('Article name already exists.');
            } else {
                $sql = "UPDATE $this->tableName SET
                      `project_id`='".intval($listInfo['project_id'])."',
                      `title`='".addslashes($listInfo['title'])."',
                      `category`='".addslashes($listInfo['category'])."',
                      `short_desc`='".addslashes($listInfo['short_desc'])."',
                      `article`='".addslashes($listInfo['article'])."'
                       WHERE `id`=".intval($listInfo['id']);
                $this->db->query($sql);
                $this->article(['keyword' => $listInfo['title']]);
                return true;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->editArticle($listInfo['id'], $listInfo);
    }
    
    /**
     * function to delete article
     */
    function deleteArticle($articleId){
        $sql = "delete from $this->tableName where id=".intval($articleId);
        $this->db->query($sql);
    }
    
    /**
     * function to new article
     */
    function newArticleList($info=''){
        $userId = isLoggedIn();
        $projectCtrler = new ManageProject();
        $cond .= isAdmin() ? "" : " and user_id=".$userId;
        $projectList = $projectCtrler->__getAllProjects($cond);      
        $this->set('projectList', $projectList);
        $this->set('sec', 'create');
        $this->set('post', $info);
        $this->pluginRender('editarticle');
    }

    /**
     * function to save new article to table
     */
    function createArticleList($listInfo){
        $errMsg = [];
        $errMsg['title'] = formatErrorMsg($this->validate->checkBlank($listInfo['title']));
        $errMsg['short_desc'] = formatErrorMsg($this->validate->checkBlank($listInfo['short_desc']));
        $errMsg['article'] = formatErrorMsg($this->validate->checkBlank($listInfo['article']));
        $errMsg['category'] = formatErrorMsg($this->validate->checkBlank($listInfo['category']));
        $errMsg['project_id'] = formatErrorMsg($this->validate->checkBlank($listInfo['project_id']));
        
        if(!$this->validate->flagErr){
            if($this->__checkArticleName($listInfo['title'], $listInfo['project_id'])){
                $errMsg['title'] = formatErrorMsg('Article name already exists.');
            } else {
                $sql = "INSERT INTO `as_article`(`project_id`, `title`,`category`, `short_desc`, `article`, `status`) VALUES
                ('".intval($listInfo['project_id'])."', '".addslashes($listInfo['title'])."',  '".addslashes($listInfo['category'])."', '".addslashes($listInfo['short_desc'])."',
                '".addslashes($listInfo['article'])."', '1')";
                $this->db->query($sql);
                $this->article(['keyword' => $listInfo['title']]);
                exit;
            }
        }
        
        $this->set('errMsg', $errMsg);
        $this->newArticleList($listInfo);
    }
}
?>
