<?php
class seopanelHelper extends ManageSubmitter {
    var $pageScript = "blog.php?id=";
    
    function submitArticleInfo($websiteInfo, $articleInfo) {
        $subResult = ['status' => 'failed', 'msg' => 'Internal error occured.'];
        
        // check whether plugin is installed or not
        if (isPluginActivated("Customizer")) {
        	$currTime = date('Y-m-d H:i:s');
        	$sql = "insert into cust_blogs(blog_title,blog_content,meta_title,meta_description,meta_keywords,
        			tags, link_page,status,created_time,updated_time)
					values('".addslashes($articleInfo['title'])."', '".addslashes($articleInfo['article'])."',
					'".addslashes($articleInfo['title'])."', '".addslashes($articleInfo['short_desc'])."','".
					addslashes($articleInfo['category'])."', '".addslashes($articleInfo['category'])."', 
					'', 1, '".$currTime."', '".$currTime."');";
			
			$this->db->query($sql);
			$blogId = $this->db->getMaxId('cust_blogs');

        	$blogLink = $this->getArtcileLink($websiteInfo['website_url'], $blogId);
        	$subResult = [
                'status' => 'success', 
        	    'msg' => "Blog posted successfully: <a href='$blogLink' target='_blank'>$blogLink</a>",
        	    'ref_id' => "$blogId",
        	];
        } else {
        	$subResult['msg'] = "Customizer plugin is not activated or installed.";
        }
        
        return $subResult;
    }
    
    function getArtcileLink($blogUrl, $blogId) {
        return Spider::addTrailingSlash($blogUrl) . $this->pageScript . $blogId;
    }    
}
?>