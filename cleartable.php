<?php


require_once("objDBSQL.pclass");

$db = new objDBSQL();
$db->clear_tables();
header('Location: /headline');

