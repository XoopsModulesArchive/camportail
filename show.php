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

include('header.php');

function showsouscat()
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $souscatid, $parpage, $orderby, $orderbyTrans, $start, $xoopsLogger;

    include $xoopsConfig['root_path'] . 'header.php';

    //OpenTable();

    camheader();

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail_souscat') . " SET souscathits=souscathits+1 WHERE souscatid='$souscatid'");

    $nb = (string)$parpage; //nombre d'enregistrement par page

    if (!$start) {
        $start = 0;
    }

    $nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail') . " where souscatid='$souscatid' AND valid='1'");

    $row = $GLOBALS['xoopsDB']->fetchRow($nombre);

    $requete = $GLOBALS['xoopsDB']->queryF('SELECT catid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " where souscatid='$souscatid'");

    while ($name = $GLOBALS['xoopsDB']->fetchObject($requete)) {
        $catid = (string)$name->catid;

        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail_cat') . " SET cathits=cathits+1 WHERE catid='$catid'");

        echo "<div align=\"center\"><h3>$name->souscatname ($row[0])</h3></div><br>\n";
    }

    $numrow = $row[0];

    if ($numrow > 0) {
        if ($numrow > 1) {
            $mysqlorderby = convertorderbyin($orderby);

            $orderbyTrans = convertorderbytrans($mysqlorderby);

            $linkorderby = convertorderbyout($orderby);

            echo '<small><center>' . _MD_SORTBY . '&nbsp;&nbsp;

              ' . _MD_DATE . " (<a href='show.php?op=showsouscat&souscatid=" . $souscatid . '&orderby=' . dateA . "'><img src=\"images/asc.png\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='show.php?op=showsouscat&souscatid=$souscatid&orderby=dateD'><img src=\"images/desc.png\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              " . _MD_RATING . " (<a href='show.php?op=showsouscat&souscatid=" . $souscatid . "&orderby=noteA'><img src=\"images/asc.png\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='show.php?op=showsouscat&souscatid=$souscatid&orderby=noteD'><img src=\"images/desc.png\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              " . _MD_POPULARITY . " (<a href='show.php?op=showsouscat&souscatid=" . $souscatid . "&orderby=hitsA'><img src=\"images/asc.png\" border=\"0\" align=\"middle\" alt=\"\"></a><a href='show.php?op=showsouscat&souscatid=$souscatid&orderby=hitsD'><img src=\"images/desc.png\" border=\"0\" align=\"middle\" alt=\"\"></a>)
              	";

            echo '<b><br>';

            printf(_MD_CURSORTEDBY, (string)$orderbyTrans);

            echo '</b></center><br>';
        } else {
            $mysqlorderby = convertorderbyin($orderby);

            $orderbyTrans = convertorderbytrans($mysqlorderby);
        }
    }

    $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " where souscatid='$souscatid' AND valid='1' order by $mysqlorderby LIMIT $start,$nb ");

    while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
        $valid = (string)$ligne->valid;

        $souscat = (string)$ligne->souscatid;

        $camid = (string)$ligne->camid;

        $camname = stripslashes((string)$ligne->camname);

        $descript = stripslashes((string)$ligne->descript);

        $camurl = (string)$ligne->camurl;

        $date = DateFormat($ligne->date);

        echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" class=\"\">\n"
    . "<tr>\n"
    . "<td rowspan=\"3\" width=\"120\" align=\"center\" class=\"even\">\n";

        if (file_exists("imgcam/$ligne->camimg")) {
            echo "<a href=\"visit.php?&camid=$camid\" target=\"new\"><img src=\"imgcam/$ligne->camimg\" width=\"120\" height=\"90\"></a></td>\n";
        } else {
            echo "<a href=\"visit.php?&camid=$camid\" target=\"new\"><img src=\"imgcam/vignette.jpg\" width=\"120\" height=\"90\"></a></td>\n";
        }

        echo "<td width=\"80%\" class=\"even\">\n"
    . "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td><a href=\"visit.php?&camid=$camid\" target=\"new\"><img src=\"images/ajout.png\" width=\"16\" height=\"16\" border=\"0\"> <b>$camname</b></a> ($ligne->pays)</td>\n"
    . '<td width="25%">' . _RATING . ": $ligne->vote &nbsp; " . _MD_RATING . ": $ligne->note</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td class="even" height="50" valign="top">' . _DEPOSE . " $date<br>$descript</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td class=\"even\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td width=\"20%\">\n";

        echo '' . _KILL . "<a href=\"formlist.php?op=lienmort&camid=$camid&souscatid=$souscat\"> <img src=\"images/special04.png\" width=\"16\" height=\"16\" border=\"0\"></a>\n";

        echo "</td>\n"
    . '<td width="20%"><img src="images/loupe.png" width="16" height="16" border="0"> ' . $ligne->camhits . ' ' . _VISITE . "</td>\n"
    . "<td width=\"35%\">\n";

        echo "<form method=\"post\" action=\"formlist.php?camid=$camid\" name=\"jevote\">\n";

        echo "<input type=\"hidden\" name=\"camid\" value=\"$camid\">\n";

        echo "<input type=\"hidden\" name=\"start\" value=\"$start\">\n";

        echo "<input type=\"hidden\" name=\"soucatid\" value=\"$souscatid\">\n";

        echo "<input type=\"hidden\" name=\"orderby\" value=\"$orderby\">\n";

        echo "<select name=\"note\" onChange=\'submit()\">\n"
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
         . '<input type="submit" value="' . _VOTE . "\">\n"
         . "</form></td>\n";

        echo '
