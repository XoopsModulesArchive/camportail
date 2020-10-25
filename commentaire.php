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
include 'header.php';

    $currenttheme = getTheme();
    include $xoopsConfig['root_path'] . 'themes/' . $currenttheme . '/theme.php';
    if (file_exists($xoopsConfig['root_path'] . 'themes/' . $currenttheme . '/language/lang-' . $xoopsConfig['language'] . '.php')) {
        include $xoopsConfig['root_path'] . 'themes/' . $currenttheme . '/language/lang-' . $xoopsConfig['language'] . '.php';
    } elseif (file_exists($xoopsConfig['root_path'] . 'themes/' . $currenttheme . '/language/lang-english.php')) {
        include $xoopsConfig['root_path'] . 'themes/' . $currenttheme . '/language/lang-english.php';
    }
    $themecss = getcss($currenttheme);
    echo "<link rel='stylesheet' type='text/css' media='all' href='" . $xoopsConfig['xoops_url'] . "/xoops.css'>\n";
    if ($themecss) {
        echo "<style type='text/css' media='all'><!-- @import url($themecss); --></style>\n\n";
    }
echo '<title>' . _COMMENTAIRE . '</title>';

function commentaire()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $cat, $img, $id, $start, $index;

    $nb = 5; //nombre d'enregistrement par page

    if (!$start) {
        $start = 0;
    }

    //Récupération et affichage des données

    $result = $GLOBALS['xoopsDB']->queryF('SELECT * FROM ' . $xoopsDB->prefix('camportail_comment') . " where id='$id' LIMIT $start,$nb");

    $nombre = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('camportail_comment') . " where id='$id'");

    $row = $GLOBALS['xoopsDB']->fetchRow($nombre);

    if ($row[0] > 1) {
        $s = '' . _PLURIEL1 . '';

        $ent = '' . _PLURIEL2 . '';

        $x = '' . _PLURIEL3 . '';
    } else {
        $s = '';

        $ent = '';

        $x = '';
    }

    echo '<h4>' . _COMMENTAIRE . '' . $s . ": $row[0] " . _ENTRE . '' . $s . " </h4>\n";

    echo "<div align=\"center\">\n";

    $image = "imgcam/$img";

    if (file_exists((string)$image)) {
        $size = getimagesize((string)$image); //recherche du format de l'image

        echo "<img src=\"$image\" $size[3]><br>";
    } else {
        echo "<img src=\"imgcam/vignette.jpg\" width=\"120\" height=\"90\"><br>\n";
    }

    $requete = $GLOBALS['xoopsDB']->queryF('SELECT camname FROM ' . $xoopsDB->prefix('camportail') . " WHERE camid=$id ");

    while (false !== ($last = $GLOBALS['xoopsDB']->fetchObject($requete))) {
        echo "$last->camname<br>";
    }

    echo "</div>\n";

    while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
        $nom = (string)$ligne->nom;

        $email = (string)$ligne->email;

        $texte = stripslashes((string)$ligne->texte);

        $date = DateFormat($ligne->date);

        echo "<br><table width=\"260\" border=\"0\" cellspacing=\"1\" cellpadding=\"4\" align=\"center\" class=\"head\">\n"
    . "<tr>\n"
    . '<td class="outer">' . _TH_POSTEDBY . "<b> $nom</b><a href=\"mailto:$email\"> $email</a></td>\n"
    . "</tr>\n"
    . "<tr>\n"
    . "<td class=\"odd\">\n"
    . '<p>' . _TH_ON . ' ' . $date . " <br><br>$texte</p>\n"
    . "</td>\n"
    . "</tr>\n"
    . "</table>\n";
    }

    //Boutons précédent et suivant

    $Npag = ($start + $nb) / $nb;

    echo "<div align=\"center\"><br>\n";

    if ($start) {
        print('<a href=commentaire.php?start=' . ($start - $nb) . '&cat=' . $cat . '&img=' . $img . '&id=' . $id . '><img src="images/gauche.png" width="11" height="11"> ' . _PREC . '</a>');

        print " [Page $Npag] \n";
    }

    if ($row[0] > ($start + $nb)) {
        if (!$start) {
            echo " [Page $Npag] ";
        }

        print('<a href=commentaire.php?start=' . ($start + $nb) . '&cat=' . $cat . '&img=' . $img . '&id=' . $id . '>' . _SUIV . ' <img src="images/droite.png" width="11" height="11"></a>');
    }

    print('<br>');

    //Affichage des n° de pages
