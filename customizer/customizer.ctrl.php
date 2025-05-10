<?php
/**
 * Copyright (C) 2009-2019 www.seopanel.in. All rights reserved.
 * @author Geo Varghese
 *
 */

// include plugins controller if not included
include_once(SP_CTRLPATH.'/seoplugins.ctrl.php');

class Customizer extends SeoPluginsController{

    // plugin settings controller object
    var $settingsCtrler;

    // the plugin text database table
    var $textTable = "texts";

    // the plugin text category
    var $textCategory = "customizer";

    // plugin directory name
    var $directoryName = "customizer";

    /*
     * function to init plugin details before each plugin action
     */
    function initPlugin($data) {

        $this->setPluginTextsForRender($this->textCategory, $this->textTable);
        $this->set('pluginText', $this->pluginText);
        
        if (!defined('PLUGIN_PATH')) {
        	define('PLUGIN_PATH', $this->pluginPath);
        }
     
    }

    /*
     * function to show the first pagewhile access plugin
     */
    function index($data) {
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->blogManage($data);
    }

    /*
     * func to show about us
     */
    function aboutus() {
        print "In about us";        
    }
    
    function sitedetails($data){
        $siteDetailsCtrler = $this->createHelper('Site');
        $siteDetailsCtrler->enterSiteDetails($data);

    }

    function insertdetails($data){
        $siteDetailsCtrler = $this->createHelper('Site');
        $siteDetailsCtrler->insertSiteDetails($data);
    }

    function blogmanagement($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->blogManage($data);
    }

    function newblog($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->addBlogForm($data);
    }

    function insertblogdetails($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->insertBlogDetails($data);
    }

    function editblog($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->editBlogDetails($data);
    }

    function updateblogdetails($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->updateBlogDetails($data);
    }

    function deleteblog($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->deleteBlog($data);
    }

    function deactivateblog($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->deactivateBlog($data);
    }

    function activateblog($data){
        $blogCtrler = $this->createHelper('Blog');
        $blogCtrler->activateBlog($data);
    }

    /* Menu Manager */
    function menumanager($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->menuDetails($data);
    }
  
    function editmenu($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->editMenu($data);
    }

    function updatemenudetails($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->updateMenu($data);
    }

    /* Menu Item Manager */
    function menuitemmanager($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->menuItemDetails($data);
    }

    function newmenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->createMenuItem($data);
    }

    function insertmenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->insertMenuItem($data);
    }

    function editmenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->editMenuItem($data);
    }

    function deletemenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->deleteMenuItem($data);
    }

    function updatemenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->updateMenuItem($data);
    }
    
    function menuTranslatior($data) {
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->menuTranslation($data);
    }

    function menutranslation($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->menuTranslation($data);
    }

    function updatetranslation($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->updateTranslation($data);
    }

    function deactivatemenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->deactivateMenuItem($data);
    }

    function activatemenuitem($data){
        $menuCtrler = $this->createHelper('Menu');
        $menuCtrler->activateMenuItem($data);
    }

    function stylemanager($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->styleManage($data);  
    }
    
    function newstyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->createStyle($data);  
    }

    function insertstyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->insertStyle($data);  
    }

    function deletestyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->deleteStyle($data);  
    }

    function activatestyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->activateStyle($data);  
    }

    function deactivatestyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->deactivateStyle($data);  
    }

    function editstyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->editStyle($data);  
    }

    function updatestyle($data){
        $styleCtrler = $this->createHelper('Style');
        $styleCtrler->updateStyle($data);  
    }

}
?>