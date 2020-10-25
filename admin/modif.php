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

if (file_exists($xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/main.php')) {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/main.php';
} else {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/french/main.php";
}

function showcam()
{
    global $xoopsDB, $camid;

    xoops_cp_header();

    include $xoopsConfig['root_path'] . 'header.php';

    OpenTable();

    echo ' <div align="center"><h3>' . _MODIFADMIN . "</h3></div><br>\n";

    echo '<table width="100%" border="0"><tr><td width="60%">';

    $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " where camid=$camid");

    while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
        $requete = $GLOBALS['xoopsDB']->queryF('SELECT catname FROM ' . $xoopsDB->prefix('camportail_cat') . " where catid='$ligne->catid'");

        while ($name = $GLOBALS['xoopsDB']->fetchObject($requete)) {
            $mort = (string)$ligne->mort;

            $valid = (string)$ligne->valid;

            echo '<b>' . _PRINCIP . ": $name->catname</b><br>\n";
        }

        $sql = $GLOBALS['xoopsDB']->queryF('SELECT souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " where souscatid='$ligne->souscatid'");

        while ($name = $GLOBALS['xoopsDB']->fetchObject($sql)) {
            echo '<b>' . _SOUSCAT . ": $name->souscatname</b><br>\n";

            echo '<b>' . _STATUT . '&nbsp';

            if ('1' == !$mort) {
                if ('1' == $valid) {
                    echo "<img src=\"../images/puce.png\" width=\"16\" height=\"16\">\n";
                } else {
                    echo "<img src=\"../images/clign.gif\" width=\"10\" height=\"10\">\n";
                }
            } else {
                echo "<img src=\"../images/special04.png\" width=\"16\" height=\"16\">\n";
            }
        }

        echo '</td><td width="40%">';

        $sql = $GLOBALS['xoopsDB']->queryF('SELECT uname, email FROM ' . $xoopsDB->prefix('users') . " where uid=$ligne->userid");

        while ($name = $GLOBALS['xoopsDB']->fetchObject($sql)) {
            echo '<b>' . _PROPOS . " $name->uname<br>" . _MAIL . " <a href=\"mailto:$name->email\">$name->email</a></b>";
        }

        echo '</td></tr><table>';

        echo '<br>';

        $valid = (string)$ligne->valid;

        $souscat = (string)$ligne->souscatid;

        $camid = (string)$ligne->camid;

        $camname = stripslashes((string)$ligne->camname);

        $descript = stripslashes((string)$ligne->descript);

        $camurl = (string)$ligne->camurl;

        $image = (string)$ligne->camimg;

        $date = DateFormat($ligne->date);

        echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" class=\"bg2\">\n"
    . "<tr>\n"
    . "<td rowspan=\"3\" width=\"120\" align=\"center\" class=\"bg3\">\n";

        if ($image) {
            echo "<a href=\"../visit.php?&camid=$camid\" target=\"new\"><img src=\"../imgcam/$ligne->camimg\" width=\"120\" height=\"90\"></a></td>\n";
        } else {
            echo "<a href=\"../visit.php?&camid=$camid\" target=\"new\"><img src=\"../imgcam/vignette.jpg\" width=\"120\" height=\"90\"></a></td>\n";
        }

        echo "<td width=\"80%\" class=\"bg3\">\n"
    . "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td><a href=\"../visit.php?camid=$camid\" target=\"new\"><img src=\"../images/ajout.png\" width=\"16\" height=\"16\" border=\"0\"> <b>$camname</b></a> ($ligne->pays)</td>\n"
    . '<td width="25%">' . _RATING . ": $ligne->vote &nbsp; " . _MD_RATING . ": $ligne->note</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td class="bg3" height="50" valign="top">' . _DEPOSE . " $date<br>$descript</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td class=\"bg3\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td width=\"20%\">\n";

        echo '' . _KILL . "<a href=\"../formlist.php?op=lienmort&camid=$camid&souscatid=$souscat\"> <img src=\"../images/special04.png\" width=\"16\" height=\"16\" border=\"0\"></a>\n";

        echo "</td>\n"
    . '<td width="20%"><img src="../images/loupe2.png" width="16" height="16" border="0"> ' . $ligne->camhits . ' ' . _VISITE . "</td>\n"
    . "<td width=\"35%\">\n";

        echo "<form method=\"post\" action=\"../formlist.php?camid=$camid\" name=\"jevote\">\n"
    . '' . _VOTE . " <select name=\"note\" onChange=\'submit()\">\n"
               . "<option selected>--</option>\n"
               . "<option value=\"1\">1</option>\n"
               . "<option value=\"2\">2</option>\n"
               . "<option value=\"3\">3</option>\n"
               . "<option value=\"4\">4</option>\n"
               . "<option value=\"5\">5</option>\n"
               . "<option value=\"6\">6</option>\n"
               . "<option value=\"7\">7</option>\n"
               . "<option value=\"8\">8</option>\n"
               . "<option value=\"9\">9</option>\n"
               . "<option value=\"10\">10</option>\n"
         . "</select>\n"
         . "<input type=\"hidden\" name=\"op\" value=\"ajoutnote\">\n"
         . "<input type=\"hidden\" name=\"souscatid\" value=\"$ligne->souscatid\">\n"
         . "<input type=\"submit\" value=\"go\">\n"
         . "</form></td>\n";

        echo '
<script language="JavaScript">
function OuvrirFenetre(url,nom,details) {
 window.open(url,nom,details)}
</script>';

        $nbcat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_comment') . " WHERE id ='$camid'");

        $rowcat = $GLOBALS['xoopsDB']->fetchRow($nbcat);

        echo "<td width=\"25%\"><a href=\"javascript:OuvrirFenetre('../commentaire.php?cat=$souscatid&img=$ligne->camimg&id=$camid','popup','scrollbars=yes, width=300,height=350')\" title=\"" . _AJOUTCOMM . "\">\n"
    . '<img src="../images/news.png" width="16" height="16" border="0"></a> ' . _COMMENT . " $rowcat[0]</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr></table>\n";

        echo "<br>\n";

        echo "<table align=\"center\" cellpacing=\"2\" border=\"0\">\n"
    . "<tr>\n"
    . "<td>&nbsp;</td><td>\n"
    . "<form method=\"post\" action=\"modif.php\">\n"
    . "<input type=\"hidden\" name=\"op\" value=\"validation\">\n"
    . "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n"
    . '<input type="submit" value="' . _VALID . "\">\n"
    . "</form>\n"
    . "</td>\n";

        echo "<td>\n"
    . "<form method=\"post\" action=\"modif.php\">\n"
    . "<input type=\"hidden\" name=\"op\" value=\"edit\">\n"
    . "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n"
    . "<input type=\"hidden\" name=\"catid\" value=\"$ligne->catid\">\n"
    . '<input type="submit" value="' . _EDIT . "\">\n"
    . "</form></td>\n";

        echo "<td><form method=\"post\" action=\"modif.php\">\n"
    . "<input type=\"hidden\" name=\"op\" value=\"confirm\">\n"
    . "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n"
    . '<input type="submit" value="' . _TUER . "\">\n"
    . "</td>\n"
    . "<td>\n"
    . '<input type="button" value="' . _PREC . "\" onclick=\"history.go(-1)\"></div>\n"
    . "</form></td></tr></table>\n";
    }

    CloseTable();

    xoops_cp_footer();
}

