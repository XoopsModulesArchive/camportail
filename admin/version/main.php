<?php
if ($fct == ''){
	echo ?>
	<html><head>
	<meta http-equiv="refresh" content="0;URL=../../index.php">
	</head></html>
	<?;
	exit();
	}
include ('../../xoops_version.php');
//$css = getCss($theme);
echo "<html>\n<head>\n";
echo '<title>' . $modversion['name'] . "</title>\n";
echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . "\"></meta>\n";

?>
<script language="JavaScript1.1">
<!--
scrollID=0;
vPos=0;

function onWard() {
   vPos+=2;
   window.scroll(0,vPos);
   vPos%=1000;
   scrollID=setTimeout("onWard()",30);
   }
function stop(){
   clearTimeout(scrollID);
}
//-->
</script>
<?php
/*
if($css){
   	echo "<link rel=\"stylesheet\" href=\"".$css."\" type=\"text/css\">\n\n";
}
*/
echo "</head>\n";
echo "<body onLoad=\"if(window.scroll)onWard()\" onmouseover=\"stop()\" onmouseout=\"if(window.scroll)onWard()\">\n";
echo '<div align="center">';
echo '<table width="100%"><tr><td align="center">';
echo '<br><br><br><br><br>';
echo '<img src="../../images/webcam.jpg" border="0"><br>';
echo '<big><b>' . $modversion['name'] . '</b></big>';

echo '<br><br>';
echo '<u>Version</u><br>';
echo $modversion['version'];

echo '<br><br>';
echo '<u>Description</u><br>';
echo $modversion['description'];

echo '<br><br>';
echo '<u>Author</u><br>';
echo $modversion['author'];

echo '<br><br>';
echo '<u>Credits</u><br>';
echo $modversion['credits'];

echo '<br><br>';
echo '<u>License</u><br>';
echo $modversion['license'];

echo '<br><br><br><br><br>';
echo '<a href="javascript:window.close();">Close</a>';
echo '<br><br><br>';
echo '</td></tr></table></div>';
echo '</body></html>';
?>
