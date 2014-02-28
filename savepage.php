<?php


require_once("objSavePage.pclass");

$web=new objSavePage("http://www.royd2.com");
$links=$web->save(true);