<script language="JavaScript">
function OuvrirFenetre(url,nom,details) {
 window.open(url,nom,details)}
</script>';

        $nbcat = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_comment') . " WHERE id ='$camid'");

        $rowcat = $GLOBALS['xoopsDB']->fetchRow($nbcat);

        echo "<td width=\"25%\"><a href=\"javascript:OuvrirFenetre('commentaire.php?cat=$souscatid&img=$ligne->camimg&id=$camid','popup','scrollbars=yes, width=300,height=350')\" title=\"" . _AJOUTCOMM . '">
      <img src="images/news.png" width="16" height="16" border="0"></a> ' . _COMMENT . " $rowcat[0]</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr></table>\n";

        echo '<br>';
    }

    //Boutons précédent et suivant

    $Npag = ($start + $nb) / $nb;

    echo '<div align="center"><br>';

    if ($start) {
        $orderby = convertorderbyout($orderby);

        print('<a href=show.php?op=showsouscat&start=' . ($start - $nb) . '&souscatid=' . $souscatid . '&orderby=' . $orderby . '><img src="images/gauche.png" width="11" height="11"> ' . _PREC . '</a>');

        print " [Page $Npag] ";
    }

    if ($row[0] > ($start + $nb)) {
        if (!$start) {
            echo " [Page $Npag] ";
        }

        print('<a href=show.php?op=showsouscat&start=' . ($start + $nb) . '&souscatid=' . $souscatid . '&orderby=' . $orderby . '>' . _SUIV . ' <img src="images/droite.png" width="11" height="11"></a>');
    }

    print('<br><br>');

    camfooter();

    //CloseTable();

    include $xoopsConfig['root_path'] . 'footer.php';
}

function showcam()
{
    global $xoopsDB, $xoopsConfig, $camid, $parpage, $start, $xoopsLogger;

    include $xoopsConfig['root_path'] . 'header.php';

    //OpenTable();

    camheader();

    $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " where camid=$camid");

    while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
        $requete = $GLOBALS['xoopsDB']->queryF('SELECT catname FROM ' . $xoopsDB->prefix('camportail_cat') . " where catid='$ligne->catid'");

        while ($name = $GLOBALS['xoopsDB']->fetchObject($requete)) {
            echo '<b>' . _PRINCIP . ": $name->catname</b><br>";
        }

        $sql = $GLOBALS['xoopsDB']->queryF('SELECT souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " where souscatid='$ligne->souscatid'");

        while ($name = $GLOBALS['xoopsDB']->fetchObject($sql)) {
            echo '<b>' . _SOUSCAT . ": $name->souscatname</b><br>";
        }

        echo '<br>';

        $valid = (string)$ligne->valid;

        $souscat = (string)$ligne->souscatid;

        $camid = (string)$ligne->camid;

        $camname = stripslashes((string)$ligne->camname);

        $descript = stripslashes((string)$ligne->descript);

        $camurl = (string)$ligne->camurl;

        $date = DateFormat($ligne->date);

        echo "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\" class=\"head\">\n"
    . "<tr>\n"
    . "<td rowspan=\"3\" width=\"120\" align=\"center\" class=\"even\">\n";

        if (file_exists("imgcam/$ligne->camimg")) {
            echo "<a href=\"visit.php?&camid=$camid\" target=\"new\"><img src=\"imgcam/$ligne->camimg\" width=\"120\" height=\"90\"></a></td>\n";
        } else {
            echo "<a href=\"visit.php?&camid=$camid\" target=\"new\"><img src=\"imgcam/vignette.jpg\" width=\"120\" height=\"90\"></a></td>\n";
        }

        echo "<td width=\"80%\" class=\"even\">\n"
    . "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td><a href=\"visit.php?camid=$camid\" target=\"new\"><img src=\"images/ajout.png\" width=\"16\" height=\"16\" border=\"0\"> <b>$camname</b></a> ($ligne->pays)</td>\n"
    . '<td width="25%">' . _RATING . ": $ligne->vote &nbsp; " . _MD_RATING . ": $ligne->note</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td class="even" height="50" valign="top">' . _DEPOSE . " $date<br>$descript</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td class=\"even\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n"
    . "<tr>\n"
    . "<td width=\"20%\">\n";

        echo '' . _KILL . "<a href=\"formlist.php?op=lienmort&camid=$camid&souscatid=$souscat\"> <img src=\"images/special04.png\" width=\"16\" height=\"16\" border=\"0\"></a>\n";

        echo "</td>\n"
    . '<td width="20%"><img src="images/loupe.png" width="16" height="16" border="0"> ' . $ligne->camhits . ' ' . _VISITE . "</td>\n"
    . "<td width=\"35%\">\n";

        echo "<form method=\"post\" action=\"formlist.php?camid=$camid\" name=\"jevote\">\n"
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

        echo "<td width=\"25%\"><a href=\"javascript:OuvrirFenetre('commentaire.php?cat=$souscatid&img=$ligne->camimg&id=$camid','popup','scrollbars=yes, width=300,height=350')\" title=\"" . _AJOUTCOMM . '">
      <img src="images/news.png" width="16" height="16" border="0"></a> ' . _COMMENT . " $rowcat[0]</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</td>\n"
    . "</tr></table>\n";

        echo '<br>';
    }

    camfooter();

    //CloseTable();

    include $xoopsConfig['root_path'] . 'footer.php';
}

switch ($op) {
    case 'showsouscat':
    showsouscat();
    break;
    case 'showcam':
    showcam();
    break;
}





















