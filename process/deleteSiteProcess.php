<?php

require_once("objSite.pclass");

$site = new objSite();
$site->id = $_GET['id'];
$site->deleteSite();
header('Location: /headline/site.php');
