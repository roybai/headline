<?php


require_once("objDBSQL.pclass");
require_once("objLoadPage.pclass");
require_once("functions.php");

show_links();

$versions=listAllVersion();
    foreach($versions as $key=>$v)
    {
        $link="<a href='showpage.php?sid=".$v[2]."&vid=".$v[3]."' target='blank'> ".$v[1]." </a>";
        echo($v[0].'.....'.$link.'...'.$v[4].'<br>');

    }

function listAllVersion()
{
    $db=new objDBSQL();

    $db->statement="
    SELECT SITE_HOST_NAME,VERSION_START_TIME,VERSION_ID,VERSION_STATUS,SITE_ID
    FROM SITE
    LEFT JOIN VERSION ON VERSION_SITE_ID=SITE_ID

    ";
    $db->execute();
    $ret=array();
    while($r=$db->next())
    {
        $ret[$r['VERSION_ID']][]=$r['SITE_HOST_NAME'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_START_TIME'];
        $ret[$r['VERSION_ID']][]=$r['SITE_ID'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_ID'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_STATUS'];
    }
    return $ret;
}


function show_links()
{
echo '<a href="mit/index.php">Mitbbs mail backup</a><br>';
echo '<a href="cleartable.php">Truncate all tables</a><br>';
echo '<a href="savepage.php" target="_blank">Start saving page</a><br>';
echo '<a href="site.php" target="_blank">Site Management</a><br>';
}