function validation()
{
    global $xoopsDB, $camid;

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET valid=1 WHERE camid=$camid");

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET mort=0 WHERE camid=$camid");

    redirect_header('index.php', 1, _2VALID);

    exit();
}

function confirm()
{
    global $xoopxDB, $camid;

    OpenTable();

    xoops_cp_header();

    OpenTable();

    echo "<form method=\"post\" action=\"modif.php\">\n";

    echo "<input type=\"hidden\" name=\"op\" value=\"delete\">\n";

    echo "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n";

    echo '<div align="center"><b>' . _CONFIRM . "</b><br><br>\n";

    echo '<input type="submit" value="' . _YES . "\">&nbsp\n";

    echo '<input type="button" value="' . _NO . "\" onclick=\"history.go(-1)\"></div>\n";

    echo "</form>\n";

    CloseTable();

    xoops_cp_footer();
}

function delete()
{
    global $xoopsDB, $camid, $catid;

    $supp = 'DELETE FROM ' . $xoopsDB->prefix('camportail') . " WHERE camid=$camid";

    $comsup = 'DELETE FROM ' . $xoopsDB->prefix('camportail_comment') . " WHERE id=$camid";

    $GLOBALS['xoopsDB']->queryF($supp);

    $GLOBALS['xoopsDB']->queryF($comsup);

    echo $GLOBALS['xoopsDB']->error();

    redirect_header('index.php', 1, _AJOUR);

    exit();
}

function edit()
{
    global $xoopsDB, $xoopsUser, $camid, $catid, $top, $souscatid;

    xoops_cp_header();

    OpenTable();

    echo '<div align="center"><h3>' . _2EDIT . "</h3></div><br>\n";
//          $sql=$GLOBALS['xoopsDB']->queryF("SELECT catid, catname FROM ".$xoopsDB->prefix("camportail_cat")." where catid=$catid");
//          $result=$GLOBALS['xoopsDB']->queryF("SELECT catid, catname FROM ".$xoopsDB->prefix("camportail_souscat")."");

    $resultat = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " where camid=$camid");

    while ($content = $GLOBALS['xoopsDB']->fetchObject($resultat)) {
        echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"4\" class=\"bg2\">\n"
    . "<tr class=\"bg3\">\n";

        echo "<td width=\"50%\">Id de la cam</td><td width=\"50%\">$content->camid</td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>catégorie</td><td>\n";

        echo "<form method=\"post\" action=\"modif.php\">\n";

        echo "<input type=\"hidden\" name=\"op\" value=\"edit\">\n";

        echo "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n";

        echo "<input type=\"hidden\" name=\"top\" value=\"oui\">\n";

        echo "<select name=\"catid\" onChange='submit()'>\n";

        $sql = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . " where catid=$catid");

        while ($cat = $GLOBALS['xoopsDB']->fetchObject($sql)) {
            echo "<option selected value=\"$cat->catid\">$cat->catname";

            echo "</option>\n";
        }

        $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . '');

        while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
            $test = (string)$ligne->catid;

            $catname = (string)$ligne->catname;

            echo "<option value=\"$test\">$catname $test</option>\n";
        }

        echo "</select>\n";

        echo "</form>\n";

        echo '</tr>'
