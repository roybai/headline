<?php


require_once("objLoadPage.pclass");
$siteID = $_GET['sid'];
$versionID = $_GET['vid'];
$pageID = $_GET['pid'];
if($pageID) {
    $lp = new objLoadPage(0);
    echo $lp->getPageBodyFromResourceId($pageID);
}
else {
$lp = new objLoadPage($versionID);
echo $lp->loadPage(1);
}
