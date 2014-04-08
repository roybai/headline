<?php
require_once("objDBSQL.pclass");
require_once("objSavePage.pclass");
require_once("objLoadPage.pclass");
require_once("functions.php");

ini_set("user_agent", "Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5");
$web=new objSavePage("http://www.royd2.com");
$links=$web->save(true);
exit();
