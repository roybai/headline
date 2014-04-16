<?php


require_once("objLoadPage.pclass");
$siteID = $_GET['sid'];
$versionID = $_GET['vid'];
$pageID = $_GET['pid'];
/*
if($pageID) {
    if($versionID)
        $lp = new objLoadPage($versionID,$pageID);
    else {

    }
    $lp->origPage = $lp->getPageBodyFromResourceId($pageID);
    echo $lp->loadPageIntoArray(0);
}
*/
if($pageID)
{
        $lp = new objLoadPage($versionID,$pageID);
        echo $lp->loadPageIntoArray(0);
}
else {
$lp = new objLoadPage($versionID);
echo $lp->loadPageIntoArray(0);
}
