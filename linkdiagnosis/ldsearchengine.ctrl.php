<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese 
 * 
 */

class LDSearchEngine extends LinkDiagnosis{

	/*
	 * show search engine list to manage
	 */
	function showSearchEngineManager($info='') {
		$seList = $this->dbHelper->getAllRows("ld_seconfig");
		$this->set('list', $seList);
		$this->set('spTextSE', $this->getLanguageTexts('searchengine', $_SESSION['lang_code']));
		$this->pluginRender('show_search_engine_manager');
	}
	
	/*
	 * func to change status
	 */ 
	function __changeStatus($seId, $status){
		$seId = intval($seId);
		$dataList = array('status|int' => $status);
		$this->dbHelper->updateRow("ld_seconfig", $dataList, "id=$seId");
	}
	
}