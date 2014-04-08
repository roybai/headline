<?php


require_once("objSavePage.pclass");

$web=new objSavePage("http://www.wenxuecity.com");
$links=$web->save(true);