. '<tr class="bg3">';

        //___________________

        echo '<td>sous-catégorie</td><td>';

        echo "<form method=\"post\" action=\"modif.php\">\n";

        echo "<input type=\"hidden\" name=\"op\" value=\"validedit\">\n";

        echo "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n";

        echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";

        if ($top) {
            $requete = $GLOBALS['xoopsDB']->queryF('SELECT souscatid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid' ");

            while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
                echo "<input type=\"radio\" name=\"souscatid\" value=\"$valu->souscatid\"> $valu->souscatname<br>\n";
            }
        } else {
            $requete = $GLOBALS['xoopsDB']->queryF('SELECT souscatid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE souscatid='$content->souscatid' ");

            while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
                echo "<input type=\"radio\" name=\"souscatid\" value=\"$valu->souscatid\" checked=\"#\"> $valu->souscatname <br>\n";
            }

            $requete = $GLOBALS['xoopsDB']->queryF('SELECT souscatid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid' AND souscatid!='$content->souscatid' ");

            while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
                echo "<input type=\"radio\" name=\"souscatid\" value=\"$valu->souscatid\"> $valu->souscatname<br>\n";
            }
        }

        echo "</td>\n";

        echo "</tr>\n"
    . '<tr class="bg3">';

        echo "<td>Propriétaire</td><td><input type=\"text\" name=\"userid\" value=\"$content->userid\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>pays</td> <td><input type=\"text\" name=\"pays\" value=\"$content->pays\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Nom de la cam </td><td><input type=\"camname\" name=\"camname\" value=\"$content->camname\"></td>\n";

        echo "</tr>\n"
    . '<tr class="bg3">';

        echo "<td>Description </td><td><textarea name=\"descript\" rows=\"3\" cols=\"25\">$content->descript</textarea></td>\n";

        echo "</tr>\n"
    . '<tr class="bg3">';

        echo "<td>Url </td><td><input type=\"text\" name=\"camurl\" value=\"$content->camurl\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Image </td><td><input type=\"text\" name=\"camimg\" value=\"$content->camimg\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Hits </td><td><input type=\"text\" name=\"camhits\" value=\"$content->camhits\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Vote </td><td><input type=\"text\" name=\"vote\" value=\"$content->vote\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Note </td><td><input type=\"text\" name=\"note\" value=\"$content->note\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Activée </td><td><input type=\"text\" name=\"valid\" value=\"$content->valid\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"bg3\">\n";

        echo "<td>Rompue </td><td><input type=\"text\" name=\"mort\" value=\"$content->mort\"></td>\n";

        echo "</tr>\n"
    . "<table>\n";

        echo '<br><div align="center"><input type="submit" value="' . _ENVOIE . '">&nbsp;<input type="button" value="' . _PREC . "\" onclick=\"history.go(-1)\"></div>\n";

        echo "</form>\n";
    }

    CloseTable();

    xoops_cp_footer();
}

function validedit()
{
    global $xoopsDB, $xoopsUser, $camid, $catid, $souscatid, $userid, $pays, $camname, $descript, $camurl, $camimg, $camhits, $vote, $note, $valid, $mort;

    if ($catid || $souscatid || $userid || $pays || $camname || $descript || $camurl || $camimg || $camhits || $vote || $note || $valid || $mort) {
        $camname = addslashes((string)$camname);

        $descript = addslashes((string)$descript);

        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET  catid='$catid' , souscatid='$souscatid' , userid='$userid' , pays='$pays' , camname='$camname' , descript='$descript' , camurl='$camurl' , camimg='$camimg' , camhits='$camhits' , vote='$vote' , note='$note' , valid='$valid' , mort='$mort' WHERE camid='$camid' ");

        redirect_header('index.php', 1, '' . _AJOUR . '');

        exit();
    }

    xoops_cp_header();

    OpenTable();

    echo '<div align="center"><b>' . _NOCAT . '</b><br><br><input type="submit" value="' . _PREC . "\" onclick=\"history.go(-1)\">\n";

    ClosTable();

    xoops_cp_footer();
}

switch ($op) {
    case 'confirm':
    confirm();
    break;
    case 'delete':
    delete();
    break;
    case 'validation':
    validation();
    break;
    case 'edit':
    edit();
    break;
    case 'validedit':
    validedit();
    break;
    default:
    showcam();
    break;
}
