<?php
require_once("process/variables.php");

/*
function get_file_time($full_url,$timeout=600)
{

    ini_set("user_agent", "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5");

    if(function_exists('curl_init')) {
        $handle = curl_init($full_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_NOBODY, TRUE);
        curl_setopt($handle, CURLOPT_TIMEOUT,$timeout);
        $parsed=parse_url($full_url);
        if($parsed['scheme']=='https')
        {
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        }
        $response = curl_exec($handle);
        $info=curl_getinfo($handle);
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if($httpCode == 200)
            return $response;
        else
            return null;

    }
    return @file_get_contents($full_url);
}
*/
    function get_hash($buffer)
    {
        $r=hash("md5",$buffer);
        return substr($r,0,19);
    }
//  this function is used by curl_setopt ($handle, CURLOPT_WRITEFUNCTION, 'write_function');
    function write_function($handle, $data) {
        /*
        if (strlen($data) > 10240000) {
            echo "too large<br>";
            return 10240;
        }
        else
        */
        {
            echo "got ".strlen($data)."<br>";
            return $data;
        }
    }
/*
 * file_get_contents() curl version
 */
    function file_get_contents_curl ($full_url,$timeout=TIME_OUT)
    {
        ini_set("user_agent", "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5");

        if(function_exists('curl_init')) {
            $handle = curl_init($full_url);
            echo "getting ".$full_url."<br>";
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($handle, CURLOPT_TIMEOUT,$timeout);
            curl_setopt($handle, CURLOPT_NOPROGRESS, false);
            curl_setopt ($handle, CURLOPT_PROGRESSFUNCTION,

                function($DownloadSize, $Downloaded, $UploadSize, $Uploaded){
                    // If $Downloaded exceeds 1KB, returning non-0 breaks the connection!
//                    echo $Downloaded.'<br>';
                    return ($Downloaded > (MAX_SIZE)) ? 1 : 0;
                });

            $parsed=parse_url($full_url);
            if($parsed['scheme']=='https')
            {
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            }
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            curl_close($handle);
            if($httpCode == 200)
                return $response;
            else
                return null;

        }
        return @file_get_contents($full_url);
    }
/* //moved into objLoadPage.pclass
    function update_page_links_by_resourceid($buffer,$allLinksFromDB)
    {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

        $regexp1= "href=(\"??)([^\" >]*?\\1)";

        preg_match_all("/$regexp/siU", $buffer, $matches);
        $pieces=preg_split("/$regexp/siU",$buffer);

        $ret=null;

        foreach($matches[0] as $k=>$link)
        {
            $l=preg_split("/$regexp1/siU",$link);
            $found=0;
            foreach($allLinksFromDB as $key=>$value)
            {
                if($value[0]===$matches[2][$k])
                {
                    $new=$l[0]."href=\"/headline/showpage.php?pid={$value[1]}\"".$l[1];
                    $ret.=$pieces[$k].$new;
                    $found=1;
                    break;
                }
            }
            if($found==0)
            {
                $ret.=$pieces[$k].$matches[0][$k];
                $found=0;
            }
        }
        $ret.=$pieces[sizeof($pieces)-1];

        return $ret;
    }
*/
    function update_page_links_by_full_url($buffer,$allLinksFromDB,$hostname)
    {
        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

        $regexp1= "href=(\"??)([^\" >]*?\\1)";

        preg_match_all("/$regexp/siU", $buffer, $matches);
        $pieces=preg_split("/$regexp/siU",$buffer);

        $ret=null;

        foreach($matches[0] as $k=>$link)
        {
                $l=preg_split("/$regexp1/siU",$link);

                $new=$l[0]."href=\"".get_full_url($matches[2][$k],$hostname)."\"".$l[1];

                    $ret.=$pieces[$k].$new;
        /*
            $l=preg_split("/$regexp1/siU",$link);
            $found=0;
            foreach($allLinksFromDB as $key=>$value)
            {
                if($value[0]===$matches[2][$k])
                {

                    $new=$l[0]."href=\"".get_full_url($value[0],$hostname)."\"".$l[1];
                    $ret.=$pieces[$k].$new;
                    $found=1;
                    break;
                }
            }
            if($found==0)
            {
                $ret.=$pieces[$k].$matches[0][$k];
                $found=0;
            }
*/
        }
        $ret.=$pieces[sizeof($pieces)-1];

        return $ret;
    }

