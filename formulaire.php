<?php

##################################################################################
# webcam-portail Version 1.0 alpha                                               #
#                                                                                #
# Projet du 30/05/2002          dernière modification: 17/06/2002                #
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

function index()
{
    global $xoopsUser, $xoopsLogger, $xoopsDB, $xoopsConfig;

    if ($xoopsUser) {
        include $xoopsConfig['root_path'] . 'header.php';

        //OpenTable();

        camheader2();

        echo '<div align="center"><h1>' . _AJOUTER . "</h1></div>\n"
    . "<br>\n"
    . "<table width=\"100%\" cellspacing=\"2\" border=\"0\" cellpadding=\"2\" align=\"center\">\n"
    . "<tr>\n"
    . "<td width=\"40%\">&nbsp;</td>\n"
    . '<td width="20%" align="center"><b>' . _REGLEMENT . "</b></td>\n"
    . "<td width=\"40%\">&nbsp;</td>\n"
    . "</tr>\n"
    . "</table>\n";

        OpenTable();

        echo '' . _TREGLE . "\n"
    . "<ul>\n"
    . "<ol>\n"
    . '<li>' . _1REGLE . "</li>\n"
    . '<li>' . _2REGLE . "</li>\n"
    . '<li>' . _3REGLE . "</li>\n"
    . '<li>' . _4REGLE . "</li>\n"
    . "</ol>\n"
    . "</ul>\n"
    . '' . _MORALITE . "\n"
    . "<br>\n";

        echo "<br>\n";

        echo "<form method=\"POST\" action=\"formulaire.php\">\n";

        echo "<div align=\"center\"><input type=\"hidden\" name=\"go\" value=\"ajoutcam\">\n";

        echo "<input type=\"hidden\" name=\"valid\" value=\"yes\">\n";

        echo '<input type="submit" value="' . _OUI . "\">\n";

        echo '<input type="button" value="' . _NON . "\" onclick=\"history.go(-1)\"></div>\n";

        echo "</form><br>\n";

        camfooter();

        CloseTable();

        include('../../footer.php');
    } else {
        redirect_header('' . $xoopsConfig['xoops_url'] . '/user.php', 3, _MESSAGE);

        exit();
    }
}

###################################################################################

function ajoutcam()
{
    global $xoopsUser, $xoopsLogger, $xoopsDB, $xoopsConfig, $camheader, $catid, $catname, $photomax;

    if ($xoopsUser) {
        include $xoopsConfig['root_path'] . 'header.php';

        $photomax1 = $photomax / 1024;

        //OpenTable();

        camheader2();

        echo '<div align="center"><b>' . _AJOUTER . "</b></div>\n";

        echo "<br>\n";

        echo "<table align=\"center\" cellspacing=\"2\" cellpadding=\"4\" border=\"0\" width=\"90%\" class=\"head\">\n"
    . "<tr>\n"
    . "<td colspan=\"2\" class=\"even\" width=\100%\"><b>" . _COMPL . "</b></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td width="50%" class="even">' . _CHOISIRCAT . "</td>\n"
    . "<td width=\"50%\" class=\"even\">\n";

        //echo "<form method=\"post\" action=\"formulaire.php\">";

        if (!$catid) {
            echo "<form method=\"post\" name=\"test\" action=\"formulaire.php\">\n";

            echo "<input type=\"hidden\" name=\"go\" value=\"ajoutcam\">\n";

            echo "<select name=\"catid\" onChange='submit()'>\n";

            echo "<option selected value=\"$catname\"></option>\n";

            $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . '');

            while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
                echo "<option value=\"$ligne->catid\">$ligne->catname</option>\n";
            }

            echo "</select> \n";
        } else {
            echo '<form method="post" action="formulaire.php">';

            echo "<select name=\"catid\" onChange='submit()'>\n";

            $result = $GLOBALS['xoopsDB']->queryF('SELECT catname FROM ' . $xoopsDB->prefix('camportail_cat') . " WHERE catid=$catid");

            while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
                echo "<option value=\"$ligne->catid\" value2=\"$ligne->catname\">$ligne->catname</option>\n";
            }

            echo '</select><b><input type="button" value="<<<" onclick="history.go(-1)"></b>';
        }

        echo '</form>';

        echo '<form method="post" action="formulaire.php" ENCTYPE="multipart/form-data" NAME="add">';

        echo "<input type=\"hidden\" name=\"go\" value=\"Addwebcam\">\n";

        echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">\n";

        echo "</td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . '<td valign="top" class="even">' . _CHOISISOUSCAT . "</td>\n"
    . "<td class=\"even\">\n";

        $requete = $GLOBALS['xoopsDB']->queryF('SELECT souscatid, souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid' ");

        while ($valu = $GLOBALS['xoopsDB']->fetchObject($requete)) {
            echo "<input type=\"radio\" name=\"souscatid\" value=\"$valu->souscatid\"> $valu->souscatname<br>";
        }

        echo "</td>\n"
    . "</tr>\n"
    . "<tr class=\"even\">\n"
    . '<td>' . _CHOISIPAYS . "</td>\n"
    . "<td><input type=\"text\" name=\"pays\">\n";

        echo "</td>\n"
    . "</tr>\n"
    . "<tr class=\"even\">\n"
    . '<td>' . _NOMCAM . "</td>\n"
    . "<td><input type=\"text\" name=\"camname\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"even\">\n"
    . '<td>' . _DESCRIPTCAM . "</td>\n"
    . "<td><textarea name=\"descript\" rows=\"3\" cols=\"25\"></textarea></td>\n";

        echo "</tr>\n"
    . "<tr class=\"even\">\n"
    . '<td>' . _URL . "</td>\n"
    . "<td><input type=\"text\" name=\"camurl\"></td>\n";

        echo "</tr>\n"
    . "<tr class=\"even\">\n"
    . '<td>' . _IMGFILE . ' ' . _FACU . '<br> ' . _REGLEIMAGE . "</td>\n"
    . '<td><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10000000"><input type=file name="photo"> ' . _LIMIT . '';

        printf('%.2f ko', $photomax1);

        echo "</td>\n";

        echo "</tr></table>\n";

        echo '<div align="center"><br><input type="submit" name="add" value="' . _ENVOITURE . "\">&nbsp;&nbsp;</div>\n";

        echo "</form>\n";

        echo "<br><br>\n";
    }

    echo '<br>';

    camfooter();

    //CloseTable();

    include('../../footer.php');
}

