<?php

/***************************************************************************
 *   Copyright (C) 2009-2011 by Geo Varghese(www.seofreetools.net)  	   *
 *   sendtogeo@gmail.com   												   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

$dirpath = realpath(dirname(__FILE__));
$spLoadFile = "$dirpath/../../includes/sp-load.php";
$spLoadFileExist = true;
if (!file_exists($spLoadFile)) {
	$dirpath = str_ireplace('/plugins/directoryimporter/reportcron.php', '', $_SERVER['SCRIPT_FILENAME']);
	$spLoadFile = $dirpath."/includes/sp-load.php";
	$spLoadFileExist = !file_exists($spLoadFile) ? false : true;
}

// check whether sp load file exists
if ($spLoadFileExist) {
	include_once($spLoadFile);

	if(empty($_SERVER['REQUEST_METHOD'])){
	
	    include_once(SP_CTRLPATH."/seoplugins.ctrl.php");
	    $controller = New SeoPluginsController();
	
	    $pluginInfo = $controller->__getSeoPluginInfo('directoryimporter', 'name');
	    $info['pid'] = $pluginInfo['id'];
	    $info['action'] = "crondirchecker";     
	    $controller->manageSeoPlugins($info, 'get', true);
	}else{
		showErrorMsg("<p style='color:red'>You don't have permission to access this page!</p>");	
	}
} else {
    echo "Seo Panel Bootstrap loader file not accessible!";
}
?>