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

$module_Name = 'camportail';

include 'admin_header.php';
include '../function.php';
include '../cache/config-inc.php';

function index()
{
    global $xoopsModule, $xoopsUser, $xoopsDB, $orderby;

    xoops_cp_header();

    OpenTable();

    //	$myts = MyTextSanitizer::getInstance();

    echo '<div align="center"><h3>' . _FORMULE . "</h3></div>\n";

    legende();

    echo '<br>';

    if (!$orderby) {
        $orderby = 'dateD';
    }

    $mysqlorderby = convertorderbyin($orderby);

    $orderbyTrans = convertorderbytrans($mysqlorderby);

    $linkorderby = convertorderbyout($orderby);

    echo '<small><center>' . _MD_SORTBY . '&nbsp;&nbsp;

              ' . _MD_DATE . " (<a href='index.php?orderby=" . dateA . "'><img src=\"../images/asc.png\" width=\"16\" height=\"16\" border=\"0\" align=\"middle\"></a><a href='index.php?&orderby=dateD'><img src=\"../images/desc.png\" border=\"0\" align=\"middle\"></a>)
              " . _MD_RATING . " (<a href='index.php?orderby=noteA'><img src=\"../images/asc.png\" width=\"16\" height=\"16\"  border=\"0\" align=\"middle\"></a><a href='index.php?orderby=noteD'><img src=\"../images/desc.png\" border=\"0\" align=\"middle\"></a>)
              " . _MD_POPULARITY . " (<a href='index.php?orderby=hitsA'><img src=\"../images/asc.png\" width=\"16\" height=\"16\"  border=\"0\" align=\"middle\"></a><a href='index.php?orderby=hitsD'><img src=\"../images/desc.png\" border=\"0\" align=\"middle\"></a>)
              	";

    echo '<b><br>';

    printf(_MD_CURSORTEDBY, (string)$orderbyTrans);

    echo '</b></center><br>';

    echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"0\" class=\"bg2\" width=\"100%\"><tr class=\"bg2\">\n"
    . "<td width=\"25\" align=\"center\">camid</td>\n"
    . "<td width=\"50\" align=\"center\">pays</td>\n"
    . "<td width=\"100\" align=\"center\">camname</td>\n"
    . "<td align=\"center\">descript</td>\n"
    . "<td align=\"center\">camurl</td>\n"
    . "<td width=\"25\" align=\"center\">voir</td>\n"
    . "<td width=\"25\" align=\"center\">\n";

    echo "etat</td>\n"
    . "</tr>\n";

    $nbcar = 25;

    $requete = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " ORDER BY $mysqlorderby");

    while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
        $camid = (string)$valu->camid;

        $mort = (string)$valu->mort;

        $valid = (string)$valu->valid;

        $descript = stripslashes((string)$valu->descript);

        if (mb_strlen($descript) > $nbcar) {
            $descript = mb_substr($descript, 0, $nbcar) . ' ...';
        }

        echo "<tr class=\"bg3\">\n"
    . "<td width=\"25\">&nbsp; $camid</td>\n"
    . "<td width=\"60\">&nbsp; $valu->pays</td>\n"
    . "<td width=\"140\">&nbsp; $valu->camname</td>\n"
    . "<td width=\"140\">&nbsp; $descript</td>\n"
    . "<td>&nbsp; $valu->camurl</td>\n"
    . "<td align=\"center\" width=\"25\"><a href=\"modif.php?op=showcam&camid=$camid\"><img src=\"../images/loupe2.png\" width=\"16\" height=\"16\"></a></td>\n"
    . "<td align=\"center\" width=\"25\">\n";

        if ('1' == !$mort) {
            if ('1' == $valid) {
                echo '<img src="../images/puce.png" width="16" height="16">';
            } else {
                echo '<img src="../images/clign.gif" width="10" height="10">';
            }
        } else {
            echo "<a id='$camid' name='$camid'><img src=\"../images/special04.png\" width=\"16\" height=\"16\"></a>";
        }

        echo '</td>
</tr>';
    }

    echo "</table>\n";

    CloseTable();

    include 'admin_footer.php';
}

