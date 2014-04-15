<?php
set_include_path("/headline/process/");
error_reporting(E_ERROR );
require_once("objSavePage.pclass");

$web=new objSavePage("http://www.mitbbs.com");
$links=$web->save(true);

function test()
{
    echo file_get_contents_curl('www.mitbbs.com/virtual_shape/images/vshape_merge_1.png');
}
