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
if ('camportail' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    //include $xoopsConfig['root_path']."header.php";

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    //include $xoopsConfig['root_path']."header.php";
}
//OpenTable();

echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">\n"
    . "<tr>\n"
    . "<td valign=\"top\">\n";

camheader();
$themepage = $nombcat;
$temcount = 1;

//Récupération et affichage des données
        $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . ' ORDER BY catname');

echo "<table class=\"odd\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" width=\"95%\">\n"
    . "<tr align=\"top\">\n"
    . "<td colspan=\"$nombcat\" align=\"center\"><h3>" . _CATDISPO . "</h3></td>\n"
    . "</tr>\n"
    . "<tr width=\"100%\">\n";

          while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
              $catid = (string)$ligne->catid;

              $catname = '' . $ligne->catname . '';

              $catnb = $GLOBALS['xoopsDB']->queryF('SELECT count(camname) FROM ' . $xoopsDB->prefix('camportail') . " where catid = '$catid'");

              $rowcatnb = $GLOBALS['xoopsDB']->fetchRow($catnb);

              echo "<td align=\"left\" width=\"$pourcent\"><br>\n";

              echo "<img src=\"images/puce.png\" width=\"16\" height=\"16\" border=\"0\">&nbsp;<b>$catname</b> ($rowcatnb[0])<br>\n";

              $sql = $GLOBALS['xoopsDB']->queryF('select distinct souscatname, souscatid from ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid'");

              while (false !== ($req = $GLOBALS['xoopsDB']->fetchObject($sql))) {
                  $souscatid = (string)$req->souscatid;

                  $souscatnb = $GLOBALS['xoopsDB']->queryF('SELECT count(camid) FROM ' . $xoopsDB->prefix('camportail') . " WHERE souscatid='$souscatid' ");

                  $rowsouscatnb = $GLOBALS['xoopsDB']->fetchRow($souscatnb);

                  echo '<a href="show.php?op=showsouscat&souscatid=' . $souscatid . '&orderby=dateA"" title="' . _VOIR . " $req->souscatname\"><b>$req->souscatname</b></a> ($rowsouscatnb[0]) ";
              }

              echo "</td>\n";

              if ($temcount == $themepage) {
                  echo '</tr><tr>';

                  $temcount -= $themepage;
              }

              $temcount++;
          }
echo "</td>\n"
    . "</tr>\n"
    . "</table>\n";

echo "<br><br>\n";
echo "<table cellspacing=\"2\" cellpadding=\"10\" border=\"0\" align=\"center\" width=\"95%\" class=\"head\">\n"
    . "<tr>\n"
    . "<td class=\"odd\">\n";

#derniers inscrits
$nbcar = 22;
echo '<div align="center"><b>' . _LASTCAM . ' ' . _CAM . "</b></div>\n";
          $requete = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail') . " WHERE valid=1 ORDER BY date desc LIMIT $dernier ");
          while (false !== ($last = $GLOBALS['xoopsDB']->fetchObject($requete))) {
              $camid = (string)$last->camid;

              $camname = stripslashes((string)$last->camname);

              $descript = stripslashes((string)$last->descript);

              if (mb_strlen($descript) > $nbcar) {
                  $descript = mb_substr($descript, 0, $nbcar) . ' ...';
              }

              $date = DateFormat($last->date);

              echo "<a href=\"show.php?op=showcam&camid=$camid\" title=\"" . _VOIR . " $camname\"><img src=\"images/news.png\" width=\"16\" height=\"16\" border=\"0\"><b> $camname</b></a> ($last->pays) $descript ($date)<br>\n";
          }
echo '<br>';

echo '<div align="center"><a href="../../modules/contact/"><b>' . _CONTACT . "</b></a></div></td>\n"
    . "</tr>\n"
    . "</table><br><br>\n";

echo "</td>\n"
    . "<td width=\"160\" valign=\"top\" cellspacing=\"0\" cellpadding=\"0\">\n";
if (!$orderby) {
    $orderby = hitsD;
}
        $mysqlorderby = convertorderbyin($orderby);
                $orderbyTrans = convertorderbytrans($mysqlorderby);
                $linkorderby = convertorderbyout($orderby);

echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"2\" width=\"100%\" class=\"head\">\n"
    . "<tr>\n"
    . "<td class=\"odd\" width=\"11\">\n"
    . '<a href="index.php?orderby=' . hitsD . '"><img src="images/droite.png" title="' . _MD_POPULARITYMTOL . "\"></a><br><br>\n"
    . '<a href="index.php?orderby=noteD"><img src="images/droite.png" title="' . _MD_RATINGHTOL . "\"></a><br>\n"

    . "</td>\n"
    . '<td class="odd" align="center"><h3>' . _AUTOP . ' ' . $nbtop . "</font></h3>$orderbyTrans\n"
    . "</tr>\n"
    . "</table><br>\n";
        $top = 'SELECT * FROM ' . $xoopsDB->prefix('camportail') . " WHERE valid=1 ORDER BY $mysqlorderby LIMIT $nbtop";
    $result_Frecent = $GLOBALS['xoopsDB']->queryF($top);
           $toprow = $GLOBALS['xoopsDB']->fetchRow($result);
    while (false !== ($item = $GLOBALS['xoopsDB']->fetchBoth($result_Frecent))) {
        $camname = stripslashes((string)$item->camname);

        echo "<table class=\"odd\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"160\">\n"
    . "<tr>\n"
    . "<td width=\"60\"><a href=\"show.php?op=showcam&camid=$item[camid]\" title=\"" . _VOIR . " $camname\"><img src=\"imgcam/$item[camimg]\" width=\"60\" height=\"45\"></a></td>\n"
    . "<td align=\"left\" width=\"100\" valign=\"top\">&nbsp;<b>$item[camname]</b><br>&nbsp;$item[camhits] " . _VISITE . '<br>&nbsp;' . _MD_RATING . " $item[note]</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "<hr>\n";
    }

echo "</td>\n"
    . "</tr>\n"
    . "</table><br>\n";

camfooter();
//CloseTable();
include $xoopsConfig['root_path'] . 'footer.php';























