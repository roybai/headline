<?php


require_once("objDBSQL.pclass");
require_once("objLoadPage.pclass");
require_once("functions.php");

$clear_table_link='<a href="cleartable.php">clear table</a>';
$save_page_link='<a href="savepage.php" target="_blank">save page</a>';
echo $clear_table_link."<br>";
echo $save_page_link."<br>";
$versions=listAllVersion();
{
    foreach($versions as $key=>$v)
    {
        $link="<a href='showpage.php?sid=".$v[2]."&vid=".$v[3]."' target='blank'> ".$v[1]." </a>";
        echo($v[0].'.....'.$link.'<br>');

    }
}
function listAllVersion()
{
    $db=new objDBSQL();

    $db->statement="
    SELECT SITE_HOST_NAME,VERSION_DATE_TIME,VERSION_ID,SITE_ID
    FROM SITE
    LEFT JOIN VERSION ON VERSION_SITE_ID=SITE_ID

    ";
    $db->execute();
    $ret=array();
    while($r=$db->next())
    {
        $ret[$r['VERSION_ID']][]=$r['SITE_HOST_NAME'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_DATE_TIME'];
        $ret[$r['VERSION_ID']][]=$r['SITE_ID'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_ID'];
    }
    return $ret;
}


