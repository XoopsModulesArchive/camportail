<?php

##################################################################################
# webcam-portail Version 1.0 alpha                                               #
#                                                                                #
# Projet du 30/05/2002          dernière modification: 15/06/2002                #
# Scripts Home:                 http://www.lespace.org                           #
#              							                 #
# Xoops-RC2 Version: 1.0 alpha                                                   #
#                                                                                #
# auteur           :            bidou                                            #
# email            :            bidou@lespace.org                                #
# Site web         :		http://www.lespace.org                           #
# licence          :            Gpl                                              #
##################################################################################
# Merci de laisser cette entête en place.                                        #
##################################################################################

function camheader()
{
    global $xoopsDB, $xoopsConfig, $Module_Name, $imglogo, $xoopsLogger;

    $nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail') . " WHERE valid='1'");

    $row = $GLOBALS['xoopsDB']->fetchRow($nombre);

    $nbcat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_cat') . '');

    $rowcat = $GLOBALS['xoopsDB']->fetchRow($nbcat);

    $nbsouscat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_souscat') . '');

    $rowsouscat = $GLOBALS['xoopsDB']->fetchRow($nbsouscat);

    echo "<table width=\"95%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\" class=\"head\" align=\"center\">\n"
    . "<tr>\n"
    . "<td class=\"even\">\n"
    . "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\">\n"
    . "<tr>\n"
    . "<td rowspan=\"3\">\n";

    $logo = (string)$imglogo;

    if (file_exists("images/$logo")) {
        $size = getimagesize("images/$logo"); //recherche du format de l'image

        echo '<a href="' . $xoopsConfig['xoops_url'] . "/modules/$Module_Name/index.php\" title=\"" . _INDEX . "\"><img src=\"images/$logo\" $size[3] border=\"0\"></a>\n";
    } else {
        echo '&nbsp;';
    }

    echo "</td>\n"
    . '<td valign="top"><h1>' . _WEBCAMPORTAIL . "</h1></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td><b>' . _NBCAM . ' ' . $row[0] . ' ' . _CAM . ' </b><br><b>' . _REPART . ' ' . $rowcat[0] . ' ' . _CAT . ' ' . _PRINCIP . '<br>' . _ET . ' ' . $rowsouscat[0] . ' ' . _SOUSCAT . "</b></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td align="right"><a href="formulaire.php" ><img src="images/ajout.png" width="16" height="16" border="0"> ' . _AJOUTER . "</a>\n"
    . "</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr>\n"
    . "</table><br>\n";
}

function camheader2()
{
    global $xoopsDB, $xoopsConfig, $Module_Name, $imglogo, $xoopsLogger;

    $nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail') . " WHERE valid='1'");

    $row = $GLOBALS['xoopsDB']->fetchRow($nombre);

    $nbcat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_cat') . '');

    $rowcat = $GLOBALS['xoopsDB']->fetchRow($nbcat);

    $nbsouscat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_souscat') . '');

    $rowsouscat = $GLOBALS['xoopsDB']->fetchRow($nbsouscat);

    echo "<table width=\"95%\" cellspacing=\"2\" cellpadding=\"0\" border=\"0\" class=\"head\" align=\"center\">\n"
    . "<tr>\n"
    . "<td class=\"even\">\n"
    . "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\">\n"
    . "<tr>\n"
    . "<td rowspan=\"3\">\n";

    $logo = (string)$imglogo;

    if (file_exists("images/$logo")) {
        $size = getimagesize("images/$logo"); //recherche du format de l'image

        echo '<a href="' . $xoopsConfig['xoops_url'] . "/modules/$Module_Name/index.php\" title=\"" . _INDEX . "\"><img src=\"images/$logo\" $size[3] border=\"0\"></a>\n";
    } else {
        echo '&nbsp;';
    }

    echo "</td>\n"
    . '<td valign="top"><h1>' . _WEBCAMPORTAIL . "</h1></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td><b>' . _NBCAM . ' ' . $row[0] . ' ' . _CAM . ' </b><br><b>' . _REPART . ' ' . $rowcat[0] . ' ' . _CAT . ' ' . _PRINCIP . '<br>' . _ET . ' ' . $rowsouscat[0] . ' ' . _SOUSCAT . "</b></td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr>\n"
    . "</table><br>\n";
}

function camfooter()
{
    echo '<div align="center"><br>'
   . 'Camportail v 1.1<br>'
   . "<a href=javascript:openWithSelfMain('" . XOOPS_URL . "/modules/camportail/admin/version/main.php?fct=version','Info',300,330);>Copyright &copy; 2002</a><br>";
}

function DateFormat($timestamp)
{
    global $weekday,$months,$offset,$dformat,$tformat;

    $timestamp += $offset * 3600;

    [$wday, $mday, $month, $year, $hour, $minutes, $hour12, $ampm] = preg_split('( )', date('w j n Y H i h A', $timestamp));

    if ('AMPM' == $tformat) {
        $newtime = " $hour12:$minutes $ampm";
    } else {
        $newtime = " $hour:$minutes";
    }

    if ('USx' == $dformat) {
        $newdate = " $month-$mday-$year";
    } elseif ('US' == $dformat) {
        $month -= 1;

        $newdate = "$weekday[$wday], $months[$month] $mday, $year";
    } elseif ('Euro' == $dformat) {
        $month -= 1;

        $newdate = "$weekday[$wday], $mday. $months[$month] $year";
    } else {
        $newdate = "$mday.$month.$year";
    }

    return ($newdate .= $newtime);
}

