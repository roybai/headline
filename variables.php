<?php
$pattern='/^a*$/';
$string='a b a b ab csnabdeabfg';
$result=preg_match($pattern,$string,$arr);
if($result==null)   echo 'no found<br>';
print_r($arr);
exit();
require_once("objDBSQL.pclass");
require_once("objLoadPage.pclass");
require_once("functions.php");
$versions=listAllVersion();
{
    foreach($versions as $key=>$v)
    {
        $link0="<a href='showPage.php?vid=".$key."&type=0' target='blank'> 0 </a>";
        $link1="<a href='showPage.php?vid=".$key."&type=1' target='blank'> 1 </a>";
        $link2="<a href='showPage.php?vid=".$key."&type=2' target='blank'> 2 </a>";
        echo($v[0].'.....'.$v[1].$link0.'...'.$link1.'...'.$link2.'<br>');

    }
}
function listAllVersion()
{
    $db=new objDBSQL();

    $db->statement="
    SELECT SITE_HOST_NAME,VERSION_DATE_TIME,VERSION_ID
    FROM SITE
    LEFT JOIN VERSION ON VERSION_SITE_ID=SITE_ID

    ";
    $db->execute();
    $ret=array();
    while($r=$db->next())
    {
        $ret[$r['VERSION_ID']][]=$r['SITE_HOST_NAME'];
        $ret[$r['VERSION_ID']][]=$r['VERSION_DATE_TIME'];
    }
    return $ret;
}


