<?php
//addSiteProcess.php
require_once("objSite.pclass");

$site = new objSite();
$site->host = $_POST['hostname'];
if($_POST['language']) $site->lang=$_POST['language'];
if($_POST['location']) $site->loc=$_POST['location'];
if($_POST['depth']) $site->depth=$_POST['depth'];
if($_POST['category']) $site->cat=$_POST['category'];
$site->addSite();
header('Location: /headline/site.php');