function validcam()
{
    global $xoopsDB;

    $valu = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(valid) FROM ' . $xoopsDB->prefix('camportail') . ' WHERE valid=0');

    $row = $GLOBALS['xoopsDB']->fetchRow($valu);

    echo '' . _AVALIDER . " $row[0]";
}

function checkmort()
{
    global $xoopsDB;

    $mrt = $GLOBALS['xoopsDB']->queryF('SELECT camid FROM ' . $xoopsDB->prefix('camportail') . ' WHERE mort=1');

    $val = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(mort) FROM ' . $xoopsDB->prefix('camportail') . ' WHERE mort=1');

    $camid = (string)$mrt->camid;

    echo '' . _LIENMORT . " $res[0]";
}

function legende()
{
    global $xoopsDB;

    echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n"
. "<tr>\n"
. "<td width=\"85%\" valign=\"bottom\" rowspan=\"4\">\n";

    $valu = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(valid) FROM ' . $xoopsDB->prefix('camportail') . ' WHERE valid=0');

    $row = $GLOBALS['xoopsDB']->fetchRow($valu);

    echo '' . _AVALIDER . " $row[0]<br>\n";

    //	$mrt=$GLOBALS['xoopsDB']->queryF("SELECT camid FROM ".$xoopsDB->prefix("camportail")." WHERE mort=1");

    $val = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(mort) FROM ' . $xoopsDB->prefix('camportail') . ' WHERE mort=1');

    $res = $GLOBALS['xoopsDB']->fetchRow($val);

    //	while($valu=$GLOBALS['xoopsDB']->fetchObject($mrt)) {

    //	$camid="$valu->camid";

    //if ( $val ){

    echo "<a href='#$camid'>" . _LIENMORT . "</a> $res[0]";

    //}else{

    //echo ""._LIENMORT." $res[0]";

    //}

    //}

    echo "</td>\n"
    . "<td rowspan=\"4\" width=\"20\"><img src=\"../images/legende2.png\"></td>\n"
    . "<td width=\"16\"><img src=\"../images/loupe2.png\" vidth=\"16\" width=\"16\"></td>\n"
    . "<td>&nbsp;Vue détaillée</td>\n"
    . "<tr>\n"
    . "<td width=\"16\"><img src=\"../images/puce.png\" vidth=\"16\" width=\"16\"></td>\n"
    . "<td>&nbsp;Rien a signaler</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td width=\"16\"><img src=\"../images/special04.png\" vidth=\"16\" width=\"16\"></td>\n"
    . "<td>&nbsp;Lien brisé</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td width=\"16\" align=\"center\"><img src=\"../images/clign.gif\" vidth=\"10\" width=\"10\"></td>\n"
    . "<td>&nbsp;En attente</td>\n"
    . "</tr>\n"
    . "</table>\n";
}

function convertorderbyin($orderby)
{
    if ('dateA' == $orderby) {
        $orderby = 'date ASC';
    }

    if ('hitsA' == $orderby) {
        $orderby = 'camhits ASC';
    }

    if ('noteA' == $orderby) {
        $orderby = 'note ASC';
    }

    if ('dateD' == $orderby) {
        $orderby = 'date DESC';
    }

    if ('hitsD' == $orderby) {
        $orderby = 'camhits DESC';
    }

    if ('noteD' == $orderby) {
        $orderby = 'note DESC';
    }

    return $orderby;
}
function convertorderbytrans($orderby)
{
    if ('camhits ASC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYLTOM . '';
    }

    if ('camhits DESC' == $orderby) {
        $orderbyTrans = '' . _MD_POPULARITYMTOL . '';
    }

    if ('date ASC' == $orderby) {
        $orderbyTrans = '' . _MD_DATEOLD . '';
    }

    if ('date DESC' == $orderby) {
        $orderbyTrans = '' . _MD_DATENEW . '';
    }

    if ('note ASC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGLTOH . '';
    }

    if ('note DESC' == $orderby) {
        $orderbyTrans = '' . _MD_RATINGHTOL . '';
    }

    return $orderbyTrans;
}
function convertorderbyout($orderby)
{
    if ('date ASC' == $orderby) {
        $orderby = 'dateA';
    }

    if ('hits ASC' == $orderby) {
        $orderby = 'hitsA';
    }

    if ('note ASC' == $orderby) {
        $orderby = 'noteA';
    }

    if ('date DESC' == $orderby) {
        $orderby = 'dateD';
    }

    if ('hits DESC' == $orderby) {
        $orderby = 'hitsD';
    }

    if ('note DESC' == $orderby) {
        $orderby = 'noteD';
    }

    return $orderby;
}







