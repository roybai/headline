<?php
set_include_path("/headline/process/");
error_reporting(E_ERROR );
require_once("objSavePage.pclass");
set_time_limit(0);
require_once("objSite.pclass");
//$web=new objSavePage('http://www.mitbbs.com/article_t/Fishing/31861193.html');
//$links=$web->save(false);
//exit;

//$buf=file_get_contents_curl('http://www.mitbbs.com/article_t/Fishing/31861193.html');
$sites = new objSite();
$siteList=$sites->getAllHost();
foreach($siteList as $site)
{
    //if($site != 'www.cnn.com') continue;
$web=new objSavePage("http://".$site);
$links=$web->save(true);
}
function test()
{
    echo file_get_contents_curl('www.mitbbs.com/virtual_shape/images/vshape_merge_1.png');
}