function saveMyFile($filename,$buffer)
{
//    $filename="/tmp/".$filename;
    $fp=fopen($filename,"w");
    if(!$fp) return false;
    fwrite($fp,$buffer,strlen($buffer));
    fclose($fp);
    return true;
}
function _echo($str)
{
    if($_SERVER['HTTP_USER_AGENT'])
        echo date("i:s: ").$str."<br>";
    else
        echo date("i:s: ").$str."\n";
}

function update_css_links_by_resourceid($buffer,$links)
{
    $pieces=array();
    foreach($links as $id=>$value)
    {
       $newLink='/headline/css/'.$id;
       $buffer=str_replace($value[0],$newLink,$buffer);
    }

    return $buffer;
}

function getCondition ($hay)
{
    $condition=null;
    foreach($hay as $needle)
    {
        if($condition==null)
            $condition="\"$needle\"";
        else
            $condition=$condition.",\"$needle\"";
    }
    return "(".$condition.")";
}

function update_image_links_by_resourceid($buffer,$allLinksFromDB)
{
    $regexp = '<img\s[^>]*src=([\'\"]??)([^\' >\"]*?)\\1[^>]*>';

    $regexp1=  "src=(\"??)([^\" >]*?\\1)";

    preg_match_all("/$regexp/siU", $buffer, $matches);
    $pieces=preg_split("/$regexp/siU",$buffer);

    $ret=null;

    foreach($matches[0] as $k=>$link)
    {
        $l=preg_split("/$regexp1/siU",$link);
        $found = false;
        foreach($allLinksFromDB as $key=>$value)
        {
            if($value[0]===$matches[2][$k])
            {
                $new=$l[0]."src=\"/headline/image/{$key}\"".$l[1];
                $ret.=$pieces[$k].$new;
                $found = true;
                break;
            }
        }
        if($found == false)
        {
            $ret .= $pieces[$k].$matches[0][$k];
        }
    }
    $ret.=$pieces[sizeof($pieces)-1];

    return $ret;
}
/*
 * extract <a href link from html
 */
    function parse_links_from_html($buffer)
    {
        if($buffer==null) return null;

        $regexp = "<a\s[^>]*href=(\"??)([^\"\' >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
        {
            return $matches[2];

        }
        return null;
    }
/*
 * extract image link from html
 */
    function parse_image_from_html($buffer)
    {
        if($buffer==null) return null;

        $regexp = '<img\s[^>]*src=([\'\"]??)([^\' >\"]*?)\\1[^>]*>';
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
        {
            return $matches[2];
            // $matches[2] = array of link addresses
            // $matches[3] = array of link text - including HTML code
        }
        return null;
    }
/*
 * extract script from html
 */

    function parse_script_from_html($buffer)
    {
        if($buffer==null) return null;

        $regexp = '<script\s[^>]*src=([\'\"]??)([^\' >\"]*?)\\1[^>]*>';
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
        {
            return $matches[2];
            // $matches[2] = array of link addresses
            // $matches[3] = array of link text - including HTML code
        }
        return null;
    }
