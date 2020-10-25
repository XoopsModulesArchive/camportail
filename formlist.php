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
function ajoutnote()
{
    global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsLogger, $camid, $note, $start, $souscatid, $orderby, $tempsparvote;

    if ('--' == $note) {
        redirect_header('show.php?op=showsouscat&souscatid=' . $souscatid . '&start=' . $start . '&orderby=' . $orderby . '', 1, _NONOTE);

        exit();
    }

    ///////////////////////////////////////////////////////////////////////////////////

    if (isset($camid) && '' != $camid) {
        $ip = getenv('REMOTE_ADDR');

        $tconn = $tempsparvote;

        $timee = time() + $tconn;

        if ($xoopsUser) {
            $camuser = $xoopsUser->uid();
        } else {
            $camuser = '0';
        }

        $timevire = time();

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('camportail_control') . " WHERE camid LIKE '$camid' AND ip LIKE '$ip'";   // on recherche si il y a le meme ip

        $query1 = $GLOBALS['xoopsDB']->queryF((string)$sql);

        $querydell = 'DELETE FROM ' . $xoopsDB->prefix('camportail_control') . " WHERE date < $timevire"; // on vire le ip trop vieu

        $GLOBALS['xoopsDB']->queryF($querydell);

        $num = $GLOBALS['xoopsDB']->getRowsNum($query1);

        if (0 == $num) {
            $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET vote=vote+1 WHERE camid='$camid'");

            $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET note=note+$note WHERE camid='$camid'");

            $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_control') . " (camid, mode, camuser, ip, date) values ( '$camid', '1', '$camuser', '$ip', '$timee')");   // on enregistre l'ip

            redirect_header('show.php?op=showsouscat&souscatid=' . $souscatid . '&start=' . $start . '&orderby=' . $orderby . '', 1, _2MESSAGE);

            exit();
        } elseif ($num > 0) {
            $sql = 'SELECT camname FROM ' . $xoopsDB->prefix('camportail') . " WHERE camid = '$camid'";

            $result = $GLOBALS['xoopsDB']->queryF($sql);

            while (false !== ($resultat = $GLOBALS['xoopsDB']->fetchBoth($result))) {
                $cam = $resultat[camname];
            }

            include('../../header.php');

            OpenTable();

            //camheader();

            echo "<table border=0 cellpadding=0 cellspacing=1 align=center width=100%>\n"
     . "<tr>\n"
     . "<td>\n"
     . "<table border=0 cellpadding=2 cellspacing=1 width=100%>\n"
     . "<tr>\n"
     . "<td width=100% align=\"center\"><b>Vous avez déjà voté.</b>\n"
     . "</td>\n"
     . "<tr>\n"
     . '<td><b>Bonjour,<br>' . _SYSTYOU . " <font color=\"red\">(<i>$ip</i>)</font> " . _DEJAVOTE . " <b><font color=\"red\">$cam</font></b>.
       <br>" . _NOPERM . " <b>$tempsparvote</b> " . _SEC . '.
       <br><br><div align=center>' . _COMPREH . "</div></td>\n"
     . "</tr>\n"
     . "</table>\n"
     . "</td>\n"
     . "</tr>\n"
     . '</table>';

            camfooter();

            CloseTable();

            include '../../footer.php';
        }
    }
}

function lienmort()
{
    global $xoopsDB, $camid, $souscatid, $start, $orderby;

    $GLOBALS['xoopsDB']->queryF('UPDATE ' . $xoopsDB->prefix('camportail') . " SET mort=1 WHERE camid='$camid'");

    redirect_header('index.php', 1, _3MESSAGE);

    exit();
}

switch ($op) {
    case 'ajoutnote':
    ajoutnote();
    break;
    case 'lienmort':
    lienmort($cam);
    break;
    default:
    index();
    break;
}