####################################################################################################
function Addwebcam()
{
    global $xoopsDB, $xoopsLogger, $xoopsConfig, $xoopsUser, $Module_Name, $catid, $souscatid, $pays, $camname, $descript, $camurl, $photo, $photomax, $destination, $photo_size, $photo_name;

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
            include $xoopsConfig['root_path'] . 'header.php';

            OpenTable();

            echo '' . _CLA_FILES . " $extension_fichier " . _CLA_FILESTOP . '.';

            CloseTable();

            include $xoopsConfig['root_path'] . 'footer.php';

            exit;
        }

        if ($photo_size > $photomax) {
            $photomax1 = $photomax / 1024;

            include $xoopsConfig['root_path'] . 'header.php';

            OpenTable();

            echo '' . _CLA_YIMG . ' ' . $photo_name . ' ' . _CLA_TOBIG . ' < ';

            printf('%.2f ko', $photomax1);

            echo '<br><br><div align="center"><input type="button" value="<<<" onclick="history.go(-1)"></div>';

            CloseTable();

            include $xoopsConfig['root_path'] . 'footer.php';

            exit();
        }

        $date = time();

        $photnom = "$date-$photo_name";

        $destination = $xoopsConfig['root_path'] . "/modules/camportail/imgcam/$photnom";

        if (!copy((string)$photo, $destination)) {
            include $xoopsConfig['root_path'] . 'header.php';

            OpenTable();

            echo '' . _CLA_JOIND . '.';

            CloseTable();

            include $xoopsConfig['root_path'] . 'footer.php';

            exit;
        }
    }

    if (!$catid || !$souscatid || !$pays || !$camname || !$descript || !$camurl) {
        include('../../header.php');

        OpenTable();

        camheader();

        echo '<div align="center"><b>' . _ERREURCOMM . '</b><br><br>';

        echo '<input type="button" value="' . _PREC . '" onclick="history.go(-1)"></div>';

        CloseTable();

        include('../../footer.php');
    } else {
        $userid = $xoopsUser->uid();

        $camname = addslashes((string)$camname);

        $descript = addslashes((string)$descript);

        $this_date = time();

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " (catid, souscatid, userid, pays, camname, descript, camurl, camimg, date) values ( '$catid', '$souscatid', '$userid', '$pays', '$camname', '$descript', '$camurl', '$photnom', '$this_date')");

        redirect_header('index.php', 3, _MESSMERCI);

        exit();
    }
}

switch ($go) {
    case 'Addwebcam':
    Addwebcam();
    break;
    case 'ajoutcam':
    ajoutcam();
    break;
    default:
    index();
    break;
}
