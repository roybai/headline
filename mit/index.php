<?php
set_time_limit(0);
header("Content-type: text/html; charset=gb2312");
//$head='<head><meta http-equiv="Content-Type" content="text/html; charset=gb2312"/></head><body>';
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "Cookie: UTMPKEY=47499726;UTMPNUM=4528;UTMPUSERID=alih"
  )
);
$page=<<<HEREDOC
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312"/>
<script src="jquery.min.js" type="text/javascript"></script>
<script>
function dump() {
    var param = "start="+$('#bar').html()+"&username="+$('#username').val()+"&key="+$('#key').val()+"&num="+$('#num').val();

    $.ajax({
        url: "dump.php",
        type: "GET",
        data: param,
        success: function(data) {
            n=parseInt(data);
            if(n>0 && n<2000) {
                $('#bar').html(n);
                dump();
            }
            else
            {
                $('#bar').html("done");
                $('#dumped').html("");
                $('#showall').html(data);
            }
        },
     });
}

function start_download() {
    $('#bar').html(0);
    $('#dumped').html(" email dumped");
    dump();
    return;
}



</script>
</head>
<body>
<table width=30%>
<tr><td width=50%> USER NAME:</td><td width=50%><input type="text" id="username"></td></tr>
<tr><td width=50%> UTMPKEY:</td><td width=50%><input type="text" id="key"></td></tr>
<tr><td width=50%> UTMPNUM:</td><td width=50%><input type="text" id="num"></td></tr>
<tr><td width=50%><input type="button" onclick="start_download();" value="start downloading email"></td><td width=50%><span id="bar"></span><span id="dumped"></span></td></tr>
</table>
<div id="showall">
</div>
</body>
HEREDOC;
echo $page;


return;
/*
$context = stream_context_create($opts);

for($i=0;$i<1000;$i++) {

$buffer = file_get_contents("http://www.mitbbs.com/mitbbs_mailbox.php?option=read&dir=r&num=".$i, false, $context);
foreach ($http_response_header as $hdr) {
//    echo $hdr;
}
//echo $buffer;
$pieces = explode('"jiawenzhang-type">', $buffer);
$p = explode('</p>',$pieces[1]);
$allTxt=$p[0];
$allTxt=str_replace('&nbsp;',' ',$allTxt);
$MONTHS=array("Jan"=>"01","Feb"=>"02","Mar"=>"03","Apr"=>"04","May"=>"05","Jun"=>"06",
"Jul"=>"07","Aug"=>"08","Sep"=>"09","Oct"=>"10","Nov"=>"11","Dec"=>"12");
$pieces = explode(': ',$allTxt);
$p = explode(' (',$pieces[1]);
$sender = $p[0];
if($sender=="") break;


$pieces = explode('/>',$allTxt);
//echo($pieces[2]);
//    echo('<br>');
    $pieces= explode('(',$pieces[2]);
    $trunk = $pieces[1];
$m = substr($trunk,4,3);
$month=$MONTHS[$m];
$day = substr($trunk,8,2);
$day=str_replace(' ','0',$day);
$time = substr($trunk,11,8);
$time=str_replace(':','-',$time);
$year = substr($trunk,20,4);




echo $allTxt;
saveMyFile($year."-".$month."-".$day."-".$time."-".$sender,$head.$allTxt);
    break;
}
echo "<br>done";
function saveMyFile($filename,$buffer)
{
    $filename="/tmp/mitbbs/".$filename.".html";
    $fp=fopen($filename,"w");
    if(!$fp) return false;
    fwrite($fp,$buffer,strlen($buffer));
    fclose($fp);
    return true;
}
*/


