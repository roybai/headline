<?php


require_once("objDBSQL.pclass");
require_once("objLoadPage.pclass");
require_once("functions.php");
$clear_table_link='<a href="cleartable.php">clear table</a>';
$save_page_link='<a href="savepage.php" target="_blank">save page</a>';
$_body="<h4>Site list </h4>";
$_body .= addSiteForm();
$versions=listSite();
$titleTag = 'this is a test';


function listSite()
{
    global $_body;

    $fields=array(
        "ID"=>"SITE_ID",
        "Host"=>"SITE_HOST_NAME",
        "Lang."=>"SITE_LANGUAGE",
        "Loc."=>"SITE_LOCATION",
        "Depth"=>"SITE_DEPTH",
        "Create time"=>"SITE_FIRST_CAPTURE_TIME",
        "Update time"=>"SITE_LAST_UPDATE_TIME",
        "Captures"=>"SITE_TOTAL_CAPTURE_TIMES",
        "Cat."=>"SITE_CATEGORY",
        "Status"=>"SITE_STATUS",
    );

    $db=new objDBSQL();

    $field_str="";
    foreach($fields as $key=>$value) {
        if($field_str!= "") $field_str.=",";
        $field_str.=$value;
    }

    $db->statement="
    SELECT ".$field_str."
    FROM SITE    ";
    $db->execute();
    $_body .= "<table width='80%' align='center' border='1'>";

    $_body.="<tr><td></td>";
    foreach($fields as $key=>$value) {

        $_body.="<td>".$key."</td>";
    }
    $_body.="</tr>";
     while($r=$db->next())
    {
        $_body.="<tr><td><a href=process/deleteSiteProcess.php?id=".$r['SITE_ID']." onclick='return confirm(\"are you sure?\");'>DEL</a></td>";
        foreach($fields as $field)
        {
            if($r[$field]===null || $r[$field]==="") $r[$field]="-";
            $_body .= "<td>".$r[$field]."</td>";
        }
        $_body.="</tr>";
    }

}

function listAllVersion()
{
    global $_body;
    $db=new objDBSQL();

    $db->statement="
    SELECT SITE_HOST_NAME,VERSION_DATE_TIME,VERSION_ID,SITE_ID
    FROM SITE
    LEFT JOIN VERSION ON VERSION_SITE_ID=SITE_ID

    ";
    $db->execute();
    $ret=array();
    $_body .= "<table width='40%' align='center' border='1'>";
    while($r=$db->next())
    {
        $_body.="<tr>";
        $_body.="<td width='20%'>".$r['SITE_HOST_NAME']."</td>";
        $_body.="<td width='20%'>".$r['VERSION_DATE_TIME']."</td>";
        $_body.="<td width='20%'>".$r['SITE_ID']."</td>";
        $_body.="<td width='20%'>".$r['VERSION_ID']."</td>";
        $_body.="</tr>";
    }
    $_body.="</table>";
    return $ret;
}

function addSiteForm()
{
return <<<form
<center><a href='#' onclick="$('#AddForm').toggle(200)";>add site</a></center>
<div id="AddForm" style="display:none">
    <form action="process/addSiteProcess.php" method="post">
    <table width=80% align='center' border='1'>
        <tr><td width=20% align='right'>host name:</td> <td width=50%><input type="text" name="hostname" matxlength="50"></td></tr>
        <tr><td align='right'>language:</td><td><input type="text" name="language" matxlength="10"></td></tr>
        <tr><td align='right'>Location:</td><td><input type="text" name="location" matxlength="10"></td></tr>
        <tr><td align='right'>depth:</td><td><input type="text" name="depth" matxlength="10"></td></tr>
        <tr><td align='right'>category:</td><td><input type="text" name="category" matxlength="10"></td></tr>
        <tr><td align='right'><input type="submit" value="Submit"></td></tr>
    </table>
    </form>
</div>
form;

}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <style>
    h1,h2,h3,h4 {text-align:center;}

    </style>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta http-equiv="Content-Style-Type" content="text/css"/>
	<meta name="copyright" content="Copyright &copy;2002-<?php echo date('Y');?> - Headline, Inc."/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="process/js/jquery.min.js" type="text/javascript"></script>
	<title><?php echo $titleTag; ?></title>

</head>
<body>
	<?php echo $_body; ?>

</body>