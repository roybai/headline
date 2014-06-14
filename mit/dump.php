<?php
/**
 * Created by Roy Bai June 11,2014
 */
header('Content-Type: text/html; charset=gb2312');
define('_LEN',10);
if(!$_GET['key'] || !$_GET['num'] || !$_GET['username'])
{
    echo 0;
    return;
}
$start_position=intval($_GET['start']);
$error=0;
if($start_position>3000 || $start_position<0)
{
    echo 0;
    return;
}

$head='<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312"/></head>';
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: UTMPKEY=".$_GET['key'].";UTMPNUM=".$_GET['num'].";UTMPUSERID=".$_GET['username'].""
  )
);
$context = stream_context_create($opts);

for($i=0;$i<_LEN;$i++) {
    $buffer = file_get_contents("http://www.mitbbs.com/mitbbs_mailbox.php?option=read&dir=r&num=".($start_position+$i), false, $context);

    $pieces = explode('"jiawenzhang-type">', $buffer);
    $p = explode('</p>',$pieces[1]);
    $allTxt=$p[0];
    $allTxt=str_replace('&nbsp;',' ',$allTxt);
    if($allTxt==""){
        $error=1;
        break;
    }
    /*
    $MONTHS=array("Jan"=>"01","Feb"=>"02","Mar"=>"03","Apr"=>"04","May"=>"05","Jun"=>"06",
    "Jul"=>"07","Aug"=>"08","Sep"=>"09","Oct"=>"10","Nov"=>"11","Dec"=>"12");
    $pieces = explode(': ',$allTxt);
    $p = explode(' (',$pieces[1]);
    $sender = $p[0];
    if($sender==""){
        echo 0;
        return;
    }


    $pieces = explode('/>',$allTxt);
    $pieces= explode('(',$pieces[2]);
    $trunk = $pieces[1];
    $m = substr($trunk,4,3);
    $month=$MONTHS[$m];
    $day = substr($trunk,8,2);
    $day=str_replace(' ','0',$day);
    $time = substr($trunk,11,8);
    $time=str_replace(':','-',$time);
    $year = substr($trunk,20,4);
    */
    if($start_position ==0)
    {
        saveFile($_GET['username'],$head.$allTxt);
    }
    else
        saveAppendFile($_GET['username'],$allTxt);
    //saveFile($year."-".$month."-".$day."-".$time."-".$sender,$head.$allTxt);
}
if($error===1){
    $buf=file_get_contents("/tmp/mitbbs/".$_GET['username'].".html");
    if($buf)
        echo $buf;
    else echo 'error occurred';
}
else
    echo $start_position+_LEN;
return;

function saveAppendFile($filename,$buffer)
{
    $filename="/tmp/mitbbs/".$filename.".html";
    $fp=fopen($filename,"a");
    if(!$fp) return false;
    fwrite($fp,$buffer,strlen($buffer));
    fclose($fp);
    return true;
}
function saveFile($filename,$buffer)
{
    $filename="/tmp/mitbbs/".$filename.".html";
    $fp=fopen($filename,"w");
    if(!$fp) return false;
    fwrite($fp,$buffer,strlen($buffer));
    fclose($fp);
    return true;
}