/*
 * extract css link from html
 */
    function parse_csslink_from_html1($buffer)
    {

    }
    function parse_csslink_from_html($buffer)
    {
        if($buffer==null) return null;

        $regexp1 = '<link\s[^>]*href=([\'\"]??)([^\' >\"]*?)\\1[^>]*>';
        $regexp2 = '<link\s[^>]*rel=([\'\"]??)([^\' >\"]*?)\\1[^>]*>';

        $ret=array();

        if(preg_match_all("/$regexp1/siU", $buffer, $matches1))
        {
            //check all the matching links and find those with text/css
            foreach($matches1[0] as $key=>$match1)
            {
                if(preg_match_all("/$regexp2/siU", $match1, $matches2))
                {
                    if(strstr(strtolower($matches2[2][0]),'stylesheet'))
                    {
                        $ret[]=$matches1[2][$key];
                    }
                }
            }
            return $ret;
            // $matches[2] = array of link addresses
            // $matches[3] = array of link text - including HTML code
        }
        return null;
    }
/*
 * extract title text from html
 */
    function parse_txtTitle_from_html($buffer,$type=null)
    {
        $regexp = '<title>(.*)<\/title>';
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
            return $matches[1];
        return null;
    }
/*
 * extract href txt from html
 */
    function parse_txtLInk_from_html($buffer)
    {

        $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
        {
//            $ret=implode('\n',$matches[3]);
            $ret=array();
            foreach($matches[3] as $match)
            {
                $match=parse_txtBody_from_html($match);
                $ret[]=$match;
            }
            return $ret;
        }
        return null;
    }

/*
 * extract text from html
 */
    function parse_txtBody_from_html ($buffer)
    {
        $buffer=addslashes($buffer);

        //get rid of <script>
        $buffer="</script>".$buffer."<script>";
        $regexp = '<\/script>(.*)<script>?';
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
            $buffer=implode("",$matches[1]);

        //get rid of <style>
        $buffer="</style>".$buffer."<style>";
        $regexp = '<\/style>(.*)<style>?';
        if(preg_match_all("/$regexp/siU", $buffer, $matches))
            $buffer=implode("",$matches[1]);

        $len=strlen($buffer);
        $ret=null;
        $in_quote=false;
        $in_tag=false;
        for($i=0;$i<$len;$i++)
        {
            $skip_tag=false;
            switch($buffer[$i])
            {
                case '\"':
                    $in_quote=!$in_quote;
                    break;
                case '>':
                    if(!$in_quote)
                    {
                        $in_tag=false;
                        $skip_tag=true;
                    }

                    break;
                case '<':
                    if(!$in_quote)
                    {
                        $in_tag=true;
                        $skip_tag=true;
                    }
                    break;
            }
            if(!$in_tag && !$skip_tag)
                $ret=$ret.$buffer[$i];
        }
        return $ret;
    }

/*
 *

function search($start,$end,$string, $borders=true){
    $reg="!".preg_quote($start)."(.*?)".preg_quote($end)."!is";
    preg_match_all($reg,$string,$matches);

    if($borders) return $matches[0];
    else return $matches[1];
}
*/
//make a SQL condition like IN(...,...)
function makeSQLCondition($values)
{
    $condition=null;
    foreach($values as $v)
    {
        if($condition===null) $condition="'".$v."'";
        else $condition=$condition.",'".$v."'";
    }
    return $condition;
}

function getTableFromType ($type)
{
    switch($type)
    {
        case _PAGE_TYPE_RESOURCE:
            return 'PAGE';
        case _LINK_TYPE_RESOURCE:
            return 'ALINK';
        case _TEXT_TYPE_RESOURCE:
            return 'TEXT';
        case _IMAGE_TYPE_RESOURCE:
            return 'IMAGE';
        case _CSS_TYPE_RESOURCE:
            return 'CSSLINK';
        case _TXT_TYPE_RESOURCE:
            return 'TXT';
        case _VIDEO_TYPE_RESOURCE:
            return NULL;

    }
    return NULL;
}


    function get_full_url ($url,$host)
    {

        $url=trim($url);
        if($url[0] != "/" && !strstr(strtolower($url), "http://") && !strstr(strtolower($url), "https://")) $url="/".$url;
        $r=parse_url($url);
        if(isset($r['host'])===false)
            $url="http://".$host.$url;
        return $url;
    }
