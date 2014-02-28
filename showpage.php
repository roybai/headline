<?php


require_once("objLoadPage.pclass");

$lp = new objLoadPage(1);
echo $lp->loadPage(0);