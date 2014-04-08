<?php


require_once("objLoadPage.pclass");
$siteID = $_GET['sid'];
$versionID = $_GET['vid'];
$lp = new objLoadPage(3);
echo $lp->loadPage(3);