function souscat()
{
    global $xoopsModule, $xoopsUser, $xoopsDB, $catid, $catname, $photomax;

    xoops_cp_header();

    $myts = MyTextSanitizer::getInstance();

    if ($xoopsUser->isAdmin()) {
        echo '<br><br><div align="center"><h3>' . _FORMULE . "</h3></div><br>\n";

        validcam();

        echo "<br>\n";

        checkmort();

        echo "<br><br>\n";

        $photomax1 = $photomax / 1024;

        echo "<table align=\"center\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\" width=\"90%\" class=\"bg2\">\n"
    . "<tr>\n"
    . "<td colspan=\"2\" class=\"bg3\" width=\100%\"><b>" . _AJOUTCAM . "</b></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td width="50%" class="bg3">' . _CHOISIRCAT . "</td>\n"
    . "<td width=\"50%\" class=\"bg3\">\n";

        echo "<form method=\"post\" action=\"index.php?op=souscat\">\n";

        if (!$catid) {
            echo "<form method=\"post\" name=\"test\" action=\"index.php\">\n";

            echo "<input type=\"hidden\" name=\"op\" value=\"souscat\">\n";

            echo "<select name=\"catid\" onChange='submit()'>\n";

            echo "<option selected value=\"$catname\"></option>\n";

            $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . '');

            while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
                echo "<option value=\"$ligne->catid\">$ligne->catname</option>\n";
            }

            echo "</select> \n";
        } else {
            echo "<select name=\"catid\" onChange='submit()'>\n";

            $result = $GLOBALS['xoopsDB']->queryF('SELECT catname FROM ' . $xoopsDB->prefix('camportail_cat') . " WHERE catid=$catid");

            while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
                echo "<option value=\"$ligne->catid\" value2=\"$ligne->catname\">$ligne->catname</option>\n";
            }

            echo '</select><b><input type="button" value="<<<" onclick="history.go(-1)"></b>';
        }

        echo '</form>';

        echo '<form method="post" action="index.php" ENCTYPE="multipart/form-data" NAME="add">';

        echo "<input type=\"hidden\" name=\"op\" value=\"addwebcam\">\n";

        echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";

        echo "</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td valign="top" class="bg3">' . _CHOISISOUSCAT . "</td>\n"
    . "<td class=\"bg3\">\n";

        $requete = $GLOBALS['xoopsDB']->queryF('SELECT souscatid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid' ");

        while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
            echo "<input type=\"radio\" name=\"souscatid\" value=\"$valu->souscatid\"> $valu->souscatname<br>";
        }

        echo "</td>\n"
    . "</tr>\n"
    . "<tr class=\"bg3\">\n"
    . '<td>' . _CHOISIPAYS . "</td>\n"
    . "<td><input type=\"text\" name=\"pays\">\n";

        echo "</td>\n"
    . "</tr>\n"
    . "<tr class=\"bg3\">\n"
    . '<td>' . _NOMCAM . "</td>\n"
    . "<td><input type=\"text\" name=\"camname\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n"
    . '<td>' . _DESCRIPTCAM . "</td>\n"
    . "<td><textarea name=\"descript\" rows=\"3\" cols=\"25\"></textarea></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n"
    . '<td>' . _URL . "</td>\n"
    . "<td><input type=\"text\" name=\"camurl\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n"
    . '<td>' . _IMGFILE . ' ' . _FACU . '<br> ' . _REGLEIMAGE . "</td>\n"
    . '<td><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10000000"><input type=file name="photo"> ' . _LIMIT . '';

        printf('%.2f ko', $photomax1);

        echo "</td>\n";

        echo "</tr></table>\n";

        echo '<div align="center"><br><input type="submit" name="add" value="' . _ENVOIE . "\">&nbsp;&nbsp;</div>\n";

        echo "</form>\n";

        echo "<br><br>\n";

        echo "<form action=\"index.php\" method=\"POST\">\n"
    . "<input type=\"hidden\" name=\"op\" value=\"ajoutcatprinc\">\n"
    . "<table width=\"90%\" cellspacing=\"2\" border=\"0\" cellpadding=\"4\" align=\"center\" class=\"bg2\">\n"
    . "<tr>\n"
    . '<td colspan="2" class="bg3"><b>' . _AJOUTCATPRINC . "</b></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td width="50%"  valign="top" class="bg3">' . _PRTXTCHOISIRCAT . "</td>\n"
    . "<td width=\"50%\" class=\"bg3\">\n";

        echo "<input type=\"text\" name=\"catname\"><br><br>\n";

        echo '<input type="submit" value="' . _ENVOIE . "\">\n";

        echo "</td>\n"
     . "</tr>\n"
     . "</table>\n"
     . "</form>\n";

        echo '<br><br>';

        echo "<form action=\"index.php\" method=\"POST\">\n"
    . "<input type=\"hidden\" name=\"op\" value=\"ajoutsouscat\">\n"
    . "<table width=\"90%\" cellspacing=\"2\" border=\"0\" cellpadding=\"4\" align=\"center\" class=\"bg2\">\n"
    . "<tr>\n"
    . '<td colspan="2" class="bg3"><b>' . _AJOUTSSCAT . "</b></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td width="50%"  valign="top" class="bg3">' . _TXTCHOISIRCAT . "</td>\n"
    . "<td width=\"50%\" class=\"bg3\">\n";

        echo "<select name=\"catid\">\n";

        echo "<option selected></option>\n";

        $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . '');

        while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
