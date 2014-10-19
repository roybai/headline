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
    if($site[1] != 'www.mitbbs.com') continue;
    $web=new objSavePage("http://".$site[1], 0);
    if($web->status=="OK")
        $links=$web->save(true);

    $db = new objDBSQL();
    $db->update_version($web);
    $db->update_site($web);
}
