<?php

include '../../mainfile.php';
require_once $xoopsConfig['root_path'] . 'class/module.textsanitizer.php';
//$myts = new MyTextSanitizer; // MyTextSanitizer object
$xoopsDB->queryF('update ' . $xoopsDB->prefix('camportail') . " set camhits=camhits+1 where camid=$camid");
$result = $xoopsDB->query('select camurl from ' . $xoopsDB->prefix('camportail') . " where camid=$camid");
[$camurl] = $xoopsDB->fetchRow($result);
//$camurl = $myts->xoopsStripSlashesRT($camurl);
echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $camurl . '"></meta></head><body></body></html>';

exit();