//          $catid = "".$ligne->catid."";
//          $catname = "$ligne->catname";

            echo "<option value=\"$ligne->catid\">$ligne->catname</option>\n";
        }

        echo "</select><br><br>\n";

        echo "<input type=\"text\" name=\"souscatname\"><br><br>\n";

        echo '<input type="submit" value="' . _ENVOIE . "\">\n";

        echo "</td>\n"
     . "</tr>\n"
     . "</table>\n"
     . "</form>\n";
    }

    echo '<br>';

    include 'admin_footer.php';
}

function ajoutcatprinc()
{
    global $xoopsDB, $catname;

    if (!$catname) {
        xoops_cp_header();

        echo '<br><br><b>' . _PRTXTCHOISIRCAT . '!</b><br><br>';

        echo '<input type="button" value="' . _PREC . '" onclick="history.go(-1)">';

        include 'admin_footer.php';
    } else {
        $GLOBALS['xoopsDB']->queryF('insert into ' . $xoopsDB->prefix('camportail_cat') . " (catname) values ('$catname') ") || die("Impossible d'insérer le résultat dans la base");

        redirect_header('index.php?op=souscat', 1, '' . _MERCI . '');

        exit();
    }
}

function ajoutsouscat()
{
    global $xoopsDB, $catid, $souscatname;

    if (!$catid || !$souscatname) {
        xoops_cp_header();

        echo '<br><br><b>' . _TXTCHOISIRCAT . '!</b><br><br>';

        echo '<input type="button" value="' . _PREC . '" onclick="history.go(-1)">';

        include 'admin_footer.php';
    } else {
        $GLOBALS['xoopsDB']->queryF('insert into ' . $xoopsDB->prefix('camportail_souscat') . " (catid, souscatname) values ('$catid', '$souscatname') ") || die("Impossible d'insérer le résultat dans la base");

        redirect_header('index.php?op=souscat', 1, '' . _MERCI . '');

        exit();
    }
}

function addwebcam()
{
    global $xoopsDB, $xoopsConfig, $xoopsUser, $Module_Name, $catid, $souscatid, $pays, $camname, $descript, $camurl, $photo, $photomax, $destination, $photo_size, $photo_name;

    if ($photo_name) {
        $typephoto[1] = 'gif';

        $typephoto[2] = 'jpg';

        $typephoto[3] = 'png';

        $typephoto[4] = 'GIF';

        $typephoto[5] = 'JPG';

        $typephoto[6] = 'PNG';

        preg_match("\.([^\.]*$)", $photo_name, $elts);

        $extension_fichier = $elts[1];

        if (!in_array($extension_fichier, $typephoto, true)) {
            xoops_cp_header();

            OpenTable();

            echo '' . _CLA_FILES . " $extension_fichier " . _CLA_FILESTOP . '.';

            CloseTable();

            include 'admin_footer.php';

            exit;
        }

        if ($photo_size > $photomax) {
            $photomax1 = $photomax / 1024;

            xoops_cp_header();

            OpenTable();

            echo '' . _CLA_YIMG . ' ' . $photo_name . ' ' . _CLA_TOBIG . ' < ';

            printf('%.2f ko', $photomax1);

            CloseTable();

            include 'admin_footer.php';

            exit();
        }

        $date = time();

        $photnom = "$date-$photo_name";

        $destination = $xoopsConfig['root_path'] . "/modules/camportail/imgcam/$photnom";

        if (!copy((string)$photo, $destination)) {
            xoops_cp_header();

            OpenTable();

            echo '' . _CLA_JOIND . '.';

            CloseTable();

            include 'admin_footer.php';

            exit;
        }
    }

    if (!$catid || !$souscatid || !$pays || !$camname || !$descript || !$camurl) {
        xoops_cp_header();

        OpenTable();

        echo '<div align="center"><b>aaaaa' . _ERREURCOMM . '</b><br><br>';

        echo '<input type="button" value="' . _PREC . '" onclick="history.go(-1)"></div>';

        CloseTable();

        include 'admin_footer.php';
    } else {
        $userid = $xoopsUser->uid();

        $camname = addslashes((string)$camname);

        $descript = addslashes((string)$descript);

        $this_date = time();

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " (catid, souscatid, userid, pays, camname, descript, camurl, camimg, valid, date) values ( '$catid', '$souscatid', '$userid', '$pays', '$camname', '$descript', '$camurl', '$photnom', '1', '$this_date')");

        redirect_header('index.php?op=souscat', 3, _AJOUR);

        exit();
    }
}

switch ($op) {
    case 'souscat':
    souscat($souscat);
    break;
    case 'ajoutcatprinc':
    ajoutcatprinc();
    break;
    case 'ajoutsouscat':
    ajoutsouscat();
    break;
    case 'addwebcam':
    addwebcam();
    break;
    default:
    index();
    break;
}
