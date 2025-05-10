<?php
class ManageSkip extends ArticleSubmitter {
    var $tableName_skip = "as_skip_websites";

    function showSkipDetails($info = '') {            
        $pgScriptPath = PLUGIN_SCRIPT_URL. "&action=skippedSubmittions";
        $userId = isLoggedIn();

        // to fetch all articles
        $articleCtrler = New ManageArticle();
        $articleList = $articleCtrler->__getArticleByUserId($userId);
        $this->set('articleList', $articleList);

        // to fetch skipped articles and their details
        $sql = "select s.*,w.website_name, a.title
                from as_skip_websites s, as_article a, as_websites w 
                WHERE s.website_id = w.id AND s.article_id = a.id";
        
        if(!empty($info['article_id'])){
            $articleId = intval($info['article_id']);
            $pgScriptPath .= "&article_id=".$articleId;
            $sql .= " and a.id=".$articleId;
            $this->set('articleId', $articleId);
        }
        
        $this->db->query($sql, true);
        $this->paging->setDivClass('pagingdiv');
        $this->paging->loadPaging($this->db->noRows, SP_PAGINGNO);
        $pagingDiv = $this->paging->printPages($pgScriptPath, '', 'scriptDoLoad', 'content', 'layout=ajax');
        $this->set('pagingDiv', $pagingDiv);

        $sql .= " order by id limit ".$this->paging->start .",". $this->paging->per_page;
        $skipList = $this->db->select($sql);
        $this->set('list', $skipList);
                 
        $this->set('pageNo', $_GET['pageno']);
        $this->set('pgScriptPath', $pgScriptPath);
        $this->pluginRender('manageskip');
    }

    function Unskip($info = ''){              
       $skipid = intval($info['skipid']);
       $sql = "delete from $this->tableName_skip where id = $skipid";
       $this->db->query($sql);
       $this->showSkipDetails($info);
    }
}
?>