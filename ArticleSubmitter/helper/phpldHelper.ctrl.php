<?php
class phpldHelper extends ManageSubmitter {

    /**
     *
     * function to login to the site
     *
     * @param string $url
     * @param string $postdata
     * @param string $cookieFile
     * @param string $request
     * @return int
     */

    function curlToFetch($url, $postdata = "",$cookieFile, $request = "POST"){  
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
        ob_start();
        $result =  curl_exec ($ch);
        ob_end_clean();
        curl_close ($ch);
        unset($ch);
        $string = '/No permissions set for this user/';
        if(preg_match($string, $result,$matches)){
            return "No permissions set for this user";
        }else{
            return 1;
        }
    }


    /**
     *
     * function to get category
     *
     * @param string $cookieFile
     * @param string $url
     * @return string
     */
    function getPageData($cookieFile,$url){ 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = curl_exec ($ch);

        curl_close ($ch);
        return $result;
    }

    /**
     *
     * curl function to post article
     *
     * @param string $url
     * @param string $postdata
     * @param string $cookieFile
     * @param string $request
     * @return string|int
     */
    function curlOperation($url, $postdata = "",$cookieFile, $request = "POST"){
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt ($ch, CURLOPT_POST, true);
        curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
        curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_TIMEOUT, 15);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);

        $result['page'] = curl_exec ($ch);
        echo $result['page'];
        $string = '/No permissions set for this user/';
        if(preg_match($string, $result['page'],$matches)){
            echo  curl_error($ch);
            curl_close($ch);
            return "No permissions set for this user";
        }else {
            $string = "/Article submitted/";
            if(preg_match($string, $result['page'],$matches)){
                echo  curl_error($ch);
                curl_close($ch);
                return "Article submitted";
            }else {
                echo  curl_error($ch);
                curl_close($ch);
                return 1;
            }
        }

    }

    /**
     *
     * function to access the page before posting article
     * @param string $url
     * @param string $cookieFile
     */
    function accessCurlPage($url,$cookieFile){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_URL,$url);
        $result = curl_exec ($ch);
        curl_close ($ch);
    }

    /**
     *
     * function to post article to website
     * will access page to submit firsy
     * @param array $info
     */
    function submitArticleSite($info){
        $articleCtrler = new ManageArticle();
        $articleList = $articleCtrler->__getArticleInfo($info['articleid']);  // fetch information about the article
        $websiteCtrler = new ManageWebsite();
        $websiteList = $websiteCtrler->__getwebsiteInfo($info['websiteid']);// fetch information about the site

        $cookieFile = SP_TMPPATH . "/" . AS_COOKIE_FILE;
        $url = $websiteList['website_url'];
        
        // To access page to submit
        $this->accessCurlPage($url, $cookieFile);
        $postdata = "TITLE=" .$articleList['title']. "&DESCRIPTION=" .$articleList['short_desc']. "&DESCRIPTION_limit=" . "0&ARTICLE=" .$articleList['article']."&CATEGORY_ID=" ."1&AGREERULES=" ."1&continue=" . "Continue&formSubmitted=" . "1&id=" . "0";

        // to perform article posting
        $curlReturnValue = $this->curlOperation($url,$postdata,$cookieFile);

        if($curlReturnValue != "1"){
            $this->saveSubmitDetails($articleList['id'], $websiteList['id'], "Success", $curlReturnValue);
            $this->showArticleSubmitterError($curlReturnValue);
        }else{
            $curlReturnValue = "Article submittion failed";
            $this->saveSubmitDetails($articleList['id'], $websiteList['id'], "Failed", $curlReturnValue);
            $this->showArticleSubmitterError($curlReturnValue);
        }
    }

}
?>