if ($row[0] > $nb) { // le nombre d'enreg. est > au nb de lignes d'affichage ?
for ($index = 0; ($index * $nb) < $row[0]; $index++) { // oui alors on affiche les numéros de pages
// Le lien est pour plus de clarté...
print('<a href=commentaire.php?start=' . $index * $nb . '&cat=' . $cat . '&img=' . $img . '&id=' . $id . '>');

    print($index + 1);

    print('</a>&nbsp');
}
}

    print '</div>';

    echo "<br><table width=\"260\" border=\"0\" cellspacing=\"1\" cellpadding=\"6\" align=\"center\" class=\"head\">\n"
    . "<tr>\n"
    . "<td class=\"outer\">\n";

    echo "<form method=\"post\" action=\"commentaire.php?op=ajouter\">\n";

    echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

    echo "<input type=\"hidden\" name=\"cat\" value=\"$cat\">\n";

    echo "<input type=\"hidden\" name=\"img\" value=\"$img\">\n";

    if ($xoopsUser) {
        echo "<input type=\"hidden\" name=\"uid\" value=\"$xoopsUser->uid\">\n";
    } else {
        echo "<input type=\"hidden\" name=\"uid\" value=\"0\">\n";
    }

    echo '<p align="right"><b>*' . _NOM . ":&nbsp;</b>\n";

    if ($xoopsUser) {
        $username = $xoopsUser->uname();

        echo "<input type=\"text\" name=\"nom\" value=\"$username\">&nbsp;\n";
    } else {
        echo "<input type=\"text\" name=\"nom\">&nbsp;\n";
    }

    echo '<br><b>' . _EMAIL . "&nbsp;:</b>\n"
    . "<input type=\"text\" name=\"email\">&nbsp;\n"
    . '<p align="center"><b>' . _COMMAJOUT . "</b>\n"
    . "<textarea name=\"texte\" cols=\"27\" rows=\"4\"></textarea>\n"
    . "</p>\n"
    . " <p align=\"center\">\n"
    . ' <input type="submit" name="Submit" value="' . _ENVOITURE . "\">\n"
    . "</p>\n"
    . "</form>\n"
    . "</td>\n"
    . "</tr>\n"
    . "</table>\n"
    . "</body>\n"
    . "</html>\n";

    echo "<br><div><input value='" . _CLOSE . "' type='button' onclick='javascript:window.close();'></div><br>\n";
}

function ajouter()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser, $id, $uid, $email, $nom, $texte, $cat, $img, $start;

    $userid = $xoopsUser->uid();

    $this_date = time();

    $texte = addslashes((string)$texte);

    if (!$nom || !$texte) {
        echo '<div align="center"><b>' . _ERREURCOMM . "</b><br><br>\n";

        echo '<input type="button" value="' . _PREC . "\" onclick=\"history.go(-1)\"></div>\n";
    } else {
        $GLOBALS['xoopsDB']->queryF('insert into ' . $xoopsDB->prefix('camportail_comment') . " (id, uid, email, nom, texte, date) values ('$id', '$userid', '$email', '$nom', '$texte', '$this_date')") || die('' . _ORDIE . '');

        redirect_header("commentaire.php?cat=$cat&img=$img&id=$id", 1, '' . _MERCI . '');

        exit();
    }
}

switch ($op) {
      default:
          commentaire($start, $cat, $img);
          break;
    case 'ajouter':
          ajouter();
          break;
}
