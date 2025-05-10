<?php
/**
 * Description of ManageSpinnerctrl
 *
 * @author Raheela Muneer.
 */
class ManageSpinner extends ArticleSubmitter {
	

	/**
	 * function to show manage spinner
	 * @param array $info
	 */
	function showManageSpinner($info = ''){
		include_once(SP_CTRLPATH."/searchengine.ctrl.php");
		$seController = New SearchEngineController();
		$info = $seController->__getAllCrawlFormatedSearchEngines();
		$this->set('list', $info);
		$this->pluginRender('ManageSpinner');
	}	

    /**
     * function to fetch data from search engine and display
     * @param mixed $info
     */
    function articleChecker($info='') { 
    	$errMsg = [];
        $errMsg['searchengine'] = formatErrorMsg($this->validate->checkBlank($info['searchengine']));
        $errMsg['keyword'] = formatErrorMsg($this->validate->checkBlank($info['keyword']));
        if($this->validate->flagErr){
        	showErrorMsg("Please enter valid keyword to search.");
        }
        
        $keywordInfo = [];
        $searchEngineId = intval($info['searchengine']);
    	$keywordInfo['searchengines'] = $searchEngineId;
    	$keywordInfo['name'] = $info['keyword'];
    	$keywordInfo['url'] = "search";
    	
    	include_once(SP_CTRLPATH."/report.ctrl.php");
    	$reController = New ReportController();
        $seController = New SearchEngineController();
        $reController->seList = $seController->__getAllCrawlFormatedSearchEngines();
        $reController->showAll = true;
    	$crawlResult = $reController->crawlKeyword($keywordInfo, $info['searchengine']);

    	// for testing
        /*$crawlResult[1]['status'] = 1;
        $crawlResult[1]['matched'] = [
        	['rank' => 1, 'title' => 'hello how are you', 'description' => "what you doing here and how are you"],
        	['rank' => 3, 'title' => 'hello how are you 2', 'description' => "what you doing here and how are you2"],
        ];*/
        
    	$resultList = array();
    	if(!empty($crawlResult[$searchEngineId]['status'])){
    		$resultList = $crawlResult[$searchEngineId]['matched'];
    		$this->set('resultList', $resultList);
			$this->pluginRender('SpinnerResults');
    	} else {
    		showErrorMsg($_SESSION['text']['common']['No Records Found']);
    	}
    }    
    
    function saveArticle($info){
    	$errMsg['article'] = formatErrorMsg($this->validate->checkBlank($info['article']));
    	if($this->validate->flagErr){
    		$this->set('errMsg', $errMsg);
    		$this->showManageSpinner($info);
    	} else {
    		$manageArticleObj = $this->createHelper("ManageArticle");
    		$manageArticleObj->newArticleList($info);
    	}
    }    
}
?>