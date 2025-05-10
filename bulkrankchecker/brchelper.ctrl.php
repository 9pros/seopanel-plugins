<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class BRCHelper extends BulkRankChecker {
	
    /*
     * function to update data list of campaign[keyword/link/searchengines]
     */
	function updateCampaignDataLists($section, $campaignId, $dataList) {
	    
	    switch ($section) {
	        
	        case "keyword":
	            $table = "brc_keywords";
	            $column = "keyword";
	            break;
	        
	        case "link":
	            $table = "brc_links";
	            $column = "url";
	            break;
	        
	        case "searchengine":
	            $table = "brc_searchengines";
	            $column = "searchengine_id";
	            break;
	        
	    }
	    
	    // to check at last whetehre any keyword is deleted 
	    $campaignId = intval($campaignId);
		$sql = "update $table set status=0 where campaign_id=$campaignId";
	    $this->db->query($sql);
	    
	    // insert data to the corresponding table
	    foreach ($dataList as $data) {
            $data = addslashes(trim($data));
            if (!empty($data)) {
                $sql = "Insert into $table(campaign_id, $column) values($campaignId, '$data') ON DUPLICATE KEY UPDATE status=1";
	            $this->db->query($sql);
            }	        
	    }
	    
	    // if section is keyword, delete all search search results of keywords whi deleted
	    if ($section == "keyword") {
	    	$sql = "delete sr.* from $table k, brc_searchresults sr where k.id=sr.keyword_id and k.status=0 and k.campaign_id=$campaignId";
		    $this->db->query($sql);
	    } 	    

	    // delete all rows with status 0
	    $sql = "delete from $table where campaign_id=$campaignId and status=0";
		$this->db->query($sql);
	    
	}
	
	 /*
     * function to get data list of campaign[keyword/link/searchengines]
     */
	function getCampaignDataLists($section, $campaignId, $activeCheck = false) {
	    
        switch ($section) {
	        
	        case "keyword":
	            $table = "brc_keywords";
	            $column = "keyword";
	            break;
	        
	        case "link":
	            $table = "brc_links";
	            $column = "url";
	            break;
	        
	        case "searchengine":
	            $table = "brc_searchengines";
	            $column = "searchengine_id";
	            break;
	        
        }
	    
        $campaignId = intval($campaignId);
	    $sql = "select * from $table where campaign_id=$campaignId"; 
	    $sql .= $activeCheck ? " and status=1" : ""; 
	    $list = $this->db->select($sql);
	    $dataList = array();
	    foreach ($list as $listInfo) {
	        $dataList[$listInfo['id']] = $listInfo[$column];
	    }
            
	    return $dataList;
	}
	
	 /*
     * function to get list of keywords without reports
     */
	function getKeywordsWithOutReports($campaignId, $lastGenerated, $limit = 0) {  
	    
		$campaignId = intval($campaignId);
		$limit = intval($limit);
		$lastGenerated = addslashes($lastGenerated);
	    $sql = "select distinct keyword_id from brc_searchresults where campaign_id=$campaignId and `time`>'$lastGenerated'";
	    $list = $this->db->select($sql);
	    $keywordList = array(0);
	    foreach ($list as $info) {
	        $keywordList[] = $info['keyword_id'];
	    }
	    
	    $sql = "select * from brc_keywords where id not in(".implode(', ', $keywordList).") 
	    AND `campaign_id`=$campaignId and status=1 order by keyword";
	    $sql .= empty($limit) ? "" : " limit 0, $limit";
	    $list = $this->db->select($sql);
	    $dataList = array();
	    foreach ($list as $listInfo) {
	        $dataList[$listInfo['id']] = $listInfo['keyword'];
	    }
	    
	    return $dataList;
	}
	
	/*
	 * function to delete all search results of a campaign
	 */
	function deleteAllSearchResults($campaignId, $date = false) {
		$date = addslashes($date);
	    $sql = "delete from brc_searchresults where campaign_id=".intval($campaignId);
	    $sql .= empty($date) ? "" : " and time>='$date' and time<='$date'";
	    $this->db->query($sql);
	}
	
	/*
	 * function to delete all keywords of a campaign
	 */
	function deleteAllKeywords($campaignId) {
	    $sql = "delete from brc_keywords where campaign_id=".intval($campaignId);
	    $this->db->query($sql);
	}
	
	/*
	 * function to delete all links of a campaign
	 */
	function deleteAllLinks($campaignId) {
	    $sql = "delete from brc_links where campaign_id=".intval($campaignId);
	    $this->db->query($sql);
	}
	
	/*
	 * function to delete all search engines of a campaign
	 */
	function deleteAllSearchEngines($campaignId) {
	    $sql = "delete from brc_searchengines where campaign_id=".intval($campaignId);
	    $this->db->query($sql);
	}
    
}
?>